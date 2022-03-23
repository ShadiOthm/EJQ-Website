    <div class="content-box tab-pane fade in active" id="suggest_visit_time" role="tabpanel">   
<?php 
                                        if (!empty($tenderInfo['Schedule']['period'])):
?>
<h1><?php echo __('Current Proposed Schedule:'); ?></h1>
                            <h2><?php 
                                        if (!empty($tenderInfo['Schedule']['period'])):
                                            echo($tenderInfo['Schedule']['period']) ;                                             
                                        endif;
?></h2>
        <h1><?php echo __('Suggest other visit time?'); ?></h1>
        <?php 
        else:
        ?>
        <h1><?php echo __('Suggest a visit time'); ?></h1>
        <?php 
        endif;
        ?>
<?php
echo $this->Form->create('Demand', array('url'=>array('controller'=>'demands', 'action' => 'suggest_visit_time')));
echo $this->Form->input('id');
echo $this->Form->input('Request.id');
echo $this->Form->input("Schedule.period_date", array(
    'type' => 'text',
    'after' => '<span class="add-on"><label for="SchedulePeriodDate" class="badge add-on">' . __('show calendar') . '</label></span>',
    'div'=>array(
        'class'=>'form-group input-append date',
        'id' => 'dispatch_date',
        ), 
    'label'=>array(
        'text' => __('Scheduled Visit Date'), 
        'class' => 'control-label'
        ), 
    'data-format' => "MM-dd-yyyy", 
    'class' => ' form-control', 
    'readonly' => true,
    'placeholder' => __('MM-DD-YYYY'),
    'required' => true,
    ));
echo $this->Form->input("Schedule.period_time_begin", array(
    'after' => '<label for="SchedulePeriodTimeBegin" class="badge add-on">' . __('select time') . '</label>',
    'div'=>array(
        'class'=>'form-group input-append',
        'id' => 'dispatch_time_begin',
        ), 
    'label'=>array(
        'text' => __('Time'), 
        'class' => 'schedule',
        ), 
    'data-format' => "HH:mm PP", 
    'class' => 'schedule_time  form-control', 
    'required' => true,
    'readonly' => true,

    //'placeholder' => __('H:M'),
    ));


echo $this->Element('forms/submit');
echo $this->Html->link($this->Html->tag('span', __('Back to tender request'), ['class' => 'badge badge-danger']), ['controller' => 'demands', 'action' => 'request_details', $tenderInfo['Demand']['id']], array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_suggest_visit_time'));
echo $this->Form->end();
?>
                        </div>