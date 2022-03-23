<?php

App::uses('AppController', 'Controller');

class ProvidersController extends AppController {

    public $components = array('Paginator');

    public function beforeFilter() {
        $this->Auth->allow(array('dashboard'));

        parent::beforeFilter();
    }

    public function dashboard($id=null)
    {
        $checkedId = $this->checkIfProviderExists($id, $this->Provider, $this->Components);

        if ($this->checkIfIsContractor($checkedId)) {
            $this->ejqContractorDashboard($checkedId);
        } 
        if ($this->checkIfIsEstimator($checkedId)) {
            $this->ejqEstimatorDashboard($checkedId);
        } 

    }

    public function update_weekdays($id=null, $serviceTypeId=null)
    {
        
        $this->initIds($id, $serviceTypeId);
        $this->setMarketplaceNameAndProviderId($id);
        
        if ($this->request->is(array('post', 'put'))) {
            
            $stringBin = $this->formatWeekdaysAsBinary($this->request->data['ProviderWeekdays']['weekdays']);
            
            $this->ProviderSaveWeekdays($id, $serviceTypeId, $stringBin);
            
            $this->Flash->success(__('Disponibilidade alterada com sucesso.'));
            return $this->redirect(array('controller' => 'providers','action' => 'dashboard', $id));

        }
        
        
        $optionsWeekDays = $this->initWeekDays();
        $selectedWeekDays = $this->getSelectedWeekdays($id, $serviceTypeId);
        
        $name = $this->getServiceTypeName($serviceTypeId);

        $this->request->data['ProviderWeekdays']['id'] = $id;
        $this->request->data['ProviderWeekdays']['service_type_id'] = $serviceTypeId;
        $this->set(compact('id', 'serviceTypeId', 'name', 'optionsWeekDays', 'selectedWeekDays'));
        
    }
    
    public function update_service_types($id=null)
    {
        $checkedId = $this->initId($id, 'Provider', 'id', 'id');
        
        if (!$this->Provider->exists($checkedId)) {
            throw new NotFoundException('Provider inválido');
        }
        
        $this->setMarketplaceNameAndProviderId($checkedId);

        
        if ($this->request->is(array('post', 'put'))) {
            if (!empty($this->request->data['ServiceType']['ServiceType'])) {
                if (FALSE !== array_search($this->EjqGeneralContractorServiceTypeId, $this->request->data['ServiceType']['ServiceType'])) {
                    $marketplaceServiceTypes = $this->Provider->Marketplace->listServiceTypes($this->EjqMarketplaceId);
                    $this->request->data['ServiceType']['ServiceType'] = array_keys($marketplaceServiceTypes);
                }
            } else {
                $this->request->data['ServiceType'] = [];
            }
            if ($this->Provider->save($this->request->data)) {
                $this->Flash->success(__('Os serviços foram alterados'));
                $url = Router::url([
                    'controller' => 'providers',
                    'action' => 'dashboard',
                    $checkedId,
                    'tab' => 'my_info',
                    '#' => 'breadcrumb'
                ]);             
                
                return $this->redirect($url);
            } else {
                $this->Flash->danger(__('Não foi possível alterar os serviços do provider. Favor tentar novamente.'));
            }
        }
        
        
        $providerServiceTypes = $this->Provider->listServiceTypes($checkedId);
        $this->set('selectedServiceTypes', array_keys($providerServiceTypes));
            
        $marketplaceServiceTypes = $this->Provider->Marketplace->listServiceTypes($this->EjqMarketplaceId);
        $this->set('optionsServiceTypes', $marketplaceServiceTypes);
        
        $this->request->data['Provider']['id'] = $checkedId;
        
        
    }
    
    public function update_online_status($id=null, $newStatus=null)
    {
        $this->Provider->id = $id;
        if ($newStatus == CRITERION_ONLINE) {
            $this->Provider->saveField(CRITERION_ONLINE, '1');
            $this->Flash->success(__('Status alterado para online.'));
        } else if ($newStatus == 'offline') {
            $this->Provider->saveField(CRITERION_ONLINE, '0');
            $this->Flash->success(__('Status alterado para offline.'));
        } else {
            $this->Flash->danger(__('Não foi possível alterar o status. Favor tentar novamente.'));
        }

        return $this->redirect(array('controller' => 'providers','action' => 'dashboard', $id));
        
    }
    
    
    
