<h2 class="title"><?php printf(__('Publicar Marketplace "%s"'), $metaMarketplaceName); ?> <?php
	
	?></h2>
<?php echo $this->Form->create('MetaMarketplace', array('novalidate' => true)); ?>
<?php
echo $this->Form->hidden('id');
echo $this->Form->hidden('status');
//echo $this->Form->input('name', array('label' => 'Nome:'));
//echo $this->Form->input('purpose', array('label' => 'Propósito:'));
//echo $this->Form->input('description', array('label' => 'Descriçao:'));


echo '<div class="button">';
echo $this->Form->button('<span class="ico">&#xf00c;</span> Publicar', array(
    'type' => 'submit',
    'escape' => false
));
echo '</div>';
echo $this->Form->end();
?>
<p class="meta_provider_info"><?php echo __('Voltar para '); ?><?php echo $this->Html->link(
        $metaMarketplaceName, array('controller' => 'meta_marketplaces', 'action' => 'detail', $metaMarketplaceId, 'adm' => false), array('escape' => false)
); ?></p>

