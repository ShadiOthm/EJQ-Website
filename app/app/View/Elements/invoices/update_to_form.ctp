<div class="content-box-edition hidden" id="update_to">
 <?php
        echo $this->Form->create('Invoice', array('url'=>array('controller'=>'invoices', 'action' => 'update_to'), 'id' => 'update_to'));
        echo $this->Form->input('id');
        echo $this->Form->input('Demand.id');
        echo $this->Form->input('Tender.id');
        echo $this->Form->input('Consumer.id');
        echo $this->Form->hidden('Invoice.type');
        echo $this->Form->input(
                'invoice_to', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('To'), 'class' => 'control-label'),
                    'type' => 'textarea',
                    'rows' => '3',
                    ));
        
        echo $this->Element('forms/submit');
        echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#', array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_update_to'));
        echo $this->Form->end();
        ?>
</div>