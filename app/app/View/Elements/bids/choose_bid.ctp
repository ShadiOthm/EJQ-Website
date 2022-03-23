                        <div class="content-box-edition" id="choose_bid">
 <?php
                echo $this->Form->create('Bid', array('novalidate' => true, 'url'=>array('controller'=>'bids', 'action' => 'choose'), 'id' => 'choose_bid_form'));
                echo $this->Form->input('Bid.id');

                echo $this->Element('forms/submit');

                echo '<a src= "#" class="" id="hide_form_choose_bid">' . __('Cancelar') . '</a>';
                //echo '<a src= "#" class="btn btn-cancel pull-right" id="hide_form_choose_bid">' . __('Cancelar') . '</a>';
                echo $this->Form->end();
        ?>
                        </div>