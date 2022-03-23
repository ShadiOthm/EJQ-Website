<?php 
echo $this->Html->script('chosen.jquery.min.js?v=1.7.0'); 
echo $this->Html->script('common.js?v=0.0.0'); 

echo $this->Html->script('//cdn.jsdelivr.net/momentjs/latest/moment.min.js');
//echo $this->Html->script('//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js');
//echo $this->Html->css('//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css');
echo $this->Html->script('bootstrap-datetimepicker.min.js');
echo $this->Html->css('bootstrap-datetimepicker.min.css');

echo $this->Html->script(array('demands_request_info.js?v=0.0.0'), false);
?>
    <div class="content-box tab-pane fade<?php echo (empty($activeTab) || $activeTab == 'description' ? ' in active' : ''); ?>" id="description" role="tabpanel">   
        <h2><?php echo $tenderInfo['Request']['title']; ?></h2>
        <?php
            if ($tenderInfo['Demand']['status'] == EJQ_DEMAND_STATUS_REQUEST_OPEN
            || $tenderInfo['Demand']['status'] == EJQ_DEMAND_STATUS_ESTIMATION_ASSIGNED
            || $tenderInfo['Demand']['status'] == EJQ_DEMAND_STATUS_ESTIMATION_READY_FOR_DISPATCH
            || $tenderInfo['Demand']['status'] == EJQ_DEMAND_STATUS_WAITING_FOR_ESTIMATOR_SCHEDULE_APPROVAL
            || $tenderInfo['Demand']['status'] == EJQ_DEMAND_STATUS_WAITING_FOR_HOME_OWNER_SCHEDULE_APPROVAL
            || $tenderInfo['Demand']['status'] == EJQ_DEMAND_STATUS_ESTIMATION_DISPATCHED):
        ?>
<?php
            $periodLabel = "";
            $showPeriod = false;
            if (!empty($tenderInfo['Schedule']['period'])):
                switch ($tenderInfo['Demand']['status'] ):
                    case EJQ_DEMAND_STATUS_WAITING_FOR_ESTIMATOR_SCHEDULE_APPROVAL:
                            $periodLabel = __('Schedule suggested by Home Owner');
                        break;
                    
                    case EJQ_DEMAND_STATUS_WAITING_FOR_HOME_OWNER_SCHEDULE_APPROVAL:
                            $periodLabel = __('Schedule suggested by Project Developer');
                        break;
                    
                    case EJQ_DEMAND_STATUS_ESTIMATION_READY_FOR_DISPATCH:
                            $periodLabel = __('Ready To Dispatch Project Developer');
                        break;
                    
                    case EJQ_DEMAND_STATUS_ESTIMATION_DISPATCHED:
                            $periodLabel = __('Visit Scheduled Date and Time');
                        break;
                    
                    default:
                        $periodLabel = __('Scheduled Visit Time');
                        break;
                    
                endswitch;
?>
    <div id="schedule_info">
        
        <h3><?php echo $periodLabel ?></h3>
        <p><?php echo($tenderInfo['Schedule']['period']) ; ?></p>
    </div>
        <?php
                endif;
            endif;
        ?>
        <h3><?php echo __("Description by Home Owner:"); ?></h3>
        <p><?php echo nl2br(h($tenderInfo['Request']['description'])); ?></p>
        
        
    </div>
