<?php
echo $this->Html->script('//cdn.jsdelivr.net/momentjs/latest/moment.min.js');
//echo $this->Html->script('//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js');
//echo $this->Html->css('//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css');
echo $this->Html->script('bootstrap-datetimepicker.min.js');
echo $this->Html->css('bootstrap-datetimepicker.min.css');
echo $this->Html->script(array('jquery.colorbox-min.js?v=0.0.0'), false); 
?>
<?php echo $this->Html->script(array('tenders_details.js?v=0.0.0'), false); ?>
<?php echo $this->Html->script(array('bids_details.js?v=0.0.0'), false); ?>
<?php echo $this->Html->script(array('invoices_details.js?v=0.0.0'), false); ?>
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
                    <li class="nav-item<?php echo (empty($activeTab) || $activeTab == 'description' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#description" role="tab"><?php echo __('Description'); ?></a></li>
                    <li class="nav-item<?php echo ($activeTab == 'images' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#images" role="tab"><?php echo __('Images'); ?></a></li>
                    <li class="nav-item<?php echo ($activeTab == 'terms' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#terms" role="tab"><?php echo __("T's & C's"); ?></a></li>
<?php

    $status = $tenderInfo['Demand']['status'];
    
    $canSeeBids = false;
    $canSeeChosenBidContractorDetails = false;
    $canSeeEvaluation = false;
    if ($canAccessAdm):
        if ($status == EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS ||
            $status == EJQ_DEMAND_STATUS_TENDER_CLOSED_TO_BIDS ||
            $status == EJQ_DEMAND_STATUS_TENDER_OPEN_FOR_BID_SELECTION ||
            $status == EJQ_DEMAND_STATUS_BID_DISCLOSED ||
            $status == EJQ_DEMAND_STATUS_BID_SELECTED
            ):
            $canSeeBids = true;
        endif;
        if ($status == EJQ_DEMAND_STATUS_BID_SELECTED ||
            $status == EJQ_DEMAND_STATUS_BID_DISCLOSED ||
            $status == EJQ_DEMAND_STATUS_CONTRACT_SIGNED ||
            $status == EJQ_DEMAND_STATUS_JOB_IN_PROGRESS ||
            $status == EJQ_DEMAND_STATUS_JOB_COMPLETED 
            ):
            $canSeeChosenBidContractorDetails = true;
        endif;
        if ($status == EJQ_DEMAND_STATUS_JOB_COMPLETED):
            $canSeeEvaluation = true;
        endif;
    endif;

    if ($isDemandHomeOwner):
        if ($status == EJQ_DEMAND_STATUS_BID_SELECTED ||
            $status == EJQ_DEMAND_STATUS_TENDER_OPEN_FOR_BID_SELECTION
            ):
        $canSeeBids = true;
        endif;
        if ($status == EJQ_DEMAND_STATUS_BID_DISCLOSED ||
            $status == EJQ_DEMAND_STATUS_CONTRACT_SIGNED ||
            $status == EJQ_DEMAND_STATUS_JOB_IN_PROGRESS ||
            $status == EJQ_DEMAND_STATUS_JOB_COMPLETED 
            ):
            $canSeeChosenBidContractorDetails = true;
        endif;
        if ($status == EJQ_DEMAND_STATUS_JOB_COMPLETED):
            $canSeeEvaluation = true;
        endif;
    endif;
?>
<?php
    if ($canSeeBids):


?>
                    <li class="nav-item<?php echo ($activeTab == 'bids' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#bids" role="tab"><?php echo __('Bids'); ?></a></li>
<?php
endif;
?>
<?php
    if ($canSeeChosenBidContractorDetails):
?>
                    <li class="nav-item<?php echo ($activeTab == 'contractor' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#contractor" role="tab"><?php echo __('Chosen Contractor'); ?></a></li>

<?php
endif;
?>
<?php

    $canSeeBidders = false;
    if ($canAccessAdm && (
            $status == EJQ_DEMAND_STATUS_TENDER_IN_PROGRESS ||
            $status == EJQ_DEMAND_STATUS_TENDER_READY_FOR_HOME_OWNER_APPROVAL ||
            $status == EJQ_DEMAND_STATUS_TENDER_READY_FOR_SITE_ADMIN_APPROVAL ||
            $status == EJQ_DEMAND_STATUS_TENDER_TO_BE_MODIFIED ||
            $status == EJQ_DEMAND_STATUS_TENDER_READY_TO_BIDS ||
            $status == EJQ_DEMAND_STATUS_TENDER_BIDDING_SCHEDULED ||
            $status == EJQ_DEMAND_STATUS_TENDER_OPEN_TO_BIDS ||
            $status == EJQ_DEMAND_STATUS_TENDER_CLOSED_TO_BIDS ||
            $status == EJQ_DEMAND_STATUS_TENDER_OPEN_FOR_BID_SELECTION ||
            $status == EJQ_DEMAND_STATUS_BID_SELECTED
            )):
        $canSeeBidders = true;
    endif;

    if ($canSeeBidders):
?>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#bidders" role="tab"><?php echo __('Elegible Bidders'); ?></a></li>
<?php
endif;
?>
<?php
    if ($canSeeEvaluation):


?>
                    <li class="nav-item<?php echo ($activeTab == 'review' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#review" role="tab"><?php echo __('Review'); ?></a></li>
<?php
endif;
?>
<?php
    if ($canAccessAdm):
?>
                    <li class="nav-item<?php echo ($activeTab == 'billing' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#billing" role="tab"><?php echo __('Billing'); ?></a></li>
<?php
    endif;
?>
</ul>

                <div class="tab-content">
                    <!-- Description -->
                    <?php echo $this->Element('tenders/description'); ?>
                    <!-- Tender Images -->
                    <?php echo $this->Element('tenders/images'); ?>
                    <!-- Terms and Conditions -->
                    <?php echo $this->Element('tenders/terms_conditions'); ?>
                    <!-- Bids -->

<?php
    if ($canSeeBids):

?>
                    <div class="content-box tab-pane fade<?php echo ($activeTab == 'bids' ? ' in active' : ''); ?>" id="bids" role="tabpanel">
                    <?php echo $this->Element('tenders/home_owner_tender_details_tender_bids'); ?>
                 	</div>
                        <?php
    endif;
?>
<?php
    if ($canSeeChosenBidContractorDetails):

?>
                    <div class="content-box tab-pane fade<?php echo ($activeTab == 'contractor' ? ' in active' : ''); ?>" id="contractor" role="tabpanel">
                    <?php echo $this->Element('tenders/chosen_contractor_details'); ?>
                 	</div>
                        <?php
    endif;
?>
<?php
    if ($canSeeBidders):

?>
                    <div class="content-box tab-pane fade" id="bidders" role="tabpanel">
                    <?php echo $this->Element('tenders/elegible_bidders'); ?>
                 	</div>
                        <?php
    endif;
?>
<?php
    if ($canSeeEvaluation):

?>
                    <div class="content-box tab-pane fade<?php echo ($activeTab == 'review' ? ' in active' : ''); ?>" id="review" role="tabpanel">
                    <?php echo $this->Element('tenders/review'); ?>
                 	</div>
                        <?php
    endif;
?>
<?php
    if ($canAccessAdm):
?>
                <?php echo $this->Element('tenders/billing'); ?>
<?php
    endif;
?>
                </div>
           	</div>
            <!-- /col 9 -->
        </div>
        <!-- /row -->
