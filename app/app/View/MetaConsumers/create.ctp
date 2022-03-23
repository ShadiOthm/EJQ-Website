<h2 class="title">Adicionar MetaConsumer&nbsp;|&nbsp;<?php
	echo $this->Html->link(
		__('Cancelar'), array('controller' => 'meta_marketplaces', 'action' => 'detail', $metaMarketplaceId), array('escape' => true)
	);
	?></h2>
<?php echo $this->Form->create('MetaConsumer', array('novalidate' => true)); ?>
<?php
echo $this->Form->hidden('meta_marketplace_id', array('value' => $metaMarketplaceId));
echo $this->Form->input('name', array('label' => 'Nome:'));
echo $this->Form->input('payment_method_id', array('label' => __('Modo de Pagamento:'), 'empty' => 'Escolha um modo de pagamento'));

echo '<div class="button">';
echo $this->Form->button('<span class="ico">&#xf00c;</span> ' . __('Confirmar'), array(
    'type' => 'submit',
    'escape' => false
));
echo '</div>';
echo $this->Form->end();
