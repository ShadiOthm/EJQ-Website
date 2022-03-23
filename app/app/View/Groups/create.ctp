<h2 class="title">Adicionar Grupo <?php
	echo $this->Html->link(
		__('Cancelar'), array('controller' => 'groups', 'action' => 'index'), array('escape' => true)
	);
	?></h2>
<?php echo $this->Form->create('Group', array('novalidate' => true)); ?>
<?php
echo $this->Form->input('name', array('label' => __('Nome:')));
echo $this->Form->input('alias', array('label' => __('Alias:')));

/*
  echo $this->Form->input('ativo', array(
  'type' => 'checkbox',
  'label' => false,
  'before' => $this->Form->label('ativo', 'Ativo: '),
  'checked' => true,
  ) );
 */

echo '<div class="button">';
echo $this->Form->button('<span class="ico">&#xf00c;</span> ' . __('Confirmar'), array(
    'type' => 'submit',
    'escape' => false
));
echo '</div>';
echo $this->Form->end();