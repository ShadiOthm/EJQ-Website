<?php

App::uses('AppController', 'Controller');

class JobsController extends AppController {

    public function beforeFilter() {
        $this->Auth->allow(array(
            'contractor_report_begin',
            'contractor_report_end',
            'home_owner_report_begin',
            'home_owner_report_end',
            ));

        parent::beforeFilter();
    }
    
    public function contractor_report_begin($id=null)
    {
        $provider = $this->Job->Marketplace->Provider->getProviderByMarketplaceAndUserId($this->EjqMarketplaceId, $this->uid);
        if(empty($provider['Provider']['id'])) {
            $this->Flash->danger(__('Only the Contractor can do  this action'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        } else {
            $providerId = $provider['Provider']['id'];
        }
        try {
            $checkedTenderId = $this->initIdInCaseOfPost($id, 'Tender', 'id');
            $this->verifyIdAndAccessRightsToChosenBid($checkedTenderId, $providerId);
            $this->set('userRole', EJQ_ROLE_CONTRACTOR);
//            $tenderInfo = $this->Job->Tender->getJobInfo($checkedTenderId);
            $tenderInfo = $this->Job->Tender->getTenderInfo($checkedTenderId);
            $tenderId = $tenderInfo['Tender']['id'];
        } catch (Exception $ex) {
            throw $ex;
        }
        
        $this->set('isDemandEstimator', false);
        $this->set('hereTitle', __('Report job\'s begin'));
        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = __('The job is proceeding?');
        $this->set('titleBox', $titleBox);

        if ($this->request->is(array('post', 'put'))) {
            if ($this->saveContractorTimeBegin($tenderInfo)) {
                
                if (!empty($tenderInfo['Job']['date_begin_home_owner'])) {
                    $this->Job->Demand->id = $tenderInfo['Demand']['id'];
                    $this->Job->Demand->saveField('status', EJQ_DEMAND_STATUS_JOB_IN_PROGRESS);
                    $this->Job->id = $tenderInfo['Job']['id'];
                    $this->Job->saveField('status', EJQ_JOB_STATUS_IN_PROGRESS);
                }
                
                $this->Flash->success(__('The job\'s beginning was registered'));
                return $this->redirect(array('controller' => 'tenders','action' => 'chosen_bid', $tenderId));
            } else {
                $this->Flash->danger(__('It was not possible to report beginning. Please try again.'));
            }
        }


        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);
        $this->set('tenderInfo', $tenderInfo);
        $this->request->data = $tenderInfo;
    }
    
    public function contractor_report_end($id=null)
    {
        $provider = $this->Job->Marketplace->Provider->getProviderByMarketplaceAndUserId($this->EjqMarketplaceId, $this->uid);
        if(empty($provider['Provider']['id'])) {
            $this->Flash->danger(__('Only the Contractor can do this action'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        } else {
            $providerId = $provider['Provider']['id'];
        }
        try {
            $checkedTenderId = $this->initIdInCaseOfPost($id, 'Tender', 'id');
            $this->verifyIdAndAccessRightsToChosenBid($checkedTenderId, $providerId);
            $this->set('userRole', EJQ_ROLE_CONTRACTOR);
//            $tenderInfo = $this->Job->Tender->getJobInfo($checkedId);
            $tenderInfo = $this->Job->Tender->getTenderInfo($checkedTenderId);
            $tenderId = $tenderInfo['Tender']['id'];
        } catch (Exception $ex) {
            throw $ex;
        }
        
        $this->set('isDemandEstimator', false);
        $this->set('hereTitle', __('Report job end'));
        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = __('The job was completed?');
        $this->set('titleBox', $titleBox);

        if ($this->request->is(array('post', 'put'))) {
            if ($this->saveContractorTimeEnd($tenderInfo)) {
                
                if (!empty($tenderInfo['Job']['date_end_home_owner'])) {
                    $this->Job->Demand->id = $tenderInfo['Demand']['id'];
                    $this->Job->Demand->saveField('status', EJQ_DEMAND_STATUS_JOB_COMPLETED);
                    $this->Job->id = $tenderInfo['Job']['id'];
                    $this->Job->saveField('status', EJQ_JOB_STATUS_COMPLETED);
                }
                
                $this->Flash->success(__('The job completion was registered'));
                return $this->redirect(array('controller' => 'tenders','action' => 'chosen_bid', $tenderId));
            } else {
                $this->Flash->danger(__('It was not possible to report completion. Please try again.'));
            }
        }


        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);
        $this->set('tenderInfo', $tenderInfo);
        $this->request->data = $tenderInfo;
    }
    
    public function home_owner_report_begin($id=null)
    {
        $consumer = $this->Job->Marketplace->Consumer->getConsumerByMarketplaceAndUserId($this->EjqMarketplaceId, $this->uid);
        if(empty($consumer['Consumer']['id'])) {
            $this->Flash->danger(__('Only the Home Owner can do  this action'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        } else {
            $consumerId = $consumer['Consumer']['id'];

        }
        try {
            $checkedTenderId = $this->initIdInCaseOfPost($id, 'Tender', 'id');
            $this->verifyIdAndAccessRightsToHomeOwnerTenderDetails($checkedTenderId, $consumerId);
            $this->set('userRole', EJQ_ROLE_HOME_OWNER);
//            $tenderInfo = $this->Job->Tender->getJobInfo($checkedTenderId);
            $tenderInfo = $this->Job->Tender->getTenderInfo($checkedTenderId);
            $tenderId = $tenderInfo['Tender']['id'];
        } catch (Exception $ex) {
            throw $ex;
        }
        
        $this->set('isDemandEstimator', false);
        $this->set('hereTitle', __('Report job\'s begin'));
        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = __('The job is proceeding?');
        $this->set('titleBox', $titleBox);

        if ($this->request->is(array('post', 'put'))) {
            if ($this->saveHomeOwnerTimeBegin($tenderInfo)) {
                
                if (!empty($tenderInfo['Job']['date_begin_contractor'])) {
                    $this->Job->Demand->id = $tenderInfo['Demand']['id'];
                    $this->Job->Demand->saveField('status', EJQ_DEMAND_STATUS_JOB_IN_PROGRESS);
                    $this->Job->id = $tenderInfo['Job']['id'];
                    $this->Job->saveField('status', EJQ_JOB_STATUS_IN_PROGRESS);
                }
                
                $this->Flash->success(__('The job\'s beginning was registered'));
                return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderId));
            } else {
                $this->Flash->danger(__('It was not possible to report beginning. Please try again.'));
            }
        }


        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);
        $this->set('tenderInfo', $tenderInfo);
        $this->request->data = $tenderInfo;
   }
    
    public function home_owner_report_end($id=null)
    {
        $consumer = $this->Job->Marketplace->Consumer->getConsumerByMarketplaceAndUserId($this->EjqMarketplaceId, $this->uid);
        if(empty($consumer['Consumer']['id'])) {
            $this->Flash->danger(__('Only the Home Owner can do  this action'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        } else {
            $consumerId = $consumer['Consumer']['id'];

        }
        try {
            $checkedTenderId = $this->initIdInCaseOfPost($id, 'Tender', 'id');
            $this->verifyIdAndAccessRightsToHomeOwnerTenderDetails($checkedTenderId, $consumerId);
            $this->set('userRole', EJQ_ROLE_HOME_OWNER);
//            $tenderInfo = $this->Job->Tender->getJobInfo($checkedTenderId);
            $tenderInfo = $this->Job->Tender->getTenderInfo($checkedTenderId);
            $tenderId = $tenderInfo['Tender']['id'];
        } catch (Exception $ex) {
            throw $ex;
        }
        
        $this->set('isDemandEstimator', false);
        $this->set('hereTitle', __('Report job completion'));
        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = __('The job is proceeding?');
        $this->set('titleBox', $titleBox);

        if ($this->request->is(array('post', 'put'))) {
            if ($this->saveHomeOwnerTimeEnd($tenderInfo)) {
                
                if (!empty($tenderInfo['Job']['date_end_contractor'])) {
                    $this->Job->Demand->id = $tenderInfo['Demand']['id'];
                    $this->Job->Demand->saveField('status', EJQ_DEMAND_STATUS_JOB_COMPLETED);
                    $this->Job->id = $tenderInfo['Job']['id'];
                    $this->Job->saveField('status', EJQ_JOB_STATUS_COMPLETED);
                    $commissionInvoice = $this->Job->Tender->Invoice->createInvoiceIfNeeded($tenderInfo['Tender']['id'], EJQ_INVOICE_TYPE_COMMISSION);
                }
                
                $this->Flash->success(__('The job completion was registered'));
                return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderId));
            } else {
                $this->Flash->danger(__('It was not possible to report completion. Please try again.'));
            }
        }


        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);
        $this->set('tenderInfo', $tenderInfo);
        $this->request->data = $tenderInfo;
   }
    
//    private function extractServiceListDescription($tenderInfo) 
//    {
//        $servicesListDescription = "";
//        if (!empty($tenderInfo['ServiceType'])) {
//            $sep = "";
//            foreach ($tenderInfo['ServiceType'] as $stKey => $serviceData ){
//                $servicesListDescription .= $sep . $serviceData['name'];
//                $sep = ", ";
//            }
//        }
//        return $servicesListDescription;
//    }
    
