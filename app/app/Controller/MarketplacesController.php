<?php

App::uses('AppController', 'Controller');

class MarketplacesController extends AppController
{

    public $uses = array();

    public function beforeFilter() 
    {
        $this->Auth->allow(array(
            'dashboard',
            'become_a_member',
            'post_a_job',
            'create_site_admin',
            'register_consumer', 
            'register_estimator',
            'post_success',
            ));

        parent::beforeFilter();
    }
        
    public function dashboard() 
    {
        $this->set('hereTitle', __('Admin Dashboard'));
        $this->setActiveTab();
        
        $this->verifyAdministratorCredentialsAndRedirectIfNeeded();
        
        $openDemands = $this->Marketplace->Demand->getMarketplaceOpenRequests($this->EjqMarketplaceId);
        $this->set('openDemands', $openDemands);
        $openTenders = $this->Marketplace->Demand->getMarketplaceOpenTenders($this->EjqMarketplaceId);
        $this->set('openTenders', $openTenders);
        
        
        $this->Marketplace->Demand->openToBidsAllTendersScheduledToBiddingToday($this->EjqMarketplaceId);
        $this->Marketplace->Demand->closeToBidsAllTendersScheduledToClosingToday($this->EjqMarketplaceId);
        $openToBids = $this->Marketplace->Demand->getMarketplaceOpenToBids($this->EjqMarketplaceId);
        $this->set('openToBids', $openToBids);
        $closedToBids = $this->Marketplace->Demand->getMarketplaceClosedToBids($this->EjqMarketplaceId);
        $this->set('closedToBids', $closedToBids);
        $jobs = $this->Marketplace->Demand->getMarketplaceJobs($this->EjqMarketplaceId);
        $this->set('jobs', $jobs);
        $rawInvoices = $this->Marketplace->Demand->Invoice->getFromMarketplace($this->EjqMarketplaceId);
        $invoices = $this->processInvoicesRawData($rawInvoices);
        $this->set('invoices', $invoices);
        
        $rawContractors = $this->Marketplace->Provider->find('all', [
                    'fields' => ['id', 'name', 'qualified'],
                    'conditions' => [
                        'marketplace_id' => $this->EjqMarketplaceId,
                        'meta_provider_id' => $this->EjqContractorMetaProviderId,
                        //'qualified' => true,
                        'active' => true,
                        'removed' => false,
                    ],
                    'order' => ['Provider.modified DESC'],
                    'contain' => ['Contractor.name'],
                ]);
        $contractors = [];
        foreach ($rawContractors as $keyProvider => $contractor) {
            $rating = $this->Marketplace->Provider->getRating($contractor['Provider']['id']);
            $totalJobs = $this->Marketplace->Provider->getTotalJobs($contractor['Provider']['id']);
            $totalBids = $this->Marketplace->Provider->getTotalBids($contractor['Provider']['id']);
            
            $ratedProvider = array_merge($contractor['Provider'], $rating['0']);
            $contractor['Provider'] = $ratedProvider;
            $contractor['Provider']['total_jobs'] = $totalJobs['0']['total_jobs'];
            $contractor['Provider']['total_bids'] = $totalBids['0']['total_bids'];
            
            $contractors[$keyProvider] = $contractor;
            
        }
        $this->set('contractors', $contractors);

        $estimators = $this->Marketplace->Provider->find('all',
                array(
                    'fields' => array('id', 'name', 'qualified'),
                    'conditions' => array(
                        'marketplace_id' => $this->EjqMarketplaceId,
                        'meta_provider_id' => $this->EjqEstimatorMetaProviderId,
                        //'qualified' => true,
                        'active' => true,
                        'removed' => false,
                    ),
                    'contain' => array(),
                ));
        $this->set('estimators', $estimators);

        $consumers = $this->Marketplace->Consumer->find('all',
                array(
                    'fields' => array('id', 'name'),
                    'conditions' => array(
                        'marketplace_id' => $this->EjqMarketplaceId,
                        'active' => true,
                        'removed' => false,
                    ),
                    'contain' => array(),
                ));
        $this->set('consumers', $consumers);
        
    }
    
