<?php

App::uses('AppModel', 'Model');

class Compliance extends AppModel {

    public $useTable = 'compliances';
    public $actsAs = array('Containable');

    public function parentNode() {
            return null;
    }

    public $validate = array(
    );

    public $belongsTo = array(
        'Bid' => array(
                'className' => 'Bid',
                'foreignKey' => 'bid_id',
        ),
        'Provider' => array(
                'className' => 'Provider',
                'foreignKey' => 'provider_id',
        ),
        'Tender' => array(
                'className' => 'Tender',
                'foreignKey' => 'tender_id',
        ),
    );


}
