<h2 class="title"><?php echo __('Modo de Pagamentos'); ?></h2>

<div id="actions">
	<?php
	//adicionar
	echo $this->Html->link('<span class="ico">&#xf067;</span> ' . __('Adicionar Modo de Pagamento '), array('controller' => 'payment_methods', 'action' => 'create'), array('escape' => false));
	?>
</div><!-- #actions-->
<p class="pagination">
	<?php echo $this->element('pagination', array('position' => 'top')); ?>
</p>
<table cellpadding="0" cellspacing="0">
        <tr>
		<th><?php echo $this->Paginator->sort('name', __('Nome:')); ?></th>
		<?php /* <th><?php echo $this->Paginator->sort('active'); ?></th> */ ?>
		<th class="actions pagination">&nbsp;</th>
        </tr>
	<?php
	//debug($paymentMethods);
	foreach ($paymentMethods as $paymentMethod):
		?>
		<tr>
			<td><?php echo h($paymentMethod['PaymentMethod']['name']); ?>&nbsp;</td>
			<td class="actions">
				<?php
				echo $this->Html->link(
					'&#xf044;', array(
				    'action' => 'update',
				    $paymentMethod['PaymentMethod']['id']
					), array('escape' => false, 'title' => __('Alterar'))
				);
				echo $this->Html->link(
					'&#xf00d;', array('action' => 'delete', $paymentMethod['PaymentMethod']['id']), array('escape' => false, 'title' => __('Excluir')), __('Tem certeza que deseja excluir o Modo de Pagamento %s?', $paymentMethod['PaymentMethod']['name'])
				);
				?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
<p class="pagination">
	<?php echo $this->element('pagination', array('position' => 'bottom')); ?>
</p>

