<?php

class ProfileField extends CActiveRecord
{

    const VISIBLE_ALL=3;
	const VISIBLE_REGISTER_USER=2;
	const VISIBLE_ONLY_OWNER=1;
	const VISIBLE_NO=0;
	
	const REQUIRED_NO = 0;
	const REQUIRED_YES_SHOW_REG = 1;
	const REQUIRED_NO_SHOW_REG = 2;
	const REQUIRED_YES_NOT_SHOW_REG = 3;

    public static function model($classname = __CLASS__)
    {
        return parent::model($classname);
    }

    public function tableName()
    {
        return Yii::app()->getModule('user')->tabelProfileField;
    }

    public function rules()
    {
        return array(
            array('varname, title, field_type', 'required'),
            array('varname', 'match', 'pattern' => '/^[A-Za-z_0-9]+$/u', 'message' => Yii::t('app',"Variable name bole terdiri dari A-z, 0-9, _, dimulai dengan huruf.")),
            array('varname', 'unique', 'message' => Yii::t('app', "Varible name sudah ada")),
            array('varname, field_type', 'length', 'max' => 50),
            array('field_size_min, required, position, visible', 'numerical', 'integerOnly' => true),
            array('field_size', 'match', 'pattern' => '/^\s*[-+]?[0-9]*\,*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/'),
            array('title, match, error_message, other_validator, default, widget', 'length', 'max' => 255),
            array('range, widgetparams', 'length', 'max' => 5000),
            array('id, varname, title, field_type, field_size, field_size_min, required, match, range, error_message, other_validator, default, widget, widgetparams, position, visible', 'safe', 'on'=>'search')
        );
    }

    public function relations()
    {
        return array();
    }

    public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','Id'),
			'varname' => Yii::t('app','Variable name'),
			'title' => Yii::t('app','Title'),
			'field_type' => Yii::t('app','Field Type'),
			'field_size' => Yii::t('app','Field Size'),
			'field_size_min' => Yii::t('app','Field Size min'),
			'required' => Yii::t('app','Required'),
			'match' => Yii::t('app','Match'),
			'range' => Yii::t('app','Range'),
			'error_message' => Yii::t('app','Error Message'),
			'other_validator' => Yii::t('app','Other Validator'),
			'default' => Yii::t('app','Default'),
			'widget' => Yii::t('app','Widget'),
			'widgetparams' => Yii::t('app','Widget parametrs'),
			'position' => Yii::t('app','Position'),
			'visible' => Yii::t('app','Visible'),
		);
	}

    public function scopes()
    {
        return array(
            'forAll'=>array(
                'condition'=>'visible='.self::VISIBLE_ALL,
                'order'=>'position',
            ),
            'forUser'=>array(
                'condition'=>'visible>='.self::VISIBLE_REGISTER_USER,
                'order'=>'position',
            ),
            'forOwner'=>array(
                'condition'=>'visible>='.self::VISIBLE_ONLY_OWNER,
                'order'=>'position',
            ),
            'forRegistration'=>array(
                'condition'=>'required='.self::REQUIRED_NO_SHOW_REG.' OR required='.self::REQUIRED_YES_SHOW_REG,
                'order'=>'position',
            ),
            'sort'=>array(
                'order'=>'position',
            ),
        );
    }

    public function widgetView($model) {
    	if ($this->widget && class_exists($this->widget)) {
			$widgetClass = new $this->widget;
			
    		$arr = $this->widgetparams;
			if ($arr) {
				$newParams = $widgetClass->params;
				$arr = (array)CJavaScript::jsonDecode($arr);
				foreach ($arr as $p=>$v) {
					if (isset($newParams[$p])) $newParams[$p] = $v;
				}
				$widgetClass->params = $newParams;
			}
			
			if (method_exists($widgetClass,'viewAttribute')) {
				return $widgetClass->viewAttribute($model,$this);
			}
		} 
		return false;
    }
    
    public function widgetEdit($model,$params=array()) {
    	if ($this->widget && class_exists($this->widget)) {
			$widgetClass = new $this->widget;
			
    		$arr = $this->widgetparams;
			if ($arr) {
				$newParams = $widgetClass->params;
				$arr = (array)CJavaScript::jsonDecode($arr);
				foreach ($arr as $p=>$v) {
					if (isset($newParams[$p])) $newParams[$p] = $v;
				}
				$widgetClass->params = $newParams;
			}
			
			if (method_exists($widgetClass,'editAttribute')) {
				return $widgetClass->editAttribute($model,$this,$params);
			}
		}
		return false;
    }
	
	public static function itemAlias($type,$code=NULL) {
		$_items = array(
			'field_type' => array(
				'INTEGER' => Yii::t('app','INTEGER'),
				'VARCHAR' => Yii::t('app','VARCHAR'),
				'TEXT'=> Yii::t('app','TEXT'),
				'DATE'=> Yii::t('app','DATE'),
				'FLOAT'=> Yii::t('app','FLOAT'),
				'DECIMAL'=> Yii::t('app','DECIMAL'),
				'BOOL'=> Yii::t('app','BOOL'),
				'BLOB'=> Yii::t('app','BLOB'),
				'BINARY'=> Yii::t('app','BINARY'),
			),
			'required' => array(
				self::REQUIRED_NO => Yii::t('app','No'),
				self::REQUIRED_NO_SHOW_REG => Yii::t('app','No, but show on registration form'),
				self::REQUIRED_YES_SHOW_REG => Yii::t('app','Yes and show on registration form'),
				self::REQUIRED_YES_NOT_SHOW_REG => Yii::t('app','Yes'),
			),
			'visible' => array(
				self::VISIBLE_ALL => Yii::t('app','For all'),
				self::VISIBLE_REGISTER_USER => Yii::t('app','Registered users'),
				self::VISIBLE_ONLY_OWNER => Yii::t('app','Only owner'),
				self::VISIBLE_NO => Yii::t('app','Hidden'),
			),
		);
		if (isset($code))
			return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
		else
			return isset($_items[$type]) ? $_items[$type] : false;
	}

    public function search()
    {

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('varname',$this->varname,true);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('field_type',$this->field_type,true);
        $criteria->compare('field_size',$this->field_size);
        $criteria->compare('field_size_min',$this->field_size_min);
        $criteria->compare('required',$this->required);
        $criteria->compare('match',$this->match,true);
        $criteria->compare('range',$this->range,true);
        $criteria->compare('error_message',$this->error_message,true);
        $criteria->compare('other_validator',$this->other_validator,true);
        $criteria->compare('default',$this->default,true);
        $criteria->compare('widget',$this->widget,true);
        $criteria->compare('widgetparams',$this->widgetparams,true);
        $criteria->compare('position',$this->position);
        $criteria->compare('visible',$this->visible);

        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>Yii::app()->controller->module->fields_page_size,
			),
			'sort'=>array(
				'defaultOrder'=>'position',
			),
        ));
    }

}
