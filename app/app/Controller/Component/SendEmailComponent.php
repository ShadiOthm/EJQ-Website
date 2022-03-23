<?php

App::uses('Component', 'Controller');
class SendEmailComponent extends Component {

    public $controller;    
    public $data;
    private $environment;    
    private $sendEmail;

    public function __construct(ComponentCollection $collection, $settings = array())
    {
        parent::__construct($collection, array_merge($this->settings, (array)$settings));        
        $this->controller = $collection->getController();
    }
    
    public function send($data, SentEmail $SentEmailModel)
    {
        $this->data['SentEmail'] = $data;
        $this->SentEmail = $SentEmailModel;
        $this->SentEmail->create();
        $this->sendSentEmail = false;

        $success = $this->tryToSaveAndSendEmail();
        return $success;
        
        
    }
    
    public function sendRendered($data, SentEmail $SentEmailModel, $view='default', $layout='default', $emailFormat='both', $viewVars=null, $attachments=null)
    {
        $this->data['SentEmail'] = $data;
        $this->SentEmail = $SentEmailModel;
        $this->SentEmail->create();
        $this->sendSentEmail = false;
        
        $success = $this->tryToSaveAndSendRenderedEmail($view, $layout, $emailFormat, $viewVars, $attachments);
                
        return $success;
        
        
    }
    
    private function completeAndSendMessage()
    {
        $this->sendEmail->subject($this->data['SentEmail']['subject']);
        $message = $this->data['SentEmail']['message'];
        if ($this->environment == 'development') {
            //debug("development");
            //debug($message);
        } elseif (($this->environment == 'sandbox') || ($this->environment == 'staging') || ($this->environment == 'production')) {
            $this->sendEmail->template('default', 'default')
                    ->emailFormat('both');
            $this->sendEmail->viewVars(['content' => $message]);
            $this->sendEmail->send();
        } else {
            $this->sendEmail->template('default', 'default')
                    ->emailFormat('both');
            $this->sendEmail->viewVars(['content' => $message]);
            $this->sendEmail->send();
        }
    }
    
    private function completeAndSendRenderedMessage($view, $layout, $emailFormat, $viewVars, $attachments)
    {
        $this->sendEmail->subject($this->data['SentEmail']['subject']);
        $message = $this->data['SentEmail']['message'];
        if ($this->environment == 'development') {
            //debug("development");
            //debug($message);
        } elseif (($this->environment == 'sandbox') || ($this->environment == 'staging') || ($this->environment == 'production')) {
            
            if (!empty($viewVars) && is_array($viewVars)) {
                $viewVars['content'] = $message;
                ob_start();
                var_dump($viewVars);
                $dump = ob_get_clean();
                $this->data['SentEmail']['message'] = $dump;
            } else {
                $viewVars = ['content' => $message];
            }

            $this->sendEmail->template($view, $layout)
                    ->emailFormat($emailFormat)
                    ->viewVars($viewVars)
                    ->attachments($attachments);
            $this->sendEmail->send();
        } else {
            $this->sendEmail->viewVars(['content' => $message]);
            $this->sendEmail->send();
        }
    }
    
    private function findTesters($alias)
    {
        $testers = [];
        $foundLisa = (substr($alias, 0, 4) == 'lisa');
        if ($foundLisa) {
            $alias = "lisa";
        }
        $foundRuss = (substr($alias, 0, 4) == 'russ');
        if ($foundRuss) {
            $alias = "russ";
        }
        switch($alias) {
            case 'rodm67':
                $testers[] = 'rodm67@gmail.com';
                break;

            case 'lisa':
                $testers[] = 'lisa@easyjobquote.com';
                break;            
            
            case 'russ':
                $testers[] = 'russ@easyjobquote.com';
                break;            
            
            case 'katie':
                $testers[] = 'katie@silverbirchsolutions.com';
//                $testers[] = 'lisa@easyjobquote.com';
//                $testers[] = 'russ@easyjobquote.com';
                break;

            case 'sseptember2007':
                //$testers[] = 'sseptember2007@gmail.com';
                $testers[] = 'lisa@easyjobquote.com';
                $testers[] = 'russ@easyjobquote.com';
                break;            
            
            case 'anissaagah':
                $testers[] = 'anissaagah@gmail.com';
//                $testers[] = 'lisa@easyjobquote.com';
//                $testers[] = 'russ@easyjobquote.com';
                break;
            
            case 'robynquinn':
                //$testers[] = 'robynquinn@shaw.ca';
                $testers[] = 'lisa@easyjobquote.com';
                $testers[] = 'russ@easyjobquote.com';
                break;
            
            case 'canadianlesley':
                //$testers[] = 'canadianlesley@gmail.com';
                $testers[] = 'lisa@easyjobquote.com';
                $testers[] = 'russ@easyjobquote.com';
                break;
            
            default:
                break;
            
        }
 
        //$testers = [];
        $testers = array_merge($testers, ['rodrigo.aocubo@gmail.com']);
        return $testers;
        
    }
    
