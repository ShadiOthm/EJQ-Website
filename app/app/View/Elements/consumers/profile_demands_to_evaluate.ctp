<?php
if (isset($demandsToEvaluate['0'])):
?>
    
<section id="demands_to_evaluate">
<h2><?php echo __('Você tem as seguintes demanda(s) para avaliar:'); ?></h2>
    <table class="list_table">
    <tr>
        <th class="info"><?php echo __('Tipo de Serviço:'); ?></th>
        <th class="info"><?php echo __('Fornecido por'); ?></th>
        <th class="info"><?php echo __('Data'); ?></th>
        <th class="info"><?php echo __('Sua Avaliação'); ?></th>
    </tr>
    <?php
    //debug($demandsToEvaluate);exit;
    foreach ($demandsToEvaluate as $key => $tenderInfo):
        
    ?>
    <tr>
        <td><?php
            echo $tenderInfo['ServiceType']['name'];
                ?>
        </td>
        <td class="info">
        <?php
                echo $tenderInfo['Provider']['name'];
                

                ?>        </td>
        <td class="info">
        <?php
                echo $this->Time->format($tenderInfo['Demand']['modified'], '%b %d, %Y');
                

                ?>        </td>
        <td class="info">
                <?php echo $this->Element('consumers/profile_evaluate_demand', array('demand' => $tenderInfo['Demand'], 'provider' => $tenderInfo['Provider'], 'serviceType' => $tenderInfo['ServiceType'])); ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>
    
    
    
    
</section>
<?php
endif;
?>