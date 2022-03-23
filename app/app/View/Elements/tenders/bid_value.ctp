<?php




    if (!empty($rights['bid'])):
        if (empty($bidInfo['Bid']['value'])):
            $actionLabel = __("add bid's value");
        else:
            $actionLabel = __("change bid's value");
        endif;
    endif;
?>

        <div id="bid_value">
                <?php if (!empty($bidInfo['Bid']['value'])): ?>
            <h3><?php echo __("Bid's Value"); ?></h3>
                <p id="bid_value"><?php 
                App::uses('CakeNumber', 'Utility');
                echo CakeNumber::format($bidInfo['Bid']['value'], array(
                    'places' => 2,
                    'before' => 'CAD ',
                    'escape' => false,
                    'decimals' => '.',
                    'thousands' => ','
                ));                
                ?>
                </p>
                <?php else: ?>
                <p class="bid_value"><?php echo __("There is no value set for this bid yet"); ?></p>
                <?php endif; ?>
<?php
    if (!empty($rights['bid'])):
?>        
                <div class="control" id="control_update_bid_value">
                    <?php
echo $this->Html->link(
        $actionLabel,
        '#show_update_bid_value',
        array(
            'escape' => false,
            'title' => $actionLabel,
            'id' => 'show_update_bid_value',
            ));
 ?>
                </div>
            


<?php echo $this->Element('bids/update_value'); ?>
<?php endif; ?>
        </div>

<div id="comments">
    <h4><?php echo __("Contractor's comments") ?></h4>
    
    <p id="bid_comments"><?php echo nl2br(h($bidInfo['Bid']['comments'])); ?></p>
                        
<?php
    if (!empty($rights['bid'])):
        if (empty($bidInfo['Bid']['comments'])):
            $actionLabel = __('add comments');
            $actionIcon = "icon-add.png";
        else:
            $actionLabel = __('update comments');
            $actionIcon = "icon-edit.png";
        endif;
?>
                        <div class="control" id="control_update_bid_comments">
                            <?php
        echo $this->Html->link(
                $this->Html->image("/img/$actionIcon") . $actionLabel, 
                '#show_update_bid_comments', 
                array(
                    'escape' => false, 
                    'title' => $actionLabel, 
                    'id' => 'show_update_bid_comments',
                    ));
         ?>
                            
                        </div>
                        
                        <div class="content-box-edition" id="update_bid_comments">
 <?php
        echo $this->Form->create('Bid', array('url'=>array('controller'=>'bids', 'action' => 'update_comments'), 'id' => 'update_bid_comments'));
        echo $this->Form->input('Demand.id');
        echo $this->Form->hidden('Tender.id');
        echo $this->Form->input(
                'Bid.comments', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('Edit'), 'class' => 'control-label'),
                    'type' => 'textarea',
                    'rows' => '8',
                    ));
        
        echo $this->Element('forms/submit');
        echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#', array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_update_bid_comments'));
        echo $this->Form->end();
        ?>
                        </div>
<?php
endif;
?>
    
    
</div>
