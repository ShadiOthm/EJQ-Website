<?php

App::uses('AppController', 'Controller');

class GroupsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function index() {
		$this->Group->recursive = 0;
		$this->set('groups', $this->Paginator->paginate());
	}

	public function create() {
		if ($this->request->is('post')) {
			$this->Group->create();
			if ($this->Group->save($this->request->data)) {
				$this->Flash->success(__('O Grupo foi adicionado'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->danger(__('Não foi possível adicionar o Grupo. Favor tentar novamente'));
			}
		}
	}

	public function update($id = null) {
		if (!$this->Group->exists($id)) {
			throw new NotFoundException('Grupo inválido');
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Group->save($this->request->data)) {
				$this->Flash->success(__('O Grupo foi alterado'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->danger(__('Não foi possível alterar o Grupo. Favor tentar novamente.'));
			}
		} else {
			$options = array('conditions' => array('Group.' . $this->Group->primaryKey => $id));
			$this->request->data = $this->Group->find('first', $options);
		}
	}

	public function delete($id = null) {
		$this->Group->id = $id;
		if (!$this->Group->exists()) {
                    throw new NotFoundException('Grupo inválido');
		}
		if ($this->Group->delete()) {
                    $this->Flash->success(__('O Grupo foi excluído'));
		} else {
                    $this->Flash->danger(__('Não foi possível excluir o Grupo. Favor tentar novamente.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

}
