<?php

App::uses('AppModel', 'Model');

class Municipality extends AppModel {

    public $useTable = 'municipalities';
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
            'Contractor' => array(
                    'className' => 'Contractor',
                    'foreignKey' => 'municipality_id',
                    ),
            'Request' => array(
                    'className' => 'Request',
                    'foreignKey' => 'municipality_id',
                    ),
            'Tender' => array(
                    'className' => 'Tender',
                    'foreignKey' => 'municipality_id',
                    ),
            );


}
