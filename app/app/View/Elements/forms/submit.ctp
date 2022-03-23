 <?php
        if (empty($submitLabel)):
            $submitLabel = __('Confirm');
        endif;
        $options = [
                'div'=>array('class'=>'form-group'), 
                'class' => 'btn btn-default one-click-button',
                'alt' => $submitLabel,
                'title' => $submitLabel,
                ];
        if (!empty($name)):
            $options['name'] = $name;
        endif;
        echo $this->Form->submit($submitLabel, $options);

        ?>