<?php

App::uses('Component', 'Controller');
class SendTokenComponent extends Component {

    public $controller;
    
    public $data;
    
    private $environment;

    public function __construct(ComponentCollection $collection, $settings = array())
    {
        
        $this->controller = $collection->getController();
        
        parent::__construct($collection, array_merge($this->settings, (array)$settings));        
        
    }
    
    public function send($email, $name, $userId, Token $TokenModel, $recover=null)
    {
        $this->data['Token']['name'] = $name;
        
        $this->data['Token']['email'] = $email;
        $this->data['Token']['user_id'] = $userId;
        $this->data['Token']['token'] = Security::hash($email . date('YmdHis') . microtime(true));
        
        $this->Token = $TokenModel;
        
        $this->Token->create();

        $this->sendToken = false;
        
        $success = $this->tryToSaveAndSendToken($recover);
                
        return $success;
        
        
    }
    
    private function completeAndSendRecoverMessage()
    {
        $this->sendEmail->subject(__('Recover Easy Job Quote password'));
        $message = 'Hi!

You have asked a new password to login in Easy Job Quote using this email account.

Click the link below to create your new password and proceed to use Easy Job Quote:

http://' . $this->domainPrefix . 'easyjobquote.com/users/confirm/' . $this->data['Token']['token'] . '

This link is valid for the next 7 days only.

If it was not you who made the request, please disregard this message.

Thank you.

Easy Job Quote Team
messages-notreply@easyjobquote.com';
        
        if ($this->environment != 'development') {
//            debug($message);
//            exit;
            $this->sendEmail->viewVars(['content' => $message]);
            $this->sendEmail->send();
        } else {
            //debug($message);
        }

        
        
    }
    
    private function completeAndSendRegisterMessage()
    {
        $this->sendEmail->subject(__('Confirm Easy Job Quote registration'));
        $message = 'Hi!

You have signed up as an user in Easy Job Quote using this email account.

Click the link below to create your password and proceed to use Easy Job Quote:

http://' . $this->domainPrefix . 'easyjobquote.com/users/confirm/' . $this->data['Token']['token'] . '

This link is valid for the next 7 days only.

If it was not you who made the request, please disregard this message.

Thank you.

Easy Job Quote Team
messages-notreply@easyjobquote.com';
        
        if ($this->environment != 'development') {
//            debug($message);
//            exit;
            $this->sendEmail->viewVars(['content' => $message]);
            $this->sendEmail->send();
        } else {
            //debug($message);
        }

        
        
    }
    
    private function prepareEmail()
    {
        if ($this->environment != 'development') {
            $this->sendEmail = new CakeEmail();
            $this->sendEmail->template('default', 'default')
                    ->emailFormat('both');
        } else {
            $this->sendEmail = new CakeEmail('test');
            $this->sendEmail->delivery = 'test';
        }
        
        $this->sendEmail->from(array('messages-notreply@easyjobquote.com' => __('Easy Job Quote')));
        

        // permite testes em contas fakes no easyjobquote.com
        if (($this->environment == 'sandbox' || $this->environment == 'staging') && 
                (stripos($this->data['Token']['email'], "@easyjobquote.com")) !== FALSE) {
            $foundLisa = (substr($this->data['Token']['email'], 0, 4) == 'lisa');
            $foundRuss = (substr($this->data['Token']['email'], 0, 4) == 'russ');
//            $email = "catch-all@easyjobquote.com";
            $email = array("rodbigua@gmail.com", "haroldb@westbay-bi.com");
            if ($foundLisa) {
                $email[] = "lisa@easyjobquote.com";
            }
            if ($foundRuss) {
                $email[] = "russ@easyjobquote.com";
            }
        } else {
            $email = $this->data['Token']['email'];
        }
        
        $this->sendEmail->to($email);
    }
        
    
    private function prepareToSend()
    {
        Configure::load('settings');
        $this->environment = Configure::read('Settings.Global.Environment');
        App::uses('CakeEmail', 'Network/Email');
        
        $this->prepareEmail();
        $this->setDomainPrefix();
        
    }
    
    private function setDomainPrefix()
    {
        if ($this->environment == 'development') {
            $this->domainPrefix = "local.";
        } else if ($this->environment == 'sandbox') {
            $this->domainPrefix = "sandbox.";
        } elseif ($this->environment == 'staging') {
            $this->domainPrefix = "staging.";
        } else {
            $this->domainPrefix = "app.";
        }
        
    }
    
    private function tryToSaveAndSendToken($recover=null)
    {
        
        if ($this->Token->save($this->data)) {

            try {
                $this->prepareToSend();

            } catch (Exception $ex) {

                throw new InternalErrorException("Não foi possível preparar a mensagem com o token.");

            }

            try {
                if ($recover) {
                    $this->completeAndSendRecoverMessage();
                } else {
                    $this->completeAndSendRegisterMessage();
                }

            } catch (Exception $ex) {
                throw new InternalErrorException("Não foi possível enviar a mensagem com o token.");

            }
        } else {
                throw new InternalErrorException("Não foi possível salvar o token.");
        }

        
        
        
    }
        
}
    