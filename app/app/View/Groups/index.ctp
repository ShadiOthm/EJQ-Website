<h2 class="title"><?php echo __('Grupos'); ?></h2>

<div id="actions">
	<?php
	//adicionar
	echo $this->Html->link(
                '<span class="ico">&#xf067;</span>' .  __('Adicionar Grupo'), 
                array('controller' => 'groups', 'action' => 'create'), 
                array('escape' => false));
	?>
</div><!-- #actions-->
<p class="paginacao">
	<?php echo $this->element('pagination', array('position' => 'top')); ?>
</p>
<table cellpadding="0" cellspacing="0">
        <tr>
		<th><?php echo $this->Paginator->sort('nome', __('Nome:')); ?></th>
		<th><?php echo $this->Paginator->sort('alias', __('Alias:')); ?></th>
		<?php /* <th><?php echo $this->Paginator->sort('ativo'); ?></th> */ ?>
		<th class="actions paginacao">&nbsp;</th>
        </tr>
	<?php
	//debug($users);
	foreach ($groups as $group):
		?>
		<tr>
			<td><?php echo h($group['Group']['name']); ?>&nbsp;</td>
			<td><?php echo h($group['Group']['alias']); ?>&nbsp;</td>
			<td class="actions">
				<?php
				echo $this->Html->link(
					'&#xf044;', array(
				    'action' => 'update',
				    $group['Group']['id']
					), array('escape' => false, 'title' => __('Alterar'))
				);
				echo $this->Html->link(
					'&#xf00d;', array('action' => 'delete', $group['Group']['id']), array('escape' => false, 'title' => __('Excluir')), __('Tem certeza que deseja excluir o Grupo %s?', $group['Group']['name'])
				);
				?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
<p class="paginacao">
	<?php echo $this->element('pagination', array('position' => 'bottom')); ?>
</p>

