    <div class="content-box-edition" id="update_bid_value">
    <?php 
        if (empty($bidInfo['Bid']['value'])):
            $bidValue = 0;
        else:
            $bidValue = $bidInfo['Bid']['value'];
        endif;

        echo $this->Form->create('Bid', array(
            'url'=>array(
                'controller'=>'bids', 
                'action' => 'update_value',
                ), 
            'id' => 'update_bid_value',
            ));
        echo $this->Form->input('Bid.id');
        echo $this->Form->input('Demand.id');
        echo $this->Form->hidden('Tender.id');
        echo $this->Form->hidden('Provider.id');
        echo $this->Form->input('Bid.value', array(
            'div'=>array(
                'class'=>'form-group col-xs-7',
                ), 
            'label'=> array(
                'text' => __('Value (CAD):'), 
                'class' => 'control-label',
                ), 
            'class' => 'form-control',
            'min' => '0',
            'step' => '10.00',
            'value' => $bidValue 
            
            ));
        echo $this->Element('forms/submit');
        echo $this->Html->link($this->Html->tag('span', __('Cancel'), ['class' => 'badge badge-danger']), '#', array('escape' => false, 'title' => __('Cancel'), 'id' => 'hide_suggest_visit_time'));
        echo $this->Form->end();
    ?>
    </div>

