        <div class="content-box tab-pane fade in active" id="disclose_chosen_bid" role="tabpanel">   
                <h1><?php echo __('Disclose contractor\'s name and notify contractor?'); ?></h1>

 <?php
                echo $this->Form->create('Tender', array('novalidate' => true, 'url'=>array('controller'=>'tenders', 'action' => 'disclose_chosen_bid'), 'id' => 'disclose_chosen_bid_form'));
                echo $this->Form->input('Tender.id');

                echo $this->Element('forms/submit');

                echo $this->Html->link($this->Html->tag('span', __('Back to tender'), ['class' => 'badge badge-danger']), ['controller' => 'tenders', 'action' => 'details', $tenderInfo['Tender']['id']], array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_disclose_chosen_bid'));

                echo $this->Form->end();
        ?>

                        </div>