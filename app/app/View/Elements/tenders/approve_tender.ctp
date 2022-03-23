        <div class="content-box tab-pane fade in active" id="approve_tender" role="tabpanel">   
                <h1><?php echo __('Approve tender to be published?'); ?></h1>
<?php
                echo $this->Form->create('Tender', array('novalidate' => true, 'url'=>array('controller'=>'tenders', 'action' => 'approve_tender'), 'id' => 'approve_tender_form'));
                echo $this->Form->input('Tender.id');

                echo $this->Element('forms/submit');

                echo $this->Html->link($this->Html->tag('span', __('Back to tender'), ['class' => 'badge badge-danger']), ['controller' => 'tenders', 'action' => 'details', $tenderInfo['Tender']['id']], array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_approve_tender'));
                echo $this->Form->end();
        ?>
                        </div>