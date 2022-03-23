        <div class="content-box tab-pane fade<?php echo (empty($activeTab) || $activeTab == 'requests' ? ' in active' : ''); ?>" id="requests" role="tabpanel">   
            <!-- col 1 de 1 -->
            <div class="col-md-12">
<?php

if (isset($requestsToTender['0'])):
    //debug($requestsToTender);
?>


            	<h2><?php echo __('Requests'); ?></h2>
                
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><?php echo __('Title'); ?></th>
                            <th><?php echo __('Job Categories'); ?></th>
                            <th><?php echo __('Schedule'); ?></th>
                            <th><?php echo __('Status'); ?></th>
                            <th class="th-control no-sort"></th> 
                        </tr>
                    </thead>
                    <tbody>
    <?php
    foreach ($requestsToTender as $key => $tenderInfo):
        $demandId = $tenderInfo['Demand']['id'];
        $servicesList = "";
        $sep = "";
        foreach ($tenderInfo['ServiceType'] as $stKey => $serviceData):
            $stId = $serviceData['id'];
            $servicesList .= $sep . $serviceData['name'];
            $sep = ", ";

        endforeach;

    ?>
                        <tr class="clickable-row" data-href="<?php 
                        echo $this->Html->url(array(
                            "controller" => "demands",
                            "action" => "request_details",
                            $demandId,
                        )); ?>">
        <td>
        <?php

        echo $tenderInfo['Request']['title'];



?>
        </td>
        <td>
        <?php

        echo $servicesList;



?>
        </td>
        <td><?php
        if (!empty($tenderInfo['Schedule'])):
            $schedule = array_values($tenderInfo['Schedule'])[0];
            $visitDate = $this->Time->format($schedule['schedule_period_begin'], '%b %d, %Y');
            $windowBegin = $this->Time->format($schedule['schedule_period_begin'], '%I:%M %p');
            $windowEnd = $this->Time->format($schedule['schedule_period_end'], '%I:%M %p');
            if ($windowBegin == $windowEnd):
                $period = sprintf(__('%s, %s'), $visitDate, $windowBegin); 
            else:
                $period = sprintf(__('%s,  from %s to %s'), $visitDate, $windowBegin, $windowEnd); 
            endif;
            
            echo $period;
        endif;
?>
        </td>
        <td><?php
            switch ($tenderInfo['Demand']['status']):
                case EJQ_DEMAND_STATUS_ESTIMATION_ASSIGNED:
                    ?><span class="label label-warning"><?php
                    echo __('Visit To Be Scheduled');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_ESTIMATION_READY_FOR_DISPATCH:
                    ?><span class="label label-info2"><?php
                    echo __('Ready for Dispatch');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_WAITING_FOR_HOME_OWNER_SCHEDULE_APPROVAL:
                    ?><span class="label label-info2"><?php
                    echo __('Schedule Waiting For Confirmation');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_WAITING_FOR_ESTIMATOR_SCHEDULE_APPROVAL:
                    ?><span class="label label-warning"><?php
                    echo __('Home Owner Waiting For Schedule Approval');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_ESTIMATION_DISPATCHED:
                    ?><span class="label label-warning"><?php
                    echo __('Visit Scheduled');
                    ?></span><?php
                    break;
                default:
                    ?><span class="label label-info2"><?php
                    echo $tenderInfo['Demand']['status'];
                    ?></span><?php
                    break;
            endswitch;
?>
        </td>
                            <td class="td-control">
        </td>
                        </tr>
<?php endforeach; ?>





                    </tbody>
                </table>








<?php
else:
?>
<h2><?php echo __('Open Requests'); ?></h2>
<p><?php echo __('There are no open requests'); ?></p>
<?php
endif;
?>
            </div>
            <!-- /col 1 de 1 -->
        </div>

