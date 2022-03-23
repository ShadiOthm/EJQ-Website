<!-- header -->
<header>
    <div>
        <div style="margin-right: -15px;  margin-left: -15px;">
            <div style="width: 100%; margin-right: -15px;  margin-left: -15px; box-sizing: border-box; list-style: none; padding: 0; margin: 0; display: block">
                <div style="width: 66.666666667%; float: left; position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; display: block; box-sizing: border-box; list-style: none; padding: 0; margin: 0;">
                    <h1>Easy Job Quote</h1>
                    <h2>Finding clients can be hard. We can help!</h2>
                    <p>2562 Magnum Place<br />
                    Victoria, BC V9B 6C9<br />
                    Phone 250 590-8182<br />
                    <a href="mailto:info@easyjobquote.com">info@easyjobquote.com</a> | <a href="https://www.easyjobquote.com">www.easyjobquote.com</a>
                    </p>
                </div>
                <div style="width: 33.33333333%; float: left; position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; display: block; box-sizing: border-box; list-style: none; padding: 0; margin: 0;">
                    <img border=0 src="cid:ejq-logo-invoice">
                    <p><strong><?php echo __("Invoice #:"); ?></strong> <?php echo sprintf("%04d", $invoiceInfo['Invoice']['number']); ?></p>
                    <p id="issue_date"><strong><?php echo __("Invoice Date:"); ?></strong> <?php echo $this->Time->format($invoiceInfo['Invoice']['issue_date'], '%b %d, %Y'); ?></p>
                    <p id="issue_date"><strong><?php echo __("Due Date:"); ?></strong> <?php echo $this->Time->format($invoiceInfo['Invoice']['due_date'], '%b %d, %Y'); ?></p>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- section -->
<div id="section-admin" style="width: 100%;">
    <!-- container -->
    <div id="main-content" style="font-weight: 100; width: 100%; height: 100%; color: #555555; font-size: 15px; line-height: 1.42857143; float: left">
        <h1><?php echo __('Overdue Notice'); ?></h1>
        <p>We noticed that your payment is overdue by <?php echo $invoiceInfo['Invoice']['days_due']; ?>  days. You need to pay the outstanding amount to regain access to the EJQ platform</p>
        
    </div>
    <!-- /container -->



<!-- footer -->
<footer class="footer" role="contentinfo">            
    <div class="container">
        <div  style="width: 100%; float: left; position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; display: block; box-sizing: border-box; list-style: none; padding: 0; margin: 0;">
            <?php echo $invoiceInfo['Invoice']['info']; ?>
        </div>
    </div> 
</footer>
<!-- /footer -->

</div>
<!-- /section -->

