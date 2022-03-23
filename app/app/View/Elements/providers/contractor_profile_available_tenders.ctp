        <div class="content-box tab-pane fade<?php echo (empty($activeTab) || $activeTab == 'tenders' ? ' in active' : ''); ?>" id="tenders" role="tabpanel">   
            <!-- col 1 de 1 -->
            <div class="col-md-12">
                <h2><?php echo __('Tenders open for bidding'); ?></h2>
<?php
if (isset($availableTenders['0'])):
?>                
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><?php echo __('Title'); ?></th>
                            <th><?php echo __('Job categories'); ?></th>
                            <th><?php echo __('Open until'); ?></th>
                            <th><?php echo __('Status'); ?></th>
                            <th class="th-control no-sort"></th> 
                        </tr>
                    </thead>
                    <tbody>
    <?php
    foreach ($availableTenders as $key => $tenderInfo):
        $demandId = $tenderInfo['Demand']['id'];
        $title = $tenderInfo['Tender']['title'];
    
        $servicesList = "";
        $sep = "";
        foreach ($tenderInfo['ServiceType'] as $stKey => $serviceData):
            $stId = $serviceData['id'];
            $servicesList .= $sep . $serviceData['name'];
            $sep = ", ";

            if (isset($tenderInfo['Schedule'][$stId])) :
                $explode = explode(" ",  $tenderInfo['Schedule'][$stId]['schedule']);
                $scheduled = sprintf(__(" para %s"), $explode[0]);
                $servicesList .= $scheduled;
            endif;
        endforeach;



    ?>
                        <tr class="clickable-row" data-href="<?php 
                        echo $this->Html->url(array(
                            "controller" => "tenders",
                            "action" => "contractor_view_tender",
                            $tenderInfo['Tender']['id'],
                        )); ?>">
        <td>
        <?php

        echo $title;



?>
        </td>
        <td><?php echo $servicesList; ?></td>
        <td><?php echo date("Y, M d", strtotime($tenderInfo['Tender']['close_to_bids_date'])); ?></td>
<td>
        <?php
            switch ($tenderInfo['Demand']['status']):
                case EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS:
                    if ((isset($tenderInfo['Tender']['already_bid'])) && ($tenderInfo['Tender']['already_bid'] === true)):
                    ?><span class="label label-info2"><?php
                        echo __('Already Bid');
                    ?></span><?php
                    else:
                    ?><span class="label label-success"><?php
                        echo __('Open To Bids');
                    ?></span><?php
                    endif;
                    break;
                default:
                    echo "&nbsp";
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
<p><?php echo __('There are no tenders open for bidding'); ?></p>
<?php
endif;
?>
            </div>
            <!-- /col 1 de 1 -->
        </div>
