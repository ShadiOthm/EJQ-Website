<!DOCTYPE html>
<html lang="en">
<head>
<title>Easy Job Quote: <?php echo $hereTitle; ?></title>
<?php echo $this->Html->charset(); ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="content-language" content="en-CA" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="googlebot" content="noarchive" />
<meta name="robots" content="index,follow" />
<meta name="googlebot" content="ALL" />
<meta name="keywords" content="" />
<meta name="description" content="" /> 
<meta name="author" content="Easy Job Quote" />
<!--[if lt IE 9]>
<?php echo $this->Html->script('html5'); ?>
<![endif]-->

<!--[if lt IE 7]>
    <style type="text/css">
        #all { height:100%; }
    </style>
<![endif]-->

<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
<link rel="icon" href="/img/favicon.ico" type="image/x-icon" />

<?php
echo $this->Html->css('bootstrap.min.css?v=0.0.0');
echo $this->Html->css('bootstrap-theme.min.css?v=0.0.0');
echo $this->Html->css('fontawesome.css?v=0.0.0');
echo $this->Html->css('style.css?v=0.0.0');
echo $this->Html->css('https://fonts.googleapis.com/css?family=Nunito:100,300,700');

echo $this->Html->script('respond.min.js?v=1.4.2'); 
echo $this->Html->script('jquery/jquery-1.12.0.min'); 


echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');

?>
    <link rel="stylesheet" type="text/css" media="screen"
     href="http://tarruda.github.com/bootstrap-datetimepicker/assets/css/bootstrap-datetimepicker.min.css"></head>
<body>

<!-- header -->
<header class="header" role="banner">
	<div class="topnav hidden-sm hidden-xs">
        <div class="container">
            <div class="row">
                <div class="col-md-5 text-right">
                    <img align="top" src="/img/icon-phone.png" />&nbsp;&nbsp;Call Us at <a href="tel:250.590.8182">250.590.8182</a>
                </div>
                <div class="col-md-7 text-left">
                    |<img align="top" src="/img/icon-email.png" />&nbsp;&nbsp;Email Us at <a href="mailto:info@easyjobquote.com">info@easyjobquote.com</a> 
                </div>
            </div>
        </div>
    </div>
    
	<div class="container">
        <div class="row main-navigation">
            <div class="col-md-12">
                <nav class="navbar" role="navigation">
        <?php                                                    
            echo $this->Html->link(
            $this->Html->image(
                    '/img/logo.png', 
                    array(
                        "alt" => __("Easy Job Quote"), 
                    )
                ), 
                'https://www.easyjobquote.com', 
                array(
                    'escape' => false, 
                    'title' => __('Easy Job Quote'),
                    'class' => 'logo',
                    'rel' => 'home',
                )
            );
        ?>
                    <div class="collapse navbar-collapse navbar-primary-collapse pull-right">
                        <ul id="menu-menu" class="nav navbar-nav">
                                <li><?php                                                    
            echo $this->Html->link(
                __('About Us'),
                'https://www.easyjobquote.com/about-us', 
                array(
                    'escape' => false, 
                    'title' => 'Testimonials.',
                    'class' => 'section_navigate', 
                    'id' => 'testimonials',
                )
            );
        ?></li>
                                <li><?php                                                    
            echo $this->Html->link(
                __('Testimonials'), 
                'https://www.easyjobquote.com/testimonials', 
                array(
                    'escape' => false, 
                    'title' => 'Testimonials.',
                    'class' => 'section_navigate', 
                    'id' => 'testimonials',
                )
            );
        ?></li>
                                <li><?php                                                    
            echo $this->Html->link(
                __('Gallery'), 
                'https://www.easyjobquote.com/gallery', 
                array(
                    'escape' => false, 
                    'title' => __('Gallery'),
                )
            );
        ?></li>
                                <li><?php                                                    
            echo $this->Html->link(
                __('Values'), 
                'https://www.easyjobquote.com/values', 
                array(
                    'escape' => false, 
                    'title' => __('Values'),
                )
            );
        ?></li>
                                <li><?php                                                    
            echo $this->Html->link(
                __('Faq'), 
                'https://www.easyjobquote.com/faq', 
                array(
                    'escape' => false, 
                    'title' => __('Faq'),
                )
            );
        ?></li>
                                <li class="last"><?php                                                    
            echo $this->Html->link(
                __('Contact Us'), 
                'https://www.easyjobquote.com/contact-us', 
                array(
                    'escape' => false, 
                    'title' => __('Contact Us'), 
                )
            );
        ?></li>
        <?php if (!empty($uid)): ?>
        <li class="logout">
        <?php
        echo $this->Html->link(
                __('Logout'), 
                    array(
                        'controller' => "users", 
                        'action' => 'logout', 
                        'adm' => false, 
                    ),
                    array(
                        'escape' => false,
                        'title' => __('Logout'),
                    )
        );
        ?>									
        </li>
        <?php endif; ?>    
                    	</ul> 
                    </div>
                    
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-primary-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                </nav>
            </div>
        </div>
	</div>
