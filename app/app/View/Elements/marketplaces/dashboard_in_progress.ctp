        <div class="content-box tab-pane fade<?php echo ($activeTab == 'in_progress' ? ' in active' : ''); ?>" id="in_progress" role="tabpanel">   
<?php
if (isset($openTenders['0'])):
?>

            <!-- col 1 de 1 -->
            <div class="col-md-12">
            	<h2><?php echo __('Tenders in progress'); ?></h2>
                
                <table class="table table-striped" id="table_in_progress">
                    <thead>
                        <tr>
                            <th><?php echo __('Title'); ?></th>
                            <th><?php echo __('Home Owner'); ?></th>
                            <th><?php echo __('Assigned Project Developer'); ?></th>
                            <th><?php echo __('Status'); ?></th>
                            <th class="th-control no-sort"></th> 
                        </tr>
                    </thead>
                    <tbody>
<?php
foreach ($openTenders as $key => $tenderInfo):
    $servicesList = "";
    $sep = "";
    $demandId = $tenderInfo['Demand']['id'];
    $scheduledDate = "";
    foreach ($tenderInfo['ServiceType'] as $stKey => $serviceData):
        $servicesList .= $sep . $serviceData['name'];
        $sep = ", ";
    endforeach;

    if (!empty($scheduledDate)):
        echo "<br /><small> (" . sprintf(__(" para %s"), $scheduledDate) . ")</small>";
    endif;
?>
                        <tr class="clickable-row" data-href="<?php 
                        echo $this->Html->url(array(
                            "controller" => "tenders",
                            "action" => "details",
                            $tenderInfo['Tender']['id'],
                        )); ?>">
                            <td><?php
    echo $tenderInfo['Tender']['title'];
            ?></td>
                            <td><?php
            if ((isset($tenderInfo['Consumer'])) && (!empty($tenderInfo['Consumer']['id']))):
                echo $tenderInfo['Consumer']['name'];
            else:
                echo __('N/D');
            endif;
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
                case EJQ_DEMAND_STATUS_TENDER_IN_PROGRESS:
                    ?><span class="label label-info2"><?php
                    echo __('Tender in progress');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_TENDER_READY_FOR_SITE_ADMIN_APPROVAL:
                    ?><span class="label label-warning"><?php
                    echo __('Waiting for approval from Site Admin');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_TENDER_TO_BE_MODIFIED:
                    ?><span class="label label-warning"><?php
                    echo __('Home Owner asked for modifications');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_TENDER_READY_FOR_HOME_OWNER_APPROVAL:
                    ?><span class="label label-info2"><?php
                    echo __('Waiting for approval from Home Owner');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_TENDER_READY_TO_BIDS:
                    ?><span class="label label-warning"><?php
                    echo __('Ready for Bidding');
                    ?></span><?php
                    break;
                default:
                    ?><span class="label label-info2"><?php
                    echo $tenderInfo['Demand']['status'];
                    ?></span><?php
                    break;
            endswitch;
?></td>
                            <td class="td-control"></td>
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
<h2><?php echo __('Tenders in Progress'); ?></h2>
<p><?php echo __('There are no tenders in progress'); ?></p>
</section>
<?php
endif;
?>
        </div>
        <!-- /row -->

            