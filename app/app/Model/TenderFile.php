<?php

App::uses('AppModel', 'Model');

class TenderFile extends AppModel {

    public $useTable = 'tenders_files';
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
		);


    public $belongsTo = array(
        'Demand' => array(
                'className' => 'Demand',
                'foreignKey' => 'demand_id',
        ),
//        'Tender' => array(
//                'className' => 'Tender',
//                'foreignKey' => 'tender_id',
//        ),
    );

    public $hasMany = array(
    );


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
        $count = 2;
        while (file_exists($folder.$fileName)) {
            $fileName  = $this->handleName($fileInfo['filename']).'-'.$count;
            $fileName .= '.'.$fileInfo['extension'];
            $count++;
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

    public function beforeValidate($options = array()) {
        
        if(!empty($this->data['File']['path'])) {
            $folder = $this->data['File']['path'];
            if (!file_exists(WWW_ROOT.$folder)) {
                try {
                    mkdir(WWW_ROOT.$folder, 0777, true);
                } catch (Exception $exc) {
                    throw $exc->getMessage();
                }

            }
        }

        if(!empty($this->data['File']['image']['name'])) {
            $this->data['File']['image'] = $this->upload($this->data['File']['image'], $folder);
            $this->data['File']['filename'] = $this->data['File']['image'];
        } else {
            unset($this->data['File']['image']);
        }        
        
        parent::beforeValidate($options);
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
