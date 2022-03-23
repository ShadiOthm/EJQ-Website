<?php

App::uses('AppController', 'Controller');

class ConsumersController extends AppController {

    public $components = array('Paginator');

    public function beforeFilter() {
        $this->Auth->allow([
            'dashboard',
            'update_estimation_request',
            'update_address',
            'update_phone',
            ]);
        
        parent::beforeFilter();
    }

    public function dashboard($id=null)
    {
        $this->set('hereTitle', __('Estimator Dashboard'));
        $this->setActiveTab();

        if (!$this->Consumer->exists($id)) {
            throw new NotFoundException('Consumer inválido');
        }
        
        
        $options = array('conditions' => array('Consumer.' . $this->Consumer->primaryKey => $id));
        $consumerData = $this->Consumer->find('first', $options);
        
        if((!$this->canAccessAdm) && ($this->uid != $consumerData['Consumer']['user_id'])) {
            $this->Flash->danger(__('Você não está autorizado a acessar esse perfil.'));
            return $this->redirect(array('controller' => 'main', 'action' => 'index'));
        }
        
        $this->set('userRole', EJQ_ROLE_HOME_OWNER);
             
        $openDemands = $this->Consumer->Demand->getHomeOwnerRequests($id);
        $this->set('openDemands', $openDemands);
        
        $tenders = $this->Consumer->Demand->getHomeOwnerTenders($id);
        $this->set('tenders', $tenders);

        $demandsToEvaluate = $this->Consumer->Demand->getEvaluationsPendingByConsumer($id);    
        $this->set('demandsToEvaluate', $demandsToEvaluate);       

        $toBeScheduledArray = array();
        $result = $this->handleDemandServiceTypes($id, $optionsServiceTypes, $toBeScheduledArray);
        $this->set('optionsServiceTypes', $optionsServiceTypes);
        $this->set('consumer', $consumerData);
        $this->request->data = $consumerData;
        $this->set('toBeScheduledArray', $toBeScheduledArray);
        $this->set('toBeScheduled', in_array(true, $toBeScheduledArray));
        
        $possibleActions['actions']['tenders']['post_a_job'] = [
            'id' => 'go_to_post_a_job',
            'href' => Router::url(array('controller' => 'marketplaces', 'action' => 'post_a_job')),
            'label' => __('Post a new job')
        ];

        
         if (empty($consumerData['Consumer']['address'])) {
             
            $url = Router::url([
                'controller' => 'consumers',
                'action' => 'dashboard',
                $id,
                'tab' => 'my_info',
                '#' => 'my_info'
            ]);             
            $label = __("update");
             $hrefText = "<a href='$url' class=''>$label</a>";
             $message = sprintf(__('Please %s your address so we can schedule a Project Developer\'s visit.', $hrefText));
             
            $this->Flash->call_to_action($message);
        }
        
       
        
        $titleBox['h1'] = sprintf(__('Welcome, %s!'), $consumerData['Consumer']['name']);
        $titleBox['tenderActions'] = $possibleActions['actions']['tenders'];

        $this->set('titleBox', $titleBox);
        $this->set('hereTitle', __('Home Owner\'s Dashboard'));
        
        
    }

