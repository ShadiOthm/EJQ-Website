                    <div class="content-box tab-pane fade<?php echo ($activeTab == 'my_info' ? ' in active' : ''); ?>" id="my_info" role="tabpanel">   
                        <div class="col-md-12">
                            <div class="content-box" style="font-size:13px;">
                                <h2><?php echo (empty($provider['Contractor']['0']['name'])?$provider['Contractor']['0']['contact_name']:$provider['Contractor']['0']['name']);?></h2>
                                 <?php echo $this->Element('contractors/qualifying_info'); ?>
                                 <?php echo $this->Element('contractors/about'); ?>
                                 <?php echo $this->Element('contractors/services'); ?>
                                 <?php echo $this->Element('contractors/contact_info'); ?>
                                 <?php echo $this->Element('contractors/company_disclosure'); ?>
                                 <?php echo $this->Element('contractors/licences'); ?>
                            </div>

                        </div>
                    </div>