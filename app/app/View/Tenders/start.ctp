<?php echo $this->Html->script(array('tenders_start.js?v=0.0.0'), false); ?>
<section id="start_tender">
<?php echo $this->Element('demands/request_info'); ?>
    <section id="request_forms">
        <section id="start_tender_form" class="inside_page_form left">

            <button type="button" id="start_tender" onclick="window.location.href='#start_tender_form'">
                <?php echo '<span class="ico">&#xf085;</span>&nbsp; ' . __('Create Tender'); ?>
            </button>

<?php
echo $this->Form->create('Tender', array('novalidate' => true, 'type' => 'file', 'url'=>array('controller'=>'tenders', 'action' => 'start')));
echo $this->Form->input('id');

echo $this->Form->input('Tender.municipality_id', array('class' => 'chosen_select'));
echo $this->Form->input('Tender.description', array('label' => __('Description:'), 'type' => 'textarea'));
echo $this->Form->input('Demand.image',array('type' => 'file', 'label' => __('Photo:')));
echo $this->Form->input('Demand.image_description', array('label' => __('Image description:'), 'type' => 'textarea'));
echo $this->Form->input('ServiceType.ServiceType', array('multiple' => 'checkbox', 'options' => $optionsServiceTypes, 'selected' => $selectedServiceTypes, 'label'=>false));
echo $this->Form->input("Tender.estimated_tender_completion", array('type' => 'text','label'=>array('text' => __('Estimation for tender completion'), 'class' => 'schedule_date'), 'class' => 'schedule_date', 'placeholder' => __('MM-DD-YYYY')));

echo $this->Form->submit(__('Confirm'));
echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#', array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_start_tender'));
echo $this->Form->end();
?>
        </section>


        <section id="cancel_request_form" class="inside_page_form right">

            <button type="button" id="cancel_request" onclick="window.location.href='#cancel_request'">
                <?php echo '<span class="ico">&#xf085;</span>&nbsp; ' . __('Cancel Request'); ?>
            </button>

<?php
echo $this->Form->create('Demand', array('novalidate' => true, 'url'=>array('controller'=>'demands', 'action' => 'cancel_request'), 'id' => 'cancel_request'));
echo $this->Form->input('id');


echo $this->Form->submit(__('Confirm cancellation'));
echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#', array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_cancel_request'));
echo $this->Form->end();
?>
        </section>
    </section>                
    
    
</section>





                
