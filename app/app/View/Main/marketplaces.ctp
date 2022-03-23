<h1><?php echo __('Marketplaces'); ?></h1>
<section id="marketplaces_main">
<?php
    $first = true;
    $open = false;
    $last = false;
    foreach ($marketplaces as $metaKey => $marketplace):
        
?>
<?php
        if ($first):
            echo '<div class="clear">';
            $open = true;
            $first = false;
        else:
            $last = true;
        endif;
?>    
<article>
    <?php
        if ((isset($marketplace['Marketplace']['logo_image']))
                && (!is_null($marketplace['Marketplace']['logo_image']))):

            $imagePath = FOLDER_LOGO . "/" . $marketplace['Marketplace']['logo_image'];
            echo $this->Html->image($imagePath, array("title" => __("logo atual"), "height" => "80px;", 'class' => 'mini-logo-image'));
        endif;
?>
    <p class="marketplace_name">
   
<?php
        echo $this->Html->link(
                $marketplace['Marketplace']['name'], array('controller' => 'marketplaces', 'action' => 'detail', 'adm' => false, $marketplace['Marketplace']['id']), array('escape' => false, 'title' => __('saiba mais'))
        ); 
        if (isset($marketplace['Provider'])):

            echo $this->Html->link(
                    '&nbsp;&nbsp;<span class="ico">&#xf007;</span>', array('controller' => 'providers', 'action' => 'profile', 'adm' => false, $marketplace['Provider']['id']), array('escape' => false, 'class' => 'more_link', 'title' => __('saiba mais'))
            ); 

        endif;
        if (isset($marketplace['Consumer'])):
            echo $this->Html->link(
                    '&nbsp;&nbsp;<span class="ico">&#xf007;</span>', array('controller' => 'consumers', 'action' => 'profile', 'adm' => false, $marketplace['Consumer']['id']), array('escape' => false, 'class' => 'more_link', 'title' => __('Ir para meu perfil neste marketplace'))
            ); 

        endif;
                    ?>
    </p>
</article>
<?php
        if ($last):
            echo '</div>';
            $last = false;
            $open = false;
            $first = true;
        endif;
?>    
<?php
    endforeach;
    if ($open):
        echo '</div>';
    endif;
?>   
</section>
<div class="clear">&nbsp;</div>
    <div style="clear: both"></div>

