<?php

/**
 * This is the model class for table "collects_program".
 *
 * The followings are the available columns in table 'collects_program':
 * @property string $id
 * @property string $name
 * @property string $nodeid
 * @property string $catid
 * @property string $config
 */
class CollectsProgram extends CiiModel
{
    public $init_config = array (
        'add_introduce' => '0',
        'introcude_length' => '200',
        'status' => '0',
        'map' => array (),
    );
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CollectsProgram the static model class
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
		return 'collects_program';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name,catid, config', 'required'),
			array('name', 'length', 'max'=>20),
			array('nodeid, catid', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, nodeid, catid, config', 'safe', 'on'=>'search'),
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
            'category'=>array(self::BELONGS_TO,'Categories','catid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '方案名称',
			'nodeid' => '采集',
			'catid' => '分类',
			'config' => '配置',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('nodeid',$this->nodeid,true);
		$criteria->compare('catid',$this->catid,true);
		$criteria->compare('config',$this->config,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function searchkey()
    {
    
        $criteria = new CDbCriteria;
        $criteria->addCondition("nodeid=".$id);
        $criteria->with = array('category'=>array('select'=>'name'));
		$criteria->compare('name',$this->name,true);
		$criteria->compare('nodeid',$this->nodeid,true);
		$criteria->compare('catid',$this->catid,true);
		$criteria->compare('config',$this->config,true);

		return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}
