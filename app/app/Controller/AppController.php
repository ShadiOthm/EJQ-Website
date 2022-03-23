<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */

class AppController extends Controller {
    
    protected $uid;
    protected $EjqMarketplaceId;
    protected $EjqEstimatorMetaProviderId;
    protected $EjqContractorMetaProviderId;
    protected $EjqHomeOwnerMetaConsumerId;
    protected $canAccessAdm;
    protected $EjqIsProvider;
    protected $EjqIsEstimator;
    protected $EjqIsContractor;
    protected $EjqIsConsumer;
    protected $EjqProfileId;   //can be userId, consumerId or profileId according to user
    protected $EjqGeneralContractorServiceTypeId;
    
//    public $layout = 'marketplaces';

    public $components = array(
        'Acl',
        'Auth' => array(
            'authorize' => array(
                'Actions' => array(
                    'userModel' => 'User',
                    'actionPath' => 'controllers/'
                )
            ),
            'authenticate' => array(
                'Form' => array(
                    'userModel' => 'User',
                    'fields' => array('username' => 'email', 'password' => 'password'),
                    'scope' => array('User.removed' => '0')
                )
            )
        ),
        'Session',
        'Flash',
        'Paginator',
        'RequestHandler'
    );
	
    public $helpers = array('Html', 'Form', 'Session');

