<?php

App::uses('AppModel', 'Model', 'AuthComponent', 'Controller/Component');

/**
 * User Model
 *
 */
class User extends AppModel {

    public $useTable = 'users';
    public $displayField = 'name';
    public $dados = "";

    public $actsAs = array('Containable', 'Acl' => array('type' => 'requester'));

    public function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        if (isset($this->data['User']['group_id'])) {
            $groupId = $this->data['User']['group_id'];
        } else {
            $groupId = $this->field('group_id');
        }
        if (!$groupId) {
            return null;
        } else {
            return array('Group' => array('id' => $groupId));
        }
    }

    public $belongsTo = array(
        'Group' => array(
                    'className' => 'Group',
                    'foreignKey' => 'group_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => ''
                ),
    );
    
    public $hasAndBelongsToMany = array(
        'MarketplaceAsConsumer' =>
            array(
                'className' => 'Marketplace',
                'joinTable' => 'consumers',
                'foreignKey' => 'user_id',
                'associationForeignKey' => 'marketplace_id',
                'unique' => 'keepExisting',
            ),
        'MarketplaceAsProvider' =>
            array(
                'className' => 'Marketplace',
                'joinTable' => 'providers',
                'foreignKey' => 'user_id',
                'associationForeignKey' => 'marketplace_id',
                'unique' => 'keepExisting',
            ),
    );

    
    

    public $hasMany = array(
        'Administrator' =>
            array(
                'className' => 'Administrator',
                'foreignKey' => 'user_id',
            ),
        'Consumer' =>
            array(
                'className' => 'Consumer',
                'foreignKey' => 'user_id',
            ),
        'Curator' =>
            array(
                'className' => 'Curator',
                'foreignKey' => 'user_id',
            ),
        'Provider' =>
            array(
                'className' => 'Provider',
                'foreignKey' => 'user_id',
            ),
    );    
    
    public $validate = array(
        'active' => array(
            'numeric' => array(
                    'rule' => array('numeric'),
                    ),
        ),
        'removed' => array(
            'numeric' => array(
                    'rule' => array('numeric'),
                    ),
        ),
        'name' => array(
            'notempty' => array(
                'rule' => array('notBlank'),
                'message' => 'Please inform a name',
                ),
        ),
        'email' => array(
                    'notempty' => array(
                        'rule' => 'notBlank',
                        'message' => 'Favor informar o e-mail',
                        'last' => true
                            ),
                    'email' => array(
                        'rule' => 'email',
                        'message' => 'Favor informar um e-mail válido',
                        'last' => true
                            ),
                    'unique' => array(
                        'rule' => 'isUnique',
                        'message' => 'Esse e-mail já consta de nossos cadastros',
                        'last' => true
                            )
            ),
        'recuperaremail' => array(
            'notempty' => array(
                        'rule' => 'notBlank',
                        'message' => 'Favor informar um email.',
                        'last' => true
                    ),
                    'email' => array(
                        'rule' => 'email',
                        'message' => 'Favor informar um email válido.',
                        'last' => true
                            )
            ),
        'newpassword' => array(
                    'password' => array(
                        'rule' => 'confirmNewpassword',
                        'message' => false,
                        'last' => true
                            )
            ),
        'confirmpassword' => array(
                    'confirmar' => array(
                        'rule' => 'confirmNewPassword',
                        'message' => 'Sua senha não confere',
                        'last' => true
                            )
            ),
        );	

    function confirmNewPassword($data) {

        $valid = false;

        if ($this->data['User']['newpassword'] || $this->data['User']['confirmpassword']) {
            if ($this->data['User']['newpassword'] == $this->data['User']['confirmpassword']) {
                    $valid = true;
            }
        } else {
            $valid = true;
        }

        return $valid;
    }

    public function beforeSave($options = array()) {

        if (isset($this->data['User']['newpassword']) && $this->data['User']['newpassword']) {
            $this->data['User']['password'] = $this->data['User']['newpassword'];
        }
        if (isset($this->data['User']['password'])) {
            $this->data['User']['password'] = AuthComponent::password(
                $this->data['User']['password']
                );
        }
        return true;
    }
    
    public function isACurator($id)
    {
        $options = array(
                    'fields' => array('id', 'user_id'),
                    'contain' => array(),
                    'conditions' => array('user_id' => $id)
                    );
        
        //$curator = $this->Curator->findByUserId($id);
        $curator = $this->Curator->find('first', $options);
        if (!empty($curator)) {
            return $curator;
        } else {
            return false;
        }
        
    }
    
    public function marketplacesAsConsumer($id=null) 
    {
        
        if (!preg_match('/^\d+$/', $id)) {
            throw new NotFoundException('Id inválido');
        }
        
        $options = array(
                    'fields' => array('User.id'),
                    'contain' => array(
                        'MarketplaceAsConsumer.id', 
                        'MarketplaceAsConsumer.name',
                        ),
                    'conditions' => array(
                        'User.active' => '1',
                        'User.id' => $id)
                    );
        
        
        
        // find all
        
        //$this->virtualFields['Marketplace.id'] = 'MarketplaceAsConsumer.id';
        $allConsumerMarketplaces = $this->find('first', 
                $options);
        $marketplacesAsConsumer = array();
        foreach ($allConsumerMarketplaces['MarketplaceAsConsumer'] as $marketplace) {
            if (isset($marketplace['Consumer'])) {
                $consumerId = $marketplace['Consumer']['id'];
                unset($marketplace['Consumer']);
                $consumerData = array('id' => $consumerId);
            } else {
                $consumerData = null;
            }
            $marketplacesAsConsumer[] = array('Marketplace' => $marketplace, 'Consumer' => $consumerData);
        }
        
        
        return $marketplacesAsConsumer;
        
    }
    
    
    public function marketplacesAsProvider($id=null) 
    {
        
        if (!preg_match('/^\d+$/', $id)) {
            throw new NotFoundException('Id inválido');
        }
        
        $options = array(
                    'fields' => array('User.id'),
                    'contain' => array(
                        'MarketplaceAsProvider.id', 
                        'MarketplaceAsProvider.name',
                        ),
                    'conditions' => array(
                        'User.active' => '1',
                        'User.id' => $id)
                    );
        
        
        
        // find all
        
        //$this->virtualFields['Marketplace.id'] = 'MarketplaceAsProvider.id';
        $allProviderMarketplaces = $this->find('first', 
                $options);
        $marketplacesAsProvider = array();
        foreach ($allProviderMarketplaces['MarketplaceAsProvider'] as $marketplace) {
            if (isset($marketplace['Provider'])) {
                $providerId = $marketplace['Provider']['id'];
                unset($marketplace['Provider']);
                $providerData = array('id' => $providerId);
            } else {
                $providerData = null;
            }
            $marketplacesAsProvider[] = array('Marketplace' => $marketplace, 'Provider' => $providerData);
        }
        
        
        return $marketplacesAsProvider;
        
    }
    
    
    

}
