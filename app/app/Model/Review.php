<?php

App::uses('AppModel', 'Model');

class Review extends AppModel {

    public $useTable = 'reviews';
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
        'Provider' => [
                'className' => 'Provider',
                'foreignKey' => 'provider_id',
        ],
        'Job' => [
                'className' => 'Job',
                'foreignKey' => 'job_id',
        ],
    ];

    public $hasMany = [];

}