    public function become_a_member($idFromGet=null)
    {

        
        $this->set('hereTitle', __('Become a member'));
        
        $titleBox['h1'] = __('Tell us about your company');
        $titleBox['h2'] = __('Finding new business is tough, we can help');
        $this->set('titleBox', $titleBox);
        
        $this->set('municipalities', $this->Marketplace->Demand->Tender->Municipality->find('list'));
        

        $marketplaceId = $this->initIdInCaseOfPost($idFromGet, 'Provider', 'marketplace_id');
        if(is_null($marketplaceId)) {
            $marketplaceId = $this->EjqMarketplaceId;
        }
        
        $knownUser = $this->isKnownUser();
        if ($knownUser) { // se usuário está logado
            if ($this->checkIfUserCanBeRegisteredAsProvider($marketplaceId, $this->uid)) {
                $this->registerKnownContractorAndRedirect($this->uid, $marketplaceId);
            } else {
                $this->Flash->danger(__('Você já é cadastrado nesse mercado'));
                return $this->redirect(array('controller' => 'main', 'action' => 'index'));
            }
        } else { // se não está logado
            //     se é post
            if ($this->request->is(array('post', 'put'))) {
                try {
                    $this->processPostContractorRegister($marketplaceId, $this->request->data);
                } catch (Exception $ex) {
                    throw $ex;
                }
                $this->Flash->success(__('Cadastro feito com sucesso. Por favor confira email enviado para criar uma senha e ativar a conta.'));
                return $this->redirect(array('controller' => 'marketplaces', 'action' => 'post_success', 'adm' => false));
                
            } else { //     se não é post 
                //         popula form
                $this->set('knownUser', false);
                $this->populateContractorRegisterForm($marketplaceId);
            }
        }
        $this->set('knownUser', $knownUser);

    }
    
    public function post_a_job($idFromGet=null)
    {
        $this->set('hereTitle', __('Post a Job'));
        
        $titleBox['h1'] = __('Post a Job');
        $titleBox['h2'] = __('Start your next home improvement project today');
        $this->set('titleBox', $titleBox);
        
        $this->set('municipalities', $this->Marketplace->Demand->Tender->Municipality->find('list'));

        
        $marketplaceId = $this->initIdInCaseOfPost($idFromGet, 'Consumer', 'marketplace_id');
        if(is_null($marketplaceId)) {
            $marketplaceId = $this->EjqMarketplaceId;
        }
        $knownUser = $this->isKnownUser();
        if ($knownUser) { // se usuário está logado
            if ($this->checkIfUserCanBeRegisteredAsConsumer($marketplaceId, $this->uid)) {
                $this->registerKnownConsumerAndRedirect($this->uid, $marketplaceId);
            } else {
                if (!$this->EjqIsConsumer) {
                    if (!$this->canAccessAdm) {
                        $this->Flash->danger(__("You can't be registered as a Home Owner."));
                        return $this->redirect(array('controller' => 'marketplaces', 'action' => 'dashboard'));
                    } else {
                        $this->Flash->danger(__("You can't be registered as a Home Owner."));
                        return $this->redirect(array('controller' => $this->profileController, 'action' => 'dashboard'));
                    }
                }
            }
        } 
        //     se é post
        if ($this->request->is(array('post', 'put'))) {
            $this->request->data['Consumer']['marketplace_id'] = $this->EjqMarketplaceId;
            $this->request->data['Consumer']['meta_consumer_id'] = $this->EjqHomeOwnerMetaConsumerId;
            $this->processPostAJobPost($marketplaceId, $this->request->data);
            $this->Flash->success(__('Your request was created successfully.'));
            if ($knownUser) {
                $consumerId = $this->request->data['Consumer']['id'];
                return $this->redirect(array('controller' => 'consumers', 'action' => 'dashboard', $consumerId));
                
            } else {
                return $this->redirect(array('controller' => 'marketplaces', 'action' => 'post_success'));
            }

        } else { //     se não é post 
            //         popula form
            $this->populateConsumerRegisterForm($marketplaceId);
        }
        
        $this->set('knownUser', $knownUser);
        
        
    }
    
    public function create_site_admin($idFromGet=null)
    {
        if(!$this->canAccessAdm) {
            $this->Flash->danger(__('Você não está autorizado a acessar essa página.'));
            return $this->redirect(array('controller' => 'main', 'action' => 'index'));
        }
        
        $marketplaceId = $this->initIdInCaseOfPost($idFromGet, 'Administrator', 'marketplace_id');
        
        if ($this->request->is(array('post', 'put'))) {
            $this->postAdministratorRegisterAndRedirect();
        } else {
            $this->set('knownUser', false);
            $this->populateAdministratorRegisterForm($marketplaceId);
        }
        
    }
    
    public function register_consumer($idFromGet=null)
    {
        $this->set('hereTitle', __('Home Owner Sign Up'));
        $titleBox['h1'] = __('Create an account to request services');
        $this->set('titleBox', $titleBox);

        $marketplaceId = $this->initIdInCaseOfPost($idFromGet, 'Consumer', 'marketplace_id');
        if ($this->isKnownUser()) { // se usuário está logado
            if ($this->checkIfUserCanBeRegisteredAsConsumer($marketplaceId, $this->uid)) {
                $this->registerKnownConsumerAndRedirect($this->uid, $marketplaceId);
            } else {
                $this->Flash->info(__('You are an user. Please login'));
                return $this->redirect(array('controller' => 'main', 'action' => 'index'));
            }
        } else { // se não está logado
            //     se é post
            if ($this->request->is(array('post', 'put'))) {
                $this->processPostConsumerRegister($marketplaceId, $this->request->data);
            } else { //     se não é post 
                //         popula form
                $this->set('knownUser', false);
                $this->populateConsumerRegisterForm($marketplaceId);
            }
        }
    }
        
