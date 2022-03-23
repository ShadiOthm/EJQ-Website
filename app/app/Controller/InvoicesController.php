<?php

App::uses('AppController', 'Controller');

class InvoicesController extends AppController {

    public function beforeFilter() {
        $this->Auth->allow([
            'contractor_details',
            'details',
            'inform_payment',
            'send_receipt_to_contractor',
            'send_receipt_to_home_owner',
            'send_reminder_to_contractor',
            'send_reminder_to_home_owner',
            'send_to_contractor',
            'send_to_home_owner',
            'update_due_date',
            'update_issue_date',
            'update_for',
            'update_receipt_date',
            'update_to',
            ]);

        parent::beforeFilter();
    }
    
    public function contractor_details($id=null)
    {
        $checkedInvoiceId = $this->verifyIdAndRightsToSeeInvoice($id);
        
        //$this->layout = 'ajax';
        $this->setActiveTab("billing");
                        
        if(empty($checkedInvoiceId)) {
            echo __("No invoice id, nothing to show.");
            exit;
        } else {
            $this->Invoice->id = $checkedInvoiceId;
            $demandId = $this->Invoice->field('demand_id');
            $tenderInfo = $this->Invoice->Demand->getTenderInfo($demandId);
            $invoiceInfo = $this->Invoice->invoiceInfo($checkedInvoiceId);
            $tenderInfo['Invoice'] = $invoiceInfo['Invoice'];
        }
        
        if(!empty($tenderInfo['Invoice']['issue_date'])) {
            $issueDate = $tenderInfo['Invoice']['issue_date'];
            $objIssueDate = DateTime::createFromFormat('Y-m-d', $issueDate);
            $formatedIssueDate = date('m/d/Y', $objIssueDate->getTimeStamp());
            $tenderInfo['Invoice']['issue_date'] = $formatedIssueDate;
        }

        if(!empty($tenderInfo['Invoice']['due_date'])) {
            $dueDate = $tenderInfo['Invoice']['due_date'];
            $objDueDate = DateTime::createFromFormat('Y-m-d', $dueDate);
            $formatedDueDate = date('m/d/Y', $objDueDate->getTimeStamp());
            $tenderInfo['Invoice']['due_date'] = $formatedDueDate;
        }

        if(!empty($tenderInfo['Invoice']['receipt_date'])) {
            $receiptDate = $tenderInfo['Invoice']['receipt_date'];
            $objReceiptDate = DateTime::createFromFormat('Y-m-d', $receiptDate);
            $formatedReceiptDate = date('m/d/Y', $objReceiptDate->getTimeStamp());
            $tenderInfo['Invoice']['receipt_date'] = $formatedReceiptDate;
        }
        
        
        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);

        $this->set('tenderInfo', $tenderInfo);
        $this->request->data = $tenderInfo;
        
