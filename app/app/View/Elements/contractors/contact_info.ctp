                        <div class="col-md-9">
                                <div class="content-box-edition<?php echo (empty($showContactInfo)?'': ' force_show') ?>" id="update_contact_info">
                                 <?php echo $this->Element('contractors/update_contact_info'); ?>
                                </div>
                                <div id="contact_info">
                                    <h4><?php echo __("Contact Info:"); ?><?php
                echo $this->Html->link(
                        $this->Html->image("/img/icon-edit.png", array('title' => __('update contact info'))), 
                        '#show_update_contact_info', 
                        array(
                            'escape' => false, 
                            'title' => __('Edit contact info'), 
                            'id' => 'show_update_contact_info',
                            ));
                 ?></h4>
<?php 
    if (empty($provider['Contractor']['0']['contact_name'])
        && empty($provider['Contractor']['0']['contact_position'])
        && empty($provider['Contractor']['0']['contact_email'])
        && empty($provider['Contractor']['0']['contact_address'])
        ):
?>                                    
                                <p id="name_position"><?php echo __("Please inform contact info"); ?></p>
<?php 
    else:
?>
<?php 
        if (!empty($provider['Contractor']['0']['contact_name'])):
?>                                    
                                <p id="name"><strong><?php echo __("Contact Name:"); ?></strong> <?php echo $provider['Contractor']['0']['contact_name']; ?></p>
<?php 
        endif;
?>                                    
<?php 
        if (!empty($provider['Contractor']['0']['contact_position'])):
?>                                    
                                <p id="position"><strong><?php echo __("Position:"); ?></strong> <?php echo $provider['Contractor']['0']['contact_position']; ?></p>
<?php 
        endif;
?>                                    
<?php 
        if (!empty($provider['Contractor']['0']['contact_address'])):
?>                                    
                                <p id="mailing_address"><strong><?php echo __("Mailing address:"); ?></strong> <?php echo $provider['Contractor']['0']['contact_address']; ?></p>
<?php 
        endif;
?>                                    
<?php 
        if (!empty($provider['Contractor']['0']['contact_email'])):
?>                                    
                                <p id="email"><strong><?php echo __("Contact Email:"); ?></strong> <?php echo $provider['Contractor']['0']['contact_email']; ?></p>
<?php 
        endif;
?>                                    
<?php 
        if (!empty($provider['Contractor']['0']['phone'])):
?>                                    
                                <p id="name"><strong><?php echo __("Phone number:"); ?></strong> <?php echo $provider['Contractor']['0']['phone']; ?></p>
<?php 
        endif;
?>                                    
<?php 
    endif;
?>                                    
                                </div>
                                <hr />
                        </div>