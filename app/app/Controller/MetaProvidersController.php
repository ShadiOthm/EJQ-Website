<?php

App::uses('AppController', 'Controller');

class MetaProvidersController extends AppController {

    public $components = array('Paginator');

    public function beforeFilter() {
        $this->Auth->allow(array('create', 'delete', 'detail', 'update'));
        
        parent::beforeFilter();
    }

    public function index() {
        $this->MetaProvider->recursive = 0;
        $this->set('metaProviders', $this->Paginator->paginate());
    }

    public function create($metaMarketplaceId) {

        if (!$this->MetaProvider->MetaMarketplace->exists($metaMarketplaceId)) {
            throw new NotFoundException('MetaMarketplace inválido');
        }
        //check permissions and redirect if should not be here
        try {
            $this->checkPermissions($metaMarketplaceId, $this->uid, $this->canAccessAdm);
        } catch (Exception $ex) {
                $this->Flash->danger(__('Você não está autorizado a acessar essa página.'));
                return $this->redirect(array('controller' => 'main','action' => 'index'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $optionsServiceTypes = $this->listServiceTypesOfMetaMarketplace($metaMarketplaceId);
            $arrayServices = array_keys($optionsServiceTypes);
            $data = array('0' => array(
                'MetaProvider' => $this->request->data,
                'ServiceType' => array('ServiceType' => $arrayServices),
            ));
            if ($this->MetaProvider->saveAll($data, array('deep' => true))) {
                $this->Flash->success(__('O MetaProvider foi adicionado'));
                return $this->redirect(array('controller' => 'meta_marketplaces','action' => 'detail', $metaMarketplaceId));
            } else {
                    $this->Flash->danger(__('Não foi possível adicionar o MetaProvider. Favor tentar novamente'));
            }
        }
        $optionsServiceTypes = $this->listServiceTypesOfMetaMarketplace($metaMarketplaceId);
        $this->set('optionsServiceTypes', $optionsServiceTypes);
        $this->set('selectedServiceTypes', array_keys($optionsServiceTypes));
        
        $this->set('paymentMethods', $this->listPaymentMethods());
        $this->set('metaMarketplaceId', $metaMarketplaceId);
    }

    public function delete($id = null) {
        if (!$this->MetaProvider->exists($id)) {
            throw new NotFoundException('MetaProvider inválido');
        }
        
        $this->MetaProvider->id = $id;
        $metaMarketplaceId = $this->MetaProvider->field('meta_marketplace_id');
        //check permissions and redirect if should not be here
        try {
            $this->checkPermissions($metaMarketplaceId, $this->uid, $this->canAccessAdm);
        } catch (Exception $ex) {
                $this->Flash->danger(__('Você não está autorizado a acessar essa página.'));
                return $this->redirect(array('controller' => 'main','action' => 'index'));
        }
        
        if ($this->MetaProvider->delete()) {
            $this->Flash->success(__('O MetaProvider foi excluído'));
        } else {
            $this->Flash->danger(__('Não foi possível excluir o MetaProvider. Favor tentar novamente.'));
        }
        return $this->redirect(array('controller' => 'meta_marketplaces', 'action' => 'detail', $metaMarketplaceId));
    }

    public function detail($id)
    {
        if (!$this->MetaProvider->exists($id)) {
            throw new NotFoundException('MetaProvider inválido');
        }
        
        $options = array('conditions' => array('MetaProvider.' . $this->MetaProvider->primaryKey => $id));
        $metaProviderData = $this->MetaProvider->find('first', $options);
        $this->set('metaProvider', $metaProviderData);
        //check permissions and redirect if should not be here
        try {
            $this->checkPermissions($metaProviderData['MetaProvider']['meta_marketplace_id'], $this->uid, $this->canAccessAdm);
        } catch (Exception $ex) {
                $this->Flash->danger(__('Você não está autorizado a acessar essa página.'));
                return $this->redirect(array('controller' => 'main','action' => 'index'));
        }

    }

    public function update($id = null) {
        if (!$this->MetaProvider->exists($id)) {
            throw new NotFoundException('MetaProvider inválido');
        }
        $options = array('conditions' => array('MetaProvider.' . $this->MetaProvider->primaryKey => $id));
        $metaProviderData = $this->MetaProvider->find('first', $options);
        $metaMarketplaceId = $metaProviderData['MetaProvider']['meta_marketplace_id'];
        //check permissions and redirect if should not be here
        try {
            $this->checkPermissions($metaMarketplaceId, $this->uid, $this->canAccessAdm);
        } catch (Exception $ex) {
            $this->Flash->danger(__('Você não está autorizado a acessar essa página.'));
            return $this->redirect(array('controller' => 'main','action' => 'index'));
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->MetaProvider->save($this->request->data)) {
                $this->Flash->success(__('O MetaProvider foi alterado'));
                return $this->redirect(array('controller' => 'meta_marketplaces', 'action' => 'detail', $metaMarketplaceId));
            } else {
                $this->Flash->danger(__('Não foi possível alterar o MetaProvider. Favor tentar novamente.'));
            }
        } else {
            $this->request->data = $metaProviderData;
        }
        $this->set('optionsServiceTypes', $this->listServiceTypesOfMetaMarketplace($metaMarketplaceId));
        if (isset($metaProviderData['ServiceType']['0'])) {

            $serviceTypes = $metaProviderData['ServiceType'];
            $selected = array();
            foreach ($serviceTypes as $key => $serviceTypeData) {
                $selected[] = $serviceTypeData['ServiceTypesMetaProvider']['service_type_id'];
            }


            $this->set('selectedServiceTypes', $selected);
        } else {
            $this->set('selectedServiceTypes', array());
        }
        $this->set('metaMarketplaceId', $metaMarketplaceId);

        $this->set('paymentMethods', $this->listPaymentMethods());

    }

    private function listServiceTypesOfMetaMarketplace($metaMarketplaceId)
    {
        $this->loadModel('ServiceType');
        $this->ServiceType->recursive = -1;
        $serviceTypes = $this->ServiceType->find(
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
        return $serviceTypes;

    }

    private function checkPermissions($id, $userId, $adminPermission)
    {
        $isTheOwner = $this->MetaProvider->MetaMarketplace->isUserTheOwner($id, $userId);
        
        if (!$isTheOwner && !$adminPermission) {
            $this->Flash->danger(__('Você não está autorizado a acessar essa função.'));
            return $this->redirect($this->Auth->loginRedirect);
        }
        
        return true;
        
    }
    
    private function listPaymentMethods()
    {
        $this->loadModel('PaymentMethod');
        $this->PaymentMethod->recursive = -1;
        $paymentMethods = $this->PaymentMethod->find(
			'list',
			array(
				'fields' => array(
					'id',
					'name'
					),
                                'order' => 'PaymentMethod.name',
				)
			);
        return $paymentMethods;
        
    }


}
