        <div class="content-box tab-pane fade in active" id="action_tab" role="tabpanel">   
                <h1><?php echo __('Extend tender to bids?'); ?></h1>
<?php
                echo $this->Form->create('Tender', array('novalidate' => true, 'url'=>array('controller'=>'tenders', 'action' => 'extend_to_bids'), 'id' => 'extend_to_bids_form'));
                echo $this->Form->input('Tender.id');
                echo $this->Form->input("Tender.close_to_bids_date", array(
                    'type' => 'text',
                    'after' => '<span class="add-on"><label for="TenderCloseToBidsDate" class="badge add-on">' . __('show calendar') . '</label></span>',
                    'div'=>array(
                        'class'=>'form-group input-append date',
                        'id' => 'extend_to_bids',
                        ), 
                    'label'=>array(
                        'text' => __('New Deadline Date For Bidding'), 
                        'class' => 'control-label'
                        ), 
                    'data-format' => "MM-dd-yyyy", 
                    'class' => ' form-control', 
                    'readonly' => true,
                    'placeholder' => __('MM-DD-YYYY'),
                    'required' => true,
                    ));

                echo $this->Element('forms/submit');

                echo $this->Html->link($this->Html->tag('span', __('Back to tender'), ['class' => 'badge badge-danger']), ['controller' => 'tenders', 'action' => 'details', $tenderInfo['Tender']['id']], array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_extend_to_bids'));
                echo $this->Form->end();
        ?>
                        </div>