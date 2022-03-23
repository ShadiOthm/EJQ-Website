        <div class="content-box tab-pane fade<?php echo ($activeTab == 'open_to_bids' ? ' in active' : ''); ?>" id="open_to_bids" role="tabpanel">   
<?php
if (isset($openToBids['0'])):
?>

            <!-- col 1 de 1 -->
            <div class="col-md-12">
            	<h2><?php echo __('Tenders Open for Bidding'); ?></h2>
                
                <table class="table table-striped" id="table_open_to_bids">
                    <thead>
                        <tr>
                            <th><?php echo __('Title'); ?></th>
                            <th><?php echo __('Home Owner'); ?></th>
                            <th><?php echo __('Bidding Period'); ?></th>
                            <th class="text-center"><?php echo __('Submitted Bids'); ?></th>
                            <th class="text-center"><?php echo __('In Progress'); ?></th>
                            <th class="text-center"><?php echo __('Visits'); ?></th>
                            <th><?php echo __('Status'); ?></th>
                            <th class="th-control no-sort"></th> 
                        </tr>
                    </thead>
                    <tbody>
<?php
foreach ($openToBids as $key => $tenderInfo):
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
                            <td><?php
            if ((isset($tenderInfo['Consumer'])) && (!empty($tenderInfo['Consumer']['id']))):
                echo $tenderInfo['Consumer']['name'];
            else:
                echo __('N/D');
            endif;
                ?></td>
                            <td><?php
                echo $this->Time->format($tenderInfo['Tender']['open_to_bids_date'], '%b %d, %Y')
                        . ' - ' . $this->Time->format($tenderInfo['Tender']['close_to_bids_date'], '%b %d, %Y');


        ?></td>
                            <td class="text-center"><?php
                echo $tenderInfo['Demand']['submitted_bids'];
                

        ?></td>
                            <td class="text-center"><?php
                echo $tenderInfo['Demand']['in_progress_bids'];
                

        ?></td>
                            <td class="text-center"><?php
                echo $tenderInfo['Demand']['visits'];
                

        ?></td>
                            <td><?php
            switch ($tenderInfo['Demand']['status']):
                case EJQ_DEMAND_STATUS_TENDER_BIDDING_SCHEDULED:
                    ?><span class="label label-info2"><?php
                    echo __('Scheduled');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS:
                    ?><span class="label label-success"><?php
                    echo __('Open to bids');
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
            <div class="col-md-12">
<h2><?php echo __('Tenders Open for Bidding'); ?></h2>
<p><?php echo __('There are no tenders open to bids'); ?></p>
            </div>
<?php
endif;
?>
        </div>