    public function register_estimator($idFromGet=null)
    {
//        if(!$this->canAccessAdm) {
//            $this->Flash->danger(__('Você não está autorizado a acessar essa página.'));
//            return $this->redirect(array('controller' => 'main', 'action' => 'index'));
//        }
        
        $marketplaceId = $this->initIdInCaseOfPost($idFromGet, 'Provider', 'marketplace_id');
        
        if ($this->isKnownUser()) { // se usuário está logado
            
            if ($this->checkIfUserCanBeRegisteredAsProvider($marketplaceId, $this->uid)) {
                $this->registerKnownEstimatorAndRedirect($this->uid, $marketplaceId);
            } else {
                $this->Flash->danger(__('Você já é fornecedor nesse mercado'));
                return $this->redirect(array('controller' => 'main', 'action' => 'index'));
            }
        } else {
            if ($this->request->is(array('post', 'put'))) {
                $this->postEstimatorRegisterAndRedirect();
            } else {
                $this->set('knownUser', false);
                $this->populateEstimatorRegisterForm($marketplaceId);
            }
        }
        
    }
    
    public function post_success()
    {
    }
        
    private function checkIfUserCanBeRegisteredAsAdministrator($marketplaceId, $userId)
    {
            $provider = $this->Marketplace->Provider->getProviderByMarketplaceAndUserId($marketplaceId, $userId);
            $consumer = $this->Marketplace->Consumer->getConsumerByMarketplaceAndUserId($marketplaceId, $userId);
            $administrator = $this->Marketplace->Administrator->getAdministratorByMarketplaceAndUserId($marketplaceId, $userId);
            if ((empty($provider) && (empty($consumer)))) {
                return true;
            } else {
                return false;
            }
        
    }
    
    private function checkIfUserCanBeRegisteredAsConsumer($marketplaceId, $userId)
    {
            $provider = $this->Marketplace->Provider->getProviderByMarketplaceAndUserId($marketplaceId, $userId);
            $consumer = $this->Marketplace->Consumer->getConsumerByMarketplaceAndUserId($marketplaceId, $userId);
            if ((empty($provider) && (empty($consumer)))) {
                return true;
            } else {
                return false;
            }
        
    }
    
    private function checkIfUserCanBeRegisteredAsProvider($marketplaceId, $userId)
    {
            $provider = $this->Marketplace->Provider->getProviderByMarketplaceAndUserId($marketplaceId, $userId);
            $consumer = $this->Marketplace->Consumer->getConsumerByMarketplaceAndUserId($marketplaceId, $userId);            
            if ((empty($provider) && (empty($consumer)))) {
                return true;
            } else {
                return false;
            }
        
    }
    
    
    private function checkMarketplace($marketplaceId)
    {
        if ($this->Marketplace->exists($marketplaceId)) {
            return true;
        }
        
        return false;
        
    }
    
    private function createAdministratorIfNeeded($userId, $data)
    {
        
        $result = array('administratorId' => null, 'newUser' => true);
        $administrator = $this->Marketplace->Administrator->findByUserIdAndMarketplaceId($userId, $this->EjqMarketplaceId);
        if (!isset($administrator['Administrator'])) {
            $this->Marketplace->Administrator->create();
            
            $result['administratorId'] = $this->saveNewAdministrator($data);
            $result['newUser'] = true;
            
        } else {
            $result['administratorId'] = $administrator['Administrator']['id'];
            $result['newUser'] = false;
        }
        return $result;
    }
    
    private function createConsumerIfNeeded($marketplaceId, $userId)
    {
        
        $data = $this->fetchNewConsumerData($marketplaceId, $userId);

        $result = array('consumerId' => null, 'newUser' => true);
        //$this->loadModel('Consumer');
        $consumer = $this->Marketplace->Consumer->findByUserIdAndMarketplaceId($userId, $marketplaceId);
        if (!isset($consumer['Consumer'])) {
            $this->Marketplace->Consumer->create();
            
            $result['consumerId'] = $this->saveNewConsumer($data);
            $result['newUser'] = true;
            if (isset($this->request->data['Consumer']['phone'])) {
                $this->Marketplace->Consumer->saveField('phone', $this->request->data['Consumer']['phone']);
            }
            
        } else {
            $result['consumerId'] = $consumer['Consumer']['id'];
            $result['newUser'] = false;
        }
        
        
        return $result;
    }
    
    private function createContractorIfNeeded($userId, $data)
    {
        
        $result = array('providerId' => null, 'newUser' => true);
        $provider = $this->Marketplace->Provider->findByUserIdAndMetaProviderId($userId, $this->EjqContractorMetaProviderId);
        if (!isset($provider['Provider'])) {
            $this->Marketplace->Provider->create();
            $result['providerId'] = $this->saveNewProvider($data);
            $result['newUser'] = true;
            
        } else {
            $result['providerId'] = $provider['Provider']['id'];
            $result['newUser'] = false;
        }
        
        return $result;
    }
    
