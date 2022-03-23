        <div class="content-box tab-pane fade<?php echo ($activeTab == 'jobs' ? ' in active' : ''); ?>" id="jobs" role="tabpanel">   
<?php
if (isset($jobs['0'])):
?>

            <!-- col 1 de 1 -->
            <div class="col-md-12">
            	<h2><?php echo __('Jobs in Progress or Completed'); ?></h2>
                
                <table class="table table-striped" id="table_jobs">
                    <thead>
                        <tr>
                            <th><?php echo __('Title'); ?></th>
                            <th><?php echo __('Home Owner'); ?></th>
                            <th><?php echo __('Contractor'); ?></th>
                            <th><?php echo __('Status'); ?></th>
                            <th class="th-control no-sort"></th> 
                        </tr>
                    </thead>
                    <tbody>
<?php
foreach ($jobs as $key => $tenderInfo):
    $servicesList = "";
    $sep = "";
    $demandId = $tenderInfo['Demand']['id'];

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
                            <td>        <?php
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
                case EJQ_DEMAND_STATUS_BID_DISCLOSED:
                    ?><span class="label label-warning"><?php
                    echo __('Bid Accepted');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_CONTRACT_SIGNED:
                    ?><span class="label label-info2"><?php
                    echo __('Contract Signed');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_JOB_IN_PROGRESS:
                    ?><span class="label label-info2"><?php
                    echo __('Job In Progress');
                    ?></span><?php
                    break;
                case EJQ_DEMAND_STATUS_JOB_COMPLETED:
                    ?><span class="label label-warning"><?php
                    echo __('Job Completed');
                    ?></span><?php
                    break;
                default:
                    echo $tenderInfo['Demand']['status'];
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
<h2><?php echo __('Jobs'); ?></h2>
<p><?php echo __('There are no jobs in progress'); ?></p>
            </div>
<?php
endif;
?>
        </div>
        <!-- /row -->
