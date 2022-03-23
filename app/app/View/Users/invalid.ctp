<h2>Expired Token</h2>
<p>Informed Token is not valid or has expired.<br>
Please ask for a <?php
echo $this->Html->link(
    __('new password'), 
    array('controller' => 'users', 'action' => 'recover_password'), 
    array(
        'escape' => false, 
        'title' => __('Recover Password'),
    )
);
?> and another message will be sent to the informed email with a new token.</p>


<?php
echo $this->Html->link('<span class="ico">&#xf0da;</span> return to home', '/', array('escape'=>false));
?>