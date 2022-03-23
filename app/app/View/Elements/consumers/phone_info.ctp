                                <div class="content-box-edition update_phone" id="update_phone">
                                 <?php echo $this->Element('consumers/update_phone'); ?>
                                </div>
                                <p id="phone"><strong><?php echo __("Phone number:"); ?></strong> <?php echo $consumer['Consumer']['phone']; ?>                                    <?php
                echo $this->Html->link(
                        $this->Html->image("/img/icon-edit.png", array('title' => __('update phone number'))), 
                        '#show_update_phone', 
                        array(
                            'escape' => false, 
                            'title' => __('Edit phone number'), 
                            'class' => 'show_update_phone',
                            'id' => 'show_update_phone',
                            ));
                 ?>

                                </p>
