<?php

/**
 * This is the model class for table "categories".
 *
 * The followings are the available columns in table 'categories':
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $slug
 * @property integer $flag
 * @property string $created
 * @property string $updated
 *
 * The followings are the available model relations:
 * @property Categories $parent
 * @property Categories[] $categories
 * @property CategoriesMetadata[] $categoriesMetadatas
 * @property Content[] $contents
 */
class Categories extends CiiModel
{
    public $level ;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Categories the static model class
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
		return 'categories';
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
			array(' name,flag', 'required'),
			array('parent_id, flag', 'numerical', 'integerOnly'=>true),
			array('name, slug', 'length', 'max'=>150),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, parent_id, name, slug,flag', 'safe', 'on'=>'search'),
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
			'parent' => array(self::BELONGS_TO, 'Categories', 'parent_id'),
			'metadata' => array(self::HAS_MANY, 'CategoriesMetadata', 'category_id'),
			'contents' => array(self::HAS_MANY, 'Content', 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'parent_id' => '父类',
			'name' => '名称',
			'slug' => '唯一标识',
			'path' => '路径',
			'flag' => '状态',
			'ename' => '表单名称',
			'created' => '创建时间',
			'updated' => '更新时间',
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
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('flag',$this->flag);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);
		$criteria->order = "id DESC";
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function beforeValidate()
	{
		$this->slug = $this->verifySlug($this->slug, $this->name);
			
		return parent::beforeValidate();
	}
        
	public function beforeSave()
	{
        $this->path = 0;

    	if ($this->isNewRecord){
			$this->created = new CDbExpression('NOW()');
			$this->updated = new CDbExpression('NOW()');
        } else {
			$this->updated = new CDbExpression('NOW()');
        }
        Yii::app()->cache->delete('categories-listing');
		Yii::app()->cache->delete('categories');
		Yii::app()->cache->delete('WFF-categories-url-rules');
		Yii::app()->cache->delete('categories-pid');
	    return parent::beforeSave();
	}

    public function afterSave()
    {
        
	    return parent::afterSave();
    }

    public function afterDelete()
    {

	    return parent::afterDelete();
    }

	
	public function beforeDelete()
    {   
        //删除表
        $table = 'content_'.$this->slug;
        $drop = " DROP TABLE IF EXISTS `".$table."`;";
		$response = Yii::app()->db->createCommand($drop)->execute();

        Yii::app()->cache->delete('categories');
        Yii::app()->cache->delete('categories-listing');
        Yii::app()->cache->delete('WFF-categories-url-rules');
        return parent::beforeDelete();
    }

    public function getPath($id) {
        return $this->model()->findByPk($id)->path;
    }

    public function getOptionName(){
        return str_repeat("&nbsp; &nbsp;", substr_count($this->path,',')).$this->name;
    }
	

   // 根据key查询slug
    public static function findBySlug($slug)
    {
        $caches = self::getCategoryCaches();
        foreach($caches as $k => $v){
            if($v['slug'] == $slug){
                return $v;
            }
        }
        return array();
    }

    //根据key查询所有父类
    public function getParentByKey($id)
    {
        $item = array();
        $parents = array();
        $new = array();
        $caches = self::getCategoryCaches();
        foreach($caches as $k => $v){
            if($v['id'] == $id){
                $item = $v;
            }
            $new[$v['id']] = $v;
        }
        if (empty($item)) return $item();
        $pids = explode(',', $item['path']);
        foreach($pids as $pid){
            $parents[$pid] = array(
                   'label'=>$new[$pid]['name'],
                   'url'=>$new[$pid]['name'],
               );
        }
        return $parents;
    }

    public function  getMenuTree($ids = null)
    {
        $item = array('label'=>'','url'=>'');
        $parents = array();
        $new = array();
        $listarr = array();
        $space='|----';
        $caches = self::getCategoryCaches();
        $mytree =new mytree($caches, 'id','parent_id');
        $categoryarr = $mytree->getChildren(0);
        foreach ($categoryarr as $key => $catid) {
            $cat = $mytree->getValue($catid);
            $cat['pre'] = $mytree->getLayer($catid, $space);
            $listarr[$cat['id']] = $cat;
        }
        return $parents;
    }

