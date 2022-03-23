        <div class="content-box tab-pane fade<?php echo ($activeTab == 'contractors' ? ' in active' : ''); ?>" id="contractors" role="tabpanel">   
<?php
if (isset($contractors['0'])):
?>
            <!-- col 1 de 1 -->
            <div class="col-md-12">
            	<h2><?php echo __('Contractors'); ?></h2>
                
<table class="table table-striped" id="table_contractors">
                            <thead>
                                <tr>
                                    <th><?php echo  __('Name'); ?></th>
                                    <th><?php echo  __('Status'); ?></th>
                                    <th><?php echo  __('Rating'); ?></th>
                                    <th><?php echo  __('Jobs'); ?></th>
                                    <th><?php echo  __('Bids'); ?></th>
                                    <th class="th-control no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
    <?php
    $alias = 1;
    App::uses('CakeNumber', 'Utility');
    foreach ($contractors as $key => $contractorData):
    ?>
                        <tr class="clickable-row" data-href="<?php 
                        echo $this->Html->url([
                            "controller" => "providers",
                            "action" => "dashboard",
                            $contractorData['Provider']['id'],
                            "tab" => "my_info",
                        ]); ?>">
                            <td><?php
    echo $contractorData['Contractor']['0']['name'];
            ?></td>
                            <td><?php
            if ($contractorData['Provider']['qualified']):
                    echo __('Qualified for bidding');
            else:
                    echo __('Waiting for approval');
            endif;
                ?></td>
                            <td><?php
            if (!empty($contractorData['Provider']['average_overall_rating'])):
                echo CakeNumber::format($contractorData['Provider']['average_overall_rating'], [
                    'places' => 2,
                    'before' => false,
                    'escape' => false,
                    'decimals' => '.',
                    'thousands' => ','
                ]);                
            else:
                    echo '&nbsp;';
            endif;
                ?></td>
                            <td><?php
            if (!empty($contractorData['Provider']['total_jobs'])):
                    echo $contractorData['Provider']['total_jobs'];
            else:
                    echo __('0');
            endif;
                ?></td>
                            <td><?php
            if (!empty($contractorData['Provider']['total_bids'])):
                    echo $contractorData['Provider']['total_bids'];
            else:
                    echo __('0');
            endif;
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
<section id="contractors">
<h2><?php echo __('Contractors'); ?></h2>
<p><?php echo __('There are no contractors'); ?></p>
</section>
<?php
endif;
?>                
        </div>
