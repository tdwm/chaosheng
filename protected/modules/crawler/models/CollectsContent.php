<?php

/**
 * This is the model class for table "collects_content".
 *
 * The followings are the available columns in table 'collects_content':
 * @property string $id
 * @property string $nodeid
 * @property integer $status
 * @property string $url
 * @property string $title
 * @property string $data
 */
class CollectsContent extends CActiveRecord
{
    public $pageSize = 20;
    public $showdata;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CollectsContent the static model class
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
		return 'collects_content';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('url, title', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('nodeid', 'length', 'max'=>10),
			array('url', 'length', 'max'=>255),
			array('title', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nodeid, status, url, title, data', 'safe'),
			array('id, nodeid, status, url, title, data', 'safe', 'on'=>'search'),
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
			'nodeid' => 'Nodeid',
			'status' => '状态',
			'url' => '原网址',
			'title' => '标题',
			'data' => '数据',
		);
	}

	public function beforeSave()
	{
    	if ($this->isNewRecord && $this->created == NULL)
    	{
    		// Implicit flush to delete the URL rules
			$this->created = new CDbExpression('NOW()');
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('nodeid',$this->nodeid,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('data',$this->data,true);

        $criteria->select = 'id,nodeid,status,title,url';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => $this->pageSize,
            )
		));
	}
}
