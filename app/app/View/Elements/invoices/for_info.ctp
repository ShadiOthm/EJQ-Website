<?php 
if ($tenderInfo['Invoice']['status'] == EJQ_INVOICE_STATUS_DRAFT && $canAccessAdm):
    $canEditInvoice = true;
else:
    $canEditInvoice = false;    
endif;

if ($canEditInvoice):
    echo $this->Element('invoices/update_for_form'); 
endif;
?>
<p id="for"><strong><?php echo __("For:"); ?></strong>&nbsp;<?php echo nl2br($tenderInfo['Invoice']['invoice_for']); ?>&nbsp;<?php
            if ($canEditInvoice):
                echo $this->Html->link(
                        $this->Html->image("/img/icon-edit.png"), 
                        '#show_update_for', 
                        array(
                            'escape' => false, 
                            'title' => __('change service title'), 
                            'id' => 'show_update_for',
                            ));
            endif;
 ?></p>