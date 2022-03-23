<?php
    $env = 'development';
    if(isset($_SERVER['SERVER_NAME'])){
        switch($_SERVER['SERVER_NAME']){
            case 'easyjobquotedev.saskpolytechbis.ca/':
                $env = "development";
                break;
            case 'local.easyjobquote.com':
                $env = "development";
                break;
            case 'sandbox.easyjobquote.com':
                $env = 'sandbox';
                break;
            case 'staging.easyjobquote.com':
                $env = 'staging';
                break;
            case 'www.easyjobquote.com':
            default:
                $env = 'production';
                break;
        }
    }
    else {
        $env = 'development';
    }

    $config = array(
        'Settings' => array(
            'Global' => array('Environment' => $env),
        )
    );

    