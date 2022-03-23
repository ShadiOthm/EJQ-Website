    <div class="content-box tab-pane fade in active" id="cancel_request" role="tabpanel">   
                            <h1><?php echo __('Cancel Tender Request?'); ?></h1>
 <?php
                echo $this->Form->create('Demand', array('novalidate' => true, 'url'=>array('controller'=>'demands', 'action' => 'cancel_request'), 'id' => 'cancel_request_form'));
                echo $this->Form->input('id');

                echo $this->Element('forms/submit', ['submitLabel' => __('Confirm cancellation')]);

                echo $this->Html->link($this->Html->tag('span', __('Back to tender request'), ['class' => 'badge badge-danger']), ['controller' => 'demands', 'action' => 'request_details', $tenderInfo['Demand']['id']], array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_cancel_request'));
                echo $this->Form->end();
        ?>
                        </div>