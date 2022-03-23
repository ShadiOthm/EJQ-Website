        <div class="content-box tab-pane fade in active" id="submit_bid" role="tabpanel">   
                <h1><?php echo __('Submit bid?'); ?></h1>
                <h1 style="color: navy"><?php 
                App::uses('CakeNumber', 'Utility');
                echo CakeNumber::format($bidInfo['Bid']['value'], array(
                    'places' => 2,
                    'before' => 'CAD ',
                    'escape' => false,
                    'decimals' => '.',
                    'thousands' => ','
                ));                
                ?>
                </h1>
                            <h3><?php echo __('ATTENTION'); ?></h3>
                            <p><?php echo __("You will be NOT able to change bid details after submission, "); ?></p>

 <?php
                echo $this->Form->create('Tender', array('novalidate' => true, 'url'=>array('controller'=>'tenders', 'action' => 'submit_bid'), 'id' => 'submit_bid_form'));
                echo $this->Form->input('Tender.id');

                echo $this->Element('forms/submit');

                echo $this->Html->link($this->Html->tag('span', __('Back to tender'), ['class' => 'badge badge-danger']), ['controller' => 'tenders', 'action' => 'contractor_view_tender', $tenderInfo['Tender']['id']], array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_submit_bid'));

                echo $this->Form->end();
        ?>

                        </div>