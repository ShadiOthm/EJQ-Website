<?php

App::uses('AppController', 'Controller');

class ServiceTypesController extends AppController {

    public $components = array('Paginator');

    public function beforeFilter() {
        
        
        parent::beforeFilter();
        
        $this->Auth->allow(array('create', 'delete', 'index','update'));
                
        if(!$this->canAccessAdm) {
            $this->Flash->danger(__('Just Site Admins can do this action.'));
            return $this->redirect(array('controller' => 'main', 'action' => 'index'));
        }
        
    }

    public function index() {
        $this->ServiceType->recursive = 0;
        $this->set('servicetypes', $this->Paginator->paginate());
    }

    public function create($metaMarketplaceId=null) {
        if (!$this->ServiceType->MetaMarketplace->exists($metaMarketplaceId)) {
            throw new NotFoundException('MetaMarketplace inválido');

        }
        
        //check permissions and redirect if should not be here
        try {
            $this->checkPermissions($metaMarketplaceId, $this->uid, $this->canAccessAdm);
        } catch (Exception $ex) {
                $this->Flash->danger(__('Você não está autorizado a acessar essa página.'));
                return $this->redirect(array('controller' => 'main','action' => 'index'));
        }

        $options = array('conditions' => array('MetaMarketplace.' . $this->ServiceType->MetaMarketplace->primaryKey => $metaMarketplaceId));
        $metaMarketplaceData = $this->ServiceType->MetaMarketplace->find('first', $options);

        $this->set('metaMarketplaceId', $metaMarketplaceData['MetaMarketplace']['id']);
        $this->set('metaMarketplaceName', $metaMarketplaceData['MetaMarketplace']['name']);

        
        if ($this->request->is(array('post', 'put'))) {
            $this->ServiceType->create();
            if ($this->ServiceType->save($this->request->data)) {
                $this->Flash->success(__('O ServiceType foi adicionado'));
                return $this->redirect(array('controller' => 'meta_marketplaces','action' => 'detail', $metaMarketplaceId));
            } else {
                    $this->Flash->danger(__('Não foi possível adicionar o ServiceType. Favor tentar novamente'));
            }
        } else {
            $this->request->data['ServiceType']['meta_marketplace_id'] = $metaMarketplaceId;
        }


    }

    public function update($id = null) {
        if (!$this->ServiceType->exists($id)) {
            throw new NotFoundException('ServiceType inválido');
        }
        
        $this->ServiceType->id = $id;
        $metaMarketplaceId = $this->ServiceType->field('meta_marketplace_id');
        
        //check permissions and redirect if should not be here
        try {
            $this->checkPermissions($metaMarketplaceId, $this->uid, $this->canAccessAdm);
        } catch (Exception $ex) {
                $this->Flash->danger(__('Você não está autorizado a acessar essa página.'));
                return $this->redirect(array('controller' => 'main','action' => 'index'));
        }
        
        
        $this->ServiceType->MetaMarketplace->id = $metaMarketplaceId;
        $name = $this->ServiceType->MetaMarketplace->field('name');
        $this->set('metaMarketplaceId', $metaMarketplaceId);
        $this->set('metaMarketplaceName', $name);

        if ($this->request->is(array('post', 'put'))) {
            if ($this->ServiceType->save($this->request->data)) {
                $metaMarketplaceId = $this->request->data['ServiceType']['meta_marketplace_id'];
                $this->Flash->success(__('O ServiceType foi alterado'));
                return $this->redirect(array('controller' => 'meta_marketplaces','action' => 'detail', $metaMarketplaceId));
            } else {
                $this->Flash->danger(__('Não foi possível alterar o ServiceType. Favor tentar novamente.'));
            }
        }
        $options = array('conditions' => array('ServiceType.' . $this->ServiceType->primaryKey => $id));
        $this->request->data = $this->ServiceType->find('first', $options);
    }

    public function delete($id = null) {
        $this->ServiceType->id = $id;
        if (!$this->ServiceType->exists()) {
            throw new NotFoundException('ServiceType inválido');
        } 


        $options = array('conditions' => array('ServiceType.' . $this->ServiceType->primaryKey => $id));
        $serviceTypeData = $this->ServiceType->find('first', $options);
        $metaMarketplaceId = $serviceTypeData['ServiceType']['meta_marketplace_id'];
        //check permissions and redirect if should not be here
        try {
            $this->checkPermissions($metaMarketplaceId, $this->uid, $this->canAccessAdm);
        } catch (Exception $ex) {
                $this->Flash->danger(__('Você não está autorizado a acessar essa página.'));
                return $this->redirect(array('controller' => 'main','action' => 'index'));
        }

        if ($this->ServiceType->delete()) {
            $this->Flash->success(__('O ServiceType foi excluído'));
            return $this->redirect(array('controller' => 'meta_marketplaces','action' => 'detail', $metaMarketplaceId));
        } else {
            $this->Flash->danger(__('Não foi possível excluir o ServiceType. Favor tentar novamente.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

    
    private function checkPermissions($id, $userId, $adminPermission)
    {
        $isTheOwner = $this->ServiceType->MetaMarketplace->isUserTheOwner($id, $userId);
        
        if (!$isTheOwner && !$adminPermission) {
            $this->Flash->danger(__('Você não está autorizado a acessar essa função.'));
            return $this->redirect($this->Auth->loginRedirect);
        }
        
        return true;
        
    }
    
    
}
