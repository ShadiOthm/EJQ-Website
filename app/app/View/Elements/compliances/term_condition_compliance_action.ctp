<div class="compliance_actions"><?php
if (is_null($compliant)):
?>
<?php
            echo $this->Html->link(
                    '&nbsp;&nbsp;<span class="ico">&#xf00c;</span> ' . __("Accept"), '/term_conditions/accept/' . $termConditionId, array('escape' => false, 'class' => 'more_link accept_term_condition', 'title' => __('Accept term and condition'), 'id' => 'accept_term_condition-' . $termConditionId)
            );
             ?>&nbsp;|&nbsp;<?php
            echo $this->Html->link(
                    '&nbsp;&nbsp;<span class="ico">&#xf00d;</span> ' . __("No Accept"), '/term_conditions/decline/' . $termConditionId, array('escape' => false, 'class' => 'more_link decline_term_condition', 'title' => __('Accept term and condition'), 'id' => 'decline_term_condition-' . $termConditionId)
            );
             ?><?php
elseif ($compliant !== FALSE):
?>
<p class="text-success"><strong><?php echo __("This term and condition was accepted"); ?></strong></p>&nbsp;(<?php
            echo $this->Html->link(
                    '<span class="ico">&#xf00d;</span> ' . __("No Accept"), '/term_conditions/decline/' . $termConditionId, array('escape' => false, 'class' => 'more_link decline_term_condition', 'title' => __("Don't accept term and condition"), 'id' => 'decline_term_condition   -' . $termConditionId)
            );
             ?>)<?php
elseif ($compliant === FALSE):
?>
             <p class="text-danger"><strong><?php echo __("This term and condition was not accepted"); ?></p></strong>&nbsp;(<?php
            echo $this->Html->link(
                    '<span class="ico">&#xf00d;</span> ' . __("Accept"), '/term_conditions/accept/' . $termConditionId, array('escape' => false, 'class' => 'more_link accept_term_condition', 'title' => __('Accept term and condition'), 'id' => 'decline_term_condition   -' . $termConditionId)
            );
             ?>)
<?php
endif;
?></div>


