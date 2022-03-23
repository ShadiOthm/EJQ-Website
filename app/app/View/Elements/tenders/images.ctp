                    
                    <div class="content-box tab-pane fade<?php echo ($activeTab == 'images' ? ' in active' : ''); ?>" id="images" role="tabpanel">   
                    
                    	<div class="title-box-admin clearfix">
                            <h3><?php echo __('Tender images'); ?></h3>                            
                        </div>
<?php
    if (!empty($rights['tender'])):
?>
                        <div class="control"  id="control_add_image">
                            <?php
        echo $this->Html->link(
                $this->Html->image("/img/icon-add.png") . __('add image'), 
                '#show_add_image', 
                array(
                    'escape' => false, 
                    'title' => __('Add image'), 
                    'id' => 'show_add_image',
                    ));
         ?>
                        </div>

                        <div class="content-box-edition" id="add_image">
 <?php
        echo $this->Form->create('Tender', array('novalidate' => true, 'type' => 'file', 'url'=>array('controller'=>'tenders', 'action' => 'add_image'), 'id' => 'add_image'));
        echo $this->Form->input('Demand.id');
        echo $this->Form->hidden('Tender.id');
        echo $this->Form->input(
                'Demand.image', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('Image File:'), 'class' => 'control-label'),
                    'type' => 'file',
                    ));
        echo $this->Form->input(
                'Demand.image_description', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('Image description:'), 'class' => 'control-label'),
                    'type' => 'textarea',
                    'rows' => '2',
                    ));
        
        echo $this->Element('forms/submit');
        echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#', array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_add_image'));
        echo $this->Form->end();
        ?>
                        </div>
<?php
endif;
?>
                        <!-- row -->
                        <div class="row">
            <?php
                if (!empty($tenderInfo['File'])):
                    $imagesCounter = 0;
                    foreach ($tenderInfo['File'] as $key => $imageData):
                        $imagePath = $imageData['path'] .
                                DS .
                                $imageData['filename'];
                        $label = nl2br(h($imageData['description']));
            ?>
<?php
    if ($imagesCounter == 3):
        $imagesCounter = 0
            ?>
                        </div>
                        <div class="row">
<?php
    endif;
?>                            
                            
                            <div class="col-md-4">
<?php
                echo $this->Html->link(
                        $this->Html->image(
                                $imagePath,
                                array(
                                    "title" => $label,
                                    'class' => 'img-responsive tender-detail-img',
                                    )), $imagePath, array('class' => 'tender_images','escape' => false)
                );
                        
                        ?> 
                                <p id="tender_image_caption-<?php echo $imageData['id']; ?>"><?php echo $label; ?></p>
<?php
    if (!empty($rights['tender'])):
?>
                                <div class="control" id="control_update_tender_image-<?php echo $imageData['id']; ?>">
                                    <?php
                echo $this->Html->link(
                        $this->Html->image("/img/icon-edit.png", array('title' => __('edit caption'))), 
                        '#show_update_tender_image-'. $imageData['id'], 
                        array(
                            'escape' => false, 
                            'title' => __('Edit caption'), 
                            'class' => 'show_update_tender_image_caption',
                            'id' => 'show_update_tender_image_caption-'. $imageData['id'],
                            ));
                echo $this->Html->link(
                        $this->Html->image("/img/icon-delete.png", array('title' => __('remove image'))), 
                        array(
                            'controller' => 'demands', 
                            'action' => 'remove_tender_image', 
                            $imageData['id']),
                        array(
                            'escape' => false, 
                            'title' => __('remove image'), 
                            'id' => 'show_update_tender_image-'. $imageData['id'],
                            ), 
                        __('Are you sure you want to remove this image?'));
                 ?>
                                </div>
                                   <div class="content-box-edition update_tender_image_caption" id="update_tender_image_caption-<?php echo $imageData['id']; ?>">
 <?php
        echo $this->Form->create('Demand', array('url'=>array('controller'=>'demands', 'action' => 'update_tender_image_caption'), 'id' => 'form_update_tender_image-'. $imageData['id']));
        echo $this->Form->input('id');
        echo $this->Form->hidden('Tender.id');
        echo $this->Form->hidden('File.id', array('value' => $imageData['id']));
        echo $this->Form->input(
                'File.description', 
                array(
                    'value' => $imageData['description'],
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('Image description:'), 'class' => 'control-label'),
                    'type' => 'textarea',
                    'rows' => '8',
                    ));
        
        
        echo $this->Element('forms/submit');
        echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#tender_image-' . $imageData['id'], array('escape' => false, 'title' => __('Cancel'), 'class' => 'hide_form_update_tender_image_caption', 'id' => 'hide_form_update_tender_image-' . $imageData['id']));
        echo $this->Form->end();
        ?>
                                </div>
<?php
endif;
?>
                            </div>
<?php
    $imagesCounter++;
?>                            
                            
                            
            <?php

                    endforeach;
                endif;

            ?>
                        </div>
                    </div>