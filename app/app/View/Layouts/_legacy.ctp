<!DOCTYPE html>
<html lang="en">
<head>
<title>Easy Job Quote: <?php echo $this->here; ?></title>
<?php echo $this->Html->charset(); ?>
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />

<!--[if lt IE 9]>
<?php echo $this->Html->script('html5'); ?>
<![endif]-->

<!--[if lt IE 7]>
    <style type="text/css">
        #all { height:100%; }
    </style>
<![endif]-->

<?php 
echo $this->Html->script('respond.min.js?v=1.4.2'); 
//echo $this->Html->script('jquery/jquery-migrate-1.4.1.min'); 
echo $this->Html->script('jquery/jquery-1.12.0.min'); 
echo $this->Html->script('chosen.jquery.min.js?v=1.7.0'); 
echo $this->Html->script('common.js?v=0.0.0'); 

echo $this->Html->meta('icon');

echo $this->Html->css('//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css');
echo $this->Html->script('//cdn.jsdelivr.net/momentjs/latest/moment.min.js');
echo $this->Html->script('//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js');

echo $this->Html->css('normalize.css?v=3.0.3');
echo $this->Html->css('fonts.css?v=0.0.0');
echo $this->Html->css('chosen.css?v=1.7.0');
echo $this->Html->css('main.css?v=0.0.0');
echo $this->Html->css('header.css?v=0.0.0');

//echo $this->Html->css('/js/fancybox/source/jquery.fancybox.css');
//echo $this->Html->script('/js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5');




echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
</head>
<body class="<?php if (isset($this->params['adm'])) echo "adm " ?><?php echo $this->params['controller'] . " "; ?><?php echo $this->params['action']; ?><?php if(isset($extraBodyClass)){ echo ' '.$extraBodyClass; } ?>">
<header id="mainMetaplaceHeader">
    <section id="metaplaceHeader">
    </section>
    <section id="metaplaceMenuHeader">
    <ul>
        <li>
        <?php                                                    
            echo $this->Html->link(
                __('Home'), 
                'http://wp2017.easyjobquote.com', 
                array(
                    'escape' => false, 
                    'title' => __('Main'),
                    'class' => 'section_navigate', 
                    'id' => 'main',
                )
            );
        ?>
        </li>
        <?php if (!empty($uid)): ?>
            <?php if ($canAccessAdm): ?>
        <li>
        <?php                                                    
            echo $this->Html->link(
                __('Admin'), 
                '/marketplaces/detail', 
                array(
                    'escape' => false, 
                    'title' => __('Control Panel'),
                    'class' => 'section_navigate', 
                    'id' => 'admin',
                )
            );
        ?>
        </li>
            <?php else: ?>    
        <li>
        <?php
        echo $this->Html->link(
                $this->Session->read('Auth.User.name') . '&nbsp;&nbsp;<span class="ico">&#xf007;</span>', 
                    array(
                        'controller' => $profileController, 
                        'action' => 'profile', 
                        'adm' => false, 
                        $EjqProfileId
                    ),
                    array(
                        'escape' => false,
                        'title' => __('My page'),
                        'class' => 'section_navigate', 
                        'id' => 'profile',
                    )
        );
        ?>									
        </li>
            <?php endif; ?>    
        <li>
        <?php                                                    
            echo $this->Html->link(
                __('Logout'), 
                '/users/logout', 
                array(
                    'escape' => false, 
                    'title' => __('Logout'),
                    'class' => 'section_navigate', 
                    'id' => 'login',
                )
            );
        ?>
        </li>
        <?php else: ?>    
        <li>
        <?php                                                    
            echo $this->Html->link(
                __('Login'), 
                '/users/login', 
                array(
                    'escape' => false, 
                    'title' => __('Login'),
                    'class' => 'section_navigate', 
                    'id' => 'login',
                )
            );
        ?>
        </li>
        <?php endif; ?>    

    </ul>
    
        
    </section>
</header>
<main id="mainMain">
<!--    <header id="flashHeader">-->
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->Flash->render('auth'); ?>
<!--    </header>-->
    <article class="mainContent metaplace">
            <?php echo $this->fetch('content'); ?>
    </article>
</main>
<footer id="pageFooter">
    <span class="legalize left"><?php echo __('&copy; 2017 Easy Job Quote. All Rights Reserved.'); ?> </span>
    <span class="legalize right"><?php echo __('Powered by Metaplace.'); ?></span>
</footer>
<?php //echo $this->element('sql_dump'); ?>
</body>
</html>
