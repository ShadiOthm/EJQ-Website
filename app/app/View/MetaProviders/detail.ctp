<h2 class="title"><?php echo __('Resumo do MetaProvider'); ?></h2>
<?php
    echo $this->Html->link(
            __('Editar MetaProvider'), array('controller' => 'meta_providers', 'action' => 'update', $metaProvider['MetaProvider']['id'], 'adm' => false), array('escape' => false, 'class' => 'more_link')
    );
    ?>
<p class="meta_provider_info"><?php echo __('MetaMarketplace: '); ?><?php echo $this->Html->link(
        $metaProvider['MetaMarketplace']['name'], array('controller' => 'meta_marketplaces', 'action' => 'detail', $metaProvider['MetaMarketplace']['id'], 'adm' => false), array('escape' => false)
); ?></p>
<p class="meta_provider_info"><?php echo __('Nome:'); ?> <?php echo $metaProvider['MetaProvider']['name']; ?></p>
<?php 
if (isset($metaProvider['ServiceType']['0'])) :
?>
<div class="list_box">
             
<h3 class="title"><?php echo __('Tipos de serviço'); ?></h3>
                <ul>
    <?php
        foreach ($metaProvider['ServiceType'] as $key => $serviceType):
            ?>
            <li>
                <div class="service_type_line"><?php echo $serviceType['name'] ?>
                </div>
                
            </li>                    
                    
<?php    
endforeach;
?>
                </ul>     

</div>
                
<?php    
 endif;
 ?>
<?php 
if (isset($metaProvider['MetaProviderAttribute']['0'])) :
?>
<div class="list_box">
<h3 class="title"><?php echo __('MetaAttributes'); ?></h3>
                <ul>
    <?php
        foreach ($metaProvider['MetaProviderAttribute'] as $key => $metaProviderAttribute):
            ?>
            <li>
                <div class="service_type_line"><?php echo $metaProviderAttribute['name'] ?>
                </div>
                
            </li>                    
                    
<?php    
endforeach;
?>
                </ul>     
</div>
<?php    
 endif;
 ?>

