        <div class="content-box tab-pane fade in active" id="close_to_bids" role="tabpanel">   
                <h1><?php echo __('Close tender to bids?'); ?></h1>
<?php
                echo $this->Form->create('Tender', array('novalidate' => true, 'url'=>array('controller'=>'tenders', 'action' => 'close_to_bids'), 'id' => 'close_to_bids_form'));
                echo $this->Form->input('Tender.id');

echo $this->Element('forms/submit');

                echo $this->Html->link($this->Html->tag('span', __('Back to tender'), ['class' => 'badge badge-danger']), ['controller' => 'tenders', 'action' => 'details', $tenderInfo['Tender']['id']], array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_close_to_bids'));
                echo $this->Form->end();
        ?>
                        </div>