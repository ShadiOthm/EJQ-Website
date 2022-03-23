        <!-- row -->
        <div class="row">
            <!-- col 3 -->
            <div class="col-md-3">
                <h3 class="form-title"><?php echo __('Basic Info'); ?> </h3>
               	<div class="content-box" style="font-size:13px;">
                    <p><strong><?php echo __("Name:"); ?></strong> <?php echo $tenderInfo['Contractor']['name']; ?></p>
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
                <?php
    if (!empty($tenderInfo['Job']['date_begin_home_owner'])):
?>
                    <p><strong><?php echo __("Home Owner Job Start Date:"); ?></strong><br /><?php echo $this->Time->format($tenderInfo['Job']['date_begin_home_owner'], '%b %d, %Y'); ?></p>
<?php
        endif;
?>
                <?php
    if (!empty($tenderInfo['Job']['date_end_home_owner'])):
?>
                    <p><strong><?php echo __("Home Owner Job End Date:"); ?></strong><br /><?php echo $this->Time->format($tenderInfo['Job']['date_end_home_owner'], '%b %d, %Y'); ?></p>
<?php
    endif;
?>
                <?php
    if (!empty($tenderInfo['Job']['date_begin_contractor'])):
?>
                    <p><strong><?php echo __("Contractor Job Start Date:"); ?></strong><br /><?php echo $this->Time->format($tenderInfo['Job']['date_begin_contractor'], '%b %d, %Y'); ?></p>
<?php
    endif;
?>
                <?php
    if (!empty($tenderInfo['Job']['date_end_contractor'])):
?>
                    <p><strong><?php echo __("Contractor Job End Date:"); ?></strong><br /><?php echo $this->Time->format($tenderInfo['Job']['date_end_contractor'], '%b %d, %Y'); ?></p>
<?php
    endif;
?>
                                <p id="phone"><strong><?php echo __("Phone number:"); ?></strong> <?php echo $tenderInfo['Consumer']['phone']; ?></p>

            <?php
                if (!empty($tenderInfo['Municipality']['name'])):
            ?>
                                <p><strong><?php echo __("Municipality"); ?>:</strong> <?php echo $tenderInfo['Municipality']['name']; ?></p>
            <?php
                endif;
            ?>
            <?php
                if (!empty($tenderInfo['Consumer']['address'])):
            ?>
                                <p id="address"><strong><?php echo __("Address:"); ?></strong><br /><?php echo nl2br(h($tenderInfo['Consumer']['address'])); ?></p>
            <?php
                endif;
            ?>
                    <p><strong><?php echo __("Job categories:"); ?></strong> <?php echo $tenderInfo['Demand']['services_list_description']; ?></p>
                </div>
            </div>
            <!-- /col 1 de 1 -->
            <!-- col 9 -->
            <div class="col-md-9">
                <ul class="nav nav-tabs">
                    <li class="nav-item<?php echo (empty($activeTab) || $activeTab == 'description' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#description" role="tab"><?php echo __('Description'); ?></a></li>
                    <li class="nav-item<?php echo ($activeTab == 'images' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#images" role="tab"><?php echo __('Images'); ?></a></li>
                    <li class="nav-item<?php echo ($activeTab == 'terms' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#terms" role="tab"><?php echo __("T's & C's"); ?></a></li>
                </ul>

                <div class="tab-content">
                    <!-- Description -->
                    <?php echo $this->Element('tenders/description'); ?>
                    <!-- Tender Images -->
                    <?php echo $this->Element('tenders/images'); ?>
                    <!-- Terms and Conditions -->
                    <?php echo $this->Element('tenders/terms_conditions_contracted'); ?>
                    <!-- Bids -->

                </div>
            </div>
            <!-- /col 9 -->
        </div>
        <!-- /row -->
