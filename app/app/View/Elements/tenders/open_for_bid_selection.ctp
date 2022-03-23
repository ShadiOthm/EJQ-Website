        <div class="content-box tab-pane fade in active" id="open_for_bid_selection" role="tabpanel">   
                <h1><?php echo __('Open tender for bid selection?'); ?></h1>
                <h3><?php echo __('The home owner will be able to select a bid now.'); ?></h3>
<?php
                echo $this->Form->create('Tender', array('novalidate' => true, 'url'=>array('controller'=>'tenders', 'action' => 'open_for_bid_selection'), 'id' => 'open_for_bid_selection_form'));
                echo $this->Form->input('Tender.id');

echo $this->Element('forms/submit');

                echo $this->Html->link($this->Html->tag('span', __('Back to tender'), ['class' => 'badge badge-danger']), ['controller' => 'tenders', 'action' => 'details', $tenderInfo['Tender']['id']], array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_reopen_to_bids'));
                echo $this->Form->end();
        ?>
                        </div>