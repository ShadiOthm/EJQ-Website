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
                <?php echo $this->Element('compliances/term_condition_compliance_action', array('termConditionId' => $termConditionData['id'], 'compliant' => $termConditionData['compliant'])); ?>
                        </div>
                        <div class="col-md-6 right">
                <?php if (empty($termConditionData['amendment'])): ?>
                        <div class="control"  id="control_amend_term_condition-<?php echo $termConditionData['id']; ?>">
                            <?php
        echo $this->Html->link(
                $this->Html->image("/img/icon-add.png") . __('make an amendment'), 
                '#amend_term_condition-' . $termConditionData['id'], 
                array(
                    'escape' => false, 
                    'title' => __('make an amendment'),
                    'class' => 'show_amend_term_condition',
                    'id' => 'amend_term_condition-' . $termConditionData['id'],
                    ));
         ?>
                        </div>
                    <?php else: ?>
                            <h4><?php echo __("Contractor's amendment"); ?></h4>
                    <p id="existing_amendment-<?php echo $termConditionData['id']; ?>">
                        <?php echo nl2br(h($termConditionData['amendment'])); ?>
                    </p>
                        <div class="control"  id="control_amend_term_condition-<?php echo $termConditionData['id']; ?>">
                <?php
        echo $this->Html->link(
                '&nbsp;&nbsp;<span class="ico">&#xf27b;</span> ' . __("change this amendment"), 
                '#amend_term_condition-' . $termConditionData['id'], 
                array(
                    'escape' => false, 
                    'title' => __('change this amendment'),                    
                    'id' => 'amend_term_condition-' . $termConditionData['id'],
                    'class' => 'show_amend_term_condition',
                    ));
                     ?>
                        </div>
                    <?php endif; ?>
                           <div class="content-box-edition amend_term_condition" id="form_amend_term_condition-<?php echo $termConditionData['id']; ?>">
<?php

        echo $this->Form->create('Compliance', array('url'=>array('controller'=>'term_conditions', 'action' => 'amend'), 'id' => 'amend_term_condition-' . $termConditionData['id']));
        echo $this->Form->input('Compliance.id');
        echo $this->Form->input('TermCondition.id', array('value'=>$termConditionData['id']));

        echo $this->Form->input(
                'Compliance.amendment', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label' => __('Amendment:'), 
                    'value' => $termConditionData['amendment'], 
                    'type' => 'textarea', 
                    'rows' => '5',
                    ));
        echo $this->Form->submit(__('Confirm'));
        echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#', array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_amend_term_condition-' . $termConditionData['id'], 'class' => 'hide_form_amend_term_condition'));
        echo $this->Form->end();
?>
                           </div>
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
