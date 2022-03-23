<?php

App::uses('AppModel', 'Model');

class Invoice extends AppModel {

    public $useTable = 'invoices';
    public $actsAs = array('Containable');

    public function parentNode() {
            return null;
    }

    public function beforeSave($options = array()) 
    {
        if (empty($this->data['Invoice']['number'])) {
            $this->data['Invoice']['number'] = $this->maxNumber() + 1;
        }
        return true;
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
        'Consumer' => array(
            'className' => 'Consumer',
            'foreignKey' => 'consumer_id',
        ),
        'Demand' => array(
                'className' => 'Demand',
                'foreignKey' => 'demand_id',
        ),
        'Provider' => array(
                'className' => 'Provider',
                'foreignKey' => 'provider_id',
        ),
        'Marketplace' => array(
            'className' => 'Marketplace',
            'foreignKey' => 'marketplace_id',
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

    public $hasAndBelongsToMany = array();

    public $hasMany = array();

    public $hasOne = array();
    
    public function createInvoiceIfNeeded($tenderId, $type)
    {
        $return = null;
        $invoice = $this->find('first', ['conditions' => ['Invoice.tender_id' => $tenderId, 'Invoice.type' => $type]]);
        if(!empty($invoice)) {
            return $invoice;
        } else {
            $this->Tender->id = $tenderId;
            $demandId = $this->Tender->field('demand_id');
            $tenderInfo = $this->Demand->getTenderInfo($demandId);
            $status = $tenderInfo['Demand']['status'];
            $needInvoice = false;
            switch($status) {
                case EJQ_DEMAND_STATUS_BID_SELECTED:
                case EJQ_DEMAND_STATUS_REQUEST_OPEN:
                case EJQ_DEMAND_STATUS_ESTIMATION_ASSIGNED:
                case EJQ_DEMAND_STATUS_ESTIMATION_DISPATCHED:
                case EJQ_DEMAND_STATUS_ESTIMATION_READY_FOR_DISPATCH:
                case EJQ_DEMAND_STATUS_TENDER_OPEN_FOR_BID_SELECTION:
                case EJQ_DEMAND_STATUS_TENDER_CLOSED_TO_BIDS:
                case EJQ_DEMAND_STATUS_TENDER_IN_PROGRESS:
                case EJQ_DEMAND_STATUS_TENDER_TO_BE_MODIFIED:
                case EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS:
                case EJQ_DEMAND_STATUS_TENDER_READY_FOR_HOME_OWNER_APPROVAL:
                case EJQ_DEMAND_STATUS_TENDER_READY_FOR_SITE_ADMIN_APPROVAL:
                case EJQ_DEMAND_STATUS_TENDER_READY_TO_BIDS:
                case EJQ_DEMAND_STATUS_WAITING_FOR_ESTIMATOR_SCHEDULE_APPROVAL:
                    if($type == EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT) {
                        $needInvoice = true;
                    }
                    break;

                case EJQ_DEMAND_STATUS_BID_DISCLOSED:
                case EJQ_DEMAND_STATUS_CONTRACT_SIGNED:
                case EJQ_DEMAND_STATUS_CLOSED:
                case EJQ_DEMAND_STATUS_JOB_COMPLETED:
                case EJQ_DEMAND_STATUS_JOB_IN_PROGRESS:
                    if(in_array($type, [EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT, EJQ_INVOICE_TYPE_COMMISSION])) {
                        $needInvoice = true;
                    }
                    break;

                default:
                   break;
            }
            if ($needInvoice) {
                $newInvoiceData = [];
                $newInvoiceData['marketplace_id'] = $this->EjqMarketplaceId;

                $newInvoiceData['type'] = $type;
                $tenderInfo['Invoice'] = $newInvoiceData;
                $invoiceInfo = $this->initNewInvoiceData($tenderInfo);

                if (!empty($invoiceInfo)) {
                    $this->Marketplace->Invoice->create();
                    $newId = $this->Marketplace->Invoice->id;
                    //$invoiceInfo['Invoice']['id'] = $newId;
                    $data = ['Invoice' => $invoiceInfo];
                    
                    $return = $this->Marketplace->Invoice->save($data);
                }

            }
        }
        return $return;
        
    }
    
    
    
    public function getFromMarketplace($marketplaceId, $filter=null)
    {
        if(empty($marketplaceId)) {
            return false;
        }
        
        $findOptions = [
                    'fields' => array(
                        'id',
                        'invoice_to',
                        'status',
                        'type',
                        'demand_id',
                        'due_date',
                        ),
                    'conditions' => array(
                        'status' => array(
                            EJQ_INVOICE_STATUS_DRAFT,
                            EJQ_INVOICE_STATUS_SENT,
                            EJQ_INVOICE_STATUS_PAID,
                            ),
                        'Invoice.marketplace_id' => $marketplaceId,
                        ),
                    'contain' => array(
                        'Tender.id',
                        'Tender.title',
                        ),
        ];

        if ($filter == 'overdue') {
            $findOptions['conditions'] = [
                'status' => array(
                            EJQ_INVOICE_STATUS_SENT,
                            ),
                'due_date < CURDATE()',
                ];
            
        }
        
        $invoices = $this->find('all', $findOptions);
        
        return $invoices;

    }

    public function initNewInvoiceData($requestData)
    {
        if (empty($requestData['Invoice']['type'])) {
            $type = EJQ_INVOICE_TYPE_OTHER;
        } else {
            $type = $requestData['Invoice']['type'];
        }
        
        $toText = $forText = "";
        $totalValue = $serviceValue = $taxValue = 0;
        switch($requestData['Invoice']['type']) {
            
            case EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT:
                $forText = $serviceDescription = __('Tender development');
                $serviceValue = EJQ_VISIT_PRICE;
                $taxValue = EJQ_VISIT_PRICE * EJQ_GST_TAX;
                $totalValue = $serviceValue + $taxValue;
               
                $this->Demand->id = $requestData['Demand']['id'];
                $consumerId = $this->Demand->field('consumer_id');
                $this->Demand->Consumer->id = $consumerId;
                $providerId = null;
                $consumerName = $this->Demand->Consumer->field('name');
                $consumerAddress = nl2br($this->Demand->Consumer->field('address'));
                $toText = "$consumerName
$consumerAddress";
               
               break;
            
           case EJQ_INVOICE_TYPE_COMMISSION:
               $forText = $serviceDescription = __('Commission charge');
                $this->Demand->id = $requestData['Demand']['id'];
                $chosenBid = $this->Demand->chosenBid($requestData['Demand']['id']);
                $serviceValue = $chosenBid['Bid']['value'] * EJQ_CONTRACTOR_COMMISSION;
                $taxValue = $serviceValue * EJQ_GST_TAX;
                $totalValue = $serviceValue + $taxValue;
                $providerId = $chosenBid['Provider']['id'];
                $contractorName = $chosenBid['Contractor']['name'];
                $consumerId = null;
                $contractorAddress = nl2br($chosenBid['Contractor']['contact_address']);
                $toText = "$contractorName
$contractorAddress";
               break;
            
           default:
               $forText = $serviceDescription = __('Services');
               break;
            
        }
        
        $data = [
            'id' => null,
            'number' => null,
            'issue_date' => null,
            'due_date' => null,
            'marketplace_id' => $requestData['Demand']['marketplace_id'],
            'tender_id' => $requestData['Tender']['id'],
            'demand_id' => $requestData['Demand']['id'],
            'provider_id' => $providerId,
            'consumer_id' => $consumerId,
            'type' => $requestData['Invoice']['type'],
            'invoice_to' => $toText,
            'invoice_for' => $forText,
            'service_description' => $serviceDescription,
            'service_value' => $serviceValue,
            'tax_value' => $taxValue,
            'total_value' => $totalValue,
            'status' => EJQ_INVOICE_STATUS_DRAFT,
            'info' => 'Please make all cheques payable to Job Confidence Inc. Payment can also be made via email to
<a href="mailto:lisa@easyjobquote.com">lisa@easyjobquote.com</a>
Payment is due within 30 days.
If you have any questions concerning this invoice, contact Lisa Tinney | 250 590-8182 |
<a href="mailto:lisa@easyjobquote.com">lisa@easyjobquote.com</a>
            ',
        ];
        
            if(!empty($requestData['Consumer']['id'])) {
                $data['consumer_id'] = $requestData['Consumer']['id'];
            }
            
            
        return $data;
    }
    
    public function invoiceInfo($id)
    {
        $data = $this->find('first',
                [
                    'conditions' => array(
                        'Invoice.id' => $id,
                        ),
                     'contain' => array(),
                    ]);
        
        $demandId = $data['Invoice']['demand_id'];
        $tenderId = $data['Invoice']['tender_id'];
        $consumerId = $data['Invoice']['consumer_id'];

        $this->Tender->id = $tenderId;
        $data['Invoice']['tender_title'] = $this->Tender->field('title');
        if ($data['Invoice']['type'] == EJQ_INVOICE_TYPE_COMMISSION) {
            $chosenBidId = $this->Tender->field('chosen_bid_id');
            $this->Tender->Bid->id = $chosenBidId;
            $data['Invoice']['bid_amount'] = $this->Tender->Bid->field('value'); 
            $this->Consumer->id = $consumerId;
            $data['Invoice']['home_owner_address'] = $this->Consumer->field('address'); 
            
            
            
        }
        
        

        return $data;
    }
    
    
    
    public function tenderDevelopmentInvoiceValues()
    {
        $serviceValue = EJQ_VISIT_PRICE;
        $taxValue = EJQ_VISIT_PRICE * EJQ_GST_TAX;
        $totalValue = $serviceValue + $taxValue;
        
        return ['service_value' => $serviceValue,
            'tax_value' => $taxValue,
            'total_value' => $totalValue,
            ];        
        
    }

    private function maxNumber()
    {
        $result = $this->find('first',
	        array(
		    'fields' => array('MAX(number) AS max_number')
	        )
        );
        
        if(!empty($result)) {
            $value = $result['0']['max_number'];
        } else {
            $value = 0;
        }
        
        return $value;

    }
    
    
}