    private function createEstimatorIfNeeded($userId, $data)
    {
        
        $result = array('providerId' => null, 'newUser' => true);
        $provider = $this->Marketplace->Provider->findByUserIdAndMetaProviderId($userId, $this->EjqEstimatorMetaProviderId);
        if (!isset($provider['Provider'])) {
            $this->Marketplace->Provider->create();
            
            $result['providerId'] = $this->saveNewProvider($data);
            $result['newUser'] = true;
            
        } else {
            $result['providerId'] = $provider['Provider']['id'];
            $result['newUser'] = false;
        }
        return $result;
    }
    
    
    private function createUser($email, $userName)
    {
        $this->loadModel('User');
        $registerComponent = $this->Components->load('Register');
        $registerResult = $registerComponent->register($email, $userName, $this->User);
        if($registerResult['sendToken']) {
            $this->loadmodel('Token');
            $senderComponent = $this->Components->load('SendToken');
            $senderComponent->send($email, 
                    $userName, 
                    $registerResult['userId'],
                    $this->Token
                    );
        }
        
        return $registerResult;
        
    }
    
    private function discoverUserName($userId)
    {
        $this->loadModel('User');
        $this->User->id = $userId;
        return $this->User->field('name');
    }
        
    private function fetchNewAdministratorData($marketplaceId, $userId)
    {

        $name = $this->discoverUserName($userId);
        $data = array(
            'Administrator' => array(
                'marketplace_id' => $marketplaceId,
                'user_id' => $userId,
                'name' => $name,
            ),
        );
        return $data;
        
        
    }
    
    private function fetchNewConsumerData($marketplaceId, $userId)
    {

        $name = $this->discoverUserName($userId);
        $metaConsumers = $this->Marketplace->getMetaConsumersByMarketplaceId($marketplaceId);
        $data = array(
            'Consumer' => array(
                'marketplace_id' => $marketplaceId,
                'meta_consumer_id' => $metaConsumers['MetaConsumer']['0']['id'],
                'user_id' => $userId,
                'name' => $name,
            ),
        );
        return $data;
        
        
    }
    
    private function fetchNewContractorData($marketplaceId, $userId)
    {

        $name = $this->discoverUserName($userId);
        
        $data = array(
            'Provider' => array(
                'marketplace_id' => $marketplaceId,
                'meta_provider_id' => $this->EjqContractorMetaProviderId,
                'user_id' => $userId,
                'name' => $name,
            ),
        );
        
        if (!empty($this->request->data['Contractor'])) {
            $data['Contractor'] = $this->request->data['Contractor'];
            $data['Contractor']['marketplace_id'] = $this->request->data['Marketplace']['id'];
        }

        return $data;
        
        
    }
    
    private function fetchNewEstimatorData($marketplaceId, $userId)
    {

        $name = $this->discoverUserName($userId);
        
        $data = array(
            'Provider' => array(
                'marketplace_id' => $marketplaceId,
                'meta_provider_id' => $this->EjqEstimatorMetaProviderId,
                'user_id' => $userId,
                'name' => $name,
            ),
        );
        
        return $data;
        
        
    }
    
    
    private function isKnownUser()
    {
        if ($this->uid) {
            return true;
        } else {
            return false;
        }

    }
    
    private function listServiceTypesOfMarketplace($marketplaceId)
    {
        $marketplaceData = $this->Marketplace->getServiceTypesByMarketplaceId($marketplaceId);

        $data = $marketplaceData['0'];
        $result = array();
        foreach ($data['ServiceType'] as $serviceType) {
            $result[$serviceType['id']] = $serviceType['name'];
        }
        
        return $result;
        
    }
    
    private function manageAdministratorCreation($marketplaceId, $administratorUserId)
    {
        $data = $this->fetchNewAdministratorData($marketplaceId, $administratorUserId);

        //     cria site admin
        $resultAdministratorCreation = $this->createAdministratorIfNeeded($administratorUserId, $data);
        if (isset($resultAdministratorCreation['administratorId'])) {
            return $resultAdministratorCreation;
        } else {
            throw new NotFoundException(__('It was not possible to register the Site Admin.'));
        }
     }
    
    private function manageConsumerCreation($marketplaceId, $consumerUserId)
    {
        //     cria consumer
        $resultConsumerCreation = $this->createConsumerIfNeeded($marketplaceId, $consumerUserId);
        if (isset($resultConsumerCreation['consumerId'])) {
            $this->Flash->success(__('Cadastro feito com sucesso. Por favor confira email enviado para criar uma senha e ativar a conta.'));
            return $this->redirect(array('controller' => 'marketplaces', 'action' => 'post_success', 'adm' => false));
        } else {
            throw new NotFoundException(__('Não foi possível criar o novo Consumer.'));
        }
     }
    
