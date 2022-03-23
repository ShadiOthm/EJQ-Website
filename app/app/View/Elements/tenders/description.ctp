                    <div class="content-box tab-pane fade<?php echo (empty($activeTab) || $activeTab == 'description' ? ' in active' : ''); ?>" id="description" role="tabpanel">   
                        <h3><?php echo $tenderInfo['Tender']['title']; ?></h3>
<?php 
        if (!empty($tenderInfo['Tender']['home_owner_comments']) && $role != 'CONTRACTOR') :
?>                                    
                        <h4><?php echo __('Home Owner Comments') ?></h4>
                    	<p id="home_owner_comments"><?php echo nl2br(h($tenderInfo['Tender']['home_owner_comments'])); ?></p>
                        <h4><?php echo __('Description') ?></h4>
<?php 
        endif;
?>                                    
                    	<p id="tender_description"><?php echo nl2br(h($tenderInfo['Tender']['description'])); ?></p>
                        
<?php
    if (!empty($rights['tender'])):
        if (empty($tenderInfo['Tender']['description'])):
            $actionLabel = __('add description');
            $actionIcon = "icon-add.png";
        else:
            $actionLabel = __('update description');
            $actionIcon = "icon-edit.png";
        endif;
?>
                        <div class="control" id="control_update_tender_description">
                            <?php
        echo $this->Html->link(
                $this->Html->image("/img/$actionIcon") . $actionLabel, 
                '#show_update_tender_description', 
                array(
                    'escape' => false, 
                    'title' => $actionLabel, 
                    'id' => 'show_update_tender_description',
                    ));
         ?>
                            
                        </div>
                        
                        <div class="content-box-edition" id="update_tender_description">
 <?php
        echo $this->Form->create('Tender', array('url'=>array('controller'=>'tenders', 'action' => 'update_description'), 'id' => 'update_tender_description'));
        echo $this->Form->input('Demand.id');
        echo $this->Form->hidden('Tender.id');
        echo $this->Form->input(
                'Tender.description', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('Edit'), 'class' => 'control-label'),
                    'type' => 'textarea',
                    'rows' => '8',
                    ));
        
        echo $this->Element('forms/submit');
        echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#', array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_update_tender_description'));
        echo $this->Form->end();
        ?>
                        </div>
<?php
endif;
?>
                        
                        
                 	</div>