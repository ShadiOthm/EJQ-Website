        <div class="content-box tab-pane fade<?php echo (empty($activeTab) || $activeTab == 'invoices' ? ' in active' : ''); ?>" id="invoices" role="tabpanel">   
            <?php
            if(!empty($invoices)):
                App::uses('CakeNumber', 'Utility');
            ?>
            <h4 class="form-title"><?php echo __("Invoices:"); ?></h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?php echo __('Tender'); ?></th>
                        <th><?php echo __('Value'); ?></th>
                        <th><?php echo  __('Status (days overdue)'); ?></th>
                        <th class="th-control no-sort"></th> 
                    </tr>
                </thead>
                <tbody>
<?php foreach ($invoices as $invoiceData): ?>
                <tr class="clickable-row" data-href="<?php
    echo $this->Html->url(array(
        "controller" => "invoices",
        "action" => "contractor_details",
        $invoiceData['Invoice']['id'],
        $invoiceData['Invoice']['demand_id'],
    )); ?>">
                    <td><?php
                    echo $invoiceData['Tender']['title']
                    ?></td>        
                    <td><?php
                        echo CakeNumber::format($invoiceData['Invoice']['total_value'], array(
                            'places' => 2,
                            'before' => 'CAD ',
                            'escape' => false,
                            'decimals' => '.',
                            'thousands' => ','
                        ));                
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
                    <td class="td-control">
                    </td>
                </tr>
<?php endforeach; ?>
                </tbody>
            </table>
            <?php
            else:
            ?>

            <?php
            endif;
            ?>



        </div>