    private function checkWeekdays($id, $serviceType, &$lackWeekdays, &$existsWeekdays)
    {
        
        $weekdays = $this->Provider->getWeekdays($id, $serviceType['ProvidersServiceType']['service_type_id']);
        if (is_null($weekdays)) {
            $lackWeekdays[$serviceType['id']] = array(
                'id' => $serviceType['id'],
                'name' => $serviceType['name'],
                );
        } else {
            $existsWeekdays[$serviceType['id']] = array(
                'id' => $serviceType['id'],
                'name' => $serviceType['name'],
                );
        }
        
        
    }
    
    private function checkIfProviderExists($id, Provider $Provider, ComponentCollection $Components)
    {
        $checkedId = $this->initId($id, 'Provider', 'id', 'id');

        if (!$Provider->exists($checkedId)) {
            throw new NotFoundException('Provider inválido');
        }
        
        return $checkedId;
        
                
    }
    
    private function checkIfIsContractor($id)
    {
        $options = array(
            'conditions' => array(
                'Provider.' . $this->Provider->primaryKey => $id,
                ),
            'fields' => array('id', 'meta_provider_id'),
            'contain' => array(),
        );
        
        $providerData = $this->Provider->find('first', $options);
        
        if (!empty($providerData['Provider']['meta_provider_id'])) {
            if($providerData['Provider']['meta_provider_id'] == $this->EjqContractorMetaProviderId) {
                return true;
            }
        }
        return false;
        
    }
    
    private function checkIfIsEstimator($id)
    {
        $options = array(
            'conditions' => array(
                'Provider.' . $this->Provider->primaryKey => $id,
                ),
            'fields' => array('id', 'meta_provider_id'),
            'contain' => array(),
        );
        
        $providerData = $this->Provider->find('first', $options);
        
        if (!empty($providerData['Provider']['meta_provider_id'])) {
            if($providerData['Provider']['meta_provider_id'] == $this->EjqEstimatorMetaProviderId) {
                return true;
            }
        }
        return false;
        
    }
    
    private function checkOnline($id, $serviceType)
    {
        
        $this->Provider->id = $id;
        $online = $this->Provider->field(CRITERION_ONLINE);
        if ($online) {
            return true;
            
        } else {
            return false;
        }
        
    }
    
    private function ejqFetchProfileData($id, Provider $Provider)
    {
        $options = array(
            'conditions' => array(
                'Provider.' . $Provider->primaryKey => $id,
                ),
            'fields' => array(
                'Provider.name',
                'Provider.user_id',
                'Provider.qualified',
                'Provider.good_standing',
                ),
            'contain' => array(
                'ServiceType' => array(
                    'fields' => array('id', 'name', 'online_criterion', 'weekdays_criterion', 'ProvidersServiceType.service_type_id'),
                ),
                'Contractor',
                'User' => ['name', 'email'],
            ),
        );
        
        $providerData = $Provider->find('first', $options);
        return $providerData;        
    }
    
    
    
    private function ejqListJobCategories($id)
    {
        
        $options = array(
            'conditions' => array(
                'id' => $id,
                ),
            'fields' => array('id'),
            'contain' => array(
                'ServiceType' => array(
                    'fields' => array('id', 'name'),
                ),
            ),
        );
        
        
        
        $providerData = $this->Provider->find('all', $options);
        
        
        $data = $providerData['0'];
        
        $result = array();
        foreach ($data['ServiceType'] as $key => $serviceType) {
            $result[$serviceType['id']] = $serviceType['name'];
        }
        
        return $result;
        
    }
    
