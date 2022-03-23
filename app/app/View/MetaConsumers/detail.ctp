<h2 class="title"><?php echo __('Resumo do MetaConsumer'); ?></h2>
<?php
    echo $this->Html->link(
            __('Editar MetaConsumer'), array('controller' => 'meta_consumers', 'action' => 'update', $metaConsumer['MetaConsumer']['id'], 'adm' => false), array('escape' => false, 'class' => 'more_link')
    );
    ?>
<p class="meta_consumer_info"><?php echo __('MetaMarketplace: '); ?><?php echo $this->Html->link(
        $metaConsumer['MetaMarketplace']['name'], array('controller' => 'meta_marketplaces', 'action' => 'detail', $metaConsumer['MetaMarketplace']['id'], 'adm' => false), array('escape' => false)
); ?></p>
<p class="meta_consumer_info"><?php echo __('Nome:'); ?> <?php echo $metaConsumer['MetaConsumer']['name']; ?></p>


