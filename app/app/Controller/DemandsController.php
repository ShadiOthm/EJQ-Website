<?php

App::uses('AppController', 'Controller');

class DemandsController extends AppController {

    public function beforeFilter() {
        $this->Auth->allow(array(
            'add_condition_to_tender',
            'approve_schedule',
            'cancel',
            'cancel_request',
            'define_estimator',
            'dispatch_estimator',
            'evaluate',
            'hire',
            'request_details',
            'remove_tender_image',
            'remove_tender_term_condition',
            'submit_to_site_admin',
            'suggest_visit_time',
            'supply',
            'update_service_types',
            'update_tender_image_caption',
            'update_tender_term_condition',
            'update_tender_title',
            ));

        parent::beforeFilter();
    }

    public function add_condition_to_tender($id=null)
    {
        $checkedId = $this->initIdInCaseOfPost($id, 'Demand', 'id');
        $this->checkDemandAndTakeActionsIfNeeded($checkedId);
        try {
            if ((!$this->Demand->isThisUserItsEstimator($checkedId, $this->uid, $this->EjqEstimatorMetaProviderId)) &&
                    (!$this->canAccessAdm)) {
                echo __('Você não está autorizado a realizar essa ação.');
                exit;
            }
        } catch (Exception $ex) {
            throw $ex;
        }


        $data = $this->Demand->getTenderInfo($checkedId);

        if ($this->request->is(array('post', 'put'))) {
            if ($this->saveEJQNewTermCondition($checkedId)) {
                $this->Flash->success(__('The tender was updated'));
                return $this->redirect(array('controller' => 'tenders','action' => 'details', $data['Tender']['id'], 'tab' => 'terms'));
            } else {
                $this->Flash->danger(__('It was not possible to update the tender. Please try again.'));
            }
        }

        $this->request->data['Demand']['id'] = $checkedId;

        
    }

    public function approve_schedule($id=null)
    {
        try {
            $checkedId = $this->verifyIdAndAccessRightsToRequestDetails($id);
            $role = $this->determineUserRole($this->uid, $checkedId, $this->EjqEstimatorMetaProviderId);        
            $this->set('userRole', $role);
            $tenderInfo = $this->Demand->getTenderInfo($checkedId);
        } catch (Exception $ex) {
            throw $ex;
        }
        
        $this->set('hereTitle', __('Approve Schedule?'));
        $titleBox['h1'] = $tenderInfo['Request']['title'];
        $titleBox['h2'] = __('Approvie Schedule?');
        $this->set('titleBox', $titleBox);

        if ($this->request->is(array('post', 'put'))) {
            $this->Demand->id = $checkedId;

            $data = array(
                'Demand' => array(
                    'id' => $checkedId,
                    'status' => EJQ_DEMAND_STATUS_ESTIMATION_READY_FOR_DISPATCH,
                    ),
            );
            $result = $this->Demand->save($data);

            if (!empty($result)) {
                $this->Flash->success(__('Schedule approved. Please wait for dispatch.'));
                return $this->redirect(array('controller' => 'demands','action' => 'request_details', $checkedId));
            } else {
                $this->Flash->danger(__('Something wrong occured when trying to do this. Sorry, please check with support.'));
            }
        }

        $requestServiceTypes = $this->listDemandServiceTypes($checkedId);
        $this->set('selectedServiceTypes', array_keys($requestServiceTypes));

        if (!empty($tenderInfo['Schedule']['0'])) {
            $tenderInfo['Schedule']['period'] = $this->build_period($tenderInfo['Schedule']['0']);
        }

        $this->request->data = $tenderInfo;
        
        $this->manageEstimatorAndVisitTimeMessage($checkedId, $role, $requestServiceTypes, $tenderInfo);

        $this->set('tenderInfo', $tenderInfo);
        
        
        
    }

    public function cancel($id=null)
    {

        $demandId = $this->initIdInCaseOfPost($id, 'Demand', 'id');
        $this->checkDemandAndTakeActionsIfNeeded($demandId);
        try {
            if ((!$this->Demand->isThisUserItsConsumer($demandId, $this->uid)) &&
                    (!$this->canAccessAdm)) {
                echo __('Você não está autorizado a realizar essa ação.');
                exit;
            }
        } catch (Exception $ex) {
            throw $ex;
        }

        $this->Demand->id = $demandId;
        $this->Demand->save(array('Demand' => array('status' => DEMAND_STATUS_CANCELED)));

        $this->autoRender = false;
        $this->layout = false;

        echo __("Demanda cancelada");

    }
    