    private function ejqEstimatorDashboard($id)
    {
        $this->set('hereTitle', __('Project Developer\'s Dashboard'));
        $this->setActiveTab();

        $providerData = $this->ejqFetchProfileData($id, $this->Provider);
        $this->verifyCredentialsAndRedirectIfNeeded($providerData['Provider']['user_id']);
        $this->setServiceTypesProfileViewVariables($id, $providerData);
        $this->handleEstimatorDemands($id, $this->Provider);
        $this->set('provider', $providerData);
        $this->render('estimator_dashboard');

    }

    
    private function ejqContractorDashboard($id)
    {
        $this->set('hereTitle', __('Contractor\'s Dashboard'));
        $this->setActiveTab();
        $providerData = $this->ejqFetchProfileData($id, $this->Provider);
        $this->verifyCredentialsAndRedirectIfNeeded($providerData['Provider']['user_id']);
        $this->setServiceTypesProfileViewVariables($id, $providerData);
        $this->handleContractorDemands($id, $this->Provider);
        $rawInvoices = $this->Provider->getInvoices($id, [EJQ_INVOICE_STATUS_PAID, EJQ_INVOICE_STATUS_SENT]);
        $invoices = $this->processInvoicesRawData($rawInvoices);
        $this->set('invoices', $invoices);
        $providerData['Contractor']['0']['services_list_description'] = $this->request->data['Contractor']['0']['services_list_description'];
        if (!empty($providerData['Contractor']['0']['municipality_id'])) {
            $this->Provider->Contractor->Municipality->id = $providerData['Contractor']['0']['municipality_id'];
            $providerData['Contractor']['0']['municipality_name'] = $this->Provider->Contractor->Municipality->field('name');
        }
        
        if (empty($providerData['Contractor']['0']['contact_name'])) {
            $providerData['Contractor']['0']['contact_name'] = $providerData['User']['name'];
        }
        if (empty($providerData['Contractor']['0']['contact_email'])) {
            $providerData['Contractor']['0']['contact_email'] = $providerData['User']['email'];
        }
        $this->request->data = ['Contractor' => $providerData['Contractor']['0'], 'Provider' => $providerData['Provider']];
        $this->set('provider', $providerData);
        
        
        if ($this->EjqIsContractor) {
            if(!$providerData['Provider']['qualified']) {
                $this->set("notQualified", true);
                //$this->Flash->danger(__("You can't bid on any tender. Please contact EasyJobQuote."));
                
            } elseif (empty($providerData['ServiceType'])) {
                $url = Router::url([
                    'controller' => 'providers',
                    'action' => 'dashboard',
                    $id,
                    'tab' => 'my_info',
                    '#' => 'update_service_types'
                ]);             
                $label = __("define");
                $hrefText = "<a href='$url' class=''>$label</a>";
                $message = sprintf(__('Please %s what types of jobs your company can do.', $hrefText));
                $this->set("activeTab", "my_info");
                $this->set("showServices", true);
                $this->Flash->call_to_action($message);
            } elseif (empty($providerData['Contractor']['0']['contact_address'])) {

                $url = Router::url([
                    'controller' => 'providers',
                    'action' => 'dashboard',
                    $id,
                    'tab' => 'my_info',
                    '#' => 'update_contact_info'
                ]);             
                $label = __("update");
                $hrefText = "<a href='$url' class=''>$label</a>";
                $message = sprintf(__('Please %s your contact info so we can reach you if necessary.', $hrefText));
                $this->set("activeTab", "my_info");
                $this->set("showContactInfo", true);

                $this->Flash->call_to_action($message);
            } elseif (empty($providerData['Contractor']['0']['years_in_business'])) {
                $url = Router::url([
                    'controller' => 'providers',
                    'action' => 'dashboard',
                    $id,
                    'tab' => 'my_info',
                    '#' => 'update_company_disclosure'
                ]);             
                $label = __("update");
                $hrefText = "<a href='$url' class=''>$label</a>";
                $message = sprintf(__('Please %s the information about your business so we can know more about your services.', $hrefText));
                $this->set("activeTab", "my_info");
                $this->set("showCompanyDisclosure", true);

                $this->Flash->call_to_action($message);
            } 
            $titleBox['h1'] = sprintf(__('Welcome, %s!'), $providerData['Provider']['name']);
            $this->set('titleBox', $titleBox);
        } else {
                $this->set("activeTab", "my_info");
        }
        
        $this->set('municipalities', $this->Marketplace->Demand->Tender->Municipality->find('list'));

        $this->set('hereTitle', __('Contractor Dashboard'));
        $this->set('breadcrumbNode', null);
        $this->render('contractor_profile');

    }

    private function formatWeekdaysAsBinary($weekDays)
    {
            $decimalValue = 0;
            foreach ($weekDays as $key => $value) {
                $decimalValue += (int)$value;
            }
            
            $stringBin = decbin($decimalValue);
            
            for ($i=strlen($stringBin); $i < 7; $i++)
            {
                $stringBin ="0$stringBin";
            }
            
            return $stringBin;
        
    }
    
