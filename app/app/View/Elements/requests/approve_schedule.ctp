    <div class="content-box tab-pane fade in active" id="approve_schedule" role="tabpanel">   

                            <h1><?php echo __('Confirm visit time?'); ?></h1>
                            <h2><?php 
                                        if (!empty($tenderInfo['Schedule']['period'])):
                                            echo($tenderInfo['Schedule']['period']) ;                                             
                                        endif;
?></h2>
<?php
echo $this->Form->create('Demand', array('url'=>array('controller'=>'demands', 'action' => 'approve_schedule')));
echo $this->Form->input('id');
echo $this->Form->input('Request.id');
echo $this->Element('forms/submit');
echo $this->Html->link($this->Html->tag('span', __('Back to tender request'), ['class' => 'badge badge-danger']), ['controller' => 'demands', 'action' => 'request_details', $tenderInfo['Demand']['id']], array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_approve_schedule'));
echo $this->Form->end();
?>
                        </div>