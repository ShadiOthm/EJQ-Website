<?php

App::uses('AppController', 'Controller');

class MetaMarketplacesController extends AppController {

    public $components = array('Paginator');

    public function beforeFilter() 
    {
        $this->Auth->allow(array('create', 'delete', 'detail', 'publish', 'update'));
        
        parent::beforeFilter();
    }

    public function index() 
    {
        $this->MetaMarketplace->recursive = 0;
        $this->set('metaMarketplaces', $this->Paginator->paginate());
    }

    public function create() 
    {
        
        $curator = $this->isUserACurator($this->uid);
        
        if (empty($curator)) {
            $this->Session->destroy();
            $this->Flash->danger(__('O usuário precisa ser um curador para criar metaMarketplaces. Por favor registre-se ou faça o login abaixo.'));
            return $this->redirect(array('controller' => 'metaplace', 'action' => 'index    '));
            
        }
        
        if ($this->request->is(array('post', 'put'))) {
            $this->MetaMarketplace->create();
            $isNoFileUploaded = ($this->request->data['MetaMarketplace']['logo_image']['error'] == UPLOAD_ERR_NO_FILE) ? true : false ;
            if ($isNoFileUploaded) {
                $this->MetaMarketplace->validator()->remove('logo_image');
                unset($this->request->data['MetaMarketplace']['logo_image']);
            }
            if ($this->MetaMarketplace->save($this->request->data)) {
                $this->Flash->success(__('O MetaMarketplace foi adicionado'));
                return $this->redirect(array('controller' => 'meta_marketplaces','action' => 'detail', $this->MetaMarketplace->id));
            } else {
                    $this->Flash->danger(__('Não foi possível adicionar o MetaMarketplace. Favor tentar novamente'));
            }
        }
        
        $this->request->data['MetaMarketplace']['curator_id'] = $curator['Curator']['id'];


    }
    
    public function delete($id = null) 
    {
        
        if (!$this->MetaMarketplace->exists($id)) {
            throw new NotFoundException('MetaMarketplace inválido');
        }
        //check permissions and redirect if should not be here
        try {
            $this->checkPermissions($id, $this->uid, $this->canAccessAdm);
        } catch (Exception $ex) {
                $this->Flash->danger(__('Você não está autorizado a acessar essa página.'));
                return $this->redirect(array('controller' => 'main','action' => 'index'));
        }
        
        $this->MetaMarketplace->id = $id;
        if ($this->MetaMarketplace->delete()) {
            $this->Flash->success(__('O MetaMarketplace foi excluído'));
        } else {
            $this->Flash->danger(__('Não foi possível excluir o MetaMarketplace. Favor tentar novamente.'));
        }
        return $this->redirect(array('controller' => 'users','action' => 'profile'));
    }
    
    public function detail($id=null)
    {
        if (!$this->MetaMarketplace->exists($id)) {
            throw new NotFoundException('MetaMarketplace inválido');
        }
        
        //check permissions and redirect if should not be here
        try {
            $this->checkPermissions($id, $this->uid, $this->canAccessAdm);
        } catch (Exception $ex) {
                $this->Flash->danger(__('Você não está autorizado a acessar essa página.'));
                return $this->redirect(array('controller' => 'main','action' => 'index'));
        }

        $options = array('conditions' => array('MetaMarketplace.' . $this->MetaMarketplace->primaryKey => $id));
        $metaMarketplaceData = $this->MetaMarketplace->find('first', $options);
        
        $this->set('canBePublished', $this->canBePublished($metaMarketplaceData));
        $this->set('metaMarketplace', $metaMarketplaceData);
        
    }
    
    public function publish($id=null) 
    {
        if (!$this->MetaMarketplace->exists($id)) {
            throw new NotFoundException('MetaMarketplace inválido');
        }

        //check permissions and redirect if should not be here
        try {
            $this->checkPermissions($id, $this->uid, $this->canAccessAdm);
        } catch (Exception $ex) {
                $this->Flash->danger(__('Você não está autorizado a acessar essa página.'));
                return $this->redirect(array('controller' => 'main','action' => 'index'));
        }
        if ($this->request->is(array('post', 'put'))) {
            
            if ($this->createMarketplace($this->request->data['MetaMarketplace']['id'])) {
                $this->Flash->success(__('O Marketplace foi publicado'));
                return $this->redirect(array('controller' => 'users','action' => 'profile'));
            } else {
                    $this->Flash->danger(__('Não foi possível adicionar o Marketplace. Favor tentar novamente'));
            }

        } else {
            
            $options = array('conditions' => array('MetaMarketplace.' . $this->MetaMarketplace->primaryKey => $id));
            

            $metaMarketplace = $this->MetaMarketplace->find('first', $options);
            $metaMarketplace['MetaMarketplace']['status'] = META_MARKETPLACE_STATUS_PUBLISHED;
            $this->request->data = $metaMarketplace;
            $this->set('metaMarketplaceId', $metaMarketplace['MetaMarketplace']['id']);
            $this->set('metaMarketplaceName', $metaMarketplace['MetaMarketplace']['name']);

        }
    }
    
