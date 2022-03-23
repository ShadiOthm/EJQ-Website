<?php echo $this->Element('providers/contractor_update_service_types'); ?>
<?php
//if (isset($provider['ServiceType']['0'])):
if ($showServices = 0): // don't show for now, keep code just in case
?>
    
<section id="demands_to_evaluate">
<h2><?php echo __('What I Can Do As a Contractor'); ?></h2>
    <table class="list_table">
    <tr>
        <th style="width:30%"><?php echo $this->Paginator->sort('name', __('Job Category:')); ?></th>
        <th class="info" style="width:40%"><?php echo __('Opções'); ?></th>
        <th class="info" style="width:30%"><?php echo __('Status'); ?></th>
    </tr>
    <?php
    foreach ($provider['ServiceType'] as $key => $serviceType):
        
    ?>
    <tr>
        <td>
            <?php echo $serviceType['name'] ?>            
        </td>
        <td>
                    <ul>
<?php
        $actionDescription = "";
        if (isset($lackWeekdays[$serviceType['id']])):
            $actionDescription = __('Informar Disponibilidade por dias da semana');
        endif;
        if (isset($existsWeekdays[$serviceType['id']])):
            $actionDescription = __('Alterar Disponibilidade por dias da semana');
        endif;
        if ($actionDescription):
            echo "<li>";
            echo $this->Html->link(
                   $actionDescription, array('controller' => 'providers', 'action' => 'update_weekdays', $provider['Provider']['id'], $serviceType['id']), array('escape' => false, 'class' => 'more_link')
            );
            echo "</li>";
        endif;

        ?>
                            
<?php
        $actionDescription = "";
        $newStatus = null;
        if (isset($onlineStatus[$serviceType['id']])):
            if ($onlineStatus[$serviceType['id']]):
                $actionDescription = __('Mudar status para offline');
                $newStatus = 'offline';
            else:
                $actionDescription = __('Mudar status para online');
                $newStatus = 'online';
            endif;
        endif;
        if ($actionDescription):
            echo "<li>";
            echo $this->Html->link(
                   $actionDescription, array('controller' => 'providers', 'action' => 'update_online_status', $provider['Provider']['id'], $newStatus), array('escape' => false, 'class' => 'more_link')
            );
            echo "</li>";
        endif;

        ?>
                            
                        
            </ul>
        </td>
        <td class="info"></td>
    </tr>
    <?php endforeach; ?>
    </table>
    
    
    
    
</section>
<?php
endif;
?>
