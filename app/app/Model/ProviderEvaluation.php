<?php

App::uses('AppModel', 'Model');

class ProviderEvaluation extends AppModel 
{

	public $useTable = 'providers_service_types';
        public $actsAs = array('Containable');

	public function parentNode() {
		return null;
	}

	public $validate = array(
            );

    public $belongsTo = array(
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
