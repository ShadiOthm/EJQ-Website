<?php

App::uses('AppController', 'Controller');

class MainController extends AppController
{

    public $uses = array();

    public function beforeFilter() {
        $this->Auth->allow(array('index', 'marketplaces', 'change_language', 'testimonials', 'pricing', 'contact'));
        
        parent::beforeFilter();
    }

    public function index()
    {
        if (!empty($this->uid)) {
            if ($this->canAccessAdm) {
                return $this->redirect(array('controller' => 'marketplaces', 'action' => 'dashboard', 'adm' => false, $this->EjqMarketplaceId));
            } else {
                if ($this->EjqIsConsumer) {
                    return $this->redirect(array('controller' => 'consumers', 'action' => 'dashboard', 'adm' => false, $this->EjqProfileId));
                } elseif ($this->EjqIsProvider) {
                    return $this->redirect(array('controller' => 'providers', 'action' => 'dashboard', 'adm' => false, $this->EjqProfileId));
                } else {
                    return $this->redirect(array('controller' => 'users', 'action' => 'login', 'adm' => false));
                }
            }
        } else {
            return $this->redirect(array('controller' => 'users', 'action' => 'login', 'adm' => false));
        }
        
        
    }

    public function change_language($lang) {
        $this->autoRender = false;
        $this->layout = false;

        setLocale(LC_ALL, $lang);
        //Configure::write('Config.language', 'pt-BR');
        $this->Session->write('Config.language', $lang);

    }

    public function contact()
    {
    }

    public function marketplaces()
    {
        $this->loadModel('Marketplace');
        $this->Marketplace->recursive = 1;

        if ($this->Auth->login()) {
            $uid = $this->Auth->user('id');
        } else {
            $uid = null;
        }
        $marketplaces = $this->Marketplace->getMarketplacesListForUser($uid);

        $this->set('marketplaces', $marketplaces);


    }

    public function metamarketplaces()
    {
        //$this->layout = 'metaplace';
        $this->loadModel('MetaMarketplace');
        $this->MetaMarketplace->recursive = 1;
        $this->set('metaMarketplaces', $this->MetaMarketplace->find('all'));

    }

    public function pricing()
    {
    }

    public function testimonials()
    {
    }

}