    private function manageContractorCreation($marketplaceId, $providerUserId)
    {
        $data = $this->fetchNewContractorData($marketplaceId, $providerUserId);
        $data['Provider']['qualified'] = false;
        $data['Provider']['good_standing'] = true;

        //     cria provider
        $resultProviderCreation = $this->createContractorIfNeeded($providerUserId, $data);
        if (isset($resultProviderCreation['providerId'])) {
            return $resultProviderCreation;
        } else {
            throw new NotFoundException(__('It was not possible to register the Contractor.'));
        }
     }
    
    private function manageEstimatorCreation($marketplaceId, $providerUserId)
    {
        $data = $this->fetchNewEstimatorData($marketplaceId, $providerUserId);
        $data['Provider']['qualified'] = true;   // TEMPORARY while there is no qualification proccess

        //     cria provider
        $resultProviderCreation = $this->createEstimatorIfNeeded($providerUserId, $data);
        if (isset($resultProviderCreation['providerId'])) {
            return $resultProviderCreation;
        } else {
            throw new NotFoundException(__('It was not possible to register the Project Developer.'));
        }
     }
    
    private function manageJobCreation($marketplaceId, $providerUserId)
    {
        
        $consumerId = $this->request->data['Consumer']['id'];
        
        $this->Marketplace->Consumer->id = $consumerId;
                        
        $requestData = array('Request' => array(
                    'title' => "", 
                    'description' => $this->request->data['Request']['description'], 
                    //'preferred_visit_time' => $this->request->data['Request']['preferred_visit_time'], 
                    'marketplace_id' => $marketplaceId,
                    'consumer_id' => $consumerId,
                    'municipality_id' => $this->request->data['Request']['municipality_id'], 
                    )
            );
        
        $tenderData = array('Tender' => array(
                    'title' => "",
                    'marketplace_id' => $marketplaceId,
                    'municipality_id' => $this->request->data['Request']['municipality_id'],
                    //'demand_id' => $demandId,
                    )
            );

        $data = array(
            'Demand' => array(
                'marketplace_id' => $marketplaceId,
                'consumer_id' => $consumerId,
                'status' => EJQ_DEMAND_STATUS_REQUEST_OPEN,
            ),
            'Request' => $requestData,
            'Tender' => $tenderData,
        );
        $this->Marketplace->Demand->saveAssociated($data);
        
        $tenderInfo = $this->Marketplace->Demand->getTenderInfo($this->Marketplace->Demand->id);
        

        $tenderIdentification = $tenderInfo['Demand']['year'] . "-" . $tenderInfo['Demand']['franchise_label'] . "-" . sprintf('%04d', $tenderInfo['Tender']['id']);
        $tenderInfo['Tender']['tenderIdentification'] = $tenderIdentification;
        
        $this->Marketplace->Demand->Request->id = $tenderInfo['Request']['id'];
        $this->Marketplace->Demand->Request->saveField('title', $tenderInfo['Tender']['tenderIdentification']);
        
        $this->Marketplace->Demand->Tender->id = $tenderInfo['Tender']['id'];
        $this->Marketplace->Demand->Tender->saveField('title', $tenderInfo['Tender']['tenderIdentification']);

        return ($this->Marketplace->Demand->id);
     }
     
    private function manageUserCreation($email, $username)
    {
        $resultUserCreation= $this->createUser($email, $username);

        if ($resultUserCreation['status'] == REGISTER_STATUS_EXISTING || $resultUserCreation['status'] == REGISTER_STATUS_UNCONFIRMED) {
            $this->Flash->info(__('Já existe um usuário registrado com esse email. Por favor, faça seu login.'));
            return $this->redirect(array('controller' => 'users', 'action' => 'login', 'adm' => false));
        }
        
        return $resultUserCreation;
        
    }
    
    private function populateAdministratorRegisterForm($marketplaceId)
    {
        $this->request->data['Administrator']['marketplace_id'] = $marketplaceId;
        $this->request->data['Administrator']['user_id'] = $this->uid;
    }
    
    private function populateConsumerRegisterForm($marketplaceId)
    {
        $options = array(
            'conditions' => array(
                'Marketplace.' . $this->Marketplace->primaryKey => $marketplaceId
                ),
            );

        $this->request->data = $this->Marketplace->find('first', $options);

        $metaConsumers = $this->Marketplace->getMetaConsumersByMarketplaceId($marketplaceId);
        $servicesData = $this->listServiceTypesOfMarketplace($marketplaceId);
        $this->setServiceOptionsAndSelected($servicesData, array());

        $this->request->data['Consumer']['marketplace_id'] = $this->request->data['Marketplace']['id'];
        $this->request->data['Consumer']['meta_consumer_id'] = $metaConsumers['MetaConsumer']['0']['id'];
        $this->request->data['Consumer']['user_id'] = $this->uid;

        
    }
    
