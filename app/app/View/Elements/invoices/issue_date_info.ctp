<?php 
if ($tenderInfo['Invoice']['status'] == EJQ_INVOICE_STATUS_DRAFT && $canAccessAdm):
    $canEditInvoice = true;
else:
    $canEditInvoice = false;    
endif;

if ($canEditInvoice):
    echo $this->Element('invoices/update_issue_date_form'); 
endif;
?>
<?php
if (empty($tenderInfo['Invoice']['issue_date'])):
    $actionLabel = __('define invoice date');
    $actionIcon = "icon-add.png";
else:
    //$actionLabel = __('change date');
    $actionLabel = "";
    $actionIcon = "icon-edit.png";
endif;
?>
<p id="issue_date">
            <?php
                if (!empty($tenderInfo['Invoice']['issue_date'])):
            ?>
                    <strong><?php echo __("Invoice Date:"); ?></strong> <?php echo $this->Time->format($tenderInfo['Invoice']['issue_date'], '%b %d, %Y'); ?>&nbsp;<?php
                endif;
            ?><?php
            if ($canEditInvoice):
                echo $this->Html->link(
                        $this->Html->image("/img/$actionIcon") . $actionLabel, 
                        '#show_update_issue_date', 
                        array(
                            'escape' => false, 
                            'title' => $actionLabel, 
                            'id' => 'show_update_issue_date',
                            ));
            endif;
 ?></p>