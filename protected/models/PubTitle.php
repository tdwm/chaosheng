<?php

/**
 * This is the model class for table "pub_title".
 *
 * The followings are the available columns in table 'pub_title':
 * @property integer $id
 * @property integer $cid
 * @property integer $col_id
 * @property string $col_title
 * @property string $col_category
 * @property string $col_time
 * @property integer $col_author
 * @property string $col_media
 * @property integer $status
 * @property string $slug
 * @property string $created
 * @property string $updated
 */
class PubTitle extends CiiModel
{
    public $pageSize = 20;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PubTitle the static model class
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
		return 'pub_title';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cid, col_id ', 'required'),
			array('cid, col_id, status', 'numerical', 'integerOnly'=>true),
			array('col_title', 'length', 'max'=>150),
			array('col_url', 'length', 'max'=>250),
			array('col_category, col_media', 'length', 'max'=>20),
			array('slug', 'length', 'max'=>50),
			array('created, updated,col_title, col_category, col_time, col_author, col_media', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, cid, col_id, col_title, col_category, col_time, col_author, col_media, status, slug, created, updated', 'safe', 'on'=>'search'),
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
			'cid' => '分类id',
			'col_id' => '采集id',
			'col_title' => '标题',
			'col_url' => '采集地址',
			'col_category' => '采集分类',
			'col_time' => '采集发布时间',
			'col_author' => '采集作者',
			'col_media' => '采集媒体',
			'status' => '状态',
			'slug' => 'Slug',
			'created' => '创建时间',
			'updated' => '更新时间',
		);
	}

    public function canpush()
    {
        return array(
			'col_title' => '标题',
			'col_category' => '分类',
			'col_time' => '时间',
			'col_author' => '作者',
			'col_media' => '媒体',
        );
    }

    public function beforesave()
    {
        if($this->isNewRecord){
            $this->created = $this->updated = new CDbExpression('NOW()');
        } else {
            $this->updated = new CDbExpression('NOW()');
        }
	    return parent::beforeSave();
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
		$criteria->compare('cid',$this->cid);
		$criteria->compare('col_id',$this->col_id);
		$criteria->compare('col_title',$this->col_title,true);
		$criteria->compare('col_category',$this->col_category,true);
		$criteria->compare('col_time',$this->col_time,true);
		$criteria->compare('col_author',$this->col_author);
		$criteria->compare('col_media',$this->col_media,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);
        $criteria->order = 'id desc';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => $this->pageSize,
            )
		));
	}
}
