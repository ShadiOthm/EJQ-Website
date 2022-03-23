                        <div class="col-md-9">
                            <h4><?php echo __("Job categories:"); ?></h4> 
                        <div class="content-box-edition<?php echo (empty($showServices)?'': ' force_show') ?>" id="update_service_types">
                        <?php
                        echo $this->Form->create('Provider', array('url'=>array('controller'=>'providers', 'action' => 'update_service_types'), 'id' => 'form_update_service_types'));
                        echo $this->Form->input('id');
                        foreach($optionsServiceTypes as $keyService => $nameService):
                            echo $this->Form->input("ServiceType.ServiceType.$keyService", array(
                            'div' => ['class' => 'check'],
                            'label' => false,
                            'value' => $keyService,
                            'type' => 'checkbox',
                            'before' => '<label for="ServiceTypeServiceType">',
                            'after' =>  "&nbsp;$nameService</label>",
                            'hiddenField' => false,
                            'checked' => (in_array($keyService, $selectedServiceTypes) ? 'checked' : null),
                            ));



                        endforeach;
//                        echo $this->Form->input("ServiceType.ServiceType", array(
//                            'multiple' => 'checkbox', 
//                            'options' => $optionsServiceTypes, 
//                            'selected' => $selectedServiceTypes,
//                            'label'=>__('Please inform what services the company can provide'), 
//                            'class' => 'check',
//                            ));
                        echo $this->Element('forms/submit');
                        echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#', array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_update_service_types'));
                        echo $this->Form->end();
                                ?>
                        </div>
<?php
        if (!empty($provider['Contractor']['0']['services_list_description'])):
?>
                                <p id ="admin_services_list"><?php echo $provider['Contractor']['0']['services_list_description']; ?></p>
<?php
        endif;
?>                        <p><?php
                        echo $this->Html->link(
                                '<span class="ico">&#xf044;</span> ' . __('Define Job Categories'), '#update_service_types', array('escape' => false, 'title' => __('Define Job Categories'), 'id' => 'show_update_service_types')
                        );
                        ?></p>
                        <hr />
                    </div>











