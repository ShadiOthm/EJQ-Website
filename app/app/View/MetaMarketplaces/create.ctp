<h2 class="title"><?php echo __('Adicionar MetaMarketplace'); ?>&nbsp;|&nbsp;<?php
	echo $this->Html->link(
		__('Cancelar'), array('controller' => 'users', 'action' => 'profile'), array('escape' => true)
	);
	?></h2>
<?php echo $this->Form->create('MetaMarketplace', array('novalidate' => true, 'type' => 'file')); ?>
<?php
echo $this->Form->hidden('curator_id');
echo $this->Form->input('name', array('label' => __('Nome:')));
echo $this->Form->input('purpose', array('label' => __('Propósito:')));
echo $this->Form->input('description', array('label' => __('Descriçao:')));
echo $this->Form->input('logo_image',array('type' => 'file', 'label' => __('Imagem da logo:')));
echo $this->Form->input('cover_image',array('type' => 'file', 'label' => __('Imagem da capa:')));

echo '<div class="button">';
echo $this->Form->button('<span class="ico">&#xf00c;</span> ' . __('Confirmar'), array(
    'type' => 'submit',
    'escape' => false
));
echo '</div>';
echo $this->Form->end();
