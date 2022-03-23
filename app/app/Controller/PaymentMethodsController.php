<?php

App::uses('AppController', 'Controller');

class PaymentMethodsController extends AppController {

    public $components = array('Paginator');

    public function beforeFilter() {
        parent::beforeFilter();        
    }

    public function index() {
        $this->PaymentMethod->recursive = 0;
        $this->set('paymentMethods', $this->Paginator->paginate());
    }

    public function create() {
        if ($this->request->is(array('post', 'put'))) {
            $this->PaymentMethod->create();
            if ($this->PaymentMethod->save($this->request->data)) {
                $this->Flash->danger(__('O Modo de Pagamento foi adicionado'));
                return $this->redirect(array('action' => 'index'));
            } else {
                    $this->Flash->danger(__('Não foi possível adicionar o Modo de Pagamento. Favor tentar novamente'));
            }
        }


    }

    public function update($id = null) {
        if (!$this->PaymentMethod->exists($id)) {
            throw new NotFoundException('Modo de Pagamento inválido');
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->PaymentMethod->save($this->request->data)) {
                $this->Flash->success(__('O Modo de Pagamento foi alterado'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->danger(__('Não foi possível alterar o Modo de Pagamento. Favor tentar novamente.'));
            }
        } else {
            $options = array('conditions' => array('PaymentMethod.' . $this->PaymentMethod->primaryKey => $id));
            $this->request->data = $this->PaymentMethod->find('first', $options);
        }
    }

    public function delete($id = null) {
        $this->PaymentMethod->id = $id;
        if (!$this->PaymentMethod->exists()) {
            throw new NotFoundException('Modo de Pagamento inválido');
        }
        if ($this->PaymentMethod->delete()) {
            $this->Flash->success(__('O Modo de Pagamento foi excluído'));
        } else {
            $this->Flash->danger(__('Não foi possível excluir o Modo de Pagamento. Favor tentar novamente.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

}