    private function populateContractorRegisterForm($marketplaceId)
    {
        $servicesData = $this->listServiceTypesOfMarketplace($marketplaceId);
        $this->setServiceOptionsAndSelected($servicesData, array());

        $this->request->data['Marketplace']['id'] = $marketplaceId;
        $this->request->data['MetaProvider']['id'] = $this->EjqContractorMetaProviderId;
        $this->request->data['Provider']['user_id'] = $this->uid;
    }
    
    private function populateEstimatorRegisterForm($marketplaceId)
    {
        $servicesData = $this->listServiceTypesOfMarketplace($marketplaceId);
        $this->setServiceOptionsAndSelected($servicesData, array());

        $this->request->data['Provider']['marketplace_id'] = $marketplaceId;
        $this->request->data['Provider']['meta_provider_id'] = $this->EjqEstimatorMetaProviderId;
        $this->request->data['Provider']['user_id'] = $this->uid;
    }
    
    private function postEstimatorRegisterAndRedirect()
    {
        try {
            $this->processPostEstimatorRegister($this->EjqMarketplaceId, $this->request->data);
        } catch (Exception $ex) {
            throw $ex;
        }
        $this->Flash->success(__('Cadastro feito com sucesso. Por favor confira email enviado para criar uma senha e ativar a conta.'));
        return $this->redirect(array('controller' => 'marketplaces', 'action' => 'post_success', 'adm' => false));
        
    }
    
