<?php 
echo $this->Html->script(array('providers_estimator_profile.js?v=0.0.0'), false);
?>


        <div class="row">
            <div class="col-md-12">

                <ul class="nav nav-tabs">
                    <li class="nav-item<?php echo (empty($activeTab) || $activeTab == 'requests' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#requests" role="tab"><?php echo __('Requests'); ?></a></li>
                    <li class="nav-item<?php echo ($activeTab == 'in_progress' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#in_progress" role="tab"><?php echo __('Tenders In Progress'); ?></a></li>
                    <li class="nav-item<?php echo ($activeTab == 'my_info' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#my_info" role="tab"><?php echo __('My Info'); ?></a></li>
                </ul>

                <div class="tab-content">
                <?php echo $this->Element('providers/estimator_requests_for_tenders'); ?>
                <?php echo $this->Element('providers/estimator_open_tenders'); ?>
                <?php echo $this->Element('providers/estimator_my_info'); ?>

                </div>
            </div>
            <!-- /col 9 -->
        </div>

    
    
    
    