    private function getJustAvailableTenders($id, $demands)
    {
        $providerServiceTypes = $this->Provider->listServiceTypes($id);
        $serviceTypesIds = array_keys($providerServiceTypes);

        $availableDemands = array();
        
        foreach ($demands as $key => $tenderInfo) {
            if($tenderInfo['Demand']['status'] == EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS) {
                if(!empty($tenderInfo['ServiceType'])) {
                    $services = $tenderInfo['ServiceType'];
                    $servicesCounter = count($services);
                    foreach ($services as $key => $serviceData) {
                        $thisServiceId = $serviceData['id'];
                        if (in_array($thisServiceId, $serviceTypesIds)) {
                            $servicesCounter--;
                        }

                    }
                    if ($servicesCounter == 0) {

                        $alreadyBid = false;
                        $providerBids = array();
                        if ((!empty($tenderInfo['Bid'])) && (is_array($tenderInfo['Bid']))) {
                            $bids = $tenderInfo['Bid'];
                            foreach ($bids as $key => $bidData) {
                                $thisBidProviderId = $bidData['provider_id'];
                                if ($id == $thisBidProviderId) {
                                    if ($bidData['status'] == EJQ_BID_STATUS_SUBMITTED || $bidData['status'] == EJQ_BID_STATUS_CHOSEN) {
                                        $alreadyBid = true;
                                    }
                                    $providerBids[] = $bidData;
                                }
                            }
                        }

                        $tenderInfo['Bid'] = $providerBids;
                        $tenderInfo['Tender']['already_bid'] = $alreadyBid;


                        $availableDemands[] = $tenderInfo;
                        }
                }
                
            }
            
        }
        return $availableDemands;
        
        
    }
    
    private function getMarketplaceInfo($id)
    {
        
        $data = $this->Provider->Marketplace->getHeaderInfo($id);
        return $data;
        
        
    }
    
    private function getSelectedWeekdays($providerId, $serviceTypeId)
    {
        
        $weekdays = $this->Provider->getWeekdays($providerId, $serviceTypeId);
        
        $binary = bindec($weekdays);
        $selectedWeekDays = array();
        for ($i=64; $i > 0; $i=$i/2) {
            $value = $binary & $i;
            if ($value) {
                $selectedWeekDays[] = $value;
            }
        }
        
        return $selectedWeekDays;
    }
    
    private function getServiceTypeName($id)
    {
        $this->loadModel('ServiceType');
        $this->ServiceType->id = $id;
        $name =  $this->ServiceType->field('name');
        
        return $name;
    }
    
    private function handleContractorDemands($id, Provider $Provider)
    {
        $Provider->id = $id;
        $qualified = $Provider->field('qualified');
        if ($qualified) {
            $Provider->Demand->openToBidsAllTendersScheduledToBiddingToday($this->EjqMarketplaceId);
            $Provider->Demand->closeToBidsAllTendersScheduledToClosingToday($this->EjqMarketplaceId);
            $tendersOpenToBids = $Provider->Demand->getMarketplaceOpenToBids($this->EjqMarketplaceId);
            $availableTenders = $this->getJustAvailableTenders($id, $tendersOpenToBids);
            $currentJobs = $Provider->getCurrentJobs($id, $this->EjqMarketplaceId);
        } else {
            $availableTenders = [];
            $currentJobs = [];
        }
        $this->set('currentJobs', $currentJobs);
        $this->set('availableTenders', $availableTenders);
    }
    
    private function handleEstimatorDemands($id, Provider $Provider)
    {
        $requestsToTender = $Provider->Demand->getEstimatorAssignedEstimations($id);
        if (!empty($requestsToTender['Schedule']['0'])) {
            App::uses('CakeTime', 'Utility');
            
            $scheduleData = $requestsToTender['Schedule']['0'];
            $visitDate = CakeTime::format($scheduleData['schedule_period_begin'], '%b %d, %Y');
            $endVisitDate = CakeTime::format($scheduleData['schedule_period_end'], '%b %d, %Y');
            $windowBegin = CakeTime::format($scheduleData['schedule_period_begin'], '%I:%M %p');
            $windowEnd = CakeTime::format($scheduleData['schedule_period_end'], '%I:%M %p');
            if (($windowBegin == $windowEnd) && ($visitDate == $endVisitDate)):
                $period = sprintf(__('%s, %s'), $visitDate, $windowBegin); 
            else:
                if ($visitDate == $endVisitDate):
                    $period = sprintf(__('%s,  from %s to %s'), $visitDate, $windowBegin, $windowEnd);
                else: 
                    $period = sprintf(__('From %s, %s to %s,  %s'), $visitDate, $windowBegin, $endVisitDate, $windowEnd);
                endif;
            endif;

            $requestsToTender['Schedule']['period'] = $period;
        }
        
        
        $this->set('requestsToTender', $requestsToTender);
        $openTenders = $Provider->Demand->getEstimatorOpenTenders($id); 
        $this->set('openTenders', $openTenders);
    }
    
    private function initIds(&$id, &$serviceTypeId)
    {
        $id = $this->initId($id, 'Provider', 'id', 'id');
        $serviceTypeId = $this->initId($serviceTypeId, 'ServiceType', 'id', 'service_type_id');
    }
        
