<?php

/**
 * This is the model class for table "stat_site".
 *
 * The followings are the available columns in table 'stat_site':
 * @property integer $id
 * @property integer $site_id
 * @property string $daytime
 * @property string $pnum
 */
class StatSite extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return StatSite the static model class
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
		return 'stat_site';
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
			array('site_id', 'numerical', 'integerOnly'=>true),
			array('pnum', 'length', 'max'=>21),
			array('daytime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, site_id, daytime, pnum', 'safe', 'on'=>'search'),
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
            //'mysite'=>array(self::BELONGS_TO,'CollectsWeb','site_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'site_id' => '站点',
			'daytime' => '时间',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('site_id',$this->site_id);
		$criteria->compare('daytime',$this->daytime,true);
		$criteria->compare('pnum',$this->pnum,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
