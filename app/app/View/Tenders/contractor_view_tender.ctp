<?php
echo $this->Html->script(array('jquery.colorbox-min.js?v=0.0.0'), false); 
echo $this->Html->script(array('demands_contractor_view_tender.js?v=0.0.0')); 
?>
        <!-- row -->
        <div class="row">
            <div class="col-md-12 bg-info2">
                <p>To make a bid you need:</p>
                <ol>
                    <li>Go through the list of Terms and Conditions and accept or reject each one</li>
                    <li>Enter a $ value for your bid</li>
                    <li>Submit the bid to EJQ</li>
                </ol>
            </div>
            <!-- col 3 -->
            <div class="col-md-3">

                <h3 class="form-title"><?php echo __('Basic Info'); ?> </h3>

               	<div class="content-box" style="font-size:13px;">

                    <p><strong><?php echo __("Job categories:"); ?></strong> <?php echo $tenderInfo['Demand']['services_list_description']; ?></p>

                    <p><strong><?php echo __("Municipality"); ?>:</strong> <?php echo $tenderInfo['Municipality']['name']; ?></p>

                <?php
                $someCondition = true;
    if (!empty($tenderInfo['Tender']['disclosed_bid'])):
        if (!empty($tenderInfo['Consumer']['phone'])):
?>
                    <p><strong><?php echo __("Home Owner Phone number:"); ?></strong> <?php echo $tenderInfo['Consumer']['phone']; ?></p>
<?php
        endif;
    endif;
?>
                </div>

            </div>
            <!-- /col 1 de 1 -->
            <!-- col 9 -->
            <div class="col-md-9">
                <ul class="nav nav-tabs">
                    <li class="nav-item<?php echo (empty($activeTab) || $activeTab == 'description' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#description" role="tab"><?php echo __('Description'); ?></a></li>
                    <li class="nav-item<?php echo ($activeTab == 'images' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#images" role="tab"><?php echo __('Images'); ?></a></li>
                    <li class="nav-item<?php echo ($activeTab == 'terms' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#terms" role="tab"><?php echo __("T's & C's"); ?></a></li>
                    <li class="nav-item<?php echo ($activeTab == 'bid_value' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#bid_value" role="tab"><?php echo  __("Bid Value and Contractor Comments"); ?></a></li>
                </ul>

                <div class="tab-content">
                    <!-- Description -->
                    <?php echo $this->Element('tenders/description'); ?>
                    <!-- Tender Images -->
                    <?php echo $this->Element('tenders/images'); ?>
                    <!-- Terms and Conditions -->
                    <?php echo $this->Element('tenders/terms_conditions_acceptance'); ?>
                    <!-- Bids -->

                    <div class="content-box tab-pane fade<?php echo ($activeTab == 'bid_value' ? ' in active' : ''); ?>" id="bid_value" role="tabpanel">
                    <?php echo $this->Element('tenders/bid_value'); ?>
                 	</div>
                </div>
           	</div>
            <!-- /col 9 -->
        </div>
        <!-- /row -->
