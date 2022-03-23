                        <div class="content-box-edition" id="create_tender">
 <?php
                echo $this->Form->create('Tender', array('novalidate' => true, 'url'=>array('controller'=>'tenders', 'action' => 'start'), 'id' => 'create_tender_form'));
                echo $this->Form->input('Tender.id');

                echo $this->Element('forms/submit', ['submitLabel' => __('Create tender')]);

                echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#', array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_cancel_request'));
                echo $this->Form->end();
        ?>
                        </div>