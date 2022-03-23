<?php
App::uses('AppModel', 'Model');
/**
 * Token Model
 *
 */
class Token extends AppModel {

    public function beforeSave($options = array()) {
        $userId = $this->data['Token']['user_id'];
        $this->deleteAll(array('Token.user_id' => $userId), false);
    }



}