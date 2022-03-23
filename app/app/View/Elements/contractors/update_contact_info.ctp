<h4><?php echo __("Contact Info:"); ?></h4>
 <?php
        echo $this->Form->create('Contractor', array('url'=>array('controller'=>'contractors', 'action' => 'update_contact_info'), 'tab' => 'my_info'));
        echo $this->Form->input('id');
        echo $this->Form->input(
                'Contractor.contact_name', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('Contact name:'), 'class' => 'control-label'),
                    'required' => true,
                    ));
        
        echo $this->Form->input(
                'Contractor.contact_position', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('Position:'), 'class' => 'control-label'),
                    'required' => true,
                    ));
        
                
        echo $this->Form->input(
                'Contractor.contact_email', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('Contact email:'), 'class' => 'control-label'),
                    ));
        
        echo $this->Form->input(
                'Contractor.phone', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control phone',
                    'label'=> array('text' => __('Phone number:'), 'class' => 'control-label'),
                    ));
        
        
        echo $this->Form->input(
                'Contractor.contact_address', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('Mailing address:'), 'class' => 'control-label'),
                    'type' => 'textarea',
                    'rows' => '3',
                    'required' => true,
                    ));
        
        
        echo $this->Element('forms/submit');
        echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']),
                '#phone', 
                array(
                    'escape' => false, 
                    'title' => __('Cancel'), 
                    'id' => 'hide_form_update_contact_info'
                    )
                );
        echo $this->Form->end();
        ?>
