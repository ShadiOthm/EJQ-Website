                        <div class="col-md-9 content-box">
                                <div class="content-box-edition" id="update_about_info">
                                 <?php echo $this->Element('contractors/update_about_info'); ?>
                                </div>
                                <div id="about_info">
                                    <h4><?php echo __("About Info:"); ?><?php
                echo $this->Html->link(
                        $this->Html->image("/img/icon-edit.png", array('title' => __('update description of company'))), 
                        '#show_update_about_info', 
                        array(
                            'escape' => false, 
                            'title' => __('Edit description of company'), 
                            'id' => 'show_update_about_info',
                            ));
                 ?></h4>
<?php 
    if (empty($provider['Contractor']['0']['about'])):
?>                                    
                                <p id="name_position"><?php echo __("Please provide a description of the company"); ?></p>
<?php 
    else:
?>
<?php 
        if (!empty($provider['Contractor']['0']['about'])):
?>                                    
                                <p id="name"><strong><?php echo __("Company description:"); ?></strong><p><?php echo nl2br(h($provider['Contractor']['0']['about'])); ?></p>
<?php 
        endif;
?>                                    
<?php 
    endif;
?>                                    
                                </div>
                                <hr />
                        </div>