        <div class="content-box tab-pane fade in active" id="open_to_bids" role="tabpanel">   
                <h1><?php echo __('Open tender to bids?'); ?></h1>
                <h3><?php echo __('The tender will be opened to bids next Monday.'); ?></h3>
<?php
                echo $this->Form->create('Tender', array('novalidate' => true, 'url'=>array('controller'=>'tenders', 'action' => 'open_to_bids'), 'id' => 'open_to_bids_form'));
                echo $this->Form->input('Tender.id');

echo $this->Element('forms/submit');

                echo $this->Html->link($this->Html->tag('span', __('Back to tender'), ['class' => 'badge badge-danger']), ['controller' => 'tenders', 'action' => 'details', $tenderInfo['Tender']['id']], array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_open_to_bids'));
                echo $this->Form->end();
        ?>
                        </div>