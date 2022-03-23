<?php

App::uses('AppModel', 'Model');

class Tender extends AppModel {

    public $useTable = 'tenders';
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

    public $hasMany = array(
        'Bid' =>
            array(
                'className' => 'Bid',
                'foreignKey' => 'tender_id',
                'conditions' => array('Bid.active' => '1', 'Bid.removed' => '0'),
            ),
        'Invoice' =>
            array(
                'className' => 'Invoice',
                'foreignKey' => 'tender_id',
                'conditions' => array('Invoice.active' => '1', 'Invoice.removed' => '0'),
            ),
        'Job' =>
            array(
                'className' => 'Job',
                'foreignKey' => 'tender_id',
                'conditions' => array('Job.active' => '1', 'Job.removed' => '0'),
            ),
        'TermCondition' =>
            array(
                'className' => 'TermCondition',
                'foreignKey' => 'tender_id',
                'conditions' => array('TermCondition.active' => '1', 'TermCondition.removed' => '0'),
            ),
        'File' =>
            array(
                'className' => 'TenderFile',
                'foreignKey' => 'tender_id',
                'conditions' => array('File.active' => '1', 'File.removed' => '0'),
            ),
        'Compliance' =>
            array(
                'className' => 'Compliance',
                'foreignKey' => 'tender_id',
                'conditions' => array('Compliance.active' => '1', 'Compliance.removed' => '0'),
            ),
    );

    public function contractorHasBidOnTender($id, $providerId)
    {
        $options =  array(
                    'fields' => array('Bid.id', 
                        'Bid.provider_id', 
                        'Bid.tender_id', 
                        'Bid.status', 
                        'Bid.value',
                        'Bid.comments',
                        ),
                    'conditions' => array(
                        'Bid.tender_id' => $id,
                        'Bid.provider_id' => $providerId,
                    ),
                    'contain' => array(
                        'Compliance' => array(
                            'fields' => array('Compliance.id', 'Compliance.term_condition_id', 'Compliance.compliant'),
                        ),
                    ),
                );
        $bid = $this->Bid->find('first', $options);
        return $bid;
    }

    public function contractorHasOpenBidOnTender($id, $providerId)
    {
        $options =  array(
                    'fields' => array(
                        'Bid.id', 
                        'Bid.provider_id', 
                        'Bid.tender_id', 
                        'Bid.status',
                        'Bid.value',
                        'Bid.comments',
                        ),
                    'conditions' => array(
                        'Bid.tender_id' => $id,
                        'Bid.provider_id' => $providerId,
                        'Bid.status' => array(
                            EJQ_BID_STATUS_IN_PROGRESS,
                        ),
                    ),
                    'contain' => array(
                        'Compliance' => array(
                            'fields' => array('Compliance.id', 'Compliance.term_condition_id', 'Compliance.compliant'),
                        ),
                    ),
                );
                
        $bid = $this->Bid->find('first', $options);
        return $bid;
    }

    public function contractorHasSubmittedBidOnTender($id, $providerId)
    {
        $options =  array(
                    'fields' => array('Bid.id', 'Bid.provider_id', 'Bid.tender_id', 'Bid.status'),
                    'conditions' => array(
                        'Bid.tender_id' => $id,
                        'Bid.provider_id' => $providerId,
                        'Bid.status' => array(
                            EJQ_BID_STATUS_SUBMITTED, 
                        ),
                    ),
                    'contain' => array(
                        'Compliance' => array(
                            'fields' => array('Compliance.id', 'Compliance.term_condition_id', 'Compliance.compliant'),
                        ),
                    ),
                );
                
        $bid = $this->Bid->find('first', $options);
        return $bid;
    }
    
