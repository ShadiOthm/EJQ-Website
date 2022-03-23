<h2 class="title"><?php echo __('Tipos de Serviços'); ?></h2>

<div id="actions">
	<?php
	//adicionar
	echo $this->Html->link('<span class="ico">&#xf067;</span> ' . __('Adicionar ServiceType'), array('controller' => 'service_types', 'action' => 'create', 1), array('escape' => false));
	?>
</div><!-- #actions-->
<p class="pagination">
	<?php echo $this->element('pagination', array('position' => 'top')); ?>
</p>
<table cellpadding="0" cellspacing="0">
        <tr>
		<th><?php echo $this->Paginator->sort('name', __('Nome:')); ?></th>
		<th><?php echo $this->Paginator->sort('description', __('Descrição:')); ?></th>
		<th class="actions pagination">&nbsp;</th>
        </tr>
	<?php
	//debug($servicetypes);
	foreach ($servicetypes as $servicetype):
		?>
		<tr>
			<td><?php echo h($servicetype['ServiceType']['name']); ?>&nbsp;</td>
			<td><?php echo h($servicetype['ServiceType']['description']); ?>&nbsp;</td>
			<td class="actions">
				<?php
				echo $this->Html->link(
					'&#xf044;', array(
				    'action' => 'update',
				    $servicetype['ServiceType']['id']
					), array('escape' => false, 'title' => __('Alterar'))
				);
				echo $this->Html->link(
					'&#xf00d;', array('action' => 'delete', $servicetype['ServiceType']['id']), array('escape' => false, 'title' => __('Excluir')), __('Tem certeza que deseja excluir o ServiceType %s?', $servicetype['ServiceType']['name'])
				);
				?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
<p class="pagination">
	<?php echo $this->element('pagination', array('position' => 'bottom')); ?>
</p>

