<?php

App::uses('AppController', 'Controller');

class MetaConsumersController extends AppController {

    public $components = array('Paginator');

    public function beforeFilter() {
        $this->Auth->allow(array('create', 'delete', 'detail', 'update'));
        
        parent::beforeFilter();
    }

    public function index() {
        $this->MetaConsumer->recursive = 0;
        $this->set('metaConsumers', $this->Paginator->paginate());
    }

    public function create($metaMarketplaceId) {

        if (!$this->MetaConsumer->MetaMarketplace->exists($metaMarketplaceId)) {
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

            $data = array('0' => array(
                'MetaConsumer' => $this->request->data,
            ));
            if ($this->MetaConsumer->saveAll($data, array('deep' => true))) {
                $this->Flash->success(__('O MetaConsumer foi adicionado'));
                return $this->redirect(array('controller' => 'meta_marketplaces','action' => 'detail', $metaMarketplaceId));
            } else {
                    $this->Flash->danger(__('Não foi possível adicionar o MetaConsumer. Favor tentar novamente'));
            }
        }

        $this->set('optionsServiceTypes', $this->listServiceTypesOfMetaMarketplace($metaMarketplaceId));
        $this->set('selectedServiceTypes', array());

        $this->set('paymentMethods', $this->listPaymentMethods());
        $this->set('metaMarketplaceId', $metaMarketplaceId);

    }

    public function delete($id = null) {
        if (!$this->MetaConsumer->exists($id)) {
            throw new NotFoundException('MetaConsumer inválido');
        }

        $this->MetaConsumer->id = $id;
        $metaMarketplaceId = $this->MetaConsumer->field('meta_marketplace_id');
        //check permissions and redirect if should not be here
        try {
            $this->checkPermissions($metaMarketplaceId, $this->uid, $this->canAccessAdm);
        } catch (Exception $ex) {
                $this->Flash->danger(__('Você não está autorizado a acessar essa página.'));
                return $this->redirect(array('controller' => 'main','action' => 'index'));
        }
        
        if ($this->MetaConsumer->delete()) {
            $this->Flash->success(__('O MetaConsumer foi excluído'));
        } else {
            $this->Flash->danger(__('Não foi possível excluir o MetaConsumer. Favor tentar novamente.'));
        }
        return $this->redirect(array('controller' => 'meta_marketplaces', 'action' => 'detail', $metaMarketplaceId));
    }

    public function detail($id)
    {
        if (!$this->MetaConsumer->exists($id)) {
            throw new NotFoundException('MetaConsumer inválido');
        }
        $options = array('conditions' => array('MetaConsumer.' . $this->MetaConsumer->primaryKey => $id));
        $metaConsumerData = $this->MetaConsumer->find('first', $options);
        $this->set('metaConsumer', $metaConsumerData);
        //check permissions and redirect if should not be here
        try {
            $this->checkPermissions($metaConsumerData['MetaConsumer']['meta_marketplace_id'], $this->uid, $this->canAccessAdm);
        } catch (Exception $ex) {
                $this->Flash->danger(__('Você não está autorizado a acessar essa página.'));
                return $this->redirect(array('controller' => 'main','action' => 'index'));
        }

    }

    public function update($id = null) {
        if (!$this->MetaConsumer->exists($id)) {
            throw new NotFoundException('MetaConsumer inválido');
        }
        
        $options = array('conditions' => array('MetaConsumer.' . $this->MetaConsumer->primaryKey => $id));
        $metaConsumerData = $this->MetaConsumer->find('first', $options);
        $metaMarketplaceId = $metaConsumerData['MetaConsumer']['meta_marketplace_id'];

        //check permissions and redirect if should not be here
        try {
            $this->checkPermissions($metaMarketplaceId, $this->uid, $this->canAccessAdm);
        } catch (Exception $ex) {
                $this->Flash->danger(__('Você não está autorizado a acessar essa página.'));
                return $this->redirect(array('controller' => 'main','action' => 'index'));
        }
        
        if ($this->request->is(array('post', 'put'))) {
            if ($this->MetaConsumer->save($this->request->data)) {
                $this->Flash->success(__('O MetaConsumer foi alterado'));
                return $this->redirect(array('controller' => 'meta_marketplaces', 'action' => 'detail', $metaMarketplaceId));
            } else {
                $this->Flash->danger(__('Não foi possível alterar o MetaConsumer. Favor tentar novamente.'));
            }
        } else {
            $this->request->data = $metaConsumerData;
        }
        $this->set('optionsServiceTypes', $this->listServiceTypesOfMetaMarketplace($metaMarketplaceId));
        if (isset($metaConsumerData['ServiceType']['0'])) {

            $serviceTypes = $metaConsumerData['ServiceType'];
            $selected = array();
            foreach ($serviceTypes as $key => $serviceTypeData) {
                $selected[] = $serviceTypeData['ServiceTypesMetaConsumer']['service_type_id'];
            }


            $this->set('selectedServiceTypes', $selected);
        } else {
            $this->set('selectedServiceTypes', array());
        }

        $this->set('metaMarketplaceId', $metaMarketplaceId);
        
        $this->set('paymentMethods', $this->listPaymentMethods());
    }

    private function checkPermissions($id, $userId, $adminPermission)
    {
        $isTheOwner = $this->MetaConsumer->MetaMarketplace->isUserTheOwner($id, $userId);
        
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

}
