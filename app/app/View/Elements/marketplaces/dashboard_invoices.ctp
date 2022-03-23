        <div class="content-box tab-pane fade<?php echo ($activeTab == 'invoices' ? ' in active' : ''); ?>" id="invoices" role="tabpanel">   
<?php
if (isset($invoices['0'])):
?>
            <!-- col 1 de 1 -->
            <div class="col-md-12">
            	<h2><?php echo __('Invoices'); ?></h2>
                
<table class="table table-striped" id="table_invoices">
                            <thead>
                                <tr>
                                    <th><?php echo  __('Tender'); ?></th>
                                    <th><?php echo  __('Type'); ?></th>
                                    <th><?php echo  __('Status (days overdue)'); ?></th>
                                    <th class="th-control no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
    <?php
    $alias = 1;
    foreach ($invoices as $key => $invoiceData):
    ?>
                        <tr class="clickable-row" data-href="<?php 
                        echo $this->Html->url(array(
                            "controller" => "invoices",
                            "action" => "details",
                            $invoiceData['Invoice']['id'],
                            "tab" => "billing",
                        )); ?>">
                            <td><?php
    echo $invoiceData['Tender']['title'];
            ?></td>
                            <td><?php
            if ($invoiceData['Invoice']['type'] == EJQ_INVOICE_TYPE_TENDER_DEVELOPMENT):
                echo __('Tender Development');
            elseif ($invoiceData['Invoice']['type'] == EJQ_INVOICE_TYPE_COMMISSION):
                echo __('Commission');
            else:
                echo __('N/D');
            endif;
                ?></td>
                            <td><?php
            if ($invoiceData['Invoice']['status'] == EJQ_INVOICE_STATUS_SENT):
                if ($invoiceData['Invoice']['overdue_status'] == 'alert'):
                    echo ' <span class="' . $invoiceData['Invoice']['overdue_class'] . '">';
                    echo __('Overdue');
                    echo '&nbsp(' . $invoiceData['Invoice']['days_due'] . ')';
                    echo ' </span>';
                else:
                    echo __('Sent');
                endif;
                
            elseif ($invoiceData['Invoice']['status'] == EJQ_INVOICE_STATUS_PAID):
                echo __('Paid');
            else:
                echo $invoiceData['Invoice']['status'];
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
<section id="invoices">
<h2><?php echo __('Invoices'); ?></h2>
<p><?php echo __('There are no invoices'); ?></p>
</section>
<?php
endif;
?>                
        </div>
