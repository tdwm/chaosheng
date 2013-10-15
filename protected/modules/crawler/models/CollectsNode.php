<?php
/**
 * This is the model class for table "collects_node".
 *
 * The followings are the available columns in table 'collects_node':
 * @property integer $nodeid
 * @property string $name
 * @property string $lastdate
 * @property string $sourcecharset
 * @property integer $urlcharset
 * @property integer $sourcetype
 * @property string $urlpage
 * @property integer $pagesize_start
 * @property integer $pagesize_end
 * @property string $page_base
 * @property integer $par_num
 * @property string $url_contain
 * @property string $url_except
 * @property string $url_start
 * @property string $url_end
 * @property string $title_rule
 * @property string $title_html_rule
 * @property string $keywords_rule
 * @property string $keywords_html_rule
 * @property string $author_rule
 * @property string $author_html_rule
 * @property string $media_rule
 * @property string $media_html_rule
 * @property string $time_rule
 * @property string $time_html_rule
 * @property string $content_rule
 * @property string $content_html_rule
 * @property string $content_page_start
 * @property string $content_page_end
 * @property integer $content_page_rule
 * @property integer $content_page
 * @property string $content_nextpage
 * @property integer $down_attachment
 * @property integer $watermark
 * @property integer $coll_order
 * @property string $customize_config
 * @property string $frequency
 * @property string $sign
 */
