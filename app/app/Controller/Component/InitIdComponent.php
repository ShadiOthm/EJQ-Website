<?php

App::uses('Component', 'Controller');
class InitIdComponent extends Component {

    public $controller;

    public function __construct(ComponentCollection $collection, $settings = array())
    {
        
        $this->controller = $collection->getController();

        parent::__construct($collection, array_merge($this->settings, (array)$settings));        
        
    }
    
    public function InitId($id, $modelName, $fieldName, $passedArgFieldname=null)
    {
        if (is_null($passedArgFieldname)) {
            $passedArgFieldname = "id";
        }
        
        if ((!$id) && isset($this->controller->passedArgs[$passedArgFieldname])) {
            $id = $this->controller->passedArgs[$passedArgFieldname];
        } else {
            $id = $this->initIdInCaseOfPost($id, $modelName, $fieldName);
        }
        
        
        if(is_null($id)){
            throw new NotFoundException('Id inválido');
        }
        
        return $id;
    }

    private function initIdInCaseOfPost($id, $modelName, $fieldName)
    {
        // init id
        if ((!$id) && isset($this->controller->request->data[$modelName][$fieldName])) {
            $id = $this->controller->request->data[$modelName][$fieldName];
        }
        return $id;
        
        
    }
    


    
}
    