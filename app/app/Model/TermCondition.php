<?php

App::uses('AppModel', 'Model');

class TermCondition extends AppModel {

    public $useTable = 'terms_conditions';
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
        'Provider' => array(
                'className' => 'Provider',
                'foreignKey' => 'provider_id',
        ),
        'Consumer' => array(
                'className' => 'Consumer',
                'foreignKey' => 'consumer_id',
        ),
        'Tender' => array(
                'className' => 'Tender',
                'foreignKey' => 'tender_id',
        ),
    );

    public $hasMany = array(
        'Compliance' =>
            array(
                'className' => 'Compliance',
                'foreignKey' => 'term_condition_id',
                'conditions' => array('Compliance.active' => '1', 'Compliance.removed' => '0'),
            ),
        );

    public function contractorHasAmendment($id, $providerId)
    {
        $options =  array(
                    'fields' => array('Compliance.id', 'Compliance.term_condition_id', 'Compliance.compliant', 'Compliance.amendment'),
                    'conditions' => array(
                        'Compliance.term_condition_id' => $id,
                        'Compliance.provider_id' => $providerId,
                    ),
                    'contain' => array(),
                );
        $bid = $this->Compliance->find('first', $options);
        return $bid;
    }


    
    public function fetchContractorAmendment($id, $providerId) 
    {
        $compliance = $this->Compliance->find('first', array(
                        'fields' => array(
                            'Compliance.id',
                            'Compliance.amendment',
                            ),
                        'contain' => array(),
                        'conditions' => array(
                            'Compliance.term_condition_id' => $id,
                            'Compliance.provider_id' => $providerId,
                        )
                    ));
        if (empty($compliance)) {
            return null;
        } else {
            return $compliance['Compliance']['amendment'];
        }
            
    }

    public function hasContractorCompliance($id, $providerId) 
    {
        $compliance = $this->Compliance->find('first', array(
                        'fields' => array(
                            'Compliance.id',
                            'Compliance.compliant',
                            ),
                        'contain' => array(),
                        'conditions' => array(
                            'Compliance.term_condition_id' => $id,
                            'Compliance.provider_id' => $providerId,
                        )
                    ));
        
        if ((empty($compliance)) || (is_null($compliance['Compliance']['compliant']))) {
            return null;
        } elseif ($compliance['Compliance']['compliant'] === FALSE) {
            return false;
        } else {
            return true;
        }
            
    }

}
