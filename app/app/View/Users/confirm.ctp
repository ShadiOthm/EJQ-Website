<?php
//echo $this->Html->script(array('jquery/jquery-1.11.1.min', 'common'), array('inline'=>false));
?>

            <div class="col-md-6">
<div id="left">


	<?php

	echo $this->Form->create( 'User', array('url' => '/users/confirm/' . $t ) );

//	echo $this->Form->input('newpassword', array('label'=>false, 'placeholder'=>__('New Password'), 'type'=>'password', 'required' => false));
//	echo $this->Form->input('confirmpassword', array('label'=>false, 'placeholder'=>__('Confirm New Password'), 'type'=>'password', 'required' => false));
        echo $this->Form->input('newpassword', 
                array(
                    'type'=>'password',
                    'div'=>array(
                        'class'=>'form-group',
                        ), 
                    'label'=> array(
                        'text' => __('Confirm New Password'), 
                        'class' => 'control-label',
                        ), 
                    'required'=>true, 
                    'class' => 'form-control',
                    ));
    
        echo $this->Form->input('confirmpassword', 
                array(
                    'type'=>'password',
                    'div'=>array(
                        'class'=>'form-group',
                        ), 
                    'label'=> array(
                        'text' => __('New Password'), 
                        'class' => 'control-label',
                        ), 
                    'required'=>true, 
                    'class' => 'form-control',
                    ));
    
        echo $this->Form->submit(__('Create Password'), array(
                'div'=>array('class'=>'form-group'), 
                'class' => 'btn btn-default',
                'alt' => __('Create Password'),
                'title' => __('Create Password'),
                ));


	echo $this->Form->end();

	?>


</div>

            </div>
            <!-- sidebar -->
            <div class="col-offset-2 col-md-4 pull-right">
            
            </div>
            <!-- /sidebar -->
