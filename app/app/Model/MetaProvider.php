<?php

App::uses('AppModel', 'Model');

class MetaProvider extends AppModel {

	public $useTable = 'meta_providers';
	public $displayField = 'name';

	public function parentNode() {
		return null;
	}
        
        public $actsAs = array('Containable');

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
		'identification' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Favor informar a identificação',
				),
			)
		);

    public $belongsTo = array(
            'MetaMarketplace' => array(
                    'className' => 'MetaMarketplace',
                    'foreignKey' => 'meta_marketplace_id',
            ),
            'PaymentMethod' => array(
                'className' => 'PaymentMethod',
                'foreignKey' => 'payment_method_id',
            ),
    );
    
    
    public $hasMany = array(
    );    
    

    

    public function beforeSave($options = array()) {

        if (isset($this->data['MetaProvider']['name'])) {
            $id = NULL;
            if (isset($this->data['MetaProvider']['id'])) {
                $id = $this->data['MetaProvider']['id'];
            }
            $this->data['MetaProvider']['slug'] = $this->createSlug($this->data['MetaProvider']['name'], $id);
            return true;
        }            

        parent::beforeSave($options);
    }

        
        

}
