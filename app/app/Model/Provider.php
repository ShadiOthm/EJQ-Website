<?php

App::uses('AppModel', 'Model');

class Provider extends AppModel {

    public $useTable = 'providers';
    public $displayField = 'name';
    public $actsAs = ['Containable'];

    public function parentNode() {
            return null;
    }

    public $validate = [
            'active' => [
                    'numeric' => [
                            'rule' => ['numeric'],
                            ],
                    ],
            'removed' => [
                    'numeric' => [
                            'rule' => ['numeric'],
                            ],
                    ],
            'name' => [
                    'notBlank' => [
                            'rule' => ['notBlank'],
                            'message' => 'Please inform a name',
                            ],
                    ],
            ];

    public $virtualFields = [
    ];        

    public $belongsTo = [
        'Marketplace' => [
                'className' => 'Marketplace',
                'foreignKey' => 'marketplace_id',
        ],
        'MetaProvider' => [
                'className' => 'MetaProvider',
                'foreignKey' => 'meta_provider_id',
        ],
        'User' => [
                'className' => 'User',
                'foreignKey' => 'user_id',
        ],
        'PaymentMethod' => [
            'className' => 'PaymentMethod',
            'foreignKey' => 'payment_method_id',
        ],
    ];
    
    public $hasAndBelongsToMany = [
        'ServiceType' =>
            [
                'className' => 'ServiceType',
                'joinTable' => 'providers_service_types',
                'foreignKey' => 'provider_id',
                'associationForeignKey' => 'service_type_id',
                'unique' => true,
            ]
    ];    
    
    public $hasMany = [
        'Bid' =>
            [
                'className' => 'Bid',
                'foreignKey' => 'provider_id',
                'conditions' => ['Bid.active' => '1', 'Bid.removed' => '0'],
            ],
        'Compliance' =>
            [
                'className' => 'Compliance',
                'foreignKey' => 'provider_id',
                'conditions' => ['Compliance.active' => '1', 'Compliance.removed' => '0'],
            ],
        'Contractor' =>
            [
                'className' => 'Contractor',
                'foreignKey' => 'provider_id',
                'conditions' => ['active' => '1', 'removed' => '0'],
            ],
        'Demand' =>
            [
                'className' => 'Demand',
                'foreignKey' => 'provider_id',
                'conditions' => ['active' => '1', 'removed' => '0'],
            ],
        'Evaluation' =>
            [
                'className' => 'ProviderEvaluation',
                'foreignKey' => 'provider_id',
            ],
        'Job' =>
            [
                'className' => 'Job',
                'foreignKey' => 'provider_id',
                'conditions' => ['Job.active' => '1', 'Job.removed' => '0'],
            ],
        'TermCondition' =>
            [
                'className' => 'TermCondition',
                'foreignKey' => 'provider_id',
                'conditions' => ['TermCondition.active' => '1', 'TermCondition.removed' => '0'],
            ],
        'Weekdays' =>
            [
                'className' => 'ProviderWeekdays',
                'foreignKey' => 'provider_id',
                'conditions' => ['active' => '1', 'removed' => '0'],
            ],
        'Review' =>
            [
                'className' => 'Review',
                'foreignKey' => 'provider_id',
                'conditions' => ['active' => '1', 'removed' => '0'],
            ],
        'Invoice' =>
            [
                'className' => 'Invoice',
                'foreignKey' => 'provider_id',
                'conditions' => ['Invoice.active' => '1', 'Invoice.removed' => '0'],
            ],
    ];    
    
    public $hasOne = [
    ];

    public function beforeSave($options = []) {

        if (isset($this->data['Provider']['name'])) {
            $id = NULL;
            if (isset($this->data['Provider']['id'])) {
                $id = $this->data['Provider']['id'];
            }
            $this->data['Provider']['slug'] = $this->createSlug($this->data['Provider']['name'], $id);
            return true;
        }            

        parent::beforeSave($options);
    }
    
