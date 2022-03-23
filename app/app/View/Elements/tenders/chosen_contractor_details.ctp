                    <div class="content-box tab-pane fade<?php echo (empty($activeTab) || $activeTab == 'contractor' ? ' in active' : ''); ?>" id="description" role="tabpanel">   
                        <div class="col-md-9">
                                <div id="contact_info">
                                    <h4><?php echo __("Contact Info:"); ?></h4>
<?php
        if (!empty($chosenBid['Contractor']['contact_name'])):
?>                                    
                                <p id="name"><strong><?php echo __("Contact Name:"); ?></strong> <?php echo $chosenBid['Contractor']['contact_name']; ?></p>
<?php 
        endif;
?>                                    
<?php 
        if (!empty($chosenBid['Contractor']['contact_position'])):
?>                                    
                                <p id="position"><strong><?php echo __("Position:"); ?></strong> <?php echo $chosenBid['Contractor']['contact_position']; ?></p>
<?php 
        endif;
?>                                    
<?php 
        if (!empty($chosenBid['Contractor']['contact_address'])):
?>                                    
                                <p id="mailing_address"><strong><?php echo __("Mailing Address:"); ?></strong> <?php echo $chosenBid['Contractor']['contact_address']; ?></p>
<?php 
        endif;
?>                                    
<?php 
        if (!empty($chosenBid['Contractor']['contact_email'])):
?>                                    
                                <p id="email"><strong><?php echo __("Contact Email:"); ?></strong> <?php echo $chosenBid['Contractor']['contact_email']; ?></p>
<?php 
        endif;
?>                                    
<?php 
        if (!empty($chosenBid['Contractor']['phone'])):
?>                                    
                                <p id="name"><strong><?php echo __("Phone Number:"); ?></strong> <?php echo $chosenBid['Contractor']['phone']; ?></p>
<?php 
        endif;
?>                                    
                                </div>
                                <hr />
                        </div>                 	
                    </div>