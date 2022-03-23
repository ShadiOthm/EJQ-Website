<!DOCTYPE html>
<html lang="en">
<head>
<?php echo $this->Html->charset(); ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="content-language" content="en-CA" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="author" content="Easy Job Quote" />
<!--[if lt IE 9]>
<?php echo $this->Html->script('html5'); ?>
<![endif]-->

<!--[if lt IE 7]>
    <style type="text/css">
        #all { height:100%; }
    </style>
<![endif]-->

<?php echo $this->Html->css(array('style')); ?>
    <?php
echo $this->fetch('meta');
echo $this->fetch('css');

?>
</head>
<body>

<?php echo $this->fetch('content'); ?>


</body>




</html>



