            <div class="col-md-6">
<?php
echo $this->Form->create('Consumer', array('url'=>array('controller'=>'marketplaces', 'action' => 'register_consumer')));
echo $this->Form->hidden('marketplace_id');
echo $this->Form->hidden('meta_consumer_id');
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
                'required'=>false, 
                'class' => 'form-control',
                ));
    echo $this->Form->input('email', 
            array(
                'div'=>array(
                    'class'=>'form-group',
                    ), 
                'label'=> array(
                    'text' => __('Email'), 
                    'class' => 'control-label',
                    ), 
                'required'=>false, 
                'class' => 'form-control',
                ));
else:
    echo $this->Form->hidden('user_id');
endif;
echo $this->Form->submit(__('Sign up'), array(
        'div'=>array('class'=>'form-group'), 
        'class' => 'btn btn-default',
        'alt' => __('Sign up'),
        'title' => __('Sign up'),
        ));
echo $this->Form->end();
?>
            </div>
            <!-- sidebar -->
            <div class="col-offset-2 col-md-4 pull-right">
            
            </div>
            <!-- /sidebar -->
