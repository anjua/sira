<?php

class Profile extends CActiveRecord
{

    public $regMode = false;
	
	private $_model;
	private $_modelReg;
	private $_rules = array();

    public static function model($classname = __CLASS__)
    {
        return parent::model($classname);
    }

    public function tableName()
    {
        return Yii::app()->getModule('user')->tabelProfile;
    }
    
    public function relations()
    {
        $relations = array(
            'user' => array(self::HAS_ONE, 'User', 'id')
        );

        return $relations;
    }

    
}
