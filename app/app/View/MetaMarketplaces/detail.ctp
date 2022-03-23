<header class="summary_comands">
    <?php 
                echo $this->Html->link(
                        h(__('Editar')), array(
                    'controller' => 'meta_marketplaces', 'action' => 'update',
                    $metaMarketplace['MetaMarketplace']['id']
                        ), array('escape' => false, 'title' => __('Editar o MetaMarketplace'))
                );

                ?>
</header>
<section class="meta_marketplace_summary">
    <header>
    <?php
if ((isset($metaMarketplace['MetaMarketplace']['logo_image']))
        && (!is_null($metaMarketplace['MetaMarketplace']['logo_image']))):
    
    $imagePath = FOLDER_LOGO . "/" . $metaMarketplace['MetaMarketplace']['logo_image'];
    echo $this->Html->image($imagePath, array('alt' => __('Logo atual'), 'class' => 'logo-image'));
endif;


?>

    <h1><?php echo sprintf(__('Resumo do Marketplace "%s"'), $metaMarketplace['MetaMarketplace']['name']); ?></h1>
    </header>
    <p class='clear'>&nbsp;</p>
    <?php
if ((isset($metaMarketplace['MetaMarketplace']['cover_image']))
        && (!is_null($metaMarketplace['MetaMarketplace']['cover_image']))):
    
    $imagePath = FOLDER_COVER . "/" . $metaMarketplace['MetaMarketplace']['cover_image'];
    echo $this->Html->image($imagePath, array("title" => __('Imagem principal atual'), 'class' => 'clear cover-image'));
endif;


?>

    <h2><?php echo __('Propósito:'); ?></h2>
    <p><?php echo $metaMarketplace['MetaMarketplace']['purpose']; ?></p>
    <h2><?php echo __('Descrição:'); ?></h2>
    <p><?php echo nl2br(h($metaMarketplace['MetaMarketplace']['description'])); ?></p>
        
<?php
if ($canBePublished):
?>
<section id="publish_meta_marketplace">
    <button type="button" onclick="window.location.href='<?php 
        echo Router::url(array('controller'=>'meta_marketplaces', 'action'=>'publish', $metaMarketplace['MetaMarketplace']['id']))
        ?>'">
        <?php echo '<span class="ico">&#xf090;</span>&nbsp; ' . __('Publicar Marketplace'); ?>
    </button>
</section>
    
<?php    
    
else:
    if (isset($metaMarketplace['Marketplace']['0'])):
    
?>
    <h2><?php echo __('Esse MetaMarketplace já foi publicado.'); ?></h2>    
<?php    
    
    else:
    
?>
    <h2><?php echo __('Esse MetaMarketplace não pode ser publicado.'); ?></h2>    
<?php    
    endif;
    
endif;
?>    
</section>

<section class="meta_marketplace_service_types">
    <h1><?php echo __('Tipos de serviço'); ?></h1>
<?php
if (!isset($metaMarketplace['Marketplace']['0'])):
?>
    <button type="button" onclick="window.location.href='<?php 
        echo Router::url(array('controller'=>'service_types', 'action'=>'create', $metaMarketplace['MetaMarketplace']['id']));
        ?>'">
        <?php echo '<span class="ico">&#xf090;</span>&nbsp; ' . __('Criar ServiceType'); ?>
    </button>
<?php
endif;
?>
<?php
if (isset($metaMarketplace['ServiceType']['0'])) :
?>
    <table class="list_table">
    <tr>
        <th><?php echo $this->Paginator->sort('name', __('Nome:')); ?></th>
        <th class="actions pagination">&nbsp;</th>
    </tr>
    <?php
    foreach ($metaMarketplace['ServiceType'] as $key => $serviceType):
    ?>
    <tr>
        <td><?php 
                echo $serviceType['name'];

                ?>&nbsp;</td>
        <td class="actions">
        <?php
        echo $this->Html->link(
                '&#xf044;', array(
            'controller' => 'service_types', 'action' => 'update',
            $serviceType['id']
                ), array('escape' => false, 'title' => __('Alterar'))
        );
        echo $this->Html->link(
                '&#xf00d;', array('controller' => 'service_types', 'action' => 'delete', $serviceType['id']), array('escape' => false, 'title' => __('Excluir')), __('Tem certeza que deseja excluir o Tipo de Serviço %s?', $serviceType['name'])
        );
        ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>
<?php
endif;
?>    
    
    
</section>


<section class="meta_marketplace_meta_providers">
    <h1><?php echo __('MetaProvider'); ?></h1>
<?php
if (isset($metaMarketplace['MetaProvider']['0'])) :
?>
    <table class="list_table">
    <tr>
        <th><?php echo $this->Paginator->sort('name', __('Nome:')); ?></th>
        <th class="actions pagination">&nbsp;</th>
    </tr>
    <?php
    foreach ($metaMarketplace['MetaProvider'] as $key => $metaProvider):
    ?>
    <tr>
        <td><?php 
                echo $metaProvider['name'];

                ?>&nbsp;</td>
        <td class="actions">
        <?php
        echo $this->Html->link(
                '&#xf044;', array(
            'controller' => 'meta_providers', 'action' => 'update',
            $metaProvider['id']
                ), array('escape' => false, 'title' => __('Alterar'))
        );
        echo $this->Html->link(
                '&#xf00d;', array('controller' => 'meta_providers', 'action' => 'delete', $metaProvider['id']), array('escape' => false, 'title' => __('Excluir')), __('Tem certeza que deseja excluir o Tipo de Serviço %s?', $metaProvider['name'])
        );
        ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>
<?php
endif;
?>
    <button type="button" onclick="window.location.href='<?php 
        echo Router::url(array('controller'=>'meta_providers', 'action'=>'create', $metaMarketplace['MetaMarketplace']['id']))
        ?>'">
        <?php echo '<span class="ico">&#xf090;</span>&nbsp; ' . __('Criar MetaProvider'); ?>
    </button>
    
    
</section>


<section class="meta_marketplace_meta_consumers">
    <h1><?php echo __('MetaConsumer'); ?></h1>
<?php
if (isset($metaMarketplace['MetaConsumer']['0'])) :
?>
    <table class="list_table">
    <tr>
        <th><?php echo $this->Paginator->sort('name', __('Nome:')); ?></th>
        <th class="actions pagination">&nbsp;</th>
    </tr>
    <?php
    foreach ($metaMarketplace['MetaConsumer'] as $key => $metaConsumer):
    ?>
    <tr>
        <td><?php 
                echo $metaConsumer['name'];

                ?>&nbsp;</td>
        <td class="actions">
        <?php
        echo $this->Html->link(
                '&#xf044;', array(
            'controller' => 'meta_consumers', 'action' => 'update',
            $metaConsumer['id']
                ), array('escape' => false, 'title' => __('Alterar'))
        );
        echo $this->Html->link(
                '&#xf00d;', array('controller' => 'meta_consumers', 'action' => 'delete', $metaConsumer['id']), array('escape' => false, 'title' => __('Excluir')), __('Tem certeza que deseja excluir o Tipo de Serviço %s?', $metaConsumer['name'])
        );
        ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>
<?php
else:
?>
    <button type="button" onclick="window.location.href='<?php 
        echo Router::url(array('controller'=>'meta_consumers', 'action'=>'create', $metaMarketplace['MetaMarketplace']['id']))
        ?>'">
        <?php echo '<span class="ico">&#xf090;</span>&nbsp; ' . __('Criar MetaConsumer'); ?>
    </button>
<?php
endif;
?>
    
    
</section>
