<?php

/**
 * This is the model class for table "collects_web".
 *
 * The followings are the available columns in table 'collects_web':
 * @property integer $id
 * @property string $secretkey
 * @property integer $uid
 * @property string $api_url
 * @property string $my_site
 * @property string $my_category
 * @property string $my_media
 * @property string $my_params
 */
class CollectsWeb extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CollectsWeb the static model class
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
		return 'collects_web';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, api_url, my_site,  my_params', 'required'),
			array('uid,my_charset', 'numerical', 'integerOnly'=>true),
			array('api_url', 'length', 'max'=>250),
			array('my_site,secretkey', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, api_url, my_site, my_params', 'safe'),
			array('id, uid,my_charset, api_url, my_site, my_params', 'safe', 'on'=>'search'),
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
            'user'=>array(self::BELONGS_TO, 'Users', 'uid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'uid' => 'Uid',
            'secretkey' => '密钥',
			'api_url' => 'api地址',
			'my_site' => '站点名称',
			'my_charset' => '编码',
			'my_params' => '其他参数',
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
		$criteria->compare('uid',$this->uid);
		$criteria->compare('api_url',$this->api_url,true);
		$criteria->compare('my_site',$this->my_site,true);
		$criteria->compare('my_params',$this->my_params,true);
        $criteria->with = array('user'=>array('select'=>'displayName'));

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function beforeSave()
	{
        Yii::app()->cache->delete('sites');
        unset(Yii::app()->session['mysites']);
	    return parent::beforeSave();
	}
    public function afterSave()
    {
        $sites = $this->model()->findAll();
        Yii::app()->cache->set('sites', $sites);
        return  parent::afterSave();
    }

	public function usersearch()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->addCondition('uid = '.Yii::app()->user->id);
		$criteria->compare('id',$this->id);
		$criteria->compare('api_url',$this->api_url,true);
		$criteria->compare('my_site',$this->my_site,true);
		$criteria->compare('my_params',$this->my_params,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public static function getSites()
    {
        $sites = Yii::app()->cache->get('sites');
        if($sites == null){
            $sites = CollectsWeb::model()->findAll();
            Yii::app()->cache->set('sites', $sites);
        }
        return $sites;
    }
}
