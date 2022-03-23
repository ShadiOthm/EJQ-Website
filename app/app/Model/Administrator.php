<?php

App::uses('AppModel', 'Model');

class Administrator extends AppModel {

    public $useTable = 'administrators';
    public $displayField = 'name';
    public $actsAs = array('Containable');

    public function parentNode() {
            return null;
    }

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
                    'notBlank' => array(
                            'rule' => array('notBlank'),
                            'message' => 'Please inform a name',
                            ),
                    ),
            );

    public $virtualFields = array(
    );        

    public $belongsTo = array(
        'Marketplace' => array(
                'className' => 'Marketplace',
                'foreignKey' => 'marketplace_id',
        ),
        'User' => array(
                'className' => 'User',
                'foreignKey' => 'user_id',
        ),
    );
    
    public $hasAndBelongsToMany = array();    
    
    public $hasMany = array();    
    
    public $hasOne = array();

    public function beforeSave($options = array()) {

        if (isset($this->data['Provider']['name'])) {
            $id = NULL;
            if (isset($this->data['Provider']['id'])) {
                $id = $this->data['Provider']['id'];
            }
            $this->data['Provider']['slug'] = $this->createSlug($this->data['Provider']['name'], $id);
            return true;
        }            

        parent::beforeSave($options);
    }
    
    public function getAdministratorByMarketplaceAndUserId($marketplaceId, $userId) {
        
        if(empty($marketplaceId)) return false;
        if(empty($userId)) return false;
        
        $administrator = $this->find('first', 
                array(
                    'fields' => array('id'),
                    'conditions' => array('Administrator.marketplace_id' => $marketplaceId, 'user_id' => $userId),
                    'contain' => array(
                        ),
                    ));
        
        if (isset($administrator['Administrator'])) {
            return $administrator;
        } else {
            return false;
        }
    }

    
    
    
}
