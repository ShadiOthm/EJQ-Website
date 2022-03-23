<?php

App::uses('AppModel', 'Model');

class Marketplace extends AppModel {

        public $actsAs = array('Containable');

	public $useTable = 'marketplaces';
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
		'description' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Favor informar um texto descrevendo o Marketplace',
				),
			)
		);

    public $hasAndBelongsToMany = array(
           'ServiceType' =>
               array(
                   'className' => 'ServiceType',
                   'joinTable' => 'marketplaces_service_types',
                   'foreignKey' => 'marketplace_id',
                   'associationForeignKey' => 'service_type_id',
                   'unique' => true,
               ),
        'UserAsConsumer' =>
            array(
                'className' => 'User',
                'joinTable' => 'consumers',
                'foreignKey' => 'marketplace_id',
                'associationForeignKey' => 'user_id',
                'unique' => 'keepExisting',
            )
       );

    public $hasMany = array(
            'Administrator' => array(
                    'className' => 'Administrator',
                    'foreignKey' => 'marketplace_id',
                    'conditions' => array('active' => '1', 'removed' => '0'),
            ),
            'Consumer' => array(
                    'className' => 'Consumer',
                    'foreignKey' => 'marketplace_id',
                    'conditions' => array('active' => '1', 'removed' => '0'),
            ),
            'Demand' => array(
                    'className' => 'Demand',
                    'foreignKey' => 'marketplace_id',
                    'conditions' => array('active' => '1', 'removed' => '0'),
            ),
            'Invoice' => array(
                    'className' => 'Invoice',
                    'foreignKey' => 'marketplace_id',
                    'conditions' => array('active' => '1', 'removed' => '0'),
            ),
            'Job' => array(
                    'className' => 'Job',
                    'foreignKey' => 'marketplace_id',
                    'conditions' => array('active' => '1', 'removed' => '0'),
            ),

            'Provider' => array(
                    'className' => 'Provider',
                    'foreignKey' => 'marketplace_id',
                    'conditions' => array('active' => '1', 'removed' => '0'),
            ),
            'Schedule' =>
                array(
                    'className' => 'DemandSchedule',
                    'foreignKey' => 'marketplace_id',
                    'conditions' => array('active' => '1', 'removed' => '0'),
                ),
            'Tender' => array(
                    'className' => 'Tender',
                    'foreignKey' => 'marketplace_id',
                    'conditions' => array('active' => '1', 'removed' => '0'),
            ),

    );

    public $belongsTo = array(
            'Curator' => array(
                    'className' => 'Curator',
                    'foreignKey' => 'curator_id',
            ),
            'MetaMarketplace' => array(
                    'className' => 'MetaMarketplace',
                    'foreignKey' => 'meta_marketplace_id',
            ),
    );



    public function beforeSave($options = array()) {

        if (isset($this->data['Marketplace']['name'])) {
            $id = NULL;
            if (isset($this->data['Marketplace']['id'])) {
                $id = $this->data['Marketplace']['id'];
            }
            $this->data['Marketplace']['slug'] = $this->createSlug($this->data['Marketplace']['name'], $id);
            return true;
        }

        parent::beforeSave($options);
    }


    public function getMetaConsumersByMarketplaceId($marketplaceId = null) {


        if(empty($marketplaceId)) return false;

        $marketplace = $this->find('first',
                array(
                    'fields' => array('meta_marketplace_id', 'name'),
                    'contain' => array('MetaMarketplace'),
                    'conditions' => array('Marketplace.id' => $marketplaceId)
                    ));

        $metaConsumer = $this->MetaMarketplace->find('first',
                array(
                    'fields' => array('id', 'name'),
                    'contain' => array('MetaConsumer.id', 'MetaConsumer.name'),
                    'conditions' => array('MetaMarketplace.id' => $marketplace['MetaMarketplace']['id'])
                    ));
        return $metaConsumer;
    }

    public function getHeaderInfo($marketplaceId = null) {


        if(empty($marketplaceId)) return false;

        $marketplaces = $this->find('first',
                array(
                    'fields' => array(
                        'id',
                        'name',
                        'logo_image'
                        ),
                    'contain' => array(),
                    'conditions' => array('Marketplace.id' => $marketplaceId)
                    ));

        return $marketplaces;
    }

    public function getMarketplacesListForUser($userId = null) {

        // find all
        $allMarketplaces = $this->find('all',
                array(
                    'fields' => array(
                        'id',
                        'name',
                        'logo_image'
                        ),
                    'contain' => array(),
                    'conditions' => array('active' => '1')
                    ));

        $consumerMarketplaces = null;
        $providerMarketplaces = null;
        if (!empty($userId)) {
            // check if is consumer
            $consumerMarketplaces = $this->find('all',
                    array(
                        'joins' => array(
                                array(
                                    'table' => 'consumers',
                                    'alias' => 'Consumer',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'Marketplace.id = Consumer.marketplace_id',
                                    )
                                )
                            ),
                        'fields' => array('Marketplace.id','Consumer.id'),
                        'contain' => array(),
                        'conditions' => array(
                            'Marketplace.active' => '1',
                            'Consumer.user_id' => $userId,
                            )
                        ));

            $indexed = array();
            foreach ($consumerMarketplaces as $marketplace) {
                $marketplaceId = $marketplace['Marketplace']['id'];
                $indexed[$marketplaceId]['Consumer'] = $marketplace['Consumer'];

            }
            $indexedConsumers = $indexed;



            // check if is provider
            $providerMarketplaces = $this->find('all',
                    array(
                        'joins' => array(
                                array(
                                    'table' => 'providers',
                                    'alias' => 'Provider',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'Marketplace.id = Provider.marketplace_id',
                                    )
                                )
                            ),
                        'fields' => array('Marketplace.id','Provider.id'),
                        'contain' => array(),
                        'conditions' => array(
                            'Marketplace.active' => '1',
                            'Provider.user_id' => $userId,
                            )
                        ));
            $indexed = array();
            foreach ($providerMarketplaces as $marketplace) {
                $marketplaceId = $marketplace['Marketplace']['id'];
                $indexed[$marketplaceId]['Provider'] = $marketplace['Provider'];

            }
            $indexedProviders = $indexed;

        }

        $joinedData = $allMarketplaces;
        foreach ($allMarketplaces as $key => $marketplace) {
                $marketplaceId = $marketplace['Marketplace']['id'];
                if (isset($indexedConsumers[$marketplaceId])) {
                    $joinedData[$key]['Consumer'] = $indexedConsumers[$marketplaceId]['Consumer'];
                }
                if (isset($indexedProviders[$marketplaceId])) {
                    $joinedData[$key]['Provider'] = $indexedProviders[$marketplaceId]['Provider'];
                }


        }

        return $joinedData;
    }

    public function getMetaProvidersByMarketplaceId($marketplaceId = null) {


        if(empty($marketplaceId)) return false;

        $metaMarketplaces = $this->find('first',
                array(
                    'fields' => array('meta_marketplace_id', 'name'),
                    'contain' => array('MetaMarketplace'),
                    'conditions' => array('Marketplace.id' => $marketplaceId)
                    ));

        $metaProviders = $this->MetaMarketplace->find('first',
                array(
                    'fields' => array('id', 'name'),
                    'contain' => array('MetaProvider.id', 'MetaProvider.name'),
                    'conditions' => array('MetaMarketplace.id' => $metaMarketplaces['MetaMarketplace']['id'])
                    ));
        return $metaProviders;
    }

    public function getServiceTypesByMarketplaceId($marketplaceId = null) {


        if(empty($marketplaceId)) return false;

        $serviceTypes = $this->find('all',
                array(
                    'conditions' => array('id' => $marketplaceId),
                    'fields' => array('id'),
                    'contain' => array('ServiceType.id',
                        'ServiceType.name',
                        'ServiceType.qualified_criterion',
                        'ServiceType.online_criterion',
                        'ServiceType.scheduled_criterion',
                        'ServiceType.weekdays_criterion',
                        )));

        return $serviceTypes;
    }

    public function listServiceTypes($id)
    {
        $marketplaceData = $this->find(
                'all',
                array(
                    'contain' => array('ServiceType.id', 'ServiceType.name'),
                    'conditions' => array(
                        'id' => $id,
                        ),
                    )
                );
        $result = array();
        foreach ($marketplaceData['0']['ServiceType'] as $key => $serviceType) {
            $result[$serviceType['id']] = $serviceType['name'];
        }
        return $result;
        
    }
    
    public function userRole($id, $userId) {

        $result = array();
        $consumer = $this->Consumer->findByUserIdAndMarketplaceId($userId, $id);
        if (isset($consumer['Consumer'])) {
            $result['Consumer'] = $consumer['Consumer'];
        }
        $provider = $this->Provider->findByUserIdAndMarketplaceId($userId, $id);
        if (isset($provider['Provider'])) {
            $result['Provider'] = $provider['Provider'];
        }
        $administrator = $this->Administrator->findByUserIdAndMarketplaceId($userId, $id);
        if (isset($administrator['Administrator'])) {
            $result['Administrator'] = $administrator['Administrator'];
        }
//        debug($id);
//        debug($userId);
//        debug($consumer);
//        debug($provider);
//        debug($administrator);
        
        return($result);

    }


    public function totalConsumers($id)
    {
        if(empty($id)) return false;

        $options = array(
                    'contain' => array(),
                    'conditions' => array('marketplace_id' => $id, 'active' => '1')
                    );

        $totalConsumers = $this->Consumer->find('count', $options);
        return $totalConsumers;
    }

    public function totalDemands($id, $includingCanceled=false)
    {
        if(empty($id)) return false;

        $options = array(
                    //'fields' => array('id', 'name'),
                    'contain' => array(),
                    'conditions' => array('marketplace_id' => $id)
                    );

        if (!$includingCanceled) {
            $options['conditions']['status <>'] = DEMAND_STATUS_CANCELED;
            $options['conditions']['not'] = array('status' => null);
        } else {
            $options['conditions']['not'] = array('status' => null);
        }


        $totalDemands = $this->Demand->find('count', $options);
        return $totalDemands;
    }

    public function totalProviders($id)
    {
        if(empty($id)) return false;

        $options = array(
                    'contain' => array(),
                    'conditions' => array('marketplace_id' => $id, 'active' => '1')
                    );

        $totalProviders = $this->Provider->find('count', $options);
        return $totalProviders;
    }


}
