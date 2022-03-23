
        <header class="bg-info">
                <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12" id="bid_value">
                                <?php echo $this->Element('bids/value'); ?>
<!--                                <h2>Bid by Kenan Davies</h2>-->
                        </div>		
<?php
    if (!empty($bidsActions)):
?>
                        <div class="col-md-6 col-sm-6 col-xs-12 text-right">
<?php
foreach ($bidsActions as $keyAction => $action):    
?>
                            <a id="<?php echo $action['id'];?>" href="<?php echo $action['href'];?>" class="text-right btn btn-admin btn-sm" title="<?php echo $action['label'];?>" id=""><?php echo $action['label'];?></a>
<?php
endforeach;
?>
                        </div>	
<?php
    endif;
?>
                </div>
        </header>	
						<div class="inner-header">
							<div class="row">		
                                                                        <?php echo $this->Element('bids/shortlisting'); ?>
							</div>
						</div>	
						
						<br clear="all" />
						
                <?php 
                    if (!empty($bidsActions['choose_bid'])):
                ?>
        <div class="row">
            <div class="col-md-6">
                <?php 
                        echo $this->Element('bids/choose_bid');
                ?>
            </div>
        </div>
                <?php 
                    endif;
                ?>

                <?php 
                    if (!empty($thisIsChosenBid)):
                ?>
                <?php echo $this->Element('bids/chosen'); ?>
                <?php 
                    endif;
                ?>

            <?php echo $this->Element('bids/comments'); ?>
            <?php echo $this->Element('bids/terms_conditions'); ?>
