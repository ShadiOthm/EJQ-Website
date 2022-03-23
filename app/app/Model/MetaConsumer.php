<?php

App::uses('AppModel', 'Model');

class MetaConsumer extends AppModel {

	public $useTable = 'meta_consumers';
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

        
    public function beforeSave($options = array()) {

        if (isset($this->data['MetaConsumer']['name'])) {
            $id = NULL;
            if (isset($this->data['MetaConsumer']['id'])) {
                $id = $this->data['MetaConsumer']['id'];
            }
            $this->data['MetaConsumer']['slug'] = $this->createSlug($this->data['MetaConsumer']['name'], $id);
            return true;
        }            

        parent::beforeSave($options);
    }

        
        

}
