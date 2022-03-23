<section id="ask_for_services">
    <button type="button" class="post_a_job" id="ask_for_services" onclick="window.location.href='#'">
        <?php echo '<span class="ico">&#xf085;</span>&nbsp; ' . __('Post a job'); ?>
    </button>
<?php
echo $this->Form->create('Consumer', array('url'=>array('controller'=>'consumers', 'action' => 'update_estimation_request'), 'id' => 'ask_for_services'));
echo $this->Form->input('id');

echo $this->Form->input('Request.title', array('label' => __('Title:')));
echo $this->Form->input('Request.description', array('label' => __('Description:'), 'type' => 'textarea'));
echo $this->Form->input('Request.preferred_visit_time', array('label' => __('Preferred visit time:'), 'type' => 'textarea', 'rows' => '2'));

echo $this->Element('forms/submit');
echo $this->Form->end();
?>
                
    
    
</section>





                
