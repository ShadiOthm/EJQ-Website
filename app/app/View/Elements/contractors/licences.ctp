                        <div class="col-md-9">
                                <div class="content-box-edition" id="update_licences">
                                 <?php echo $this->Element('contractors/update_licences'); ?>
                                </div>
                                <div id="licences">
                                    <h4><?php echo __("Licences and trade certificates:"); ?><?php
                echo $this->Html->link(
                        $this->Html->image("/img/icon-edit.png", array('title' => __('update licences list'))), 
                        '#show_update_licences', 
                        array(
                            'escape' => false, 
                            'title' => __('Edit licences list'), 
                            'id' => 'show_update_licences',
                            ));
                 ?></h4>
<?php 
    if (empty($provider['Contractor']['0']['licences_and_certifications'])):
?>                                    
                                <p id="name_position"><?php echo __("Please inform about Licences and trade certificates"); ?></p>
<?php 
    else:
?>
                                <p id="name"><strong><?php echo __("Licences and trade certificates:"); ?></strong> <?php echo nl2br(h($provider['Contractor']['0']['licences_and_certifications'])); ?></p>
<?php 
    endif;
?>                                    
                                </div>
                        </div>