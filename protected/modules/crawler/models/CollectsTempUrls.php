<?php

/**
 * This is the model class for table "collects_temp_urls".
 *
 * The followings are the available columns in table 'collects_temp_urls':
 * @property string $id
 * @property string $source
 * @property string $type
 * @property string $url
 * @property string $md5
 * @property integer $status
 * @property string $ctime
 */
class CollectsTempUrls extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CollectsTempUrls the static model class
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
		return 'collects_temp_urls';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('source', 'length', 'max'=>16),
			array('type, md5', 'length', 'max'=>32),
			array('url', 'length', 'max'=>1024),
			array('ctime', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, source, type, url, md5, status, ctime', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'source' => 'Source',
			'type' => 'Type',
			'url' => 'Url',
			'md5' => 'Md5',
			'status' => 'Status',
			'ctime' => 'Ctime',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('source',$this->source,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('md5',$this->md5,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('ctime',$this->ctime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}