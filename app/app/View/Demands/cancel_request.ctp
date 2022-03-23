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
                    <li class="nav-item active"><a class="nav-link" data-toggle="tab" href="#cancel_request" role="tab"><?php echo __('Cancel tender request'); ?></a></li>
                </ul>
                
                <div class="tab-content">
                    <?php echo $this->Element('requests/cancel_request'); ?>
                </div>

                 
            </div>
            <!-- /col 9 -->
        </div>
        <!-- /row -->