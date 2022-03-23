<h4><?php echo __("Licences and trade certificates:"); ?>
 <?php
        echo $this->Form->create('Contractor', array('url'=>array('controller'=>'contractors', 'action' => 'update_licences'), 'tab' => 'my_info'));
        echo $this->Form->input('id');
        echo $this->Form->input(
                'Contractor.licences_and_certifications', 
                array(
                    'div'=>array('class'=>'form-group col-xs-12'), 
                    'class' => 'form-control',
                    'rows' => '7',
                    'label'=> array('text' => __('Please list licences and trade certificates'), 'class' => 'control-label'),
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
