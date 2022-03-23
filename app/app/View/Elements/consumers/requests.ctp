                    <div class="content-box tab-pane fade<?php echo (empty($activeTab) || $activeTab == 'requests' ? ' in active' : ''); ?>" id="requests" role="tabpanel">   
<?php
if (isset($openDemands['0'])):
?>
        <div id="open-requests" class="row">
            <!-- col 1 de 1 -->
            <div class="col-md-12">

            	<h2><?php echo __('Requests'); ?></h2>
                
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><?php echo __('Identification'); ?></th>
                            <th><?php echo __('Data da solicitação'); ?></th>
                            <th><?php echo __('Visit Schedule'); ?></th>
                            <th><?php echo __('Status'); ?></th>
                            <th class="th-control no-sort"></th> 
                        </tr>
                    </thead>
                    <tbody>
    <?php
    foreach ($openDemands as $key => $tenderInfo):
                $servicesList = "";
                $sep = "";
                $demandId = $tenderInfo['Demand']['id'];
                $scheduledDate = "";
                $period = "";
                foreach ($tenderInfo['ServiceType'] as $stKey => $serviceData):
                    $servicesList .= $sep . $serviceData['name'];
                    $sep = ", ";
                    if (isset($serviceData['Schedule']['0'])) : 

                        foreach ($serviceData['Schedule'] as $schKey => $scheduleData):
                            if ($scheduleData['demand_id'] == $demandId):
                                $visitDate = $this->Time->format($scheduleData['schedule_period_begin'], '%b %d, %Y');
                                $endVisitDate = $this->Time->format($scheduleData['schedule_period_end'], '%b %d, %Y');
                                $windowBegin = $this->Time->format($scheduleData['schedule_period_begin'], '%I:%M %p');
                                $windowEnd = $this->Time->format($scheduleData['schedule_period_end'], '%I:%M %p');
                                if (($windowBegin == $windowEnd) && ($visitDate == $endVisitDate)):
                                    $period = sprintf(__('%s, %s'), $visitDate, $windowBegin); 
                                else:
                                    if ($visitDate == $endVisitDate):
                                        $period = sprintf(__('%s,  from %s to %s'), $visitDate, $windowBegin, $windowEnd);
                                    else: 
                                        $period = sprintf(__('From %s, %s to %s,  %s'), $visitDate, $windowBegin, $endVisitDate, $windowEnd);
                                    endif;
                                endif;

                            
                            endif;
                        endforeach;
                    endif;
                endforeach;
        
    ?>
                        <tr class="clickable-row" data-href="<?php 
                        echo $this->Html->url(array(
                            "controller" => "demands",
                            "action" => "request_details",
                            $demandId,
                        )); ?>">
        <td>
<?php echo $tenderInfo['Request']['title']; ?>
        </td>
        <td><?php
                echo $this->Time->format($tenderInfo['Demand']['created'], '%b %d, %Y');
        ?>
        </td>
        <td>
        <?php
                if (!empty($period)):
                    echo $period;
                else:
                    echo __("To be scheduled");
                endif;
                
      ?>          
        </td>
<td>
        <?php
            switch ($tenderInfo['Demand']['status']):
                case EJQ_DEMAND_STATUS_ESTIMATION_ASSIGNED:
                    ?><span class="label label-info2"><?php
                    echo __('Visit To Be Scheduled');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_WAITING_FOR_HOME_OWNER_SCHEDULE_APPROVAL:
                    ?><span class="label label-warning"><?php
                    echo __('Schedule Waiting For Home Owner Confirmation');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_WAITING_FOR_ESTIMATOR_SCHEDULE_APPROVAL:
                case EJQ_DEMAND_STATUS_ESTIMATION_READY_FOR_DISPATCH:
                    ?><span class="label label-info2"><?php
                    echo __('Schedule Waiting For Confirmation From Easy Job Quote');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_ESTIMATION_DISPATCHED:
                    ?><span class="label label-info2"><?php
                    echo __('Scheduled Visit');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_REQUEST_OPEN:
                    ?><span class="label label-info2"><?php
                    echo __('To be assigned');
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
                
            </div>
            <!-- /col 1 de 1 -->
        </div>

<?php
endif;
?>
                    </div>