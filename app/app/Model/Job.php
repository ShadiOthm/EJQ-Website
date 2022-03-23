<?php

App::uses('AppModel', 'Model');

class Job extends AppModel {

    public $useTable = 'jobs';
    public $actsAs = ['Containable'];

    public function parentNode() {
            return null;
    }

    public $validate = [];

    public $belongsTo = [
        'Consumer' => [
                'className' => 'Consumer',
                'foreignKey' => 'consumer_id',
        ],
        'Demand' => [
                'className' => 'Demand',
                'foreignKey' => 'demand_id',
        ],
        'Marketplace' => [
                'className' => 'Marketplace',
                'foreignKey' => 'marketplace_id',
        ],
        'Provider' => [
                'className' => 'Provider',
                'foreignKey' => 'provider_id',
        ],
        'Tender' => [
                'className' => 'Tender',
                'foreignKey' => 'tender_id',
        ],
    ];

    public $hasMany = [
        'Review' =>
            array(
                'className' => 'Review',
                'foreignKey' => 'job_id',
                'conditions' => array('Review.active' => '1', 'Review.removed' => '0'),
            ),
    ];

}
