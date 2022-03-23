<h1><?php echo $marketplace['name']; ?></h1>
<?php
if ((isset($marketplace['logo_image']))
        && (!is_null($marketplace['logo_image']))):
    
    $imagePath = FOLDER_LOGO . "/" . $marketplace['logo_image'];
    echo $this->Html->link( 
            $this->Html->image(
                    $imagePath, 
                    array(
                        "title" => __("logo atual"), 
                        "height" => "80px;", 
                        'class' => 'logo-marketplace'
                    )
                ), 
                array(
                    'controller' => 'marketplaces', 
                    'action' => 'detail', 
                    $marketplace['id'], 
                    'adm' => false
                    ), 
                array('escape' => false, 'title' => __('Ir para a página do marketplace'))
            );
else:
    echo '<br />';
    echo $this->Html->link(
            __('Ir para a página do marketplace'), array('controller' => 'marketplaces', 'action' => 'detail', $marketplace['id'], 'adm' => false), array('escape' => false)
    );
endif;
?>
