<div class="register_form">
<?php echo $this->Element('marketplace_h1_logo', array('marketplace' => $provider['Marketplace'])); ?>
<?php echo $this->element('back_to_provider_profile', array('id' => $provider['Provider']['id'], 'name' => $provider['Marketplace']['name']) ); ?>

<h2 class="title">
	<?php printf(__('Informe a disponibilidade para %s'), $name); ?>
</h2>

<?php

echo $this->Form->create('ProviderWeekdays', array('url'=>array('controller'=>'providers', 'action' => 'update_weekdays', $id, $serviceTypeId)));
echo $this->Form->hidden('id');
echo $this->Form->hidden('service_type_id');
echo $this->Form->input('weekdays', array('multiple' => 'checkbox', 'options' => $optionsWeekDays, 'selected' => $selectedWeekDays, 'label'=>__('Que dias da semana?')));
echo $this->Form->submit(__('Atualizar'));
echo $this->Form->end();

?>

</div>