    public function update_address($id=null)
    {
        try {
            $checkedId = $this->initIdInCaseOfPost($id, 'Consumer', 'id');
            $consumerId = $this->verifyIfUserCanEditConsumerInfo($checkedId);
        } catch (Exception $ex) {
            throw $ex;
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->saveAddress()) {
                $this->Flash->success(__('The adress was updated'));
            } else {
                $this->Flash->danger(__('It was not possible to update the address. Please contact support.'));
            }
        } else {
            $this->Flash->danger(__('Nothing to be updated'));
        }
        return $this->redirect(array('controller' => 'consumers','action' => 'dashboard', $consumerId, 'tab' => 'my_info'));
    }

    public function update_estimation_request($id=null)
    {
        // init id
        $checkedId = $this->initId($id, 'Consumer', 'user_id');
                
        if (!$this->Consumer->exists($checkedId)) {
            throw new NotFoundException(__('Invalid Consumer'));
        }
        $this->Consumer->id = $checkedId;
        $marketplaceId = $this->Consumer->field('marketplace_id');
        
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Consumer->save($this->request->data)) {
                $demandId = $this->saveEJQRequest($marketplaceId);
                if (!is_null($demandId)) {
                        $this->Flash->success(__('The request was made. We will contact soon.'));

                } else {
                    $this->Flash->danger(__('Not possible to accept your request.'));
                }

                return $this->redirect(array('controller' => 'consumers','action' => 'dashboard', $checkedId));
            } else {
                $this->Flash->danger(__('Not possible to save your request.'));
            }
        }
        
        $this->Flash->danger(__("This action can't be taken. Nothing posted."));
        return $this->redirect(array('controller' => 'consumers','action' => 'dashboard', $checkedId));
    }
    
    public function update_phone($id=null)
    {
        try {
            $checkedId = $this->initIdInCaseOfPost($id, 'Consumer', 'id');
            $consumerId = $this->verifyIfUserCanEditConsumerInfo($checkedId);
        } catch (Exception $ex) {
            throw $ex;
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->savePhone()) {
                $this->Flash->success(__('The phone number was updated'));
            } else {
                $this->Flash->danger(__('It was not possible to update the phone number. Please contact support.'));
            }
        } else {
            $this->Flash->danger(__('Nothing to be updated'));
        }
        return $this->redirect(array('controller' => 'consumers','action' => 'dashboard', $consumerId, 'tab' => 'my_info'));
    }

    public function update_service_types($id=null)
    {
        // init id
        if ((!$id) && ($this->request->data['Consumer']['id'])) {
            $id = $this->request->data['Consumer']['id'];
        }
        
        if (!$this->Consumer->exists($id)) {
            throw new NotFoundException(__('Invalid Consumer'));
        }
        
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Consumer->save($this->request->data)) {
                $demandId = $this->saveEJQRequest();
                if (!is_null($demandId)) {
                        $this->Flash->info(__('The request was made. Please wait for our contact soon.'));

                } else {
                    $this->Flash->danger(__('Not possible to accept your request.'));
                }

                return $this->redirect(array('controller' => 'consumers','action' => 'dashboard', $id));
            } else {
                $this->Flash->danger(__('Not possible to save your request.'));
            }
        }
        
        $this->Flash->danger(__("This action can't be taken. Nothing posted."));
        return $this->redirect(array('controller' => 'consumers','action' => 'dashboard', $id));
        
