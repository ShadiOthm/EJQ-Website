<?php

App::uses('AppModel', 'Model');

class Curator extends AppModel {

	public $useTable = 'curators';
	public $displayField = 'name';
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
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please inform a name',
				),
			),
		);

    public $belongsTo = array(
            'User' => array(
                    'className' => 'User',
                    'foreignKey' => 'user_id',
            ),
    );
    
    public $hasMany = array(
        'Marketplace' =>
            array(
                'className' => 'Marketplace',
                'foreignKey' => 'curator_id',
                'conditions' => array('active' => '1', 'removed' => '0'),
            ),
        'MetaMarketplace' =>
            array(
                'className' => 'MetaMarketplace',
                'foreignKey' => 'curator_id',
                'conditions' => array('active' => '1', 'removed' => '0'),
            ),
    );

    public function marketplaces($id=null) 
    {
        $options = array(
                    'fields' => array('id', 'name'),
                    'contain' => array(),
                    'conditions' => array(
                        'Marketplace.active' => '1',
                        )
                    );
        
        if (preg_match('/^\d+$/', $id)) {
            $options['conditions']['curator_id'] = $id;
        }
        
        // find all
        return $this->Marketplace->find('all',$options);
    }
    
    public function metaMarketplaces($id=null) 
    {
        $options = array(
                    'fields' => array('id', 'name'),
                    'contain' => array(
                        'Marketplace' => array(
                            'fields' => array('id'),
                        'conditions' => array('Marketplace.active' => '1'),
                    )),
                    'conditions' => array(
                        'MetaMarketplace.active' => '1',
                        )
                    );
        
        if (preg_match('/^\d+$/', $id)) {
            $options['conditions']['curator_id'] = $id;
        }
        
        // find all
        return $this->MetaMarketplace->find('all',$options);
    }
    

}