    public function pathParents($path, $tree){
        $path = explode(',',$v['path']); 
        $n = count($path);
        if($n == 0) return false;
        foreach($path as $pid){
            $tree = $tree[$pid];
        }
        var_dump($tree);exit;

        return $tree;
    }

    public function getTopParents(){
        $new = array();
        $caches = self::getCategoryCaches();
        foreach($caches as $v){
            if($v['parent_id'] == 0) {
                $new[] = $v;
            }    
        }
        return $new;
    }

    //根据主键获取数据
    public static function getCatArray($key)
    {
        $c = new Categories();
        $caches  =  $c->getCategoryCaches();
        foreach($caches as $v){
            if($v['id'] == $key) {
                return $v;
            }    
        }
        return array();
    }

    public function getTopParentByKey($id){
        $parents = $this->getParentByKey($id);
        if(empty($parents)) return array();
        return array_shift($parents);
    }

    public static function getCategoryCaches()
    {
        $response = Yii::app()->cache->get('categories');

        if ($response == NULL)
        {
            $response = Yii::app()->db->createCommand('SELECT id,path, parent_id, name, slug FROM categories order by path')->queryAll();
            Yii::app()->cache->set('categories', $response);
        }
        return $response;
    }

    public function getDeepById($id){
        $item = array();
        $parents = $temp = array();
        $new = array();
        $caches = self::getCategoryCaches();
        foreach($caches as $k => $v){
            if($v['id'] == $id){
                $item = $v;
            }
            $new[$v['id']] = $v;
        }
        if (empty($item)) return $item();
        $pids = explode(',', $item['path']);
        foreach($pids as $k => $pid){
          //  $parents = $this->makeDeep($new[$pid],$parents);
            if($k == 0) {
                $parents[$pid] = array( 'label'=>$new[$pid]['name'], 'url'=>$new[$pid]['name']);
            }
            $parents[$pid]['items'] = array( 'label'=>$new[$pid]['name'], 'url'=>$new[$pid]['name']);
            //这个地方很奇怪
            $temp = $parents;
            $parents = $parents[$pid];
        }
        return $temp;
    }

    public function makeDeep($pids, $categories){
    }

    function getCategoryProvider()
    {
        $categories = self::getCategoryCaches();
        return new CArrayDataProvider($categories);
    }
	
	public function getParentCategories($id, $tree = false)
    {
        // Retrieve the data from cache if necessary
        $response = self::getCategoryCaches();

        if ($tree) {
            return $this->__getTreeCategories($response, $id);
        } else {
            return $this->__getParentCategories($response, $id);
        }
    }

	private function __getTreeCategories($all_categories, $id, array $stack = array())
    {
		if ($id == 1)
        {
            //return array_reverse($stack);
            return $stack;
        }
		
		foreach ($all_categories as $k=>$v)
		{
			if ($v['id'] == $id)
            {
				$stack[$v['slug']] = $v;
				return $this->__getTreeCategories($all_categories, $v['parent_id'], $stack);
            }
		}
        
    }

    public function getTreeHtml($nodes, $root = 0)
    {
        if(!is_array($nodes)) return  '<div></div>';
        if (!isset(Yii::app()->session['admin_cslug']))
            Yii::app()->session['admin_cslug'] = 'content';
        $html = '<ul class="nav nav-list">';
        foreach($nodes as $k => $v){
            /*
            if($v['id'] == 1) {
                unset($nodes[$k]);
                continue;
            }
             */
            if($v['parent_id'] == $root){
                unset($nodes[$k]);
                $html .= '<li  class="tree-toggle"><i class="icon-minus"></i>'.$v['name'].'';
                $html .= $this->ifHaveChild($v['id'],$nodes)?$this->getNode($nodes,$v['id'], 0):'';
                $html .= '</li>';
                $html .= '<li class="divider"></li>';
            }
        }
        $html .='</ul>';
        return $html;
    }


