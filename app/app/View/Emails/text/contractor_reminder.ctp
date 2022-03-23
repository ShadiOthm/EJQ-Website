Easy Job Quote
Finding clients is hard. We can help!
2562 Magnum Place
Victoria, BC V9B 6C9
Phone 250 590-8182
info@easyjobquote.com | www.easyjobquote.com

<?php echo __("Invoice #:"); ?> <?php echo sprintf("%04d", $invoiceInfo['Invoice']['number']); ?>
<?php echo __("Invoice Date:"); ?></strong> <?php echo $this->Time->format($invoiceInfo['Invoice']['issue_date'], '%b %d, %Y'); ?>
<?php echo __("Due Date:"); ?></strong> <?php echo $this->Time->format($invoiceInfo['Invoice']['due_date'], '%b %d, %Y'); ?>

 <?php echo __('Overdue Notice'); ?>
We noticed that your payment is overdue by <?php echo $invoiceInfo['Invoice']['due_days']; ?>  days. You need to pay the outstanding amount to regain access to the EJQ platform