    public function getCurrentJobs($id)
    {
        if(empty($id)) {
            return false;
        }
        $demands=[];

        $bids = $this->Bid->find('list',
                [
                    'conditions' => [
                        'status' => [
                            EJQ_BID_STATUS_CHOSEN,
                            ],
                        'provider_id' => $id,
                        ],
                    ]);
        foreach ($bids as $bidId) {
            $this->Bid->id = $bidId;
            $bidValue = $this->Bid->field('value');
            $tenderInfo = $this->Demand->Tender->getTenderInfo($this->Bid->field('tender_id'));
            if ($tenderInfo['Demand']['status'] == EJQ_DEMAND_STATUS_JOB_IN_PROGRESS ||
                $tenderInfo['Demand']['status'] == EJQ_DEMAND_STATUS_BID_DISCLOSED ||
                $tenderInfo['Demand']['status'] == EJQ_DEMAND_STATUS_CONTRACT_SIGNED
                ) {
                $tenderInfo['Bid']['value'] = $bidValue;
                $demands[] = $tenderInfo;
            }
        }
        return $demands;

    }


    
    public function getProviderByMarketplaceAndUserId($marketplaceId, $userId) {
        
        if(empty($marketplaceId)) return false;
        if(empty($userId)) return false;
        
        $provider = $this->find('first', 
                [
                    'fields' => ['id'],
                    'conditions' => ['Provider.marketplace_id' => $marketplaceId, 'user_id' => $userId],
                    'contain' => [
                        'ServiceType' => [
                            'id',
                            'name',
                            ],  
                        ],
                    ]);
        
        if (isset($provider['Provider'])) {
            return $provider;
        } else {
            return false;
        }
    }

    public function canBeMatched($id)
    {
        // consultWhatAttributeAreRequired
        $attributes = $this->getRequiredAttributes($id);

        // checkIfAttributeIsFulfilled
        if (isset($attributes['weekdays']) && $attributes['weekdays']) {
            $weekdays = $this->getWeekdays($id);
        }
        
        if (!is_null($weekdays)) {
            return true;
        }
        return false;
    }
    
    public function getRating($id)
    {
        
        $rating = $this->Review->find('first', [
                    'fields' => [
                        'AVG(overall_rating) as average_overall_rating',
                        'AVG(punctuality_rating) as average_punctuality_rating',
                        'AVG(behaviour_rating) as average_behaviour_rating',
                        'AVG(cleanliness_rating) as average_cleanliness_rating',
                        'AVG(quality_of_work_rating) as average_quality_of_work_rating',
                        'AVG(likelihood_to_recommend_rating) as average_likelihood_to_recommend_rating',
                    ],
                    'conditions' => ['Review.provider_id' => $id],
                ]);
        
        return $rating;
        
        
        
    }
    
    public function getRequiredAttributes($id)
    {
        $attributes = [];
        $required = $this->needToInformWeekdays($id);
        if (is_array($required) && count($required)) {
            $attributes['weekdays'] = true;
        } else {
            $attributes['weekdays'] = falsee;
        }
        return ($attributes);
        
    }
    
    
    public function getTotalBids($id)
    {
        
        $result = $this->Bid->find('first', [
                    'fields' => [
                        'COUNT(Bid.id) as total_bids',
                    ],
                    'conditions' => [
                        'Bid.provider_id' => $id,
                        'Bid.status' => ['SUBMITTED', 'CHOSEN'],
                    ],
                ]);
        
        return $result;
        
        
        
    }
    
    public function getTotalJobs($id)
    {
        
        $result = $this->Job->find('first', [
                    'fields' => [
                        'COUNT(Job.id) as total_jobs',
                    ],
                    'conditions' => [
                        'Job.provider_id' => $id,
                        'Job.status' => ['COMPLETED'],
                    ],
                ]);
        
        return $result;
        
        
        
    }
    
    public function getInvoices($id, $status=null)
    {
        if(empty($id)) {
            return false;
        }
        
        $options = [
                'conditions' => [
                    'Invoice.provider_id' => $id,
                    ],
                ];
        
        if (!empty($status)) {
            $options['conditions']['Invoice.status'] = $status;
        }
        
       
        $result = $this->Invoice->find('all', $options);
        
        return $result;

    }


    public function getOverdueInvoicesList($id)
    {
        if(empty($id)) {
            return false;
        }
        
        $invoices = $this->getInvoices($id, EJQ_INVOICE_STATUS_SENT);
        
        $unpaid = [];
        foreach ($invoices as $invoice) {
            $this->Invoice->id = $invoice['Invoice']['id'];
            $now = time();
            $dueDate = strtotime($this->Invoice->field('due_date'));
            $dateDiff = $now - $dueDate;
            $daysDiff =  floor($dateDiff / (60 * 60 * 24));
            if ($daysDiff > 0) {
                $unpaid[$invoice['Invoice']['id']] = $invoice;
            }

        }
        return $unpaid;

    }



