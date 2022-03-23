<?php

App::uses('AppModel', 'Model');

class ServiceType extends AppModel {

    public $useTable = 'service_types';
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
            'description' => array(
                    'notBlank' => array(
                            'rule' => array('notBlank'),
                            'message' => 'Favor informar a descrição',
                            ),
                    )
            );

    public $belongsTo = array(
            'MetaMarketplace' => array(
                    'className' => 'MetaMarketplace',
                    'foreignKey' => 'meta_marketplace_id',
            ),
    );
    
    public $hasAndBelongsToMany = array(
        'Demand' =>
            array(
                'className' => 'Demand',
                'joinTable' => 'demands_service_types',
                'foreignKey' => 'service_type_id',
                'associationForeignKey' => 'demand_id',
                'unique' => 'keepexisting',
            ),
        'Marketplace' =>
            array(
                'className' => 'Marketplace',
                'joinTable' => 'marketplaces_service_types',
                'foreignKey' => 'service_type_id',
                'associationForeignKey' => 'marketplace_id',
                'unique' => true,
            ),
        'Provider' =>
            array(
                'className' => 'Provider',
                'joinTable' => 'providers_service_types',
                'foreignKey' => 'service_type_id',
                'associationForeignKey' => 'provider_id',
                'unique' => true,
            ),
    );    
    
    
    
    public $hasMany = array(
            'MetaProvider' => array(
                    'className' => 'MetaProvider',
                    'foreignKey' => 'service_type_id',
            ),
            'Schedule' =>
                array(
                    'className' => 'DemandSchedule',
                    'foreignKey' => 'service_type_id',
                    'conditions' => array('active' => '1', 'removed' => '0'),
                ),
        
    );

    public function beforeSave($options = array()) {

        if (isset($this->data['ServiceType']['name'])) {
            $id = NULL;
            if (isset($this->data['ServiceType']['id'])) {
                $id = $this->data['ServiceType']['id'];
            }
            $this->data['ServiceType']['slug'] = $this->createSlug($this->data['ServiceType']['name'], $id);
            return true;
        }            

        parent::beforeSave($options);
    }

    public function listByMetaMarketplace($metaMarketplaceId)
    {
        $this->recursive = -1;
        $serviceTypes = $this->find(
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
