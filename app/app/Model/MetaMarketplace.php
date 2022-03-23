<?php

App::uses('AppModel', 'Model');

class MetaMarketplace extends AppModel {

	public $useTable = 'meta_marketplaces';
	public $displayField = 'name';
        public $actsAs = array('Containable');

	public function parentNode() {
		return null;
	}

	public $validate = array(
		'active' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				),
			),
		'removed' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				),
			),
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please inform a name',
				),
			),
		'description' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Favor informar um texto descrevendo o Marketplace',
				),
			),
//                'logo_image' => array(
//			// http://book.cakephp.org/2.0/en/models/data-validation.html#Validation::uploadError
//			'uploadError' => array(
//				'rule' => 'uploadError',
//				'message' => 'Something went wrong with the file upload',
//				'required' => FALSE,
//				'allowEmpty' => TRUE,
//                        	),
//			// http://book.cakephp.org/2.0/en/models/data-validation.html#Validation::mimeType
//			'mimeType' => array(
//				'rule' => array('mimeType', array('image/gif','image/png','image/jpg','image/jpeg')),
//				'message' => 'Invalid file, only images allowed',
//				'required' => FALSE,
//				'allowEmpty' => TRUE,
//                        	),
//			// custom callback to deal with the file upload
//			'processUpload' => array(
//				'rule' => 'processUpload',
//				'message' => 'Something went wrong processing your file',
//				'required' => FALSE,
//				'allowEmpty' => TRUE,
//				'last' => TRUE,
//                		),
//        		),
             
		);

    public $belongsTo = array(
            'Curator' => array(
                    'className' => 'Curator',
                    'foreignKey' => 'curator_id',
            ),
    );

    public $hasMany = array(
            'MetaConsumer' => array(
                    'className' => 'MetaConsumer',
                    'foreignKey' => 'meta_marketplace_id',
                    'conditions' => array('active' => '1', 'removed' => '0'),
            ),
            'MetaProvider' => array(
                    'className' => 'MetaProvider',
                    'foreignKey' => 'meta_marketplace_id',
                    'conditions' => array('active' => '1', 'removed' => '0'),
            ),
            'Marketplace' => array(
                    'className' => 'Marketplace',
                    'foreignKey' => 'meta_marketplace_id',
                    'conditions' => array('active' => '1', 'removed' => '0'),
            ),
            'ServiceType' => array(
                    'className' => 'ServiceType',
                    'foreignKey' => 'meta_marketplace_id',
                    'conditions' => array('active' => '1', 'removed' => '0'),
            ),
    );

    
    
    public function beforeSave($options = array()) {

        if (isset($this->data['MetaMarketplace']['name'])) {
            $id = NULL;
            if (isset($this->data['MetaMarketplace']['id'])) {
                $id = $this->data['MetaMarketplace']['id'];
            }
            $this->data['MetaMarketplace']['slug'] = $this->createSlug($this->data['MetaMarketplace']['name'], $id);
            return true;
        }
        parent::beforeSave($options);
    }
    
    public function beforeValidate($options = array()) {
        
        if(!empty($this->data['MetaMarketplace']['logo_image']['name'])) {
            $this->data['MetaMarketplace']['logo_image'] = $this->upload($this->data['MetaMarketplace']['logo_image'], FOLDER_LOGO);
        } else {
            unset($this->data['MetaMarketplace']['logo_image']);
        }        
        
        if(!empty($this->data['MetaMarketplace']['cover_image']['name'])) {
            $this->data['MetaMarketplace']['cover_image'] = $this->upload($this->data['MetaMarketplace']['cover_image'], FOLDER_COVER);
        } else {
            unset($this->data['MetaMarketplace']['cover_image']);
        }        
        
        parent::beforeValidate($options);
    }
    
    public function isUserTheOwner($id, $userId) 
    {
        try {
            $this->verifyIdAndUserId($id, $userId);
        } catch (Exception $ex) {
            throw $ex;
        }
                
        $metaMarketplace = $this->find('first', array(
                        'fields' => array(
                            'MetaMarketplace.id',
                            'MetaMarketplace.curator_id',
                            ),
                        'contain' => array('Curator.user_id'),
                        'conditions' => array(
                            'MetaMarketplace.id' => $id, 
                            'Curator.user_id' => $userId,
                        )
                    ));
        
        if (empty($metaMarketplace)) {
            return false;
        } 
        return true;
    }

    /**
     * Check if folder exists, if it does not create it.
     * @access private
     * @param Array $dir
    */ 
    private function checkFolder($dir)
    {
        App::uses('Folder', 'Utility');
        $folder = new Folder();
        if (!is_dir($dir)){
            $folder->create($dir);
        }
    }

    /**
     * Check if filename already exists; if it does add a number and check again
     * @access private
     * @param Array $file
     * @param String $folder
     * @return file
    */ 
    private function checkName($file, $folder)
    {
        $fileInfo = pathinfo($folder.$file['name']);
        $fileName = $this->handleName($fileInfo['filename']).'.'.$fileInfo['extension'];
        debug($fileName);
        $count = 2;
        while (file_exists($folder.$fileName)) {
            $fileName  = $this->handleName($fileInfo['filename']).'-'.$count;
            $fileName .= '.'.$fileInfo['extension'];
            $count++;
            debug($fileName);
        }
        $file['name'] = $fileName;
        return $file;
    }

    /**
     * Handle name, removing spaces, specoal characters and uppercase.
     * @access private
     * @param String $filename
    */ 
    private function handleName($filename)
    {
        $filenameHandled = strtolower(Inflector::slug($filename,'-'));
        return $filenameHandled;
    }

    /**
     * Move file to folder
     * @access private
     * @param Array $image
     * @param String $folder
    */ 
    private function moveFiles($image, $folder)
    {
        App::uses('File', 'Utility');
        $file = new File($image['tmp_name']);
        $file->copy($folder.$image['name']);
        $file->close();
    }

    
    
    /**
     * Manage upload.
     * @access private
     * @param Array $image
     * @param String $data
    */ 
    private function upload($image = array(), $folder = 'img/upload')
    {
        
        $folder = WWW_ROOT.$folder.DS;

        if(($image['error']!=0) and ($image['size']==0)) {
            throw new NotImplementedException('Something has gone wrong. Upload returned error: '.$image['error'].' and size '.$image['size']);
        }

        $this->checkFolder($folder);

        $image = $this->checkName($image, $folder);

        $this->moveFiles($image, $folder);

        return $image['name'];
    }
    

}
