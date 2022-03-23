                        <div class="content-box-edition" id="update_service_types">
 <?php
echo $this->Form->create('Demand', array('url'=>array('controller'=>'demands', 'action' => 'update_service_types'), 'id' => 'update_service_types_form'));
echo $this->Form->input('id');


foreach($optionsServiceTypes as $keyService => $nameService):
    echo $this->Form->input("ServiceType.ServiceType.$keyService", array(
    'div' => ['class' => 'check'],
    'label' => false,
    'value' => $keyService,
    'type' => 'checkbox',
    'before' => '<label for="ServiceTypeServiceType">',
    'after' =>  "&nbsp;$nameService</label>",
    'hiddenField' => false,
    'checked' => (in_array($keyService, $selectedServiceTypes) ? 'checked' : null),
    ));


    
endforeach;

echo $this->Element('forms/submit');
echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#', array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_update_service_types'));
echo $this->Form->end();
        ?>
                        </div>