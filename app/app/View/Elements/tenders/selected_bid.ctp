<?php
if (isset($tenderInfo['Bid']['0'])):
    $bids = $tenderInfo['Bid'];
?>
<section id="bids">
<h2><?php echo __('Selected Bid'); ?></h2>
<p><?php echo __('A bid was selected'); ?></p>
</section>
<?php
endif;
?>