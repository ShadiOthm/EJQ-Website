                        <div class="content-box-edition" id="submit_bid">
                            <h3><?php echo __('ATTENTION'); ?></h3>
                            <p><?php echo __("You will be NOT able to change bid details after submission, "); ?></p>

 <?php
                echo $this->Form->create('Tender', array('novalidate' => true, 'url'=>array('controller'=>'tenders', 'action' => 'submit_bid'), 'id' => 'submit_bid_form'));
                echo $this->Form->input('Tender.id');

                echo $this->Element('forms/submit', ['submitLabel' => __('Submit bid')]);
                
                echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#', array('class' => '','escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_submit_bid'));
                //echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#', array('class' => 'btn btn-cancel pull-right','escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_submit_bid'));

                echo $this->Form->end();
        ?>
                        </div>