<?php

App::uses('AppModel', 'Model');

class Consumer extends AppModel {

	public $useTable = 'consumers';
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
            'Marketplace' => array(
                    'className' => 'Marketplace',
                    'foreignKey' => 'marketplace_id',
            ),
            'MetaConsumer' => array(
                    'className' => 'MetaConsumer',
                    'foreignKey' => 'meta_consumer_id',
            ),
            'User' => array(
                    'className' => 'User',
                    'foreignKey' => 'user_id',
            ),
    );
    
    public $hasMany = array(
        'TermCondition' =>
            array(
                'className' => 'TermCondition',
                'foreignKey' => 'consumer_id',
                'conditions' => array('TermCondition.active' => '1', 'TermCondition.removed' => '0'),
            ),
        'Demand' =>
            array(
                'className' => 'Demand',
                'foreignKey' => 'consumer_id',
                'conditions' => array('active' => '1', 'removed' => '0'),
            ),
        'Schedule' =>
            array(
                'className' => 'DemandSchedule',
                'foreignKey' => 'consumer_id',
                'conditions' => array('active' => '1', 'removed' => '0'),
            ),
        
    );    
    
    public function getConsumerByMarketplaceAndUserId($marketplaceId, $userId) {


        if(empty($marketplaceId)) return false;
        if(empty($userId)) return false;
        
        $consumer = $this->find('first', 
                array(
                    'fields' => array('id'),
                    'conditions' => array('marketplace_id' => $marketplaceId, 'user_id' => $userId),
                    ));
        
        
        if (isset($consumer['Consumer'])) {
            return $consumer;
        } else {
            return false;
        }
    }  
    
    
    public function getSchedule($id, $demandId)
    {
        if(empty($id)) return false;
        
        $data = $this->Schedule->find('first', 
                array(
                    'fields' => array('schedule'),
                    'conditions' => array('Schedule.consumer_id' => $id, 'Schedule.demand_id' => $demandId),
                    'contain' => array(),
                    ));
        $schedule = null;
        if (isset($data['Schedule']['schedule'])) {
            $schedule = $data['Schedule']['schedule'];
        }
        
        
        return $schedule;
        
    }

    public function getScheduleId($id, $demandId)
    {
        if(empty($id)) return false;
        
        $data = $this->Schedule->find('first', 
                array(
                    'fields' => array('id'),
                    'conditions' => array('Schedule.consumer_id' => $id, 'Schedule.demand_id' => $demandId),
                    'contain' => array(),
                    ));
        $scheduleId = null;
        if (isset($data['Schedule']['id'])) {
            $scheduleId = $data['Schedule']['id'];
        }
        
        
        return $scheduleId;
        
    }


    
    

}
