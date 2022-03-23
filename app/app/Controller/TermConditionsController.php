<?php

App::uses('AppController', 'Controller');

class TermConditionsController extends AppController {

    public function beforeFilter() 
    {
        $this->Auth->allow(array(
            'accept',
            'amend',
            'decline',
            ));

        parent::beforeFilter();
    }

    public function accept($id=null)
    {
        try {
            $termConditionData = $this->checkIfContractorCanRespondToTermCondition($id, $this->uid);
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }

        $bid = $this->getInProgressBidOrCreateOneIfNeeded($termConditionData['Tender']['id'], $termConditionData['Provider']['id']);
        if (empty($bid)) {
            $this->Flash->info(__('This tender is closed to bids'));
        }

        //  save compliance
        try {
            $this->saveCompliance($termConditionData['TermCondition']['id'], $termConditionData['Provider']['id'], TRUE);
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
        }
        return $this->redirect(array('controller' => 'tenders','action' => 'contractor_view_tender', $termConditionData['Tender']['id'], 'tab' => 'terms'));
        
    }

    public function amend($id=null)
    {
        try {
            $termConditionData = $this->checkIfContractorCanRespondToTermCondition($id, $this->uid);
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->saveTermConditionAmendment($termConditionData['TermCondition']['id'], $termConditionData['Provider']['id'])) {
                $this->Flash->success(__('The amendment was updated'));
            } else {
                $this->Flash->danger(__('It was not possible to save the amendment. Please try again.'));
            }
        } else {
            $this->Flash->danger(__('Nothing to be updated'));
        }
        return $this->redirect(array('controller' => 'tenders','action' => 'contractor_view_tender', $termConditionData['Tender']['id'], 'tab' => 'terms'));

    }

    public function decline($id=null)
    {
        try {
            $termConditionData = $this->checkIfContractorCanRespondToTermCondition($id, $this->uid);
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }

        $bid = $this->getInProgressBidOrCreateOneIfNeeded($termConditionData['Tender']['id'], $termConditionData['Provider']['id']);

        if (empty($bid)) {
            $this->Flash->info(__('This tender is closed to bids'));
        }

        //  save compliance
        try {
            $this->saveCompliance($termConditionData['TermCondition']['id'], $termConditionData['Provider']['id'], FALSE);
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
        }
        return $this->redirect(array('controller' => 'tenders','action' => 'contractor_view_tender', $termConditionData['Tender']['id'], 'tab' => 'terms'));
    }
    
    public function checkIfContractorCanRespondToTermCondition($id, $userId)
    {
        
        
        $checkedId = $this->verifyId($id);
        $this->TermCondition->id = $checkedId;
        $demandId = $this->TermCondition->field('demand_id');
        $tenderId = $this->TermCondition->field('tender_id');
        $providerId = $this->verifyContractor($userId, $demandId);

        $data = array(
            'TermCondition' => array('id' => $checkedId,),
            'Demand' => array('id' => $demandId,),
            'Tender' => array('id' => $tenderId,),
            'Provider' => array('id' => $providerId),
        );
        return $data;
    }
    
    private function findContractorData($demandId)
    {
        $providerData = $this->TermCondition->
                Tender->
                Bid->
                Provider->
                getProviderByMarketplaceAndUserId($this->EjqMarketplaceId, $this->uid);
        
        if (empty($providerData)) {
            throw new ForbiddenException(__('Sorry, you are not allowed to do this action.')) ;
        }
        $providerId = $providerData['Provider']['id'];

        $canBid = $this->TermCondition->Tender->Demand->verifyIfContractorCanBidOnDemand($demandId, $providerId);
        if (!$canBid) {
            throw new ForbiddenException(__('Sorry, it is not possible to bid on this tender.')) ;
        }
                
        return $providerData;
    }
    
    private function getExistingComplianceOrCreateOneIfNeeded($termConditionId, $providerId)
    {
        $amendment = $this->TermCondition->contractorHasAmendment($termConditionId, $providerId);
        $this->TermCondition->id = $termConditionId;
        $tenderId = $this->TermCondition->field("tender_id");

        $bid = $this->getInProgressBidOrCreateOneIfNeeded($tenderId, $providerId);

        //  if there is not bid in progress
        if (empty($amendment)) {
            //    create bid
            $data = array('Compliance' => array(
                'term_condition_id' => $termConditionId,
                'tender_id' => $tenderId,
                'provider_id' => $providerId,
                'bid_id' => $bid['Bid']['id'],
            ));
            $amendment = $this->TermCondition->Compliance->save($data);
        }

        return ($amendment);
    }
    
    private function getInProgressBidOrCreateOneIfNeeded($tenderId, $providerId)
    {
        $submittedBid = $this->TermCondition->Tender->contractorHasSubmittedBidOnTender($tenderId, $providerId);
        $openBid = $this->TermCondition->Tender->contractorHasOpenBidOnTender($tenderId, $providerId);
        $visitedTender = $this->TermCondition->Tender->contractorHasVisitedTender($tenderId, $providerId);

        $this->TermCondition->Tender->id = $tenderId;
        $demandId = $this->TermCondition->Tender->field('demand_id');
        $bid = null;
        //if there is not bid submitted
        if (empty($submittedBid)) {
            //  if there is not bid in progress
            if (empty($openBid)) {
                if (empty($visitedTender)) {
                    //    create bid
                    $data = array('Bid' => array(
                        'tender_id' => $tenderId,
                        'provider_id' => $providerId,
                        'marketplace_id' => $this->EjqMarketplaceId,
                        'demand_id' => $demandId,
                        'status' => EJQ_BID_STATUS_IN_PROGRESS,
                    ));
                    $bid = $this->TermCondition->Tender->Bid->save($data);
                } else {
                    $this->TermCondition->Tender->Bid->id = $visitedTender['Bid']['id'];
                    $this->TermCondition->Tender->Bid->saveField('status', EJQ_BID_STATUS_IN_PROGRESS);
                    $bid = $this->TermCondition->Tender->contractorHasOpenBidOnTender($tenderId, $providerId);
                }
            } else {
                $bid = $openBid;
            }
        }
        return ($bid);
    }

    private function saveCompliance($id, $providerId, $compliant)
    {
        $complianceData = $this->getExistingComplianceOrCreateOneIfNeeded($id, $providerId);

        $newData = array('Compliance' => array(
            'id' => $complianceData['Compliance']['id'],
            'compliant' => $compliant,
        ));
        $savedCompliance = $this->TermCondition->Tender->Bid->Compliance->save($newData);

        if (empty($savedCompliance)) {
            throw new InternalErrorException(__('An error occurred when saving compliance'));
        }
        
        return $savedCompliance;
        
    }

    private function saveTermConditionAmendment($termConditionId, $providerId)
    {
        $compliance = $this->getExistingComplianceOrCreateOneIfNeeded($termConditionId, $providerId);

        $data = array('Compliance' => array(
            'id' => $compliance['Compliance']['id'],
            'amendment' => $this->request->data['Compliance']['amendment'],
        ));
        $updatedCompliance = $this->TermCondition->Tender->Bid->Compliance->save($data);

        if (empty($updatedCompliance)) {
            echo __('An error occurred when saving compliance');
            exit;
        }

        return ($updatedCompliance);

    }

    private function verifyIdAndAccessRightsToAmendTermCondition($id, $providerId) 
    {
        $checkedId = $this->initIdInCaseOfPost($id, 'TermCondition', 'id');
        if (!$this->TermCondition->exists($checkedId)) {
            throw new NotFoundException('Invalid term and condition id');
        }

        $this->TermCondition->id = $checkedId;
        $tenderId = $this->TermCondition->field("tender_id");

        if ($this->TermCondition->Tender->providerCanBidOnTender($tenderId, $providerId, $this->EjqContractorMetaProviderId)) {
            return $checkedId;
        } else {
            throw new NotFoundException('You are not allowed to do this.');
        }
    }

    private function verifyContractor($userId, $demandId)
    {
        
        //check if there is a user
        if (empty($userId)) {
            throw new UnexpectedValueException(__('Please login to do this action.')) ;
        }
     
        try {
            $provider = $this->findContractorData($demandId);
            $providerId = $provider['Provider']['id'];
        } catch (Exception $ex) {
            throw new ForbiddenException($ex->getMessage());
        }
        
        $canBid = $this->TermCondition->Tender->Demand->verifyIfContractorCanBidOnDemand($demandId, $providerId);
        if (!$canBid) {
            throw new ForbiddenException(__('Sorry, it is not possible to bid on this tender.')) ;
        }
        
        return $providerId;
    }
    
    private function verifyId($id) 
    {
        // check if the term and condition exists
        $checkedId = $this->initId($id, 'TermCondition', 'id');
        if (empty($checkedId)) {
            throw new InvalidArgumentException(__('Invalid Term Condition Id'));
        } else {
            return $checkedId;
        }
    }
}
