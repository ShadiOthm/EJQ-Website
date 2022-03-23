        <div class="content-box tab-pane fade in active" id="report_contract_signing" role="tabpanel">   
                <h1><?php echo __('Report contract signing?'); ?></h1>

 <?php
                echo $this->Form->create('Tender', array('novalidate' => true, 'url'=>array('controller'=>'tenders', 'action' => 'report_contract_signing'), 'id' => 'report_contract_signing_form'));
                echo $this->Form->input('Tender.id');

                echo $this->Element('forms/submit');

                echo $this->Html->link($this->Html->tag('span', __('Back to tender'), ['class' => 'badge badge-danger']), ['controller' => 'tenders', 'action' => 'details', $tenderInfo['Tender']['id']], array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_report_contract_signing'));

                echo $this->Form->end();
        ?>

                        </div>