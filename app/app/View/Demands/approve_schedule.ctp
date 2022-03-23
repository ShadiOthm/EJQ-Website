<?php 
echo $this->Html->script(array('demands_request_details.js?v=0.0.0'), false); 
?>
        <!-- row -->
        <div class="row">
            <!-- col 3 -->
            <div class="col-md-3">
                <?php echo $this->Element('requests/main_info'); ?>
            </div>
            <!-- /col 1 de 1 -->
            <!-- col 9 -->
            <div class="col-md-9">
            	
                <ul class="nav nav-tabs">
                    <li class="nav-item active"><a class="nav-link" data-toggle="tab" href="#approve_schedule" role="tab"><?php echo __('Approve Schedule'); ?></a></li>
                </ul>
                
                <div class="tab-content">
                    <?php echo $this->Element('requests/approve_schedule'); ?>
                </div>

                 
            </div>
            <!-- /col 9 -->
        </div>
        <!-- /row -->