    public function beforeFilter() {
        //debug($this->request->here);exit;
        if($this->request->here == '/users/login') {
            $this->Session->delete('redirect_url_after_login');
        }

        
        if($this->request->here != '/users/login/redirect:1') {
            $uid = $this->Auth->user('id');
            if(empty($uid)) {
                $queryString = "";
                if ((!empty($this->request->query)) && is_array($this->request->query)) {
                    $sep = "?";
                    foreach ($this->request->query as $key => $value) {
                        $queryString .= "$sep$key=$value";
                        $sep = "&";
                    }
                    
                }
                $this->Session->write('redirect_url_after_login', Router::url($this->request->here . $queryString, true));
            }
        }
        
        $this->set('hereTitle', $this->here);
        
        //Configure AuthComponent
        $this->Auth->loginAction = array('controller' => 'users', 'action' => 'login', 'adm' =>false);
        $this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login', 'adm'=>false);
//        $this->Auth->loginRedirect = array('controller' => 'main', 'action' => 'index', 'adm'=>false);

        $compIsCurator = $this->Components->load('IsCurator');
        $this->isCurator = $compIsCurator->IsCurator($this->Auth, $this->Acl);
        $this->set('isCurator', $this->isCurator);
        
        $this->loadModel('Marketplace');
        $this->Marketplace->recursive = -1;
        $result = $this->Marketplace->findBySlug(EJQ_MARKETPLACE_SLUG, array('Marketplace.id'));
        if (isset($result['Marketplace']['id'])) {
            $marketplaceId = $result['Marketplace']['id'];
            if ((!is_int($marketplaceId) && !ctype_digit($marketplaceId)) || (int)$marketplaceId <= 0 ) {
                throw new Exception(__('Easy Job Quote Marketplace not available'));
            }
            $this->EjqMarketplaceId = $marketplaceId;
        } else {
            throw new Exception(__('Easy Job Quote Marketplace not available'));
        }
        
        $this->loadModel('ServiceType');
        $this->ServiceType->recursive = -1;
        $result = $this->ServiceType->findBySlug(EJQ_GENERAL_CONTRACTOR_SLUG, array('ServiceType.id'));
        if (isset($result['ServiceType']['id'])) {
            $generalContractorServiceTypeId = $result['ServiceType']['id'];
            if ((!is_int($generalContractorServiceTypeId) && !ctype_digit($generalContractorServiceTypeId)) || (int)$generalContractorServiceTypeId <= 0 ) {
                throw new Exception(__('Easy Job Quote ServiceType not available'));
            }
            $this->EjqGeneralContractorServiceTypeId = $generalContractorServiceTypeId;
        } else {
            throw new Exception(__('General Contractor Service Type not available'));
        }
        
        
        $this->loadModel('MetaConsumer');
        $this->MetaConsumer->recursive = -1;
        $result = $this->
                MetaConsumer->
                findBySlugAndMetaMarketplaceId(
                        EJQ_META_CONSUMER_HOME_OWNER_SLUG,
                        $this->EjqMarketplaceId);
        
        if (isset($result['MetaConsumer']['id'])) {
            $homeOwnerMetaConsumerId = $result['MetaConsumer']['id'];
            if ((!is_int($homeOwnerMetaConsumerId) && !ctype_digit($homeOwnerMetaConsumerId)) || (int)$homeOwnerMetaConsumerId <= 0 ) {
                throw new Exception(__('Home Owner Role not available'));
            }
            $this->EjqHomeOwnerMetaConsumerId = $homeOwnerMetaConsumerId;
        } else {
            throw new Exception(__('Home Owner Role not defined'));

        }

        $this->loadModel('MetaProvider');
        $this->MetaProvider->recursive = -1;
        $result = $this->
                MetaProvider->
                findBySlugAndMetaMarketplaceId(
                        EJQ_META_PROVIDER_CONTRACTOR_SLUG,
                        $this->EjqMarketplaceId);
        
        if (isset($result['MetaProvider']['id'])) {
            $repairsMetaProviderId = $result['MetaProvider']['id'];
            if ((!is_int($repairsMetaProviderId) && !ctype_digit($repairsMetaProviderId)) || (int)$repairsMetaProviderId <= 0 ) {
                throw new Exception(__('Repairs MetaProvider not available'));
            }
            $this->EjqContractorMetaProviderId = $repairsMetaProviderId;
        } else {
//            $this->EjqRepairsMetaProviderId = 0;
            throw new Exception(__('Repairs Service not available'));

        }
        
        $result = $this->
                MetaProvider->
                findBySlugAndMetaMarketplaceId(
                        EJQ_META_PROVIDER_ESTIMATION_SLUG,
                        $this->EjqMarketplaceId);
        if (isset($result['MetaProvider']['id'])) {
            $estimationMetaProviderId = $result['MetaProvider']['id'];
            if ((!is_int($estimationMetaProviderId) && !ctype_digit($estimationMetaProviderId)) || (int)$estimationMetaProviderId <= 0 ) {
                throw new Exception(__('Estimation MetaProvider not available'));
            }
            $this->EjqEstimatorMetaProviderId = $estimationMetaProviderId;
        } else {
//            $this->EjqEstimationMetaProviderId = 0;
            throw new Exception(__('Estimation Service not available'));

        }
        
        $this->uid = null;
        if (!empty($this->Auth->user())){
            $this->uid = $this->Auth->user('id');
            $groupId = $this->Auth->user('group_id');
            $aro = array('model' => 'Group', 'foreign_key' => $groupId);
            $this->canAccessAdm =  $this->Acl->check($aro, 'controllers/Main/metamarketplaces');
            if (!$this->canAccessAdm) {
                $this->Marketplace->Administrator->recursive = -1;
                $result = $this->
                        Marketplace->
                        Administrator->
                        findByMarketplaceIdAndUserId(
                                $this->EjqMarketplaceId,
                                $this->uid
                        );
                if (!empty($result['Administrator'])) {
                    $this->canAccessAdm = true;
                    $this->set('role', EJQ_ROLE_ADMIN);
                }
            }
        } else {
            $this->canAccessAdm = false;
        }        
        $this->set('uid', $this->uid);
        $this->set('canAccessAdm', $this->canAccessAdm);

//        if ($this->canAccessAdm) {
//            $this->Auth->loginRedirect = array('controller' => 'main', 'action' => 'metamarketplaces', 'adm' => true);
//        } else {
//            $this->Auth->loginRedirect = array('controller' => 'main', 'action' => 'index', 'adm' => false);
//        }
        //$this->Auth->loginRedirect = '/';
        $this->Auth->authError = __("Você não está autorizado a acessar essa página.");

        $result = $this->Marketplace->userRole($this->EjqMarketplaceId, $this->Auth->user('id'));

        $this->EjqIsConsumer = false;
        $this->EjqIsProvider = false;
        $this->EjqIsEstimator = false;
        $this->EjqIsContractor = false;
        $this->EjqProfileId = $this->uid;
                
        if (isset($result['Consumer'])) {
            $this->EjqIsConsumer = true;
            $this->EjqProfileId = $result['Consumer']['id'];
            $this->set('role', EJQ_ROLE_HOME_OWNER);
        } elseif (isset($result['Provider'])) {                
            $this->EjqIsProvider = true;
            $this->EjqProfileId = $result['Provider']['id'];
            $metaProviderId = $result['Provider']['meta_provider_id'];
            if ($metaProviderId == $this->EjqEstimatorMetaProviderId) {
                $this->EjqIsEstimator = true;
                $this->set('role', EJQ_ROLE_ESTIMATOR);
                
            }
            if ($metaProviderId == $this->EjqContractorMetaProviderId) {
                $this->EjqIsContractor = true;
                $this->set('role', EJQ_ROLE_CONTRACTOR);
                
            }
        }
        


        if ($this->Session->check('Config.language')) {
            Configure::write('Config.language', $this->Session->read('Config.language'));
        } else {
            setLocale(LC_ALL, 'eng');
            Configure::write('Config.language', 'eng');
        }



        Configure::write('AES_ENCRYPT_KEY', '1EC788C117BF1C2F152D5B05C6095880DFA3CEA0C56EC5A0512E16C9F9594992');

        // define root ACO
//		 $this->Acl->Aco->create(array('parent_id' => null, 'alias' => 'controllers'));
//		 $this->Acl->Aco->save();


        //  For CakePHP 2.1 and up
        //$this->Auth->allow();

        // Define Permissions
        // SuperUsers
        $this->Acl->allow(
            array(
                'model' => 'Group', 
                'foreign_key' => SUPERUSERS_GROUP
                ), 
            'controllers'
            );
        $this->Acl->deny(array(
            'model' => 'Group',
            'foreign_key' => USERS_GROUP
            ), 'controllers');
    }

