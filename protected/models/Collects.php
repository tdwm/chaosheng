<?php

/**
 * This is the model class for table "Collects".
 *
 * The followings are the available columns in table 'Collects':
 * @property integer $id
 * @property integer $user_id
 * @property integer $category_id
 * @property integer $type_id
 * @property integer $all_num
 * @property integer $day_num
 * @property integer $send_num
 * @property string $start_time
 * @property string $end_time
 * @property integer $period_time
 * @property string $created
 * @property string $updated
 */
class Collects extends CiiModel
{
    public $type = array('1'=>'数量','2'=>'时间');
    public $encoding_arr = array('2'=>'GBK','1'=>'UTF8');
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Collects the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'collects';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id,api_url, category_id, type_id, all_num, start_time, end_time', 'required'),
			array('user_id, category_id, type_id, all_num, day_num, send_num,encoding, period_time', 'numerical', 'integerOnly'=>true),
            array('end_time', 'safe'),
            array('api_url', 'url'),
            array('user_id', 'UniqueAttributesValidator','with'=>'category_id'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, category_id, type_id, start_time, end_time, ', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'category' => array(self::BELONGS_TO, 'Categories', 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => '用户',
			'api_url' => 'api地址',
			'encoding' => '数据编码',
			'category_id' => '分类',
			'type_id' => '类型',
			'all_num' => '推送总数',
			'day_num' => '日推送量',
			'send_num' => '已推送量',
			'start_time' => '开始时间',
			'end_time' => '结束时间',
			'site_categories' => '网站分类',
			'site_media' => '网站媒体',
			'period_time' => '周期',
			'created' => '创建时间',
			'updated' => '更新时间',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('category_id',$this->category_id);
        $criteria->compare('type_id',$this->type_id);
        if ($this->start_time)
            $criteria->addCondition("start_time > '$this->start_time'");
        if ($this->end_time)
            $criteria->addCondition("end_time < '$this->end_time'");
        $criteria->with = array("user","category");

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

	public function beforeSave()
	{
    	if ($this->isNewRecord)
    	{
			$this->created = new CDbExpression('NOW()');
			$this->updated = new CDbExpression('NOW()');
		}
	   	else
			$this->updated = new CDbExpression('NOW()');
	 
	    return parent::beforeSave();
	}

    public function getCategories()
    {
          $role = Yii::app()->user->role;        
          $user_id = Yii::app()->user->id;        
          //Yii::app()->cache->delete('categories-listing');
          //Yii::app()->controller->getCategories();
          $catmodel = new Categories();
          $cats = $catmodel->getCategoryCaches();
          //$cats = Yii::app()->cache->get('categories-listing');
          //var_dump($cats);exit;
          $return = array('cats'=>$cats,'collects'=>array());
          if ($role == 5){
              return $return;
          }else{
              $user_id = Yii::app()->user->push_uid;
          }
          $categories = Collects::model()->findAllByAttributes(array('user_id'=>$user_id));
          $arr = array();
          foreach($categories as $t)
          {
              $arr[$t->id] = $t->attributes;
              $catids[$t->id]  = $t->category_id;
          }
          $collects = array();
          foreach($catids as $v){
              $temp = Categories::model()->getParentCategories($v,true);
              if($temp == null) $temp = array();
              $collects= array_merge($temp,$collects);
          }
          
          $return['collects'] = $collects;
          $return['collects_id'] = $catids;
          return $return;
    }
    public function changeSiteSetting($data, $type =1){
        $temp = array();
        if(empty($data)) return $temp;
        $data = explode("\n",trim($data));
        if(count($data) == 0) return $temp;
        foreach($data as $k =>  $v){
            if(!empty($v) && strpos($v,',')){
                $t = explode(',', $v);
                $temp[$t[0]] = $t[1];
            }

        }
        return $temp;
    }
}