        if ($tenderInfo['Invoice']['type'] == EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT) {
            $typeLabel = __('Tender Development');
        } elseif ($tenderInfo['Invoice']['type'] == EJQ_INVOICE_TYPE_COMMISSION) {
            $typeLabel = __('Commission');
        } else {
            $typeLabel = __('Services');
        }
        
        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = sprintf(__('Invoice for %s'), $typeLabel);
        $this->set('titleBox', $titleBox);
        $this->set('hereTitle', $tenderInfo['Tender']['title']);
        $this->set('tenderBreadcrumbNode', [
            'controller' => 'tenders',
            'action' => 'details',
            'id' => $tenderInfo['Tender']['id'],
            'label' => $tenderInfo['Tender']['title'],
        ]);
        $this->set('breadcrumbNode', sprintf(__('Invoice for %s'), $typeLabel));

        
        $this->render('details');
        
        
            
    }

    public function details($id=null)
    {
        $checkedInvoiceId = $this->verifyIdAndAdminRights($id);
        
        //$this->layout = 'ajax';
        $this->setActiveTab("billing");
        
        if (!$this->canAccessAdm) {
            echo__("Sorry, just site admins can see this info.");
            exit;
        }
                
        if(empty($checkedInvoiceId)) {
            echo __("No invoice id, nothing to show.");
            exit;
        } else {
            $this->Invoice->id = $checkedInvoiceId;
            $demandId = $this->Invoice->field('demand_id');
            $tenderInfo = $this->Invoice->Demand->getTenderInfo($demandId);
            $invoiceInfo = $this->Invoice->invoiceInfo($checkedInvoiceId);
            $tenderInfo['Invoice'] = $invoiceInfo['Invoice'];
        }
        
        if(!empty($tenderInfo['Invoice']['issue_date'])) {
            $issueDate = $tenderInfo['Invoice']['issue_date'];
            $objIssueDate = DateTime::createFromFormat('Y-m-d', $issueDate);
            $formatedIssueDate = date('m/d/Y', $objIssueDate->getTimeStamp());
            $tenderInfo['Invoice']['issue_date'] = $formatedIssueDate;
        }

        if(!empty($tenderInfo['Invoice']['due_date'])) {
            $dueDate = $tenderInfo['Invoice']['due_date'];
            $objDueDate = DateTime::createFromFormat('Y-m-d', $dueDate);
            $formatedDueDate = date('m/d/Y', $objDueDate->getTimeStamp());
            $tenderInfo['Invoice']['due_date'] = $formatedDueDate;
        }

        if(!empty($tenderInfo['Invoice']['receipt_date'])) {
            $receiptDate = $tenderInfo['Invoice']['receipt_date'];
            $objReceiptDate = DateTime::createFromFormat('Y-m-d', $receiptDate);
            $formatedReceiptDate = date('m/d/Y', $objReceiptDate->getTimeStamp());
            $tenderInfo['Invoice']['receipt_date'] = $formatedReceiptDate;
        }
        
        if(!empty($tenderInfo['Schedule']['0']['schedule_period_begin'])) {
            $visitDate = $tenderInfo['Schedule']['0']['schedule_period_begin'];
            $objVisitDate = DateTime::createFromFormat('Y-m-d H:i:s', $visitDate);
            $formatedVisitDate = date('Y, M d', $objVisitDate->getTimeStamp());
            $tenderInfo['Invoice']['visit_date'] = $formatedVisitDate;
        }
        
        
        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);

        $this->set('tenderInfo', $tenderInfo);
        $this->request->data = $tenderInfo;
        
        if ($this->canAccessAdm) {
            $invoiceActions = [];
            $invoiceType = $tenderInfo['Invoice']['type'];
            if(empty($tenderInfo['Invoice']) || $tenderInfo['Invoice']['status'] == EJQ_INVOICE_STATUS_DRAFT) {
                if ($invoiceType == EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT) {
                    $invoiceActions['send_invoice_to_home_owner'] = array(
                        'id' => 'send_invoice_to_home_owner',
                        'href' => '/invoices/send_to_home_owner',
                        'label' => __('Send invoice'),
                    );
                } elseif ($invoiceType == EJQ_INVOICE_TYPE_COMMISSION) {
                    $invoiceActions['send_invoice_to_contractor'] = array(
                        'id' => 'send_invoice_to_contractor',
                        'href' => '/invoices/send_to_contractor',
                        'label' => __('Send invoice'),
                    );
                }

            }
            if(!empty($tenderInfo['Invoice']) && $tenderInfo['Invoice']['status'] == EJQ_INVOICE_STATUS_SENT) {
                $invoiceActions['inform_payment'] = array(
                    'id' => 'inform_payment',
                    'href' => ['controller' => 'invoices', 'action' => 'inform_payment'],
                    'label' => __('Inform Payment'),
                );
                
                $now = time();
                $dueDate = strtotime($tenderInfo['Invoice']['due_date']);
                $dateDiff = $now - $dueDate;
                $daysDiff =  floor($dateDiff / (60 * 60 * 24));
                
                if ($daysDiff > 0) {
                    if ($invoiceType == EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT) {
                        $invoiceActions['send_reminder_to_home_owner'] = array(
                            'id' => 'send_reminder_to_home_owner',
                            'href' => ['controller' => 'invoices', 'action' => 'send_reminder_to_home_owner'],
                            'label' => __('Send Reminder'),
                        );
                    } elseif ($invoiceType == EJQ_INVOICE_TYPE_COMMISSION) {
                        $invoiceActions['send_reminder_to_contractor'] = array(
                            'id' => 'send_reminder_to_contractor',
                            'href' => ['controller' => 'invoices', 'action' => 'send_reminder_to_contractor'],
                            'label' => __('Send Reminder'),
                        );
                    }
                }
            

                
            }
            if(!empty($tenderInfo['Invoice']) && $tenderInfo['Invoice']['status'] == EJQ_INVOICE_STATUS_PAID) {
                if(empty($tenderInfo['Invoice']['receipt_sent_on'])) {
                    if ($invoiceType == EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT) {
                        $invoiceActions['send_receipt'] = array(
                            'id' => 'send_receipt',
                            'href' => ['controller' => 'invoices', 'action' => 'send_receipt_to_home_owner'],
                            'label' => __('Send Receipt'),
                        );
                    } elseif ($invoiceType == EJQ_INVOICE_TYPE_COMMISSION) {
                        $invoiceActions['send_receipt'] = array(
                            'id' => 'send_receipt',
                            'href' => ['controller' => 'invoices', 'action' => 'send_receipt_to_contractor'],
                            'label' => __('Send Receipt'),
                        );
                    }
                }
                
            }
            $this->set('invoiceActions', $invoiceActions);
        }

        if ($tenderInfo['Invoice']['type'] == EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT) {
            $typeLabel = __('Tender Development');
        } elseif ($tenderInfo['Invoice']['type'] == EJQ_INVOICE_TYPE_COMMISSION) {
            $typeLabel = __('Commission');
        } else {
            $typeLabel = __('Services');
        }
        
        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = sprintf(__('Invoice for %s'), $typeLabel);
        $this->set('titleBox', $titleBox);
        $this->set('hereTitle', $tenderInfo['Tender']['title']);
        $this->set('tenderBreadcrumbNode', [
            'controller' => 'tenders',
            'action' => 'details',
            'id' => $tenderInfo['Tender']['id'],
            'label' => $tenderInfo['Tender']['title'],
        ]);
        $this->set('breadcrumbNode', sprintf(__('Invoice for %s'), $typeLabel));

        
        
        
        
            
    }

    
    public function inform_payment($id=null)
    {
        try {
            $checkedId = $this->verifyIdAndAdminRights($id);
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
            $this->redirect(array('controller' => 'main', 'action' => 'index'));
        }
        
        if ($this->request->is(array('post', 'put'))) {
            $this->Invoice->id = $this->request->data['Invoice']['id'];
            $type = $this->Invoice->field('type');
            $cleaningStanding = false;
            if ($type == EJQ_INVOICE_TYPE_COMMISSION) {
                $chosenBid = $this->Invoice->Demand->chosenBid($this->request->data['Demand']['id']);
                $this->Invoice->Provider->id = $chosenBid['Provider']['id'];
                $overdueInvoicesList = $this->Invoice->Provider->getOverdueInvoicesList($chosenBid['Provider']['id']);
                if (!empty($overdueInvoicesList) && count($overdueInvoicesList) == 1) {
                    $cleaningStanding = true;
                }
            }
            
            $dataToBeSaved = $this->request->data;
            
            $dataToBeSaved['Invoice']['status'] = EJQ_INVOICE_STATUS_PAID;
            $dataToBeSaved['Invoice']['receipt_date'] = date('Y-m-d H:i:s');
            if ($this->Invoice->saveAssociated($dataToBeSaved)) {
                if($cleaningStanding) {
                    $this->Invoice->Provider->id = $chosenBid['Provider']['id'];
                    $this->Invoice->Provider->saveField('good_standing', true);
                }
                $this->Flash->success(__('The payment was registered'));
            } else {
                $this->Flash->danger(__('It was not possible to register the payment. Please try again.'));
            }
        } else {
            $this->Flash->danger(__('There was no data to be used on update'));
        }
        
        $this->Invoice->id = $checkedId;        
        return $this->redirect(array('controller' => 'invoices','action' => 'details', $checkedId));

    }
        
    public function send_receipt_to_contractor($id=null)
    {
        
        try {
            $checkedId = $this->verifyIdAndAdminRights($id);
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }
        
        $this->Invoice->Demand->id = $this->request->data['Demand']['id'];
        App::uses('CakeTime', 'Utility');
        if (empty($checkedId)) {
            throw new NotFoundException('No invoice was informed');
        }
        
        $this->Invoice->id = $checkedId;
        $demandId = $this->Invoice->field('demand_id');
        $invoiceInfo = $this->Invoice->invoiceInfo($checkedId);

        $this->Invoice->Demand->id = $invoiceInfo['Invoice']['demand_id'];
        $this->Invoice->Demand->Provider->id = $invoiceInfo['Invoice']['provider_id'];
        $userId = $this->Invoice->Demand->Provider->field('user_id');
        $this->Invoice->Demand->Provider->User->id = $userId;
        $userName = $this->Invoice->Demand->Provider->User->field('name');
        $userEmail = $this->Invoice->Demand->Provider->User->field('email');

        $contractorModel = $this->Invoice->
                Provider->
                Contractor;
        $contractorId = $contractorModel->
                getContractorByMarketplaceAndProviderId($this->EjqMarketplaceId, $invoiceInfo['Invoice']['provider_id']);

        $contractorModel->id = $contractorId['Contractor']['id'];
        $contractorName = $contractorModel->field('name');
        
        $data = [
                'user_id' => $userId,
                'name' => $contractorName,
                'email' => $userEmail,
                'subject' => __('Receipt for ') . $invoiceInfo['Invoice']['invoice_for'],
                'message' => "",
                'type' => EJQ_INVOICE_TYPE_COMMISSION,
        ];
        $attachments = [
                        ['file' => WWW_ROOT . '/img/logo-email.png',
                        'mimetype' => 'image/png',
                        'contentId' => 'ejq-logo-invoice'
                        ],
            ];
                
        $viewVars = ['invoiceInfo' => $invoiceInfo];
        
        $sendResult = $this->sendRenderedMessage($data, 'contractor_receipt', 'default', 'both', $viewVars, $attachments);
        
        $this->Invoice->id = $checkedId;
        $this->Invoice->saveField('receipt_sent', true);
        $this->Invoice->saveField('receipt_sent_on', date('Y-m-d H:i:s'));
        $this->Flash->success(__('The receipt was sent to Contractor'));

        return $this->redirect(['controller' => 'tenders','action' => 'details', $invoiceInfo['Invoice']['tender_id'], 'tab' => 'billing']);
        
    }

    public function send_receipt_to_home_owner($id=null)
    {
        
        try {
            $checkedId = $this->verifyIdAndAdminRights($id);
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }
        
        $this->Invoice->Demand->id = $this->request->data['Demand']['id'];
        App::uses('CakeTime', 'Utility');
        if (empty($checkedId)) {
            throw new NotFoundException('No invoice was informed');
        }

        $this->Invoice->id = $checkedId;
        $demandId = $this->Invoice->field('demand_id');
        $invoiceInfo = $this->Invoice->invoiceInfo($checkedId);

        $viewVars = ['invoiceInfo' => $invoiceInfo];
        $this->Invoice->Demand->id = $invoiceInfo['Invoice']['demand_id'];
        $this->Invoice->Demand->Consumer->id = $this->Invoice->Demand->field('consumer_id');
        $userId = $this->Invoice->Demand->Consumer->field('user_id');
        $this->Invoice->Demand->Consumer->User->id = $userId;
        $userName = $this->Invoice->Demand->Consumer->User->field('name');
        $userEmail = $this->Invoice->Demand->Consumer->User->field('email');
        $data = [
                'user_id' => $userId,
                'name' => $userName,
                'email' => $userEmail,
                'subject' => __('Receipt for ') . $invoiceInfo['Invoice']['invoice_for'],
                'message' => "",
                'type' => EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT,
        ];
        $attachments = [
                        ['file' => WWW_ROOT . '/img/logo-email.png',
                        'mimetype' => 'image/png',
                        'contentId' => 'ejq-logo-invoice'
                        ],
            ];
                
        $sendResult = $this->sendRenderedMessage($data, 'home_owner_receipt', 'default', 'both', $viewVars, $attachments);
        
        $this->Invoice->id = $checkedId;
        $this->Invoice->saveField('receipt_sent', true);
        $this->Invoice->saveField('receipt_sent_on', date('Y-m-d H:i:s'));
        $this->Flash->success(__('The receipt was sent to Home Owner'));

        return $this->redirect(['controller' => 'demands','action' => 'request_details', $invoiceInfo['Invoice']['demand_id'], 'tab' => 'billing']);
        
    }

    public function send_reminder_to_contractor($id=null)
    {
        try {
            $checkedId = $this->verifyIdAndAdminRights($id);
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
            $this->redirect(array('controller' => 'main', 'action' => 'index'));
        }
        
        if ($this->request->is(array('post', 'put'))) {
            
            $this->Invoice->id = $this->request->data['Invoice']['id'];
            $now = time();
            $dueDate = strtotime($this->Invoice->field('due_date'));
            $dateDiff = $now - $dueDate;
            $daysDiff =  floor($dateDiff / (60 * 60 * 24));
            
            $invoiceInfo = $this->Invoice->invoiceInfo($checkedId);
            $chosenBid = $this->Invoice->Demand->chosenBid($this->request->data['Demand']['id']);
            $invoiceInfo['Invoice']['days_due'] = $daysDiff;
            $viewVars=[
                'invoiceInfo' => $invoiceInfo,
                ];
            
            $this->Invoice->Demand->id = $this->request->data['Demand']['id'];
            $this->Invoice->Provider->id = $chosenBid['Provider']['id'];
            $userId = $this->Invoice->Provider->field('user_id');
            $this->Invoice->Demand->Provider->User->id = $userId;
            $userName = $this->Invoice->Demand->Provider->User->field('name');
            $userEmail = $this->Invoice->Demand->Provider->User->field('email');
            $data = [
                    'user_id' => $userId,
                    'name' => $userName,
                    'email' => $userEmail,
                    'subject' => __('Reminder of payment overdue '),
                    'message' => "",
                    'type' => 'contractor_payment_reminder',
            ];

            $attachments = [
                            ['file' => WWW_ROOT . '/img/logo-email.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'ejq-logo-invoice'
                            ],
                ];
            $sendResult = $this->sendRenderedMessage($data, 'contractor_reminder', 'default', 'both', $viewVars, $attachments);

            $this->Invoice->Provider->id = $chosenBid['Provider']['id'];
            $this->Invoice->Provider->saveField('good_standing', false);
            $this->Flash->success(__('The Due Notice was sent to Contractor'));

            return $this->redirect(['controller' => 'demands','action' => 'request_details', $this->request->data['Demand']['id'], 'tab' => 'billing']);
        } else {
            $this->Flash->danger(__('There was no data to be used on update'));
        }
        
        $this->Invoice->id = $checkedId;
        $demandId = $this->Invoice->field('demand_id');
        
        return $this->redirect(array('controller' => 'invoices','action' => 'details', $checkedId));

        
    }
        
    public function send_reminder_to_home_owner($id=null)
    {
        try {
            $checkedId = $this->verifyIdAndAdminRights($id);
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
            $this->redirect(array('controller' => 'main', 'action' => 'index'));
        }
        
        if ($this->request->is(array('post', 'put'))) {
            
            $this->Invoice->id = $this->request->data['Invoice']['id'];
            $now = time();
            $dueDate = strtotime($this->Invoice->field('due_date'));
            $dateDiff = $now - $dueDate;
            $daysDiff =  floor($dateDiff / (60 * 60 * 24));
            
            $invoiceInfo = $this->Invoice->invoiceInfo($checkedId);
            $invoiceInfo['Invoice']['days_due'] = $daysDiff;
            $viewVars=[
                'invoiceInfo' => $invoiceInfo,
                ];
            
            $this->Invoice->Demand->id = $this->request->data['Demand']['id'];
            $this->Invoice->Demand->Consumer->id = $this->Invoice->Demand->field('consumer_id');
            $userId = $this->Invoice->Demand->Consumer->field('user_id');
            $this->Invoice->Demand->Consumer->User->id = $userId;
            $userName = $this->Invoice->Demand->Consumer->User->field('name');
            $userEmail = $this->Invoice->Demand->Consumer->User->field('email');
            $data = [
                    'user_id' => $userId,
                    'name' => $userName,
                    'email' => $userEmail,
                    'subject' => __('Reminder of payment overdue '),
                    'message' => "",
                    'type' => 'home_owner_payment_reminder',
            ];

            $attachments = [
                            ['file' => WWW_ROOT . '/img/logo-email.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'ejq-logo-invoice'
                            ],
                ];
            $sendResult = $this->sendRenderedMessage($data, 'home_owner_reminder', 'default', 'both', $viewVars, $attachments);
            $this->Flash->success(__('The Due Notice was sent to Home Owner'));

            return $this->redirect(['controller' => 'demands','action' => 'request_details', $this->request->data['Demand']['id'], 'tab' => 'billing']);
        } else {
            $this->Flash->danger(__('There was no data to be used on update'));
        }
        
        $this->Invoice->id = $checkedId;
        $demandId = $this->Invoice->field('demand_id');
        
        return $this->redirect(array('controller' => 'invoices','action' => 'details', $checkedId));

        
    }
        
    public function send_to_contractor($id=null)
    {
        try {
            $checkedId = $this->verifyIdAndRightsToSendInvoice($id);
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }
        $this->Invoice->id = $checkedId;

        if (empty($this->Invoice->field('issue_date'))) {
            $this->Invoice->saveField('issue_date', date('Y-m-d H:i:s'));
        }

        if (empty($this->Invoice->field('due_date'))) {
            $issueDate = $this->Invoice->field('issue_date');
            $this->Invoice->saveField('due_date',date('Y-m-d H:i:s', strtotime($issueDate . ' + 30 days')));
        }
        $invoiceInfo = $this->Invoice->invoiceInfo($checkedId);

        App::uses('CakeTime', 'Utility');
        
        $this->Invoice->Demand->id = $invoiceInfo['Invoice']['demand_id'];
        $this->Invoice->Demand->Provider->id = $invoiceInfo['Invoice']['provider_id'];

        $contractorModel = $this->Invoice->
                Provider->
                Contractor;
        $contractorId = $contractorModel->
                getContractorByMarketplaceAndProviderId($this->EjqMarketplaceId, $invoiceInfo['Invoice']['provider_id']);

        $contractorModel->id = $contractorId['Contractor']['id'];
        $contractorName = $contractorModel->field('name');


        $userId = $this->Invoice->Demand->Provider->field('user_id');
        $this->Invoice->Demand->Provider->User->id = $userId;
        $userName = $this->Invoice->Demand->Provider->User->field('name');
        $userEmail = $this->Invoice->Demand->Provider->User->field('email');
            
        $data = [
                'user_id' => $userId,
                'name' => $contractorName,
                'email' => $userEmail,
                'subject' => __('Invoice for ') . $invoiceInfo['Invoice']['invoice_for'],
                'message' => "",
                'type' => EJQ_INVOICE_TYPE_COMMISSION,
        ];
                
        $attachments = [
                        ['file' => WWW_ROOT . '/img/logo-email.png',
                        'mimetype' => 'image/png',
                        'contentId' => 'ejq-logo-invoice'
                        ],
            ];
        
        $viewVars = ['invoiceInfo' => $invoiceInfo];
        $sendResult = $this->sendRenderedMessage($data, 'contractor_invoice', 'default', 'both', $viewVars, $attachments);
        
        $this->Invoice->saveField('status', EJQ_INVOICE_STATUS_SENT);
        $this->Flash->success(__('The invoice was sent to Contractor'));

        return $this->redirect(['controller' => 'demands','action' => 'request_details', $invoiceInfo['Invoice']['demand_id'], 'tab' => 'billing']);
        
    }

    public function send_to_home_owner($id=null)
    {
        
        try {
            $checkedId = $this->verifyIdAndRightsToSendInvoice($id);
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }
        
        $this->Invoice->Demand->id = $this->request->data['Demand']['id'];
        $this->Invoice->Tender->id = $this->request->data['Tender']['id'];
        $tenderTitle = $this->Invoice->Tender->field('title');
        $this->request->data['Consumer']['id'] = $this->Invoice->Demand->field('consumer_id');
        $this->request->data['Invoice']['type'] = EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT;
        App::uses('CakeTime', 'Utility');
        if (empty($checkedId)) {
            $tempTenderInfo = $this->Invoice->initNewInvoiceData($this->request->data);
            $this->Invoice->create();
            $tempTenderInfo['Invoice']['number'] = $this->Invoice->id;
            $tempTenderInfo['Invoice']['issue_date'] = date('Y-m-d H:i:s');
            $tempTenderInfo['Invoice']['due_date'] = date('Y-m-d H:i:s', strtotime(' + 30 days'));
            $tempTenderInfo['Invoice']['type'] = EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT;
            $invoiceInfo = $this->Invoice->save($tempTenderInfo);
            $invoiceInfo['Invoice']['issue_date'] = CakeTime::format($tempTenderInfo['Invoice']['issue_date'], '%b %d, %Y');
            $invoiceInfo['Invoice']['due_date'] = CakeTime::format($tempTenderInfo['Invoice']['due_date'], '%b %d, %Y');
        } else {
            $this->Invoice->id = $checkedId;
            
            if (empty($this->Invoice->field('issue_date'))) {
                $this->Invoice->saveField('issue_date', date('Y-m-d H:i:s'));
            }

            if (empty($this->Invoice->field('due_date'))) {
                $issueDate = $this->Invoice->field('issue_date');
                $this->Invoice->saveField('due_date',date('Y-m-d H:i:s', strtotime($issueDate . ' + 30 days')));
            }

            $invoiceInfo = [
                'Invoice' => [
                    'marketplace_id' => $this->EjqMarketplaceId,
                    'tender_id' => $this->request->data['Tender']['id'],
                    'demand_id' => $this->request->data['Demand']['id'],
                    'consumer_id' => $this->request->data['Consumer']['id'],
                    'type' => EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT,
                    'number' => $this->Invoice->field('number'),
                    'issue_date' => CakeTime::format($this->Invoice->field('issue_date'), '%b %d, %Y'),
                    'due_date' => CakeTime::format($this->Invoice->field('due_date'), '%b %d, %Y'),
                    'invoice_to' => $this->Invoice->field('invoice_to'),
                    'invoice_for' => nl2br($this->Invoice->field('invoice_for')),
                    'service_description' => $this->Invoice->field('service_description'),
                    'service_value' => $this->Invoice->field('service_value'),
                    'tax_value' => $this->Invoice->field('tax_value'),
                    'total_value' => $this->Invoice->field('total_value'),
                    'tender_title' => $tenderTitle,
                    'info' => 'Please make all cheques payable to Job Confidence Inc. Payment can also be made via email to
<a href="mailto:lisa@easyjobquote.com">lisa@easyjobquote.com</a>
Payment is due within 30 days.
If you have any questions concerning this invoice, contact Lisa Tinney | 250 590-8182 |
<a href="mailto:lisa@easyjobquote.com">lisa@easyjobquote.com</a>
            ',
                ],
            ];
        
        

            
        }
        
        
        $this->Invoice->Demand->id = $invoiceInfo['Invoice']['demand_id'];
        $this->Invoice->Demand->Consumer->id = $this->Invoice->Demand->field('consumer_id');
        $userId = $this->Invoice->Demand->Consumer->field('user_id');
        $this->Invoice->Demand->Consumer->User->id = $userId;
        $userName = $this->Invoice->Demand->Consumer->User->field('name');
        $userEmail = $this->Invoice->Demand->Consumer->User->field('email');
            
        $data = [
                'user_id' => $userId,
                'name' => $userName,
                'email' => $userEmail,
                'subject' => __('Invoice for ') . $invoiceInfo['Invoice']['invoice_for'],
                'message' => "",
                'type' => EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT,
        ];
                
        $attachments = [
                        ['file' => WWW_ROOT . '/img/logo-email.png',
                        'mimetype' => 'image/png',
                        'contentId' => 'ejq-logo-invoice'
                        ],
            ];
        
        $viewVars = ['invoiceInfo' => $invoiceInfo];
        
        $sendResult = $this->sendRenderedMessage($data, 'home_owner_invoice', 'default', 'both', $viewVars, $attachments);
        
        $this->Invoice->saveField('status', EJQ_INVOICE_STATUS_SENT);
        $this->Flash->success(__('The invoice was sent to Home Owner'));

        return $this->redirect(['controller' => 'demands','action' => 'request_details', $invoiceInfo['Invoice']['demand_id'], 'tab' => 'billing']);
        
    }

    public function update_due_date($id=null)
    {
        if (!$this->canAccessAdm) {
                $this->Flash->danger(__('You are not allowed to do this action.'));
                return $this->redirect(['controller' => 'main','action' => 'index']);
        }
        try {
            $checkedId = $this->initIdInCaseOfPost($id, 'Invoice', 'id');
            $demandId = $this->initIdInCaseOfPost(null, 'Demand', 'id');
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
        }

        if ($this->request->is(['post', 'put'])) {
            if ($this->saveDueDate()) {
                $this->Flash->success(__('The due date was updated'));
            } else {
                $this->Flash->danger(__('It was not possible to update the due date. Please contact support.'));
            }
        } else {
            $this->Flash->danger(__('Nothing to be updated'));
        }
        return $this->redirect(['controller' => 'invoices','action' => 'details', $checkedId]);
    }

    public function update_for($id=null)
    {
        if (!$this->canAccessAdm) {
                $this->Flash->danger(__('You are not allowed to do this action.'));
                return $this->redirect(['controller' => 'main','action' => 'index']);
        }
        try {
            $checkedId = $this->initIdInCaseOfPost($id, 'Invoice', 'id');
            $demandId = $this->initIdInCaseOfPost(null, 'Demand', 'id');
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
        }

        if ($this->request->is(['post', 'put'])) {
            if ($this->saveFor()) {
                $this->Flash->success(__('The invoice was updated'));
            } else {
                $this->Flash->danger(__('It was not possible to update the information. Please contact support.'));
            }
        } else {
            $this->Flash->danger(__('Nothing to be updated'));
        }
        return $this->redirect(['controller' => 'invoices','action' => 'details', $checkedId]);
    }
    
    public function update_issue_date($id=null)
    {
        if (!$this->canAccessAdm) {
                $this->Flash->danger(__('You are not allowed to do this action.'));
                return $this->redirect(['controller' => 'main','action' => 'index']);
        }
        try {
            $checkedId = $this->initIdInCaseOfPost($id, 'Invoice', 'id');
            $demandId = $this->initIdInCaseOfPost(null, 'Demand', 'id');
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
        }

        if ($this->request->is(['post', 'put'])) {
            if ($this->saveIssueDate()) {
                $this->Flash->success(__('The issue date was updated'));
            } else {
                $this->Flash->danger(__('It was not possible to update the issue date. Please contact support.'));
            }
        } else {
            $this->Flash->danger(__('Nothing to be updated'));
        }
        return $this->redirect(['controller' => 'invoices','action' => 'details', $checkedId]);
    }

    public function update_receipt_date($id=null)
    {
        if (!$this->canAccessAdm) {
                $this->Flash->danger(__('You are not allowed to do this action.'));
                return $this->redirect(['controller' => 'main','action' => 'index']);
        }
        try {
            $checkedId = $this->initIdInCaseOfPost($id, 'Invoice', 'id');
            $demandId = $this->initIdInCaseOfPost(null, 'Demand', 'id');
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
        }

        if ($this->request->is(['post', 'put'])) {
            if ($this->saveReceiptDate()) {
                $this->Flash->success(__('The receipt date was updated'));
            } else {
                $this->Flash->danger(__('It was not possible to update the receipt date. Please contact support.'));
            }
        } else {
            $this->Flash->danger(__('Nothing to be updated'));
        }
        return $this->redirect(['controller' => 'invoices','action' => 'details', $checkedId]);
    }

    public function update_to($id=null)
    {
        if (!$this->canAccessAdm) {
                $this->Flash->danger(__('You are not allowed to do this action.'));
                return $this->redirect(['controller' => 'main','action' => 'index']);
        }
        try {
            $checkedId = $this->initIdInCaseOfPost($id, 'Invoice', 'id');
            $demandId = $this->initIdInCaseOfPost(null, 'Demand', 'id');
        } catch (Exception $ex) {
            $this->Flash->danger($ex->getMessage());
        }

        if ($this->request->is(['post', 'put'])) {
            if ($this->saveTo()) {
                $this->Flash->success(__('The adress was updated'));
            } else {
                $this->Flash->danger(__('It was not possible to update the to. Please contact support.'));
            }
        } else {
            $this->Flash->danger(__('Nothing to be updated'));
        }
        return $this->redirect(['controller' => 'invoices','action' => 'details', $checkedId]);
    }

    private function saveDueDate()
    {
        
        App::uses('DateTime', 'Utility');

        $result = [];
        if (isset($this->request->data['Invoice']['due_date'])) {
            $dueDate = $this->request->data['Invoice']['due_date'];
            try {
                $objDueDate = DateTime::createFromFormat('m/d/Y', $dueDate);
                $mySqlDueDate = date('Y-m-d 0:0:0', $objDueDate->getTimeStamp());
            } catch (Exception $ex) {
                return null;
            }
            
            if(empty($this->request->data['Invoice']['id'])) {
                $data = $this->Invoice->initNewInvoiceData($this->request->data);
                $data['Invoice']['due_date'] = $mySqlDueDate;
                $invoiceData['Invoice'] = $data['Invoice'];
                $this->Invoice->create();
                $result = $this->Invoice->save($invoiceData);
            } else {
                $this->Invoice->id = $this->request->data['Invoice']['id'];
                $result = $this->Invoice->saveField('due_date', $mySqlDueDate);
            }
        }
        return ($result);
    }

    private function saveIssueDate()
    {
        
        App::uses('DateTime', 'Utility');

        
        $result = [];
        if (isset($this->request->data['Invoice']['issue_date'])) {
            $issueDate = $this->request->data['Invoice']['issue_date'];
            try {
                $objIssueDate = DateTime::createFromFormat('m/d/Y', $issueDate);
                $mySqlIssueDate = date('Y-m-d 0:0:0', $objIssueDate->getTimeStamp());
            } catch (Exception $ex) {
                return null;
            }
            
            if (empty($this->request->data['Invoice']['due_date']) && !empty($mySqlIssueDate)) {
                $dueDate = date('m/d/Y', strtotime($mySqlIssueDate . ' + 30 days'));
                $this->request->data['Invoice']['due_date'] = date('Y-m-d H:i:s', strtotime($mySqlIssueDate . ' + 30 days'));
                try {
                    $objDueDate = DateTime::createFromFormat('m/d/Y', $dueDate);
                    $mySqlDueDate = date('Y-m-d 0:0:0', $objDueDate->getTimeStamp());
                } catch (Exception $ex) {
                    return null;
                }
            } else {
                return null;
            }
            
            if(empty($this->request->data['Invoice']['id'])) {
                $data = $this->Invoice->initNewInvoiceData($this->request->data);
                $data['Invoice']['issue_date'] = $mySqlIssueDate;
                $data['Invoice']['due_date'] = $mySqlDueDate;
                $invoiceData['Invoice'] = $data['Invoice'];
                $this->Invoice->create();
                $result = $this->Invoice->save($invoiceData);
            } else {
                $this->Invoice->id = $this->request->data['Invoice']['id'];
                $result = $this->Invoice->saveField('issue_date', $mySqlIssueDate);
                $result = $this->Invoice->saveField('due_date', $mySqlDueDate);
            }
        }
        return ($result);
    }

    private function saveFor()
    {
        if(empty($this->request->data['Invoice']['id'])) {
            $data = $this->Invoice->initNewInvoiceData($this->request->data);
            $data['invoice_for'] = $this->request->data['Invoice']['invoice_for'];
            $invoiceData['Invoice'] = $data;
            $this->Invoice->create();
            $result = $this->Invoice->save($invoiceData);
        } else {
            $this->Invoice->id = $this->request->data['Invoice']['id'];
            $result = $this->Invoice->saveField('invoice_for', $this->request->data['Invoice']['invoice_for']);
        }
        return ($result);
    }

    private function saveReceiptDate()
    {
        
        App::uses('DateTime', 'Utility');
        
        $result = [];
        if (isset($this->request->data['Invoice']['receipt_date'])) {
            $receiptDate = $this->request->data['Invoice']['receipt_date'];
            try {
                $objReceiptDate = DateTime::createFromFormat('m/d/Y', $receiptDate);
                $mySqlReceiptDate = date('Y-m-d 0:0:0', $objReceiptDate->getTimeStamp());
            } catch (Exception $ex) {
                return null;
            }
            
            $this->Invoice->id = $this->request->data['Invoice']['id'];
            $this->Invoice->saveField('receipt_sent', false);
            $this->Invoice->saveField('receipt_sent_on', null);
            $result = $this->Invoice->saveField('receipt_date', $mySqlReceiptDate);
        }
        return ($result);
    }

    private function saveTo()
    {
        if(empty($this->request->data['Invoice']['id'])) {
            $data = $this->Invoice->initNewInvoiceData($this->request->data);
            $data['invoice_to'] = $this->request->data['Invoice']['invoice_to'];
            $invoiceData['Invoice'] = $data;
            $this->Invoice->create();
            $result = $this->Invoice->save($invoiceData);
        } else {
            $this->Invoice->id = $this->request->data['Invoice']['id'];
            $result = $this->Invoice->saveField('invoice_to', $this->request->data['Invoice']['invoice_to']);
        }
        return ($result);
    }
    
    private function sendRenderedMessage($data, $view, $layout, $emailFormat, $viewVars, $attachments)
    {
            $this->loadmodel('SentEmail');
            $senderComponent = $this->Components->load('SendEmail');
            $sendResult = $senderComponent->sendRendered($data, $this->SentEmail, $view, $layout, $emailFormat, $viewVars, $attachments);

            return $sendResult;

    }

    private function verifyIdAndAdminRights($id)
    {
        if(empty($id)) {
            $checkedId = $this->initIdInCaseOfPost($id, 'Invoice', 'id');
            $checkedTenderId = $this->initIdInCaseOfPost(null, 'Tender', 'id');
            
            if (empty($checkedId)) {
                if (empty($checkedTenderId)) {
                    throw new NotFoundException('verifyIdRightsToSendInvoice: neither invoice id nor tender id');
                } else {
                    if (!$this->Invoice->Tender->exists($checkedTenderId)) {
                        throw new NotFoundException('verifyIdRightsToSendInvoice: Invalid tender id');
                    } else {
                        $this->Invoice->Tender->id = $checkedTenderId;
                        return null;
                    }
                }
                
            }
            
            
        } else {
            $checkedId = $this->initIdInCaseOfPost($id, 'Invoice', 'id');
            if (!$this->Invoice->exists($checkedId)) {
                throw new NotFoundException('verifyIdRightsToSendInvoice: Invalid invoice id');
            }

            
        }
        if (!$this->canAccessAdm) {
            throw new NotFoundException('Only Site Admins can send Invoices.');
        }
                
        return $checkedId;
    }

    private function verifyIdAndRightsToSeeInvoice($id)
    {
        if(empty($id)) {
            $checkedId = $this->initIdInCaseOfPost($id, 'Invoice', 'id');
            $checkedTenderId = $this->initIdInCaseOfPost(null, 'Tender', 'id');
            
            if (empty($checkedId)) {
                if (empty($checkedTenderId)) {
                    throw new NotFoundException('verifyIdRightsToSendInvoice: neither invoice id nor tender id');
                } else {
                    if (!$this->Invoice->Tender->exists($checkedTenderId)) {
                        throw new NotFoundException('verifyIdRightsToSendInvoice: Invalid tender id');
                    } else {
                        return null;
                    }
                }
                
            }
            
            
        } else {
            $checkedId = $this->initIdInCaseOfPost($id, 'Invoice', 'id');
            if (!$this->Invoice->exists($checkedId)) {
                throw new NotFoundException('verifyIdRightsToSendInvoice: Invalid invoice id');
            }

            
        }
        
        $this->Invoice->id = $checkedId;
        $type = $this->Invoice->field('status');
        if ($type == EJQ_INVOICE_TYPE_COMMISSION) {
            $providerId = $this->Invoice->field('provider_id');
            $this->Invoice->Provider->id = $providerId;
            $userId = $this->Invoice->Provider->field('user_id');
            
            if (!$this->canAccessAdm && $this->uid != $userId) {
                $this->Flash->danger(__("Sorry, you can\'t see this invoice"));
                $tenderId = $this->Invoice->field('tender_id');
                $this->redirect(array('controller' => 'tender', 'action' => 'details', 'redirect' => true, $tenderId));
            }
        } elseif ($type == EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT) {
            $consumerId = $this->Invoice->field('consumer_id');
            $this->Invoice->Consumer->id = $consumerId;
            $userId = $this->Invoice->Consumer->field('user_id');
            
            if (!$this->canAccessAdm && $this->uid != $userId) {
                $this->Flash->danger(__("Sorry, you can\'t see this invoice"));
                $tenderId = $this->Invoice->field('tender_id');
                $this->redirect(array('controller' => 'tender', 'action' => 'details', 'redirect' => true, $tenderId));
            }
        }
        
        $status = $this->Invoice->field('status');
        if ($status != EJQ_INVOICE_STATUS_SENT && $status != EJQ_INVOICE_STATUS_PAID) {
            $this->Flash->danger(__("Sorry, this invoice is not available"));
            $tenderId = $this->Invoice->field('tender_id');
            $this->redirect(array('controller' => 'tender', 'action' => 'details', 'redirect' => true, $tenderId));
        }
        
        return $checkedId;
    }

    private function verifyIdAndRightsToSendInvoice($id)
    {
        if(empty($id)) {
            $checkedId = $this->initIdInCaseOfPost($id, 'Invoice', 'id');
            $checkedTenderId = $this->initIdInCaseOfPost(null, 'Tender', 'id');
            
            if (empty($checkedId)) {
                if (empty($checkedTenderId)) {
                    throw new NotFoundException('verifyIdRightsToSendInvoice: neither invoice id nor tender id');
                } else {
                    if (!$this->Invoice->Tender->exists($checkedTenderId)) {
                        throw new NotFoundException('verifyIdRightsToSendInvoice: Invalid tender id');
                    } else {
                        return null;
                    }
                }
                
            }
            
            
        } else {
            $checkedId = $this->initIdInCaseOfPost($id, 'Invoice', 'id');
            if (!$this->Invoice->exists($checkedId)) {
                throw new NotFoundException('verifyIdRightsToSendInvoice: Invalid invoice id');
            }

            
        }
        if (!$this->canAccessAdm) {
            throw new NotFoundException('Only Site Admins can send Invoices.');
        }
        
        $this->Invoice->id = $checkedId;
        $status = $this->Invoice->field('status');
        if ($status == EJQ_INVOICE_STATUS_SENT || $status == EJQ_INVOICE_STATUS_PAID) {
            $this->Flash->danger(__('This invoice was already sent'));
            $demandId = $this->Invoice->field('demand_id');
            $this->redirect(array('controller' => 'demands', 'action' => 'request_details', 'redirect' => true, $demandId));
        }
        
        return $checkedId;
    }

}
