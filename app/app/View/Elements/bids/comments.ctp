<?php
    if (!empty($bidInfo['Bid']['comments'])):

 ?>
        <div class="row" id="comments">
                <div class="col-md-12">
                       <h4><?php echo __('Contractor Comments') ?></h4>
                    	<p id="contractor_comments"><?php echo nl2br(h($bidInfo['Bid']['comments'])); ?></p>
                 </div>
                        
        </div>
                        
<?php
    endif;

?>