    public function contractorHasVisitedTender($id, $providerId)
    {
        $options =  array(
                    'fields' => array(
                        'Bid.id', 
                        'Bid.provider_id', 
                        'Bid.tender_id', 
                        'Bid.status',
                        'Bid.value',
                        ),
                    'conditions' => array(
                        'Bid.tender_id' => $id,
                        'Bid.provider_id' => $providerId,
                        'Bid.status' => array(
                            EJQ_BID_STATUS_VISITED,
                        ),
                    ),
                    'contain' => array(),
                );
                
        $bid = $this->Bid->find('first', $options);
        return $bid;
    }

    public function getJobInfo($id)
    {
        
        if(empty($id)) {
            return false;
        }

        //$data = $this->getTenderInfo($id);
        
        $jobData = $this->Job->find('first', [
                    'fields' => [
                        'Job.id', 
                        'Job.demand_id', 
                        'Job.tender_id', 
                        'Job.provider_id', 
                        'Job.consumer_id', 
                        'Job.date_begin_home_owner', 
                        'Job.date_end_home_owner', 
                        'Job.date_begin_contractor', 
                        'Job.date_end_contractor', 
                        ],
                    'conditions' => [
                        'Job.tender_id' => $id,
                        ],
                    'contain' => ['Review']
                    ]);
        
        $data = [];
        if (!empty($jobData['Job'])) {
            $data['Job'] = $jobData['Job'];
        }
        if (!empty($jobData['Review'])) {
            $data['Review'] = $jobData['Review']['0'];
        }
        return $data;
    }
    
    
    public function getTenderInfo($id)
    {
        
        if(empty($id)) {
            return false;
        }
        
        $this->id = $id;
        $demandId = $this->field('demand_id');
        
        $data = $this->Demand->getTenderInfo($demandId);
        
        $municipalityId = $this->field('municipality_id');
        $this->Municipality->id = $municipalityId;
        $data['Municipality'] = array('id' => $municipalityId, 'name' => $this->Municipality->field('name'));
        
        return $data;

    }
    
    public function estimatorCanSeeTenderDetails($id, $providerId)
    {
        $this->id = $id;
        $demandId = $this->field('demand_id');
        $this->Demand->id = $demandId;
                
        $demandEstimatorId = $this->Demand->field('provider_id');
        
        $demandStatus = $this->Demand->field('status');
        if(($demandEstimatorId != $providerId) || 
                (($demandStatus != EJQ_DEMAND_STATUS_TENDER_IN_PROGRESS) && 
                ($demandStatus != EJQ_DEMAND_STATUS_TENDER_READY_FOR_SITE_ADMIN_APPROVAL) &&
                ($demandStatus != EJQ_DEMAND_STATUS_TENDER_READY_FOR_HOME_OWNER_APPROVAL) &&
                ($demandStatus != EJQ_DEMAND_STATUS_TENDER_TO_BE_MODIFIED) &&
                ($demandStatus != EJQ_DEMAND_STATUS_TENDER_READY_TO_BIDS) &&
                ($demandStatus != EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS) &&
                ($demandStatus != EJQ_DEMAND_STATUS_TENDER_CLOSED_TO_BIDS) &&
                ($demandStatus != EJQ_DEMAND_STATUS_TENDER_OPEN_FOR_BID_SELECTION) 
                )) {
            return false;
        }

        return $id;
        
    }
    
    public function homeOwnerCanSeeTenderDetails($id, $consumerId)
    {
        $this->id = $id;
        $demandId = $this->field('demand_id');
        $this->Demand->id = $demandId;
                
        $demandHomeOwnerId = $this->Demand->field('consumer_id');
        
        $demandStatus = $this->Demand->field('status');
        if(($demandHomeOwnerId != $consumerId) || 
                (($demandStatus != EJQ_DEMAND_STATUS_TENDER_CLOSED_TO_BIDS) && 
                ($demandStatus != EJQ_DEMAND_STATUS_TENDER_READY_FOR_HOME_OWNER_APPROVAL) &&
                ($demandStatus != EJQ_DEMAND_STATUS_TENDER_TO_BE_MODIFIED) &&
                ($demandStatus != EJQ_DEMAND_STATUS_TENDER_READY_TO_BIDS) &&
                ($demandStatus != EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS) &&
                ($demandStatus != EJQ_DEMAND_STATUS_TENDER_OPEN_FOR_BID_SELECTION) &&
                ($demandStatus != EJQ_DEMAND_STATUS_BID_SELECTED) &&
                ($demandStatus != EJQ_DEMAND_STATUS_BID_DISCLOSED) &&
                ($demandStatus != EJQ_DEMAND_STATUS_CONTRACT_SIGNED) &&
                ($demandStatus != EJQ_DEMAND_STATUS_JOB_IN_PROGRESS) &&
                ($demandStatus != EJQ_DEMAND_STATUS_JOB_COMPLETED)
                )) {
            return false;
        }

        return $id;
        
    }
    
