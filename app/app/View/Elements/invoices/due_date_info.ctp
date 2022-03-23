<?php
if ($tenderInfo['Invoice']['status'] == EJQ_INVOICE_STATUS_DRAFT && $canAccessAdm):
    $canEditInvoice = true;
else:
    $canEditInvoice = false;    
endif;

if ($tenderInfo['Invoice']['status'] != EJQ_INVOICE_STATUS_PAID && !empty($tenderInfo['Invoice']['due_date'])):
?>
<?php 
if ($canEditInvoice):
    echo $this->Element('invoices/update_due_date_form'); 
endif;
?>
<p id="due_date">
            <?php
                if (!empty($tenderInfo['Invoice']['due_date'])):
            ?>
                    <strong><?php echo __("Due Date:"); ?></strong> <?php echo $this->Time->format($tenderInfo['Invoice']['due_date'], '%b %d, %Y'); ?>&nbsp;<?php
                endif;
            ?><?php
            if ($canEditInvoice):
                $actionLabel = "";
                $actionIcon = "icon-edit.png";
                echo $this->Html->link(
                        $this->Html->image("/img/$actionIcon") . $actionLabel, 
                        '#show_update_due_date', 
                        array(
                            'escape' => false, 
                            'title' => $actionLabel, 
                            'id' => 'show_update_due_date',
                            ));
            endif;
 ?></p>
<?php
endif;
?>