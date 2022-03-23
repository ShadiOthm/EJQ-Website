
<div class="content-box tab-pane fade<?php echo ($activeTab == 'terms' ? ' in active' : ''); ?>" id="terms" role="tabpanel">   
                    
                    	<div class="title-box-admin clearfix">
                            <h3><?php echo __('Terms and Conditions'); ?></h3>
                                
                        </div>
<?php
    if (!empty($rights['tender'])):
?>
                        <div class="control"  id="control_add_condition_to_tender">
                            <?php
        echo $this->Html->link(
                $this->Html->image("/img/icon-add.png") . __('add term and condition'), 
                '#show_add_condition_to_tender', 
                array(
                    'escape' => false, 
                    'title' => __('Add term and condition'), 
                    'id' => 'show_add_condition_to_tender',
                    ));
         ?>
                        </div>
                        <div class="content-box-edition" id="add_condition_to_tender">
 <?php
        echo $this->Form->create('Demand', array('url'=>array('controller'=>'demands', 'action' => 'add_condition_to_tender'), 'id' => 'add_condition_to_tender'));
        echo $this->Form->input('id');
        echo $this->Form->hidden('Tender.id');
        echo $this->Form->input(
                'TermCondition.description', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('New Term and Condition:'), 'class' => 'control-label'),
                    'type' => 'textarea',
                    'rows' => '5',
                    ));
        
        echo $this->Element('forms/submit');
        echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#', array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_add_condition_to_tender'));
        echo $this->Form->end();
        ?>
                        </div>
<?php
endif;
?>
<?php
                if (!empty($tenderInfo['TermCondition'])):
                    $conditionsCounter = 0;
                    foreach ($tenderInfo['TermCondition'] as $key => $termConditionData):
                        $conditionsCounter++;
?>
                        <div class="content-box" id="div_tender_term_condition-<?php echo $termConditionData['id']; ?>">
                            
                            <p id="tender_term_condition-<?php echo $termConditionData['id']; ?>"><?php echo $conditionsCounter . ". " . nl2br(h($termConditionData['description'])); ?></p>
<?php
    if (!empty($rights['tender'])):
?>

                            <div class="control" id="control_update_tender_term_condition-<?php echo $termConditionData['id']; ?>">
                                <?php
            echo $this->Html->link(
                    $this->Html->image("/img/icon-edit.png", array('title' => __('edit'))), 
                    '#show_update_tender_term_condition-'. $termConditionData['id'], 
                    array(
                        'escape' => false, 
                        'title' => __('Edit'), 
                        'class' => 'show_update_tender_term_condition',
                        'id' => 'show_update_tender_term_condition-'. $termConditionData['id'],
                        ));
            echo $this->Html->link(
                    $this->Html->image("/img/icon-delete.png", array('title' => __('removeX'))), 
                    array(
                        'controller' => 'demands', 
                        'action' => 'remove_tender_term_condition', 
                        $termConditionData['id']),
                    array(
                        'escape' => false, 
                        'title' => __('remove'), 
                        'class' => 'remove_tender_term_condition', 
                        'id' => 'remove_tender_term_condition-'. $termConditionData['id'],
                        ), 
                        __('Are you sure you want to remove this T&C?'));
             ?>
                            </div>
        
                       <div class="content-box-edition update_tender_term_condition" id="update_tender_term_condition-<?php echo $termConditionData['id']; ?>">
 <?php
        echo $this->Form->create('Demand', array('url'=>array('controller'=>'demands', 'action' => 'update_tender_term_condition'), 'id' => 'form_update_tender_term_condition-'. $termConditionData['id']));
        echo $this->Form->input('id');
        echo $this->Form->hidden('Tender.id');
        echo $this->Form->hidden('TermCondition.id', array('value' => $termConditionData['id']));
        echo $this->Form->input(
                'TermCondition.description', 
                array(
                    'value' => $termConditionData['description'], 
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> '',
                    'type' => 'textarea',
                    'rows' => '5',
                    ));
        
        echo $this->Element('forms/submit');
        echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#update_tender_term_condition-' . $termConditionData['id'], array('escape' => false, 'title' => __('Cancel'), 'class' => 'hide_form_update_tender_term_condition', 'id' => 'hide_form_update_tender_term_condition-' . $termConditionData['id']));
        echo $this->Form->end();
        ?>
                        </div>
<?php
endif;
?>
                        </div>
                        <br clear="all" />
        <?php
                    endforeach;
                endif;

            ?>
                        
                        
                    </div>     
