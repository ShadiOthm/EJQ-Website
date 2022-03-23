<?php
// echo $this->Html->script(array('users'), false);
?>
<h2 class="title">Alterar Usuário <?php
	echo $this->Html->link(
		__('Cancelar'), array('controller' => 'users', 'action' => 'index'), array('escape' => true)
	);
	?></h2>
<?php echo $this->Form->create('User', array('novalidate' => true)); ?>
<?php
echo $this->Form->input('id');
echo $this->Form->input('name', array('label' => 'Nome:'));
echo $this->Form->input('email', array('label' => 'Email:'));


echo $this->Form->input('group_id', array('label' => 'Perfil de Acesso:', 'empty' => 'Selecione o perfil', 'options' => $groups));

echo $this->Form->input('newpassword', array('label' => 'Senha:', 'type' => 'password', 'after' => '<span class="after">(Opcional - usuário pode usar "recuperar senha" para definir sua própria senha)</span>'));
echo $this->Form->input('confirmpassword', array('label' => 'Confirmar senha:', 'type' => 'password'));

echo '<div class="button">';
echo $this->Form->button('<span class="ico">&#xf00c;</span> Confirmar', array(
    'type' => 'submit',
    'escape' => false
));
echo '</div>';
echo $this->Form->end();
