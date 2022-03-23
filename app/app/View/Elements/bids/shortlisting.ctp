<div class="col-md-8">
    <div class="bid_shortlisting">
<?php
    if(!empty($isDemandHomeOwner) && $isDemandHomeOwner === TRUE):
        if ((empty($bidInfo['Bid']['shortlisted'])) 
                || ($bidInfo['Bid']['shortlisted'] === FALSE)):
    ?>
    <?php
                echo $this->Html->link(
                        '<span class="ico">&#xf00c;</span> ' . __("Add to shortlist"), '#add_to_shortlist-' . $bidInfo['Bid']['id'], array('escape' => false, 'class' => 'add_to_shortlist', 'title' => __('Add to shortlist'), 'id' => 'add_to_shortlist-' . $bidInfo['Bid']['id'])
                );
                 ?>
                     <?php
    else:
    ?>
    <span class="shortlist_status"><span class="ico"><img class="ico-check" src="/img/check.png" /></span> <?php echo __("This bid was shortlisted"); ?></span> <?php
                echo $this->Html->link(
                        "(" . 
                        $this->Html->tag('span',
                            $this->Html->image(
                                    "/img/remove.png", 
                                    array(
                                        'title' => __('Remove from shortlist'),
                                        'class' => 'ico-remove',
                                    )
                                ), 
                            ['class' => 'ico']
                        ) .  __('Remove from shortlist') . ")"
                        ,
                        '#add_to_shortlist-' . $bidInfo['Bid']['id'], 
                        array(
                            'escape' => false, 
                            'title' => __('Remove from shortlist'), 
                            'alt' => __('Remove from shortlist'), 
                            'class' => 'remove_from_shortlist',
                            'id' =>  'remove_from_shortlist-' . $bidInfo['Bid']['id'],
                            ));
//                echo $this->Html->link(
//                        '<span class="ico"><img class="ico-remove" src="img/remove.png" /></span> ' . __("Remove from shortlist"), '#remove-from-shortlist-' . $bidInfo['Bid']['id'], array('escape' => false, 'class' => 'remove_from_shortlist', 'title' => __("Remove from shortlist"), 'id' => 'remove_from_shortlist-' . $bidInfo['Bid']['id'])
//                );
                 ?><?php 
            endif;
         endif;
        ?>

                 
    </div>
</div>
    <div class="col-md-4 text-right">
<?php
                echo $this->Html->link(
                        $this->Html->tag('span',
                            $this->Html->image(
                                    "/img/list.png", 
                                    array(
                                        'title' => __('Back to bids list'),
                                        'class' => 'ico-list',
                                    )
                                ), 
                            ['class' => 'ico']
                        ) .  __('Back to bids list')
                        ,
                        '#back_to_bids_list', 
                        array(
                            'escape' => false, 
                            'title' => __('Back to bids list'), 
                            'alt' => __('Back to bids list'), 
                            'class' => 'back_to_bids_list',
                            'id' =>  'back_to_bids_list-' . $bidInfo['Tender']['id'],
                            ));


?>                    
    </div>
    
    