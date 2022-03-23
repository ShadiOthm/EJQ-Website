        <div class="content-box tab-pane fade<?php echo ($activeTab == 'closed_to_bids' ? ' in active' : ''); ?>" id="closed_to_bids" role="tabpanel">   
<?php
if (isset($closedToBids['0'])):
?>

            <!-- col 1 de 1 -->
            <div class="col-md-12">
            	<h2><?php echo __('Tenders Closed For Bidding'); ?></h2>
                
                <table class="table table-striped" id="table_closed_to_bids">
                    <thead>
                        <tr>
                            <th><?php echo __('Title'); ?></th>
                            <th><?php echo __('Home Owner'); ?></th>
                            <th><?php echo __('Bids'); ?></th>
                            <th><?php echo __('Tender by'); ?></th>
                            <th><?php echo __('Status'); ?></th>
                            <th class="th-control no-sort"></th> 
                        </tr>
                    </thead>
                    <tbody>
<?php
foreach ($closedToBids as $key => $tenderInfo):
    $servicesList = "";
    $sep = "";
    $demandId = $tenderInfo['Demand']['id'];
    $scheduledDate = "";
    if (!empty($tenderInfo['Bid'])):    
        $totalBids = count($tenderInfo['Bid']);
    else:
        $totalBids = 0;
    endif;
    foreach ($tenderInfo['ServiceType'] as $stKey => $serviceData):
        $servicesList .= $sep . $serviceData['name'];
        $sep = ", ";

    endforeach;

?>
                        <tr class="clickable-row" data-href="<?php 
                        echo $this->Html->url(array(
                            "controller" => "tenders",
                            "action" => "details",
                            $tenderInfo['Tender']['id'],
                        )); ?>">
                            <td><?php
    echo $tenderInfo['Tender']['title'];
    if (!empty($scheduledDate)):
        echo "<br /><small> (" . sprintf(__(" para %s"), $scheduledDate) . ")</small>";
    endif;

            ?></td>
                            <td>        <?php
            if ((isset($tenderInfo['Consumer'])) && (!empty($tenderInfo['Consumer']['id']))):
                echo $tenderInfo['Consumer']['name'];
            else:
                echo __('N/D');
            endif;
                ?></td>
                            <td><?php
                echo $totalBids;
                

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
                case EJQ_DEMAND_STATUS_CONTRACT_SIGNED:
                    ?><span class="label label-info2"><?php
                    echo __('Contract Signed');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_BID_DISCLOSED:
                    ?><span class="label label-warning"><?php
                    echo __('Waiting for Contract Signing');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_TENDER_CLOSED_TO_BIDS:
                    ?><span class="label label-warning"><?php
                    echo __('Closed to Bids');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_TENDER_OPEN_FOR_BID_SELECTION:
                    ?><span class="label label-info2"><?php
                    echo __('Open For Bid Selection');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_BID_SELECTED:
                    ?><span class="label label-warning"><?php
                    echo __('Bid Selected');
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
            <!-- /col 1 de 1 -->
            </div>
<?php
else:
?>
            <div class="col-md-12">
<h2><?php echo __('Tenders Closed for Bidding'); ?></h2>
<p><?php echo __('There are no tenders closed to bids'); ?></p>
            </div>
<?php
endif;
?>
        </div>
        <!-- /row -->
