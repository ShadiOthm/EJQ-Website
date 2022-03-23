<div class="content-box-edition" id="update_due_date">
 <?php
        echo $this->Form->create('Invoice', array('url'=>array('controller'=>'invoices', 'action' => 'update_due_date'), 'id' => 'update_due_date'));
        echo $this->Form->input('id');
        echo $this->Form->input('Demand.id');
        echo $this->Form->input('Tender.id');
        echo $this->Form->input('Consumer.id');
        echo $this->Form->hidden('Invoice.type');
echo $this->Form->input("Invoice.due_date", array(
    'type' => 'text',
    'after' => '<label for="InvoiceDueDate" class="badge add-on">' . __('show calendar') . '</label>',
    'div'=>array(
        'class'=>'form-group input-append date',
        'id' => 'invoice_due_date',
        ), 
    'label'=>array(
        'text' => __('Define Due Date'), 
        'class' => 'control-label'
        ), 
    'date-format' => "MM-dd-yyyy", 
    'class' => 'form-control', 
    'readonly' => true,
    'placeholder' => __('MM-DD-YYYY'),
    'required' => true,
    ));
        echo $this->Element('forms/submit');
        echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#', array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_update_due_date'));
        echo $this->Form->end();
        ?>
</div>
