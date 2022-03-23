                    <div class="content-box tab-pane fade<?php echo ($activeTab == 'my_info' ? ' in active' : ''); ?>" id="my_info" role="tabpanel">   
                        <div class="col-md-9">
                            <h3 class="form-title"><?php echo __("My Info:"); ?></h3>
                            <div class="content-box" style="font-size:13px;">
                                <p><strong><?php echo __("Name:"); ?></strong> <?php echo $consumer['Consumer']['name']; ?></p>
                                 <?php echo $this->Element('consumers/phone_info'); ?>

            <?php
                if (!empty($consumer['Municipality']['name'])):
            ?>
                                <p><strong><?php echo __("Municipality"); ?>:</strong> <?php echo $consumer['Municipality']['name']; ?></p>
            <?php
                endif;
            ?>
            <?php
                $actionLabel = "";
                $icon = "/img/icon-edit.png";
                if (!empty($consumer['Consumer']['address'])):
                    //$actionLabel = __('update address');
                    $icon = "/img/icon-edit.png";
                else:
                    $actionLabel = __('add address');
                    $icon = "/img/icon-add.png";
                endif;
            ?>
                        <div class="content-box-edition" id="update_address">
 <?php
        echo $this->Form->create('Consumer', array('url'=>array('controller'=>'consumers', 'action' => 'update_address'), 'id' => 'update_address'));
        echo $this->Form->input('id');
        echo $this->Form->input(
                'address', 
                array(
                    'div'=>array('class'=>'form-group'), 
                    'class' => 'form-control',
                    'label'=> array('text' => __('Address'), 'class' => 'control-label'),
                    'type' => 'textarea',
                    'rows' => '3',
                    ));
        
        echo $this->Element('forms/submit');
        echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#', array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_update_address'));
        echo $this->Form->end();
        ?>
                        </div>
            <?php
                if (!empty($consumer['Consumer']['address'])):
            ?>
                                <p id="address"><strong><?php echo __("Address:"); ?></strong><br /><?php echo nl2br(h($consumer['Consumer']['address'])); ?>
                            <?php
        echo $this->Html->link(
                $this->Html->image($icon) . $actionLabel, 
                '#show_update_address', 
                array(
                    'escape' => false, 
                    'title' => $actionLabel, 
                    'id' => 'show_update_address',
                    ));
         ?>
                                </p>
            <?php
                else:
            ?>
                        <div class="control" id="control_update_address">
                            <?php
        echo $this->Html->link(
                $this->Html->image($icon) . $actionLabel, 
                '#show_update_address', 
                array(
                    'escape' => false, 
                    'title' => $actionLabel, 
                    'id' => 'show_update_address',
                    ));
         ?>
                            
                        </div>
            <?php
                endif;
            ?>
                            </div>

                        </div>
                    </div>