                                    <p class="<?php echo $feedbackClass; ?>"><strong><?php echo $feedbackMessage; ?></strong></p>
 <?php
        echo $this->Form->create('Contractor', array('url'=>array('controller'=>'contractors', 'action' => 'update_company_disclosure'), 'tab' => 'my_info'));
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