<div class="content-box-edition" id="update_receipt_date">
 <?php
        echo $this->Form->create('Invoice', array('url'=>array('controller'=>'invoices', 'action' => 'update_receipt_date'), 'id' => 'update_receipt_date'));
        echo $this->Form->input('id');
        echo $this->Form->input('Demand.id');
        echo $this->Form->hidden('Invoice.type');
echo $this->Form->input("Invoice.receipt_date", array(
    'type' => 'text',
    'after' => '<label for="InvoiceReceiptDate" class="badge add-on">' . __('show calendar') . '</label>',
    'div'=>array(
        'class'=>'form-group input-append date',
        'id' => 'invoice_receipt_date',
        ), 
    'label'=>array(
        'text' => __('Define Receipt Date'), 
        'class' => 'control-label'
        ), 
    'date-format' => "MM/dd/yyyy", 
    'class' => 'form-control', 
    'readonly' => true,
    'placeholder' => __('MM/DD/YYYY'),
    'required' => true,
    ));
        echo $this->Element('forms/submit');
        echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#', array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_update_receipt_date'));
        echo $this->Form->end();
        ?>
</div>