    private function saveContractorTimeEnd($tenderInfo)
    {
        $jobData = $this->request->data['Job'];
        try {
            $obj_date = DateTime::createFromFormat('m-d-Y', $jobData['date_end_contractor']);
            if ($obj_date !== FALSE) {
                $formatedDate = date('Y-m-d', $obj_date->getTimeStamp());
            } else {
                return null;
            }
        } catch (Exception $ex) {
            return null;
        }

        if (empty($tenderInfo['Job'])) {
            $chosenBidId = $tenderInfo['Tender']['chosen_bid_id'];
            $this->Job->Tender->Bid->id = $chosenBidId;
            $providerId = $this->Job->Tender->Bid->field('provider_id');
            $jobData = ['Job' => [
                'consumer_id' => $tenderInfo['Consumer']['id'],
                'provider_id' => $providerId,
                'marketplace_id' => $tenderInfo['Demand']['marketplace_id'],
                'demand_id' => $tenderInfo['Demand']['id'],
                'tender_id' => $this->request->data['Tender']['id'],
                'bid_id' => $chosenBidId,
                'date_end_contractor'=> $formatedDate,
                ],
            ];
            $result = $this->Job->save($jobData);
        } else {
            $this->Job->id = $tenderInfo['Job']['id'];
            $result = $this->Job->saveField('date_end_contractor', $formatedDate);
        }
        return $result;
        
    }