    public function beforeRender() {
        //desabilita cache quando o usuário está logado para evitar o back button depois do logout
        if (!empty($this->Auth->user())){
            $this->response->disableCache();
        }
        
        if ($this->EjqIsConsumer) {
            $this->set('profileController', 'consumers');
        } elseif ($this->EjqIsProvider) {
            $this->set('profileController', 'providers');
        } else {
            $this->set('profileController', 'users');
        }
        $this->set('EjqProfileId', $this->EjqProfileId);
        
        $this->set('EjqMarketplaceId', $this->EjqMarketplaceId);
        
    }
        

    protected function InitId($id, $modelName, $fieldName, $passedArgFieldname=null)
    {
        if (is_null($passedArgFieldname)) {
            $passedArgFieldname = "id";
        }
        $checkedId = null;
        if ((empty($id)) && isset($this->request->query[$passedArgFieldname])) {
            $checkedId = $this->request->query[$passedArgFieldname];
        } else {
            $checkedId = $this->initIdInCaseOfPost($id, $modelName, $fieldName);
        }
        
        if(is_null($checkedId)){
            throw new NotFoundException('Id inválido');
        }
        
        return $checkedId;
    }
    
    protected function extractServiceListDescription($tenderInfo)
    {
        $servicesListDescription = "";
        if (!empty($tenderInfo['ServiceType'])) {
            $sep = "";
            foreach ($tenderInfo['ServiceType'] as $stKey => $serviceData ){
                $servicesListDescription .= $sep . $serviceData['name'];
                $sep = ", ";
            }
        }
        return $servicesListDescription;
    }

    protected function initIdInCaseOfPost($id, $modelName, $fieldName)
    {
        // init id
        if ((!$id) && isset($this->request->data[$modelName][$fieldName])) {
            $id = $this->request->data[$modelName][$fieldName];
        }
        return $id;
        
        
    }
    
    protected function setActiveTab($activeTab=null)
    {
        if (empty($activeTab)) { 
            if(!empty($this->request->params['named']['tab'])) {
                $this->set("activeTab", $this->request->params['named']['tab']);
            } else {
                $this->set("activeTab", null);
            }
        } else {
            $this->set("activeTab", $activeTab);
        }
        $this->activeTab = $activeTab;
    }
    
    protected function setHeaderInfo($title, $subTitle, $actions)
    {
        $titleBox['h1'] = $title;
        $titleBox['h2'] = $subTitle;
        $titleBox['tenderActions'] = $actions;

        $this->set('titleBox', $titleBox);
        $this->set('hereTitle', $title);
        $this->set('breadcrumbNode', $title);
        
    }
    
    
        
        
/**
* This is a maint function to recover/reorder the
*  lft/rght columns of the aco and aro models.
* 
*/
private function recover_acl_tree( ) {
    App::Import('Model', 'Aco' );
    App::Import('Model', 'Aro' );
    $Aco = new Aco;
    $Aro = new Aro;
    if ( !$Aco->recover() || !$Aro->recover() ) {
        echo 'Error recovering acl tree';
    } else {
        echo 'Aros and Acos reordered.'; 
    }
    exit;
}   
	
}
