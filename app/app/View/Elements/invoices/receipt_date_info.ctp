<?php
if ($tenderInfo['Invoice']['status'] == EJQ_INVOICE_STATUS_PAID && $canAccessAdm):
    $canEditReceiptDate = true;
else:
    $canEditReceiptDate = false;    
endif;

if ($tenderInfo['Invoice']['status'] == EJQ_INVOICE_STATUS_PAID):
?>
<?php 
if ($canEditReceiptDate):
    echo $this->Element('invoices/update_receipt_date_form'); 
endif;
?>
<?php
if (empty($tenderInfo['Invoice']['receipt_date'])):
    $actionLabel = __('define receipt date');
    $actionIcon = "icon-add.png";
else:
    //$actionLabel = __('change date');
    $actionLabel = "";
    $actionIcon = "icon-edit.png";
endif;
?>
<p id="receipt_date">
            <?php
                if (!empty($tenderInfo['Invoice']['receipt_date'])):
            ?>
                    <strong><?php echo __("Receipt Date:"); ?></strong> <?php echo $this->Time->format($tenderInfo['Invoice']['receipt_date'], '%b %d, %Y'); ?>&nbsp;<?php
                endif;
            ?><?php
            if ($canEditReceiptDate):
                echo $this->Html->link(
                        $this->Html->image("/img/$actionIcon") . $actionLabel, 
                        '#show_update_receipt_date', 
                        array(
                            'escape' => false, 
                            'title' => $actionLabel, 
                            'id' => 'show_update_receipt_date',
                            ));
            endif;
 ?></p>
<?php endif; ?>