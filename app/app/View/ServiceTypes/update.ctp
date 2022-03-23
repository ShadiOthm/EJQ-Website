<h2 class="title"><?php echo __('Alterar Tipo de Serviço'); ?> </h2>
<p class="meta_provider_info"><?php echo __('Voltar para '); ?><?php echo $this->Html->link(
        $metaMarketplaceName, array('controller' => 'meta_marketplaces', 'action' => 'detail', $metaMarketplaceId, 'adm' => false), array('escape' => false)
); ?></p>
<?php echo $this->Form->create('ServiceType', array('novalidate' => true)); ?>
<?php
echo $this->Form->input('id');
echo $this->Form->hidden('meta_marketplace_id');
echo $this->Form->input('name', array('label' => __('Nome:')));
echo $this->Form->input('description', array('label' => __('Descrição:')));
echo $this->Form->label(__("Critérios"));
echo $this->Form->input("online_criterion", array('div' => false,
'label' => false,
'type' => 'checkbox',
'before' => '<label class="checkbox">',
'after' =>  __("Disponibilidade online/offline") . '</label>'));

echo $this->Form->input("qualified_criterion", array('div' => false,
'label' => false,
'type' => 'checkbox',
'before' => '<label class="checkbox">',
'after' =>  __("Fornecedores devem ser aprovados") . '</label>'));

echo $this->Form->input("weekdays_criterion", array('div' => false,
'label' => false,
'type' => 'checkbox',
'before' => '<label class="checkbox">',
'after' =>  __("Disponibilidade por dias da semana") . '</label>'));


echo $this->Form->input("scheduled_criterion", array('div' => false,
'label' => false,
'type' => 'checkbox',
'before' => '<label class="checkbox">',
'after' =>  __("Disponibilidade para data agendada") . '</label>'));


echo '<div class="button">';
echo $this->Form->button('<span class="ico">&#xf00c;</span> ' . __('Confirmar'), array(
    'type' => 'submit',
    'escape' => false
));
echo '</div>';
echo $this->Form->end();
