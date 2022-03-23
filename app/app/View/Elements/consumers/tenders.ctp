    <div class="content-box tab-pane fade<?php echo ($activeTab == 'tenders' ? ' in active' : ''); ?>" id="tenders" role="tabpanel">   
<?php
if (isset($tenders['0'])):
?>
        <div id="open-requests" class="row">
            <!-- col 1 de 1 -->
            <div class="col-md-12">

            	<h2><?php echo __('Tenders'); ?></h2>
                
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><?php echo __('Identification'); ?></th>
                            <th><?php echo __('Status'); ?></th>
                            <th class="th-control no-sort"></th> 
                        </tr>
                    </thead>
                    <tbody>
    <?php
    foreach ($tenders as $key => $tenderInfo):
                $servicesList = "";
                $sep = "";
                $demandId = $tenderInfo['Demand']['id'];
                
                if (!empty($tenderInfo['Bid'])):    
                    $totalBids = count($tenderInfo['Bid']);
                else:
                    $totalBids = 0;
                endif;
                
    ?>
                        <tr class="clickable-row" data-href="<?php 
                        echo $this->Html->url(array(
                            "controller" => "tenders",
                            "action" => "details",
                            $tenderInfo['Tender']['id'],
                        )); ?>">
        <td>
<?php echo $tenderInfo['Tender']['title']; ?>
        </td>
        <td>
<?php             
            switch ($tenderInfo['Demand']['status']):
                case EJQ_DEMAND_STATUS_TENDER_IN_PROGRESS:
                case EJQ_DEMAND_STATUS_TENDER_READY_FOR_SITE_ADMIN_APPROVAL:
                    ?><span class="label label-info2"><?php
                    echo __('Tender in progress');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_TENDER_READY_FOR_HOME_OWNER_APPROVAL:
                    ?><span class="label label-warning"><?php
                    echo __('Tender to be approved');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_TENDER_TO_BE_MODIFIED:
                    ?><span class="label label-info2"><?php
                    echo __('Tender to be modified');
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
                    echo __('Closed for Bidding');
                    if ($userRole == EJQ_ROLE_ADMIN):
                        echo "&nbsp;($totalBids&nbsp;" . __("bids") . ")";
                    endif;
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_TENDER_OPEN_FOR_BID_SELECTION:
                    ?><span class="label label-warning"><?php
                    echo __('Open for Bid Selection');
                    if ($userRole == EJQ_ROLE_ADMIN || $userRole == EJQ_ROLE_HOME_OWNER):
                        echo "&nbsp;($totalBids&nbsp;" . __("bids") . ")";
                    endif;
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_CLOSED:
                    ?><span class="label label-info2"><?php
                    echo __('Closed');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_BID_SELECTED:
                    ?><span class="label label-info2"><?php
                    echo __('Bid Selected');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_BID_DISCLOSED:
                    ?><span class="label label-warning"><?php
                    echo __('Bid Accepted');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_CONTRACT_SIGNED:
                    ?><span class="label label-warning"><?php
                    echo __('Contract Signed');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_JOB_IN_PROGRESS:
                    ?><span class="label label-warning"><?php
                    echo __('Job In Progress');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_JOB_COMPLETED:
                    ?><span class="label label-info2"><?php
                    echo __('Job Completed');
                    ?></span><?php
                    break;
                default:
                    ?><span class="label label-warning"><?php
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