class CollectsNode extends CActiveRecord
{
    public $crawlFileDir;
    public $crawl_temp_url;
    public $crawl_temp_content;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CollectsNode the static model class
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
        return 'collects_node';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, urlpage, frequency, sign', 'required'),
            array('sourcetype,urlcrawlbyfile,contentcrawlbyfile, pagesize_start, pagesize_end, par_num, content_page_rule, content_page, down_attachment, watermark, coll_order', 'numerical', 'integerOnly'=>true),
            array('name, frequency', 'length', 'max'=>20),
            array('sourcecharset,urlcharset', 'length', 'max'=>5),
            array('page_base', 'length', 'max'=>255),
            array('url_contain, url_except, url_start, url_end, title_rule, keywords_rule, author_rule, media_rule, time_rule, content_rule, content_page_start, content_page_end, content_nextpage', 'length', 'max'=>100),
            array('sign', 'length', 'max'=>50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('nodeid, name, lastdate, sourcecharset,urlcharset, sourcetype,ifstring,string_title_rule,string_url_rule, urlpage, pagesize_start, pagesize_end, page_base, par_num, url_contain, url_except, url_start, url_end, title_rule, title_html_rule, keywords_rule, keywords_html_rule, author_rule, author_html_rule, media_rule, media_html_rule, time_rule, time_html_rule, content_rule, content_html_rule, content_page_start, content_page_end, content_page_rule, content_page, content_nextpage, down_attachment, watermark, coll_order, customize_config, frequency, sign', 'safe'),
            array('nodeid, name, lastdate, sourcecharset,urlcharset, sourcetype, urlpage, pagesize_start, pagesize_end, page_base, par_num, url_contain, url_except, url_start, url_end, title_rule, title_html_rule, keywords_rule, keywords_html_rule, author_rule, author_html_rule, media_rule, media_html_rule, time_rule, time_html_rule, content_rule, content_html_rule, content_page_start, content_page_end, content_page_rule, content_page, content_nextpage, down_attachment, watermark, coll_order, customize_config, frequency, sign', 'safe', 'on'=>'search'),
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
            'nodeid' => 'Nodeid',
            'name' => '采集名称',
            'lastdate' => '采集时间',
            'frequency' => '采集频率',
            'sign' => '唯一标识',
            'urlcrawlbyfile' => '采集列表by文件',
            'contentcrawlbyfile' => '采集内容by文件',
            'sourcecharset' => 'show页面编码',
            'urlcharset' => 'url页面编码',
            'sourcetype' => '网址类型',
            'ifstring' => '网址代码类型',
            'string_title_rule' => '字符标题匹配',
            'string_url_rule' => '字符url匹配',
            'urlpage' => '采集网址',
            'pagesize_start' => '页码开始',
            'pagesize_end' => '页码结束',
            'page_base' => 'base配置',
            'par_num' => '增加幅度',
            'url_contain' => 'url包含',
            'url_except' => 'url不包含',
            'url_start' => '网址采集开始位置',
            'url_end' => '网址采集结束位置',
            'title_rule' => 'Title 匹配原则',
            'title_html_rule' => 'Title 过滤原则',
            'keywords_rule' => 'Keywords 匹配原则',
            'keywords_html_rule' => 'Keywords 过滤原则',
            'author_rule' => 'Author 匹配原则',
            'author_html_rule' => 'Author 过滤原则',
            'media_rule' => 'media 匹配原则',
            'media_html_rule' => 'media 过滤原则',
            'time_rule' => 'Time 匹配原则',
            'time_html_rule' => 'Time 过滤原则',
            'content_rule' => 'Content 匹配原则',
            'content_html_rule' => 'Content 过滤原则',
            'content_page_start' => '分页匹配开始',
            'content_page_end' => '分页匹配结束',
            'content_page_rule' => '分页 匹配原则',
            'content_page' => '内容分页',
            'content_nextpage' => '下一页规则',
            'down_attachment' => '下载图片',
            'watermark' => '添加水印',
            'coll_order' => '导入顺序',
            'customize_config' => '用户自定义',
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

        $criteria->compare('nodeid',$this->nodeid);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('lastdate',$this->lastdate,true);
        $criteria->compare('sourcecharset',$this->sourcecharset,true);
        $criteria->compare('urlcharset',$this->urlcharset);
        $criteria->compare('sourcetype',$this->sourcetype);
        $criteria->compare('urlpage',$this->urlpage,true);
        $criteria->compare('pagesize_start',$this->pagesize_start);
        $criteria->compare('pagesize_end',$this->pagesize_end);
        $criteria->compare('page_base',$this->page_base,true);
        $criteria->compare('par_num',$this->par_num);
        $criteria->compare('url_contain',$this->url_contain,true);
        $criteria->compare('url_except',$this->url_except,true);
        $criteria->compare('url_start',$this->url_start,true);
        $criteria->compare('url_end',$this->url_end,true);
        $criteria->compare('title_rule',$this->title_rule,true);
        $criteria->compare('title_html_rule',$this->title_html_rule,true);
        $criteria->compare('keywords_rule',$this->keywords_rule,true);
        $criteria->compare('keywords_html_rule',$this->keywords_html_rule,true);
        $criteria->compare('author_rule',$this->author_rule,true);
        $criteria->compare('author_html_rule',$this->author_html_rule,true);
        $criteria->compare('media_rule',$this->media_rule,true);
        $criteria->compare('media_html_rule',$this->media_html_rule,true);
        $criteria->compare('time_rule',$this->time_rule,true);
        $criteria->compare('time_html_rule',$this->time_html_rule,true);
        $criteria->compare('content_rule',$this->content_rule,true);
        $criteria->compare('content_html_rule',$this->content_html_rule,true);
        $criteria->compare('content_page_start',$this->content_page_start,true);
        $criteria->compare('content_page_end',$this->content_page_end,true);
        $criteria->compare('content_page_rule',$this->content_page_rule);
        $criteria->compare('content_page',$this->content_page);
        $criteria->compare('content_nextpage',$this->content_nextpage,true);
        $criteria->compare('down_attachment',$this->down_attachment);
        $criteria->compare('watermark',$this->watermark);
        $criteria->compare('coll_order',$this->coll_order);
        $criteria->compare('customize_config',$this->customize_config,true);
        $criteria->compare('frequency',$this->frequency,true);
        $criteria->compare('sign',$this->sign,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public function beforeSave()
    {
        Yii::app()->cache->delete('nodes');
        return parent::beforeSave();
    }

    public function afterSave()
    {
        self::getNodeCache();
        return parent::afterSave();
    }

    public static function getNodeCache()
    {
        $response = Yii::app()->cache->get('nodes');

        if ($response == NULL)
        {
            $response = Yii::app()->db->createCommand('SELECT nodeid,name, lastdate, sign FROM collects_node ')->queryAll();
            Yii::app()->cache->set('nodes', $response);
        }
        return $response;
    }

    public static function getNodeArray($id)
    {
        $c = new CollectsNode();
        $caches  =  $c->getNodeCache();
        foreach($caches as $v){
            if($v['nodeid'] == $id) {
                return $v;
            }    
        }
        return array();
    }

    /*
     * 初始化创建采集文件
     */
    public function crawlerInit()
    {
        $this->crawlFileDir = dirname(__File__).'/../crawlfiles/';
        $this->crawl_temp_url = $this->crawlFileDir.'url.template.php';
        $this->crawl_temp_content = $this->crawlFileDir.'content.template.php';
    }

    /*
     * 创建采集代码文件
     * params $sign:唯一标识
     */
    public function makeCrawlerFile($sign)
    {

        $this->crawlerInit();
        $urlFileName = $sign.'.url.php';
        $contentFileName = $sign.'.content.php';
        $urlFile = $this->crawlFileDir.$urlFileName;
        $contentFile = $this->crawlFileDir.$contentFileName;
        if(!file_exists($urlFile))
        {
            $template = file_get_contents($this->crawl_temp_url);
            $newUrl = str_replace('[CLASSNAME]',$sign.'Url',$template);
            $fileHandle = fopen($urlFile, 'w') or die("file could not be accessed/created");
            fwrite($fileHandle, $newUrl);
            fclose($fileHandle);
            chmod($urlFile,0777);
        }
        if(!file_exists($contentFile))
        {
            $template = file_get_contents($this->crawl_temp_content);
            $newContent = str_replace('[CLASSNAME]',$sign.'Content',$template);
            $fileHandle = fopen($contentFile, 'w') or die("file could not be accessed/created");
            fwrite($fileHandle, $newContent);
            fclose($fileHandle);
            chmod($contentFile,0777);
        }
    }


    /*
     * 获取文件采集代码或者类名称
     * params $sign:唯一标识
     *        $file:文件标识
     *        $type:获取名还是文件内容
     */
    public function getCrawlerFile($sign,$file='url',$type='code')
    {
        $this->crawlerInit();
        $cfile = $this->crawlFileDir.$sign.".".$file.'.php';    
        if($type == 'code'){
            return file_get_contents($cfile);
        }else{
            require_once($cfile);
            //echo $sign.ucwords($file);exit;
            return $sign.ucwords($file);
        }
    }

    /*
     * 获取文章采集字段
     */
    public static function getCrawlerFileds($sign)
    {
        $className = CollectsNode::model()->getCrawlerFile($sign,'content','');
        return $className::getFileds();
    }

    /*
     * 保存采集代码文件
     * params $sign:唯一标识
     *        $file:文件标识
     *        $data:数据
     */
    public function saveCrawlerFile($sign,$file='url',$data='')
    {
        $this->crawlerInit();
        $cfile = $this->crawlFileDir.$sign.".".$file.'.php';    
        $fileHandle = fopen($cfile, 'w') or die("file could not be accessed/created");
        $result = fwrite($fileHandle, $data);
        fclose($fileHandle);
        //chmod($cfile,0777);
        return $result;
    }
}
?>
