    <div class="content-box tab-pane fade in active" id="contractor_report_begin" role="tabpanel">   
        <h1><?php echo __('Please inform the date the job begun:'); ?></h1>
<?php
$this->request->data['Job']['date_begin_contractor'] = "";
echo $this->Form->create('Tender', array('url'=>array('controller'=>'jobs', 'action' => 'contractor_report_begin')));
echo $this->Form->input('Job.id');
echo $this->Form->input('Tender.id');

echo $this->Form->input("Job.date_begin_contractor", array(
    'type' => 'text',
    'after' => '<span class="add-on"><label for="JobDateBeginContractor" class="badge add-on">' . __('show calendar') . '</label></span>',
    'div'=>array(
        'class'=>'form-group input-append date',
        'id' => 'date_begin',
        ), 
    'label'=>array(
        'text' => __('Job Start Date'), 
        'class' => 'control-label'
        ), 
    'data-format' => "MM-dd-yyyy", 
    'class' => ' form-control', 
    'readonly' => true,
    'placeholder' => __('MM-DD-YYYY'),
    'required' => true,
    ));

echo $this->Element('forms/submit');
echo $this->Html->link($this->Html->tag('span', __('Back to tender details'), ['class' => 'badge badge-danger']), ['controller' => 'tenders', 'action' => 'details', $tenderInfo['Tender']['id']], array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_suggest_visit_time'));
echo $this->Form->end();
?>
                        </div>