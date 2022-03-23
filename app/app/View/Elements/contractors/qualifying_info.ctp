<?php 
        if ($canAccessAdm):
?>                                    
                        <div class="col-md-9">
                                <h4><?php echo __("Qualified for bidding?"); ?></h4>
                                <div id="update_company_qualifying">
 <?php
        echo $this->Form->create('Contractor', array('url'=>array('controller'=>'contractors', 'action' => 'toggle_qualify'), 'tab' => 'my_info'));
        echo $this->Form->input('id');
        echo $this->Form->input(
            "Provider.qualified",  
             array(
                'div'=>array('class'=>'form-group col-xs-12'), 
                'type' => 'checkbox',
                'class' => 'check toggle_qualify one-click-checkbox',
                'label' => false,
                'before' => '<label for="ConsumerAgreed">',
                'after' =>  '&nbsp;' . __('This contractor is qualified for bidding?') . '</label>',
                'checked' => $this->request->data['Provider']['qualified'],
                'required'=>true, 
            ) );
        echo $this->Form->end();
?>
                                </div>        
                                <div id="update_company_qualifying">
 <?php
        echo $this->Form->create('Contractor');
        echo $this->Form->input('id');
        echo $this->Form->input(
            "Provider.qualified",  
             array(
                'div'=>array('class'=>'form-group col-xs-12'), 
                'type' => 'checkbox',
                'class' => 'check disabled',
                'label' => false,
                'before' => '<label for="ConsumerAgreed">',
                'after' =>  '&nbsp;' . __('This contractor is in good standing regarding invoices?') . '</label>',
                'checked' => $this->request->data['Provider']['good_standing'],
                'required'=>true, 
            ) );
        
        
        
        echo $this->Form->end();
?>
                                </div>        
                                <div style='clear:both'></div>
                                <hr />
                            </div>
<?php 
    endif;
?>