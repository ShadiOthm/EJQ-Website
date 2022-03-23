        <div class="content-box tab-pane fade<?php echo (empty($activeTab) || $activeTab == 'requests' ? ' in active' : ''); ?>" id="requests" role="tabpanel">   


<?php
if (isset($openDemands['0'])):
    //debug($openDemands);
?>

            <!-- col 1 de 1 -->
            <div class="col-md-12">
            	<h2><?php echo __('Open Requests'); ?></h2>
                
                <table class="table table-striped" id="table_requests">
                    <thead>
                        <tr>
                            <th><?php echo __('Title'); ?></th>
                            <th><?php echo __('Home Owner'); ?></th>
                            <th><?php echo __('Request Date'); ?></th>
                            <th><?php echo __('Assigned Project Developer'); ?></th>
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
    foreach ($tenderInfo['ServiceType'] as $stKey => $serviceData):
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
                            <td><?php                 
                echo $tenderInfo['Tender']['title'];
                if (!empty($scheduledDate)):
                    echo "<br /><small> (" . sprintf(__(" para %s"), $scheduledDate) . ")</small>";
                endif;
 ?></td>
                            <td><?php
            if ((isset($tenderInfo['Consumer'])) && (!empty($tenderInfo['Consumer']['id']))):
                echo $tenderInfo['Consumer']['name'];
            else:
                echo __('N/D');
            endif;
                ?></td>
                            <td><?php
                echo $this->Time->format($tenderInfo['Demand']['created'], '%b %d, %Y');


        ?></td>
                            <td><?php
            if ((isset($tenderInfo['Provider'])) && (!empty($tenderInfo['Provider']['id']))):
                echo $tenderInfo['Provider']['name'];
            else:
                echo __('N/D');
            endif;
                ?></td>
                            <td><?php
            switch ($tenderInfo['Demand']['status']):
                case EJQ_DEMAND_STATUS_ESTIMATION_READY_FOR_DISPATCH:
                    ?><span class="label label-warning"><?php
                    echo __('Ready For Dispatch');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_ESTIMATION_DISPATCHED:
                    ?><span class="label label-info2"><?php
                    echo __('Visit Scheduled');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_ESTIMATION_ASSIGNED:
                    ?><span class="label label-info2"><?php
                    echo __('Project Developer Assigned');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_WAITING_FOR_HOME_OWNER_SCHEDULE_APPROVAL:
                    ?><span class="label label-info2"><?php
                    echo __('Schedule Waiting For Approval');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_WAITING_FOR_ESTIMATOR_SCHEDULE_APPROVAL:
                    ?><span class="label label-info2"><?php
                    echo __('Home Owner Waiting For Schedule Approval');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_REQUEST_OPEN:
                    ?><span class="label label-warning"><?php
                    echo __('To be Assigned');
                    ?></span><?php
                    break;
                default:
                    ?><span class="label label-info2"><?php
                    echo $tenderInfo['Demand']['status'];
                    ?></span><?php
                    break;
            endswitch;
?></td>
                            <td class="td-control">
<?php
//    $label = "";
//    $action = "";
//    switch ($tenderInfo['Demand']['status']):
//        case EJQ_DEMAND_STATUS_REQUEST_OPEN:
//        case EJQ_DEMAND_STATUS_ESTIMATION_ASSIGNED:
//        case EJQ_DEMAND_STATUS_ESTIMATION_READY_FOR_DISPATCH:
//        case EJQ_DEMAND_STATUS_ESTIMATION_DISPATCHED:
//            $label = __("Request's details");
//            $action = 'request_details';
//            $imagePath = '/img/view-details-icon.png';
//            break;
//
//    endswitch;
//
//    if (!empty($action)):
//        echo $this->Html->image(
//                $imagePath,
//                array(
//                    "title" => $label,
//                    'id'=> $demandId,
//                    'height' => "24px;",
//                    'class' => 'ico view_details',
//                    'url' => array('controller' => 'demands', 'action' => $action, $demandId)
//                    ));
//    endif;


?>&nbsp;
            <?php //echo $this->Element('demands/cancel', array('demand' => $tenderInfo['Demand'])); ?>
        </td>
                        </tr>
<?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- /col 1 de 1 -->









<?php
else:
?>
<section id="open_requests">
<h2><?php echo __('Open Requests'); ?></h2>
<p><?php echo __('There are no open requests'); ?></p>
</section>
<?php
endif;
?>
        </div>
