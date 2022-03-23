<?php 
echo $this->Html->script('bootstrap-datetimepicker.min.js');
echo $this->Html->css('bootstrap-datetimepicker.min.css');
echo $this->Html->script(array('home_owner_report_end.js?v=0.0.0'), false); 
?>
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
                    <li class="nav-item active"><a class="nav-link" data-toggle="tab" href="#home_owner_report_end" role="tab"><?php echo __('Report job ending'); ?></a></li>
                </ul>

                <div class="tab-content">
                    <?php echo $this->Element('jobs/home_owner_report_end'); ?>
           	</div>
            <!-- /col 9 -->
        </div>
        <!-- /row -->
