<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property string $firstName
 * @property string $lastName
 * @property string $displayName
 * @property string $about
 * @property integer $user_role
 * @property integer $status
 * @property string $created
 * @property string $updated
 *
 * The followings are the available model relations:
 * @property Comments[] $comments
 * @property Content[] $contents
 * @property Tags[] $tags
 * @property UserMetadata[] $userMetadatas
 * @property UserRoles $userRole
 */
class Users extends CiiModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Users the static model class
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
		return 'users';
	}

	/**
	 * @return array primary key of the table
	 **/	 
	public function primaryKey()
	{
		return array('id');
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, password, displayName', 'required'),
			array('email', 'email'),
			array('email', 'unique'),
			array('user_role, status,parent_id', 'numerical', 'integerOnly'=>true),
			array('email, firstName, lastName, displayName', 'length', 'max'=>255),
			array('password', 'length', 'max'=>64),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, password, firstName,parent_id, lastName, displayName, about, user_role, status, created, updated', 'safe', 'on'=>'search'),
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
			'comments' => array(self::HAS_MANY, 'Comments', 'user_id'),
			'content' => array(self::HAS_MANY, 'Content', 'author_id'),
			'tags' => array(self::HAS_MANY, 'Tags', 'user_id'),
			'metadata' => array(self::HAS_MANY, 'UserMetadata', 'user_id'),
			'role' => array(self::BELONGS_TO, 'UserRoles', 'user_role'),
			'parent' => array(self::BELONGS_TO, 'Users', 'parent_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '序列号',
			'email' => '邮箱[账号]',
			'password' => '密码',
			'firstName' => '姓 ',
			'lastName' => '名 ',
			'displayName' => '昵称',
			'about'		=> '简介',
			'user_role' => '角色',
			'parent_id' => '所属',
			'status' => '状态',
			'created' => '注册时间',
			'updated' => '上次登录时间',
		);
	}

    /**
     * Gets the first and last name instead of the displayname
     */
    public function getName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getUsersByRole($user_role = 4)
    {
		$criteria=new CDbCriteria;
        $criteria->addCondition("user_role='$user_role'");
        return  $return =  $this->model()->findAll($criteria);
    }


    public function getParentUsers($id)
    {
    
		$response = Yii::app()->cache->get('users-pid');
		if ($response == NULL)
		{
			$response = Yii::app()->db->createCommand('SELECT id, parent_id, displayName FROM users')->queryAll();
			Yii::app()->cache->set('users-pid', $response);
		}
		
		return $this->__getParentUsers($response, $id);
    
    }
    
	
	private function __getParentUsers($all_users, $id, array $stack = array())
	{
		if ($id == 1)
		{
			return array_reverse($stack);
		}
		
		foreach ($all_users as $k=>$v)
		{
			if ($v['id'] == $id)
			{
				$stack[$v['id']] = $v;
				return $this->__getParentUsers($all_users, $v['parent_id'], $stack);
			}
		}
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

		$criteria->alias = "users";
		$criteria->compare('users.id',$this->id);
		$criteria->compare('users.email',$this->email,true);
		$criteria->compare('users.password',$this->password,true);
		$criteria->compare('users.firstName',$this->firstName,true);
		$criteria->compare('users.lastName',$this->lastName,true);
		$criteria->compare('users.displayName',$this->displayName,true);
		$criteria->compare('users.about',$this->about,true);
		$criteria->compare('users.user_role',$this->user_role);
		$criteria->compare('users.status',$this->status);
		$criteria->compare('users.created',$this->created,true);
		$criteria->compare('users.updated',$this->updated,true);
		$criteria->compare('users.parent_id',$this->parent_id,true);
		//$criteria->order = "users.id DESC";
		$criteria->with = array("role","parent"=>array('select'=>'displayName'));
        if (Yii::app()->user->role == 4) {
            $criteria->condition = " users.parent_id = '".Yii::app()->user->id."'";
        }
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function beforeSave()
	{
    	if ($this->isNewRecord)
        {
            $this->created = new CDbExpression('NOW()');
            $this->updated = new CDbExpression('NOW()');
            if (Yii::app()->user->role == 4) {
                $this->user_role = 1;
                $this->status = 1;
            }
            $this->parent_id = Yii::app()->user->id ;
        }
	   	else
			$this->updated = new CDbExpression('NOW()');

	    return parent::beforeSave();
	}
	
	/**
	 * Lets us know if the user likes a given content post or not
	 * @param  int $id The id of the content we want to know about
	 * @return bool    Whether or not the user likes the post
	 */
	public function likesPost($id = NULL)
	{
		if ($id === NULL)
			return false;

		$likes = UserMetadata::model()->findByAttributes(array('user_id' => $this->id, 'key' => 'likes'));

		if ($likes === NULL)
			return false;

		$likesArray = json_decode($likes->value, true);
		if (in_array($id, array_values($likesArray)))
			return true;

		return false;
	}

	/**
	 * Creates an encrypted hash to be used as a password
	 * @param string $email 	The user email
	 * @param string $password	The password to be encrypted
	 * @param string $_dbsalt	The salt value to be used (Yii::app()->params['encryptionKey'])
	 * @return 64 character encrypted string
	 */
	public function encryptHash($email, $password, $_dbsalt)
	{
		return mb_strimwidth(hash("sha512", hash("sha512", hash("whirlpool", md5($password . md5($email)))) . hash("sha512", md5($password . md5($_dbsalt))) . $_dbsalt), 0, 64);	
	}

	/**
	 * Returns the gravatar image url for a particular user
	 * The beauty of this is that you can call User::model()->findByPk()->gravatarImage() and not have to do anything else
	 * Implemention details borrowed from Hypatia Cii User Extensions with permission
	 * @param  string $size		The size of the image we want to display
	 * @param  string $default	The default image to be displayed if none is found
	 * @return string gravatar api image
	 */
	public function gravatarImage($size=20, $default=NULL)
	{
		return "https://www.gravatar.com/avatar/" . md5(strtolower(trim($this->email))).'?s='.$size;
	}
}
