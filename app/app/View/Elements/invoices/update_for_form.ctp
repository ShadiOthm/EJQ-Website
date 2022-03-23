<div class="content-box-edition" id="update_for">
 <?php
        echo $this->Form->create('Invoice', array('url'=>array('controller'=>'invoices', 'action' => 'update_for'), 'id' => 'update_for'));
        echo $this->Form->input('id');
        echo $this->Form->input('Demand.id');
        echo $this->Form->input('Tender.id');
        echo $this->Form->input('Consumer.id');
        echo $this->Form->hidden('Invoice.type');
        echo $this->Form->input(
                'invoice_for', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('For'), 'class' => 'control-label'),
                    'type' => 'textarea',
                    'rows' => '3',
                    ));
        
        echo $this->Element('forms/submit');
        echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#', array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_update_for'));
        echo $this->Form->end();
        ?>
</div>