    private function postAdministratorRegisterAndRedirect()
    {
        try {
            $this->processPostAdministratorRegister($this->EjqMarketplaceId, $this->request->data);
        } catch (Exception $ex) {
            throw $ex;
        }
        $this->Flash->success(__('Cadastro feito com sucesso. Por favor confira email enviado para criar uma senha e ativar a conta.'));
        return $this->redirect(array('controller' => 'marketplaces', 'action' => 'post_success', 'adm' => false));
        
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
    
    private function processPostAJobPost($marketplaceId, $requestData)
    {
        if (isset($requestData['Consumer']['user_id'])) {
            $this->loadModel('User');
            $this->User->id = $requestData['Consumer']['user_id'];
            $this->request->data['Consumer']['user_name'] = $this->User->field('name');
            $this->request->data['Consumer']['email'] = $this->User->field('email');
            $this->request->data['Consumer']['id'] = $this->EjqProfileId;
            $this->manageJobCreation($marketplaceId, $requestData['Consumer']['user_id']);
            
        } else {
            //try to create new user and redirect if aready exists
            $resultUserCreation = $this->manageUserCreation($requestData['Consumer']['email'], $requestData['Consumer']['user_name']);
            $resultConsumerCreation = $this->createConsumerIfNeeded($marketplaceId, $resultUserCreation['userId']);
            $this->request->data['Consumer']['id'] = $resultConsumerCreation['consumerId'];
            $this->manageJobCreation($marketplaceId, $resultUserCreation['userId']);
        }
        
    }

    private function processPostAdministratorRegister($marketplaceId, $requestData)
    {
        //try to create new user and redirect if aready exists
        $resultUserCreation = $this->manageUserCreation($requestData['Administrator']['email'], $requestData['Administrator']['user_name']);

        $this->manageAdministratorCreation($marketplaceId, $resultUserCreation['userId']);
    }

    private function processPostConsumerRegister($marketplaceId, $requestData)
    {
        //try to create new user and redirect if aready exists
        $resultUserCreation = $this->manageUserCreation($requestData['Consumer']['email'], $requestData['Consumer']['user_name']);

        $this->manageConsumerCreation($marketplaceId, $resultUserCreation['userId']);
    }

    private function processPostContractorRegister($marketplaceId, $requestData)
    {
        //try to create new user and redirect if aready exists
        $resultUserCreation = $this->manageUserCreation($requestData['Provider']['email'], $requestData['Provider']['user_name']);

        $this->manageContractorCreation($marketplaceId, $resultUserCreation['userId']);
    }

    private function processPostEstimatorRegister($marketplaceId, $requestData)
    {
        //try to create new user and redirect if aready exists
        $resultUserCreation = $this->manageUserCreation($requestData['Provider']['email'], $requestData['Provider']['user_name']);

        $this->manageEstimatorCreation($marketplaceId, $resultUserCreation['userId']);
    }

    private function registerKnownAdministratorAndRedirect($administratorUserId, $marketplaceId) 
    {
            $data = $this->fetchNewAdministratorData($marketplaceId, $administratorUserId);
            $result = $this->createAdministratorIfNeeded($administratorUserId, $data);
            if (isset($result['administratorId'])) {
                //     redirect para dashboard
                $administratorId = $result['administratorId'];
                $this->Flash->success(__('The Site Admin was created'));
                return $this->redirect(array('controller' => 'marketplaces','action' => 'dashboard', $this->EjqMarketplaceId));
            } else {
                throw new NotFoundException(__('It was not possible to create a new site admin'));
            }
        
    }
    
    private function registerKnownConsumerAndRedirect($consumerUserId, $marketplaceId) 
    {
            //     cria consumer
            $result = $this->createConsumerIfNeeded($marketplaceId, $consumerUserId);
            if (isset($result['consumerId'])) {
                //     redirect para dashboard
                $consumerId = $result['consumerId'];
                $this->Flash->success(__('You can post job requests now.'));
                return $this->redirect(array('controller' => 'consumers','action' => 'dashboard', $consumerId));
            } else {
                throw new NotFoundException(__('Não foi possível criar o novo Consumer.'));
            }
        
    }
    

    private function registerKnownContractorAndRedirect($providerUserId, $marketplaceId) 
    {
            $data = $this->fetchNewContractorData($marketplaceId, $providerUserId);
            $result = $this->createContractorIfNeeded($providerUserId, $data);
            if (isset($result['providerId'])) {
                //     redirect para dashboard
                $providerId = $result['providerId'];
                $this->Flash->success(__('You can define your job categories now.'));
                return $this->redirect(array('controller' => 'providers','action' => 'dashboard', $providerId));
            } else {
                throw new NotFoundException(__('Não foi possível criar o novo Provider. '));
            }
        
    }
    
    private function registerKnownEstimatorAndRedirect($providerUserId, $marketplaceId) 
    {
            $data = $this->fetchNewEstimatorData($marketplaceId, $providerUserId);
            $result = $this->createEstimatorIfNeeded($providerUserId, $data);
            if (isset($result['providerId'])) {
                //     redirect para dashboard
                $providerId = $result['providerId'];
                $this->Flash->success(__('You can define your job categories now.'));
                return $this->redirect(array('controller' => 'providers','action' => 'dashboard', $providerId));
            } else {
                throw new NotFoundException(__('Não foi possível criar o novo Provider. '));
            }
        
    }
    
    private function saveNewAdministrator($data)
    {
        if ($this->Marketplace->Administrator->save($data)) {
            return $this->Marketplace->Administrator->id;
        } else {
            throw new NotFoundException('Not possible to create site admin ');
        }
    }
    
    private function saveNewConsumer($data)
    {
        if ($this->Marketplace->Consumer->save($data)) {
            return $this->Marketplace->Consumer->id;
        } else {
            throw new NotFoundException('Não foi possível adicionar o Consumer. ');
        }
    }

    private function saveNewProvider($data)
    {
        if (!empty($data['Contractor'])) {
            $contractorData['Contractor'] = $data['Contractor'];
            $data['Contractor'] = $contractorData;
        }
        if ($this->Marketplace->Provider->saveAssociated($data)) {
            return $this->Marketplace->Provider->id;
        } else {
            throw new NotFoundException('Não foi possível adicionar o Provider. ');
        }
    }
    
    private function setServiceOptionsAndSelected($options, $selected) 
    {
        $this->set('optionsServiceTypes', $options);
        $this->set('selectedServiceTypes', $selected);
    }
    
    private function tryToRegisterContractorAndRedirectIfCant($marketplaceId, $userId)
    {
            $provider = $this->Marketplace->Provider->getProviderByMarketplaceAndUserId($marketplaceId, $userId);
            $consumer = $this->Marketplace->Consumer->getConsumerByMarketplaceAndUserId($marketplaceId, $userId);
            if ((empty($provider) && (empty($consumer)))) {
                try {
                    $result = $this->registerKnownContractor($userId, $marketplaceId);
                    return $result;
                } catch (Exception $ex) {
                    throw $ex;
                }
            } else {
                if (empty($consumer)) {
                    $this->Flash->danger(__('You are already a registered supplier.'));
                    return $this->redirect(array('controller' => 'main', 'action' => 'index'));
                }
                if (empty($provider)) {
                    $this->Flash->danger(__('You are already a registered home owner.'));
                    return $this->redirect(array('controller' => 'main', 'action' => 'index'));
                }
            }
        
    }
    
    private function tryToRegisterProviderAndRedirectIfCant($marketplaceId, $userId)
    {
            $provider = $this->Marketplace->Provider->getProviderByMarketplaceAndUserId($marketplaceId, $userId);
            $consumer = $this->Marketplace->Consumer->getConsumerByMarketplaceAndUserId($marketplaceId, $userId);
            if ((empty($provider) && (empty($consumer)))) {
                try {
                    $result = $this->registerKnownProvider($userId, $marketplaceId);
                    return $result;
                } catch (Exception $ex) {
                    throw $ex;
                }
            } else {
                if (empty($consumer)) {
                    $this->Flash->danger(__('You are already a registered supplier.'));
                    return $this->redirect(array('controller' => 'main', 'action' => 'index'));
                }
                if (empty($provider)) {
                    $this->Flash->danger(__('You are already a registered home owner.'));
                    return $this->redirect(array('controller' => 'main', 'action' => 'index'));
                }
            }
        
    }
    
    private function verifyAdministratorCredentialsAndRedirectIfNeeded()
    {
        if(!$this->canAccessAdm) {
            $this->Flash->danger(__('Você não está autorizado a acessar essa página.'));
            return $this->redirect(array('controller' => 'main', 'action' => 'index'));
        }
        
        
    }
    
    
    private function createContractorsThatShouldHaveBeenCreatedBefore()
    {
        $providers = $this->Marketplace->Provider->find('all', ['conditions' => ['meta_provider_id' => $this->EjqContractorMetaProviderId]]);
        foreach ($providers as $key => $providerData) {
            if (empty($providerData['Contractor'])) {
                $contractorData = [
                    'provider_id' => $providerData['Provider']['id'], 
                    'name' => $providerData['Provider']['name'],
                    'marketplace_id' => $providerData['Provider']['marketplace_id'], 
                ];
                $this->Marketplace->Provider->Contractor->create();
                $this->Marketplace->Provider->Contractor->save($contractorData);
            }
        }
        exit; 
        
    }
    
    private function createInvoicesThatShouldHaveBeenCreatedBefore()
    {
        $demands = $this->Marketplace->Demand->find('all', ['conditions' => ['Demand.marketplace_id' => $this->EjqMarketplaceId]]);
        foreach ($demands as $demandData) {
            $lackTenderDevelopment = true;
            $needTenderDevelopment = false;
            $lackCommission = true;
            $needCommission = false;
            switch($demandData['Demand']['status']) {
                case EJQ_DEMAND_STATUS_CONTRACT_SIGNED:
                case EJQ_DEMAND_STATUS_BID_DISCLOSED:
                case EJQ_DEMAND_STATUS_BID_SELECTED:
                case EJQ_DEMAND_STATUS_ESTIMATION_ASSIGNED:
                case EJQ_DEMAND_STATUS_ESTIMATION_DISPATCHED:
                case EJQ_DEMAND_STATUS_ESTIMATION_READY_FOR_DISPATCH:
                case EJQ_DEMAND_STATUS_TENDER_CLOSED_TO_BIDS:
                case EJQ_DEMAND_STATUS_TENDER_IN_PROGRESS:
                case EJQ_DEMAND_STATUS_TENDER_TO_BE_MODIFIED:
                case EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS:
                case EJQ_DEMAND_STATUS_TENDER_READY_FOR_HOME_OWNER_APPROVAL:
                case EJQ_DEMAND_STATUS_TENDER_READY_FOR_SITE_ADMIN_APPROVAL:
                case EJQ_DEMAND_STATUS_TENDER_READY_TO_BIDS:
                case EJQ_DEMAND_STATUS_WAITING_FOR_ESTIMATOR_SCHEDULE_APPROVAL:
                    $needTenderDevelopment = true;
                    break;

                case EJQ_DEMAND_STATUS_CLOSED:
                case EJQ_DEMAND_STATUS_JOB_COMPLETED:
                case EJQ_DEMAND_STATUS_JOB_IN_PROGRESS:
                    $needCommission = true;
                    $needTenderDevelopment = true;
                    break;

                default:
                   break;
            }
            if (!empty($demandData['Invoice'])) {
                foreach ($demandData['Invoice'] as $invoiceData) {
                    if ($invoiceData['type'] == EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT) {
                        $lackTenderDevelopment = false;
                    }
                    if ($invoiceData['type'] == EJQ_INVOICE_TYPE_COMMISSION) {
                        $lackCommission = false;
                    }
                }
            }
            
            $tenderInfo = $this->Marketplace->Demand->getTenderInfo($demandData['Demand']['id']);
            $tenderInfo['Invoice']['marketplace_id'] = $this->EjqMarketplaceId;
            
            $invoiceInfo = [];

            if ($needTenderDevelopment && $lackTenderDevelopment) {
                $tenderInfo['Invoice']['type'] = EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT;
                $invoiceInfo = $this->Marketplace->Demand->Invoice->initNewInvoiceData($tenderInfo);
            }
            if ($needCommission && $lackCommission) {
                $tenderInfo['Invoice']['type'] = EJQ_INVOICE_TYPE_COMMISSION;
                $invoiceInfo = $this->Marketplace->Demand->Invoice->initNewInvoiceData($tenderInfo);
            }
            
            if (!empty($invoiceInfo)) {
                $this->Marketplace->Invoice->create();
                $newId = $this->Marketplace->Invoice->id;
                $invoiceInfo['Invoice']['id'] = $newId;
                $data = ['Invoice' => $invoiceInfo];
                $this->Marketplace->Invoice->save($data);
            }
            
        }
        exit; 
        
    }
    

}
