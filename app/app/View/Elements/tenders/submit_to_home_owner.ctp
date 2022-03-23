    <div class="content-box tab-pane fade in active" id="submit_to_home_owner" role="tabpanel">   
                            <h1><?php echo __('Submit tender to Home Owner Review?'); ?></h1>
 <?php
                echo $this->Form->create('Tender', array('novalidate' => true, 'url'=>array('controller'=>'tenders', 'action' => 'submit_to_home_owner'), 'id' => 'submit_to_home_owner_form'));
                echo $this->Form->input('Tender.id');

                echo $this->Element('forms/submit');

                echo $this->Html->link($this->Html->tag('span', __('Back to tender'), ['class' => 'badge badge-danger']), ['controller' => 'tenders', 'action' => 'details', $tenderInfo['Tender']['id']], array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_submit_to_home_owner'));
                echo $this->Form->end();
        ?>
                        </div>