    public function possibleActions($id, $role, $profileId=null)
    {
        $this->id = $id;
        $demandId = $this->field('demand_id');
        $this->Demand->id = $demandId;
        $actions = $this->Demand->possibleActions($demandId, $role, $profileId);
        
        return $actions;
        
        
    }

    public function providerCanBidOnTender($id, $providerId, $contractorMetaProviderId)
    {
        $this->id = $id;
        $demandId = $this->field('demand_id');
        $this->Demand->id = $demandId;
        $demandStatus = $this->Demand->field('status');
        $demandServices = $this->Demand->listServiceTypes($demandId);
        $this->Demand->Provider->id = $providerId;
        $metaProviderId = $this->Demand->Provider->field('meta_provider_id');
        $providerisQualified = $this->Demand->Provider->field('qualified');
        
        if ($providerisQualified) {
            $providerServiceTypes = $this->Demand->Provider->listServiceTypes($providerId);
            $serviceTypesIds = array_keys($demandServices);
            $providerServiceIds = array_keys($providerServiceTypes);
            $canBid = false;
            if(!empty($serviceTypesIds)) {
                $servicesCounter = count($serviceTypesIds);
                foreach ($serviceTypesIds as $thisServiceId) {
                    if (in_array($thisServiceId, $providerServiceIds)) {
                        $servicesCounter--;
                    }
                }
                if ($servicesCounter == 0) {
                    $canBid = true;
                }
            }
            
        } else {
            return false;
        }
        

        if((!$canBid) || ($demandStatus != EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS) || ($metaProviderId != $contractorMetaProviderId)) {
            return false;
        }

        return $id;
    }
    
    public function providerCanSeeChosenBid($id, $providerId)
    {
        $this->id = $id;
        $demandId = $this->field('demand_id');
        $this->Demand->id = $demandId;
        
        $chosenBidId = $this->field('chosen_bid_id');
        
        if (!$chosenBidId) {
            return false;
        }
        $this->Bid->id = $chosenBidId;
        
        $chosenContractorId = $this->Bid->field('provider_id');
        
        $demandStatus = $this->Demand->field('status');

        if(($chosenContractorId != $providerId) || 
                ($demandStatus != EJQ_DEMAND_STATUS_BID_DISCLOSED && 
                $demandStatus != EJQ_DEMAND_STATUS_CONTRACT_SIGNED && 
                $demandStatus != EJQ_DEMAND_STATUS_JOB_IN_PROGRESS && 
                $demandStatus != EJQ_DEMAND_STATUS_JOB_COMPLETED
                )) {
            return false;
        }

        return $id;
    }
    
    public function registerVisit($id, $providerId) 
    {
        
        $this->id = $id;
        $bidData = array('Bid' => array(
                    'provider_id' => $providerId,
                    'marketplace_id' => $this->field('marketplace_id'),
                    'tender_id' => $id,
                    'demand_id' => $this->field('demand_id'),
                    'status' => EJQ_BID_STATUS_VISITED,
                    )
            );
        $data = array(
            'Tender' => array(
                'id' => $id,
            ),
            'Bid' => $bidData,
        );
        $result = $this->saveAssociated($data);
        
        return ($result);
        
    }

}
