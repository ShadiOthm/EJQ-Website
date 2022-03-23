<?php
// echo $this->Html->script(array('payment_methods'), false);
?>
<h2 class="title"><?php echo __('Alterar Modo de Pagamento'); ?><?php
	echo $this->Html->link(
		__('Cancelar'), array('controller' => 'payment_methods', 'action' => 'index'), array('escape' => true)
	);
	?></h2>
<?php echo $this->Form->create('PaymentMethod', array('novalidate' => true)); ?>
<?php
echo $this->Form->input('id');
echo $this->Form->input('name', array('label' => __('Nome:')));

echo '<div class="button">';
echo $this->Form->button('<span class="ico">&#xf00c;</span> ' . __('Confirmar'), array(
    'type' => 'submit',
    'escape' => false
));
echo '</div>';
echo $this->Form->end();
