<?php
// echo $this->Html->script(array('meta_marketplaces'), false);
?>
<h2 class="title"><?php echo _('Alterar Marketplace'); ?>&nbsp;|&nbsp;<?php
	echo $this->Html->link(
		_('Cancelar'), array('controller' => 'meta_marketplaces', 'action' => 'detail', $this->data['MetaMarketplace']['id']), array('escape' => true)
	);
	?></h2>
<?php echo $this->Form->create('MetaMarketplace', array('novalidate' => true, 'type' => 'file')); ?>
<?php
echo $this->Form->input('id');
echo $this->Form->input('name', array('label' => 'Nome:'));
echo $this->Form->input('purpose', array('label' => 'Propósito:'));
echo $this->Form->input('description', array('label' => 'Propósito:'));
if ((isset($this->data['MetaMarketplace']['logo_image']))
        && (!is_null($this->data['MetaMarketplace']['logo_image']))):
    
    $imagePath = FOLDER_LOGO . "/" . $this->data['MetaMarketplace']['logo_image'];
    echo $this->Html->image($imagePath, array("title" => "logo atual", "width" => "240px;"));
    $imageVerb = __("Alterar imagem do logo");
else:
    $imageVerb = __("Enviar imagem do logo");
        
endif;

echo $this->Form->input('logo_image',array('type' => 'file', 'label' => $imageVerb));
    

if ((isset($this->data['MetaMarketplace']['cover_image']))
        && (!is_null($this->data['MetaMarketplace']['cover_image']))):
    
    $imagePath = FOLDER_COVER . "/" . $this->data['MetaMarketplace']['cover_image'];
    echo $this->Html->image($imagePath, array("title" => "cover atual", "width" => "240px;"));
    $imageVerb = __("Alterar imagem da capa");
else:
    $imageVerb = __("Enviar imagem da capa");
        
endif;

echo $this->Form->input('cover_image',array('type' => 'file', 'label' => $imageVerb));
    


echo '<div class="button">';
echo $this->Form->button('<span class="ico">&#xf00c;</span> ' . __('Confirmar'), array(
    'type' => 'submit',
    'escape' => false
));
echo '</div>';
echo $this->Form->end();
