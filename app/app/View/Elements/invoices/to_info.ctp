<?php 
if ($tenderInfo['Invoice']['status'] == EJQ_INVOICE_STATUS_DRAFT && $canAccessAdm):
    $canEditInvoice = true;
else:
    $canEditInvoice = false;    
endif;

if ($canEditInvoice):
    echo $this->Element('invoices/update_to_form'); 
endif;
?>
<p id="to"><strong><?php echo __("To:"); ?></strong><br /> <?php echo nl2br($tenderInfo['Invoice']['invoice_to']); ?>&nbsp;<?php
if ($canEditInvoice):
    echo $this->Html->link(
            $this->Html->image("/img/icon-edit.png"), 
            '#show_update_to', 
            array(
                'escape' => false, 
                'title' => __('change address'), 
                'id' => 'show_update_to',
                ));
endif;
 ?></p>