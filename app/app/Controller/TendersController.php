<?php

App::uses('AppController', 'Controller');

class TendersController extends AppController {

    public function beforeFilter() {
        $this->Auth->allow(array(
            'add_image',
            'approve_tender',
            'ask_for_modifications',
            'contractor_view_tender',
            'open_yesterday',
            'open_today',
            'open_tomorrow',
            'close_yesterday',
            'close_today',
            'close_tomorrow',
            'choose_bid',
            'chosen_bid',
            'close_to_bids',
            'details',
            'disclose_chosen_bid',
            'extend_to_bids',
            'open_to_bids',
            'reopen_to_bids',
            'open_for_bid_selection',
            'report_contract_signing',
            'see_all_bids',
            'see_shortlist',
            'start',
            'submit_bid',
            'submit_to_home_owner',
            'submit_to_site_admin',
            'test_email',
            'update_description',
            ));

        parent::beforeFilter();
    }

    public function add_image($id=null)
    {
        try {
            $checkedId = $this->initIdInCaseOfPost($id, 'Tender', 'id');
            $tenderId = $this->verifyIfUserCanEditTender($checkedId);
            $tenderInfo = $this->Tender->getTenderInfo($tenderId);
            $demandId = $tenderInfo['Demand']['id'];
        } catch (Exception $ex) {
            throw $ex;
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->saveImage($tenderId)) {
                $this->Flash->success(__('The image was uploaded'));
                return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderId, 'tab' => 'images'));
            } else {
                $this->Flash->danger(__('It was not possible to upload the image. Please try again.'));
            }
        }

        $this->request->data['Demand']['id'] = $demandId;
        $this->request->data['Tender']['id'] = $tenderId;

        
    }

    public function approve_tender($id)
    {
        
        try {
            $tenderInfo = $this->verifyTender($id);
            $tenderId = $tenderInfo['Tender']['id'];
            $demandId = $tenderInfo['Demand']['id'];
            if ($this->Tender->Demand->isThisUserItsConsumer($demandId, $this->uid)) {
                $this->set('isDemandHomeOwner', true);
            } else {
                if (!$this->canAccessAdm){
                    $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
                    return $this->redirect(array('controller' => 'main', 'action' => 'index'));
                }
                $this->set('isDemandHomeOwner', false);
            }
            $role = $this->determineUserRole($this->uid, $demandId, $this->EjqEstimatorMetaProviderId);        
            $this->set('userRole', $role);
        } catch (Exception $ex) {
            throw $ex;
        }

        $this->set('hereTitle', __('Approve tender to be published'));
        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = __('Approve tender to be published?');
        $this->set('titleBox', $titleBox);

        if ($this->request->is(array('post', 'put'))) {
        
            $data = array(
                'Demand' => array(
                    'id' => $demandId,
                    'status' => EJQ_DEMAND_STATUS_TENDER_READY_TO_BIDS,
                    )
                );

            try {
                $this->Tender->Demand->save($data);
            } catch (Exception $ex) {
                $this->Flash->danger(__('It was not possible to save this approval.'));
                return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderId));
            }

            $this->Flash->success(__('The tender was approved for bidding.'));
            return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderId));
            
        }
        
        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);
        $this->set('tenderInfo', $tenderInfo);
        $this->request->data = $tenderInfo;
            
    }

    public function ask_for_modifications($id)
    {
        
        try {
            $tenderInfo = $this->verifyTender($id);
            $tenderId = $tenderInfo['Tender']['id'];
            $demandId = $tenderInfo['Demand']['id'];
            if ($this->Tender->Demand->isThisUserItsConsumer($demandId, $this->uid)) {
                $this->set('isDemandHomeOwner', true);
            } else {
                if (!$this->canAccessAdm){
                    $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
                    return $this->redirect(array('controller' => 'main', 'action' => 'index'));
                }
                $this->set('isDemandHomeOwner', false);
            }
            $role = $this->determineUserRole($this->uid, $demandId, $this->EjqEstimatorMetaProviderId);        
            $this->set('userRole', $role);
        } catch (Exception $ex) {
            throw $ex;
        }

        $this->set('hereTitle', __('Ask for modifications in the Tender'));
        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = __('Ask for modifications in the Tender?');
        $this->set('titleBox', $titleBox);

        if ($this->request->is(array('post', 'put'))) {
            
        
            try {
                $this->Tender->id = $tenderId;
                $this->Tender->saveField('home_owner_comments', $this->request->data['Tender']['home_owner_comments']);
                $this->Tender->Demand->id = $demandId;
                $this->Tender->Demand->saveField('status', EJQ_DEMAND_STATUS_TENDER_TO_BE_MODIFIED);
            } catch (Exception $ex) {
                $this->Flash->danger(__('It was not possible to save your comments.'));
                return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderId));
            }

            $this->Flash->success(__('Your comments were saved and the tender will be reviewed.'));
            return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderId));
            
        }
        
        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);
        $this->set('tenderInfo', $tenderInfo);
        $this->request->data = $tenderInfo;
            
    }

    public function contractor_view_tender($id=null, $tab=null)
    {
        $this->setActiveTab();
        $this->verifyUserId();

        $checkedId = $this->initId($id, 'Tender', 'id', 'id');

        $provider = $this->Tender->Demand->Marketplace->Provider->getProviderByMarketplaceAndUserId($this->EjqMarketplaceId, $this->uid);
        if(empty($provider['Provider']['id'])) {
            $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        } else {
            $providerId = $provider['Provider']['id'];
        }
        try {
            $checkedId = $this->verifyIdAndAccessRightsToBidOnTender($checkedId, $providerId);
        } catch (Exception $ex) {
            $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }
        
        $submittedBid = $this->Tender->contractorHasSubmittedBidOnTender($checkedId, $providerId);
        $openBid = $this->Tender->contractorHasOpenBidOnTender($checkedId, $providerId);
        $visitedTender = $this->Tender->contractorHasVisitedTender($checkedId, $providerId);
        
        if (empty($submittedBid) && empty($openBid) && empty($visitedTender)) {
            $this->createBid($checkedId, $providerId);
        }
        
        $tenderInfo = $this->Tender->getTenderInfo($checkedId);
        $demandId = $tenderInfo['Demand']['id'];
        if (!empty($tenderInfo['TermCondition'])) {

            foreach ($tenderInfo['TermCondition'] as $termConditionLoopId => $termConditionLoopData) {
                $tenderInfo['TermCondition'][$termConditionLoopId]['compliant'] = $this
                        ->Tender
                        ->TermCondition
                        ->hasContractorCompliance($termConditionLoopData['id'], $providerId);
                
                $tenderInfo['TermCondition'][$termConditionLoopId]['amendment'] = $this
                        ->Tender
                        ->TermCondition
                        ->fetchContractorAmendment($termConditionLoopData['id'], $providerId);
            }
        }
        
        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);

        $possibleActions = $this->Tender->possibleActions($checkedId, EJQ_ROLE_CONTRACTOR, $providerId);
        $this->set('rights', $possibleActions['rights']);
        
        $this->set('tenderInfo', $tenderInfo);
        $bidInfo = $this->Tender->contractorHasBidOnTender($checkedId, $providerId);
        $this->set('bidInfo', $bidInfo);

        if (!empty($bidInfo['Bid']['id'])) {
            $this->request->data['Bid']['id'] = $bidInfo['Bid']['id'];
        }
        $this->request->data['Demand']['id'] = $demandId;
        $this->request->data['Provider']['id'] = $providerId;
        $this->request->data['Tender']['id'] = $checkedId;

        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = $this->Tender->Demand->getStatusLabel($tenderInfo['Demand']['id']);
        if (!empty($submittedBid)) {
            $titleBox['h2'] .= __("(You have already submitted a bid on this tender)");
        }
        $titleBox['tenderActions'] = $possibleActions['actions']['tenders'];

        $this->set('titleBox', $titleBox);
        $this->set('hereTitle', $tenderInfo['Tender']['title']);
        $this->set('breadcrumbNode', $tenderInfo['Tender']['title']);


    }

    public function chosen_bid($id=null)
    {
        $this->setActiveTab();
        $this->verifyUserId();
        $checkedTenderId = $this->initId($id, 'Tender', 'id', 'id');

        $provider = $this->Tender->Demand->Marketplace->Provider->getProviderByMarketplaceAndUserId($this->EjqMarketplaceId, $this->uid);
        if(empty($provider['Provider']['id'])) {
            $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        } else {
            $providerId = $provider['Provider']['id'];

        }
        try {
            $checkedTenderId = $this->verifyIdAndAccessRightsToChosenBid($checkedTenderId, $providerId);
        } catch (Exception $ex) {
            $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }
//            $tenderInfo = $this->Job->Tender->getJobInfo($checkedTenderId);
        $tenderInfo = $this->Tender->getTenderInfo($checkedTenderId);
        $demandId = $tenderInfo['Demand']['id'];
        if (!empty($tenderInfo['TermCondition'])) {

            foreach ($tenderInfo['TermCondition'] as $termConditionLoopId => $termConditionLoopData) {
                $tenderInfo['TermCondition'][$termConditionLoopId]['compliant'] = $this
                        ->Tender
                        ->TermCondition
                        ->hasContractorCompliance($termConditionLoopData['id'], $providerId);
                $tenderInfo['TermCondition'][$termConditionLoopId]['amendment'] = $this
                        ->Tender
                        ->TermCondition
                        ->fetchContractorAmendment($termConditionLoopData['id'], $providerId);
            }
        }

        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);

        $possibleActions = $this->Tender->possibleActions($checkedTenderId, EJQ_ROLE_CONTRACTOR, $providerId);
        $this->set('rights', $possibleActions['rights']);
        
        $this->set('tenderInfo', $tenderInfo);
        $bidInfo = $this->Tender->contractorHasBidOnTender($checkedTenderId, $providerId);
        $this->set('bidInfo', $bidInfo);

        $this->request->data['Demand']['id'] = $demandId;
        $this->request->data['Provider']['id'] = $providerId;
        $this->request->data['Tender']['id'] = $checkedTenderId;

        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = __('Job\'s Info');
        if (!empty($submittedBid)) {
            $titleBox['h2'] .= __("(You have already submitted a bid on this tender)");
    }
        $titleBox['tenderActions'] = $possibleActions['actions']['tenders'];
    
        $this->set('titleBox', $titleBox);
        $this->set('hereTitle', $tenderInfo['Tender']['title']);
        $this->set('breadcrumbNode', $tenderInfo['Tender']['title']);



    }
    
    public function close_to_bids($id)
    {

        try {
            $tenderId = $this->verifyIdAndAdminRights($id);
            $tenderInfo = $this->Tender->getTenderInfo($tenderId);
            $demandId = $tenderInfo['Demand']['id'];
            $role = $this->determineUserRole($this->uid, $demandId, $this->EjqEstimatorMetaProviderId);        
            $this->set('userRole', $role);
        } catch (Exception $ex) {
            throw $ex;
        }
        
        $this->set('hereTitle', __('Close Tender To Bids'));
        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = __('Close Tender To Bids?');
        $this->set('titleBox', $titleBox);

        if ($this->request->is(array('post', 'put'))) {
            $data = array(
                'Demand' => array(
                    'id' => $demandId,
                    'status' => EJQ_DEMAND_STATUS_TENDER_CLOSED_TO_BIDS,
                    )
                );

            try {
                $this->Tender->Demand->save($data);
            } catch (Exception $ex) {
                throw $ex;
            }

            $this->sendMessageToHomeOwnerAboutTenderClosedForBidding(
                    $tenderInfo['Consumer']['id'],
                    $tenderInfo['Consumer']['name'],
                    $tenderId);


            $this->Flash->success(__('The tender now is closed to bids and ready for home owner bid selection.'));
            return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderId));
        }
        
        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);
        $this->set('tenderInfo', $tenderInfo);
        $this->request->data = $tenderInfo;
        






    }

    public function details($id=null)
    {
        $this->verifyUserId();
      
        try {
            $tenderInfo = $this->verifyTender($id);
            
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
            $this->redirect(array('controller' => 'main', 'action' => 'index'));

        }
        $demandId = $tenderInfo['Demand']['id'];
        $tenderId = $tenderInfo['Tender']['id'];
        
        $role = $this->determineUserRole($this->uid, $demandId, $this->EjqEstimatorMetaProviderId);
        $this->checkIfUserCanSeeTenderDetails($tenderInfo, $role);
        
        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);
        $tenderInfo['Bidder'] = $this->Tender->Demand->listQualifiedProviders($demandId, $this->EjqContractorMetaProviderId);
        foreach ($tenderInfo['Bidder'] as $bidderId => $bidderUserName) {
            $this->Tender->Demand->Provider->id = $bidderId;
            
            $contractorResult = $this->Tender->Demand->Provider->Contractor->findByProviderId($bidderId);
            $this->Tender->Demand->Provider->Contractor->id = $contractorResult['Contractor']['id'];
            $tenderInfo['Bidder'][$bidderId] = $this->Tender->Demand->Provider->Contractor->field('name');             
        }
        $bidsToBeSeen = $this->extractJustTheBidsToBeSeen($tenderInfo, $role);
        
        $tenderDevelopmentInvoice = $this->Tender->Invoice->createInvoiceIfNeeded($tenderInfo['Tender']['id'], EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT);
        $commissionInvoice = $this->Tender->Invoice->createInvoiceIfNeeded($tenderInfo['Tender']['id'], EJQ_INVOICE_TYPE_COMMISSION);
        $tenderInvoices = $this->Tender->Demand->billing($demandId, [EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT, EJQ_INVOICE_TYPE_COMMISSION]);
        $tenderInfo['Invoice'] = $tenderInvoices;
