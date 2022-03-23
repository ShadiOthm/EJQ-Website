                    
                    <div class="content-box tab-pane fade<?php echo ($activeTab == 'billing' ? ' in active' : ''); ?>" id="billing" role="tabpanel">   
                        <header class="bg-info">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12" id="bid_value">
                                    <h1 style="color: white;"><?php echo __("Tender Development Invoice"); ?></h1>
                                    <h2><?php
                if ($tenderInfo['Invoice']['status'] == EJQ_INVOICE_STATUS_SENT):
            ?>
                        <?php echo __('SENT'); ?>
            <?php
                elseif ($tenderInfo['Invoice']['status'] == EJQ_INVOICE_STATUS_PAID):
            ?>
                        <?php echo __('PAID'); ?>
            <?php
                endif;
            ?></h2>
                                </div>		
<?php
    if (!empty($invoiceActions)):
?>
                                        <div class="col-md-6 col-sm-6 col-xs-12 text-right">
<?php
foreach ($invoiceActions as $keyAction => $action):
?>    
<?php    
                echo $this->Form->create('Invoice', array('novalidate' => true, 'url'=>$action['href'], 'id' => $action['id']));
                echo $this->Form->input('id');
                echo $this->Form->input('Demand.id');
                echo $this->Form->input('Tender.id');
                echo $this->Form->submit($action['label'], array(
//                        'div'=>array('class'=>'form-group'), 
                        'class' => 'text-right btn btn-admin btn-sm',
                        'alt' => $action['label'],
                        'title' => $action['label'],
                    
                        ));
                echo $this->Form->end();
?>
    
<?php
endforeach;
?>                                    
                                        </div>	
<?php
                endif;
            ?>                        
                                </div>
                        </header>
                        <br clear="all" />
                        <div class="row">
                            <div class="col-md-6">
                            <?php echo $this->Element('invoices/to_info'); ?>
                            <?php echo $this->Element('invoices/for_info'); ?>
                            </div>
                            <div class="col-md-6">
            <?php
                if (!empty($tenderInfo['Invoice']['number'])):
            ?>
                            <p><strong><?php echo __("Invoice #:"); ?></strong> <?php echo sprintf("%04d", $tenderInfo['Invoice']['number']); ?></p>
            <?php
                endif;
            ?>
                            <?php echo $this->Element('invoices/issue_date_info'); ?>
                            <?php echo $this->Element('invoices/due_date_info'); ?>
                            <?php echo $this->Element('invoices/receipt_date_info'); ?>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                            <table class="table table-striped" id="services">
                                <thead>
                                    <tr>
                                        <th><?php echo  __('Description'); ?></th>
                                        <th><?php echo __('Amount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo $tenderInfo['Invoice']['service_description']; ?></td>
                                        <td><p class="text-right"><?php 
                App::uses('CakeNumber', 'Utility');
                echo CakeNumber::format($tenderInfo['Invoice']['service_value'], array(
                    'places' => 2,
                    'before' => 'CAD ',
                    'escape' => false,
                    'decimals' => '.',
                    'thousands' => ','
                ));                
                ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('GST'); ?></td>
                                        <td><p class="text-right"><?php 
                App::uses('CakeNumber', 'Utility');
                echo CakeNumber::format($tenderInfo['Invoice']['tax_value'], array(
                    'places' => 2,
                    'before' => 'CAD ',
                    'escape' => false,
                    'decimals' => '.',
                    'thousands' => ','
                ));                
                ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><strong><?php echo __('Total'); ?></strong></td>
                                        <td><p class="text-right"><?php 
                App::uses('CakeNumber', 'Utility');
                echo CakeNumber::format($tenderInfo['Invoice']['total_value'], array(
                    'places' => 2,
                    'before' => 'CAD ',
                    'escape' => false,
                    'decimals' => '.',
                    'thousands' => ','
                ));                
                ?>
</p></td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            </div>
                        </div>
            <?php
                if ($tenderInfo['Invoice']['status'] == EJQ_INVOICE_STATUS_SENT):
            ?>
                            <?php //echo $this->Element('invoices/inform_payment_form'); ?>
            <?php
                endif;
            ?>
                    </div>