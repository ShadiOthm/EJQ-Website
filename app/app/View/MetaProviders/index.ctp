<h2 class="title"><?php echo __('MetaProviders'); ?></h2>

<p class="pagination">
	<?php echo $this->element('pagination', array('position' => 'top')); ?>
</p>
<table cellpadding="0" cellspacing="0">
        <tr>
		<th><?php echo $this->Paginator->sort('name', __('Nome:')); ?></th>
		<th><?php echo $this->Paginator->sort('slug', __('Alias:')); ?></th>
		<?php /* <th><?php echo $this->Paginator->sort('active'); ?></th> */ ?>
		<th class="actions pagination">&nbsp;</th>
        </tr>
	<?php
	//debug($metaProviders);
	foreach ($metaProviders as $metaProvider):
		?>
		<tr>
			<td><?php echo h($metaProvider['MetaProvider']['name']); ?>&nbsp;</td>
			<td><?php echo h($metaProvider['MetaProvider']['slug']); ?>&nbsp;</td>
			<td class="actions">
                            <?php
                            echo $this->Html->link(
                                '&#xf044;',
                                array(
                                    'action' => 'update',
                                    $metaProvider['MetaProvider']['id']
                                ), 
                                array('escape' => false, 'title' => __('Alterar'))
                            );
                            echo $this->Html->link(
                                    '&#xf00d;', array('action' => 'delete', $metaProvider['MetaProvider']['id']), array('escape' => false, 'title' => __('Excluir')), __('Tem certeza que deseja excluir o MetaProvider %s?', $metaProvider['MetaProvider']['name'])
                            );
                            ?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
<p class="pagination">
	<?php echo $this->element('pagination', array('position' => 'bottom')); ?>
</p>

