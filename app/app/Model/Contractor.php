<?php

App::uses('AppModel', 'Model');

class Contractor extends AppModel {

    public $useTable = 'contractors';
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
        'Municipality' => array(
                'className' => 'Municipality',
                'foreignKey' => 'municipality_id',
        ),
        'Provider' => array(
                'className' => 'Provider',
                'foreignKey' => 'provider_id',
        ),
    );
    
    public $hasAndBelongsToMany = array();    
    
    public $hasMany = array();    
    
    public $hasOne = array();

    public function beforeSave($options = array()) {

        if (isset($this->data['Contractor']['name'])) {
            $id = NULL;
            if (isset($this->data['Contractor']['id'])) {
                $id = $this->data['Contractor']['id'];
            }
            $this->data['Contractor']['slug'] = $this->createSlug($this->data['Contractor']['name'], $id);
            return true;
        }            

        parent::beforeSave($options);
    }
    
    public function getContractorByMarketplaceAndProviderId($marketplaceId, $providerId) {

        if(empty($marketplaceId)) return false;
        if(empty($providerId)) return false;
        
        $contractor = $this->find('first', 
                array(
                    'fields' => array('id'),
                    'conditions' => array('Contractor.marketplace_id' => $marketplaceId, 'provider_id' => $providerId),
                    'contain' => array(),
                    ));

        if (isset($contractor['Contractor'])) {
            return $contractor;
        } else {
            return false;
        }
    }
    
    
}
