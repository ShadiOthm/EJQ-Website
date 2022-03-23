        <div class="content-box tab-pane fade<?php echo ($activeTab == 'in_progress' ? ' in active' : ''); ?>" id="in_progress" role="tabpanel">   
            <div class="col-md-12">
<?php
if (isset($openTenders['0'])):
?>
            	<h2><?php echo __('Tenders'); ?></h2>
                
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><?php echo __('Title'); ?></th>
                            <th><?php echo __('Status'); ?></th>
                            <th class="th-control no-sort"></th> 
                        </tr>
                    </thead>
                    <tbody>
    <?php
    foreach ($openTenders as $key => $tenderInfo):
    ?>
                        <tr class="clickable-row" data-href="<?php 
                        echo $this->Html->url(array(
                            "controller" => "tenders",
                            "action" => "details",
                            $tenderInfo['Tender']['id'],
                        )); ?>">
        <td>
        <?php

        echo $tenderInfo['Request']['title'];



?>
        </td>
        <td><?php
            switch ($tenderInfo['Demand']['status']):
                case EJQ_DEMAND_STATUS_TENDER_IN_PROGRESS:
                    ?><span class="label label-warning"><?php
                    echo __('Tender in progress');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_TENDER_TO_BE_MODIFIED:
                    ?><span class="label label-info2"><?php
                    echo __('Home Owner asked for modifications');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_TENDER_READY_FOR_SITE_ADMIN_APPROVAL:
                    ?><span class="label label-info2"><?php
                    echo __('Waiting for approval from Site Admin');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_TENDER_READY_FOR_HOME_OWNER_APPROVAL:
                    ?><span class="label label-info2"><?php
                    echo __('Waiting for approval from Home Owner');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_TENDER_READY_TO_BIDS:
                    ?><span class="label label-info2"><?php
                    echo __('Ready for Bidding');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS:
                    ?><span class="label label-info2"><?php
                    echo __('Open for Bidding');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_TENDER_CLOSED_TO_BIDS:
                    ?><span class="label label-info2"><?php
                    echo __('Closed to bids');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_TENDER_OPEN_FOR_BID_SELECTION:
                    ?><span class="label label-info2"><?php
                    echo __('Open for Bid Selection');
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
<h2><?php echo __('Open Tenders'); ?></h2>
<p><?php echo __('There are no open tenders'); ?></p>
<?php
endif;
?>
            </div>
            <!-- /col 1 de 1 -->
        </div>



