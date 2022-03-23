            <div class="col-md-6">
<?php
echo $this->Form->create('User', array('url' => array('controller'=>'users', 'action' => 'login'), 'novalidate' => true));
?>
<?php

echo $this->Form->input('email', array('div'=>array('class'=>'form-group'), 'label'=> array('text' => __('Email'), 'class' => 'control-label'), 'required'=>false, 'class' => 'form-control'));
echo $this->Form->input('password', array('div'=>array('class'=>'form-group'), 'type' => 'password', 'label'=> array('text' => __('Password'), 'class' => 'control-label'), 'required'=>false, 'class' => 'form-control'));
echo $this->Html->link(
    __('Lost password?'), 
    array('controller' => 'users', 'action' => 'recover_password'), 
    array(
        'escape' => false, 
        'title' => __('Lost password?'),
    )
);
echo "<br />";
echo __("New to EasyJobQuote?") . "&nbsp;";
echo $this->Html->link(
    __("Sign Up"), 
    array('controller' => 'marketplaces', 'action' => 'register_consumer', $EjqMarketplaceId), 
    array(
        'escape' => false, 
        'title' => __('Sign Up'),
    )
);


$redirectUrlAfterLogin = $this->Session->read('redirect_url_after_login');
if(!empty($redirectUrlAfterLogin)) {
    if(strpos($redirectUrlAfterLogin, '/users/login') === false) {
        echo $this->Form->input('redirect_url_after_login', ['value'=>$redirectUrlAfterLogin, 'type'=>'hidden']);
    }
}
echo $this->Form->submit(__('Login'), array(
        'div'=>array('class'=>'form-group'), 
        'class' => 'btn btn-default',
        'alt' => __('Login'),
        'title' => __('Login'),
        ));
echo $this->Form->end();

?>
            </div>
            <!-- sidebar -->
            <div class="col-offset-2 col-md-4 pull-right">
            
            </div>
            <!-- /sidebar -->
