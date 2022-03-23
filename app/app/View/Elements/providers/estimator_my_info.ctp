                    <div class="content-box tab-pane fade<?php echo ($activeTab == 'my_info' ? ' in active' : ''); ?>" id="my_info" role="tabpanel">   
                        <p><?php
                        echo $this->Html->link(
                                '&nbsp;&nbsp;<span class="ico">&#xf044;</span> Define My Job Categories', '#update_service_types', array('escape' => false, 'title' => __('Define Job Categories'), 'id' => 'show_update_service_types')
                        );
                        ?></p>
                        <div class="content-box-edition col-md-4" id="provider_service_types">
                        <?php
                        echo $this->Form->create('Provider', array('url'=>array('controller'=>'providers', 'action' => 'update_service_types'), 'id' => 'form_update_service_types'));
                        echo $this->Form->input('id');
                        echo $this->Form->input("ServiceType.ServiceType", array(
                            'multiple' => 'checkbox', 
                            'options' => $optionsServiceTypes, 
                            'selected' => $selectedServiceTypes,
                            'label'=>__('Please inform your categories jobs'), 
                            'class' => 'check',
                            ));
                        echo $this->Element('forms/submit');
                        echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#', array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_form_update_service_types'));
                        echo $this->Form->end();
                                ?>
                        </div>
                    </div> 

