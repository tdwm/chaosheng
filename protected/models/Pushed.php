<?php

/**
 * This is the model class for table "collects_pushed".
 *
 * The followings are the available columns in table 'collects_pushed':
 * @property integer $id
 * @property integer $collect_id
 * @property integer $push_uid
 * @property integer $category_id
 * @property integer $content_id
 * @property string $created
 */
class Pushed extends CiiModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Pushed the static model class
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
		return 'collects_pushed';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array(' push_uid, category_id, content_id', 'required'),
			array('collect_id,user_id, push_uid, category_id, content_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, collect_id, push_uid, category_id, content_id, created', 'safe', 'on'=>'search'),
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
			'collect_id' => 'Collect',
			'push_uid' => 'Push Uid',
			'user_id' => 'User Uid',
			'category_id' => 'Category',
			'content_id' => 'Content',
			'created' => 'Created',
		);
	}

    public function beforeSave(){

        if ($this->isNewRecord){
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

		$criteria->compare('id',$this->id);
		$criteria->compare('collect_id',$this->collect_id);
		$criteria->compare('push_uid',$this->push_uid);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('content_id',$this->content_id);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


    public function checkPushed( $push_uid, $category_id, $content_id)
    {
    
       $check = $this->findByAttributes(array(
                        "push_uid"=>$push_uid,
                        "category_id"=>$category_id,
                        "content_id"=>$content_id,
                    ));
       //  echo $push_uid,"-",$category_id,"-",$content_id;
       return empty($check)? 1 : 2;
    }
}
