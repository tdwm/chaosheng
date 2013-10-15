<?php

/**
 * This is the model class for table "collects_titles".
 *
 * The followings are the available columns in table 'collects_titles':
 * @property integer $id
 * @property integer $web_id
 * @property string $title
 * @property string $collect_url
 * @property string $media
 * @property string $pubtime
 * @property string $catname
 * @property string $slug
 * @property string $created
 */
class CollectsTitles extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CollectsTitles the static model class
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
		return 'collects_titles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('catname', 'required'),
			array('web_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>150),
			array('collect_url', 'length', 'max'=>300),
			array('media', 'length', 'max'=>50),
			array('catname', 'length', 'max'=>20),
			array('slug', 'length', 'max'=>32),
			array('pubtime, created', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, web_id, title, collect_url, media, pubtime, catname, slug, created', 'safe', 'on'=>'search'),
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
			'web_id' => '站点主键',
			'title' => '标题',
			'collect_url' => '文章链接',
			'media' => '媒体',
			'pubtime' => '发布时间',
			'catname' => '媒体分类',
			'slug' => 'Slug',
			'created' => '采集时间',
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
		$criteria->compare('web_id',$this->web_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('collect_url',$this->collect_url,true);
		$criteria->compare('media',$this->media,true);
		$criteria->compare('pubtime',$this->pubtime,true);
		$criteria->compare('catname',$this->catname,true);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
