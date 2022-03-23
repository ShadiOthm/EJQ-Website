<?php

App::uses('AppModel', 'Model');

class Suitability extends AppModel {

	public $useTable = 'suitabilities';
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
    );
    
    
}
