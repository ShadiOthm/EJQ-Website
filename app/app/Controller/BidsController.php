<?php

App::uses('AppController', 'Controller');

class BidsController extends AppController {

    public function beforeFilter() {
        $this->Auth->allow(array(
            'add_to_shortlist',
            'choose',
            'chosen_contractor',
            'details',
            'remove_from_shortlist',
            'update_comments',
            'update_value',
            ));

        parent::beforeFilter();
    }
    
    public function add_to_shortlist($id=null) 
    {
        $checkedId = $this->verifyId($id);
        try {
            $this->verifyIfUserHasAccessToBidDetails($checkedId);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            exit;
        }
        
        $this->Bid->id = $checkedId; //set property id in Bid object with a value = checkedId
        $this->Bid->saveField('shortlisted', TRUE); // then call saveField method

        $this->layout = 'ajax'; // set alyout = ajax
        
    }

    public function choose($id=null)
    {
        $bidId = $this->initIdInCaseOfPost($id, 'Bid', 'id'); // set value
        $this->Bid->id = $bidId; //set value id = $bidID in Bid object
        $demandId = $this->Bid->field('demand_id');
        $tenderId = $this->Bid->field('tender_id');
        
        try {
            if (!$this->Bid->Demand->isThisUserItsConsumer($demandId, $this->uid)) {
                echo __('Você não está autorizado a realizar essa ação.');
                exit;
            }
        } catch (Exception $ex) {
            echo __('An error ocurred');
            exit;
        }

        $data = array(
            'Demand' => array(
                'id' => $demandId,
                'status' => EJQ_DEMAND_STATUS_BID_SELECTED,
                ),
            'Tender' => array(
                'id' => $tenderId,
                'demand_id' => $demandId,
                'chosen_bid_id' => $bidId,
                )
            );

        try {
            $this->Bid->Demand->saveAll($data);
            $this->Bid->saveField('status', EJQ_BID_STATUS_CHOSEN);
        } catch (Exception $ex) {
            throw $ex;
        }


        $this->Flash->success(__('The bid was chosen.'));
        return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderId, 'tab' => 'bids'));
    }

    public function chosen_contractor($id=null)
    {
        if (empty($this->uid)) {
            $this->Flash->danger(__('Please login'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }

        $tenderId = $this->initId($id, 'Tender', 'id', 'id');

        $provider = $this->Marketplace->Provider->getProviderByMarketplaceAndUserId($this->EjqMarketplaceId, $this->uid);
        if(empty($provider['Provider']['id'])) {
            $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        } else {
            $providerId = $provider['Provider']['id'];

        }
        try {
            $this->verifyIdAndAccessRightsToChosenBid($tenderId, $providerId);
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }

    }

    public function details($id=null)
    {
        $this->layout = 'ajax';
        try {
            $checkedId = $this->initId($id, 'Bid', 'id', 'id');
        } catch (Exception $ex) {
            echo __("An invalid bid's id was provided");
            exit;
        }

        try {
            $this->verifyIfUserHasAccessToBidDetails($checkedId);
        } catch (Exception $ex) {
            echo __("Você não está autorizado a realizar essa ação.");
            exit;
        }
        $this->Bid->id = $checkedId;
        $tenderId = $this->Bid->field('tender_id');
        $demandId = $this->Bid->field('demand_id');
        $bidInfo = $this->Bid->getBidInfo($checkedId);
        
        $thisIsChosenBid = false;
        $chosenBid = $this->Bid->Demand->chosenBid($demandId);
        
        if (!empty($chosenBid['Bid']['id'])) {
            if ($chosenBid['Bid']['id'] == $bidInfo['Bid']['id']) {
                $thisIsChosenBid = true;
            } 
        }
        $this->set('thisIsChosenBid', $thisIsChosenBid);
        
        if(!$this->canAccessAdm) {
            unset($bidInfo['Provider']['name']); 
        }
        
        $tenderInfo = $this->Bid->Tender->getTenderInfo($tenderId);
        $tenderConsumerId = $tenderInfo['Consumer']['id'];
        $demandId = $tenderInfo['Demand']['id'];
        $userConsumerId = $this->Bid->Demand->isThisUserItsConsumer($demandId, $this->uid);

        if(!empty($this->request->data['Bid']['contractor_alias'])) {
            $this->set('contractorAlias', urldecode($this->request->data['Bid']['contractor_alias']));
        }
        
        

        if ($userConsumerId == $tenderConsumerId) {
            $possibleActions = $this->Bid->Tender->possibleActions($tenderId, EJQ_ROLE_HOME_OWNER);
            $this->set('isDemandHomeOwner', true);               
        } else {
            if ($this->canAccessAdm) {
                $possibleActions = $this->Bid->Tender->possibleActions($tenderId, EJQ_ROLE_ADMIN);
            }
            $this->set('isDemandHomeOwner', false);
        }
        $this->set('bidsActions', $possibleActions['actions']['bids']);
        
        $this->set('tenderInfo', $tenderInfo);
        $this->set('bidInfo', $bidInfo);
    }
    
    public function remove_from_shortlist($id=null)
    {
        $checkedId = $this->verifyId($id);

        try {
            $this->verifyIfUserHasAccessToBidDetails($checkedId);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            exit;
        }
        
        $this->Bid->id = $checkedId;
        $this->Bid->saveField('shortlisted', FALSE);

        $this->layout = 'ajax';
        
        
    }

    public function update_comments($id=null)
    {
        $tenderId = $this->initIdInCaseOfPost(null, 'Tender', 'id');
        $bidId = $this->initIdInCaseOfPost($id, 'Bid', 'id');
        
        
        $provider = $this->Bid->Tender->Demand->Marketplace->Provider->getProviderByMarketplaceAndUserId($this->EjqMarketplaceId, $this->uid);
        if(empty($provider['Provider']['id'])) {
            $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        } else {
            $providerId = $provider['Provider']['id'];
        }
        try {
            $checkedId = $this->verifyAccessRightsToBidOnTender($tenderId, $providerId);
        } catch (Exception $ex) {
            $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }
        
        if (empty($bidId)) {
            $this->Flash->danger(__('There is no comments to save'));
        } else {
            $this->Bid->id = $bidId;
            $this->Bid->saveField('comments', $this->request->data['Bid']['comments']);
            $this->Flash->success(__('The comments was saved.'));
        }
        $this->redirect(array('controller' => 'tenders','action' => 'contractor_view_tender', $tenderId, 'tab' => 'bid_value'));
    }

    public function update_value($id=null)
    {
        $tenderId = $this->initIdInCaseOfPost(null, 'Tender', 'id');
        $bidId = $this->initIdInCaseOfPost($id, 'Bid', 'id');
        
        
        $provider = $this->Bid->Tender->Demand->Marketplace->Provider->getProviderByMarketplaceAndUserId($this->EjqMarketplaceId, $this->uid);
        if(empty($provider['Provider']['id'])) {
            $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        } else {
            $providerId = $provider['Provider']['id'];
        }
        try {
            $checkedId = $this->verifyAccessRightsToBidOnTender($tenderId, $providerId);
        } catch (Exception $ex) {
            $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }
        
        if (empty($bidId)) {
            $this->Flash->danger(__('There is no bid to update value'));
        } else {
            $submittedBid = $this->Bid->Tender->contractorHasSubmittedBidOnTender($tenderId, $providerId);
            if (!empty($submittedBid)) {
                $this->Flash->danger(__('You already submitted your bid on this tender.'));
            } else {
                    $this->Bid->id = $bidId;
                    $this->Bid->saveField('value', $this->request->data['Bid']['value']);
                    $this->Bid->saveField('status', EJQ_BID_STATUS_IN_PROGRESS);
                    $this->Flash->success(__('The value was saved.'));
                }
        }
        $this->redirect(array('controller' => 'tenders','action' => 'contractor_view_tender', $tenderId, 'tab' => 'bid_value'));
    }
    
    private function sendMessage($userId, $userName, $to, $subject, $message, $type)
    {
            $data = array(
                'user_id' => $userId,
                'name' => $userName,
                'email' => $to,
                'subject' => $subject,
                'message' => $message,
                'type' => $type
            );

            $this->loadmodel('SentEmail');
            $senderComponent = $this->Components->load('SendEmail');
            $sendResult = $senderComponent->send($data, $this->SentEmail);

            return $sendResult;

    }

    private function sendMessageToChosenBidContractor($providerId, $providerName, $tenderId)
    {
            $this->Bid->Provider->id = $providerId;
            $userId = $this->Bid->Provider->field('user_id');
            $this->Bid->Provider->User->id = $userId;
            $emailAddress = $this->Bid->Provider->User->field('email');
            $type = EJQ_EMAIL_TYPES_CONTRACTOR_QUALIFIED_FOR_BIDDING;
            $message = 'Hi ' . $providerName . '!

The bid you made was chosen on a job in Easy Job Quote.

Click the link below to find more info about this job:

http://%domain_prefix%.easyjobquote.com/tenders/chosen_bid/' . $tenderId . '

If it was not you who bid on Easy Job Quote, please disregard this message. Thank you.

Easy Job Quote Team
messages-notreply@easyjobquote.com';

            $sendResult = $this->sendMessage($userId,
                    $providerName,
                    $emailAddress,
                    __('Your bid was chosen on a job in EasyJobQuote'),
                    $message,
                    $type
                );

            return $sendResult;

    }
    
    private function verifyId($id) 
    {
        // if id wasn't passed as an argument, let's look at request data
        if (is_null($id)) {
            $id = $this->request->data['id'];
        }
        // check if the term and condition exists
        $checkedId = $this->initId($id, 'Bid', 'id');
        
        if (empty($checkedId)) {
            throw new InvalidArgumentException(__('Invalid Bid Id'));
        } else {
            return $checkedId;
        }
        
    }

    private function verifyIdAndAccessRightsToChosenBid($tenderId, $providerId) 
    {

        $checkedId = $this->initIdInCaseOfPost($tenderId, 'Tender', 'id');
        if (!$this->Bid->Tender->exists($checkedId)) {
            throw new NotFoundException('Invalid tender id');
        }
        try {
            $canSeeChosenBid = $this->Bid->Tender->providerCanSeeChosenBid($tenderId, $providerId);
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }

        if (!$canSeeChosenBid) {
            $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }

        return $checkedId;


    }

    private function verifyAccessRightsToBidOnTender($tenderId, $providerId)
    {
        if (!$this->Bid->Tender->exists($tenderId)) {
            throw new NotFoundException('verifyIdAndAccessRightsToBidOnTender: Invalid tender id');
        }
        try {
            $canBid = $this->Bid->Tender->providerCanBidOnTender($tenderId, $providerId, $this->EjqContractorMetaProviderId);
        } catch (Exception $ex) {
            $this->Flash->danger(__('An error ocurred'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }

        if (!$canBid) {
            $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
            $this->redirect(array('controller' => 'main', 'action' => 'index', 'redirect' => true ));
        }

        return $tenderId;
    }

    private function verifyIfUserHasAccessToBidDetails($id)
    {
        $this->Bid->id = $id;
        
        $demandId = $this->Bid->field('demand_id');
        
        $consumerId = $this->Bid->Demand->isThisUserItsConsumer($demandId, $this->uid);

        if((!$this->canAccessAdm) &&
                (empty($consumerId))) {
            throw new NotFoundException(__('Você não está autorizado a acessar essa demanda.'));
        }
        return $id;
    }

}
