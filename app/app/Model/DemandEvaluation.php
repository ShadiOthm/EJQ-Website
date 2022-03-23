<?php

App::uses('AppModel', 'Model');

class DemandEvaluation extends AppModel {

	public $useTable = 'demands_service_types';
        public $actsAs = array('Containable');

	public function parentNode() {
		return null;
	}

	public $validate = array(
            );

    public $belongsTo = array(
            'Demand' => array(
                    'className' => 'Demand',
                    'foreignKey' => 'demand_id',
            ),
            'ServiceType' => array(
                    'className' => 'ServiceType',
                    'foreignKey' => 'service_type_id',
            ),
    );
    
    
}