    public function getWeekdays($id, $serviceTypeId)
    {
        if(empty($id)) {
            return false;
        }
        
        $data = $this->Weekdays->find('first', 
                [
                    'fields' => ['weekdays'],
                    'conditions' => ['Weekdays.provider_id' => $id, 'Weekdays.service_type_id' => $serviceTypeId],
                    'contain' => [],
                    ]);
        $weekdays = null;
        if (isset($data['Weekdays']['weekdays'])) {
            $weekdays = $data['Weekdays']['weekdays'];
        }
        
        
        return $weekdays;
        
    }

    public function getWeekdaysId($id, $serviceTypeId)
    {
        if(empty($id)) return false;
        
        $attributes = [];
        $data = $this->Weekdays->find('first', 
                [
                    'fields' => ['id'],
                    'conditions' => ['Weekdays.provider_id' => $id, 'Weekdays.service_type_id' => $serviceTypeId],
                    'contain' => [],
                    ]);
        $weekdaysId = null;
        if (isset($data['Weekdays']['id'])) {
            $weekdaysId = $data['Weekdays']['id'];
        }
        
        
        return $weekdaysId;
        
    }
    
    public function listServiceTypes($id)
    {
        $providerData = $this->find(
                'all',
                [
                    'contain' => ['ServiceType.id', 'ServiceType.name'],
                    'conditions' => [
                        'id' => $id,
                        ],
                    ]
                );
        $result = [];
        foreach ($providerData['0']['ServiceType'] as $key => $serviceType) {
            $result[$serviceType['id']] = $serviceType['name'];
        }
        return $result;
        
    }
    
    public function needToInformWeekdays($id)
    {
        if(empty($id)) {
            return false;
        }
        
        $need = false;
        $data = $this->find('first', 
                [
                    'fields' => ['Provider.id'],
                    'conditions' => ['Provider.id' => $id],
                    'contain' => ['ServiceType.id', 'ServiceType.weekdays_criterion'],
                    ]);
        
        foreach ($data['ServiceType'] as $serviceType) {
            $need = $this->checkIfHasToInformWeekdays($serviceType['weekdays_criterion']);
        }

        return $need;
        
    }
    
    public function saveEvaluation($id, $serviceTypeId, $evaluationValue)
    {
        
        $providerService= $this->find('first', 
                    [
                        'joins' => [
                                [
                                    'table' => 'providers_service_types',
                                    'alias' => 'Service',
                                    'type' => 'INNER',
                                    'conditions' => [
                                        'Provider.id = Service.provider_id',
                                    ]
                                ]
                            ],        
                        'fields' => [
                            'Provider.id',
                            'Service.id',
                            'Service.evaluations_average',
                            'Service.evaluations_count',
                            ],
                        'contain' => [],
                        'conditions' => [
                            'Service.service_type_id' => $serviceTypeId, 
                            'Service.provider_id' => $id,
            ]
        ]);
        
        if (!empty($providerService)) {
            $service = $providerService['Service'];
            if (is_null($service['evaluations_count'])) {
                $count = 0;
            } else {
                $count = $service['evaluations_count'];
            }
            if (is_null($service['evaluations_average'])) {
                $average = 0;
            } else {
                $average = $service['evaluations_average'];
            }
            
            $totalCalculated = $count * $average;
            $newCount = $count + 1;
            $newTotal = $totalCalculated + $evaluationValue;
            $newAverage = $newTotal / $newCount;
            
            $this->Evaluation->id = $service['id'];
            $data = ['Evaluation' => [
                    'evaluations_count' => $newCount,
                    'evaluations_average' => $newAverage,
                ]];

            $this->Evaluation->save($data, true, ['evaluations_count', 'evaluations_average']);
        }
    }

    public function saveWeekdays($id, $serviceTypeId, $weekDays)
    {
        $this->id = $id;

        $data = [
            'Provider' => ['id' => $id],
            'Weekdays' => ['0' => [
                'id' => $this->getWeekdaysId($id, $serviceTypeId),
                'provider_id' => $id,
                'service_type_id' => $serviceTypeId,
                'marketplace_id' => $this->field('marketplace_id'),
                'weekdays' => $weekDays,
                ]
            ]];
        $this->Provider->ProviderWeekdays->saveAssociated($data);

    }
    
    private function checkIfHasToInformWeekdays($id, $weekdaysCriterion)
    {
        $need = false;
        if ($weekdaysCriterion) {
            $has = $this->getWeekdays($id, $serviceType['id']);
            if (is_null($has)) {
                $need = true;
            }
        }        
        return $need;
    }
    
    


    
    
}
