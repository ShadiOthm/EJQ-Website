<?php 
echo $this->Html->script(array('jquery.mask.min.js?v=0.0.0'), false); 
?>
<?php echo $this->Html->script(array('consumers_dashboard.js?v=0.0.0'), false); ?>
        <div class="row">
            <!-- /col 1 de 1 -->
            <!-- col 9 -->
            <div class="col-md-12">

                <ul class="nav nav-tabs">
<?php
if (!empty($openDemands)):
?>
                    <li class="nav-item<?php echo (empty($activeTab) || $activeTab == 'requests' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#requests" role="tab"><?php echo __('Requests'); ?></a></li>
<?php
    if(empty($activeTab)):
        $activeTab = 'requests';
    endif;
else:
    if(empty($activeTab)):
        $activeTab = 'tenders';
    endif;
endif;
?>
<?php
if (isset($tenders['0'])):
?>
                    <li class="nav-item<?php echo ($activeTab == 'tenders' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#tenders" role="tab"><?php echo __('Tenders'); ?></a></li>
<?php
else:
    if(empty($activeTab)):
        $activeTab = 'my_info';
    endif;
endif;
?>
                    <li class="nav-item<?php echo ($activeTab == 'my_info' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#my_info" role="tab"><?php echo __('My Info'); ?></a></li>
                </ul>

                <div class="tab-content">
                    <!-- Requests -->
<?php
if (!empty($openDemands)):
    echo $this->Element('consumers/requests', ['activeTab' => $activeTab]);
endif;
?>
                    <!-- Tenders -->
<?php
if (!empty($tenders)):
    echo $this->Element('consumers/tenders', ['activeTab' => $activeTab]);
endif;
?>
<!-- User Info -->
<?php echo $this->Element('consumers/my_info', ['activeTab' => $activeTab]); ?>

                </div>
            </div>
            <!-- /col 9 -->
        </div>


