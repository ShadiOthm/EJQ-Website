<?php 
echo $this->Html->script(array('jquery.mask.min.js?v=0.0.0'), false); 
echo $this->Html->script(array('providers_contractor_profile.js?v=0.0.0'), false);
?>
        <div class="row">
            
<?php
if (!empty($notQualified)):
?>
    <div class="col-md-12 bg-info2">
                <p>You're not qualified to bid in Easy Job Quote</p>
                <p>If you have just register to become a member, please wait and we will contact you.</p>
                <p>If you already have been able to bid in Easy Job Quote, please contact us to understand the reason why you can't bid now.</p>
            </div>
<?php
endif;
?>
            
            
            <!-- /col 1 de 1 -->
            <!-- col 9 -->
            <div class="col-md-12">

                <ul class="nav nav-tabs">
<?php
    if (!empty($currentJobs)):
        if (empty($activeTab)):
            $activeTab = 'jobs';
        endif;
?>
                    <li class="nav-item<?php echo (empty($activeTab) || $activeTab == 'jobs' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#jobs" role="tab"><?php echo __('Current Jobs'); ?></a></li>
<?php
    endif;
?>
<?php
    if (!empty($availableTenders)):
        if (empty($activeTab)):
            $activeTab = 'tenders';
        endif;
?>
                    <li class="nav-item<?php echo (empty($activeTab) || $activeTab == 'tenders' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#tenders" role="tab"><?php echo __('Tenders'); ?></a></li>
<?php
    else:
        $activeTab = "my_info";
    endif;
?>
                    <li class="nav-item<?php echo ($activeTab == 'my_info' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#my_info" role="tab"><?php echo __('Company Info'); ?></a></li>
<?php
    if (!empty($invoices)):
?>
                    <li class="nav-item<?php echo (empty($activeTab) || $activeTab == 'invoices' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#invoices" role="tab"><?php echo __('Invoices'); ?></a></li>
<?php
    endif;
?>
                </ul>

                <div class="tab-content">
<?php
    if (!empty($currentJobs)):
?>
                    <?php echo $this->Element('providers/contractor_profile_current_jobs', ['activeTab' => $activeTab]); ?>
<?php                    
    endif;
    
?>
<?php
    if (!empty($availableTenders)):
?>
                    <?php echo $this->Element('providers/contractor_profile_available_tenders', ['activeTab' => $activeTab]); ?>
<?php                    
    endif;
    
?>
                    <?php echo $this->Element('providers/contractor_my_info', ['activeTab' => $activeTab]); ?>
<?php
    if (!empty($invoices)):
?>
                    <?php echo $this->Element('providers/contractor_profile_invoices', ['activeTab' => $activeTab]); ?>
<?php                    
    endif;
    
?>
                </div>
            </div>
            <!-- /col 9 -->
        </div>



