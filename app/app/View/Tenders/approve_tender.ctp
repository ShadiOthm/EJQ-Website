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
                    <li class="nav-item active"><a class="nav-link" data-toggle="tab" href="#approve_tender" role="tab"><?php echo __('Approve tender'); ?></a></li>
                </ul>

                <div class="tab-content">
                    <?php echo $this->Element('tenders/approve_tender'); ?>
           	</div>
            </div>
            <!-- /col 9 -->
        </div>
        <!-- /row -->
