<?php

App::uses('Component', 'Controller');
class RegisterComponent extends Component {

    public $controller;
    
    public $data;
    
    private $userId = null;
    private $sendToken = false;
    private $status = "NIL";

    public function __construct(ComponentCollection $collection, $settings = array())
    {
        
        $this->controller = $collection->getController();
        
        $this->initData();

        parent::__construct($collection, array_merge($this->settings, (array)$settings));        
        
    }
    
    public function register($email, $name, User $UserModel)
    {
        $this->userId = null;
        $this->data['User']['name'] = $name;
        $this->data['User']['email'] = $email;
        
        $this->User = $UserModel;
        
        if (!$this->tryToSave()) {
            $this->tryToFind();
        }
        
        
        
        return array('status' => $this->status, 'userId' => $this->userId, 'sendToken' => $this->sendToken);
        
        
    }
    
    private function initData()
    {
        $this->data = array(
            'User' => array(
                'active' =>  0, 
                'confirmed' =>  REGISTER_UNCONFIRMED, 
                'group_id' =>  USERS_GROUP,
            ),
        );
        
    }
    
    private function tryToFind()
    {
        $email = $this->data['User']['email'];
        $user = $this->User->findByEmail($email);
        if($user) {
            if ($user['User']['confirmed'] == REGISTER_UNCONFIRMED) {
                $this->userId = $user['User']['id'];
                $this->sendToken = true;
                $this->User->id = $this->userId;
                $this->status = 'UNCONFIRMED';
                //$this->User->saveField('active', '0');
            } else {
                $this->status = 'EXISTING';
            }
        }
        
    }
    
    private function tryToSave()
    {
        if ($this->User->save($this->data)) {
            $this->sendToken = true;
            $this->userId = $this->User->id;
            $this->status = 'NEW';
            return true;
        } else {
            return false;
        }
        
    }
    
}
    