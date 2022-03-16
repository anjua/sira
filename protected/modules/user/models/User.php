<?php

class User extends CActiveRecord
{
    const STATUS_NOTACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BANNED = -1;

    public static function itemAlias($type, $code = NULL)
    {
        $_items = array(
            'UserStatus' => array(
                self::STATUS_NOTACTIVE => Yii::t('app', 'Not Active'),
                self::STATUS_ACTIVE => Yii::t('app', 'Active'),
                self::STATUS_BANNED => Yii::t('app', 'Banned')
            ),
            'AdminStatus' => array(
                '0' => Yii::t('app', 'No'),
                '1' => Yii::t('app', 'Yes')
            )
        );

        if(isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }

    public static function model($classname = __CLASS__)
    {
        return parent::model($classname);
    }

    public function tableName()
    {
        return Yii::app()->getModule('user')->tableUser;
    }

}
