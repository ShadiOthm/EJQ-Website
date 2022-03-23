    <div class="content-box tab-pane fade in active" id="dispatch_estimator" role="tabpanel">   
<?php 
    echo $this->Form->create('Demand', array('url'=>array('controller'=>'demands', 'action' => 'dispatch_estimator')));
    $emptySchedule = true;
    if (!empty($tenderInfo['Schedule']['period'])):
?>
<h1><?php echo __('Current Proposed Schedule:'); ?></h1>
<h2><?php
        if (!empty($tenderInfo['Schedule']['period'])):
            echo($tenderInfo['Schedule']['period']) ;                                             
            $emptySchedule = false;
        endif;
?></h2>
<p><?php echo __('Do you want to change the scheduled visit time?'); ?></p>
        <?php 
        echo $this->Form->input(
                'Demand.change_schedule', 
                array(
                    'div'=>array('class'=>'form-group input-append'), 
                    'class' => 'radiobutton show_schedule_fields',
                    'before' => '',
                    'after' =>  '',
                    'label' => false,
                    'legend' => false,
                    'fieldset' => false,
                    'type' => 'radio',
                    'options' => ['1' => __('Yes'), '0' => __('No')],
                    'required' => false,
                    ));
        
        else:
        ?>
        <h1><?php echo __('Schedule the visit'); ?></h1>
        <?php 
        endif;
        ?>
<?php
echo $this->Form->input('id');
echo $this->Form->hidden('Request.id');
echo $this->Form->hidden('Provider.id');
echo $this->Form->hidden('marketplace_id');
echo $this->Form->hidden('Consumer.id');
echo $this->Form->input("Schedule.period_date", array(
    'type' => 'text',
    'after' => '<span class="add-on"><label for="SchedulePeriodDate" class="badge add-on">' . __('show calendar') . '</label></span>',
    'div'=>array(
        'class'=>'form-group input-append date schedule-fields' . ($emptySchedule ? "" : " hidden"),
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
        'class'=>'form-group input-append schedule-fields' . ($emptySchedule ? "" : " hidden"),
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

    ));


echo $this->Element('forms/submit', ['submitLabel' => __('Confirm and dispatch')]);
echo $this->Html->link($this->Html->tag('span', __('Back to tender request'), ['class' => 'badge badge-danger']), ['controller' => 'demands', 'action' => 'request_details', $tenderInfo['Demand']['id']], array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_suggest_visit_time'));
echo $this->Form->end();
?>
        
                       </div>