</header>
<!-- breadcrumb -->
<div id="breadcrumb" class="breadcrumb">
    <div class="container">
<?php
        echo $this->Html->link(            $this->Html->image(
                    '/img/icon-breadcrumb.png', 
                    array(
                        "alt" => __("My Dashboard"),
                        "align" => "texttop",                    )
                ) . "&nbsp;" .
                __('My Dashboard'), 
                    array(
                        'controller' => 'main', 
                        'action' => 'index', 
                        'adm' => false
                        ),
    array('escape' => false, 'rel' => 'nofollow')
        );
        if (isset($breadcrumbNode)):
            echo "&nbsp;&nbsp;&gt;&nbsp;{$breadcrumbNode}";
            endif;        
        ?>
        
<!--        <a href="/"><img src="img/icon-breadcrumb.png" align="texttop" /> Dashboard</a>&nbsp;&nbsp;>&nbsp;Old bathtub removal-->

    </div>
</div>
<!-- /breadcrumb -->



<?php
if (!empty($titleBox['h1'])):
?>
<!-- box title -->
<div class="title-box">
	<div class="container">
    	<div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h1><?php echo $titleBox['h1']; ?></h1>
<?php
    if (!empty($titleBox['h2'])):
?>
                <h2><?php echo $titleBox['h2']; ?></h2>
<?php
    endif;
?>
			</div>
<?php
    if (!empty($titleBox['tenderActions'])):
?>
            <div class="col-md-6 col-sm-6 col-xs-12 text-right">
<?php
foreach ($titleBox['tenderActions'] as $keyAction => $action):
?>
                <a id="<?php echo $action['id'];?>" class="btn btn-admin" href="<?php echo $action['href'];?>" role="button"><?php echo $action['label'];?></a>&nbsp;&nbsp;
<?php
endforeach;
?>            </div>
<?php
    endif;
?>
        </div>
    </div>
</div>
<!-- /box title -->
<?php
endif;
?>

<!-- section -->
<div id="section-admin">
    <!-- container -->
    <div id="main-content" class="container">
<?php echo $this->Flash->render(); ?>
<?php echo $this->fetch('content'); ?>

    </div>
    <!-- /container -->



<!-- Core JavaScript -->
<!--<script src="/js/jquery.js"></script>--> 
<script src="/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="/js/ie10-viewport-bug-workaround.js"></script>

<?php 
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<!-- footer -->
<footer class="footer" role="contentinfo">            
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-xs-6 col-sm-6">
                &copy; 2017 Easy Job Quote
            </div>
            <div class="col-md-6 col-xs-6 col-sm-6 text-right">
                <a href="" target="_blank" title="Twitter"><img src="/img/icon-twitter.png" alt="Twitter" /></a>&nbsp;&nbsp;&nbsp;<a href="" target="_blank" title="Facebook"><img src="/img/icon-facebook.png" alt="Facebook" /></a>&nbsp;&nbsp;&nbsp;<a href="" target="_blank" title="Linkedin"><img src="/img/icon-linkedin.png" alt="Linkedin" /></a>
            </div>
        </div>
    </div> 
</footer>
<!-- /footer -->

</div>
<!-- /section -->


</body>




</html>
