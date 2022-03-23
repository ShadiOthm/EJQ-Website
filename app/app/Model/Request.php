<?php

App::uses('AppModel', 'Model');

class Request extends AppModel {

    public $useTable = 'requests';
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
        'Municipality' => array(
                'className' => 'Municipality',
                'foreignKey' => 'municipality_id',
        ),
    );


}
