<div class="register_form">

<h2 class="title">
	<?php echo __('Register user as Site Admin.'); ?>
</h2>

<?php

echo $this->Form->create('Administrator', array('url'=>array('controller'=>'marketplaces', 'action' => 'create_site_admin')));
echo $this->Form->hidden('marketplace_id');

if (!$knownUser):
    echo $this->Form->input('user_name', array('label'=>false, 'placeholder'=>__('seu nome'), 'required'=>false));
    echo $this->Form->input('email', array('label'=>false, 'placeholder'=>__('seu email'), 'required'=>false));
else:
    echo $this->Form->hidden('user_id');
endif;

echo $this->Form->submit(__('Register Site Admin'));

echo $this->Form->end();

?>

</div><!--  register form-->