    public function cancel_request($id=null)
    {
        try {
            $checkedId = $this->verifyIdAndAccessRightsToRequestDetails($id);
            $role = $this->determineUserRole($this->uid, $checkedId, $this->EjqEstimatorMetaProviderId);        
            $this->set('userRole', $role);
            $tenderInfo = $this->Demand->getTenderInfo($checkedId);
        } catch (Exception $ex) {
            throw $ex;
        }
        
        $this->set('hereTitle', __('Cancel Tender Request'));
        $titleBox['h1'] = $tenderInfo['Request']['title'];
        $titleBox['h2'] = __('Cancel Tender Request?');
        $this->set('titleBox', $titleBox);

        if ($this->request->is(array('post', 'put'))) {            
            if ($this->doRequestCancelment($checkedId)) {
                $this->Flash->success(__('The request was canceled'));
                if ($this->canAccessAdm) {
                    return $this->redirect(array('controller' => 'marketplaces','action' => 'dashboard', $this->EjqMarketplaceId));
                } else {
                    return $this->redirect(array('controller' => 'providers','action' => 'dashboard', $this->EjqProfileId));
                }
            } else {
                $this->Flash->danger(__('It was not possible to cancel the request. Please try again.'));
            }
        }

        $requestServiceTypes = $this->listDemandServiceTypes($checkedId);
        $this->set('selectedServiceTypes', array_keys($requestServiceTypes));

        $this->request->data['Demand']['id'] = $checkedId;
        
        $this->manageEstimatorAndVisitTimeMessage($checkedId, $role, $requestServiceTypes, $tenderInfo);

        $this->set('tenderInfo', $tenderInfo);

    }
    
    public function define_estimator($id=null)
    {
        if (!$this->canAccessAdm) {
            $this->Flash->danger(__('Você não está autorizado a acessar esse perfil.'));
            return $this->redirect(array('controller' => 'main', 'action' => 'index'));
        }

        $checkedId = $this->initId($id, 'Demand', 'id', 'id');
        if (!$this->Demand->exists($checkedId)) {
            throw new NotFoundException('Demand inválido');
        }
        $providerId = $this->initId(null, 'Provider', 'id', 'id');
        if (!$this->Demand->Provider->exists($providerId)) {
            throw new NotFoundException('Invalid Estimator');
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->request->data['Provider']['id'] > 0 ) {
                $this->request->data['Demand']['status'] = EJQ_DEMAND_STATUS_ESTIMATION_ASSIGNED;
            } else {
                $this->request->data['Demand']['status'] = EJQ_DEMAND_STATUS_REQUEST_OPEN;
            }
            $this->request->data['Request']['demand_id'] = $this->request->data['Demand']['id'];
            $this->request->data['Request']['provider_id'] = $this->request->data['Provider']['id'];
            if ($this->Demand->saveAssociated($this->request->data)) {
                $this->Flash->success(__('The request was updated'));
                return $this->redirect(array('controller' => 'demands','action' => 'request_details', $checkedId));
            } else {
                $this->Flash->danger(__('It was not possible to update the request. Please try again.'));
            }
        }