    private function saveContractorTimeBegin($tenderInfo)
    {
        $jobData = $this->request->data['Job'];
        try {
            $obj_date = DateTime::createFromFormat('m-d-Y', $jobData['date_begin_contractor']);
            if ($obj_date !== FALSE) {
                $formatedDate = date('Y-m-d', $obj_date->getTimeStamp());
            } else {
                return null;
            }
        } catch (Exception $ex) {
            return null;
        }

        if (empty($tenderInfo['Job'])) {
            $chosenBidId = $tenderInfo['Tender']['chosen_bid_id'];
            $this->Job->Tender->Bid->id = $chosenBidId;
            $providerId = $this->Job->Tender->Bid->field('provider_id');
            $jobData = ['Job' => [
                'consumer_id' => $tenderInfo['Consumer']['id'],
                'provider_id' => $providerId,
                'marketplace_id' => $tenderInfo['Demand']['marketplace_id'],
                'demand_id' => $tenderInfo['Demand']['id'],
                'tender_id' => $this->request->data['Tender']['id'],
                'bid_id' => $chosenBidId,
                'date_begin_contractor'=> $formatedDate,
                ],
            ];
            $result = $this->Job->save($jobData);
        } else {
            $this->Job->id = $tenderInfo['Job']['id'];
            $result = $this->Job->saveField('date_begin_contractor', $formatedDate);
        }
        return $result;
        
    }