//        $marketplaceServiceTypes = $this->listAvailableServiceTypesForConsumer($id);
//        $this->set('optionsServiceTypes', $marketplaceServiceTypes);
//        
//        $this->request->data['Consumer']['id'] = $id;
        
        
    }
    
    private function saveDemandsSchedules($id, $demandId, $schedule)
    {
        
        $success = true;
        foreach ($schedule as $serviceTypeId => $dateScheduled) {
            if ($dateScheduled != '') {
                $result = $this->saveScheduleData($id, $demandId, $serviceTypeId, $dateScheduled);
                if(!$result) {
                    $success = false;
                }
            }

        }
        return $success;
    }
    
    private function handleDemandServiceTypes($id, &$optionsServiceTypes, &$toBeScheduled) 
    {
        $this->Consumer->id = $id;
        $marketplaceId = $this->Consumer->field('marketplace_id');
        
        $marketplaceData = $this->Consumer->Marketplace->getServiceTypesByMarketplaceId($marketplaceId);

        if((isset($marketplaceData['0']['ServiceType']) && (is_array($marketplaceData['0']['ServiceType'])))) {
            foreach ($marketplaceData['0']['ServiceType'] as $service) {
                if($service['scheduled_criterion']) {
                    $toBeScheduled[$service['id']] = true;
                }
                
            }
            
        }

        if (isset($marketplaceData['0'])) {
            $optionsServiceTypes = $this->listAvailableServiceTypesForConsumer($marketplaceData['0']);
        }
        return ($marketplaceData);
    }
    
    private function saveAddress()
    {
        $this->Consumer->id = $this->request->data['Consumer']['id'];
        $result = $this->Consumer->saveField('address', $this->request->data['Consumer']['address']);
        return ($result);
    }

    private function saveDemands()
    {
        $id = $this->request->data['Consumer']['id'];
        
        $this->Consumer->id = $id;
        $marketplaceId = $this->Consumer->field('marketplace_id');
                
        $serviceTypesDemanded = $this->request->data['ServiceType']['ServiceType'];
        $hasSomeServiceToDemand = false;
        if(is_array($serviceTypesDemanded)) {
            foreach ($serviceTypesDemanded as $something) {
                if (is_array($something)) {
                    $hasSomeServiceToDemand = true;
                    break;
                }
            }
        }
        
        if($hasSomeServiceToDemand) {
            $data = array();
            $services = $this->request->data['ServiceType']['ServiceType'];
            foreach ($services as $service) {
                if (is_array($service)) {
                    $servicesData['ServiceType'][] = $service['0'];
                }
            }
            $data = array(
                'Demand' => array(
                    'marketplace_id' => $marketplaceId,
                    'consumer_id' => $id,
                    'status' => EJQ_DEMAND_STATUS_REQUEST_OPEN,
                ),
                'ServiceType' => $servicesData,
            );
            $this->Consumer->Demand->save($data);
            $scheduled = array();
            if (isset($this->request->data['ServiceType']['Scheduled'])) {
                $scheduled = $this->request->data['ServiceType']['Scheduled'];
            }
            $marketplaceData = $this->Consumer->Marketplace->getServiceTypesByMarketplaceId($marketplaceId);
            
            $schedulesByServiceType = array();
            foreach($marketplaceData[0]['ServiceType'] as $serviceType) {
                if (($serviceType['scheduled_criterion'] && (in_array($serviceType['id'], $servicesData['ServiceType'])))) {
                    $schedulesByServiceType[$serviceType['id']] = $scheduled[0];
                }
            }
            $resultSchedule = $this->saveDemandsSchedules($id, $this->Consumer->Demand->id, $schedulesByServiceType);
            return ($this->Consumer->Demand->id);
            
        } else {
            $this->Flash->danger(__('Por favor, informe pelo menos um tipo de serviço.'));
            return null;
            
        }
        return null;
        
    }
    
    private function saveEJQRequest($marketplaceId)
    {
        $id = $this->request->data['Consumer']['id'];
        $this->Consumer->id = $id;
        
        $requestData = array('Request' => array(
                    'title' => $this->request->data['Request']['title'], 
                    'description' => $this->request->data['Request']['description'], 
                    'preferred_visit_time' => $this->request->data['Request']['preferred_visit_time'], 
                    'marketplace_id' => $marketplaceId,
                    'consumer_id' => $id,
                    )
            );
        
        $data = array(
            'Demand' => array(
                'marketplace_id' => $marketplaceId,
                'consumer_id' => $id,
                'status' => EJQ_DEMAND_STATUS_REQUEST_OPEN,
            ),
            'Request' => $requestData,
        );
        $this->Consumer->Demand->saveAssociated($data);
        return ($this->Consumer->Demand->id);
    }

    private function savePhone()
    {
        $this->Consumer->id = $this->request->data['Consumer']['id'];
        $result = $this->Consumer->saveField('phone', $this->request->data['Consumer']['phone']);
        return ($result);
    }

    private function saveScheduleData($id, $demandId, $serviceTypeId, $schedule)
    {
        $reversedArray = array_reverse(explode("-", $schedule));
        $reversedString = implode("-", $reversedArray);
        $formatedDate = "$reversedString 00:00:00";
        $this->Consumer->id = $id;
        $data = array(
            'Consumer' => array('id' => "$id"),
            'ServiceType' => array('id' => "$serviceTypeId"),
            'Schedule' => array('0' => array(
                'provider_id' => "$id",
                'demand_id' => "$demandId",
                'service_type_id' => "$serviceTypeId",
                'marketplace_id' => $this->Consumer->field('marketplace_id'),
                'schedule' => $formatedDate,
                )
            ));
        $result = $this->Consumer->saveAssociated($data);
        return $result;
    }
        
    private function verifyIfUserCanEditConsumerInfo($id)
    {
        $this->Consumer->id = $id;
        $userId = $this->Consumer->field('user_id');


        if((!$this->canAccessAdm) &&
                ($this->uid != $userId)) {
            throw new NotFoundException(__('You are not authorized to do this action.'));
        }

        return $id;


    }


    
    private function listAvailableServiceTypesForConsumer($data)
    {
        
        $result = array();
        foreach ($data['ServiceType'] as $serviceType) {
            $result[$serviceType['id']] = $serviceType['name'];
        }        
        return $result;
               
        
    }
    
    
    
    

}
