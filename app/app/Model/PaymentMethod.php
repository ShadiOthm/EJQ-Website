<?php

App::uses('AppModel', 'Model');

class PaymentMethod extends AppModel {

	public $useTable = 'payment_methods';
	public $displayField = 'name';

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

    public $hasMany = array(
            'MetaProvider' => array(
                    'className' => 'MetaProvider',
                    'foreignKey' => 'service_type_id',
            ),
            'Provider' => array(
                    'className' => 'Provider',
                    'foreignKey' => 'service_type_id',
            ),
    );

    public function beforeSave($options = array()) {

        if (isset($this->data['PaymentMethod']['name'])) {
            $id = NULL;
            if (isset($this->data['PaymentMethod']['id'])) {
                $id = $this->data['PaymentMethod']['id'];
            }
            $this->data['PaymentMethod']['slug'] = $this->createSlug($this->data['PaymentMethod']['name'], $id);
            return true;
        }            

        parent::beforeSave($options);
    }

        
        

}
