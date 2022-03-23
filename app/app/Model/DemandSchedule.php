<?php

App::uses('AppModel', 'Model');

class DemandSchedule extends AppModel {

	public $useTable = 'demands_schedules';
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
		'schedule' => array(
                    'notBlank' => array(
                        'rule' => array('notBlank'),
                        'message' => 'Favor informar a data do agendamento',
                    ),
                    'datetime' => [
                        'rule' => array('datetime', 'ymd'),
                        'message' => 'You must provide a schedule in YYYY-MM-DD format.',
                        'allowEmpty' => true
                    ],
                ),
//                'future' => array(
//                    'rule' => array('checkFutureDate'),
//                    'message' => 'The schedule must be not be in the past'
//                )
            );

    public $belongsTo = array(
            'Marketplace' => array(
                    'className' => 'Marketplace',
                    'foreignKey' => 'marketplace_id',
            ),
            'Demand' => array(
                    'className' => 'Demand',
                    'foreignKey' => 'demand_id',
            ),
            'Consumer' => array(
                    'className' => 'Consumer',
                    'foreignKey' => 'consumer_id',
            ),
            'ServiceType' => array(
                    'className' => 'ServiceType',
                    'foreignKey' => 'service_type_id',
            ),
    );

    private function validateFutureDate($fieldName, $params)
    {

        if ($result = $this->validateDate($fieldName, $params))
        {
            return $result;
        }
        $date = strtotime($this->data[$this->name][$fieldName]);        
        return $this->_evaluate($date > time(), "is not set in a future date", $fieldName, $params);
        
    }
    
    
    
}
