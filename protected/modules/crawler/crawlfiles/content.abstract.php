<?php
/**
 * 
 * @author wangchao
 * @since 2012-11-18
 * @package 
 *
 */
abstract class contentCrawlerAbstract{
    protected $htmldom = null;
    protected $url = '';

    //已经抓取到的文章内容
    protected $data = array(
        'keywords' => '',
        'description' => '',
        'title' => '',
        'content' => '',
        'category' => '',
        'time' => '',
        'media' => '',
    );
    public function __construct($url,$htmldom){
        $this->url = $url;
        $this->htmldom = $htmldom;
    }
    public function getData(){
        $ret = $this->init();
        if ($ret === false){
            return false;
        }
        $this->getKeywords();
        $this->getDescription();
        //$this->getCategory();
        //$this->getMedia();
        //$this->getTime();
        $ret = $this->getTitle();
        if ($ret === false){
            return false;
        }
        $this->data['title'] = trim($this->data['title']);

        $ret = $this->getContent();
        if ($ret === false){
            return false;
        }
        return $this->data;
    }



    protected function getKeywords(){
        $meta = $this->htmldom->find('meta[name="keywords"]',0);
        if (!empty($meta)) {
            $this->data['keywords'] = trim($meta->content);
        }
    }

    protected function getDescription(){
        $meta = $this->htmldom->find('meta[name="description"]',0);
        if (!empty($meta)) {
            $this->data['description'] = trim($meta->content);
        }
    }

    protected static function url_check($url, $baseurl) {
        $urlinfo = parse_url($baseurl);
        $baseurl = $urlinfo['scheme'].'://'.$urlinfo['host'].(substr($urlinfo['path'], -1, 1) === '/' ? substr($urlinfo['path'], 0, -1) : str_replace('\\', '/', dirname($urlinfo['path']))).'/';
        if (strpos($url, '://') === false) {
            if ($url[0] == '/') {
                $url = $urlinfo['scheme'].'://'.$urlinfo['host'].$url;
            } else {
                $url = $baseurl.$url;
            }
        }
        return $url;
    }

    /**
     * 过滤代码
     * @param string $html  HTML代码
     * @param array $config 过滤配置
     */
    protected static function replace_dom($html, $patterns = array(),$replace = array()) {
        $html = @preg_replace('/<script[^>]*?>(.*?)<\/script>/si', '', $html);
        return trim(@preg_replace($patterns, $replace, $html));
    }


    protected function page($pagedom,$url)
    {
        $content = $this->data['content'];
        //全部列出形式
        $pages = $pagedom->find('a',0);
        if ($pages) foreach ($pages as $k=>$v) {
            if ($v->href == '#' || $v->href == null) continue;
            $page = self::url_check($v->href, $url);
            if ($v->href == $url) continue;
            $results = $this->get_content();
            if ($results)
                $content .= $this->data['content'];
        }

        $this->data['content'] = $content;
    }

    protected function init(){
        return true;
    }

    public static function getFileds()
    {
        return  array(
            'title' => '标题',
            'time' => '时间',
            'media' => '媒体',
            'category' => '分类',
            'keywords' => '关键字',
            'description' => '摘要',
            'content' => '内容',
        );
    }
    /*
    abstract public function getAllPagerUrl();
    abstract protected function getTime();
    abstract protected function getMedia();
    abstract protected function getCategory();
     */

    abstract protected function getTitle();
    abstract protected function getContent();
}
?>
