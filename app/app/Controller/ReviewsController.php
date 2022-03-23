<?php

App::uses('AppController', 'Controller');

class ReviewsController extends AppController {

    public function beforeFilter() {
        $this->Auth->allow(array(
            'update',
            ));

        parent::beforeFilter();
    }
    
    public function update($id=null) {
    
        $consumer = $this->Review->Job->Marketplace->Consumer->getConsumerByMarketplaceAndUserId($this->EjqMarketplaceId, $this->uid);
        if(empty($consumer['Consumer']['id']) && !$this->canAccessAdm) {
            $this->Flash->danger(__("Sorry, you are not allowed to do this action"));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        } else {
            $consumerId = $consumer['Consumer']['id'];
        }
        try {
            $checkedTenderId = $this->initIdInCaseOfPost($id, 'Tender', 'id');
            if (empty($checkedTenderId)){
                $checkedJobId = $this->initIdInCaseOfPost($id, 'Job', 'id');
                $this->Review->Job->id = $checkedJobId;
                $checkedTenderId = $this->Review->Job->field('tender_id');
            }
            if (!$this->canAccessAdm) {
                $this->verifyIdAndAccessRightsToHomeOwnerTenderDetails($checkedJobId, $consumerId);
                $this->set('userRole', EJQ_ROLE_HOME_OWNER);
            } else {
                $this->set('userRole', EJQ_ROLE_ADMIN);
            }
//            $tenderInfo = $this->Job->Tender->getJobInfo($checkedTenderId);
            $tenderInfo = $this->Review->Job->Tender->getTenderInfo($checkedTenderId);
            $tenderId = $tenderInfo['Tender']['id'];
        } catch (Exception $ex) {
            throw $ex;
        }
        
        $this->set('isDemandEstimator', false);
        $this->set('hereTitle', __('Post Job Review'));
        $titleBox['h1'] = $tenderInfo['Tender']['title'];
        $titleBox['h2'] = __('Please tell us about your experience');
        $this->set('titleBox', $titleBox);

        if ($this->request->is(array('post', 'put'))) {
            if ($this->saveReview($tenderInfo)) {
                $this->Flash->success(__('The job review was saved'));
                return $this->redirect(array('controller' => 'tenders','action' => 'details', $tenderId));
            } else {
                $this->Flash->danger(__('It was not possible to save the review. Please try again.'));
            }
        }


        $tenderInfo['Demand']['services_list_description'] = $this->extractServiceListDescription($tenderInfo);
        $this->set('tenderInfo', $tenderInfo);
        $this->request->data = $tenderInfo;
    }
    
    
    private function saveReview($tenderInfo)
    {
        $this->Review->id = $this->request->data['Review']['id'];
        $jobId = $this->request->data['Job']['id'];
        $this->Review->Job->id = $jobId;
        $this->request->data['Review']['provider_id'] = $this->Review->Job->field('provider_id');
        $this->request->data['Review']['consumer_id'] = $this->Review->Job->field('consumer_id');
        $this->request->data['Review']['job_id'] = $jobId;
        
        $overallRating = (
                $this->request->data['Review']['punctuality_rating'] +
                $this->request->data['Review']['behaviour_rating'] +
                $this->request->data['Review']['cleanliness_rating'] +
                $this->request->data['Review']['quality_of_work_rating'] +
                $this->request->data['Review']['likelihood_to_recommend_rating']
                ) / 5;
                        
        $this->request->data['Review']['overall_rating'] = $overallRating;
        $reviewData = $this->request->data;
        $result = $this->Review->save($reviewData);
        
        return ($result);
        
    }

    private function verifyIdAndAccessRightsToHomeOwnerTenderDetails($id, $consumerId)
    {
        $jobId = $this->initIdInCaseOfPost($id, 'Job', 'id');
        if (!$this->Review->Job->exists($jobId)) {
            throw new NotFoundException('verifyIdAndAccessRightsToHomeOwnerTenderDetails: Invalid tender id');
        } else {
            $this->Review->Job->id = $jobId;
            $tenderId = $this->Review->Job->field('tender_id');
        }
        try {
            $canSee = $this->Review->Job->Tender->homeOwnerCanSeeTenderDetails($tenderId, $consumerId);
        } catch (Exception $ex) {
            $this->Flash->danger(__('An error ocurred'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }

        if (!$canSee) {
            $this->Flash->danger(__('Você não está autorizado a realizar essa ação.'));
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'redirect' => true ));
        }

        return $tenderId;
    }




}
