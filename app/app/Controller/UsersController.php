<?php

App::uses('AppController', 'Controller');

class UsersController extends AppController {

    public $components = array('Paginator');

    public function beforeFilter() {
        $this->Auth->allow('login', 'logout', 'dashboard', 'recover_password', 'register', 'confirm', 'thanks', 'invalid', 'index', 'delete');
        
        parent::beforeFilter();
    }

    public function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->Paginator->paginate());
    }

    public function create() {

        if($this->Session->read('Auth.User.Group.id') == '1'){
            $groupsConditions = array();
        }else{
            $groupsConditions = array(
                'NOT' => array(
                    'Group.id' => '1'
                    )
                );
        }
        $groupsConditions = array();

        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Flash->success(__('O Usuário foi adicionado'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->danger(__('Não foi possível adicionar o Usuário. Favor tentar novamente'));
            }
        }

        $groups = $this->User->Group->find(
            'list',
            array(
                'fields' => array(
                    'id', 
                    'name'
                    ),
                'conditions' => $groupsConditions
                )
            );

        $this->set(compact('groups'));

    }
        
    public function delete($id = null) {
        if ($this->canAccessAdm) {
            $this->User->id = $id;
            if (!$this->User->exists()) {
                throw new NotFoundException('Usuário inválido');
            }
            if ($this->User->delete()) {
                $this->Flash->success(__('O Usuário foi excluído'));
            } else {
                $this->Flash->danger(__('Não foi possível excluir o Usuário. Favor tentar novamente.'));
            }
        } else {
                $this->Flash->danger(__('Sorry, only Administrators can delete users.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function login() 
    {
        $this->layout = 'login';
        $this->set('hereTitle', __('Login'));
        
        $titleBox['h1'] = __('Login');
        $this->set('titleBox', $titleBox);
        
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Auth->login()) {
                
                if (!empty($this->request->data['User']['redirect_url_after_login'])) {

                    $redirectUrlAfterLogin = $this->request->data['User']['redirect_url_after_login'];
                    if(!empty($redirectUrlAfterLogin)
                        &&filter_var($redirectUrlAfterLogin, FILTER_VALIDATE_URL)
                        &&parse_url($redirectUrlAfterLogin, PHP_URL_HOST)==$_SERVER['HTTP_HOST']) {
                            return $this->redirect($redirectUrlAfterLogin);
                    }
                }
                $this->Session->delete('redirect_url_after_login');
                //$this->redirect( $this->referer() );                
                $this->redirectAccordingToUserRole();
                                
            }else{
                $this->Flash->danger(__('Não foi possível autenticar seu login. Favor tentar novamente.'));
            }
        }
        
    }

    public function logout() {
        $this->Session->destroy();
        return $this->redirect('/');
    }

    public function recover_password() 
    {
        $this->set('hereTitle', __('Recover Password'));
        
        $titleBox['h1'] = __('Recover Password');
        $titleBox['h2'] = __('Enter your registered email below for instructions on how to recover your password.');
        $this->set('titleBox', $titleBox);
        
         if ($this->request->is(array('post', 'put'))) {

            $email = $this->request->data['User']['email_recover'];

            $this->User->set($this->request->data);

            if ($this->User->validates(array('fieldList' => array('email_recover')))) {

                $this->User->recursive = -1;
                $user = $this->User->findByEmail($email);

                if ($user) {
                    $this->loadmodel('Token');
                    $senderComponent = $this->Components->load('SendToken');
                    $sendResult = $senderComponent->send($email, 
                            $user['User']['name'], 
                            $user['User']['id'], 
                            $this->Token,
                            true
                            );

                    $this->render('return');

                } else {
                        $this->User->invalidate('email_recover', __('This email was not found in our records. please try again or contact our support.'));
                }
        } else {
//$this->User->invalidate( 'recuperaremail', 'Favor informar um email válido. Favor tentar novamente. ' );
        }
    }
    }
    
    public function confirm($t = null) 
    {
        $this->Session->destroy();
        $this->set('hereTitle', __('Create New Password'));
        $this->loadModel('Token');

        $this->Token->recursive = -1;
        $token = $this->Token->findByToken($t);

        if (!$token) {
            $this->render('invalid');
        } else {
            //checa validade do token
            $now = strtotime('now');
            $tokenExpiresAt = strtotime('+7 day', strtotime($token['Token']['modified']));
            if ($now > $tokenExpiresAt) {
                $this->render('invalid');
            } else {
                $user = $this->User->findByEmail($token['Token']['email']);
                if (!$user) {
                    $this->render('invalid');
                } else {
                    $name = $user['User']['name'];
                    $titleBox['h1'] = sprintf(__('Welcome %s!'), $name);
                    $titleBox['h2'] = __('Please create a new password and repeat it to confirm.');
                    $this->set('titleBox', $titleBox);
        
                    if ($this->request->is('post') || $this->request->is('put')) {

                        $this->User->set($this->request->data);

                        if ($this->User->validates(array('fieldList' => array('newpassword', 'confirmpassword')))) {

                            $user['User']['password'] = $this->request->data['User']['newpassword'];
                            $user['User']['confirmed'] = REGISTER_CONFIRMED;
                            $user['User']['active'] =  1;
                            if ($this->User->save($user)) {
                                $this->request->data['User']['password'] = $this->request->data['User']['newpassword'];
                                $this->request->data['User']['email'] = $user['User']['email'];
                                $this->Token->id = $token['Token']['id'];
                                $this->Token->delete();
                                $user['User']['id'] = $this->User->id;

                                if ($this->Auth->login()) {
                                    $this->redirectAccordingToUserRole();
//                                    $this->Auth->loginRedirect = array('controller' => 'main', 'action' => 'index', 'adm' => false);
//                                    $this->redirect($this->Auth->redirect());
                                } else {
                                    $this->Flash->danger(__('Não foi possível autenticar seu acesso.<br/> Favor tentar novamente.'));
                                }

                                //$this->redirect(array('controller' => 'perfil', 'action' => 'index'));
                            } else {
                                $this->Flash->danger(__('Não foi possível confirmar seu cadastro. Favor tentar novamente ou entrar em contato com o suporte.'));
                            }
                        } else {
                            $this->set('t', $t);
                        }
                    } else {
                        $this->set('t', $t);
                    }
                }
            }
	}
    }
    
    public function thanks()
    {
        
    }


        
        

    public function update($id = null) {
        if (!$this->User->exists($id)) {
            throw new NotFoundException('Usuário inválido');
        }

        if($this->Session->read('Auth.User.Group.id') == SUPERUSERS_GROUP){
            $groupsConditions = array();
        }else{
            $groupsConditions = array(
                'NOT' => array(
                    'Group.id' => SUPERUSERS_GROUP
                )
            );
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->User->save($this->request->data)) {
                $this->Flash->success(__('O Usuário foi alterado'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->danger(__('Não foi possível alterar o Usuário. Favor tentar novamente.'));
            }
        } else {
            $options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
            $this->request->data = $this->User->find('first', $options);
        }

        $groups = $this->User->Group->find(
            'list',
            array(
                'fields' => array(
                    'id', 
                    'alias'
                    ),
                'conditions' => $groupsConditions
                )
            );

        $this->set('groups', $groups);
    }
    
    private function checkPermissions($profileUserId, $loggedUserId, $adminPermission)
    {
        $isTheOwner = ($profileUserId == $loggedUserId);
        
        if (!$isTheOwner && !$adminPermission) {
            $this->Flash->danger(__('Você não está autorizado a acessar essa função.'));
            return $this->redirect($this->Auth->loginRedirect);
        }
        
        return true;
        
    }
    
    private function redirectAccordingToUserRole()
    {
        $this->loadModel('Marketplace');
        $result = $this->Marketplace->userRole($this->EjqMarketplaceId, $this->Auth->user('id'));
        if (isset($result['Administrator'])) {                
            return $this->redirect(array('controller' => 'marketplaces', 'action' => 'dashboard', $this->EjqMarketplaceId));
        } elseif (isset($result['Consumer'])) {                
            return $this->redirect(array('controller' => 'consumers', 'action' => 'dashboard', $result['Consumer']['id']));
        } elseif (isset($result['Provider'])) {
            if($result['Provider']['meta_provider_id'] == $this->EjqContractorMetaProviderId
                    && $result['Provider']['good_standing'] === FALSE) {
                $this->Session->destroy();
                $this->Flash->danger(__('Please pay overdue amount to regain access to the system.'));
                return $this->redirect('/');
            } 
            return $this->redirect(array('controller' => 'providers', 'action' => 'dashboard', $result['Provider']['id']));
        } else {
            return $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
        
    }
    
    

}
