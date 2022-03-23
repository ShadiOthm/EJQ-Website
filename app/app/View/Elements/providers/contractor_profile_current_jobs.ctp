        <div class="content-box tab-pane fade<?php echo (empty($activeTab) || $activeTab == 'jobs' ? ' in active' : ''); ?>" id="jobs" role="tabpanel">   
            <!-- col 1 de 1 -->
            <div class="col-md-12">
                <h2><?php echo __('Contracted Jobs'); ?></h2>
<?php
if (isset($currentJobs['0'])):
?>                
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><?php echo __('Title'); ?></th>
                            <th><?php echo __('Home Owner'); ?></th>
                            <th><?php echo __('Job categories'); ?></th>
                            <th><?php echo __('Value'); ?></th>
                            <th><?php echo __('Status'); ?></th>
                            <th class="th-control no-sort"></th> 
                        </tr>
                    </thead>
                    <tbody>
    <?php
    foreach ($currentJobs as $key => $tenderInfo):
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
                            "action" => "chosen_bid",
                            $tenderInfo['Tender']['id'],
                        )); ?>">
        <td>
        <?php

        echo $title;



?>
        </td>
        <td><?php echo $tenderInfo['Consumer']['name']; ?></td>
        <td><?php echo $servicesList; ?></td>
        <td><?php echo $tenderInfo['Bid']['value']; ?></td>
<td>
        <?php
            switch ($tenderInfo['Demand']['status']):
                case EJQ_DEMAND_STATUS_BID_DISCLOSED:
                    ?><span class="label label-info2"><?php
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
<p><?php echo __('There are no tenders open for bidding'); ?></p>
<?php
endif;
?>
            </div>
            <!-- /col 1 de 1 -->
        </div>
