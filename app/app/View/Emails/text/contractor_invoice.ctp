Easy Job Quote
Finding clients is hard. We can help!
2562 Magnum Place
Victoria, BC V9B 6C9
Phone 250 590-8182
info@easyjobquote.com | www.easyjobquote.com

<?php echo __("Invoice #:"); ?> <?php echo sprintf("%04d", $invoiceInfo['Invoice']['number']); ?>
<?php echo __("Invoice Date:"); ?> <?php echo $this->Time->format($invoiceInfo['Invoice']['issue_date'], '%b %d, %Y'); ?>
<?php echo __("Tender #:"); ?> <?php echo $invoiceInfo['Invoice']['tender_title']; ?>
<?php echo __("Bid Amount:"); ?> <?php 
App::uses('CakeNumber', 'Utility');
echo CakeNumber::format($invoiceInfo['Invoice']['bid_amount'], array(
'places' => 2,
'before' => 'CAD ',
'escape' => false,
'decimals' => '.',
'thousands' => ','
));                
?>
<?php echo __("Home Owner Address:"); ?> <?php echo $invoiceInfo['Invoice']['home_owner_address']; ?>

 <?php echo __('INVOICE'); ?>
 <?php echo __("To:"); ?>
 <?php echo $invoiceInfo['Invoice']['invoice_to']; ?>
<?php echo __("For:"); ?>&nbsp;<?php echo $invoiceInfo['Invoice']['invoice_for']; ?>
        
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
                echo CakeNumber::format($invoiceInfo['Invoice']['service_value'], array(
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

<?php echo $invoiceInfo['Invoice']['info']; ?>
THANK YOU FOR BEING A VALUABLE CONTRACTOR!
