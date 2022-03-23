<?php

App::uses('AppModel', 'Model');

class Demand extends AppModel {

    public $useTable = 'demands';
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
        );

    public $belongsTo = array(
        'Marketplace' => array(
            'className' => 'Marketplace',
            'foreignKey' => 'marketplace_id',
        ),
        'Consumer' => array(
            'className' => 'Consumer',
            'foreignKey' => 'consumer_id',
        ),
        'Provider' => array(
            'className' => 'Provider',
            'foreignKey' => 'provider_id',
        ),
    );

    public $hasAndBelongsToMany = [
        'ServiceType' =>
            [
                'className' => 'ServiceType',
                'joinTable' => 'demands_service_types',
                'foreignKey' => 'demand_id',
                'associationForeignKey' => 'service_type_id',
                'unique' => 'keepExisting',
            ],
    ];

    public $hasMany = [
        'Bid' =>
            [
                'className' => 'Bid',
                'foreignKey' => 'demand_id',
                'conditions' => ['Bid.active' => '1', 'Bid.removed' => '0'],
            ],
        'Evaluation' =>
            [
                'className' => 'DemandEvaluation',
                'foreignKey' => 'demand_id',
            ],
        'File' =>
            [
                'className' => 'TenderFile',
                'foreignKey' => 'demand_id',
                'conditions' => ['File.active' => '1', 'File.removed' => '0'],
            ],
        'Invoice' =>
            [
                'className' => 'Invoice',
                'foreignKey' => 'demand_id',
            ],
        'Schedule' =>
            [
                'className' => 'DemandSchedule',
                'foreignKey' => 'demand_id',
                'conditions' => ['Schedule.active' => '1', 'Schedule.removed' => '0'],
            ],
        'Suitability' =>
            [
                'className' => 'Suitability',
                'foreignKey' => 'demand_id',
            ],
        'TermCondition' =>
            [
                'className' => 'TermCondition',
                'foreignKey' => 'demand_id',
                'conditions' => ['TermCondition.active' => '1', 'TermCondition.removed' => '0'],
            ],
    ];

    public $hasOne = array(
        'Request' =>
            array(
                'className' => 'Request',
                'foreignKey' => 'demand_id',
                'conditions' => array('Request.active' => '1', 'Request.removed' => '0'),
            ),
        'Tender' =>
            array(
                'className' => 'Tender',
                'foreignKey' => 'demand_id',
                'conditions' => array('Tender.active' => '1', 'Tender.removed' => '0'),
            ),
    );
    
    public $virtualFields = array(
        'year' => 'YEAR("Demand.created")',
        'franchise_label' => 'UPPER("EJQ")',
        );

    public function billing($id, $type)
    {
        $data = $this->Invoice->find('all',
                [
//                    'fields' => array(
//                        'Invoice.id',
//                        ),
                    'conditions' => array(
                        'Invoice.demand_id' => $id,
                        'Invoice.type' => $type,
                        ),
                     'contain' => array(),
                    ]);
        

        return $data;
    }
    
    public function canBeCanceled($demand)
    {
        switch ($demand['status']) {
            case EJQ_DEMAND_STATUS_REQUEST_OPEN:
            case EJQ_DEMAND_STATUS_WAITING_FOR_ESTIMATOR_SCHEDULE_APPROVAL:
            case EJQ_DEMAND_STATUS_WAITING_FOR_HOME_OWNER_SCHEDULE_APPROVAL:
            case EJQ_DEMAND_STATUS_ESTIMATION_ASSIGNED:
            case EJQ_DEMAND_STATUS_ESTIMATION_READY_FOR_DISPATCH:
            case EJQ_DEMAND_STATUS_ESTIMATION_DISPATCHED:
            case EJQ_DEMAND_STATUS_TENDER_IN_PROGRESS:
            case EJQ_DEMAND_STATUS_TENDER_TO_BE_MODIFIED:
                return true;
                break;

            default:
                return false;
                break;
        }

    }

    public function chosenBid($id)
    {
        if(empty($id)) {
            return false;
        }
        $bidData = null;
        $demandData = $this->find('first',
                array(
                    'fields' => array(
                        'Demand.id',
                        ),
                    'conditions' => array(
                        'Demand.id' => $id,
                        'Demand.status' => [EJQ_DEMAND_STATUS_BID_SELECTED, EJQ_DEMAND_STATUS_BID_DISCLOSED, EJQ_DEMAND_STATUS_CONTRACT_SIGNED, EJQ_DEMAND_STATUS_JOB_IN_PROGRESS, EJQ_DEMAND_STATUS_JOB_COMPLETED],
                        ),
                    'contain' => array(
                        'Provider.id',
                        'Tender.id',
                        'Tender.chosen_bid_id',
                        ),
                    ));
        
        
        if (!empty($demandData) && !empty($demandData['Tender']['chosen_bid_id'])) {
            $bidData = $this->Bid->getBidInfo($demandData['Tender']['chosen_bid_id']);            
        }
        return $bidData;


    }
    
    public function closeToBidsAllTendersScheduledToClosingToday($marketplaceId)
    {
        $demands = $this->listMarketplaceScheduleToCloseBidding($marketplaceId);
        foreach ($demands as $key => $demandId) {
            $Demand = new Demand;
            $Demand->id = $demandId;
            $Demand->saveField('status', EJQ_DEMAND_STATUS_TENDER_CLOSED_TO_BIDS);
            unset($Demand);
        }
    }

    public function countSubmittedBids($id)
    {
        $result = $this->Bid->find('list',[
            'conditions' => [
                'status' => [EJQ_BID_STATUS_SUBMITTED, EJQ_BID_STATUS_CHOSEN],
                'demand_id' => $id,
                ],
            ]);
        $count = count($result);
        if(!$count) {
            return "0";
        } else {
            foreach ($result as $bidId) {
                $this->Bid->id = $bidId;
                $providerId = $this->Bid->field('provider_id');
                $this->Bid->Provider->id = $providerId;
                $qualified = $this->Bid->Provider->field('qualified');
                if (!$qualified) {
                    $count--;
                }
            }
            
            return $count;
        }
    }

    public function countInProgressBids($id)
    {
        $result = $this->Bid->find('list',[
            'conditions' => [
                'status' => EJQ_BID_STATUS_IN_PROGRESS,
                'demand_id' => $id,
                ],
            ]);
        $count = count($result);
        if(!$count) {
            return "0";
        } else {
            foreach ($result as $bidId) {
                $this->Bid->id = $bidId;
                $providerId = $this->Bid->field('provider_id');
                $this->Bid->Provider->id = $providerId;
                $qualified = $this->Bid->Provider->field('qualified');
                if (!$qualified) {
                    $count--;
                }
            }
            
            return $count;
        }
    }

    public function countVisits($id)
    {
        $result = $this->Bid->find('list',[
            'conditions' => [
                'status' => EJQ_BID_STATUS_VISITED,
                'demand_id' => $id,
                ],
            ]);
        $count = count($result);
        if(!$count) {
            return "0";
        } else {
            foreach ($result as $bidId) {
                $this->Bid->id = $bidId;
                $providerId = $this->Bid->field('provider_id');
                $this->Bid->Provider->id = $providerId;
                $qualified = $this->Bid->Provider->field('qualified');
                if (!$qualified) {
                    $count--;
                }
            }
            
            return $count;
        }
    }

    public function getHomeOwnerTenders($consumerId)
    {
        if(empty($consumerId)) return false;

        $demands = $this->find('all',
                array(
                    'fields' => array(
                        'id',
                        'created',
                        'modified',
                        'status',
                        'YEAR(Demand.created) as Demand__year',
                        'franchise_label',
                        ),
                    'conditions' => array(
                        'status' => array(
                            EJQ_DEMAND_STATUS_TENDER_IN_PROGRESS,
                            EJQ_DEMAND_STATUS_TENDER_READY_FOR_SITE_ADMIN_APPROVAL,
                            EJQ_DEMAND_STATUS_TENDER_READY_FOR_HOME_OWNER_APPROVAL,
                            EJQ_DEMAND_STATUS_TENDER_TO_BE_MODIFIED,
                            EJQ_DEMAND_STATUS_TENDER_READY_TO_BIDS,
                            EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS,
                            EJQ_DEMAND_STATUS_TENDER_CLOSED_TO_BIDS,
                            EJQ_DEMAND_STATUS_TENDER_OPEN_FOR_BID_SELECTION,
                            EJQ_DEMAND_STATUS_CLOSED,
                            EJQ_DEMAND_STATUS_BID_SELECTED,
                            EJQ_DEMAND_STATUS_BID_DISCLOSED,
                            EJQ_DEMAND_STATUS_CONTRACT_SIGNED,
                            EJQ_DEMAND_STATUS_JOB_IN_PROGRESS,
                            EJQ_DEMAND_STATUS_JOB_COMPLETED,
                            ),
                        'Demand.consumer_id' => $consumerId,
                        ),
                    'contain' => array(
                        'Request.title',
                        'Request.description',
                        'Tender.id',
                        'Tender.title',
                        'ServiceType.id',
                        'ServiceType.name',
                        'Provider.name',
                        'Bid' => array('fields' => array(
                            'Bid.modified',
                            'Bid.provider_id',
                            'Bid.value',
                            ), 
                            'order' => array('Bid.modified'
                                
                                ),
                            'conditions' => array(
                                'status' => array(
                                    EJQ_BID_STATUS_SUBMITTED,
                                    ),
                                //'Demand.marketplace_id' => $marketplaceId,
                                ),
                            ),
                        ),
                    'order' => ['Demand.modified DESC'],
                    ));
        foreach ($demands as $key => $demandData) {
            $demand = $demandData['Demand'];
            $demands[$key]['Demand']['can_cancel'] = $this->canBeCanceled($demand);
        }

        return $demands;

    }

    public function getMarketplaceClosedToBids($marketplaceId)
    {
        if(empty($marketplaceId)) return false;

        $demands = $this->find('all',
                array(
                    'fields' => array(
                        'id',
                        'created',
                        'modified',
                        'status',
                        'YEAR(Demand.created) as Demand__year',
                        'franchise_label',
                        
                        ),
                    'conditions' => array(
                        'status' => array(
                            EJQ_DEMAND_STATUS_TENDER_CLOSED_TO_BIDS,
                            EJQ_DEMAND_STATUS_TENDER_OPEN_FOR_BID_SELECTION,
                            EJQ_DEMAND_STATUS_BID_SELECTED,
                            EJQ_DEMAND_STATUS_BID_DISCLOSED,
                            EJQ_DEMAND_STATUS_CONTRACT_SIGNED,
                            ),
                        'Demand.marketplace_id' => $marketplaceId,
                        ),
                    'contain' => array(
                        'Tender.id',
                        'Tender.title',
                        'Request.title',
                        'Request.description',
                        'ServiceType.id',
                        'ServiceType.name',
                        'Consumer.name',
                        'Provider.name',
                        'Bid' => array('fields' => array(
                            'Bid.modified',
                            'Bid.provider_id',
                            'Bid.status',
                            'Bid.value',
                            ), 
                            'order' => array('Bid.modified'
                                
                            ),
                            'conditions' => array(
                                'status' => array(
                                    EJQ_BID_STATUS_SUBMITTED,
                                    EJQ_BID_STATUS_CHOSEN,
                                    ),
                                //'Demand.marketplace_id' => $marketplaceId,
                                ),
                            ),
                        'ServiceType.Schedule' => array(
                            'demand_id',
                            'schedule',

                            ),
                        ),
                    'order' => ['Demand.modified DESC'],
                    ));
        foreach ($demands as $key => $demandData) {
            $demand = $demandData['Demand'];
            $demands[$key]['Demand']['can_cancel'] = $this->canBeCanceled($demand);
        }
        return $demands;

    }

    public function getMarketplaceJobs($marketplaceId)
    {
        if(empty($marketplaceId)) return false;

        $jobs = $this->Tender->Job->find('all',
                array(
                    'fields' => array(
                        'id',
                        ),
                    'conditions' => array(
                        'Job.marketplace_id' => $marketplaceId,
                        ),
                    'contain' => array(
                        'Tender.id',
                        'Tender.title',
                        'Consumer.name',
                        'Provider.name',
                        'Demand' => array('fields' => array(
                            'Demand.id',
                            'Demand.status',
                            ), 
                            'conditions' => array(
                                'Demand.status' => array(
                                    EJQ_DEMAND_STATUS_BID_DISCLOSED,
                                    EJQ_DEMAND_STATUS_CONTRACT_SIGNED,
                                    EJQ_DEMAND_STATUS_JOB_COMPLETED,
                                    EJQ_DEMAND_STATUS_JOB_IN_PROGRESS,
                                    ),
                                ),
                            ),
                        ),
                    'order' => ['Job.modified DESC'],
                    ));
        return $jobs;

    }

    public function getMarketplaceOpenRequests($marketplaceId)
    {
        if(empty($marketplaceId)) return false;

        $this->virtualFields['year'] = '';
        $demands = $this->find('all',
                array(
                    'order' => ['Demand.modified DESC'],
                    'fields' => array(
                        'id',
                        'created',
                        'modified',
                        'status',
                        ),
                    'conditions' => array(
                        'status' => array(
                            EJQ_DEMAND_STATUS_REQUEST_OPEN,
                            EJQ_DEMAND_STATUS_ESTIMATION_ASSIGNED,
                            EJQ_DEMAND_STATUS_WAITING_FOR_ESTIMATOR_SCHEDULE_APPROVAL,
                            EJQ_DEMAND_STATUS_WAITING_FOR_HOME_OWNER_SCHEDULE_APPROVAL,
                            EJQ_DEMAND_STATUS_ESTIMATION_READY_FOR_DISPATCH,
                            EJQ_DEMAND_STATUS_ESTIMATION_DISPATCHED,
                            ),
                        'Demand.marketplace_id' => $marketplaceId,
                        ),
                    'contain' => array(
                        'Request.title',
                        'Request.description',
                        'ServiceType.id',
                        'ServiceType.name',
                        'Tender.title',
                        'Consumer.name',
                        'Provider.name',
                        'ServiceType.Schedule' => array(
                            'demand_id',
                            'schedule',

                        ),
                        ),
                    ));
        foreach ($demands as $key => $demandData) {
            $demand = $demandData['Demand'];
            $demands[$key]['Demand']['can_cancel'] = $this->canBeCanceled($demand);
        }
        return $demands;

    }

    public function getMarketplaceOpenTenders($marketplaceId)
    {
        if(empty($marketplaceId)) return false;

        $demands = $this->find('all',
                array(
                    'order' => ['Demand.modified DESC'],
                    'fields' => array(
                        'id',
                        'created',
                        'modified',
                        'status',
                        'YEAR(Demand.created) as Demand__year',
                        'franchise_label',
                        ),
                    'conditions' => array(
                        'status' => array(
                            EJQ_DEMAND_STATUS_TENDER_IN_PROGRESS,
                            EJQ_DEMAND_STATUS_TENDER_READY_FOR_SITE_ADMIN_APPROVAL,
                            EJQ_DEMAND_STATUS_TENDER_READY_FOR_HOME_OWNER_APPROVAL,
                            EJQ_DEMAND_STATUS_TENDER_TO_BE_MODIFIED,
                            EJQ_DEMAND_STATUS_TENDER_READY_TO_BIDS,
                            ),
                        'Demand.marketplace_id' => $marketplaceId,
                        ),
                    'contain' => array(
                        'Tender.id',
                        'Tender.title',
                        'Request.title',
                        'Request.description',
                        'ServiceType.id',
                        'ServiceType.name',
                        'Consumer.name',
                        'Provider.name',
                        'ServiceType.Schedule' => array(
                            'demand_id',
                            'schedule',

                        ),
                        ),
                    ));
        foreach ($demands as $key => $demandData) {
            $demand = $demandData['Demand'];
            $demands[$key]['Demand']['can_cancel'] = $this->canBeCanceled($demand);
        }
        return $demands;

    }

    public function getMarketplaceOpenToBids($marketplaceId)
    {
        if(empty($marketplaceId)) {
            return false;
        }
        
        $demands = $this->find('all',
                array(
                    'order' => array(
                        'Tender.close_to_bids_date',
                        'Demand.modified',
                    ),
                    'fields' => array(
                        'id',
                        'created',
                        'modified',
                        'status',
                        'YEAR(Demand.created) as Demand__year',
                        'franchise_label',
                        ),
                    'conditions' => array(
                        'status' => array(
                            EJQ_DEMAND_STATUS_TENDER_BIDDING_SCHEDULED,
                            EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS,
                            ),
                        'Demand.marketplace_id' => $marketplaceId,
                        ),
                    'contain' => array(
                        'Tender' => array(
                            'id',
                            'title',
                            'description',
                            'chosen_bid_id',
                            'open_to_bids_date',
                            'close_to_bids_date',
                            ),
                        'Request.title',
                        'Request.description',
                        'ServiceType.id',
                        'ServiceType.name',
                        'Consumer.name',
                        'Provider.name',
                        'Bid' => array('fields' => array(
                            'Bid.modified',
                            'Bid.provider_id',
                            'Bid.status',
                            'Bid.value',
                            ), 
                            'order' => array(
                                'Bid.modified'
                            ),
                            'conditions' => array(
                                'status' => array(
                                    EJQ_BID_STATUS_SUBMITTED,
                                    ),
                                //'Demand.marketplace_id' => $marketplaceId,
                                ),
                            ),
                        'ServiceType.Schedule' => array(
                            'demand_id',
                            'schedule',

                            ),
                        ),
                    ));
        foreach ($demands as $key => $demandData) {
            $demand = $demandData['Demand'];
            $demands[$key]['Demand']['can_cancel'] = $this->canBeCanceled($demand);
            $demands[$key]['Demand']['submitted_bids'] = $this->countSubmittedBids($demand['id']);
            $demands[$key]['Demand']['in_progress_bids'] = $this->countInProgressBids($demand['id']);
            $demands[$key]['Demand']['visits'] = $this->countVisits($demand['id']);
        }
        return $demands;

    }

    public function getEstimatorOpenTenders($providerId)
    {
        if(empty($providerId)) {
            return false;
        }

        $demands = $this->find('all',
                array(
                    'fields' => array(
                        'id', 
                        'created', 
                        'YEAR(Demand.created) as Demand__year',
                        'franchise_label',
                        'status',
                        ),
                    'conditions' => array(
                        'Demand.status' => array(
                            EJQ_DEMAND_STATUS_TENDER_IN_PROGRESS,
                            EJQ_DEMAND_STATUS_TENDER_READY_FOR_SITE_ADMIN_APPROVAL,
                            EJQ_DEMAND_STATUS_TENDER_READY_FOR_HOME_OWNER_APPROVAL,
                            EJQ_DEMAND_STATUS_TENDER_READY_TO_BIDS,
                            EJQ_DEMAND_STATUS_TENDER_TO_BE_MODIFIED,
                            EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS,
                            EJQ_DEMAND_STATUS_TENDER_CLOSED_TO_BIDS,
                            ),
                        'Demand.provider_id' => $providerId,
                        //'Schedule.demand_id = Demand.id'
                ),
                    'contain' => array(
                        'ServiceType.id',
                        'ServiceType.name',
                        'Schedule.schedule',
                        'Schedule.service_type_id',
                        'Consumer.name',
                        'Request.title',
                        'Tender.id',
                        'Tender.title',
                        'Tender.description',
                        ),
                    ));
        
        $indexedDemands = $this->indexesServicesAndSchedules($demands);
        return $indexedDemands;
    }

    public function getEstimatorAssignedEstimations($providerId)
    {
        if(empty($providerId)) {
            return false;
        }

        $demands = $this->find('all',
                array(
                    'fields' => array('id', 'created', 'status'),
                    'conditions' => array(
                        'Demand.status' => array(
                            EJQ_DEMAND_STATUS_ESTIMATION_ASSIGNED,
                            EJQ_DEMAND_STATUS_ESTIMATION_READY_FOR_DISPATCH,
                            EJQ_DEMAND_STATUS_ESTIMATION_DISPATCHED,
                            EJQ_DEMAND_STATUS_WAITING_FOR_ESTIMATOR_SCHEDULE_APPROVAL,
                            EJQ_DEMAND_STATUS_WAITING_FOR_HOME_OWNER_SCHEDULE_APPROVAL,
                            ),
                        'Demand.provider_id' => $providerId,
                ),
                    'contain' => array(
                        'ServiceType.id',
                        'ServiceType.name',
                        'Schedule.schedule',
                        'Schedule.service_type_id',
                        'Schedule.schedule_period_begin',
                        'Schedule.schedule_period_end',
                        'Consumer.name',
                        'Request.title'
                        ),
                    ));

        $indexedDemands = $this->indexesServicesAndSchedules($demands);

        return $indexedDemands;

    }

    public function getEvaluationsPendingByConsumer($consumerId)
    {
        if(empty($consumerId)) {
            return false;
        }

        $demandsList = $this->find('list',
                array(
                    'fields' => array('id'),
                    'conditions' => array(
                        'consumer_id' => $consumerId,
                        'status' => DEMAND_STATUS_SUPPLIED,
                    ),
                    'contain' => array(),
                ));


        $demandsIds = array_keys($demandsList);

        if (!empty($demandsIds)) {

            $services = $this->find('all',
                        array(
                            'joins' => array(
                                    array(
                                        'table' => 'demands_service_types',
                                        'alias' => 'Evaluation',
                                        'type' => 'INNER',
                                        'conditions' => array(
                                            'Demand.id = Evaluation.demand_id',
                                        )
                                    ),
                                    array(
                                        'table' => 'service_types',
                                        'alias' => 'ServiceType',
                                        'type' => 'INNER',
                                        'conditions' => array(
                                            'Evaluation.service_type_id = ServiceType.id',
                                        )
                                    ),
                                    array(
                                        'table' => 'providers',
                                        'alias' => 'Provider',
                                        'type' => 'INNER',
                                        'conditions' => array(
                                            'Provider.id = Demand.provider_id',
                                        )
                                    ),
                                ),
                            'fields' => array('ServiceType.name','Demand.id', 'Demand.modified','Evaluation.id', 'Evaluation.service_type_id', 'Provider.name'),
                            'contain' => array(),
                            'conditions' => array(
                                'Demand.id IN' => $demandsIds,
                                'Evaluation.pending_consumer_evaluation' => '1',
                )
            ));
            return $services;
        } else {
            return null;
        }

    }

    public function getHomeOwnerRequests($consumerId)
    {
        if(empty($consumerId)) return false;

        $demands = $this->find('all',
                array(
                    'fields' => array(
                        'id',
                        'created',
                        'modified',
                        'status'
                        ),
                    'conditions' => array(
                        'status' => array(
                            EJQ_DEMAND_STATUS_REQUEST_OPEN,
                            EJQ_DEMAND_STATUS_ESTIMATION_ASSIGNED,
                            EJQ_DEMAND_STATUS_WAITING_FOR_ESTIMATOR_SCHEDULE_APPROVAL,
                            EJQ_DEMAND_STATUS_WAITING_FOR_HOME_OWNER_SCHEDULE_APPROVAL,
                            EJQ_DEMAND_STATUS_ESTIMATION_READY_FOR_DISPATCH,
                            EJQ_DEMAND_STATUS_ESTIMATION_DISPATCHED,
                            ),
                        'Demand.consumer_id' => $consumerId,
                        ),
                    'contain' => array(
                        'Request.title',
                        'Request.description',
                        'ServiceType.id',
                        'ServiceType.name',
                        'Provider.name',
                        'ServiceType.Schedule' => array(
                            'demand_id',
                            'schedule_period_begin',
                            'schedule_period_end',

                        ),
                        ),
                    'order' => ['Demand.modified DESC'],
                    ));
        foreach ($demands as $key => $demandData) {
            $demand = $demandData['Demand'];
            $demands[$key]['Demand']['can_cancel'] = $this->canBeCanceled($demand);
        }

        return $demands;

    }

    public function getRequestInfo($id)
    {
        if(empty($id)) {
            return false;
        }

        $data = $this->find('first',
                array(
                    'fields' => array(
                        'Demand.id',
                        'Demand.marketplace_id',
                        'Demand.provider_id',
                        'Demand.consumer_id',
                        'Demand.status',
                        ),
                    'conditions' => array(
                        'Demand.id' => $id,
                        ),
                    'contain' => array(
                        'Request.id',
                        'Request.description',
                        'Request.title',
                        'Request.preferred_visit_time',
                        'Consumer.id',
                        'Consumer.user_id',
                        'Consumer.name',
                        'Consumer.phone',
                        'Consumer.address',
                        'Provider.id',
                        'Provider.user_id',
                        'Provider.name',
                        'Schedule.schedule_period_begin',
                        'Schedule.schedule_period_end',
                        'Tender.id',
                        ),
                    ));
        
        return $data;

    }
    
    public function getStatusLabel($id, $role=EJQ_ROLE_VISITOR)
    {
        if(empty($id)) {
            return false;
        }
        
        $tenderInfo = $this->getTenderInfo($id);
        $status = $tenderInfo['Demand']['status'];
        $this->Provider->User->id = $tenderInfo['Provider']['user_id'];
        $estimatorName = $this->Provider->User->field('name');
        
        switch ($status) {
            case EJQ_DEMAND_STATUS_REQUEST_OPEN:
                return __('To be assigned');
                break;
                
            case EJQ_DEMAND_STATUS_ESTIMATION_ASSIGNED:
                return sprintf(__('Assigned to %s'), $estimatorName);
                break;
            
            case EJQ_DEMAND_STATUS_WAITING_FOR_ESTIMATOR_SCHEDULE_APPROVAL:
                if ($role == EJQ_ROLE_HOME_OWNER) {
                    return __('Schedule Waiting for confirmation');
                } else {
                    return __('Schedule Waiting for Project Developer Confirmation');
                }
                        
                break;

            case EJQ_DEMAND_STATUS_WAITING_FOR_HOME_OWNER_SCHEDULE_APPROVAL:
                return __('Schedule Waiting for approval from Home Owner ');
                break;

            case EJQ_DEMAND_STATUS_ESTIMATION_READY_FOR_DISPATCH:
                return __('Ready to dispatch');
                break;
            
            case EJQ_DEMAND_STATUS_ESTIMATION_DISPATCHED:
                //$this->Time->format($scheduleData['schedule'], '%b %d, %Y')
                return __('Visit scheduled');
                break;

            case EJQ_DEMAND_STATUS_TENDER_READY_TO_BIDS:
                return __('Ready to be published');
                break;

            case EJQ_DEMAND_STATUS_TENDER_READY_FOR_SITE_ADMIN_APPROVAL:
                return __('Waiting for approval from Site Admin ');
                break;

            case EJQ_DEMAND_STATUS_TENDER_READY_FOR_HOME_OWNER_APPROVAL:
                return __('Waiting for approval from Home Owner ');
                break;

            case EJQ_DEMAND_STATUS_TENDER_BIDDING_SCHEDULED:
                return sprintf(__('Bidding scheduled to begin on %s'), date("Y, M d", strtotime($tenderInfo['Tender']['open_to_bids_date'])));
                break;

            case EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS:
                return sprintf(__('Open for bidding until %s'), date("Y, M d", strtotime($tenderInfo['Tender']['close_to_bids_date'])));
                break;

            case EJQ_DEMAND_STATUS_TENDER_IN_PROGRESS:
                return __('Tender in progress');
                break;
            
            case EJQ_DEMAND_STATUS_TENDER_TO_BE_MODIFIED:
                return __('Tender needs modifications');
                break;
            
            case EJQ_DEMAND_STATUS_TENDER_CLOSED_TO_BIDS:
                return __('Closed to Bids');
                break;

            case EJQ_DEMAND_STATUS_TENDER_OPEN_FOR_BID_SELECTION:
                return __('Open for Bid Selection');
                break;

            case EJQ_DEMAND_STATUS_BID_SELECTED:
                return __('Bid Selected');
                break;

            case EJQ_DEMAND_STATUS_BID_DISCLOSED:
                return __('Contractor Disclosed');
                break;

            case EJQ_DEMAND_STATUS_CONTRACT_SIGNED:
                return __('Contract Signed');
                break;

            case EJQ_DEMAND_STATUS_JOB_IN_PROGRESS:
                return __('Job In Progress');
                break;

            case EJQ_DEMAND_STATUS_JOB_COMPLETED:
                return __('Job Completed');
                break;

            case EJQ_DEMAND_STATUS_REQUEST_OPEN:
            case EJQ_DEMAND_STATUS_REQUEST_CANCEL:
            case EJQ_DEMAND_STATUS_CLOSED:
            default:
                break;
        }

        return $status;
        
    }

    public function getTenderInfo($id)
    {
        if(empty($id)) {
            return false;
        }

        $data = $this->find('first',
                array(
                    'fields' => array(
                        'Demand.id', 
                        'Demand.status', 
                        'Demand.marketplace_id',
                        'YEAR(Demand.created) as Demand__year',
                        'Demand.franchise_label',
                        ),
                    'conditions' => array(
                        'Demand.id' => $id,
                        ),
                    'contain' => array(
                        'TermCondition' => array(
                            'id',
                            'description',
                            ),
                        'Tender' => array(
                            'id',
                            'title',
                            'description',
                            'chosen_bid_id',
                            'open_to_bids_date',
                            'close_to_bids_date',
                            'home_owner_comments',
                            ),
                        'Bid' => array('fields' => array(
                            'Bid.modified',
                            'Bid.id',
                            'Bid.status',
                            'Bid.value',
                            'Bid.shortlisted',
                            'Bid.provider_id',
                            'Bid.comments',
                            'Bid.contractor_alias',
                            ), 
                            'order' => array('Bid.modified'
                                
                            ),
                            'conditions' => array(
                                'status' => array(
                                    EJQ_BID_STATUS_SUBMITTED,
                                    EJQ_BID_STATUS_CHOSEN,
                                    ),
                                //'Demand.marketplace_id' => $marketplaceId,
                                ),
                            ),
                         'Bid' => array('fields' => array(
                            'Bid.modified',
                            'Bid.id',
                            'Bid.status',
                            'Bid.value',
                            'Bid.shortlisted',
                            'Bid.provider_id',
                            'Bid.contractor_alias',
                            ), 
                            'order' => array('Bid.modified'
                            )),
                       'Request' => array(
                            'id',
                            'title',
                            'description',
                            'preferred_visit_time',
                            'visit_time_suggested',
                            'municipality_id'
                            ),
                        'Schedule' => array(
                            'id',
                            'schedule_period_begin',
                            'schedule_period_end',
                        ),
                        'Provider' => array(
                            'id',
                            'user_id',
                            'name',
                            ),
                        'Consumer' => array(
                            'id',
                            'user_id',
                            'name',
                            'phone',
                            'address',
                            ),
                        'File' => array(
                            'id',
                            'path',
                            'filename',
                            'description',
                            ),
                        'ServiceType' => array(
                            'id',
                            'name',
                            ),
                        'Invoice' => array(
                            'id',
                            'invoice_for',
                            'type',
                            'total_value',
                            'consumer_id',
                            'provider_id',
                            'status',
                            'due_date',
                            'receipt_date',
                            'number',
                            ),
                        ),
                    ));
        if ($data['Demand']['status'] == EJQ_DEMAND_STATUS_TENDER_BIDDING_SCHEDULED) {
            $scheduledTime = strtotime($data['Tender']['open_to_bids_date'] . " 00:00:00") ;
            $today = strtotime("today");
            if ( $scheduledTime <= $today) {
                $Demand = new Demand;
                $Demand->id = $data['Tender']['id'];
                $Demand->saveField('status', EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS);
                unset($Demand);
                $data['Demand']['status'] = EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS;
            }
            
        }

        if ($data['Demand']['status'] == EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS) {
            $scheduledTime = strtotime($data['Tender']['close_to_bids_date'] . " 00:00:00") ;
            $today = strtotime("today");
            if ($scheduledTime < $today) {
                $Demand = new Demand;
                $Demand->id = $data['Tender']['id'];
                $Demand->saveField('status', EJQ_DEMAND_STATUS_TENDER_CLOSED_TO_BIDS);
                unset($Demand);
                $data['Demand']['status'] = EJQ_DEMAND_STATUS_TENDER_CLOSED_TO_BIDS;
            }
            
        }
        
        if ($data['Demand']['status'] == EJQ_DEMAND_STATUS_BID_DISCLOSED
                || $data['Demand']['status'] == EJQ_DEMAND_STATUS_CONTRACT_SIGNED
                || $data['Demand']['status'] == EJQ_DEMAND_STATUS_JOB_IN_PROGRESS
                || $data['Demand']['status'] == EJQ_DEMAND_STATUS_JOB_COMPLETED
                ) {
                $data['Tender']['disclosed_bid'] = true;
        } else {
                $data['Tender']['disclosed_bid'] = false;
        }


        $userId = $data['Consumer']['user_id'];
        $this->Consumer->User->id = $userId;
        $userEmail =$this->Consumer->User->field('email');
        $data['User']['email'] = $userEmail;

        $municipalityId = $data['Request']['municipality_id'];
        $this->Request->Municipality->id = $municipalityId;
        $municipalityName =$this->Request->Municipality->field('name');
        $data['Municipality']['name'] = $municipalityName;
        
        $jobInfo = $this->Tender->getJobInfo($data['Tender']['id']);
        if (!empty($jobInfo)) {
            $data['Job'] = $jobInfo['Job'];
            if (!empty($jobInfo['Review'])) {
                $data['Review'] = $jobInfo['Review'];
            }
        }

        return $data;

    }

    public function handleEvaluationAfterSupply($id)
    {

        $services = $this->find('all',
                    array(
                        'joins' => array(
                                array(
                                    'table' => 'demands_service_types',
                                    'alias' => 'Evaluation',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'Demand.id = Evaluation.demand_id',
                                    ),
                                ),
                            ),
                        'fields' => array('Demand.id','Evaluation.id', 'Evaluation.service_type_id'),
                        'contain' => array(),
                        'conditions' => array(
                            'Demand.id' => $id,
                            'Evaluation.pending_consumer_evaluation IS NULL',
            ),
        ));

        $this->id = $id;
        foreach ($services as $key => $evaluation) {
            if (isset($evaluation['Evaluation'])) {
                $this->Evaluation->id = $evaluation['Evaluation']['id'];
                $data['Evaluation'] = $evaluation['Evaluation'];
                $data['Evaluation']['demand_id'] = $id;
                $data['Evaluation']['pending_consumer_evaluation'] = '1';
                $this->Evaluation->save($data);
            }
        }
    }

    public function indexesServicesAndSchedules($data)
    {
        foreach ($data as $mainKey => $demandData) {

            $indexedSchedule = array();
            $scheduleData = $demandData['Schedule'];

            foreach ($demandData['ServiceType'] as $serviceData) {
                $serviceTypeId = $serviceData['id'];
                foreach ($scheduleData as $key => $schedule) {
                    if ($serviceTypeId == $schedule['service_type_id']) {
                        $indexedSchedule[$serviceTypeId] = $schedule;
                        unset($scheduleData[$key]);
                        break;
                    }
                }


            }
            $data[$mainKey]['Schedule'] = $indexedSchedule;

        }
        return($data);
    }

    public function isThisUserItsConsumer($id, $userId)
    {
        try {
            $this->verifyIdAndUserId($id, $userId);
        } catch (Exception $ex) {
            return false;
        }

        $demand = $this->find('first', array(
                        'fields' => array(
                            'Demand.id',
                            'Demand.consumer_id',
                            ),
                        'contain' => array('Consumer.user_id'),
                        'conditions' => array(
                            'Demand.id' => $id,
                            'Consumer.user_id' => $userId,
                        )
                    ));

        if (empty($demand)) {
            return false;
        }
        return $demand['Demand']['consumer_id'];
    }

    public function isThisUserItsEstimator($id, $userId, $EjqEstimatorMetaProviderId)
    {
        try {
            $this->verifyIdAndUserId($id, $userId);
        } catch (Exception $ex) {
            return false;
        }

        $demand = $this->find('first', array(
                        'fields' => array(
                            'Demand.id',
                            'Demand.consumer_id',
                            ),
                        'contain' => array('Provider.user_id'),
                        'conditions' => array(
                            'Demand.id' => $id,
                            'Provider.user_id' => $userId,
                            'Provider.meta_provider_id' => $EjqEstimatorMetaProviderId,
                        )
                    ));

        if (empty($demand)) {
            return false;
        }
        return true;
    }

    public function isThisUserItsProvider($id, $userId)
    {
        try {
            $this->verifyIdAndUserId($id, $userId);
        } catch (Exception $ex) {
            throw $ex;
        }

        $demand = $this->find('first', array(
                        'fields' => array(
                            'Demand.id',
                            'Demand.consumer_id',
                            ),
                        'contain' => array('Provider.user_id'),
                        'conditions' => array(
                            'Demand.id' => $id,
                            'Provider.user_id' => $userId,
                        )
                    ));

        if (empty($demand)) {
            return false;
        }
        return true;
    }

    public function listDemandsByConsumerId($consumerId)
    {


        if(empty($consumerId)) {
            return false;
        }

        $demands = $this->find('list',
                array(
                    'fields' => array('id'),
                    'conditions' => array('consumer_id' => $consumerId),
                    ));

        if (isset($demands['Demand'])) {
            return $demands;
        } else {
            return false;
        }
    }

    public function listMarketplaceScheduleToCloseBidding($marketplaceId)
    {
        if(empty($marketplaceId)) {
            return false;
        }
        $todayString = date("Y-m-d");
        $demands = $this->find('list',
                array(
                    'fields' => array(
                        'id',
                        ),
                    'conditions' => array(
                        'status' => array(
                            EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS,
                            ),
                        'Demand.marketplace_id' => $marketplaceId,
                        'Tender.close_to_bids_date <' => $todayString,
                        ),
                    'contain' => array(
                        'Tender.id',
                        ),
                    ));
        return $demands;

    }

    public function listMarketplaceScheduleToBiddingToday($marketplaceId)
    {
        if(empty($marketplaceId)) {
            return false;
        }
        $todayString = date("Y-m-d");
        $demands = $this->find('list',
                array(
                    'fields' => array(
                        'id',
                        ),
                    'conditions' => array(
                        'status' => array(
                            EJQ_DEMAND_STATUS_TENDER_BIDDING_SCHEDULED,
                            ),
                        'Demand.marketplace_id' => $marketplaceId,
                        'Tender.open_to_bids_date <=' => $todayString,
                        ),
                    'contain' => array(
                        'Tender.id',
                        ),
                    ));
        return $demands;

    }

    public function listQualifiedProviders($id, $metaProviderId)
    {
        // discover which services are needed
        $demandServices = $this->find('first',
                array(
                    'fields' => array('id', 'marketplace_id'),
                    'conditions' => array('id' => $id),
                    'contain' => array(
                        'ServiceType.id', 'ServiceType.name',
                        'ServiceType.online_criterion',
                        'ServiceType.qualified_criterion',
                        'ServiceType.scheduled_criterion',
                        'ServiceType.weekdays_criterion',
                        ),
                    ));
        
        // discover apliable criterias
        $criteria = $this->discoverDemandApliableCriteria($demandServices['ServiceType']);
        // find provider aplying criterias
        $apliable = $this->extractApliableData($criteria);
        $needed = $this->extractNeededData($criteria);
        $providers = $this->fetchProvidersForThisServices(array_keys($apliable['qualified']), $demandServices['Demand']['marketplace_id'], $metaProviderId);  
        $qualifiedProviders = $this->whichProvidersAreQualified($providers, $needed);
        
        // associate demand to provider
        if (count($qualifiedProviders) > 0) {
            $providersList = array();
            foreach ($qualifiedProviders as $providerId) {
                $this->Provider->id = $providerId;
                $name = $this->Provider->field('name');
                $providersList[$providerId] = $name;
            }
            
            return($providersList);

        } else {
            return null;
        }

    }

    public function listServiceTypes($id)
    {
        $demandData = $this->find(
                'all',
                array(
                    'contain' => array('ServiceType.id', 'ServiceType.name'),
                    'conditions' => array(
                        'id' => $id,
                        ),
                    )
                );
        $result = array();
        foreach ($demandData['0']['ServiceType'] as $key => $serviceType) {
            $result[$serviceType['id']] = $serviceType['name'];
        }
        return $result;
        
    }
    
    public function openToBidsAllTendersScheduledToBiddingToday($marketplaceId)
    {
        $demands = $this->listMarketplaceScheduleToBiddingToday($marketplaceId);
        foreach ($demands as $key => $demandId) {
            $Demand = new Demand;
            $Demand->id = $demandId;
            $Demand->saveField('status', EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS);
            unset($Demand);
        }
        
    }
    
    public function possibleActions($id, $role, $profileId=null)
    {


        $this->id = $id;
        $status = $this->field('status');
        switch ($role) {
            case EJQ_ROLE_ADMIN:
                $actions = $this->possibleAdminActions($id, $status);
                break;

            case EJQ_ROLE_HOME_OWNER:
                $actions = $this->possibleHomeOwnerActions($id, $status);
                break;

            case EJQ_ROLE_ESTIMATOR:
                $actions = $this->possibleEstimatorActions($id, $status);
                break;

            case EJQ_ROLE_CONTRACTOR:
                $actions = $this->possibleContractorActions($id, $status, $profileId);
                break;

            default:
                $actions = array();
                break;
        }
        
        return $actions;
        
        
    }

    public function countUserDemandsAsConsumer($userId, $marketplaceId, $status=array())
    {
        if(empty($userId)) {
            return false;
        }

        $count = $this->find('count', array(
                'fields' => array(
                    'Demand.id',
                    'Demand.consumer_id',
                    ),
                'contain' => array('Consumer.user_id'),
                'conditions' => array(
                    'Demand.marketplace_id' => $marketplaceId,
                    'Consumer.user_id' => $userId,
                    'Demand.status' => $status,
                )
            ));

        return $count;

    }

    public function countUserDemandsAsProvider($userId, $marketplaceId, $status=array())
    {
        if(empty($userId)) {
            return false;
        }

        $count = $this->find('count', array(
                'fields' => array(
                    'Demand.id',
                    'Demand.provider_id',
                    ),
                'contain' => array('Provider.user_id'),
                'conditions' => array(
                    'Demand.marketplace_id' => $marketplaceId,
                    'Provider.user_id' => $userId,
                    'Demand.status' => $status,
                )
            ));

        return $count;

    }
   


    public function saveEvaluation($id, $value)
    {
        $services = $this->find('all',
                    array(
                        'joins' => array(
                                array(
                                    'table' => 'demands_service_types',
                                    'alias' => 'Evaluation',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'Demand.id = Evaluation.demand_id',
                                    )
                                )
                            ),
                        'fields' => array('Demand.id', 'Demand.provider_id','Evaluation.id', 'Evaluation.service_type_id'),
                        'contain' => array(),
                        'conditions' => array(
                            'Demand.id' => $id,
            )
        ));
        $this->id = $id;
        foreach ($services as $key => $evaluation) {
            if (isset($evaluation['Evaluation'])) {
                $this->Evaluation->id = $evaluation['Evaluation']['id'];
                $data['Evaluation'] = $evaluation['Evaluation'];
                $data['Evaluation']['demand_id'] = $id;
                $data['Evaluation']['pending_consumer_evaluation'] = false;
                $data['Evaluation']['consumer_evaluation_value'] = $value;
                $this->Evaluation->save($data);
                $this->Provider->saveEvaluation($evaluation['Demand']['provider_id'], $evaluation['Evaluation']['service_type_id'], $value);
            }
        }
    }
    
    public function verifyIfContractorCanBidOnDemand($id, $providerId)
    {
        $this->id = $id;
        $demandStatus = $this->field('status');
        
        $demandServices = $this->listServiceTypes($id);
        $this->Provider->id = $providerId;
        $qualified = $this->Provider->field('qualified');
        if (!$qualified) {
            return false;
        }
        
        $providerServiceTypes = $this->Provider->listServiceTypes($providerId);
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

        if((!$canBid) || ($demandStatus != EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS)) {
            $canBid = false;
        } 

        return $canBid;

    }

    public function tenderDetailsShouldBeSeen($id, $role)
    {
        $should = false;
        $this->id = $id;
        $demandStatus = $this->field('status');

        if($role == EJQ_ROLE_HOME_OWNER) {
            switch($demandStatus) {
                case EJQ_DEMAND_STATUS_TENDER_READY_FOR_HOME_OWNER_APPROVAL:
                case EJQ_DEMAND_STATUS_TENDER_TO_BE_MODIFIED:
                case EJQ_DEMAND_STATUS_TENDER_READY_TO_BIDS:
                case EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS:
                case EJQ_DEMAND_STATUS_TENDER_CLOSED_TO_BIDS:
                case EJQ_DEMAND_STATUS_BID_SELECTED:
                case EJQ_DEMAND_STATUS_BID_DISCLOSED:
                case EJQ_DEMAND_STATUS_CONTRACT_SIGNED:
                case EJQ_DEMAND_STATUS_JOB_IN_PROGRESS:
                case EJQ_DEMAND_STATUS_JOB_COMPLETED:
                case EJQ_DEMAND_STATUS_CLOSED:
                    $should = true;
                    break;
                
                default:
                    break;
            }
        } 

        if($role == EJQ_ROLE_ESTIMATOR || $role == EJQ_ROLE_ADMIN) {
            switch($demandStatus) {
                case EJQ_DEMAND_STATUS_TENDER_IN_PROGRESS:
                case EJQ_DEMAND_STATUS_TENDER_READY_FOR_SITE_ADMIN_APPROVAL:
                case EJQ_DEMAND_STATUS_TENDER_READY_FOR_HOME_OWNER_APPROVAL:
                case EJQ_DEMAND_STATUS_TENDER_TO_BE_MODIFIED:
                case EJQ_DEMAND_STATUS_TENDER_READY_TO_BIDS:
                case EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS:
                case EJQ_DEMAND_STATUS_TENDER_CLOSED_TO_BIDS:
                case EJQ_DEMAND_STATUS_CLOSED:
                case EJQ_DEMAND_STATUS_BID_SELECTED:
                case EJQ_DEMAND_STATUS_BID_DISCLOSED:
                case EJQ_DEMAND_STATUS_CONTRACT_SIGNED:
                case EJQ_DEMAND_STATUS_JOB_IN_PROGRESS:
                case EJQ_DEMAND_STATUS_JOB_COMPLETED:
                    $should = true;
                    break;
                
                default:
                    break;
            }
        } 

        return $should;

    }

    
    

    private function applyCriteria($apliable, $onlineProviders, $qualifiedProviders, $weekdayProviders, $scheduledProviders)
    {
        $elegible = array();
        if (isset($apliable[CRITERION_ONLINE])) {
            $elegible = $onlineProviders;
            if (isset($apliable[CRITERION_QUALIFIED])) {
                $elegible = array_intersect($elegible, $qualifiedProviders);
            }
            if (isset($apliable[CRITERION_WEEKDAYS])) {
                $elegible = array_intersect($elegible, $weekdayProviders);
            }
            if (isset($apliable[CRITERION_SCHEDULED])) {
                $elegible = array_intersect($elegible, $scheduledProviders);
            }
        } else {
            if (isset($apliable[CRITERION_QUALIFIED])) {
                $elegible = $qualifiedProviders;
                if (isset($apliable[CRITERION_WEEKDAYS])) {
                    $elegible = array_intersect($elegible, $weekdayProviders);
                }
                if (isset($apliable[CRITERION_SCHEDULED])) {
                    $elegible = array_intersect($elegible, $scheduledProviders);
                }
            } else {
                if (isset($apliable[CRITERION_WEEKDAYS])) {
                    $elegible = $weekdayProviders;
                    if (isset($apliable[CRITERION_SCHEDULED])) {
                        $elegible = array_intersect($elegible, $scheduledProviders);
                    }
                } else {
                    if (isset($apliable[CRITERION_SCHEDULED])) {
                        $elegible = $scheduledProviders;
                    } else {
                        $elegible = null;
                    }
                }
            }




        }

        return $elegible;

    }

    private function assignToProvider($id, $providerId)
    {
        $this->id = $id;
        $data = array('Demand' => array(
                'provider_id' => $providerId,
                'status' => DEMAND_STATUS_ASSIGNED,
            ));

        $this->save($data, true, array('provider_id', 'status'));

    }

    private function checkIfProviderIsAvailable($providerId, $serviceTypeId, $dayOfTheWeekMask)
    {
        $providerWeekdays = $this->
                Provider->getWeekdays($providerId, $serviceTypeId);

        $intWeekdays = bindec($providerWeekdays);
        $isAvailable = $intWeekdays & $dayOfTheWeekMask; //binary AND

        return $isAvailable;

    }
    
    

    private function discoverDemandApliableCriteria($demandServiceTypes)
    {

        foreach ($demandServiceTypes as $serviceDemanded) {
            $data[$serviceDemanded['id']] = $this->discoverServiceTypeApliableCriteria($serviceDemanded);
        }

        $apliedForDemand = array();
        $neededForDemand = array();
        foreach ($data as $serviceTypeId => $criteria) {
            if ((isset($criteria['apliable'])) && (is_array($criteria['apliable']))) {
                foreach ($criteria['apliable'] as $type => $status) {
                    $apliedForDemand[$type][$serviceTypeId] = true;
                    $neededForDemand[$type][$serviceTypeId] = true;
                }
            }
        }

        $criteria = array('apliable' => $apliedForDemand, 'needed' => $neededForDemand);

        return($criteria);

    }

    private function discoverServiceTypeApliableCriteria($serviceDemanded)
    {
        $apliable = array();
        $serviceId = $serviceDemanded['DemandsServiceType']['service_type_id'];
        if ($serviceDemanded['online_criterion'] == true) {
            $apliable[CRITERION_ONLINE] = true;
        }
        if ($serviceDemanded['qualified_criterion'] == true) {
            $apliable[CRITERION_QUALIFIED] = true;
        }
        if ($serviceDemanded['scheduled_criterion'] == true) {
            $apliable[CRITERION_SCHEDULED] = true;
        }
        if ($serviceDemanded['weekdays_criterion'] == true) {
            $apliable[CRITERION_WEEKDAYS] = true;
        }


        $criteria = array('apliable' => $apliable);
        return($criteria);
    }

    private function extractApliableData($data)
    {
        $apliable = array();
        if (isset($data['apliable'])) {
            $apliable = $data['apliable'];
        }

        return $apliable;
    }

    private function extractNeededData($data)
    {
        $needed = array();
        if (isset($data['needed'])) {
            $needed = $data['needed'];
        }

        return $needed;
    }

    private function fetchProvidersForThisServices($services, $marketplaceId, $metaProviderId=null)
    {
        $options =  array(
                    'fields' => array('Provider.id', 'Provider.created'),
                    'conditions' => array(
                        'Provider.marketplace_id' => $marketplaceId,
                        'Provider.qualified'  => true,
                        ),
                    'contain' => array(
                        'ServiceType' => array(
                                 'fields' => array('id', 'name'),
                                'conditions'=>array(
                                    'ServiceType.id IN'=>$services)
                                ),
                            ),
                    );
        
        if (!empty($metaProviderId)) {
            $options['conditions']['Provider.meta_provider_id'] = $metaProviderId;
        }
        
        $providers = $this->Provider->find('all', $options);
        return $providers;

    }

    private function getMaskOfTodayDate()
    {
        $dayOfTheWeek = 6 - date('w');
        $dayOfTheWeekMask = pow(2, $dayOfTheWeek);
        return $dayOfTheWeekMask;
    }

    private function manageOnlineCriterion($apliable, $needed, $marketplaceId)
    {
       
        $onlineProviders = array();
        if (isset($apliable[CRITERION_ONLINE])) {
            $serviceIds = array_keys($apliable[CRITERION_ONLINE]);
            $providers = $this->fetchProvidersForThisServices($serviceIds, $marketplaceId);
            $onlineProviders = $this->whichProvidersAreOnline($providers, $needed);
        }

        return $onlineProviders;
    }

    private function manageQualifiedCriterion($apliable, $needed, $marketplaceId)
    {
        $qualifiedProviders = array();
        if (isset($apliable[CRITERION_QUALIFIED])) {
            $serviceIds = array_keys($apliable[CRITERION_QUALIFIED]);
            $providers = $this->fetchProvidersForThisServices($serviceIds, $marketplaceId);
            $qualifiedProviders = $this->whichProvidersAreQualified($providers, $needed);
        }

        return $qualifiedProviders;
    }

    private function manageScheduledCriterion($apliable, $needed, $marketplaceId)
    {
        $scheduledProviders = array();
        if (isset($apliable[CRITERION_SCHEDULED])) {
            $serviceIds = array_keys($apliable[CRITERION_SCHEDULED]);
            $providers = $this->fetchProvidersForThisServices($serviceIds, $marketplaceId);
            $scheduledProviders = $this->whichProvidersMeetSchedule($providers, $needed);
        }
        return $scheduledProviders;
    }

    private function manageWeekdaysCriterion($apliable, $needed, $marketplaceId)
    {
        $weekdayProviders = array();
        // manage weekdays
        if (isset($apliable[CRITERION_WEEKDAYS])) {
            $serviceIds = array_keys($apliable[CRITERION_WEEKDAYS]);
            $providers = $this->fetchProvidersForThisServices($serviceIds, $marketplaceId);
            $weekdayProviders = $this->whichProvidersAreAvailableDayOfWeek($providers, $needed);
        }
        return $weekdayProviders;
    }
    
    private function possibleAdminActions($id, $status)
    {
        $tenderInfo = $this->getTenderInfo($id);
        if (!empty($tenderInfo['Tender']['id'])) {
            $tenderId = $tenderInfo['Tender']['id'];
        } else {
            $tenderId = null;
        }
        
        $tenderActions = array();
        $rights = array();
        
        $rights['tender'] = true;
        
        switch ($status) {
            case EJQ_DEMAND_STATUS_REQUEST_OPEN:
            case EJQ_DEMAND_STATUS_ESTIMATION_ASSIGNED:
            case EJQ_DEMAND_STATUS_WAITING_FOR_ESTIMATOR_SCHEDULE_APPROVAL:
            case EJQ_DEMAND_STATUS_WAITING_FOR_HOME_OWNER_SCHEDULE_APPROVAL:
            case EJQ_DEMAND_STATUS_ESTIMATION_READY_FOR_DISPATCH:
            case EJQ_DEMAND_STATUS_ESTIMATION_DISPATCHED:
                $rights['municipality'] = true;

                if (!empty($tenderInfo['ServiceType'])) {
                    if (empty($tenderInfo['Provider']['id'])) {
                        $label = __('Define Project Developer');
                    } else {
                        $label = __('Change Project Developer');
                    }
                    $rights['estimator'] = array(
                        'id' => 'show_define_estimator',
                        'href' => '#define_estimator',
                        'label' => $label,
                    );
                }

                if (empty($tenderInfo['ServiceType'])) {
                    $label = __('Define job categories');
                } else {
                    $label = __('Change job categories');
                }
                
                $rights['service_types'] = array(
                    'id' => 'update_service_types',
                    'href' => '#request_services_form',
                    'label' => $label,
                );                   
                
                
                if (in_array($status, [
                    EJQ_DEMAND_STATUS_ESTIMATION_READY_FOR_DISPATCH,
                    EJQ_DEMAND_STATUS_WAITING_FOR_ESTIMATOR_SCHEDULE_APPROVAL,
                    EJQ_DEMAND_STATUS_WAITING_FOR_HOME_OWNER_SCHEDULE_APPROVAL,
                    EJQ_DEMAND_STATUS_ESTIMATION_ASSIGNED,
                    ])) {
                    $tenderActions['dispatch_estimator'] = [
                        'id' => 'dispatch_estimator',
                        'href' => Router::url(['controller'=>'demands', 'action'=>'dispatch_estimator', $id]),
                        'label' => __('Dispatch Project Developer'),
                    ];

                }
                
                $tenderActions['cancel_request'] = [
                    'id' => 'call_cancel_request',
                    'href' => Router::url(['controller'=>'demands', 'action'=>'cancel_request', $id]),
                    'label' => __('Cancel Request'),
                ];                
                break;

            case EJQ_DEMAND_STATUS_TENDER_READY_TO_BIDS:
                $tenderActions['open_to_bids'] = array(
                    'id' => 'open_tender_to_bids',
                    'href' => Router::url(['controller'=>'tenders', 'action'=>'open_to_bids', $tenderId]),
                    'label' => __('Open tender to bids'),
                );
                break;

            case EJQ_DEMAND_STATUS_TENDER_IN_PROGRESS:
            case EJQ_DEMAND_STATUS_TENDER_READY_FOR_SITE_ADMIN_APPROVAL:
            case EJQ_DEMAND_STATUS_TENDER_TO_BE_MODIFIED:
                if (!empty($tenderInfo['Tender']['description'])) {
                    $tenderActions['submit_to_home_owner'] = array(
                        'id' => 'submit_to_home_owner',
                        'href' => Router::url(array('controller'=>'tenders', 'action'=>'submit_to_home_owner', $tenderId)),
                        'label' => __('Submit to home owner'),
                    );
                }
                break;

            case EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS:
                $tenderActions['extend_to_bids'] = array(
                    'id' => 'extend_to_bids',
                    'href' => Router::url(array('controller'=>'tenders', 'action'=>'extend_to_bids', $tenderId)),
                    'label' => __('Extend tender to bids'),
                );
                $tenderActions['close_to_bids'] = array(
                    'id' => 'close_tender_to_bids',
                    'href' => Router::url(array('controller'=>'tenders', 'action'=>'close_to_bids', $tenderId)),
                    'label' => __('Close tender to bids'),
                );
                break;

            case EJQ_DEMAND_STATUS_TENDER_CLOSED_TO_BIDS:
                $tenderActions['reopen_to_bids'] = array(
                    'id' => 'reopen_tender_to_bids',
                    'href' => Router::url(array('controller'=>'tenders', 'action'=>'reopen_to_bids', $tenderId)),
                    'label' => __('Reopen to bids'),
                );
                $tenderActions['open_for_bid_selection'] = array(
                    'id' => 'open_for_bid_selection',
                    'href' => Router::url(array('controller'=>'tenders', 'action'=>'open_for_bid_selection', $tenderId)),
                    'label' => __('Open for bid selection'),
                );
                break;

            case EJQ_DEMAND_STATUS_BID_SELECTED:
                $tenderActions['disclose_chosen_bid'] = array(
                    'id' => 'close_disclose_chosen_bid',
                    'href' => Router::url(array('controller'=>'tenders', 'action'=>'disclose_chosen_bid', $tenderId)),
                    'label' => __('Disclose chosen bid'),
                );
                break;
            
            case EJQ_DEMAND_STATUS_BID_DISCLOSED:
                $tenderActions['report_contract_signing'] = array(
                    'id' => 'report_contract_signing',
                    'href' => Router::url(array('controller'=>'tenders', 'action'=>'report_contract_signing', $tenderId)),
                    'label' => __('Report contract signing'),
                );
                break;
            
            default:
                break;
        }
        
        
        return ['actions' => ['tenders' => $tenderActions, 'bids' => []], 'rights' => $rights];
        
    }

    private function possibleContractorActions($id, $status, $profileId)
    {
        $tenderInfo = $this->getTenderInfo($id);
        if (!empty($tenderInfo['Tender']['id'])) {
            $tenderId = $tenderInfo['Tender']['id'];
        } else {
            $tenderId = null;
        }
        
        $tenderActions = array();
        $rights = array();

        $submittedBid = $this->Tender->contractorHasSubmittedBidOnTender($tenderId, $profileId);
        $openBid = $this->Tender->contractorHasOpenBidOnTender($tenderId, $profileId);
        
        switch($status) {
            case EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS:
                $termsCount = $complianceCount = 0;
                if (empty($submittedBid)) {
                    if (!empty($tenderInfo['TermCondition'])) {
                        $termsCount = count($tenderInfo['TermCondition']);
                        if (!empty($openBid['Compliance'])) {
                            foreach ($openBid['Compliance'] as $compliance) {
                                if (!is_null($compliance['compliant'])) {
                                    $complianceCount++;
                                }
                            }
                        }
                    }
                    if ($termsCount <= $complianceCount) {
                        if (!empty($openBid['Bid']['value'])) {
                            if ($openBid['Bid']['value'] > 0) {
                                $tenderActions['submit_bid'] = array(
                                    'id' => 'show_submit_bid',
                                    'href' => Router::url(['controller'=>'tenders', 'action'=>'submit_bid', $tenderId]),
                                    'label' => __('Submit bid'),
                                );
                            }
                        }
                        if (empty($tenderActions['submit_bid'])) {
                            $tenderActions['submit_bid'] = array(
                                'id' => 'show_submit_bid',
                                'href' => '#',
                                'label' => __("Enter a value to Submit bid"),
                                'disabled' => true, 
                            );
                        }
                        
                    } else {
                        $tenderActions['submit_bid'] = array(
                            'id' => 'show_submit_bid',
                            'href' => '#',
                            'label' => __("Respond T's & C's to Submit bid"),
                            'disabled' => true, 
                        );
                        
                    }
                    $rights['bid'] = true;

                }
                
                break;
                
//            case EJQ_DEMAND_STATUS_BID_DISCLOSED:
            case EJQ_DEMAND_STATUS_CONTRACT_SIGNED:
                $tenderActions['contractor_report_begin'] = array(
                    'id' => 'contractor_report_begin',
                    'href' => Router::url(['controller'=>'jobs', 'action'=>'contractor_report_begin', $tenderId]),
                    'label' => __('Report job start'),
                );
                break;
                
            case EJQ_DEMAND_STATUS_JOB_IN_PROGRESS:
                $tenderActions['contractor_report_end'] = array(
                    'id' => 'contractor_report_end',
                    'href' => Router::url(['controller'=>'jobs', 'action'=>'contractor_report_end', $tenderId]),
                    'label' => __('Report job completion'),
                );
                $jobInfo = $this->Tender->getJobInfo($tenderId);
                if(!empty($jobInfo['Job']['date_end_contractor'])) {
                    $tenderActions['contractor_report_end']['label'] = __('Change job completion date');
                }
                
                break;
                
            default:
                break;

            
        }
        return ['actions' => ['tenders' => $tenderActions, 'bids' => []], 'rights' => $rights];

    }

    private function possibleEstimatorActions($id, $status)
    {
        $tenderInfo = $this->getTenderInfo($id);
        if (!empty($tenderInfo['Tender']['id'])) {
            $tenderId = $tenderInfo['Tender']['id'];
        } else {
            $tenderId = null;
        }
        $tenderActions = array();
        $rights = array();
        
        switch($status) {
            case EJQ_DEMAND_STATUS_TENDER_IN_PROGRESS:
                if (!empty($tenderInfo['Tender']['description'])) {
                    $tenderActions['submit_to_site_admin'] = array(
                        'id' => 'submit_to_site_admin',
                        'href' => Router::url(['controller'=>'tenders', 'action'=>'submit_to_site_admin', $tenderId]),
                        'label' => __('Submit to Site Admin'),
                    );
                }
                $rights['tender'] = true;
                break;

            case EJQ_DEMAND_STATUS_ESTIMATION_ASSIGNED:
                $tenderActions['suggest_visit_time'] = [
                    'id' => 'suggest_visit_time',
                    'href' => Router::url(['controller'=>'demands', 'action'=>'suggest_visit_time', $id]),
                    'label' => __('Suggest visit time'),
                ];                
                break;

            case EJQ_DEMAND_STATUS_WAITING_FOR_ESTIMATOR_SCHEDULE_APPROVAL:
                $tenderActions['approve_schedule'] = array(
                    'id' => 'approve_schedule',
                    'href' => Router::url(['controller'=>'demands', 'action'=>'approve_schedule', $id]),
                    'label' => __('Approve schedule'),
                );
                $tenderActions['suggest_visit_time'] = [
                    'id' => 'suggest_visit_time',
                    'href' => Router::url(['controller'=>'demands', 'action'=>'suggest_visit_time', $id]),
                    'label' => __('Suggest visit time'),
                ];                
                break;

            case EJQ_DEMAND_STATUS_ESTIMATION_DISPATCHED:
                $tenderActions['create_tender'] = array(
                    'id' => 'show_create_tender',
                    'href' => Router::url(['controller'=>'tenders', 'action'=>'start', $tenderId]),
                    'label' => __('Create Tender'),
                );
                $tenderActions['cancel_request'] = array(
                    'id' => 'call_cancel_request',
                    'href' => Router::url(['controller'=>'demands', 'action'=>'cancel_request', $id]),
                    'label' => __('Cancel Request'),
                );
                break;
            
            default:
                break;
        }
        return ['actions' => ['tenders' => $tenderActions, 'bids' => []], 'rights' => $rights];
        
    }

    private function possibleHomeOwnerActions($id, $status)
    {
        $tenderInfo = $this->getTenderInfo($id);
        if (!empty($tenderInfo['Tender']['id'])) {
            $tenderId = $tenderInfo['Tender']['id'];
        } else {
            $tenderId = null;
        }
        $tenderActions = array();
        $bidActions = array();
        $rights = array();
        
        
        switch($status) {
            case EJQ_DEMAND_STATUS_TENDER_READY_FOR_HOME_OWNER_APPROVAL:
                $tenderActions['ask_for_modifications'] = array(
                    'id' => 'ask_for_modifications',
                    'href' => Router::url(['controller'=>'tenders', 'action'=>'ask_for_modifications', $tenderId]),
                    'label' => __('Ask for modifications'),
                );
                $tenderActions['approve_tender'] = array(
                    'id' => 'approve_tender',
                    'href' => Router::url(['controller'=>'tenders', 'action'=>'approve_tender', $tenderId]),
                    'label' => __('Approve tender to publish'),
                );
                break;

            case EJQ_DEMAND_STATUS_TENDER_OPEN_FOR_BID_SELECTION:
                $bidActions['choose_bid'] = array(
                    'id' => 'show_choose_bid',
                    'href' => '#choose_bid',
                    'label' => __('Choose this bid'),
                );
                break;

            case EJQ_DEMAND_STATUS_WAITING_FOR_HOME_OWNER_SCHEDULE_APPROVAL:
                $tenderActions['approve_schedule'] = array(
                    'id' => 'approve_schedule',
                    'href' => Router::url(['controller'=>'demands', 'action'=>'approve_schedule', $id]),
                    'label' => __('Approve schedule'),
                );
                $tenderActions['suggest_visit_time'] = [
                    'id' => 'suggest_visit_time',
                    'href' => Router::url(['controller'=>'demands', 'action'=>'suggest_visit_time', $id]),
                    'label' => __('Suggest visit time'),
                ];                
                break;

//            case EJQ_DEMAND_STATUS_BID_DISCLOSED:
            case EJQ_DEMAND_STATUS_CONTRACT_SIGNED:
                $tenderActions['home_owner_report_begin'] = array(
                    'id' => 'home_owner_report_begin',
                    'href' => Router::url(['controller'=>'jobs', 'action'=>'home_owner_report_begin', $tenderId]),
                    'label' => __('Report job start'),
                );
                $jobInfo = $this->Tender->getJobInfo($tenderId);
                if(!empty($jobInfo['Job']['date_begin_home_owner'])) {
                    $tenderActions['home_owner_report_begin']['label'] = __("Change job's beginning date");
                }
                
                break;
                
            case EJQ_DEMAND_STATUS_JOB_IN_PROGRESS:
                $tenderActions['home_owner_report_end'] = array(
                    'id' => 'home_owner_report_end',
                    'href' => Router::url(['controller'=>'jobs', 'action'=>'home_owner_report_end', $tenderId]),
                    'label' => __('Report job completion'),
                );
                $jobInfo = $this->Tender->getJobInfo($tenderId);
                if(!empty($jobInfo['Job']['date_end_home_owner'])) {
                    $tenderActions['home_owner_report_end']['label'] = __('Change job completion date');
                }                
                break;
            
            default:
                break;
        }
        return ['actions' => ['tenders' => $tenderActions, 'bids' => $bidActions], 'rights' => $rights];
    }


    

    

    private function whichProvidersAreAvailableDayOfWeek($providers, $needed)
    {

        $weekdayProviders = array();
        $dayOfTheWeekMask = $this->getMaskOfTodayDate();
        foreach ($providers as $provider) {
            $neededServices = $needed[CRITERION_WEEKDAYS];
            foreach ($provider['ServiceType'] as $serviceOffered) {
                if (isset($neededServices[$serviceOffered['id']])) {
                    $isAvailable = $this->checkIfProviderIsAvailable($provider['Provider']['id'], $serviceOffered['id'], $dayOfTheWeekMask);
                    if ($isAvailable) {
                        unset($neededServices[$serviceOffered['id']]);
                    }
                }
            }

            if (count($neededServices) == 0) {
                $weekdayProviders[] = $provider['Provider']['id'];
            }
        }

        return $weekdayProviders;

    }

    private function whichProvidersAreOnline($providers, $needed)
    {
        $onlineProviders = array();

        foreach ($providers as $provider) {
            $neededServices = $needed[CRITERION_ONLINE];
            foreach ($provider['ServiceType'] as $serviceOffered) {
                if (isset($neededServices[$serviceOffered['id']])) {
                    unset($neededServices[$serviceOffered['id']]);
                }
            }
            if (count($neededServices) == 0) {
                $onlineProviders[] = $provider['Provider']['id'];
            }
        }

        return $onlineProviders;
    }

    private function whichProvidersAreQualified($providers, $needed)
    {
        $qualifiedProviders = array();

        foreach ($providers as $provider) {
            $neededServices = $needed[CRITERION_QUALIFIED];
            foreach ($provider['ServiceType'] as $serviceOffered) {
                if (isset($neededServices[$serviceOffered['id']])) {
                    unset($neededServices[$serviceOffered['id']]);
                }
            }
            if (count($neededServices) == 0) {
                $qualifiedProviders[] = $provider['Provider']['id'];
            }
        }

        return $qualifiedProviders;
    }

    private function whichProvidersMeetSchedule($providers, $needed)
    {
        $scheduledProviders = array();

        foreach ($providers as $key => $provider) {
            $neededServices = $needed[CRITERION_SCHEDULED];
            foreach ($provider['ServiceType'] as $key => $serviceOffered) {

                if (isset($neededServices[$serviceOffered['id']])) {
                    unset($neededServices[$serviceOffered['id']]);
                }
            }
            if (count($neededServices) == 0) {
                $scheduledProviders[] = $provider['Provider']['id'];
            }
        }

        return $scheduledProviders;
    }

}
