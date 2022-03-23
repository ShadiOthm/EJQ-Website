    			<h3 class="form-title"><?php echo __("Main info"); ?></h3>
               	<div class="content-box" style="font-size:13px;">
                
                    <p><strong><?php echo __("Home Owner:"); ?></strong> <?php echo $tenderInfo['Consumer']['name']; ?></p>
                <?php
    if ($canAccessAdm || $isDemandEstimator):
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
    if (!empty($tenderInfo['Consumer']['address'])):
?>
                    <p><strong><?php echo __("Address:"); ?></strong><br /><?php echo nl2br($tenderInfo['Consumer']['address']); ?></p>
<?php
    else:
?>
                    <p><strong><?php echo __("Address Not Informed"); ?></strong></p>
<?php
    endif;
?>

                <?php
    if (!empty($tenderInfo['Municipality']['name'])):
?>
                    <p><strong><?php echo __("Municipality"); ?>:</strong> <?php echo $tenderInfo['Municipality']['name']; ?></p>
<?php
    else:
?>
                    <p><strong><?php echo __("Municipality Not Informed"); ?></strong></p>
<?php
    endif;
?>
<?php
    if (($userRole == EJQ_ROLE_ADMIN) && 
        (!empty($rights['service_types']))):
            echo $this->Element('requests/update_service_types');
    endif;
?>
        
                <?php
    if (($userRole == EJQ_ROLE_ADMIN || $userRole == EJQ_ROLE_ESTIMATOR) && 
        (!empty($tenderInfo['Demand']['services_list_description']))):
?>
                    <p><strong><?php echo __("Job categories:"); ?></strong> <?php echo $tenderInfo['Demand']['services_list_description']; ?>
<?php
        if (($userRole == EJQ_ROLE_ADMIN) && 
            (!empty($rights['service_types']))):
            echo $this->Html->link(
                    $this->Html->image("/img/icon-edit.png"), 
                    $rights['service_types']['href'], 
                    array(
                        'escape' => false, 
                        'title' => __('Change Job Categories'), 
                        'id' => $rights['service_types']['id'],
                    ));
            
        endif;
?>
<?php
    else:
?>
<?php
        if (($userRole == EJQ_ROLE_ADMIN) && 
            (!empty($rights['service_types']))):
            echo $this->Html->link(
                    $this->Html->image("/img/icon-add.png") . '&nbsp;' . __('Set Job Categories'), 
                    $rights['service_types']['href'],
                    array(
                        'escape' => false, 
                        'title' => __('Set Job Categories'), 
                        'id' => $rights['service_types']['id']
                    ));
        endif;
    endif;
?>
</p>


<?php
    if (($userRole == EJQ_ROLE_ADMIN) && 
        (!empty($rights['estimator']))):
            echo $this->Element('requests/define_estimator');
    endif;
?>


                    <p id="estimator_info"><strong><?php echo __("Project Developer:"); ?></strong> <?php echo $estimatorsMessage; ?>

<?php
    if (($userRole == EJQ_ROLE_ADMIN) && 
        (!empty($rights['estimator']))):
?>
                <?php
                
        if (!empty($tenderInfo['Provider']['id'])):
?>
            <?php         
            echo $this->Html->link(
                    $this->Html->image("/img/icon-edit.png"), 
                    $rights['estimator']['href'], 
                    array(
                        'escape' => false, 
                        'title' => __('Change Project Developer'), 
                        'id' => $rights['estimator']['id'],
                    ));
            ?>
<?php
        else:
            echo '<br /><span>';
            echo $this->Html->link(
                    $this->Html->image("/img/icon-add.png") . '&nbsp;' . __('Define Project Developer'), 
                    $rights['estimator']['href'],
                    array(
                        'escape' => false, 
                        'title' => __('Define Project Developer'), 
                        'id' => $rights['estimator']['id'],
                    ));
            echo '</span>';
        endif;
    endif;
?>

                    </p>





                <?php
    if (($userRole == EJQ_ROLE_ADMIN || $userRole == EJQ_ROLE_ESTIMATOR) && 
        (!empty($visitTimeMessage))):
?>
                    <p><strong><?php echo __("Visit Time Suggestion:"); ?></strong> <?php echo $visitTimeMessage; ?></p>
<?php
    endif;
?>
                </div>     
           
