            <div class="col-md-6">
<?php

echo $this->Form->create( 'User', array('url' => array('controller' => 'users', 'action' => 'recover_password'), 'novalidate' => true ));

    echo $this->Form->input('email_recover', 
            array(
                'div'=>array(
                    'class'=>'form-group',
                    ), 
                'label'=> array(
                    'text' => __('Account\'s email'), 
                    'class' => 'control-label',
                    ), 
                'required'=>false, 
                'class' => 'form-control',
                ));
    
echo $this->Form->submit(__('Send'), array(
        'div'=>array('class'=>'form-group'), 
        'class' => 'btn btn-default',
        'alt' => __('Send'),
        'title' => __('Send'),
        ));

echo $this->Form->end();

echo $this->Html->link(
    '&laquo; ' . __('Back to login'), 
    array('controller' => 'users', 'action' => 'login'), 
    array(
        'escape' => false, 
        'title' => __('Back to login'),
    )
);

?>
            </div>
            <!-- sidebar -->
            <div class="col-offset-2 col-md-4 pull-right">
            
            </div>
            <!-- /sidebar -->
