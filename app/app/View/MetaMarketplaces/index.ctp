<h2 class="title">MetaMarketplaces</h2>

<div id="actions">
	<?php
	//adicionar
	echo $this->Html->link('<span class="ico">&#xf067;</span> Adicionar MetaMarketplace', array('controller' => 'meta_marketplaces', 'action' => 'create'), array('escape' => false));
	?>
</div><!-- #actions-->
<p class="pagination">
	<?php echo $this->element('pagination', array('position' => 'top')); ?>
</p>
<table cellpadding="0" cellspacing="0">
        <tr>
		<th><?php echo $this->Paginator->sort('name', __('Nome:')); ?></th>
		<th><?php echo $this->Paginator->sort('purpose', __('Propósito:')); ?></th>
		<?php /* <th><?php echo $this->Paginator->sort('active'); ?></th> */ ?>
		<th class="actions pagination">&nbsp;</th>
        </tr>
	<?php
	//debug($marketplaces);
	foreach ($metaMarketplaces as $metaMarketplace):
		?>
		<tr>
			<td><?php echo h($metaMarketplace['MetaMarketplace']['name']); ?>&nbsp;</td>
			<td><?php echo h($metaMarketplace['MetaMarketplace']['purpose']); ?>&nbsp;</td>
			<td class="actions">
				<?php
				echo $this->Html->link(
					'&#xf044;', array(
				    'action' => 'update',
				    $metaMarketplace['MetaMarketplace']['id']
					), array('escape' => false, 'title' => __('Alterar'))
				);
				echo $this->Html->link(
					'&#xf00d;', array('action' => 'delete', $metaMarketplace['MetaMarketplace']['id']), array('escape' => false, 'title' => __('Excluir')), __('Tem certeza que deseja excluir o Marketplace %s?', $metaMarketplace['MetaMarketplace']['name'])
				);
				?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
<p class="pagination">
	<?php echo $this->element('pagination', array('position' => 'bottom')); ?>
</p>

