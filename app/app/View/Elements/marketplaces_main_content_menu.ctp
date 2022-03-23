<header class="mainContentMenu">
    <span class="menuLabel"><?php echo __('Ir para:'); ?></span>
    <nav>
        <ul>
            <li class="first"><?php
                echo $this->Html->link(
                        h(__('Marketplaces Publicados')), array(
                    'controller' => 'main', 'action' => 'marketplaces'
                        ), array('escape' => false, 'title' => __('Ir para a página principal dos Marketplaces'))
                );
                ?>
                
            </li>
<?php
    if ($canAccessAdm):
?>
            <li><?php
                echo $this->Html->link(
                        h(__('MetaMarketplaces')), array(
                    'controller' => 'main', 'action' => 'metamarketplaces'
                        ), array('escape' => false, 'title' => __('Ir para a página principal dos MetaMarketplaces'))
                );
                ?>
                
            </li>            
<?php
    endif;
?>
        </ul>
    </nav>
<?php
    if ((isset($marketplace['logo_image']))
            && (!is_null($marketplace['logo_image']))):

        $imagePath = FOLDER_LOGO . "/" . $marketplace['logo_image'];
        echo $this->Html->image($imagePath, array("title" => __("logo atual"), "height" => "80px;", 'class' => 'mini-logo-image clear'));
    endif;
?>
<p class="marketplace_name">

<?php
    echo $this->Html->link(
            $marketplace['name'], array('controller' => 'main', 'action' => 'index', 'adm' => false), array('escape' => false, 'title' => __('saiba mais'))
    ); 
?>
</p>
</header>