        $this->Flash->danger(__('There was no request posted to be updated'));
        return $this->redirect(array('controller' => 'demands','action' => 'request_details', $checkedId));


    }

    public function dispatch_estimator($id=null)
    {
        try {
            $checkedId = $this->verifyIdAndAccessRightsToDemandDetails($id);
            $role = $this->determineUserRole($this->uid, $checkedId, $this->EjqEstimatorMetaProviderId);        
            $this->set('userRole', $role);
            $tenderInfo = $this->Demand->getTenderInfo($checkedId);
        } catch (Exception $ex) {
            throw $ex;
        }
        
        $this->set('hereTitle', __('Dispatch Project Developer'));
        $titleBox['h1'] = $tenderInfo['Request']['title'];
        $titleBox['h2'] = __('Dispatch Project Developer?');
        $this->set('titleBox', $titleBox);

        if (!$this->canAccessAdm) {
            $this->Flash->danger(__('Você não está autorizado a acessar essa função.'));
            return $this->redirect(array('controller' => 'main', 'action' => 'index'));
        }

        if ($this->request->is(array('post', 'put'))) {
            
            $existingSchedule = false;
            if (!empty($tenderInfo['Schedule'])) {
                $existingSchedule = true;
            } 
            
            $requestSchedule = false;
            if (!empty($this->request->data['Schedule']['period_date'])
                    && !empty($this->request->data['Schedule']['period_time_begin'])) {
                $requestSchedule = true;
            } 
            
            $changeSchedule = false;
            if (!empty($this->request->data['Demand']['change_schedule'])) {
                $changeSchedule = true;
            }
            if (isset($this->request->data['Demand']['change_schedule'])) {
                unset($this->request->data['Demand']['change_schedule']);
            }
            
            if ($existingSchedule || $requestSchedule) {
                if ($requestSchedule) {
                    if (!$existingSchedule) {
                        $scheduleData = $this->processScheduleData($id, $this->request->data);
                        unset($this->request->data['Schedule']);
                        $scheduleData['demand_id'] = $this->request->data['Demand']['id'];
                        $scheduleData['provider_id'] = $this->request->data['Provider']['id'];
                        $scheduleData['marketplace_id'] = $this->request->data['Demand']['marketplace_id'];
                        $scheduleData['consumer_id'] = $this->request->data['Consumer']['id'];

                        $serviceTypes = $this->Demand->listServiceTypes($checkedId);
                        foreach ($serviceTypes as $key => $value) {
                            $scheduleData['service_type_id'] = $key;
                            $this->request->data['Schedule'][] = $scheduleData;
                        }
                        $this->request->data['Demand']['status'] = EJQ_DEMAND_STATUS_ESTIMATION_DISPATCHED;
                        if ($this->Demand->saveAssociated($this->request->data)) {
                            $this->Flash->success(__('The visit was scheduled'));
                            return $this->redirect(array('controller' => 'demands','action' => 'request_details', $checkedId));
                        } else {
                            $this->Flash->danger(__('It was not possible to update the request. Please try again.'));
                        }
                    } else {
                        $periodData = $this->formatPeriod($this->request->data['Schedule']['period_date'], $this->request->data['Schedule']['period_time_begin']);
                        if (empty($periodData)) {
                            $this->Flash->danger(__('It was not possible to dispatch Project Developer because no schedule date was informed.'));
                            return $this->redirect(array('controller' => 'demands','action' => 'dispatch_estimator', $checkedId));
                        }
                        $periodBegin = $periodData['schedule_period_begin'];
                        $periodEnd = $periodData['schedule_period_end'];
                        if ($changeSchedule) {
                         
                            foreach ($tenderInfo['Schedule'] as $existingSchedule) {
                                $this->Demand->Schedule->id = $existingSchedule['id'];
                                $this->Demand->Schedule->saveField('schedule_period_begin', $periodBegin) ;
                                $this->Demand->Schedule->saveField('schedule_period_end', $periodEnd) ;
                            }
                        }
                        $this->Demand->id = $this->request->data['Demand']['id'];
                        $this->Demand->saveField('status', EJQ_DEMAND_STATUS_ESTIMATION_DISPATCHED) ;
                        $this->Flash->success(__('The visit was scheduled'));
                        return $this->redirect(array('controller' => 'demands','action' => 'request_details', $checkedId));
                    }
                } else {
                    $this->Demand->id = $tenderInfo['Demand']['id'];
                    $this->Demand->saveField('status', EJQ_DEMAND_STATUS_ESTIMATION_DISPATCHED) ;
                    $this->Flash->success(__('The visit was scheduled'));
                    return $this->redirect(array('controller' => 'demands','action' => 'request_details', $checkedId));
                }
            } else {
                $this->Flash->danger(__('It was not possible to dispatch Project Developer because no schedule date was informed.'));
            }
        }

        $requestServiceTypes = $this->listDemandServiceTypes($checkedId);
        $this->set('selectedServiceTypes', array_keys($requestServiceTypes));
        if (!empty($tenderInfo['Schedule']['0'])) {
            $tenderInfo['Schedule']['period'] = $this->build_period($tenderInfo['Schedule']['0']);
        }

        $this->request->data = $tenderInfo;
        $this->manageEstimatorAndVisitTimeMessage($checkedId, $role, $requestServiceTypes, $tenderInfo);
        $this->set('tenderInfo', $tenderInfo);
        

    }

    public function evaluate()
    {

        $this->autoRender = false;
        $this->layout = false;

        if (isset($this->request->data['demand_id'])) {
            $demandId = $this->request->data['demand_id'];
        } else {
            throw new NotFoundException(__('Não foi informada qual demanda deve ser avaliada'));

        }

        if (!$this->Demand->isThisUserItsConsumer($demandId, $this->uid)) {
            $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
            return $this->redirect(array('controller' => 'main', 'action' => 'index'));
        }

        if (isset($this->request->data['consumer_evaluation_value'])) {
            $evaluationValue = $this->request->data['consumer_evaluation_value'];
        } else {
            throw new NotFoundException(__('Não foi informada a avaliação a ser registrada.'));

        }

        $this->Demand->saveEvaluation($demandId, $evaluationValue);

        echo __("Avaliação registrada");

    }

    public function hire($id=null)
    {
        $checkedId = $this->initIdInCaseOfPost($id, 'Demand', 'id');
        $this->checkDemandAndTakeActionsIfNeeded($checkedId);

        if (!$this->Demand->isThisUserItsConsumer($checkedId, $this->uid)) {
            $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
            return $this->redirect(array('controller' => 'main', 'action' => 'index'));
        }

        $this->Demand->id = $checkedId;
        $this->Demand->save(array('Demand' => array('status' => DEMAND_STATUS_HIRED)));

        $this->autoRender = false;
        $this->layout = false;

        echo __("Demanda contratada");

    }

    public function remove_tender_image($photoId=null)
    {
        $this->Demand->File->id = $photoId;
        $demandId = $this->Demand->File->field('demand_id');
        $tenderId = $this->Demand->File->field('tender_id');
        try {
            $checkedId = $this->verifyIdAndAccessRightsToDemandDetails($demandId);
        } catch (Exception $ex) {
            throw $ex;
        }
        if ($this->Demand->File->delete($photoId)) {
            $this->Flash->success(__('The photo was removed'));
        } else {
            $this->Flash->danger(__('It was not possible to remove the photo. Please try again.'));
        }
        return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderId, 'tab' => 'images'));
    }

    public function remove_tender_term_condition($conditionId=null)
    {
        $this->Demand->TermCondition->id = $conditionId;
        $demandId = $this->Demand->TermCondition->field('demand_id');
        $tenderId = $this->Demand->TermCondition->field('tender_id');
        
        try {
            $checkedId = $this->verifyIdAndAccessRightsToDemandDetails($demandId);
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }
        if ($this->Demand->TermCondition->delete($conditionId)) {
            $this->Flash->success(__('The term and condition was removed'));
        } else {
            $this->Flash->danger(__('It was not possible to remove the term and condition. Please try again.'));
        }
        return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderId, 'tab' => 'terms'));
    }

    public function request_details($id)
    {
        $this->set('hereTitle', __('Request Details'));
        $this->setActiveTab();

        try {
            $checkedId = $this->verifyIdAndAccessRightsToRequestDetails($id);
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }
        
        $tenderInfo = $this->Demand->getTenderInfo($checkedId);

        $role = $this->determineUserRole($this->uid, $checkedId, $this->EjqEstimatorMetaProviderId);        
        $this->set('userRole', $role);
        $possibleActions = $this->Demand->possibleActions($checkedId, $role);
        $this->set('rights', $possibleActions['rights']);
        
        if ($this->Demand->tenderDetailsShouldBeSeen($checkedId, $role)) {
                $activeTab = null;
                if (!empty($this->request->params['named']['tab'])) {
                    $activeTab = $this->request->params['named']['tab'];
                }
                return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderInfo['Tender']['id'], 'tab' => $activeTab));
        }
        

        if (!empty($tenderInfo['Schedule']['0'])) {
            $tenderInfo['Schedule']['period'] = $this->build_period($tenderInfo['Schedule']['0']);
        }

        $requestServiceTypes = $this->listDemandServiceTypes($checkedId);
        $this->set('selectedServiceTypes', array_keys($requestServiceTypes));

        $demandsList = implode(", ", $requestServiceTypes);
        $this->set('servicesList', $demandsList);
        $tenderInfo['Demand']['services_list_description'] = $demandsList;


        $marketplaceServiceTypes = $this->listMarketplaceServiceTypes($tenderInfo['Demand']['marketplace_id']);
        $this->set('optionsServiceTypes', $marketplaceServiceTypes);

        $this->request->data['Demand']['id'] = $checkedId;
        $this->request->data['Tender']['id'] = $tenderInfo['Tender']['id'];
        $this->request->data['Consumer']['id'] = $tenderInfo['Consumer']['id'];
        $this->request->data['Provider']['id'] = $tenderInfo['Provider']['id'];
        $this->request->data['Demand']['marketplace_id'] = $tenderInfo['Demand']['marketplace_id'];
        $this->request->data['Demand']['consumer_id'] = $tenderInfo['Consumer']['id'];
        $this->request->data['Request']['id'] = $tenderInfo['Request']['id'];

        
        $tenderDevelopmentInvoice = $this->Demand->Invoice->createInvoiceIfNeeded($tenderInfo['Tender']['id'], EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT);
        $tenderDevelopmentInvoice['Invoice']['payee'] = $tenderInfo['Consumer']['name'];
        $tenderInfo['Invoice']['0'] = $tenderDevelopmentInvoice;
        
        $this->manageEstimatorAndVisitTimeMessage($checkedId, $role, $requestServiceTypes, $tenderInfo);


        $this->request->data['Invoice'] = $tenderInfo['Invoice'];
        $this->set('tenderInfo', $tenderInfo);
        
        $titleBox['h1'] = $tenderInfo['Request']['title'];
        $titleBox['h2'] = $this->Demand->getStatusLabel($checkedId);
        $titleBox['tenderActions'] = $possibleActions['actions']['tenders'];

        $this->set('titleBox', $titleBox);
        $this->set('hereTitle', $tenderInfo['Request']['title']);

    }

    public function suggest_visit_time($id=null)
    {
        try {
            $checkedId = $this->verifyIdAndAccessRightsToRequestDetails($id);
            $role = $this->determineUserRole($this->uid, $checkedId, $this->EjqEstimatorMetaProviderId);        
            $this->set('userRole', $role);
            $tenderInfo = $this->Demand->getTenderInfo($checkedId);
        } catch (Exception $ex) {
            throw $ex;
        }
        
        $this->set('hereTitle', __('Suggest Visit Time'));
        $titleBox['h1'] = $tenderInfo['Request']['title'];
        $titleBox['h2'] = __('Suggest Visit Time?');
        $this->set('titleBox', $titleBox);

        //$requestData = $this->Demand->getRequestInfo($checkedId);
        //$this->set('requestInfo', $requestData);

        if ($this->request->is(array('post', 'put'))) {
            if ($this->saveEJQVisitTimeSuggestion($checkedId)) {
                $this->Flash->success(__('The suggestion was registered'));
                return $this->redirect(array('controller' => 'demands','action' => 'request_details', $checkedId));
            } else {
                $this->Flash->danger(__('It was not possible to update the request. Please try again.'));
            }
        }

        $requestServiceTypes = $this->listDemandServiceTypes($checkedId);
        $this->set('selectedServiceTypes', array_keys($requestServiceTypes));

        if (!empty($tenderInfo['Schedule']['0'])) {
            $tenderInfo['Schedule']['period'] = $this->build_period($tenderInfo['Schedule']['0']);
        }

        $this->request->data = $tenderInfo;
        
        $this->manageEstimatorAndVisitTimeMessage($checkedId, $role, $requestServiceTypes, $tenderInfo);

        $this->set('tenderInfo', $tenderInfo);
    }
    
    public function update_service_types($id=null)
    {
        if (!$this->canAccessAdm) {
            $this->Flash->danger(__('Você não está autorizado a acessar esse perfil.'));
            return $this->redirect(array('controller' => 'main', 'action' => 'index'));
        }

        $checkedId = $this->initIdInCaseOfPost($id, 'Demand', 'id');
        $this->checkDemandAndTakeActionsIfNeeded($checkedId);

        if ($this->request->is(array('post', 'put'))) {
            $this->request->data['Provider']['id'] = NULL;
            if ($this->Demand->save($this->request->data)) {
                $this->Flash->success(__('Os serviços foram alterados'));
                return $this->redirect(array('controller' => 'demands','action' => 'request_details', $checkedId));
            } else {
                $this->Flash->danger(__('Não foi possível alterar os serviços do provider. Favor tentar novamente.'));
            }
        }


        $requestServiceTypes = $this->listDemandServiceTypes($demandId);
        $this->set('selectedServiceTypes', array_keys($requestServiceTypes));

        $this->Demand->id = $checkedId;
        $marketplaceId = $this->Demand->field('marketplace_id');

        $marketplaceServiceTypes = $this->listMarketplaceServiceTypes($marketplaceId);
        $this->set('optionsServiceTypes', $marketplaceServiceTypes);

        $this->request->data['Provider']['id'] = $checkedId;


    }

    public function update_tender_image_caption($id=null)
    {
        try {
            $checkedId = $this->verifyIdAndAccessRightsToDemandDetails($id);
        } catch (Exception $ex) {
            throw $ex;
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->saveTenderPhotoCaption()) {
                $this->Flash->success(__('The photo caption was updated'));
            } else {
                $this->Flash->danger(__('It was not possible to update the photo caption. Please try again.'));
            }
        } else {
            $this->Flash->danger(__('Nothing to be updated'));
        }
        return $this->redirect(array('controller' => 'tenders','action' => 'details', $this->request->data['Tender']['id'], 'tab' => 'images'));
    }

    public function update_tender_term_condition($id=null)
    {
        try {
            $checkedId = $this->verifyIdAndAccessRightsToDemandDetails($id);
        } catch (Exception $ex) {
            throw $ex;
        }
        
        if ($this->request->is(array('post', 'put'))) {
            if ($this->saveTenderTermCondition()) {
                $this->Flash->success(__('The term and condition was updated'));
            } else {
                $this->Flash->danger(__('It was not possible to update the term and condition. Please try again.'));
            }
        } else {
            $this->Flash->danger(__('Nothing to be updated'));
        }
        return $this->redirect(array('controller' => 'tenders','action' => 'details', $this->request->data['Tender']['id'], 'tab' => 'terms'));
    }

    public function update_tender_title($id=null)
    {
        try {
            $checkedId = $this->verifyIdAndAccessRightsToDemandDetails($id);
        } catch (Exception $ex) {
            throw $ex;
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->saveTenderTitle()) {
                $this->Flash->success(__('The title was updated'));
            } else {
                $this->Flash->danger(__('It was not possible to update the title. Please try again.'));
            }
        } else {
            $this->Flash->danger(__('Nothing to be updated'));
        }
        return $this->redirect(array('controller' => 'tenders','action' => 'details', $this->request->data['Tender']['id']));
    }
    
    private function build_period($schedule)
    {
            App::uses('CakeTime', 'Utility');

            $visitDate = CakeTime::format($schedule['schedule_period_begin'], '%b %d, %Y');
            $endVisitDate = CakeTime::format($schedule['schedule_period_end'], '%b %d, %Y');
            $windowBegin = CakeTime::format($schedule['schedule_period_begin'], '%I:%M %p');
            $windowEnd = CakeTime::format($schedule['schedule_period_end'], '%I:%M %p');
            if (($windowBegin == $windowEnd) && ($visitDate == $endVisitDate)):
                $period = sprintf(__('%s, %s'), $visitDate, $windowBegin); 
            else:
                if ($visitDate == $endVisitDate):
                    $period = sprintf(__('%s,  from %s to %s'), $visitDate, $windowBegin, $windowEnd);
                else: 
                    $period = sprintf(__('From %s, %s to %s,  %s'), $visitDate, $windowBegin, $endVisitDate, $windowEnd);
                endif;
            endif;
            
            return $period;
        
    }

    private function checkDemandAndTakeActionsIfNeeded($demandId)
    {
        $found = $this->Demand->exists($demandId);
        if ($found) {
            return $found;
        } else {
            $this->Flash->danger(__('Essa demanda não está especificada!!!!!'));
            return $this->redirect(array('controller' => 'main', 'action' => 'index'));
        }

    }

    private function determineUserRole($userId, $demandId, $estimatorMetaProviderId)
    {
        $role = EJQ_ROLE_VISITOR;
        $isDemandEstimator = false;
        if ($this->Demand->isThisUserItsEstimator($demandId, $userId, $estimatorMetaProviderId)) {
            $this->set('isDemandEstimator', true);
            $role = EJQ_ROLE_ESTIMATOR;
        }
        $this->set('isDemandEstimator', $isDemandEstimator);

        if ($this->canAccessAdm) {
            $role = EJQ_ROLE_ADMIN;
        }
        
        $consumerId = $this->Demand->isThisUserItsConsumer($demandId, $userId);
        $this->set('isDemandHomeOwner', false);
        if($consumerId) {
            $this->set('isDemandHomeOwner', true);
            $role = EJQ_ROLE_HOME_OWNER;
        }

        return $role;
    }

    private function doRequestCancelment($id)
    {

        $this->Demand->id = $id;
 
        $data = array(
            'Demand' => array(
                'id' => $id,
                'status' => EJQ_DEMAND_STATUS_REQUEST_CANCEL,
                ),
        );
        $result = $this->Demand->save($data);
        return ($result);


    }
    
    private function formatPeriod($date, $timeBegin)
    {
        $periodData = [];
        if (!empty($date)) {
            try {
                $objDate = DateTime::createFromFormat('m-d-Y', $date);
                if ($obj_date !== FALSE) {
                    $periodDate = date('Y-m-d', $objDate->getTimeStamp());
                } else {
                    return null;
                }
            } catch (Exception $ex) {
                return null;
            }
            $periodEnd = $periodBegin = "";
            if (!empty($timeBegin)) {
                try {
                    $objTimeBegin = DateTime::createFromFormat('H:i A', $timeBegin);
                    if ($obj_date !== FALSE) {
                        $periodBegin = date('H:i:s', $objTimeBegin->getTimeStamp());
                    } else {
                        return null;
                    }
                } catch (Exception $ex) {
                    return null;
                }
                try {
                    $obj_time_end = date_modify($objTimeBegin, "+2 hours");
                    if ($obj_date !== FALSE) {
                        $periodEnd = date('H:i:s', $obj_time_end->getTimeStamp());
                    } else {
                        return null;
                    }
                } catch (Exception $ex) {
                    return null;
                }
            }
            $periodData['schedule_period_begin'] = "$periodDate $periodBegin";
            $periodData['schedule_period_end'] = "$periodDate $periodEnd";
        } else {
            return null;
        }
        
        return $periodData;
        
    }

    private function listDemandServiceTypes($id)
    {

        $servicesData = $this->Demand->find(
                'all',
                array(
                    'contain' => array('ServiceType.id', 'ServiceType.name'),
                    'conditions' => array(
                        'Demand.id' => $id,
                        ),

                    )
                );




        $data = $servicesData['0'];

        $result = array();
        foreach ($data['ServiceType'] as $key => $serviceType) {
            $result[$serviceType['id']] = $serviceType['name'];
        }

        return $result;

    }

    private function listMarketplaceServiceTypes($id)
    {

        $servicesData = $this->Demand->Marketplace->find(
                'all',
                array(
                    'contain' => array('ServiceType.id', 'ServiceType.name'),
                    'conditions' => array(
                        'Marketplace.id' => $id,
                        ),

                    )
                );




        $data = $servicesData['0'];

        $result = array();
        foreach ($data['ServiceType'] as $key => $serviceType) {
            $result[$serviceType['id']] = $serviceType['name'];
        }

        return $result;

    }
    
    private function manageEstimatorAndVisitTimeMessage($checkedId, $role, $requestServiceTypes, $tenderInfo)
    {
        $visitTimeMessage = "";
        if (empty($requestServiceTypes)) {
            if ($role == EJQ_ROLE_ADMIN) {
                $estimatorsMessage = __("Please define the job categories first.");
            } else {
                $estimatorsMessage = __("To be defined");
            }
            $elegibleEstimators = array();
        } else {

            $elegibleEstimators = $this->Demand->listQualifiedProviders($checkedId, $this->EjqEstimatorMetaProviderId);

            if (!empty($elegibleEstimators)) {
                if (!empty($tenderInfo['Provider']['id'])) {
                    $this->Demand->Provider->id = $tenderInfo['Provider']['id'];
                    $name = $this->Demand->Provider->field('name');
                    $estimatorsMessage = $name;
                } else {
                    $estimatorsMessage = __("Not chosen yet.");
                }
                if(!empty($tenderInfo['Request']['visit_time_suggested'])) {
                    $visitTimeMessage = $tenderInfo['Request']['visit_time_suggested'];
                }
            } else {
                $estimatorsMessage = __("There are no elegible estimators for this job.");
            }
        }
        $this->set('estimatorsMessage', $estimatorsMessage);
        $this->set('optionsEstimators', $elegibleEstimators);
        //$this->set('optionsEstimators', $elegibleEstimators);

        
    }
    
    private function processScheduleData($id, $requestData)
    {
        $this->Demand->id = $id;
        if (!empty($requestData['Schedule']['period_date'])) {
            $scheduleData = $requestData['Schedule'];
            try {
                $obj_date = DateTime::createFromFormat('m-d-Y', $scheduleData['period_date']);
                if ($obj_date !== FALSE) {
                    $periodDate = date('Y-m-d', $obj_date->getTimeStamp());
                } else {
                    $this->Flash->danger(__('No visit date to register. Please inform a date to schedule a visit.'));
                    return $this->redirect(array('controller' => 'demands','action' => 'suggest_visit_time', $id));
                }
            } catch (Exception $ex) {
                $this->Flash->danger(__('No visit date to register. Please inform a date to schedule a visit.'));
                return $this->redirect(array('controller' => 'demands','action' => 'suggest_visit_time', $id));
            }
            $periodEnd = $periodBegin = "";
            if (isset($scheduleData['period_time_begin'])) {
                try {
                    $obj_time_begin = DateTime::createFromFormat('H:i A', $scheduleData['period_time_begin']);
                    if (!empty($obj_time_begin)) {
                        $periodBegin = date('H:i:s', $obj_time_begin->getTimeStamp());
                    } else {
                        $this->Flash->danger(__('No visit time to register. Please inform a time to schedule a visit.'));
                        return $this->redirect(array('controller' => 'demands','action' => 'suggest_visit_time', $id));
                    }
                } catch (Exception $ex) {
                    $this->Flash->danger(__('No visit time to register. Please inform a time to schedule a visit.'));
                    return $this->redirect(array('controller' => 'demands','action' => 'suggest_visit_time', $id));
                }
                try {
                    $obj_time_end = date_modify($obj_time_begin, "+2 hours");
                    if (!empty($obj_time_end)) {
                        $periodEnd = date('H:i:s', $obj_time_end->getTimeStamp());
                    } else {
                        $this->Flash->danger(__('No visit time to register. Please inform a time to schedule a visit.'));
                        return $this->redirect(array('controller' => 'demands','action' => 'suggest_visit_time', $id));
                    }
                } catch (Exception $ex) {
                    $this->Flash->danger(__('No visit time to register. Please inform a time to schedule a visit.'));
                    return $this->redirect(array('controller' => 'demands','action' => 'suggest_visit_time', $id));
                }
            }


            $scheduleData['schedule_period_begin'] = "$periodDate $periodBegin";
            $scheduleData['schedule_period_end'] = "$periodDate $periodEnd";
            unset($scheduleData['period_date']);
            unset($scheduleData['period_time_begin']);
            unset($scheduleData['period_time_end']);
        } else {
            $this->Flash->danger(__('No schedule to register. Please inform a date and time to schedule a visit.'));
            return $this->redirect(array('controller' => 'demands','action' => 'suggest_visit_time', $id));
        }
        
        return $scheduleData;
    }

    private function removeTenderPhoto()
    {
        
        $this->Demand->File->delete($this->request->data['File']['id']);
        return ($result);


    }

    private function saveEJQNewTermCondition($id)
    {

        $this->Demand->id = $id;
        $marketplaceId = $this->Demand->field('marketplace_id');
        $providerId = $this->Demand->field('provider_id');
        $consumerId = $this->Demand->field('consumer_id');
        $tenderId = $this->request->data['Tender']['id'];

        $termConditionData = array('TermCondition' => array(
                    'description' => $this->request->data['TermCondition']['description'],
                    'consumer_id' => $consumerId,
                    'provider_id' => $providerId,
                    'marketplace_id' => $marketplaceId,
                    'tender_id' => $this->request->data['Tender']['id'],
                    'demand_id' => $id,
                    )
            );

        $data = array(
            'Demand' => array(
                'id' => $id,
            ),
            'TermCondition' => $termConditionData,
        );
        $result = $this->Demand->saveAssociated($data);
        return ($result);

    }

    private function saveEJQVisitTimeSuggestion($id)
    {
        $this->Demand->id = $id;
        
        $scheduleData = $this->processScheduleData($id, $this->request->data);
        unset($this->request->data['Schedule']);

        $scheduleData['demand_id'] = $this->request->data['Demand']['id'];
        $scheduleData['provider_id'] = $this->Demand->field('provider_id');
        $scheduleData['marketplace_id'] = $this->EjqMarketplaceId;
        $scheduleData['consumer_id'] = $this->Demand->field('consumer_id');;
        $serviceTypes = $this->Demand->listServiceTypes($id);
        foreach ($serviceTypes as $key => $value) {
            $scheduleData['service_type_id'] = $key;
            $this->request->data['Schedule'][] = $scheduleData;

        }
        
        if ($this->Demand->field('status') == EJQ_DEMAND_STATUS_WAITING_FOR_ESTIMATOR_SCHEDULE_APPROVAL 
                ||  $this->Demand->field('status') == EJQ_DEMAND_STATUS_ESTIMATION_ASSIGNED) {
            $this->request->data['Demand']['status'] = EJQ_DEMAND_STATUS_WAITING_FOR_HOME_OWNER_SCHEDULE_APPROVAL;
        } else {
            $this->request->data['Demand']['status'] = EJQ_DEMAND_STATUS_WAITING_FOR_ESTIMATOR_SCHEDULE_APPROVAL;
        }
        
        $tenderInfo = $this->Demand->getTenderInfo($id);
        if (!empty($tenderInfo['Schedule'])) {
            $this->Demand->Schedule->deleteAll(
                array(
                    "Schedule.demand_id" => $id,
                )
            );            
        }
        
        
        
        
        if ($this->Demand->saveAssociated($this->request->data)) {
            $this->Flash->success(__('The schedule suggestion was registered. Please wait confirmation.'));
            return $this->redirect(array('controller' => 'demands','action' => 'request_details', $id));
        } else {
            $this->Flash->danger(__('It was not possible to update the request. Please try again.'));
        }
        return ($result);


    }

    private function saveTenderPhotoCaption()
    {
        $fileData = array('File' => array(
                    'id' => $this->request->data['File']['id'],
                    'description' => $this->request->data['File']['description'],
                    )
            );

        $data = array(
            'Demand' => array(
                'id' => $this->request->data['Demand']['id'],
            ),
            'File' => $fileData,
        );
        $result = $this->Demand->saveAssociated($data);
        return ($result);


    }

    private function saveTenderTermCondition()
    {
        
        $termConditionData = array('TermCondition' => array(
                    'id' => $this->request->data['TermCondition']['id'],
                    'description' => $this->request->data['TermCondition']['description'],
                    )
            );

        $data = array(
            'Demand' => array(
                'id' => $this->request->data['Demand']['id'],
            ),
            'TermCondition' => $termConditionData,
        );
        
        
        $result = $this->Demand->saveAssociated($data);
        
        return ($result);


    }

    private function saveTenderTitle()
    {

        $tenderData = array('Tender' => array(
                    'id' => $this->request->data['Tender']['id'],
                    'title' => $this->request->data['Tender']['title'],
                    'demand_id' => $this->request->data['Demand']['id'],
                    )
            );

        $data = array(
            'Demand' => array(
                'id' => $this->request->data['Demand']['id'],
            ),
            'Tender' => $tenderData,
        );
        $result = $this->Demand->saveAssociated($data);
        return ($result);


    }

    private function verifyIdAndAccessRightsToDemandDetails($id) {
        $checkedId = $this->initIdInCaseOfPost($id, 'Demand', 'id');
        if (!$this->Demand->exists($checkedId)) {
            throw new NotFoundException('Invalid demand id');
        }

        try {
            $hasAccess = $this->verifyIfUserHasAccessToDemandDetails($checkedId);
        } catch (Exception $ex) {
            throw $ex;
        }
        
        if ($hasAccess) {
            return $checkedId;
        } else {
            return false;
        }

    }

    private function verifyIdAndAccessRightsToRequestDetails($id) {
        $checkedId = $this->initIdInCaseOfPost($id, 'Demand', 'id');
        if (!$this->Demand->exists($checkedId)) {
            throw new NotFoundException('Invalid demand id');
        }

        try {
            $hasAccess = $this->verifyIfUserHasAccessToRequestDetails($checkedId);
        } catch (Exception $ex) {
            throw $ex;
        }
        
        if ($hasAccess) {
            return $checkedId;
        } else {
            return false;
        }

    }

    private function verifyIfUserCanBidOnDemand($id, $providerId)
    {
        $this->Demand->id = $id;
        $demandStatus = $this->Demand->field('status');
        $demandServices = $this->Demand->listServiceTypes($id);
        $this->Demand->Provider->id = $providerId;
        $metaProviderId = $this->Demand->Provider->field('meta_provider_id');
        $providerServiceTypes = $this->Demand->Provider->listServiceTypes($providerId);
        $serviceTypesIds = array_keys($demandServices);
        $providerServiceIds = array_keys($providerServiceTypes);
        $canBid = false;
        if(!empty($serviceTypesIds)) {
            $servicesCounter = count($serviceTypesIds);
            foreach ($serviceTypesIds as $thisServiceId) {
                if (in_array($thisServiceId, $providerServiceIds)) {
                    $servicesCounter--;
                }
            }
            if ($servicesCounter == 0) {
                $canBid = true;
            }
        }

        if((!$canBid) || ($demandStatus != EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS) || ($metaProviderId != $this->EjqContractorMetaProviderId)) {
            throw new NotFoundException(__('Você não está autorizado a acessar essa demanda.'));
        }

        return $id;


    }

    private function verifyIfUserHasAccessToDemandDetails($id)
    {
        
        if (empty($this->uid)) {
            throw new NotFoundException(__('Please log in to access this information.'));
        }
        
        $hasAccess = false;
        if ($this->Demand->isThisUserItsEstimator($id, $this->uid, $this->EjqEstimatorMetaProviderId)) {
            $hasAccess = true;
        }
        
        if ($this->canAccessAdm) {
            $hasAccess = true;
        }

        $consumerId = $this->Demand->isThisUserItsConsumer($id, $this->uid);
        if($consumerId) {
            $tenderInfo = $this->Demand->getTenderInfo($id);
            if ($this->Demand->Tender->homeOwnerCanSeeTenderDetails($tenderInfo['Tender']['id'], $consumerId)) {
                $hasAccess = true;
            }
        }
        if ($hasAccess) {
            return $id;
        } else {
            throw new NotFoundException(__('Sorry, this information is not available now.'));
        }
        
    }
    
    private function verifyIfUserHasAccessToRequestDetails($id)
    {
        
        if (empty($this->uid)) {
            throw new NotFoundException(__('Please log in to access this information.'));
        }
        
        $hasAccess = false;
        if ($this->Demand->isThisUserItsEstimator($id, $this->uid, $this->EjqEstimatorMetaProviderId)) {
            $hasAccess = true;
        }
        
        if ($this->canAccessAdm) {
            $hasAccess = true;
        }

        $consumerId = $this->Demand->isThisUserItsConsumer($id, $this->uid);
        if($consumerId) {
            $hasAccess = true;
        }
        
        if ($hasAccess) {
            return $id;
        } else {
            throw new NotFoundException(__('Sorry, this information is not available now.'));
        }
        
    }

}