//        if(empty($tenderInvoices)) {
//            $tenderInvoices['Invoice']['payee'] = $tenderInfo['Consumer']['name'];
//            $tenderInfo['Invoice']['0'] = $tenderInvoices['Invoice'];
//        }
        if ($role == EJQ_ROLE_ADMIN && !empty($tenderInfo['Invoice'])) {
            $completeInvoices = $this->extractInvoicePayeesAndStatus($tenderInfo);
            $tenderInfo['Invoice'] = $completeInvoices;
        }

        if ($role == EJQ_ROLE_ADMIN) {
            $canSeeNames = true;
        } else {
            $canSeeNames = false;
        }
        $aliasedBids = $this->defineBiddersAliases($bidsToBeSeen, $canSeeNames, $this->Tender->Demand->Provider);
        $tenderInfo['Bid'] = $aliasedBids;
        
        $chosenBid = $this->Tender->Demand->chosenBid($demandId);
        if(!empty($chosenBid)) {
            $chosenBid['Bid']['contractor_alias'] = $chosenBid['Provider']['name'];
            $providerId = $chosenBid['Provider']['id'];
            $providerData = $this->Tender->Demand->Provider->find('first', ['conditions' => ['Provider.id' => $providerId], 'contain' => ['Contractor']]);
            if (!empty($providerData['Contractor']['0'])) {
                $chosenBid['Contractor'] = $providerData['Contractor']['0'];
            }
            if (is_null($chosenBid['Contractor']['contact_name'])) {
                $this->Tender->Demand->Provider->User->id = $providerData['Provider']['user_id'];
                $chosenBid['Contractor']['contact_name'] = $this->Tender->Demand->Provider->User->field('name');
            }
            if (is_null($chosenBid['Contractor']['contact_email'])) {
                $this->Tender->Demand->Provider->User->id = $providerData['Provider']['user_id'];
                $chosenBid['Contractor']['contact_email'] = $this->Tender->Demand->Provider->User->field('email');
            }
        }

        if(!empty($tenderInfo['Job']['date_begin_home_owner'])) {
            $rawDate = $tenderInfo['Job']['date_begin_home_owner'];
            try {
                $objDate = DateTime::createFromFormat('Y-m-d', $rawDate);
                $formatedDate = date('m/d/Y', $objDate->getTimeStamp());
            } catch (Exception $ex) {
                $formatedDate = null;
            }
            $tenderInfo['Job']['date_begin_home_owner'] = $formatedDate;
        }

        if(!empty($tenderInfo['Job']['date_begin_contractor'])) {
            $rawDate = $tenderInfo['Job']['date_begin_contractor'];
            try {
                $objDate = DateTime::createFromFormat('Y-m-d', $rawDate);
                $formatedDate = date('m/d/Y', $objDate->getTimeStamp());
            } catch (Exception $ex) {
                $formatedDate = null;
            }
            $tenderInfo['Job']['date_begin_contractor'] = $formatedDate;
        }


        
        
        $this->set("chosenBid", $chosenBid);
        $possibleActions = $this->extractPossibleActions($tenderId, $role);
        $this->setDetailsViewVariables($tenderInfo, $possibleActions);
    }

    public function disclose_chosen_bid($id)
    {

        try {
            $tenderId = $this->verifyIdAndAdminRights($id);
            $tenderInfo = $this->Tender->getTenderInfo($tenderId);
            $demandId = $tenderInfo['Demand']['id'];
//            $role = $this->determineUserRole($this->uid, $demandId, $this->EjqEstimatorMetaProviderId);        
//            $this->set('userRole', $role);
        } catch (Exception $ex) {
            throw $ex;
        }
        
        $this->set('hereTitle', __('Disclose Chosen Bid'));
        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = __('Disclose Chosen Bid?');
        $this->set('titleBox', $titleBox);
        
        

        if ($this->request->is(array('post', 'put'))) {
            $data = array(
                'Demand' => array(
                    'id' => $demandId,
                    'status' => EJQ_DEMAND_STATUS_BID_DISCLOSED,
                    )
                );

            try {
                $this->Tender->Demand->save($data);
            } catch (Exception $ex) {
                throw $ex;
            }

            try {
                $this->Tender->Bid->id = $tenderInfo['Tender']['chosen_bid_id'];
                $providerId = $this->Tender->Bid->field('provider_id');
                $this->Tender->Bid->Provider->id = $providerId;
                $name = $this->Tender->Bid->Provider->field('name');

                $this->sendMessageToChosenBidContractor(
                        $providerId, 
                        $name,
                        $tenderId
                    );

            } catch (Exception $ex) {
                echo $ex->getMessage();
                echo __("Error sending message to contractor");
                exit;
            }

            $this->Flash->success(__('The contractor was notified and disclosed to home owner.'));
            return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderId));
        }
        
        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);
        $this->set('tenderInfo', $tenderInfo);
        $this->request->data = $tenderInfo;
        
    }

    public function extend_to_bids($id)
    {
        try {
            $tenderId = $this->verifyIdAndAdminRights($id);
            $tenderInfo = $this->Tender->getTenderInfo($tenderId);
            $demandId = $tenderInfo['Demand']['id'];
            $role = $this->determineUserRole($this->uid, $demandId, $this->EjqEstimatorMetaProviderId);        
            $this->set('userRole', $role);
        } catch (Exception $ex) {
            throw $ex;
        }
        
        $this->set('hereTitle', __('Extend Tender To Bids'));
        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = __('Extend Tender To Bids?');
        $this->set('titleBox', $titleBox);

        if ($this->request->is(array('post', 'put'))) {
            
            if(!empty($this->request->data['Tender']['close_to_bids_date'])) {
                $dateToBeSaved = $this->request->data['Tender']['close_to_bids_date'];
                try {
                    $objDate = DateTime::createFromFormat('m-d-Y', $dateToBeSaved);
                    $formatedDateToBeSaved = date('Y-m-d', $objDate->getTimeStamp());
                } catch (Exception $ex) {
                    $this->Flash->danger(__('Please informa a date.'));
                    return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderId));
                }
                $data = array(
                    'Tender' => array(
                        'id' => $tenderId,
                        'close_to_bids_date' => $formatedDateToBeSaved,
                        ),
                    );

                try {
                    $this->Tender->save($data);
                } catch (Exception $ex) {
                    throw $ex;
                }

                $this->sendMessageToHomeOwnerAboutTenderExtendedForBidding(
                        $tenderInfo['Consumer']['id'],
                        $tenderInfo['Consumer']['name'],
                        $tenderId);

                $elegibleContractors = $this->Tender->Demand->listQualifiedProviders($demandId, $this->EjqContractorMetaProviderId);
                foreach ($elegibleContractors as $providerId => $providerName) {
                    $this->sendExtensionMessageToContractor($providerId, $providerName, $tenderId);
                }

                $this->Flash->success(__('The tender deadline was extended.'));
                return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderId));
            } else {
                $this->Flash->danger(__('Please inform a date.'));
            }
        }
        
        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);
        $this->set('tenderInfo', $tenderInfo);
        $this->request->data = $tenderInfo;        
    }

    public function open_for_bid_selection($id)
    {
        try {
            $checkedId = $this->verifyIdAndAdminRights($id);
            $tenderInfo = $this->Tender->getTenderInfo($checkedId);
            $tenderId = $tenderInfo['Tender']['id'];
            $demandId = $tenderInfo['Demand']['id'];
            $role = $this->determineUserRole($this->uid, $demandId, $this->EjqEstimatorMetaProviderId);        
            $this->set('userRole', $role);
        } catch (Exception $ex) {
            throw $ex;
        }
        
        $this->set('hereTitle', __('Open Tender For Bid Selection'));
        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = __('Open Tender For Bid Selection?');
        $this->set('titleBox', $titleBox);

        if ($this->request->is(array('post', 'put'))) {

            $data = array(
                'Demand' => array(
                    'id' => $demandId,
                    'status' => EJQ_DEMAND_STATUS_TENDER_OPEN_FOR_BID_SELECTION,
                    ),
                'Tender' => array(
                    'id' => $tenderId,
                    ),
                );

            try {
                $return = $this->Tender->Demand->saveAll($data);
            } catch (Exception $ex) {
                throw $ex;
            }

            $this->Flash->success(__('The home owner now can select a bid.'));

            return $this->redirect(array('controller' => 'tenders','action' => 'details', $checkedId));
        }
        
        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);
        $this->set('tenderInfo', $tenderInfo);
        $this->request->data = $tenderInfo;
        
    }

    public function open_to_bids($id)
    {
        try {
            $checkedId = $this->verifyIdAndAdminRights($id);
            $tenderInfo = $this->Tender->getTenderInfo($checkedId);
            $tenderId = $tenderInfo['Tender']['id'];
            $demandId = $tenderInfo['Demand']['id'];
            $role = $this->determineUserRole($this->uid, $demandId, $this->EjqEstimatorMetaProviderId);        
            $this->set('userRole', $role);
        } catch (Exception $ex) {
            throw $ex;
        }
        
        $this->set('hereTitle', __('Open Tender To Bids'));
        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = __('Open Tender To Bids?');
        $this->set('titleBox', $titleBox);

        if ($this->request->is(array('post', 'put'))) {
            $elegibleContractors = $this->Tender->Demand->listQualifiedProviders($demandId, $this->EjqContractorMetaProviderId);
            if(!empty($elegibleContractors)) {
                $nextMonday = strtotime('next monday');
                $nextMondayString = date('Y-m-d', $nextMonday);
                $fridayAfterNextMonday = strtotime($nextMondayString . ' + 6 days');
                $fridayAfterNextMondayString = date('Y-m-d', $fridayAfterNextMonday);

                $data = array(
                    'Demand' => array(
                        'id' => $demandId,
                        'status' => EJQ_DEMAND_STATUS_TENDER_BIDDING_SCHEDULED,
                        ),
                    'Tender' => array(
                        'id' => $tenderId,
                        'open_to_bids_date' => $nextMondayString,
                        'close_to_bids_date' => $fridayAfterNextMondayString,
                        ),
                    );

                try {
                    $return = $this->Tender->Demand->saveAll($data);
                } catch (Exception $ex) {
                    throw $ex;
                }
                
                foreach ($elegibleContractors as $providerId => $providerName) {

                    $this->sendMessageToContractorsElegibleForBidding($providerId, $providerName, $checkedId);
                }

                $this->sendMessageToHomeOwnerAboutTenderOpenForBidding(
                        $tenderInfo['Consumer']['id'],
                        $tenderInfo['Consumer']['name'],
                        $checkedId);

                $this->Flash->success(__('The tender now can receive bids and qualified contractors were notified.'));
            } else {
                $this->Flash->danger(__('There are no qualified contractors elegible for bidding.'));
            }

            return $this->redirect(array('controller' => 'tenders','action' => 'details', $checkedId));
        }
        
        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);
        $this->set('tenderInfo', $tenderInfo);
        $this->request->data = $tenderInfo;
        
    }

    public function reopen_to_bids($id)
    {
        try {
            $checkedId = $this->verifyIdAndAdminRights($id);
            $tenderInfo = $this->Tender->getTenderInfo($checkedId);
            $tenderId = $tenderInfo['Tender']['id'];
            $demandId = $tenderInfo['Demand']['id'];
            $role = $this->determineUserRole($this->uid, $demandId, $this->EjqEstimatorMetaProviderId);        
            $this->set('userRole', $role);
        } catch (Exception $ex) {
            throw $ex;
        }
        
        $this->set('hereTitle', __('Reopen Tender To Bids'));
        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = __('Reopen Tender To Bids?');
        $this->set('titleBox', $titleBox);

        if ($this->request->is(array('post', 'put'))) {
            $nextFriday = strtotime('next friday');
            $nextFridayString = date('Y-m-d', $nextFriday);

            $data = array(
                'Demand' => array(
                    'id' => $demandId,
                    'status' => EJQ_DEMAND_STATUS_TENDER_BIDDING_SCHEDULED,
                    ),
                'Tender' => array(
                    'id' => $tenderId,
                    'close_to_bids_date' => $nextFridayString,
                    ),
                );

            try {
                $return = $this->Tender->Demand->saveAll($data);
            } catch (Exception $ex) {
                throw $ex;
            }

            $this->Flash->success(__('The tender can receive bids again.'));

            return $this->redirect(array('controller' => 'tenders','action' => 'details', $checkedId));
        }
        
        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);
        $this->set('tenderInfo', $tenderInfo);
        $this->request->data = $tenderInfo;
        
    }

    public function report_contract_signing($id)
    {

        try {
            $tenderId = $this->verifyIdAndAdminRights($id);
            $tenderInfo = $this->Tender->getTenderInfo($tenderId);
            $demandId = $tenderInfo['Demand']['id'];
        } catch (Exception $ex) {
            throw $ex;
        }
        
        $this->set('hereTitle', __('Report Contract Signing'));
        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = __('Report Contract Signing?');
        $this->set('titleBox', $titleBox);
        
        

        if ($this->request->is(array('post', 'put'))) {
            $data = array(
                'Demand' => array(
                    'id' => $demandId,
                    'status' => EJQ_DEMAND_STATUS_CONTRACT_SIGNED,
                    )
                );

            try {
                $this->Tender->Demand->save($data);
            } catch (Exception $ex) {
                throw $ex;
            }

            $this->Flash->success(__('The contract signing was registered.'));
            return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderId));
        }
        
        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);
        $this->set('tenderInfo', $tenderInfo);
        $this->request->data = $tenderInfo;
        
    }

    public function see_all_bids($id= null)
    {
        $this->layout = 'ajax';
        try {
            $tenderInfo = $this->verifyTender($id);
            
        } catch (Exception $ex) {
            echo $ex->getMessage();
            exit;

        }
        $demandId = $tenderInfo['Demand']['id'];
        $tenderId = $tenderInfo['Tender']['id'];
        $role = $this->determineUserRole($this->uid, $demandId, $this->EjqEstimatorMetaProviderId);        

        try {
            $consumerId = $this->Tender->Demand->isThisUserItsConsumer($demandId, $this->uid);
            if ($consumerId) {
                $this->verifyIdAndAccessRightsToHomeOwnerTenderDetails($tenderId, $consumerId);
            } elseif (!$this->canAccessAdm) {
                echo __('Not allowed');
                exit;
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
            exit;
        }

        $bidsToBeSeen = $this->extractJustTheBidsToBeSeen($tenderInfo, $role);
        
        if ($role == EJQ_ROLE_ADMIN) {
            $canSeeNames = true;
        } else {
            $canSeeNames = false;
        }
        $aliasedBids = $this->defineBiddersAliases($bidsToBeSeen, $canSeeNames, $this->Tender->Demand->Provider);
        $tenderInfo['Bid'] = $aliasedBids;
        $this->set('tenderInfo', $tenderInfo);

        $chosenBid = $this->Tender->Demand->chosenBid($demandId);
        if(!empty($chosenBid)) {
            $chosenBid['Bid']['contractor_alias'] = $chosenBid['Provider']['name'];
            $providerId = $chosenBid['Provider']['id'];
            $providerData = $this->Tender->Demand->Provider->find('first', ['conditions' => ['Provider.id' => $providerId], 'contain' => ['Contractor']]);
            if (!empty($providerData['Contractor']['0'])) {
                $chosenBid['Contractor'] = $providerData['Contractor']['0'];
            }

            if (is_null($chosenBid['Contractor']['contact_name'])) {
                $this->Tender->Demand->Provider->User->id = $providerData['Provider']['user_id'];
                $chosenBid['Contractor']['contact_name'] = $this->Tender->Demand->Provider->User->field('name');
            }
            if (is_null($chosenBid['Contractor']['contact_email'])) {
                $this->Tender->Demand->Provider->User->id = $providerData['Provider']['user_id'];
                $chosenBid['Contractor']['contact_email'] = $this->Tender->Demand->Provider->User->field('email');
            }
        } else {
            $chosenBid = null;
            $chosenBidId = null;
        }
        $this->set('chosenBid', $chosenBid);
        $this->set('chosenBidId', $chosenBid['Bid']['id']);




        $this->set('shortlist', FALSE);
        $this->layout = 'ajax';

    }

    public function see_shortlist($id= null)
    {
        
        try {
            $tenderInfo = $this->verifyTender($id);
            
        } catch (Exception $ex) {
            echo $ex->getMessage();
            exit;

        }
        $demandId = $tenderInfo['Demand']['id'];
        $tenderId = $tenderInfo['Tender']['id'];
        $role = $this->determineUserRole($this->uid, $demandId, $this->EjqEstimatorMetaProviderId);        

        try {
            $consumerId = $this->Tender->Demand->isThisUserItsConsumer($demandId, $this->uid);
            if ($consumerId) {
                $this->verifyIdAndAccessRightsToHomeOwnerTenderDetails($tenderId, $consumerId);
            } elseif (!$this->canAccessAdm) {
                echo __('Not allowed');
                exit;
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
            exit;
        }
        $bidsToBeSeen = $this->extractJustTheBidsToBeSeen($tenderInfo, $role);
        
        if ($role == EJQ_ROLE_ADMIN) {
            $canSeeNames = true;
        } else {
            $canSeeNames = false;
        }
        $aliasedBids = $this->defineBiddersAliases($bidsToBeSeen, $canSeeNames, $this->Tender->Demand->Provider);
        $tenderInfo['Bid'] = $aliasedBids;


        $shortlistArray = array();
        foreach ($tenderInfo['Bid'] as $bidData) {
            if ($bidData['shortlisted'] === TRUE) {
                $shortlistArray[] = $bidData;
            }
        }

        $tenderInfo['Bid'] = $shortlistArray;
        $this->set('tenderInfo', $tenderInfo);
        $this->set('shortlist', TRUE);


        $this->layout = 'ajax';



    }

    public function start($id=null)
    {
        try {
            $checkedId = $this->initIdInCaseOfPost($id, 'Tender', 'id');
            $tenderId = $this->verifyIfUserCanEditTender($checkedId);
            $tenderInfo = $this->Tender->getTenderInfo($tenderId);
            $demandId = $tenderInfo['Demand']['id'];
        } catch (Exception $ex) {
            throw $ex;
        }

        $data = array(
            'Demand' => array(
                'id' => $demandId,
                'status' => EJQ_DEMAND_STATUS_TENDER_IN_PROGRESS,
                )
            );

        try {
            $this->Tender->Demand->save($data);
        } catch (Exception $ex) {
            throw $ex;
        }

        $this->Flash->success(__('Please provide details about the tender'));
        return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderId));
    }

    public function submit_bid($id=null)
    {
        
        try {
            $this->verifyUserId();
            $tenderInfo = $this->verifyTender($id);
            $tenderId = $tenderInfo['Tender']['id'];
            $demandId = $tenderInfo['Demand']['id'];
            $provider = $this->Tender->Demand->Marketplace->Provider->getProviderByMarketplaceAndUserId($this->EjqMarketplaceId, $this->uid);
            if(empty($provider['Provider']['id'])) {
                $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
                $this->redirect(array('controller' => 'main', 'action' => 'index', 'redirect' => true ));
            } else {
                $providerId = $provider['Provider']['id'];
            }
            $canBid = $this->Tender->providerCanBidOnTender($tenderId, $providerId, $this->EjqContractorMetaProviderId);
            if (!$canBid) {
                $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
                $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
            }
            $role = $this->determineUserRole($this->uid, $demandId, $this->EjqEstimatorMetaProviderId);        
            $this->set('userRole', $role);
        } catch (Exception $ex) {
            throw $ex;
        }

        $this->set('hereTitle', __('Submit bid to tender'));
        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = __('Submit bid to tender?');
        $this->set('titleBox', $titleBox);

        $submittedBid = $this->Tender->contractorHasSubmittedBidOnTender($tenderId, $providerId);
        $openBid = $this->Tender->contractorHasOpenBidOnTender($tenderId, $providerId);        
        
        if ($this->request->is(array('post', 'put'))) {
        
            if (empty($submittedBid)) {
                //  if there is not bid in progress
                if (empty($openBid)) {
                    $this->Flash->danger(__('There is no open bid to be submitted.'));
                } else {
                    if ($this->closeBid($openBid['Bid']['id'])) {
                        $this->Flash->success(__('The bid was submitted'));
                    } else {
                        $this->Flash->danger(__('It was not possible to submit the bid. Please try again or contact us.'));
                    }
                }

            } else {
                $this->Flash->danger(__('You already submitted your bid on this tender.'));
            }

            return $this->redirect(array('controller' => 'tenders','action' => 'contractor_view_tender', $tenderId));
            
        }
        
        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);
        $this->set('tenderInfo', $tenderInfo);
        $this->set('bidInfo', $openBid);
        $this->request->data = $tenderInfo;
            
    }

    public function submit_to_home_owner($id)
    {
        try {
            $postedId = $this->initIdInCaseOfPost($id, 'Tender', 'id');
            $tenderId = $this->verifyIdAndAdminRights($postedId);
            $tenderInfo = $this->Tender->getTenderInfo($tenderId);
            $demandId = $tenderInfo['Demand']['id'];
            $role = $this->determineUserRole($this->uid, $demandId, $this->EjqEstimatorMetaProviderId);        
            $this->set('userRole', $role);
        } catch (Exception $ex) {
            throw $ex;
        }

        $this->set('hereTitle', __('Submit tender to Home Owner review'));
        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = __('Submit tender to Home Owner review?');
        $this->set('titleBox', $titleBox);

        if ($this->request->is(array('post', 'put'))) {

            $data = array(
                'Demand' => array(
                    'id' => $demandId,
                    'status' => EJQ_DEMAND_STATUS_TENDER_READY_FOR_HOME_OWNER_APPROVAL,
                    )
                );

            try {
                $this->Tender->Demand->save($data);
            } catch (Exception $ex) {
                throw $ex;
            }

            try {
                $this->sendMessageToHomeOwnerAboutTenderReadyForReview(
                    $tenderInfo['Consumer']['id'],
                    $tenderInfo['Consumer']['name'],
                    $tenderId);

            } catch (Exception $ex) {
                $this->Flash->danger(__($ex->getMessage()));
                return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderId));
            }

            $this->Flash->success(__('The tender was submitted for home owner review.'));
            return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderId));
        }
        
        
        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);
        $this->set('tenderInfo', $tenderInfo);
        $this->request->data = $tenderInfo;
        
    }

    public function submit_to_site_admin($id=null)
    {
        try {
            $postedId = $this->initIdInCaseOfPost($id, 'Tender', 'id');
            $checkedId = $this->verifyIfUserHasAccessToInProgressDemandDetails($postedId);
            $this->Tender->id = $checkedId;
            $demandId = $this->Tender->field('demand_id');
            $role = $this->determineUserRole($this->uid, $demandId, $this->EjqEstimatorMetaProviderId);        
            $this->set('userRole', $role);
            $tenderInfo = $this->Tender->getTenderInfo($checkedId);
        } catch (Exception $ex) {
            $this->Flash->danger(__('You can\'t do this action.'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }
        
        $this->set('hereTitle', __('Submit tender to approval'));
        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = __('Submit tender to approval?');
        $this->set('titleBox', $titleBox);

        if ($this->request->is(array('post', 'put'))) {
            $data = array(
                'Demand' => array(
                    'id' => $demandId,
                    'status' => EJQ_DEMAND_STATUS_TENDER_READY_FOR_SITE_ADMIN_APPROVAL,
                    )
                );

            try {
                $this->Tender->Demand->save($data);
            } catch (Exception $ex) {
                throw $ex;
            }

            $this->Flash->success(__('The tender was submitted.'));
            return $this->redirect(array('controller' => 'tenders','action' => 'details', $checkedId));
        }
        
        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);
        $this->set('tenderInfo', $tenderInfo);
        $this->request->data = $tenderInfo;
        
        
    }

    public function test_email($userId=1)
    {
        
        $viewVars = [
            'tenderInfo' => [
                'Invoice' => [
                    'number' => '9999',
                    'date' => 'Mnt 34, 2017',
                    'invoice_to' => 'Fake To Text',
                    'invoice_for' => 'Fake For Text<br/>Other line<br/>completing 8787',
                    'service_description' => 'Fake service description text',
                    'service_value' => '149.00',
                    'tax_value' => '149.00',
                    'total_value' => '149.00',
                    'total_value' => '149.00',
                    'info' => 'Please make all cheques payable to Job Confidence Inc. Payment can also be made via email to
<a href="mailto:lisa@easyjobquote.com">lisa@easyjobquote.com</a>
Payment is due within 30 days.
If you have any questions concerning this invoice, contact Lisa Tinney | 250 590-8182 |
<a href="mailto:lisa@easyjobquote.com">lisa@easyjobquote.com</a>
            ',
                ],
            ],
        ];
        $this->Tender->Demand->Provider->User->id = $userId;
        $emailAddress = $this->Tender->Demand->Provider->User->field('email');
        $type = "EMAIL_TESTING";
        $message = 'Hi you! (' . $emailAddress . '

This is a test:

http://%domain_prefix%.easyjobquote.com/

If it was not you who bid on Easy Job Quote, please disregard this message. Thank you.

Easy Job Quote Team
messages-notreply@easyjobquote.com';

        //$userId, $userName, $to, $subject, $message, $type

        $data = [
                'user_id' => $userId,
                'name' => "Test Name",
                'email' => $emailAddress,
                'subject' => __('Testing email sending'),
                'message' => $message,
                'type' => $type
        ];
        
        $attachments = [
                        ['file' => WWW_ROOT . '/img/logo-email.png',
                        'mimetype' => 'image/png',
                        'contentId' => 'ejq-logo-invoice'
                        ],
            ];
        
        $sendResult = $this->sendRenderedMessage($data, 'invoice', 'default', 'both', $viewVars, $attachments);

        return $this->redirect(array('controller' => 'main','action' => 'index'));

        
    }

    public function update_description($id=null)
    {
        try {
            $checkedId = $this->initIdInCaseOfPost($id, 'Tender', 'id');
            $tenderId = $this->verifyIfUserCanEditTender($checkedId);
            $tenderInfo = $this->Tender->getTenderInfo($tenderId);
            $demandId = $tenderInfo['Demand']['id'];
        } catch (Exception $ex) {
            throw $ex;
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->saveDescription()) {
                $this->Flash->success(__('The description was updated'));
            } else {
                $this->Flash->danger(__('It was not possible to update the description. Please try again.'));
            }
        } else {
            $this->Flash->danger(__('Nothing to be updated'));
        }
        return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderId));
    }

    private function checkIfUserCanSeeTenderDetails($tenderInfo, $role)
    {
        $tenderId = $tenderInfo['Tender']['id'];
        $demandId = $tenderInfo['Demand']['id'];
        $consumerId = $tenderInfo['Consumer']['id'];
        $providerId = $tenderInfo['Provider']['id'];
        
        if ($role == EJQ_ROLE_HOME_OWNER) {
            if (!$this->Tender->homeOwnerCanSeeTenderDetails($tenderId, $consumerId)) {
                $this->Flash->info(__('The tender for your request is in progress'));
                $this->redirect(array('controller' => 'demands', 'action' => 'request_details', $demandId));
            }
        }

        if ($role == EJQ_ROLE_ESTIMATOR) {
            if (!$this->Tender->estimatorCanSeeTenderDetails($tenderId, $providerId)) {
                $this->Flash->info(__('This tender already has a chosen bid'));
                $this->redirect(array('controller' => 'providers', 'action' => 'dashboard', $providerId));
            }
        }

        if ($role == EJQ_ROLE_VISITOR) {
            $this->Flash->danger(__('Sorry. This information is not available for you. Please login with right credentials to do this operation'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }

        return true;
        
    }

    private function closeBid($id)
    {
        $this->Tender->Bid->id = $id;
        $tenderId = $this->Tender->Bid->field('tender_id');

        $result = $this->Tender->Bid->find('list', 
                ['conditions' => [
                    'status' => EJQ_BID_STATUS_SUBMITTED,
                    'tender_id' => $tenderId,
        ]]);
        
        if (empty($result)) {
            $counter = 1;
        } else {
            $counter = count($result) + 1;
        }
        $contractorAlias = __('Contractor') . " $counter";

        
        $bidData = array('Bid' => array(
                    'status' => EJQ_BID_STATUS_SUBMITTED,
                    'contractor_alias' => $contractorAlias,
                    )
            );
        $result = $this->Tender->Bid->save($bidData);
        return ($result);
    }

    private function createBid($id, $providerId)
    {
        $this->Tender->id = $id;
        $bidData = array('Bid' => array(
                    'provider_id' => $providerId,
                    'marketplace_id' => $this->Tender->field('marketplace_id'),
                    'tender_id' => $id,
                    'demand_id' => $this->Tender->field('demand_id'),
                    'status' => EJQ_BID_STATUS_VISITED,
                    )
            );
        $data = array(
            'Tender' => array(
                'id' => $id,
            ),
            'Bid' => $bidData,
        );
        $result = $this->Tender->saveAssociated($data);
        return ($result);
    }
    
    private function defineBiddersAliases($bids, $canSeeNames, Provider $Provider)
    {
        $aliased = array();
        if ($canSeeNames) {
            foreach ($bids as $bid) {
                $Provider->id = $bid['provider_id'];
                $contractorData = $Provider->Contractor->getContractorByMarketplaceAndProviderId($this->EjqMarketplaceId, $bid['provider_id']);
                $Provider->Contractor->id = $contractorData['Contractor']['id'];
                $bid['contractor_alias'] = $Provider->Contractor->field('name');
                $aliased[] = $bid;
            }
        } else {
            $counter = 1;
            foreach ($bids as $bid) {
                $this->Tender->Bid->id = $bid['id'];
                if (empty($bid['contractor_alias'])) {
                    $tenderId = $this->Tender->Bid->field('tender_id');

                    $result = $this->Tender->Bid->find('list', 
                            ['conditions' => [
                                'status' => EJQ_BID_STATUS_SUBMITTED,
                                'tender_id' => $tenderId,
                                'contractor_alias IS NOT NULL',
                    ]]);

                    if (empty($result)) {
                        $counter = 1;
                    } else {
                        $counter = count($result) + 1;
                    }
                    $contractorAlias = __('Contractor') . " $counter";
                    $this->Tender->Bid->saveField('contractor_alias', $contractorAlias);
                } else {
                    $contractorAlias = $this->Tender->Bid->field('contractor_alias');
                }
                $bid['contractor_alias'] = $contractorAlias;
                $aliased[] = $bid;
            }
        }
        return $aliased;
        
    }

    private function determineUserRole($userId, $demandId, $estimatorMetaProviderId)
    {
        $role = EJQ_ROLE_VISITOR;
        $isDemandEstimator = false;
        if ($this->Tender->Demand->isThisUserItsEstimator($demandId, $userId, $estimatorMetaProviderId)) {
            $this->set('isDemandEstimator', true);
            $role = EJQ_ROLE_ESTIMATOR;
        }
        $this->set('isDemandEstimator', $isDemandEstimator);

        if ($this->canAccessAdm) {
            $role = EJQ_ROLE_ADMIN;
        }
        
        $consumerId = $this->Tender->Demand->isThisUserItsConsumer($demandId, $userId);
        $this->set('isDemandHomeOwner', false);
        if($consumerId) {
            $this->set('isDemandHomeOwner', true);
            $role = EJQ_ROLE_HOME_OWNER;
        }

        return $role;
    }

    private function extractInvoicePayeesAndStatus($tenderInfo)
    {
        $returnData = array();
        foreach ($tenderInfo['Invoice'] as $invoice) {
            $consumerId = $invoice['Invoice']['consumer_id'];
            $this->Tender->Demand->Consumer->id = $consumerId;
            $consumerName = $this->Tender->Demand->Consumer->field('name');
            $providerId = $invoice['Invoice']['provider_id'];
            $type = $invoice['Invoice']['type'];
            $contractorName = "";
            if ($type == EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT) {
                $invoice['Invoice']['payee'] = $consumerName; 
            } elseif ($type == EJQ_INVOICE_TYPE_COMMISSION) {
                $contractorModel = $this->Tender->
                        Demand->
                        Provider->
                        Contractor;
                $contractorId = $contractorModel->
                        getContractorByMarketplaceAndProviderId($this->EjqMarketplaceId, $providerId);
                
                $contractorModel->id = $contractorId['Contractor']['id'];
                $contractorName = $contractorModel->field('name');
                $invoice['Invoice']['payee'] = $contractorName; 
                
            }
            $returnData[] = $invoice;
        }
        return ($returnData);
    }
    
    private function extractJustTheBidsToBeSeen($tenderInfo, $role)
    {
        $toBeSeen = array();
        foreach ($tenderInfo['Bid'] as $bid) {
            if ($bid['status'] == EJQ_BID_STATUS_SUBMITTED || $bid['status'] == EJQ_BID_STATUS_CHOSEN) {
                $this->Tender->Demand->Provider->id = $bid['provider_id'];
                $qualified = $this->Tender->Demand->Provider->field('qualified');
                if ($qualified) {
                    $toBeSeen[] = $bid;
                }
                
            }
        }
        return ($toBeSeen);
    }

    private function extractPossibleActions($tenderId, $role)
    {
        $possibleActions = $this->Tender->possibleActions($tenderId, $role);
        
        return $possibleActions;
        
    }

    
    private function saveDescription()
    {

        $tenderData = array('Tender' => array(
                    'id' => $this->request->data['Tender']['id'],
                    'description' => $this->request->data['Tender']['description'],
                    'demand_id' => $this->request->data['Demand']['id'],
                    )
            );

        $data = array(
            'Demand' => array(
                'id' => $this->request->data['Demand']['id'],
            ),
            'Tender' => $tenderData,
        );
        $result = $this->Tender->Demand->saveAssociated($data);
        return ($result);


    }

    private function saveImage($id)
    {
        $this->Tender->id = $id;
        $demandId = $this->Tender->field('demand_id');
        $marketplaceId = $this->Tender->Demand->field('marketplace_id');
        $providerId = $this->Tender->Demand->field('provider_id');
        
        if (!empty($this->request->data['Demand']['image']['name'])) {
            $folder = EJQ_FOLDER_TENDER_PHOTOS;
            $folder = str_replace(':id:', $id, $folder);
            
            $imageData = array('File' => array(
                        'description' => $this->request->data['Demand']['image_description'],
                        'type' => EJQ_TENDER_FILE_TYPE_IMAGE,
                        'phase' => EJQ_TENDER_FILE_PHASE_BEFORE_TENDER,
                        'path' => $folder,
                        'filename' => $this->request->data['Demand']['image']['name'],
                        'marketplace_id' => $marketplaceId,
                        'provider_id' => $providerId,
                        'demand_id' => $demandId,
                        'tender_id' => $id,
                        'image' => $this->request->data['Demand']['image'],
                    )
                );
            //$data['File'] = $imageData;
        }
        
        $resultFile = $this->Tender->File->save($imageData);
        
        return ($resultFile);


    }

    private function sendExtensionMessageToContractor($providerId, $providerName, $tenderId)
    {
            $this->Tender->Demand->Provider->id = $providerId;
            $userId = $this->Tender->Demand->Provider->field('user_id');
            $this->Tender->Demand->Provider->User->id = $userId;
            $emailAddress = $this->Tender->Demand->Provider->User->field('email');
            $type = EJQ_EMAIL_TYPES_CONTRACTOR_TENDER_EXTENDED_FOR_BIDDING;
            App::uses('CakeTime', 'Utility');
            $formatedDateToBeDisplayed = CakeTime::format($this->Tender->field('close_to_bids_date'), '%b %d, %Y');
            
            $message = 'Hi ' . $providerName . '

The Tender ' . $this->Tender->field('title') . 'will remain open for bidding until ' . $formatedDateToBeDisplayed . '.

Click the link below to bid on it and review terms and conditions:

http://%domain_prefix%.easyjobquote.com/tenders/contractor_view_tender/' . $tenderId . '

If it was not you who made the request, please disregard this message. Thank you.

Easy Job Quote Team';

            $sendResult = $this->sendMessage($userId,
                    $providerName,
                    $emailAddress,
                    __('Job extended for bidding in EasyJobQuote'),
                    $message,
                    $type
                );

            return $sendResult;

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

    private function sendRenderedMessage($data, $view, $layout, $emailFormat, $viewVars)
    {
            $this->loadmodel('SentEmail');
            $senderComponent = $this->Components->load('SendEmail');
            $sendResult = $senderComponent->sendRendered($data, $this->SentEmail, $view, $layout, $emailFormat, $viewVars);

            return $sendResult;

    }

    private function sendMessageToChosenBidContractor($providerId, $providerName, $tenderId)
    {
        $this->Tender->Demand->Provider->id = $providerId;
        $userId = $this->Tender->Demand->Provider->field('user_id');
        $this->Tender->Demand->Provider->User->id = $userId;
        $emailAddress = $this->Tender->Demand->Provider->User->field('email');
        $type = EJQ_EMAIL_TYPES_CONTRACTOR_QUALIFIED_FOR_BIDDING;


        $viewVars = [];
        $this->Tender->Demand->Provider->User->id = $userId;
        $emailAddress = $this->Tender->Demand->Provider->User->field('email');
        $message = 'Hi ' . $providerName . '!

The bid you made was chosen on a job in Easy Job Quote.

Click the link below to find the contract for this job:

<a href="http://%domain_prefix%.easyjobquote.com/tenders/chosen_bid/' . $tenderId . '">http://%domain_prefix%.easyjobquote.com/tenders/chosen_bid/' . $tenderId . '</a>

If it was not you who bid on Easy Job Quote, please disregard this message. Thank you.

Easy Job Quote Team';

        $data = [
                'user_id' => $userId,
                'name' => $providerName,
                'email' => $emailAddress,
                'subject' => __('Your bid was chosen in Easy Job Quote'),
                'message' => $message,
                'type' => $type
        ];
        
            $sendResult = $this->sendRenderedMessage($data, 'default', 'default', 'both', $viewVars);

            return $sendResult;

    }

    private function sendMessageToContractorsElegibleForBidding($providerId, $providerName, $tenderId)
    {
            $this->Tender->Demand->Provider->id = $providerId;
            $userId = $this->Tender->Demand->Provider->field('user_id');
            $this->Tender->Demand->Provider->User->id = $userId;
            $emailAddress = $this->Tender->Demand->Provider->User->field('email');
            $type = EJQ_EMAIL_TYPES_CONTRACTOR_QUALIFIED_FOR_BIDDING;

            $viewVars = [];
            $message = 'Hi ' . $providerName . '!

You are qualified to bid on a job that next Monday will be open to bids in Easy Job Quote.

When it is open, access your dashboard with the link below for bidding on it and review terms and conditions:

<a href="http://%domain_prefix%.easyjobquote.com/my_jobs">http://%domain_prefix%.easyjobquote.com/my_jobs</a>

If it was not you who made the request, please disregard this message. Thank you.

Easy Job Quote Team';

        $data = [
                'user_id' => $userId,
                'name' => $providerName,
                'email' => $emailAddress,
                'subject' => __('Job open for bidding in EasyJobQuote'),
                'message' => $message,
                'type' => $type
        ];
        
            $sendResult = $this->sendRenderedMessage($data, 'default', 'default', 'both', $viewVars);
//            $sendResult = $this->sendMessage($userId,
//                    $providerName,
//                    $emailAddress,
//                    __('Job open for bidding in EasyJobQuote'),
//                    $message,
//                    $type
//                );

            return $sendResult;

    }

    private function sendMessageToHomeOwnerAboutTenderClosedForBidding($consumerId, $consumerName, $tenderId)
    {
        $this->Tender->id = $tenderId;
        $demandId = $this->Tender->field("demand_id");
        $this->Tender->Demand->Consumer->id = $consumerId;
        $userId = $this->Tender->Demand->Consumer->field('user_id');
        $this->Tender->Demand->Consumer->User->id = $userId;
        $emailAddress = $this->Tender->Demand->Consumer->User->field('email');
        $type = EJQ_EMAIL_TYPES_HOME_OWNER_TENDER_POSTED_FOR_BIDDING;

        $viewVars = [];
        $message = 'Hi ' . $consumerName . '

Your tender is closed to bids now. You can see the bids and select your favorite.

Click the link below to see the bids list:

<a href="http://%domain_prefix%.easyjobquote.com/tenders/details/' . $tenderId . '/tab:bids">http://%domain_prefix%.easyjobquote.com/tenders/details/' . $tenderId . '/tab:bids</a>

If it was not you who made the request, please disregard this message. Thank you.

Easy Job Quote Team';

        $data = [
                'user_id' => $userId,
                'name' => $consumerName,
                'email' => $emailAddress,
                'subject' => __('Tender open for bids selection in EasyJobQuote'),
                'message' => $message,
                'type' => $type
        ];
        
        $sendResult = $this->sendRenderedMessage($data, 'default', 'default', 'both', $viewVars);
//        $sendResult = $this->sendMessage($userId,
//                $consumerName,
//                $emailAddress,
//                __('Tender open for bids selection in EasyJobQuote'),
//                $message,
//                    $type
//            );

        return $sendResult;

    }

    private function sendMessageToHomeOwnerAboutTenderExtendedForBidding($consumerId, $consumerName, $tenderId)
    {
        $this->Tender->id = $tenderId;
        $demandId = $this->Tender->field("demand_id");
        $this->Tender->Demand->Consumer->id = $consumerId;
        $userId = $this->Tender->Demand->Consumer->field('user_id');
        $this->Tender->Demand->Consumer->User->id = $userId;
        $emailAddress = $this->Tender->Demand->Consumer->User->field('email');
        $type = EJQ_EMAIL_TYPES_HOME_OWNER_TENDER_EXTENDED_FOR_BIDDING;

        App::uses('CakeTime', 'Utility');
        $formatedDateToBeDisplayed = CakeTime::format($this->Tender->field('close_to_bids_date'), '%b %d, %Y');
        $viewVars = [];
        $message = 'Hi ' . $consumerName . '

Your tender will be open to bids until' . $formatedDateToBeDisplayed . '. You will be informed when it is open for your selection.

Click the link below to see your tender details:

<a href="http://%domain_prefix%.easyjobquote.com/tenders/details/' . $tenderId . '">http://%domain_prefix%.easyjobquote.com/tenders/details/' . $tenderId . '</a>

If it was not you who made the request, please disregard this message. Thank you.

Easy Job Quote Team';

        $data = [
                'user_id' => $userId,
                'name' => $consumerName,
                'email' => $emailAddress,
                'subject' => __('Tender extended for bidding in EasyJobQuote'),
                'message' => $message,
                'type' => $type
        ];
        
        $sendResult = $this->sendRenderedMessage($data, 'default', 'default', 'both', $viewVars);
//        $sendResult = $this->sendMessage($userId,
//                $consumerName,
//                $emailAddress,
//                __('Tender extended for bidding in EasyJobQuote'),
//                $message,
//                    $type
//            );

        return $sendResult;

    }

    private function sendMessageToHomeOwnerAboutTenderOpenForBidding($consumerId, $consumerName, $tenderId)
    {
        $this->Tender->id = $tenderId;
        $demandId = $this->Tender->field("demand_id");
        $this->Tender->Demand->Consumer->id = $consumerId;
        $userId = $this->Tender->Demand->Consumer->field('user_id');
        $this->Tender->Demand->Consumer->User->id = $userId;
        $emailAddress = $this->Tender->Demand->Consumer->User->field('email');
        $type = EJQ_EMAIL_TYPES_HOME_OWNER_TENDER_POSTED_FOR_BIDDING;

        $viewVars = [];
        $message = 'Hi ' . $consumerName . '

Next Monday your tender will be open to bids. You will be informed when it is open for your selection.

Click the link below to see your tender details:

<a href="http://%domain_prefix%.easyjobquote.com/tenders/details/' . $tenderId . '">http://%domain_prefix%.easyjobquote.com/tenders/details/' . $tenderId . '</a>

If it was not you who made the request, please disregard this message. Thank you.

Easy Job Quote Team';

        $data = [
                'user_id' => $userId,
                'name' => $consumerName,
                'email' => $emailAddress,
                'subject' => __('Tender open for bidding in EasyJobQuote'),
                'message' => $message,
                'type' => $type
        ];
        
        $sendResult = $this->sendRenderedMessage($data, 'default', 'default', 'both', $viewVars);
//        $sendResult = $this->sendMessage($userId,
//                $consumerName,
//                $emailAddress,
//                __('Tender open for bidding in EasyJobQuote'),
//                $message,
//                    $type
//            );

        return $sendResult;

    }

    private function sendMessageToHomeOwnerAboutTenderReadyForReview($consumerId, $name, $tenderId)
    {
        $this->Tender->id = $tenderId;
        $demandId = $this->Tender->field("demand_id");
        $this->Tender->Demand->Consumer->id = $consumerId;
        $userId = $this->Tender->Demand->Consumer->field('user_id');
        $this->Tender->Demand->Consumer->User->id = $userId;
        $emailAddress = $this->Tender->Demand->Consumer->User->field('email');
        $type = EJQ_EMAIL_TYPES_HOME_OWNER_TENDER_READY_FOR_REVIEW;

        $viewVars = [];
        $message = 'Hi ' . $name . '!

Your tender is ready for approval in Easy Job Quote.

Click the link below to find the tender for your job:

<a href="http://%domain_prefix%.easyjobquote.com/tenders/details/' . $tenderId . '">http://%domain_prefix%.easyjobquote.com/tenders/details/' . $tenderId . '</a>

If it was not you who made this request in Easy Job Quote, please disregard this message. Thank you.

Easy Job Quote Team';

        $data = [
                'user_id' => $userId,
                'name' => $name,
                'email' => $emailAddress,
                'subject' => __('Tender ready for your approval'),
                'message' => $message,
                'type' => $type
        ];
        
        $sendResult = $this->sendRenderedMessage($data, 'default', 'default', 'both', $viewVars);
//        $sendResult = $this->sendMessage($userId,
//                $name,
//                $emailAddress,
//                __('Tender ready for your approval'),
//                $message,
//                $type
//            );

        return $sendResult;
    }

    private function setDetailsViewVariables($tenderInfo, $possibleActions)
    {
        $this->setActiveTab();
        $this->set('rights', $possibleActions['rights']);
        $this->set('tenderInfo', $tenderInfo);
        $this->request->data = $tenderInfo;
        

        $this->setHeaderInfo($tenderInfo['Tender']['title'], 
                $this->Tender->Demand->getStatusLabel($tenderInfo['Demand']['id']), 
                $possibleActions['actions']['tenders']);
        
    }
    
    private function verifyIdAndAccessRightsToBidOnTender($id, $providerId)
    {
        $checkedId = $this->initIdInCaseOfPost($id, 'Tender', 'id');
        if (!$this->Tender->exists($checkedId)) {
            throw new NotFoundException('verifyIdAndAccessRightsToBidOnTender: Invalid tender id');
        }
        try {
            $canBid = $this->Tender->providerCanBidOnTender($id, $providerId, $this->EjqContractorMetaProviderId);
        } catch (Exception $ex) {
            $this->Flash->danger(__('An error ocurred'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }

        if (!$canBid) {
            $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }

        return $checkedId;
    }

    private function verifyIdAndAccessRightsToChosenBid($id, $providerId) 
    {

        $checkedId = $this->initIdInCaseOfPost($id, 'Tender', 'id');
        if (!$this->Tender->exists($checkedId)) {
            throw new NotFoundException('verifyIdAndAccessRightsToChosenBid: Invalid tender id');
        }
        try {
            $canBid = $this->Tender->providerCanSeeChosenBid($id, $providerId);
        } catch (Exception $ex) {
            $this->Flash->danger(__('An error ocurred'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }

        if (!$canBid) {
            $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }

        return $checkedId;
    }

    private function verifyIdAndAccessRightsToHomeOwnerTenderDetails($id, $consumerId)
    {
        $checkedId = $this->initIdInCaseOfPost($id, 'Tender', 'id');
        if (!$this->Tender->exists($checkedId)) {
            throw new NotFoundException('verifyIdAndAccessRightsToHomeOwnerTenderDetails: Invalid tender id');
        }
        try {
            $canSee = $this->Tender->homeOwnerCanSeeTenderDetails($id, $consumerId);
        } catch (Exception $ex) {
            $this->Flash->danger(__('An error ocurred'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }

        if (!$canSee) {
            $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }

        return $checkedId;
    }

    private function verifyIdAndAdminRights($id)
    {

        $checkedId = $this->initIdInCaseOfPost($id, 'Tender', 'id');
        if (!$this->Tender->exists($checkedId)) {
            throw new NotFoundException('verifyIdAndAdminRights: Invalid tender id');
        }

        if ($this->canAccessAdm) {
            return $checkedId;
        } else {
            $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }

    }

    private function verifyIfUserCanEditTender($id)
    {
        $this->Tender->id = $id;
        $demandId = $this->Tender->field('demand_id');

        $this->Tender->Demand->id = $demandId;
        $providerId = $this->Tender->Demand->field('provider_id');

        $this->Tender->Demand->Provider->id = $providerId;
        $metaProviderId = $this->Tender->Demand->Provider->field('meta_provider_id');

        if((!$this->canAccessAdm) &&
                (($this->EjqProfileId != $providerId) || ($metaProviderId != $this->EjqEstimatorMetaProviderId))) {
            throw new NotFoundException(__('Você não está autorizado a acessar essa demanda.'));
        }

        return $id;


    }

    private function verifyIfUserHasAccessToInProgressDemandDetails($id)
    {
        $this->Tender->id = $id;
        $demandId = $this->Tender->field('demand_id');
        $this->Tender->Demand->id = $demandId;
        $providerId = $this->Tender->Demand->field('provider_id');
        $this->Tender->Demand->Provider->id = $providerId;
        $metaProviderId = $this->Tender->Demand->Provider->field('meta_provider_id');

        if((!$this->canAccessAdm) &&
                (($this->EjqProfileId != $providerId) || ($metaProviderId != $this->EjqEstimatorMetaProviderId))) {
            throw new NotFoundException(__('Você não está autorizado a acessar essa demanda.'));
        }

        return $id;


    }

    private function verifyTender($id)
    {
        $checkedTenderId = $this->initIdInCaseOfPost($id, 'Tender', 'id');
        if (!$this->Tender->exists($checkedTenderId)) {
            throw new InvalidArgumentException(__('verifyTender: Invalid Tender Id'));
        }
//            $tenderInfo = $this->Job->Tender->getJobInfo($checkedTenderId);
        $tenderInfo = $this->Tender->getTenderInfo($checkedTenderId);
        return $tenderInfo;
    }

    private function verifyUserId()
    {
        if (empty($this->uid)) {
            $this->Flash->danger(__('Please login'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }
    }
    
    
    public function close_yesterday($id=null)
    {
        $this->Tender->id = $id;
        $demandId = $this->Tender->field('demand_id');
        $this->Tender->Demand->id = $demandId;
        $status = $this->Tender->Demand->field('status');
        if($status == EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS) {
            $this->Tender->saveField('close_to_bids_date', date('Y-m-d',strtotime('yesterday')));
        }
        $this->Flash->success(__('The date was changed'));
        return $this->redirect(array('controller' => 'marketplaces','action' => 'dashboard', 1, 'tab' => 'open_to_bids'));
    }

    public function close_today($id=null)
    {
        $this->Tender->id = $id;
        $demandId = $this->Tender->field('demand_id');
        $this->Tender->Demand->id = $demandId;
        $status = $this->Tender->Demand->field('status');
        if($status == EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS) {
            $this->Tender->saveField('close_to_bids_date', date('Y-m-d',strtotime('today')));
        }
        $this->Flash->success(__('The date was changed'));
        return $this->redirect(array('controller' => 'marketplaces','action' => 'dashboard', 1, 'tab' => 'open_to_bids'));
    }

    public function close_tomorrow($id=null)
    {
        $this->Tender->id = $id;
        $demandId = $this->Tender->field('demand_id');
        $this->Tender->Demand->id = $demandId;
        $status = $this->Tender->Demand->field('status');
        if($status == EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS) {
            $this->Tender->saveField('close_to_bids_date', date('Y-m-d',strtotime('tomorrow')));
        }
        $this->Flash->success(__('The date was changed'));
        return $this->redirect(array('controller' => 'marketplaces','action' => 'dashboard', 1, 'tab' => 'open_to_bids'));
    }

    public function open_yesterday($id=null)
    {
        $this->Tender->id = $id;
        $demandId = $this->Tender->field('demand_id');
        $this->Tender->Demand->id = $demandId;
        $status = $this->Tender->Demand->field('status');
        if($status == EJQ_DEMAND_STATUS_TENDER_BIDDING_SCHEDULED
                || $status == EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS) {
            $this->Tender->saveField('open_to_bids_date', date('Y-m-d',strtotime('yesterday')));
            $this->Tender->Demand->saveField('status', EJQ_DEMAND_STATUS_TENDER_BIDDING_SCHEDULED);
        }
        $this->Flash->success(__('The date was changed'));
        return $this->redirect(array('controller' => 'marketplaces','action' => 'dashboard', 1, 'tab' => 'open_to_bids'));
    }

    public function open_today($id=null)
    {
        $this->Tender->id = $id;
        $demandId = $this->Tender->field('demand_id');
        $this->Tender->Demand->id = $demandId;
        $status = $this->Tender->Demand->field('status');
        if($status == EJQ_DEMAND_STATUS_TENDER_BIDDING_SCHEDULED
                || $status == EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS) {
            $this->Tender->saveField('open_to_bids_date', date('Y-m-d',strtotime('today')));
            $this->Tender->Demand->saveField('status', EJQ_DEMAND_STATUS_TENDER_BIDDING_SCHEDULED);
        }
        $this->Flash->success(__('The date was changed'));
        return $this->redirect(array('controller' => 'marketplaces','action' => 'dashboard', 1, 'tab' => 'open_to_bids'));
    }

    public function open_tomorrow($id=null)
    {
        $this->Tender->id = $id;
        $demandId = $this->Tender->field('demand_id');
        $this->Tender->Demand->id = $demandId;
        $status = $this->Tender->Demand->field('status');
        if($status == EJQ_DEMAND_STATUS_TENDER_BIDDING_SCHEDULED
                || $status == EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS) {
            $this->Tender->saveField('open_to_bids_date', date('Y-m-d',strtotime('tomorrow')));
            $this->Tender->Demand->saveField('status', EJQ_DEMAND_STATUS_TENDER_BIDDING_SCHEDULED);
        }
        $this->Flash->success(__('The date was changed'));
        return $this->redirect(array('controller' => 'marketplaces','action' => 'dashboard', 1, 'tab' => 'open_to_bids'));
    }



}
