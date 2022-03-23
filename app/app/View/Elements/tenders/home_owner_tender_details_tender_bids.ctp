

                    	<div class="title-box-admin clearfix">
<?php
echo $this->Html->css('bids_style.css?v=0.0.0');

if ((empty($shortlist))
        || ($shortlist === FALSE)):
?>
                            <h3><?php echo __('All Bids'); ?></h3>
                            <div class="control"  id="control_add_condition_to_tender">
<?php
            echo $this->Html->link(
                     __('see shortlist'), 
                    '#see_shortlist-' . $tenderInfo['Tender']['id'], 
                    array(
                        'class' => 'see_shortlist',
                        'escape' => false, 
                        'title' => __('See just shortlist'), 
                        'id' => 'see_shortlist-' . $tenderInfo['Tender']['id'],
                        ));
             ?>
                            </div>
                 <?php
else:
?>                                
                            <h3><?php echo __('Shortlist'); ?></h3>
                            
                            <div class="control"  id="control_add_condition_to_tender">
<?php
            echo $this->Html->link(
                     __('see all bids'), 
                    '#see-all-bids-' . $tenderInfo['Tender']['id'], 
                    array(
                        'class' => 'see_all_bids',
                        'escape' => false, 
                        'title' => __('See all bids'), 
                        'id' => 'see_all_bids-' . $tenderInfo['Tender']['id'],
                        ));
             ?>
                            </div>
                 <?php 
                 endif;

?>
                        </div>

<?php
$chosenBidId = null;
if (isset($chosenBid['Bid'])):
    $chosenBidId = $chosenBid['Bid']['id'];
    if ($tenderInfo['Demand']['status'] == EJQ_DEMAND_STATUS_BID_DISCLOSED ||
            $tenderInfo['Demand']['status'] == EJQ_DEMAND_STATUS_CONTRACT_SIGNED ||
            $tenderInfo['Demand']['status'] == EJQ_DEMAND_STATUS_JOB_IN_PROGRESS ||
            $tenderInfo['Demand']['status'] == EJQ_DEMAND_STATUS_JOB_COMPLETED):
?>
<h1><?php echo __('Chosen Bid details'); ?></h1>
<?php 
        echo $this->Element('tenders/bids_list', ['bids' => [$chosenBid['Bid']], 'chosenBidId' => $chosenBidId]); ?>
<?php
    endif;
endif;
?>

<?php
if (isset($tenderInfo['Bid']['0'])):
    $bids = $tenderInfo['Bid'];
?>
                <?php echo $this->Element('tenders/bids_list', ['bids' => $bids, 'chosenBidId' => $chosenBidId]); ?>

<?php
else:
?>
<p><?php echo __('There are no bids for this tender.'); ?></p>
<?php
endif;
?>
