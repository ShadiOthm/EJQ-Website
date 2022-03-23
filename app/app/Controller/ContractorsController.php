<?php

App::uses('AppController', 'Controller');

class ContractorsController extends AppController {

    public $components = ['Paginator'];

    public function beforeFilter() {
        $this->Auth->allow([
            'toggle_qualify',
            'update_about_info',
            'update_company_disclosure',
            'update_contact_info',
            'update_licences',
            ]);

        parent::beforeFilter();
    }
    
    public function toggle_qualify($id=null)
    {
        
        try {
            $checkedId = $this->initIdInCaseOfPost($id, 'Contractor', 'id');
            $this->Contractor->id = $checkedId;
            $providerId = $this->Contractor->field('provider_id');
        } catch (Exception $ex) {
            echo __('Some error occurred and was not possible to fetch data about contractor');
            exit;
        }
        
        if (!$this->canAccessAdm) {
            echo __('Você não está autorizado a realizar essa ação.');
            exit;
        }
        
        $this->Contractor->Provider->id = $providerId;
        $currentStatus = $this->Contractor->Provider->field('qualified');
        $newStatus = !$currentStatus;
        $this->Contractor->Provider->saveField('qualified', $newStatus);
        
        if ($newStatus) {
            $this->set('feedbackMessage', __('This contractor can bid on tenders now.'));
            $this->set('feedbackClass', 'text-success');
        } else {
            $this->set('feedbackMessage', __("This contractor can't bid on tenders now."));
            $this->set('feedbackClass', 'text-danger');
        }
            
        $this->request->data = [
            'Contractor' => ['id' => $checkedId],
            'Provider' => ['qualified' => $newStatus],
        ];
        
        $this->layout = 'ajax';
    }

    public function update_about_info($id=null)
    {
        try {
            $checkedId = $this->initIdInCaseOfPost($id, 'Contractor', 'id');
            $this->verifyIfUserCanEditContractorInfo($checkedId);
            $this->Contractor->id = $checkedId;
            $providerId = $this->Contractor->field('provider_id');
        } catch (Exception $ex) {
            throw $ex;
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->saveAboutInfo()) {
                $this->Flash->success(__('The company description was updated'));
            } else {
                $this->Flash->danger(__('It was not possible to update the conmpany description. Please contact support.'));
            }
        } else {
            $this->Flash->danger(__('Nothing to be updated'));
        }
        return $this->redirect(array('controller' => 'providers','action' => 'dashboard', $providerId, 'tab' => 'my_info', '#' => 'breadcrumb'));
    }

    public function update_company_disclosure($id=null)
    {
        try {
            $checkedId = $this->initIdInCaseOfPost($id, 'Contractor', 'id');
            $this->verifyIfUserCanEditContractorInfo($checkedId);
            $this->Contractor->id = $checkedId;
            $providerId = $this->Contractor->field('provider_id');
        } catch (Exception $ex) {
            throw $ex;
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->saveCompanyInfo()) {
                $this->Flash->success(__('The company info was updated'));
            } else {
                $this->Flash->danger(__('It was not possible to update the company info. Please contact support.'));
            }
        } else {
            $this->Flash->danger(__('Nothing to be updated'));
        }
        return $this->redirect(array('controller' => 'providers','action' => 'dashboard', $providerId, 'tab' => 'my_info', '#' => 'breadcrumb'));
    }

    public function update_contact_info($id=null)
    {
        try {
            $checkedId = $this->initIdInCaseOfPost($id, 'Contractor', 'id');
            $this->verifyIfUserCanEditContractorInfo($checkedId);
            $this->Contractor->id = $checkedId;
            $providerId = $this->Contractor->field('provider_id');
        } catch (Exception $ex) {
            throw $ex;
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->saveContactInfo()) {
                $this->Flash->success(__('The contact info was updated'));
            } else {
                $this->Flash->danger(__('It was not possible to update the contact info. Please contact support.'));
            }
        } else {
            $this->Flash->danger(__('Nothing to be updated'));
        }
        return $this->redirect(array('controller' => 'providers','action' => 'dashboard', $providerId, 'tab' => 'my_info', '#' => 'breadcrumb'));
    }

    public function update_licences($id=null)
    {
        try {
            $checkedId = $this->initIdInCaseOfPost($id, 'Contractor', 'id');
            $this->verifyIfUserCanEditContractorInfo($checkedId);
            $this->Contractor->id = $checkedId;
            $providerId = $this->Contractor->field('provider_id');
        } catch (Exception $ex) {
            throw $ex;
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->saveLicences()) {
                $this->Flash->success(__('The licences list was updated'));
            } else {
                $this->Flash->danger(__('It was not possible to update the licences list. Please contact support.'));
            }
        } else {
            $this->Flash->danger(__('Nothing to be updated'));
        }
        return $this->redirect(array('controller' => 'providers','action' => 'dashboard', $providerId, 'tab' => 'my_info', '#' => 'breadcrumb'));
    }

    private function saveAboutInfo()
    {
        $this->Contractor->id = $this->request->data['Contractor']['id'];
        $contractorData = $this->request->data;
        $result = $this->Contractor->save($contractorData);
        
        return ($result);
        
    }
    
    private function saveCompanyInfo()
    {
        $this->Contractor->id = $this->request->data['Contractor']['id'];
        $providerId = $this->Contractor->field('provider_id');
        
        if (!empty($this->request->data['ServiceType'])) {
            if (FALSE !== array_search($this->EjqGeneralContractorServiceTypeId, $this->request->data['ServiceType']['ServiceType'])) {
                $marketplaceServiceTypes = $this->Contractor->Marketplace->listServiceTypes($this->EjqMarketplaceId);
                $this->request->data['ServiceType']['ServiceType'] = array_keys($marketplaceServiceTypes);
            }
            $providerData = [
                'Provider' => [
                    'id' => $providerId
                ],
                'ServiceType' => $this->request->data['ServiceType'],
            ];
            $this->Contractor->Provider->save($providerData);
            unset($this->request->data['ServiceType']);
        }
        
        $contractorData = $this->request->data;
        $result = $this->Contractor->save($contractorData);
        
        return ($result);
        
    }

    private function saveContactInfo()
    {
        $this->Contractor->id = $this->request->data['Contractor']['id'];
                        
        $contractorData = $this->request->data;
        
        $result = $this->Contractor->save($contractorData);
        
        return ($result);
        
    }

    private function saveLicences()
    {
        $this->Contractor->id = $this->request->data['Contractor']['id'];
                        
        $contractorData = $this->request->data;
        
        $result = $this->Contractor->save($contractorData);
        
        return ($result);
        
    }

    private function verifyIfUserCanEditContractorInfo($id)
    {
        $this->Contractor->id = $id;
        $providerId = $this->Contractor->field('provider_id');
        $this->Contractor->Provider->id = $providerId;
        $userId = $this->Contractor->Provider->field('user_id');

        if((!$this->canAccessAdm) &&
                ($this->uid != $userId)) {
            throw new NotFoundException(__('You are not authorized to do this action.'));
        }

        return $providerId;


    }

    
    
}
