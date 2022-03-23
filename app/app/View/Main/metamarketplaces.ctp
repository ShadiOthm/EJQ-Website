<h1><?php echo __('MetaMarketplaces existentes'); ?></h2>
<section id="main_metamarketplaces_list">
<?php
if (empty($metaMarketplaces)):
?>
    <p><?php echo __('Não existem MetaMarketplaces cadastrados.');?></p>    
    
<?php
else:
?>
    
    <table class="list_table">
    <tr>
        <th><?php echo $this->Paginator->sort('name', __('Nome:')); ?></th>
        <th class="actions pagination">&nbsp;</th>
    </tr>
    <?php
    foreach ($metaMarketplaces as $metaMarketplace):
    ?>
    <tr>
        <td><?php
                echo $this->Html->link(
                        h($metaMarketplace['MetaMarketplace']['name']), array(
                    'controller' => 'meta_marketplaces', 'action' => 'detail',
                    $metaMarketplace['MetaMarketplace']['id']
                        ), array('escape' => false, 'title' => __('Ir para a página do MetaMarketplace'))
                );
                
                if(!empty($metaMarketplace['Marketplace'])):
                    echo '<span class="actions" title="' . __('MetaMarketplace publicado') . '">&#xf0c1;</span>';
                endif;


                ?>&nbsp;</td>
        <td class="actions">
        <?php
        echo $this->Html->link(
                '&#xf044;', array(
            'controller' => 'meta_marketplaces', 'action' => 'update',
            $metaMarketplace['MetaMarketplace']['id']
                ), array('escape' => false, 'title' => __('Alterar'))
        );
        echo $this->Html->link(
                '&#xf00d;', array('controller' => 'meta_marketplaces', 'action' => 'delete', $metaMarketplace['MetaMarketplace']['id']), array('escape' => false, 'title' => __('Excluir')), __('Tem certeza que deseja excluir o MetaMarketplace %s?', $metaMarketplace['MetaMarketplace']['name'])
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

