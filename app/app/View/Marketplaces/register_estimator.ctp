<?php
//echo $this->Html->script(array('jquery/jquery-1.11.1.min', 'common'), array('inline'=>false));
?>
<div class="col-md-6">

<h2 class="title">
	<?php echo __('Cadastre-se para oferecer serviços.'); ?>
</h2>

<?php

echo $this->Form->create('Provider', array('url'=>array('controller'=>'marketplaces', 'action' => 'register_estimator')));
echo $this->Form->hidden('marketplace_id');
echo $this->Form->hidden('meta_provider_id');

if (!$knownUser):
                    echo $this->Form->input('user_name', 
                            array(
                                'div'=>array(
                                    'class'=>'form-group',
                                    ), 
                                'label'=> array(
                                    'text' => __('Name'), 
                                    'class' => 'control-label',
                                    ), 
                                'required'=>true, 
                                'class' => 'form-control',
                                ));
                    echo $this->Form->input('email', array(
                        'div'=>array(
                            'class'=>'form-group',
                            ), 
                        'label'=> array(
                            'text' => __('Email'), 
                            'class' => 'control-label'
                            ), 
                        'required'=>true, 
                        'class' => 'form-control'
                        ));
else:
    echo $this->Form->hidden('user_id');
endif;

                echo $this->Form->submit(__('Confirm'), array(
                        'div'=>array('class'=>'form-group'), 
                        'class' => 'btn btn-default',
                        'alt' => __('Solicitar Cadastro'),
                        'title' => __('Solicitar Cadastro'),
                        ));

echo $this->Form->end();

?>

</div><!--  register form-->