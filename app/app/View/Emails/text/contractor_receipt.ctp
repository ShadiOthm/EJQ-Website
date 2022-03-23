Easy Job Quote
Finding clients is hard. We can help!
2562 Magnum Place
Victoria, BC V9B 6C9
Phone 250 590-8182
info@easyjobquote.com | www.easyjobquote.com

<?php echo __("Invoice #:"); ?> <?php echo sprintf("%04d", $invoiceInfo['Invoice']['number']); ?>
<?php echo __("Invoice Date:"); ?></strong> <?php echo $this->Time->format($invoiceInfo['Invoice']['issue_date'], '%b %d, %Y'); ?>
<?php echo __("Receipt Date:"); ?></strong> <?php echo $this->Time->format($invoiceInfo['Invoice']['receipt_date'], '%b %d, %Y'); ?>

 <?php echo __('RECEIPT'); ?>
 <?php echo __("To:"); ?>
 <?php echo nl2br($invoiceInfo['Invoice']['invoice_to']); ?>
<?php echo __("For:"); ?>&nbsp;<?php echo nl2br($invoiceInfo['Invoice']['invoice_for']); ?>
        
<?php echo  __('Description'); ?>
<?php echo $invoiceInfo['Invoice']['service_description']; ?>

<?php echo __('Amount'); ?>
<?php 
                App::uses('CakeNumber', 'Utility');
                echo CakeNumber::format($invoiceInfo['Invoice']['service_value'], array(
                    'places' => 2,
                    'before' => 'CAD ',
                    'escape' => false,
                    'decimals' => '.',
                    'thousands' => ','
                ));                
                ?>
 
<?php echo __('GST'); ?>
<?php 
                App::uses('CakeNumber', 'Utility');
                echo CakeNumber::format($invoiceInfo['Invoice']['tax_value'], array(
                    'places' => 2,
                    'before' => 'CAD ',
                    'escape' => false,
                    'decimals' => '.',
                    'thousands' => ','
                ));                
                ?>

<?php echo __('Total'); ?>
<?php 
                App::uses('CakeNumber', 'Utility');
                echo CakeNumber::format($invoiceInfo['Invoice']['total_value'], array(
                    'places' => 2,
                    'before' => 'CAD ',
                    'escape' => false,
                    'decimals' => '.',
                    'thousands' => ','
                ));                
                ?>

THANK YOU FOR BEING A VALUABLE CONTRACTOR!
