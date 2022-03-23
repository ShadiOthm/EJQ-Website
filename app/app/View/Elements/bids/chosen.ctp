<?php
    if ((!empty($tenderInfo['Bid']['status']) && 
            ($tenderInfo['Bid']['status'] == EJQ_DEMAND_STATUS_BID_DISCLOSED ||
            $tenderInfo['Bid']['status'] == EJQ_DEMAND_STATUS_CONTRACT_SIGNED ||
            $tenderInfo['Bid']['status'] == EJQ_DEMAND_STATUS_JOB_IN_PROGRESS ||
            $tenderInfo['Bid']['status'] == EJQ_DEMAND_STATUS_JOB_COMPLETED)
            )
            || $canAccessAdm):
?>
        <div class="row" id="chosen">
                <div class="col-md-12">
                     <h4><?php echo sprintf(__('Bid by %s'), $bidInfo['Contractor']['name']); ?></h4>
<?php 
        if (!empty($bidInfo['Contractor']['contact_name'])):
?>                                    
                                <p id="name"><strong><?php echo __("Contact Name:"); ?></strong> <?php echo $bidInfo['Contractor']['contact_name']; ?></p>
<?php 
        endif;
?>                                    
<?php 
        if (!empty($bidInfo['Contractor']['contact_position'])):
?>                                    
                                <p id="position"><strong><?php echo __("Position:"); ?></strong> <?php echo $bidInfo['Contractor']['contact_position']; ?></p>
<?php 
        endif;
?>                                    
<?php 
        if (!empty($bidInfo['Contractor']['contact_address'])):
?>                                    
                                <p id="mailing_address"><strong><?php echo __("Mailing address:"); ?></strong> <?php echo $bidInfo['Contractor']['contact_address']; ?></p>
<?php 
        endif;
?>                                    
<?php 
        if (!empty($bidInfo['Contractor']['contact_email'])):
?>                                    
                                <p id="email"><strong><?php echo __("Contact Email:"); ?></strong> <?php echo $bidInfo['Contractor']['contact_email']; ?></p>
<?php 
        endif;
?>                                    
<?php 
        if (!empty($bidInfo['Contractor']['phone'])):
?>                                    
                                <p id="name"><strong><?php echo __("Phone number:"); ?></strong> <?php echo $bidInfo['Contractor']['phone']; ?></p>
<?php 
        endif;
?>                                    
                </div>
        </div>
        <hr />
<?php
    endif;
?>

