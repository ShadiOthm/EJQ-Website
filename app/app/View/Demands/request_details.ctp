<?php echo $this->Html->script(array('demands_request_details.js?v=0.0.0'), false); ?>
<?php echo $this->Html->script(array('invoices_details.js?v=0.0.0'), false); ?>
        <!-- row -->
        <div class="row">
            <!-- col 3 -->
            <div class="col-md-3">
                <?php echo $this->Element('requests/main_info'); ?>
            </div>
            <!-- /col 1 de 1 -->
            <!-- col 9 -->
            <div class="col-md-9">
            	
                <ul class="nav nav-tabs">
                    <li class="nav-item<?php echo (empty($activeTab) || $activeTab == 'description' ? ' active' : ''); 
                    ?>"><a class="nav-link" data-toggle="tab" href="#description" role="tab"><?php echo __('Description'); ?></a></li>
<?php
    if ($userRole == EJQ_ROLE_ADMIN && !empty($tenderInfo['Invoice'])):
?>
                    <li class="nav-item<?php echo ($activeTab == 'billing' ? ' active' : ''); 
                    ?>"><a class="nav-link" data-toggle="tab" href="#billing" role="tab"><?php echo __('Billing'); ?></a></li>
<?php
    endif;
?>
                </ul>
                
                <div class="tab-content">
                <?php echo $this->Element('demands/request_info'); ?>
<?php
    if ($userRole == EJQ_ROLE_ADMIN && !empty($tenderInfo['Invoice'])):
?>
                <?php echo $this->Element('tenders/billing'); ?>
<?php
    endif;
?>
                </div>

                 
            </div>
            <!-- /col 9 -->
        </div>
        <!-- /row -->