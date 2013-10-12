<?php

/**
 * This is the model class for table "stat_userpush".
 *
 * The followings are the available columns in table 'stat_userpush':
 * @property integer $site_id
 * @property integer $user_id
 * @property string $daytime
 * @property integer $status
 * @property integer $pnum
 */
class StatUserpush extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return StatUserpush the static model class
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
		return 'stat_userpush';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('site_id', 'required'),
			array('site_id, user_id, status, pnum', 'numerical', 'integerOnly'=>true),
			array('daytime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('site_id, user_id, daytime, status, pnum', 'safe', 'on'=>'search'),
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
            'mysite'=>array(self::BELONGS_TO,'CollectsWeb','site_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键',
			'site_id' => '站点名称',
			'user_id' => '用户',
			'daytime' => '时间',
			'status' => '状态',
			'pnum' => '数量',
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

		$criteria->compare('site_id',$this->site_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('daytime',$this->daytime,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('pnum',$this->pnum);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
