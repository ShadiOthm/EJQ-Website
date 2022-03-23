<?php
//echo sprintf(__("%s: avalie os serviços prestados por %s"), $serviceType['name'], $provider['name']);
for ($i=1; $i <= 5; $i++):
    echo $this->Html->image(
            '/img/star-128-off.png', 
            array(
                "title" => sprintf(__("Dar nota %d em uma escala de %d a %d"), $i, 1, 5), 
                'id'=> $demand['id'] . '-' . $i, 
                'height' => "24px;",
                'class' => 'ico evaluation_star',
                'evaluation' => $i,
                'demand' => $demand['id'],
                ));
    //echo '<span class="ico evaluation_star" id="' . $demand['id'] . '-' . $i . '" evaluation="' . $i . '" demand="' . $demand['id'] . '">&#xf006;</span>';
    
endfor;
?>