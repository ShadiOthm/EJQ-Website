<table class="table table-striped" id="bids_made">
                            <thead>
                                <tr>
                                    <th><?php echo  __('Contractor'); ?></th>
                                    <th><?php echo __('Value'); ?></th>
                                    <th class="th-control no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
    <?php
    $alias = 1;
    foreach ($bids as $key => $bidData):
    ?>
                                <tr class='clickable-bid' data-href="<?php
                        echo $this->Html->url(array(
                            "controller" => "bids",
                            "action" => "details",
                            $bidData['id'],
                            $bidData['contractor_alias'],
                        )); ?>">
                                    <td><?php echo $bidData['contractor_alias']; ?><?php
if (empty($chosenBidId)
    && !empty($bidData['shortlisted'])
    && $bidData['shortlisted'] === TRUE
    ):
?>
&nbsp;<?php echo __('(Shortlisted)'); ?>
<?php
    endif;
?><?php
if (!empty($chosenBidId) && $bidData['id'] === $chosenBidId):
?>
&nbsp;<?php echo __('(Chosen bid)'); ?>
<?php
    endif;
?></td>
                                    <td><?php echo $bidData['value']; ?></td>
                                    <td class="td-control"></td>
                                </tr>
    <?php endforeach; ?>

                            </tbody>
                        </table>
