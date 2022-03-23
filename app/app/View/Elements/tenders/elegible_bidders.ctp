<?php
    if (!empty($tenderInfo['Bidder'])):
?>
<table class="table table-striped" id="bidders">
                            <thead>
                                <tr>
                                    <th><?php echo  __('Contractor'); ?></th>
                                    <th class="th-control no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
    <?php
    $alias = 1;
    foreach ($tenderInfo['Bidder'] as $contractorId => $contractorName):
    ?>
                                <tr class='clickable-row' data-href="<?php
                        echo $this->Html->url(array(
                            "controller" => "providers",
                            "action" => "dashboard",
                            $contractorId,
                            $contractorName,
                        )); ?>">
                                    <td><?php echo $contractorName; ?></td>
                                    <td class="td-control"></td>
                                </tr>
    <?php endforeach; ?>

                            </tbody>
                        </table>
<?php
    else:
?>
<p><?php echo __('There are no qualified bidders for this tender.'); ?></p>
<?php
    endif;
?>

