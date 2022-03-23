<h4><?php echo __("Company Disclosure:"); ?></h4>
 <?php
        echo $this->Form->create('Contractor', array('url'=>array('controller'=>'contractors', 'action' => 'update_company_disclosure'), 'tab' => 'my_info'));
        echo $this->Form->input('id');
        echo $this->Form->input(
                'Contractor.years_in_business', 
                array(
                    'div'=>array(
                        'class'=>'form-group col-xs-12',
                        ), 
                    'label'=> array(
                        'text' => __('In business since (YYYY):'), 
                        'class' => 'control-label',
                        ), 
                    'class' => 'form-control year',
                    'min' => '0',
                    'required' => true,
                ));
        
        echo $this->Form->input(
                'Contractor.number_of_employees', 
                array(
                    'div'=>array(
                        'class'=>'form-group col-xs-12',
                        ), 
                    'label'=> array(
                        'text' => __('Number of employees:'), 
                        'class' => 'control-label',
                        ), 
                    'class' => 'form-control',
                    'min' => '0',
                    'required' => true,
                ));
        
        echo $this->Form->input(
                'Contractor.business_licence', 
                array(
                    'div'=>array('class'=>'form-group col-xs-12'), 
                    'class' => 'radiobutton',
                    'before' => '<label for="ContractorBusinessLicence">' . __('Registered business with business licence?') . '</label><br />',
                    'after' =>  '',
                    'label' => false,
                    'legend' => false,
                    'fieldset' => false,
                    'type' => 'radio',
                    'options' => ['1' => __('Yes'), '0' => __('No')],
                    'required' => true,
                    ));
        
        $selectOption = ['0' => __('(choose a municipality)')];
        if (!empty($municipalities)) {
            asort($municipalities);
            $municipalities = $selectOption + $municipalities;
        } else {
            $municipalities = $selectOption;

        }
        echo $this->Form->input(
                'Contractor.municipality_id', 
                array(
                    'div'=>array('class'=>'form-group  col-xs-12'), 
                    'class' => 'form-control chosen-select',
                    'label'=> array('text' => __('Municipality'), 'class' => 'control-label'),
                    'type' => 'select',
                    'options' => $municipalities,
                    'required' => true,
                    ));
        
        
        echo $this->Form->input(
                'Contractor.work_safe_BC', 
                array(
                    'div'=>array('class'=>'form-group col-xs-12'), 
                    'class' => 'radiobutton',
                    'before' => '<label for="ContractorWorkSafeBC">' . __('Business in good standing with WorkSafe BC?') . '</label><br />',
                    'after' =>  '',
                    'label' => false,
                    'legend' => false,
                    'fieldset' => false,
                    'type' => 'radio',
                    'options' => ['1' => __('Yes'), '0' => __('No')],
                    'required' => true,
                    ));
        
        echo $this->Form->input(
                'Contractor.insurance_amount', 
                array(
                    'div'=>array(
                        'class'=>'form-group col-xs-12',
                        ), 
                    'label'=> array(
                        'text' => __('Amount of Liability Insurance Coverage:'), 
                        'class' => 'control-label',
                        ), 
                    'class' => 'form-control',
                    'min' => '0',
                    'required' => true,
                ));
        
        echo $this->Form->input(
                'Contractor.insurance_provider', 
                array(
                    'div'=>array('class'=>'form-group col-xs-12'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('Liability Insurance Provider:'), 'class' => 'control-label'),
                    'required' => true,
                    ));
        
        echo $this->Form->input(
                'Contractor.bbb_complaints', 
                array(
                    'div'=>array('class'=>'form-group col-xs-12'), 
                    'class' => 'radiobutton',
                    'before' => '<label for="ContractorBBBComplaints">' . __('Any BBB Complaints? (explain if yes)') . '</label><br />',
                    'after' =>  '',
                    'label' => false,
                    'legend' => false,
                    'fieldset' => false,
                    'type' => 'radio',
                    'options' => ['1' => __('Yes'), '0' => __('No')],
                    'required' => true,
                    ));
        
        echo $this->Form->input(
                'Contractor.bbb_observation', 
                array(
                    'div'=>array('class'=>'form-group col-xs-12'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('Observations about complaints:'), 'class' => 'control-label'),
                    ));
        
        echo $this->Element('forms/submit');
        echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']),
                '#company_disclosure', 
                array(
                    'escape' => false, 
                    'title' => __('Cancel'), 
                    'id' => 'hide_form_update_company_disclosure'
                    )
                );
        echo $this->Form->end();
        ?>
