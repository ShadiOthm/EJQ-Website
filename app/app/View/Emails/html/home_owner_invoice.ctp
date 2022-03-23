<!-- header -->
<header>
    <div>
        <div style="margin-right: -15px;  margin-left: -15px;">
            <div style="width: 100%; margin-right: -15px;  margin-left: -15px; box-sizing: border-box; list-style: none; padding: 0; margin: 0; display: block">
                <div style="width: 66.666666667%; float: left; position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; display: block; box-sizing: border-box; list-style: none; padding: 0; margin: 0;">
                    <h1>Easy Job Quote</h1>
                    <h2>Finding a contractor is stressful. We can help!</h2>
                    <p>2562 Magnum Place<br />
                    Victoria, BC V9B 6C9<br />
                    Phone 250 590-8182<br />
                    <a href="mailto:info@easyjobquote.com">info@easyjobquote.com</a> | <a href="https://www.easyjobquote.com">www.easyjobquote.com</a>
                    </p>
                </div>
                <div style="width: 33.33333333%; float: left; position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; display: block; box-sizing: border-box; list-style: none; padding: 0; margin: 0;">
                    <img border=0 src="cid:ejq-logo-invoice">
                    <p><strong><?php echo __("Invoice #:"); ?></strong> <?php echo sprintf("%04d", $invoiceInfo['Invoice']['number']); ?></p>
                    <p id="issue_date"><strong><?php echo __("Date:"); ?></strong> <?php echo $this->Time->format($invoiceInfo['Invoice']['issue_date'], '%b %d, %Y'); ?></p>
                    <p><strong><?php echo __("Tender #:"); ?></strong> <?php echo $invoiceInfo['Invoice']['tender_title']; ?></p>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- section -->
<div id="section-admin">
    <!-- container -->
    <div id="main-content" style="font-weight: 100; width: 100%; height: 100%; color: #555555; font-size: 15px; line-height: 1.42857143">
        <div style="margin-right: -15px;  margin-left: -15px; box-sizing: border-box; list-style: none; padding: 0; margin: 0; display: block">
            <div  style="width: 100%; float: left; position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; display: block; box-sizing: border-box; list-style: none; padding: 0; margin: 0;">
                <h1><?php echo __('INVOICE'); ?></h1>
                <div style="width: 33.33333333%; float: left; position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; display: block; box-sizing: border-box; list-style: none; padding: 0; margin: 0;">
                    <p id="to"><strong><?php echo __("To:"); ?></strong><br /> <?php echo nl2br($invoiceInfo['Invoice']['invoice_to']); ?></p>
                </div>
                <div  style="width: 66.666666667%; float: left; position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; display: block; box-sizing: border-box; list-style: none; padding: 0; margin: 0;">
                    <p id="for"><strong><?php echo __("For:"); ?></strong>&nbsp;<?php echo nl2br($invoiceInfo['Invoice']['invoice_for']); ?></p>
                </div>
            </div>
        </div>
        
        <div style="margin-right: -15px;  margin-left: -15px; box-sizing: border-box; list-style: none; padding: 0; margin: 0; display: block">
            <div  style="width: 100%; float: left; position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; display: block; box-sizing: border-box; list-style: none; padding: 0; margin: 0;">
            <table style="width: 100%;  font-size: 14px; margin-bottom: 50px; display: table;" id="services">
                <thead style="display: table-header-group; border-color: inherit;">
                    <tr>
                        <th style="width: 75%"><p style="text-align: left;"><?php echo  __('Description'); ?></p></th>
                        <th style="width: 25%"><p style="text-align: right;"><?php echo __('Amount'); ?></p></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 75%"><?php echo $invoiceInfo['Invoice']['service_description']; ?></td>
                        <td style="width: 25%"><p style="text-align: right;"><?php 
                App::uses('CakeNumber', 'Utility');
                echo CakeNumber::format($invoiceInfo['Invoice']['service_value'], array(
                    'places' => 2,
                    'before' => 'CAD ',
                    'escape' => false,
                    'decimals' => '.',
                    'thousands' => ','
                ));                
                ?></p></td>
                    </tr>
                    <tr>
                        <td style="width: 75%"><?php echo __('GST'); ?></td>
                        <td style="width: 25%"><p style="text-align: right;"><?php 
                App::uses('CakeNumber', 'Utility');
                echo CakeNumber::format($invoiceInfo['Invoice']['tax_value'], array(
                    'places' => 2,
                    'before' => 'CAD ',
                    'escape' => false,
                    'decimals' => '.',
                    'thousands' => ','
                ));                
                ?></p></td>
                    </tr>
                    <tr>
                        <td style="width: 75%"><strong><?php echo __('Total'); ?></strong></td>
                        <td style="width: 25%"><p style="text-align: right;"><?php 
                App::uses('CakeNumber', 'Utility');
                echo CakeNumber::format($invoiceInfo['Invoice']['total_value'], array(
                    'places' => 2,
                    'before' => 'CAD ',
                    'escape' => false,
                    'decimals' => '.',
                    'thousands' => ','
                ));                
                ?></p></td>
                    </tr>
                </tbody>
            </table>

            </div>
        </div>

    </div>
    <!-- /container -->



<!-- footer -->
<footer class="footer" role="contentinfo">            
    <div class="container">
        <div  style="width: 100%; float: left; position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; display: block; box-sizing: border-box; list-style: none; padding: 0; margin: 0;">
            <?php echo $invoiceInfo['Invoice']['info']; ?>
            <h4>THANK YOU FOR LETTING US HELP WITH YOUR HOME IMPROVEMENT!</h4>
        </div>
    </div> 
</footer>
<!-- /footer -->

</div>
<!-- /section -->

