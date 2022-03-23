<?php

App::uses('AppModel', 'Model');

class Bid extends AppModel {

    public $useTable = 'bids';
    public $actsAs = array('Containable');

    public function parentNode() {
            return null;
    }

    public $validate = array(
        'value' => array(
            'positive' => array(
                'rule' => array('comparison', '>', 0),
            ),
            'numeric' => array(
                'rule' => array('numeric'),
            ),
            'money' => array(
                'rule' => array('money', 'left'),
            ),
        )
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
        'Tender' => array(
                'className' => 'Tender',
                'foreignKey' => 'tender_id',
        ),
    );

    public $hasMany = array(
        'Compliance' =>
            array(
                'className' => 'Compliance',
                'foreignKey' => 'bid_id',
                'conditions' => array('Compliance.active' => '1', 'Compliance.removed' => '0'),
            ),
    );

    public function getBidInfo($id)
    {
        if(empty($id)) {
            return false;
        }

        $data = $this->find('first',
                array(
                    'fields' => array(
                        'Bid.id', 
                        'Bid.value', 
                        'Bid.shortlisted',
                        'Bid.marketplace_id',
                        'Bid.comments',
                        'Bid.contractor_alias',
                        ),
                    'conditions' => array(
                        'Bid.id' => $id,
                        ),
                    'contain' => array(
                        'Tender' => array(
                            'id',
                            ),
                        'Provider' => array(
                            'name',
                            'user_id',
                            'good_standing',
                            'qualified',
                            ),
                        'Compliance',
                        ),
                    ));
        
        $contractor = $this->Provider->Contractor->getContractorByMarketplaceAndProviderId($data['Bid']['marketplace_id'], $data['Provider']['id']);
        $this->Provider->Contractor->id = $contractor['Contractor']['id'];
        $contractorName = $this->Provider->Contractor->field('name');
        $data['Contractor']['name'] = $contractorName;
        $contractorAddress = $this->Provider->Contractor->field('contact_address');
        $data['Contractor']['contact_address'] = $contractorAddress;
        $contractorPhone = $this->Provider->Contractor->field('phone');
        $data['Contractor']['phone'] = $contractorPhone;
        $contractorContactName = $this->Provider->Contractor->field('contact_name');
        if (is_null($contractorContactName)) {
            $this->Provider->User->id = $data['Provider']['user_id'];
            $data['Contractor']['contact_name'] = $this->Provider->User->field('name');
        } else {
            $data['Contractor']['contact_name'] = $contractorName;
        }
        $contractorEmail = $this->Provider->Contractor->field('contact_email');
        if (is_null($contractorEmail)) {
            $this->Provider->User->id = $data['Provider']['user_id'];
            $data['Contractor']['contact_email'] = $this->Provider->User->field('email');
        } else {
            $data['Contractor']['contact_email'] = $contractorEmail;
        }
        $contractorPosition = $this->Provider->Contractor->field('contact_position');
        $data['Contractor']['contact_position'] = $contractorPosition;
        
        $newComplianceArray = array();
        foreach ($data['Compliance'] as $value) {
            $newComplianceArray[$value['term_condition_id']] = $value;
        }
        
        $data['Compliance'] = $newComplianceArray;
        
        return $data;

    }


}
