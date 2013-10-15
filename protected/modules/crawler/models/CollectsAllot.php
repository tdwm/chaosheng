<?php

/**
 * This is the model class for table "collects_allot".
 *
 * The followings are the available columns in table 'collects_allot':
 * @property integer $id
 * @property integer $site_id
 * @property integer $category_id
 * @property integer $type_id
 * @property integer $all_num
 * @property integer $day_num
 * @property integer $send_num
 * @property string $start_time
 * @property string $end_time
 * @property integer $period_time
 * @property integer $status
 * @property string $created
 * @property string $updated
 */
class CollectsAllot extends CActiveRecord
{
    public $status_type = array('0'=>'禁止','1'=>'允许');
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CollectsAllot the static model class
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
		return 'collects_allot';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('site_id, category_id, type_id, status', 'required'),
			array('site_id, category_id, type_id, all_num, day_num, send_num, period_time, status', 'numerical', 'integerOnly'=>true),
			array('start_time, end_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, site_id, category_id, type_id, all_num, day_num, send_num, start_time, end_time, period_time, status, created, updated', 'safe', 'on'=>'search'),
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
            //'site'=>array(self::HAS_MANY,'collectsweb','id','on'=>'t.site_id=site.id'),
            //'category'=>array(self::HAS_MANY,'categories','id','on'=>'t.category_id=category.id')
            'site'=>array(self::BELONGS_TO,'CollectsWeb','site_id'),
            'category'=>array(self::BELONGS_TO,'Categories','category_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'site_id' => '网站',
			'category_id' => '分类',
			'status' => '状态',
			'type_id' => '类型',
			'all_num' => '推送总数',
			'day_num' => '日推送量',
			'send_num' => '已推送量',
			'start_time' => '开始时间',
			'end_time' => '结束时间',
			'period_time' => '周期',
			'created' => '创建时间',
			'updated' => '更新时间',
		);
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
	 
        Yii::app()->cache->delete('allots');
	    return parent::beforeSave();
    }

    public function afterSave()
    {
        $allots = $this->model()->findAll();
        Yii::app()->cache->set('allots', $allots);
	    return parent::afterSave();
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
		$criteria->compare('site_id',$this->site_id);
		$criteria->compare('status',$this->status);
        $criteria->compare('category_id',$this->category_id);
        $criteria->compare('type_id',$this->type_id);
        if ($this->start_time)
            $criteria->addCondition("start_time > '$this->start_time'");
        if ($this->end_time)
            $criteria->addCondition("end_time < '$this->end_time'");
		$criteria->with = array('site'=>array('select'=>'my_site'),'category'=>array('select'=>'name'));

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public static function getAllots()
    {
        $allots = Yii::app()->cache->get('allots');
        if($allots == null){
            $allots = CollectsAllot::model()->findAll();
            Yii::app()->cache->set('allots', $allots);
        }
        return $allots;
    }
}
