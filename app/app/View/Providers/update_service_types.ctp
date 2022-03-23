<?php
//echo $this->Html->script(array('jquery/jquery-1.11.1.min', 'common'), array('inline'=>false));
?>
<div class="register_form">
<?php echo $this->element('back_to_provider_profile', array('id' => $provider['Provider']['id'], 'name' => $provider['Marketplace']['name']) ); ?>
<h2 class="title">
	<?php echo __('Escolha os serviços que você quer oferecer.'); ?>
</h2>
<?php
echo $this->Form->create('Provider', array('url'=>array('controller'=>'providers', 'action' => 'update_service_types')));
echo $this->Form->input('id');
echo $this->Form->input('ServiceType.ServiceType', array('multiple' => 'checkbox', 'options' => $optionsServiceTypes, 'selected' => $selectedServiceTypes, 'label'=>__('Que serviços quer fornecer?')));
echo $this->Form->submit(__('Atualizar'));
echo $this->Form->end();
?>
</div><!--  register form-->