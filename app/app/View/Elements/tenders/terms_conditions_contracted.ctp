                    <div class="content-box tab-pane fade<?php echo ($activeTab == 'terms' ? ' in active' : ''); ?>" id="terms" role="tabpanel">   
                    
                    	<div class="title-box-admin clearfix">
                            <h3><?php echo __('Terms and Conditions'); ?></h3>
                                
                        </div>
<?php
                if (!empty($tenderInfo['TermCondition'])):
                    $conditionsCounter = 0;
                    foreach ($tenderInfo['TermCondition'] as $key => $termConditionData):
                        $conditionsCounter++;
?>
                    <div class="content-box col-md-12">   
                        <div class="col-md-6 left">
                                <p><?php echo $conditionsCounter . ". " . nl2br(h($termConditionData['description'])); ?></p>
<?php
$compliant = $termConditionData['compliant'];
$termConditionId = $termConditionData['id'];
if ($compliant !== FALSE):
?>
<p class="text-success"><strong><?php echo __("This term and condition was accepted"); ?></strong></p><?php
elseif ($compliant === FALSE):
?>
             <p class="text-danger"><strong><?php echo __("This term and condition was not accepted"); ?></strong></p>
                 <?php
endif;
?>                        </div>
                        <div class="col-md-6 right">
                <?php if (!empty($termConditionData['amendment'])): ?>
                            <h4><?php echo __("Contractor's amendment"); ?></h4>
                    <p id="existing_amendment-<?php echo $termConditionData['id']; ?>">
                        <?php echo nl2br(h($termConditionData['amendment'])); ?>
                    </p>
                    <?php endif; ?>
                        </div>
                    </div>
                    <div class="content-box col-md-12">   
                        <hr>
                    </div>
        <?php
                    endforeach;
                endif;

            ?>
                        
                        
                    </div>     
