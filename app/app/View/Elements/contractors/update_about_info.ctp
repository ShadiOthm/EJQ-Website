<h4><?php echo __("Company Description:"); ?></h4>
 <?php
        echo $this->Form->create('Contractor', array('url'=>array('controller'=>'contractors', 'action' => 'update_about_info'), 'tab' => 'my_info'));
        echo $this->Form->input('id');
        echo $this->Form->input(
                'Contractor.name', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('Company name:'), 'class' => 'control-label'),
                    'required' => true,
                    ));
        
        echo $this->Form->input(
                'Contractor.about', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('Description:'), 'class' => 'control-label'),
                    ));
                
        echo $this->Element('forms/submit');
        echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']),
                '#company_disclosure', 
                array(
                    'escape' => false, 
                    'title' => __('Cancel'), 
                    'id' => 'hide_form_update_about_info'
                    )
                );
        echo $this->Form->end();
        ?>