    private function initWeekDays()
    {

        $weekDays = array('64' => __('Domingo'),
            '32' => __('Segunda-feira'), 
            '16' => __('Terça-feira'),
            '8' => __('Quarta-feira'), 
            '4' => __('Quinta-feira'), 
            '2' => __('Sexta-feira'), 
            '1' => __('Sábado'));
        
        
        return $weekDays;
    }
    
    
    
    private function listAvailableServiceTypesForProvider($id)
    {
        $providerData = $this->Provider->find(
                'first',
                array(
                    'fields' => array('Provider.marketplace_id'),
                    'conditions' => array(
                        'Provider.id' => $id,
                        ),
                    
                    )
                );
        
        $this->loadModel('Marketplace');
        
        $marketplaceData = $this->Marketplace->getServiceTypesByMarketplaceId($providerData['Provider']['marketplace_id']);

        $servicesData = $marketplaceData['0'];
        $result = array();
        foreach ($servicesData['ServiceType'] as $key => $serviceType) {
            $result[$serviceType['id']] = $serviceType['name'];
        }
        
        return $result;
               
    }
    
    private function processInvoicesRawData($raw)
    {
        $processed = [];
        foreach ($raw as $key => $invoice) {
            $now = time();
            $dueDate = strtotime($invoice['Invoice']['due_date']);
            $dateDiff = $now - $dueDate;
            $daysDiff =  floor($dateDiff / (60 * 60 * 24));
            
            if ($daysDiff <= 0 || $invoice['Invoice']['status'] == EJQ_INVOICE_STATUS_PAID) {
                $overdueStatus = "normal";
                $overdueClass = "";
                $daysDiff = 0;
            }elseif ($daysDiff > 0 && $daysDiff <=30) {
                $overdueStatus = "alert";
                $overdueClass = "alert-info";
            } elseif ($daysDiff <= 60) {
                $overdueStatus = "alert";
                $overdueClass = "alert-warning";
            } else {
                $overdueStatus = "alert";
                $overdueClass = "alert-danger";
            }
            
            
            $invoice['Invoice']['days_due'] = $daysDiff;
            $invoice['Invoice']['overdue_status'] = $overdueStatus;
            $invoice['Invoice']['overdue_class'] = $overdueClass;
            $processed[$key] = $invoice;
        }
        
        return $processed;
    }
    
    private function setMarketplaceNameAndProviderId($id)
    {
        
        $this->Provider->id = $id;
        $providerData = $this->getProviderInfo($id);
        
        $marketplaceData = $this->getMarketplaceInfo($this->Provider->field('marketplace_id'));
        
        $provider = array('Provider' => $providerData['Provider'], 'Marketplace' => $marketplaceData['Marketplace']);
        $this->set(compact('provider'));
        
    }
    
    private function getProviderInfo($id)
    {
        $this->Provider->id = $id;
        $name = $this->Provider->field('name');

        $data = array(
            'Provider' => array(
                'id' => $id,
                'name' => $name,
                )
            );
                
        return $data;
        
        
    }
    
    private function setServiceTypesProfileViewVariables($id, $data)
    {
        $lackWeekdays = array(); 
        $existsWeekdays = array(); 
        $onlineStatus = array(); 
        foreach ($data['ServiceType'] as $key => $serviceType) {
            if ($serviceType['weekdays_criterion'] === true) {
                $this->checkWeekdays($id, $serviceType, $lackWeekdays, $existsWeekdays);
            }
            if ($serviceType['online_criterion'] === true) {
                $onlineStatus[$serviceType['id']] = $this->checkOnline($id, $serviceType);
            }
        }
        $this->set(compact('lackWeekdays', 'existsWeekdays', 'onlineStatus'));
        
        $providerServiceTypes = $this->ejqListJobCategories($id);
                
        $this->set('selectedServiceTypes', array_keys($providerServiceTypes));
        
        $marketplaceServiceTypes = $this->Provider->Marketplace->listServiceTypes($this->EjqMarketplaceId);
        $this->set('optionsServiceTypes', $marketplaceServiceTypes);
        
        $servicesList = implode(", ", $providerServiceTypes);
        $this->set('servicesList', $servicesList);
        $this->request->data['Contractor']['0']['services_list_description'] = $servicesList;
        
        $this->request->data['Provider']['id'] = $id;
        
        
    }
    
    private function verifyCredentialsAndRedirectIfNeeded($profileUserId)
    {
        if((!$this->canAccessAdm) && ($this->uid != $profileUserId)) {
            $this->Flash->danger(__('Você não está autorizado a acessar esse perfil.'));
            return $this->redirect(array('controller' => 'main', 'action' => 'index'));
        }
        
        
    }
    

}
