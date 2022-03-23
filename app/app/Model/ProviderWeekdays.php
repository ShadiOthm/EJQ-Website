<?php

App::uses('AppModel', 'Model');

class ProviderWeekdays extends AppModel {

	public $useTable = 'providers_weekdays';
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
		'weekdays' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please inform a name do atributo',
				),
			),
		);

    public $belongsTo = array(
            'Marketplace' => array(
                    'className' => 'Marketplace',
                    'foreignKey' => 'marketplace_id',
            ),
            'Provider' => array(
                    'className' => 'Provider',
                    'foreignKey' => 'provider_id',
            ),
            'ServiceType' => array(
                    'className' => 'ServiceType',
                    'foreignKey' => 'service_type_id',
            ),
    );

}