    public function update($id = null) 
    {
        if (!$this->MetaMarketplace->exists($id)) {
            throw new NotFoundException('MetaMarketplace inválido');
        }
        //check permissions and redirect if should not be here
        try {
            $this->checkPermissions($id, $this->uid, $this->canAccessAdm);
        } catch (Exception $ex) {
                $this->Flash->danger(__('Você não está autorizado a acessar essa página.'));
                return $this->redirect(array('controller' => 'main','action' => 'index'));
        }


        if ($this->request->is(array('post', 'put'))) {
            $isNoFileUploaded = ($this->request->data['MetaMarketplace']['logo_image']['error'] == UPLOAD_ERR_NO_FILE) ? true : false ;
            if ($isNoFileUploaded) {
                $this->MetaMarketplace->validator()->remove('logo_image');
                unset($this->request->data['MetaMarketplace']['logo_image']);
            }
            $isNoFileUploaded = ($this->request->data['MetaMarketplace']['cover_image']['error'] == UPLOAD_ERR_NO_FILE) ? true : false ;
            if ($isNoFileUploaded) {
                $this->MetaMarketplace->validator()->remove('cover_image');
                unset($this->request->data['MetaMarketplace']['cover_image']);
            }
            if ($this->MetaMarketplace->save($this->request->data)) {
                $this->Flash->success(__('O MetaMarketplace foi alterado'));
                return $this->redirect(array('controller' => 'main','action' => 'metamarketplaces'));
            } else {
                $this->Flash->danger(__('Não foi possível alterar o MetaMarketplace. Favor tentar novamente.'));
            }
        }
        $options = array('conditions' => array('MetaMarketplace.' . $this->MetaMarketplace->primaryKey => $id));
        $this->request->data = $this->MetaMarketplace->find('first', $options);
    }
     
    private function canBePublished($metaMarketplace)
    {
        $canBe = true;
        
        if (isset($metaMarketplace['Marketplace']['0'])) {
            $canBe = false;
        }        
        
        if (!isset($metaMarketplace['MetaConsumer']['0'])) {
            $canBe = false;
        }        
        
        if (!isset($metaMarketplace['MetaProvider']['0'])) {
            $canBe = false;
        }     
        
        return $canBe;
    }
 
    private function checkPermissions($id, $userId, $adminPermission)
    {
        $isTheOwner = $this->MetaMarketplace->isUserTheOwner($id, $userId);
        
        if (!$isTheOwner && !$adminPermission) {
            $this->Flash->danger(__('Você não está autorizado a acessar essa função.'));
            return $this->redirect($this->Auth->loginRedirect);
        }
        
        return true;
        
    }
    
    private function createMarketplace($metaMarketplaceId)
    {

        $data = $this->fetchDataForMarketplaceCreation($metaMarketplaceId);
        
        $this->MetaMarketplace->Marketplace->create();

        return $this->MetaMarketplace->Marketplace->save($data);
    }
    
    private function fetchDataForMarketplaceCreation($metaMarketplaceId)
    {
        
        $options = array(
            'conditions' => array(
                'MetaMarketplace.' . $this->MetaMarketplace->primaryKey => $metaMarketplaceId
                ),
            'fields' => array('id as meta_marketplace_id', 'name', 'purpose', 'description', 'logo_image', 'cover_image'),
            'contain' => array('ServiceType'),
            );
        $metaMarketplace = $this->MetaMarketplace->find('first', $options);
        $data['Marketplace'] = $metaMarketplace['MetaMarketplace'];
        
        $data['ServiceType']['ServiceType'] = $this->fetchDataForServiceTypesAssociations($metaMarketplaceId);
        
        
        return $data;
        
    }
    
    private function fetchDataForServiceTypesAssociations($metaMarketplaceId)
    {
        $serviceTypes = $this->MetaMarketplace->ServiceType->find(
			'list',
			array(
                                'conditions' => array(
                                    'meta_marketplace_id' => $metaMarketplaceId,
                                ),
				'fields' => array(
					'id',
					'name'
					),
                                'order' => 'ServiceType.name',
				)
			);        
        
        
        $result = array();
        foreach ($serviceTypes as $key => $serviceType) {
            $result[$key] = $serviceType;
        }
        $association = array_keys($result);
        
        return $association;
        
    }
    
    private function isUserACurator($userId)
    {
        //$curator = $CuratorModel->getCuratorByUserId($userId);
        $curator = $this->MetaMarketplace->Curator->findByUserId($userId);
        
        if (!empty($curator)) {
            return $curator;
        } else {
            return false;
        }
        
    }
    
}
