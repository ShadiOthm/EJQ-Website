        <div class="content-box tab-pane fade in active" id="ask_for_modifications" role="tabpanel">   
                <h1><?php echo __('Ask for modifications in the Tender?'); ?></h1>
<?php
                echo $this->Form->create('Tender', array('novalidate' => true, 'url'=>array('controller'=>'tenders', 'action' => 'ask_for_modifications'), 'id' => 'ask_for_modifications_form'));
                echo $this->Form->input('Tender.id');
                echo $this->Form->input(
                        'Tender.home_owner_comments', 
                        array(
                            'div'=>array('class'=>'form-group'), 
                            'class' => 'form-control',
                            'label'=> array('text' => __('Your comments'), 'class' => 'control-label'),
                            'type' => 'textarea',
                            'rows' => '8',
                            ));

                echo $this->Element('forms/submit');

                echo $this->Html->link($this->Html->tag('span', __('Back to tender'), ['class' => 'badge badge-danger']), ['controller' => 'tenders', 'action' => 'details', $tenderInfo['Tender']['id']], array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_ask_for_modifications'));
                echo $this->Form->end();
        ?>
                        </div>