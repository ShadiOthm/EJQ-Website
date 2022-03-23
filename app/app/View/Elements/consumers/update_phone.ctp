<?php
        echo $this->Form->create('Consumer', array('url'=>array('controller'=>'consumers', 'action' => 'update_phone'), 'tab' => 'my_info'));
        echo $this->Form->input('id');
        echo $this->Form->input(
                'Consumer.phone', 
                array(
                    'value' => $consumer['Consumer']['phone'],
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control phone',
                    'label'=> array('text' => __('Phone numberx:'), 'class' => 'control-label'),
//                    'type' => 'textarea',
//                    'rows' => '2',
                    ));
        
        
        echo $this->Element('forms/submit');
        echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']),
                '#phone', 
                array(
                    'escape' => false, 
                    'title' => __('Cancel'), 
                    'id' => 'hide_form_update_phone'
                    )
                );
        echo $this->Form->end();
        ?>
