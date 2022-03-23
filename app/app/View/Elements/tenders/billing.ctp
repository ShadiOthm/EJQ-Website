                   
                    <div class="content-box tab-pane fade<?php echo ($activeTab == 'billing' ? ' in active' : ''); ?>" id="billing" role="tabpanel">   
                        <div class="col-md-12">
                            <div class="content-box">
                                <?php
                                if(!empty($tenderInfo['Invoice'])):
                                    App::uses('CakeNumber', 'Utility');
                                ?>
                                <h4 class="form-title"><?php echo __("Invoices:"); ?></h4>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th><?php echo __('Payee'); ?></th>
                                            <th><?php echo __('For'); ?></th>
                                            <th><?php echo __('Value'); ?></th>
                                            <th><?php echo __('Status'); ?></th>
                                            <th class="th-control no-sort"></th> 
                                        </tr>
                                    </thead>
                                    <tbody>
<?php foreach ($tenderInfo['Invoice'] as $invoiceData): ?>
                                    <tr class="clickable-row" data-href="<?php
                        echo $this->Html->url(array(
                            "controller" => "invoices",
                            "action" => "details",
                            $invoiceData['Invoice']['id'],
                            $invoiceData['Invoice']['demand_id'],
                        )); ?>">
                                        <td><?php
                                        echo $invoiceData['Invoice']['payee']
                                        ?></td>        
                                        <td><?php
                                        echo $invoiceData['Invoice']['invoice_for']
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
                                        echo $invoiceData['Invoice']['status']
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
                        </div>
                    </div>