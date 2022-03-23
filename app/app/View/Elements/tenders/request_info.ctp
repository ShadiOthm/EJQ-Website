
                <h3 class="form-title">Info</h3>

               	<div class="content-box" style="font-size:13px;">

                    <p><strong><?php echo __("Home Owner:"); ?></strong> <?php echo $tenderInfo['Consumer']['name']; ?></p>
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
    if ($canAccessAdm || !empty($isDemandEstimator) || !empty($tenderInfo['Tender']['disclosed_bid'])):
        if (!empty($tenderInfo['Consumer']['phone'])):
?>
                    <p><strong><?php echo __("Phone number:"); ?></strong> <?php echo $tenderInfo['Consumer']['phone']; ?></p>
<?php
        endif;
?>
<?php
        if (!empty($tenderInfo['User']['email'])):
?>
                    <p><strong><?php echo __("Email:"); ?></strong> <?php echo $this->Html->link($tenderInfo['User']['email'], 'mailto:' . $tenderInfo['User']['email']); ?></p>
<?php
        endif;
    endif;
    
    ?>

<?php
        if (!empty($tenderInfo['Tender']['disclosed_bid'])):
            if (!empty($chosenBid['Contractor']['name'])):
?>
                    <p><strong><?php echo __("Contractor:"); ?></strong> <?php echo $chosenBid['Contractor']['name']; ?></p>
<?php
            endif;
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
                    <p><strong><?php echo __("Project Developer:"); ?></strong> <?php echo $tenderInfo['Provider']['name']; ?></p>

                    <p><strong><?php echo __("Job categories:"); ?></strong> <?php echo $tenderInfo['Demand']['services_list_description']; ?></p>
<?php
    if (!empty($rights['service_types'])):
?>
                            <div class="control" id="control_update_service_types">
                                <?php
            echo $this->Html->link(
                    $this->Html->image("/img/icon-edit.png", array('title' => __('edit'))),
                    '#show_update_service_types',
                    array(
                        'escape' => false,
                        'title' => __('Change'),
                        'id' => 'show_update_service_types',
                        ));
             ?>
                            </div>
<?php
    endif;
?>

<?php
    if (!empty($tenderInfo['Municipality']['name'])):
?>
                    <p><strong><?php echo __("Municipality"); ?>:</strong> <?php echo $tenderInfo['Municipality']['name']; ?></p>
<?php
    endif;
?>
                    <p><strong><?php echo __("Description by Home Owner:"); ?></strong><br /><?php echo nl2br(h($tenderInfo['Request']['description'])); ?></p>

                </div>