    private function saveHomeOwnerTimeEnd($tenderInfo)
    {
        $jobData = $this->request->data['Job'];
        try {
            $obj_date = DateTime::createFromFormat('m-d-Y', $jobData['date_end_home_owner']);
            if ($obj_date !== FALSE) {
                $formatedDate = date('Y-m-d', $obj_date->getTimeStamp());
            } else {
                return null;
            }
        } catch (Exception $ex) {
            return null;
        }

        if (empty($tenderInfo['Job'])) {
            $chosenBidId = $tenderInfo['Tender']['chosen_bid_id'];
            $this->Job->Tender->Bid->id = $chosenBidId;
            $providerId = $this->Job->Tender->Bid->field('provider_id');
            $jobData = ['Job' => [
                'consumer_id' => $tenderInfo['Consumer']['id'],
                'provider_id' => $providerId,
                'marketplace_id' => $tenderInfo['Demand']['marketplace_id'],
                'demand_id' => $tenderInfo['Demand']['id'],
                'tender_id' => $this->request->data['Tender']['id'],
                'bid_id' => $chosenBidId,
                'date_end_home_owner'=> $formatedDate,
                ],
            ];
            $result = $this->Job->save($jobData);
        } else {
            $this->Job->id = $tenderInfo['Job']['id'];
            $result = $this->Job->saveField('date_end_home_owner', $formatedDate);
        }
        return $result;
        
    }

    private function saveHomeOwnerTimeBegin($tenderInfo)
    {
        $jobData = $this->request->data['Job'];
        try {
            $obj_date = DateTime::createFromFormat('m-d-Y', $jobData['date_begin_home_owner']);
            if ($obj_date !== FALSE) {
                $formatedDate = date('Y-m-d', $obj_date->getTimeStamp());
            } else {
                return null;
            }
        } catch (Exception $ex) {
            return null;
        }

        if (empty($tenderInfo['Job'])) {
            $chosenBidId = $tenderInfo['Tender']['chosen_bid_id'];
            $this->Job->Tender->Bid->id = $chosenBidId;
            $providerId = $this->Job->Tender->Bid->field('provider_id');
            $jobData = ['Job' => [
                'consumer_id' => $tenderInfo['Consumer']['id'],
                'provider_id' => $providerId,
                'marketplace_id' => $tenderInfo['Demand']['marketplace_id'],
                'demand_id' => $tenderInfo['Demand']['id'],
                'tender_id' => $this->request->data['Tender']['id'],
                'bid_id' => $chosenBidId,
                'date_begin_home_owner'=> $formatedDate,
                ],
            ];
            $result = $this->Job->save($jobData);
        } else {
            $this->Job->id = $tenderInfo['Job']['id'];
            $result = $this->Job->saveField('date_begin_home_owner', $formatedDate);
        }
        return $result;
        
    }


    private function verifyIdAndAccessRightsToChosenBid($id, $providerId) 
    {

        $checkedId = $this->initIdInCaseOfPost($id, 'Tender', 'id');
        if (!$this->Job->Tender->exists($checkedId)) {
            throw new NotFoundException('verifyIdAndAccessRightsToChosenBid: Invalid tender id');
        }
        try {
            $canBid = $this->Job->Tender->providerCanSeeChosenBid($id, $providerId);
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
        if (!$this->Job->Tender->exists($checkedId)) {
            throw new NotFoundException('verifyIdAndAccessRightsToHomeOwnerTenderDetails: Invalid tender id');
        }
        try {
            $canSee = $this->Job->Tender->homeOwnerCanSeeTenderDetails($checkedId, $consumerId);
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
        if (!$this->Job->Tender->exists($checkedId)) {
            throw new NotFoundException('verifyIdAndAdminRights: Invalid tender id');
        }

        if ($this->canAccessAdm) {
            return $checkedId;
        } else {
            $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }

    }


}
