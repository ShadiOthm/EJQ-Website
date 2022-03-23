<?php //echo $this->Html->script('confirm-bootstrap.js'); ?>
<?php echo $this->Html->script(array('tenders_details.js?v=0.0.0'), false); ?>
        <!-- row -->
        <div class="row">
            <!-- col 3 -->
            <div class="col-md-3">
            <?php echo $this->Element('tenders/request_info'); ?>
            </div>
            <!-- /col 1 de 1 -->
            <!-- col 9 -->
            <div class="col-md-9">

                <ul class="nav nav-tabs">
                    <li class="nav-item active"><a class="nav-link" data-toggle="tab" href="#submit_to_home_owner" role="tab"><?php echo __('Submit tender to Home Owner Review?'); ?></a></li>
                </ul>

                <div class="tab-content">
                    <?php echo $this->Element('tenders/submit_to_home_owner'); ?>
           	</div>
            </div>
            <!-- /col 9 -->
        </div>
        <!-- /row -->
