                    <h1 style="color: white;">
                <?php if (!empty($bidInfo['Bid']['value'])): ?>
                <h1 style="color: white"><?php 
                App::uses('CakeNumber', 'Utility');
                echo CakeNumber::format($bidInfo['Bid']['value'], array(
                    'places' => 2,
                    'before' => 'CAD ',
                    'escape' => false,
                    'decimals' => '.',
                    'thousands' => ','
                ));                
                ?>
                </h1>
                    <?php if (!empty($contractorAlias)):
                            if (empty($thisIsChosenBid)):?>
                    <h2><?php echo sprintf(__("Bid by %s"), $contractorAlias); ?></h2>
                            <?php else: ?>
                    <h2><?php echo sprintf(__("Chosen Bid")); ?></h2>
                    <?php endif; 
                        endif; ?>
                            
                <?php else: ?>
                <h1><?php echo __("Null bid"); ?></h1>
                <?php endif; ?>
</h1>