    public function getNode($nodes,$id,$level)
    {
        $level++; 
        $html = '<ul class="nav nav-list tree">';
        foreach($nodes  as $k => $v) {
            if($v['slug'] ==  Yii::app()->session['admin_cslug']) {
                $isactive = 'active';
            } else {
                $isactive = '';
            }
            $ischild = $this->ifHaveChild($v['id'], $nodes);
            if (Yii::app()->user->role == 5) {
                $name = "<a href='".Yii::app()->baseUrl."/admin/content/?slug=".$v['slug']."' ><i class='icon-minus'></i>".$v['name']."</a>";
            } else {
                $name = "<a href='".Yii::app()->baseUrl."/admin/content/list/?slug=".$v['slug']."' ><i class='icon-minus'></i>".$v['name']."</a>";
            }
            if($v['parent_id'] == $id){
                unset($nodes[$k]);
                if($ischild){
                    $html .='<li id="tree_'.$v['slug'].'"  class="tree-toggle '.$isactive.' ">'. $name.  '';
                    $html .=$this->getNode($nodes,$v['id'],$level);
                    $html .=   '</li>';
                } else {
                    $html .='<li id="tree_'.$v['slug'].'"  class="'.$isactive.'">'.  $name.'</li>';
                }
            } 
        }
        $html .="</ul>";
        return $html;
    }

    public function echoChar($char, $num)
    {
        for($i=0;$i< $num; $i++)
        {
            $strChar .= $char;
        }
        return $strChar;
    }

    public function ifHaveChild($id,$nodes = array()){
        if(!empty($nodes)){
            foreach($nodes as $v){
                if($v['parent_id'] == $id) return true;
            }
            return false;
        } else{
            return false;
            $nodes = Yii::app()->cache->get('categories-pid');
            foreach($nodes as $v){
                if($v['parent_id'] == $id) return true;
            }
            return false;
        }
    }

    
	
	private function __getParentCategories($all_categories, $id, array $stack = array())
	{
		if ($id == 1)
		{
			return array_reverse($stack);
		}
		
		foreach ($all_categories as $k=>$v)
		{
			if ($v['id'] == $id)
			{
				$stack[$v['name']] = array(str_replace(Yii::app()->baseUrl, NULL, Yii::app()->createUrl($v['slug'])));
				return $this->__getParentCategories($all_categories, $v['parent_id'], $stack);
			}
		}
	}

    
    /**
     * checkSlug - Recursive method to verify that the slug can be used
     * This method is purposfuly declared here to so that Content::findByPk is used instead of CiiModel::findByPk
     * @param string $slug - the slug to be checked
     * @param int $id - the numeric id to be appended to the slug if a conflict exists
     * @return string $slug - the final slug to be used
     */
    public function checkSlug($slug, $id=NULL)
    {
        $content = false;
        
        // Find the number of items that have the same slug as this one
        $count = $this->countByAttributes(array('slug'=>$slug . $id));
        
        if ($count == 0)
        {
            $content = true;
            //$count = Content::model()->countByAttributes(array('slug'=>$slug . $id));
        }
        
        // If we found an item that matched, it's possible that it is the current item (or a previous version of it)
        // in which case we don't need to alter the slug
        if ($count)
        {
            if ($content)
                return $this->checkSlug($slug, ($id == NULL ? 1 : ($id+1)));
            
            // Pull the data that matches
            $data = $this->findByPk($this->id == NULL ? -1 : $this->id);
            
            // Check the pulled data id to the current item
            if ($data !== NULL && $data->id == $this->id)
                return $slug;
        }
        
        if ($count == 0 && !in_array($slug, $this->forbiddenRoutes))
            return $slug . $id;
        else
            return $this->checkSlug($slug, ($id == NULL ? 1 : ($id+1)));
    }

}
