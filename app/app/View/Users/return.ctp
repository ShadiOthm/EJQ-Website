<div id="retorno">
    <div>
        <h2 class="titulo"><?php echo __("Recover your password"); ?></h2>
        <p><?php echo __('Instructions on how to set a new password have been sent to the informed email.'); ?></p>
        <p><?php echo __('If you do not receive them, check your spam box.'); ?></p>
        <p><?php echo __('If you have not yet received it, please email our <a href="mailto:messages-notreply@easyjobquote.com"> support </a> and we will do our best to help you.'); ?></p>

        <?php

        echo $this->Html->link('&laquo; ' . __('Back to login'),
            array('controller' => 'users', 'action' => 'login'),
            array(
                'title' => __('Back to login'),
                'id' => 'back_to_login',
                'escape' => false
            )
        );

        ?>
    </div>
</div>

