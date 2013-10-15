<?php

/**
 * This is the model class for table "collects_push".
 *
 * The followings are the available columns in table 'collects_push':
 * @property integer $id
 * @property integer $col_id
 * @property integer $site_id
 * @property integer $user_id
 * @property integer $category_id
 * @property string $created
 */
class CollectsPush extends CActiveRecord
{
    public $ifpush = array(0=>'未推送',1=>'正在推送',2=>'已推送',3=>'不推送');
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CollectsPush the static model class
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
        return 'collects_push';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('col_id, site_id,  category_id,user_id', 'required'),
            array('col_id, site_id, status, user_id, category_id', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, col_id, site_id,  user_id, category_id, created', 'safe', 'on'=>'search'),
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
            'col_id' => 'Col',
            'site_id' => 'Site',
            'user_id' => 'User',
            'category_id' => 'Category',
            'status' => 'status',
            'created' => 'Created',
        );
    }

    public function beforeSave()
    {
        if($this->isNewRecord){
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
        $criteria->compare('site_id',$this->site_id);
        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('category_id',$this->category_id);
        $criteria->compare('created',$this->created,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public function checkPushed($col_id, $site_id)
    {

        $check = $this->findByAttributes(array(
            "col_id"=>$col_id,
            "site_id"=>$site_id,
        ));
        //var_dump($check);
        // echo $col_id,"-",$site_id,"-",$category_id;exit;
        if(empty($check)) return $this->ifpush[0];
        return $this->ifpush[$check->status];
    }
}
