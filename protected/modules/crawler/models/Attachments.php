<?php

/**
 * This is the model class for table "attachment".
 *
 * The followings are the available columns in table 'attachment':
 * @property string $aid
 * @property integer $nodeid
 * @property string $filename
 * @property string $filepath
 * @property string $filesize
 * @property string $fileext
 * @property integer $isimage
 * @property integer $isthumb
 * @property integer $downloads
 * @property string $uploadtime
 * @property integer $status
 * @property string $authcode
 */
class Attachments extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Attachment the static model class
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
		return 'attachment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('filename, filepath, fileext, authcode', 'required'),
			array('nodeid, isimage, isthumb, downloads, status', 'numerical', 'integerOnly'=>true),
			array('filename', 'length', 'max'=>50),
			array('filepath', 'length', 'max'=>200),
			array('filesize, fileext, uploadtime', 'length', 'max'=>10),
			array('authcode', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('aid, nodeid, filename, filepath, filesize, fileext, isimage, isthumb, downloads, uploadtime, status, authcode', 'safe'),
			array('aid, nodeid, filename, filepath, filesize, fileext, isimage, isthumb, downloads, uploadtime, status, authcode', 'safe', 'on'=>'search'),
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
			'aid' => 'Aid',
			'nodeid' => 'Nodeid',
			'filename' => 'Filename',
			'filepath' => 'Filepath',
			'filesize' => 'Filesize',
			'fileext' => 'Fileext',
			'isimage' => 'Isimage',
			'isthumb' => 'Isthumb',
			'downloads' => 'Downloads',
			'uploadtime' => 'Uploadtime',
			'status' => 'Status',
			'authcode' => 'Authcode',
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

		$criteria->compare('aid',$this->aid,true);
		$criteria->compare('nodeid',$this->nodeid);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('filepath',$this->filepath,true);
		$criteria->compare('filesize',$this->filesize,true);
		$criteria->compare('fileext',$this->fileext,true);
		$criteria->compare('isimage',$this->isimage);
		$criteria->compare('isthumb',$this->isthumb);
		$criteria->compare('downloads',$this->downloads);
		$criteria->compare('uploadtime',$this->uploadtime,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('authcode',$this->authcode,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
