<?php

App::uses('Component', 'Controller');
class IsCuratorComponent extends Component {

    public $controller;

    public function __construct(ComponentCollection $collection, $settings = array())
    {
        
        $this->controller = $collection->getController();

        parent::__construct($collection, array_merge($this->settings, (array)$settings));        
        
    }
    
    public function IsCurator(AuthComponent $Auth, AclComponent $Acl)
    {
        $groupId = $Auth->user('group_id'); 
        if ($groupId) {
            $aro = array('model' => 'Group', 'foreign_key' => $groupId);
            $isCurator = $Acl->check($aro, 'controllers/MetaMarketplaces/index');
            return $isCurator;
        } else {
            return false;
        }
    }
    
}
    