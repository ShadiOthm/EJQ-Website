<?php
    if (!empty($tenderInfo['TermCondition'])):

 ?>
        <div class="row" id="terms_and_conditions">
                <div class="col-md-12">
                     <h3><?php echo __('Compliance to Terms and conditions'); ?></h3>
                </div>
                <div class="col-md-12">
                        
            <?php
                    $conditionsCounter = 0;
                    foreach ($tenderInfo['TermCondition'] as $key => $termConditionData):
                        $conditionsCounter++;
?>
            <hr />
            <div class="row" id="term_condition-<?php echo $termConditionData['id']; ?>">
                <div class="col-md-6 content-box" id="home_owner_term_condition-<?php echo $termConditionData['id']; ?>">
                        
                 <?php if (!empty($bidInfo['Compliance'][$termConditionData['id']]['compliant'])): ?>
                        <h2 class="alert-success"><?php echo __("Accepted"); ?></h2>
                <?php else: ?>
                        <h2 class="alert-danger"><?php echo __("Not Accepted"); ?></h2>
                <?php endif; ?>

                    <p><?php echo $conditionsCounter . ". " . nl2br(h($termConditionData['description'])); ?></p>
                </div>                        
                        
                        
                <div id="contractor-amendment-<?php echo $termConditionData['id']; ?>" class="col-md-6 content-box ">
                    <h5><?php echo __("Contractor's Amendment"); ?></h5>
                    <?php if (!empty($bidInfo['Compliance'][$termConditionData['id']]['amendment'])): ?>
                    <p><?php echo nl2br(h($bidInfo['Compliance'][$termConditionData['id']]['amendment'])); ?></p>
                    <?php else: ?>
                    <p><?php echo __("---"); ?></p>
                    <?php endif; ?>
                </div>

            </div>
                        
                        
            <?php
                    endforeach;
                    ?>
                </div>

        </div>
            
                        
                        
        <?php
                endif;

            ?>
