 <?php
        echo $this->Form->create('Invoice', array('url'=>array('controller'=>'invoices', 'action' => 'inform_payment'), 'id' => 'update_to'));
        echo $this->Form->input('id');
        echo $this->Form->input('Demand.id');
        echo $this->Form->input('Tender.id');
        echo $this->Form->input('Consumer.id');
        echo $this->Form->submit(__('Inform payment'), array(
//                        'div'=>array('class'=>'form-group'), 
                'class' => 'btn',
                'alt' => __('Inform payment'),
                'title' => __('Inform payment'),
                ));
        echo $this->Form->submit(__('Inform payment and Send Receipt'), array(
//                        'div'=>array('class'=>'form-group'), 
                'class' => 'btn',
                'alt' => __('Inform payment and Send Receipt'),
                'title' => __('Inform payment and Send Receipt'),
                ));
        echo $this->Form->input('send_receipt', array(
            'type' => 'checkbox',
            'class' => 'check',
            'label' => false,
            'before' => '<label for="InvoiceSendReceipt">',
            'after' =>  '&nbsp;' . __('Send the receipt to home owner') . '</label>',
            'checked' => false,
            ) );
        
        echo $this->Form->end();
        ?>
