        <div class="content-box tab-pane fade<?php echo ($activeTab == 'consumers' ? ' in active' : ''); ?>" id="consumers" role="tabpanel">   
<?php
if (isset($consumers['0'])):
?>
            <!-- col 1 de 1 -->
            <div class="col-md-12">
            	<h2><?php echo __('Home Owners'); ?></h2>
                
<table class="table table-striped" id="table_consumers">
                            <thead>
                                <tr>
                                    <th><?php echo  __('Name'); ?></th>
                                    <th class="th-control no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
    <?php
    $alias = 1;
    foreach ($consumers as $key => $consumerData):
    ?>
                        <tr class="clickable-row" data-href="<?php 
                        echo $this->Html->url(array(
                            "controller" => "consumers",
                            "action" => "dashboard",
                            $consumerData['Consumer']['id'],
                            "tab" => "my_info",
                        )); ?>">
                            <td><?php
    echo $consumerData['Consumer']['name'];
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
<section id="consumers">
<h2><?php echo __('Project Developers'); ?></h2>
<p><?php echo __('There are no project developers'); ?></p>
</section>
<?php
endif;
?>                
        </div>
