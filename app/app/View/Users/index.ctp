<h2 class="title">Usuários</h2>

<div id="actions">
	<?php
	//adicionar
	echo $this->Html->link('<span class="ico">&#xf067;</span> Adicionar Usuário', array('controller' => 'users', 'action' => 'create'), array('escape' => false));
	?>
</div><!-- #actions-->
<p class="pagination">
	<?php echo $this->element('pagination', array('position' => 'top')); ?>
</p>
<table cellpadding="0" cellspacing="0">
        <tr>
		<th><?php echo $this->Paginator->sort('name', __('Nome:')); ?></th>
		<th><?php echo $this->Paginator->sort('alias', __('Alias:')); ?></th>
		<?php /* <th><?php echo $this->Paginator->sort('active'); ?></th> */ ?>
		<th class="actions pagination">&nbsp;</th>
        </tr>
	<?php
	//debug($users);
	foreach ($users as $user):
		?>
		<tr>
			<td><?php echo h($user['User']['name']); ?>&nbsp;</td>
			<td><?php echo h($user['User']['email']); ?>&nbsp;</td>
			<td class="actions">
				<?php
				echo $this->Html->link(
					'&#xf044;', array(
				    'action' => 'update',
				    $user['User']['id']
					), array('escape' => false, 'title' => __('Alterar'))
				);
				echo $this->Html->link(
					'&#xf00d; delete', array('action' => 'delete', $user['User']['id']), array('escape' => false, 'title' => __('Excluir')), __('Tem certeza que deseja excluir o Usuário %s?', $user['User']['name'])
				);
				?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
<p class="pagination">
	<?php echo $this->element('pagination', array('position' => 'bottom')); ?>
</p>

