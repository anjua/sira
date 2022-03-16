<?php

class UserModule extends CWebModule
{
    public $tabelUser = '{{user}}';
    public $tabelProfile = '{{profile}}';
    public $tableProfileField = '{{profile_field}}';

    public $fields_page_size = 10; 

    private static $_admin;
    private static $_users = array();

    public static function user($id = 0, $clearCache = false) 
    {
        if (!$id && !Yii::app()->user->isGuest)
            $id = Yii::app()->user->id;
            if ($id) 
            {
                if (!isset(self::$_users[$id])||$clearCache)
                    self::$_users[$id] = User::model()->with(array('profile'))->findbyPk($id);

                return self::$_users[$id];
            } else return false;
        }
	}

    // public static function isAdmin()
    // {
    //     if(Yii::app()->user->isGuest)
    //         return false;

    // }
}