    private function prepareEmail()
    {
        if (($this->environment == 'sandbox')
            || ($this->environment == 'staging') 
            || ($this->environment == 'production')) {
            $this->sendEmail = new CakeEmail();
        } else {
            $this->sendEmail = new CakeEmail('test');
            $this->sendEmail->delivery = 'test';
        }
        
        $this->sendEmail->from(array('messages-notreply@easyjobquote.com' => __('Easy Job Quote'))); //HuTTwgAZmFM]
        
        $userEmail = trim($this->data['SentEmail']['email']);
        // permite testes em contas fakes no easyjobquote.com
        if (($this->environment == 'sandbox' || $this->environment == 'staging') && 
             stripos($userEmail, "@easyjobquote.com") !== FALSE) {
            $bcc = $this->redirectTests($userEmail);
        } else {
            $bcc = array($userEmail);
        }
        $this->sendEmail->bcc($bcc);
    }
        
    
    private function prepareToSend()
    {
        Configure::load('settings');
        $this->environment = Configure::read('Settings.Global.Environment');
        App::uses('CakeEmail', 'Network/Email');
        
        $this->prepareEmail();
        $this->setDomainPrefix();
        
    }
    
    private function redirectTests($userEmail)
    {
            $bcc = array("rodbigua@gmail.com", "haroldb@westbay-bi.com");
            if (stripos($userEmail, "-site-admin@easyjobquote.com") !== FALSE) {
                $pos = stripos($userEmail, "-site-admin@easyjobquote.com");
                $testers = $this->findTesters(substr($userEmail, 0, $pos));
                $bcc = array_merge($bcc, $testers);
            }
            if (stripos($userEmail, "-project-developer@easyjobquote.com") !== FALSE) {
                $pos = stripos($userEmail, "-project-developer@easyjobquote.com");
                $testers = $this->findTesters(substr($userEmail, 0, $pos));
                $bcc = array_merge($bcc, $testers);
            }
            if (stripos($userEmail, "-home-owner@easyjobquote.com") !== FALSE) {
                $pos = stripos($userEmail, "-home-owner@easyjobquote.com");
                $testers = $this->findTesters(substr($userEmail, 0, $pos));
                $bcc = array_merge($bcc, $testers);
            }
            if (stripos($userEmail, "-contractor@easyjobquote.com") !== FALSE) {
                $pos = stripos($userEmail, "-contractor@easyjobquote.com");
                $testers = $this->findTesters(substr($userEmail, 0, $pos));
                $bcc = array_merge($bcc, $testers);
            }
            
            return $bcc;
        
    }

    
    private function setDomainPrefix()
    {
        if ($this->environment == 'production') {
            $this->domainPrefix = "app";
        } elseif ($this->environment == 'sandbox') {
            $this->domainPrefix = "sandbox";
        } elseif ($this->environment == 'staging') {
            $this->domainPrefix = "staging";
        } else {
            $this->domainPrefix = "local";
        }
        
        $this->data['SentEmail']['message'] = str_replace('%domain_prefix%', $this->domainPrefix, $this->data['SentEmail']['message']);

    }
    
    private function tryToSaveAndSendRenderedEmail($view, $layout, $emailFormat, $viewVars=null, $attachments=null) {
        try {
            $this->prepareToSend();
        } catch (Exception $ex) {
            throw new InternalErrorException("An error ocurred while preparing to send email");
        }
        
        try {
            $this->completeAndSendRenderedMessage($view, $layout, $emailFormat, $viewVars, $attachments);
        } catch (Exception $ex) {
            throw new InternalErrorException("It was not possible to send the message: " . $ex->getMessage());
        }
        
        if ($this->SentEmail->save($this->data)) {
            return true;
        } else {
            throw new InternalErrorException("Não foi possível salvar a mensagem.");
        }
        
    }
    private function tryToSaveAndSendEmail() {
        try {
            $this->prepareToSend();
        } catch (Exception $ex) {
            throw new InternalErrorException("An error ocurred while preparing to send email");
        }
        
        try {
            $this->completeAndSendMessage();
        } catch (Exception $ex) {
            throw new InternalErrorException("Não foi possível enviar a mensagem.");
        }
        
        if ($this->SentEmail->save($this->data)) {
            return true;
        } else {
            throw new InternalErrorException("Não foi possível salvar a mensagem.");
        }
        
    }
        
}
    