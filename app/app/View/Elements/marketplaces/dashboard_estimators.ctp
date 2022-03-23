        <div class="content-box tab-pane fade<?php echo ($activeTab == 'estimators' ? ' in active' : ''); ?>" id="estimators" role="tabpanel">   
<?php
if (isset($estimators['0'])):
?>
            <!-- col 1 de 1 -->
            <div class="col-md-12">
            	<h2><?php echo __('Project Developers'); ?></h2>
                
<table class="table table-striped" id="table_estimators">
                            <thead>
                                <tr>
                                    <th><?php echo  __('Name'); ?></th>
                                    <th><?php echo  __('Status'); ?></th>
                                    <th class="th-control no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
    <?php
    $alias = 1;
    foreach ($estimators as $key => $estimatorData):
    ?>
                        <tr class="clickable-row" data-href="<?php 
                        echo $this->Html->url(array(
                            "controller" => "providers",
                            "action" => "dashboard",
                            $estimatorData['Provider']['id'],
                            "tab" => "my_info",
                        )); ?>">
                            <td><?php
    echo $estimatorData['Provider']['name'];
            ?></td>
                            <td><?php
            if ($estimatorData['Provider']['qualified']):
                    echo __('Qualified for visiting');
            else:
                    echo __('Waiting for approval');
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
<section id="estimators">
<h2><?php echo __('Project Developers'); ?></h2>
<p><?php echo __('There are no project developers'); ?></p>
</section>
<?php
endif;
?>                
        </div>
