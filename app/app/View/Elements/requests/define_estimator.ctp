                        <div class="content-box-edition" id="define_estimator">
<?php
echo $this->Form->create('Demand', array('url'=>array('controller'=>'demands', 'action' => 'define_estimator'), 'id' => 'define_estimator_form'));
echo $this->Form->input('id');
echo $this->Form->hidden('Request.id');

$selectOption = ['0' => __('(choose a project developer)')];
if (!empty($optionsEstimators)) {
    asort($optionsEstimators);
    $optionsEstimators = $selectOption + $optionsEstimators;
} else {
    $optionsEstimators = $selectOption;
    
}
echo $this->Form->input('Provider.id', array(
        'type' => 'select', 
//        'div'=>array(
//            'class'=>'',
//            ), 
        'options' => $optionsEstimators, 
        'label'=>false, 
        'class' => 'form-control select sl-lg',
        ));

echo $this->Element('forms/submit');
echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#', array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_define_estimator'));
echo $this->Form->end();
?>
                        </div>