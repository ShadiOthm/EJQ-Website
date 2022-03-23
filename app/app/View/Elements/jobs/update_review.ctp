<h4><?php echo __("Job Review:"); ?></h4>
 <?php
        echo $this->Form->create('Review', array('url'=>array('controller'=>'reviews', 'action' => 'update'), 'tab' => 'review'));
        echo $this->Form->input('id');
        echo $this->Form->input('Job.id');
        echo $this->Form->input(
                'Review.punctuality_rating', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control select sl-lg',
                    'before' => '<label for="ReviewPunctualityRating">' . __('Punctuality Rating') . '</label><br />',
                    'after' =>  '',
                    'label' => false,
                    'legend' => false,
                    'fieldset' => false,
                    'type' => 'select',
                    'options' => ['0' => __('(choose one)'), '5' => __('*****'), '4' => __('****'), '3' => __('***'), '2' => __('**'), '1' => __('*')],
                    'required' => true,
                    ));
        
        echo $this->Form->input(
                'Review.punctuality_comment', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('Comments About Punctuality:'), 'class' => 'control-label'),
                    ));
        
        echo $this->Form->input(
                'Review.behaviour_rating', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control select sl-lg',
                    'before' => '<label for="ReviewBehaviourRating">' . __('Behaviour Rating') . '</label><br />',
                    'after' =>  '',
                    'label' => false,
                    'legend' => false,
                    'fieldset' => false,
                    'type' => 'select',
                    'options' => ['0' => __('(choose one)'), '5' => __('*****'), '4' => __('****'), '3' => __('***'), '2' => __('**'), '1' => __('*')],
                    'required' => true,
                    ));
        
        echo $this->Form->input(
                'Review.behaviour_comment', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('Comments About Behaviour:'), 'class' => 'control-label'),
                    ));
        
        echo $this->Form->input(
                'Review.cleanliness_rating', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control select sl-lg',
                    'before' => '<label for="ReviewCleanlinessRating">' . __('Cleanliness Rating') . '</label><br />',
                    'after' =>  '',
                    'label' => false,
                    'legend' => false,
                    'fieldset' => false,
                    'type' => 'select',
                    'options' => ['0' => __('(choose one)'), '5' => __('*****'), '4' => __('****'), '3' => __('***'), '2' => __('**'), '1' => __('*')],
                    'required' => true,
                    ));
        
        echo $this->Form->input(
                'Review.cleanliness_comment', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('Comments About Cleanliness:'), 'class' => 'control-label'),
                    ));
        
        echo $this->Form->input(
                'Review.quality_of_work_rating', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control select sl-lg',
                    'before' => '<label for="ReviewQualityOfWorkRating">' . __('Quality Of Work Rating') . '</label><br />',
                    'after' =>  '',
                    'label' => false,
                    'legend' => false,
                    'fieldset' => false,
                    'type' => 'select',
                    'options' => ['0' => __('(choose one)'), '5' => __('*****'), '4' => __('****'), '3' => __('***'), '2' => __('**'), '1' => __('*')],
                    'required' => true,
                    ));
        
        echo $this->Form->input(
                'Review.quality_of_work_comment', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('Comments About Quality Of Work:'), 'class' => 'control-label'),
                    ));
        
        echo $this->Form->input(
                'Review.likelihood_to_recommend_rating', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control select sl-lg',
                    'before' => '<label class="control-label" for="ReviewLikelihoodToRecommendRating">' . __('Likelihood To Recommend Rating') . '</label><br />',
                    'after' =>  '',
                    'label' => false,
                    'legend' => false,
                    'fieldset' => false,
                    'type' => 'select',
                    'options' => ['0' => __('(choose one)'), '5' => __('*****'), '4' => __('****'), '3' => __('***'), '2' => __('**'), '1' => __('*')],
                    'required' => true,
                    ));
        
        echo $this->Form->input(
                'Review.likelihood_to_recommend_comment', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('Comments About Likelihood To Recommend:'), 'class' => 'control-label'),
                    ));
        
        echo $this->Element('forms/submit');
        echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']),
                '#review', 
                array(
                    'escape' => false, 
                    'title' => __('Cancel'), 
                    'id' => 'hide_form_update_review'
                    )
                );
        echo $this->Form->end();
        ?>
