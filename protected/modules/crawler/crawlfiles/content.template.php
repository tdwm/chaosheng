<?php
require_once('content.abstract.php');
class [CLASSNAME] extends contentCrawlerAbstract 
{

    protected $contentdom = '';

    public function init()
    {
        $dom = $this->htmldom->find('div[id=""]',0);
        if (empty($dom)){
            return false;
        }
        $this->contentdom = $dom;
        return true;

    }

    protected function getTitle() {
        $dom = $this->contentdom->find('h1',0);
        if (empty($dom)){
            return false;
        }
        $this->data['title'] = $this->contentdom->find('h1',0)->plaintext;
        return true;
    }

    protected function getContent() {
        $dom = $this->contentdom->find('div[id="content"]',0);
        if (empty($dom)){
            return false;
        }
        $content = $dom->innertext;
        $content = trim(strip_tags($content,'<p>,<img>,<br>'));
        $this->data['content'] = $content;
        return true;
    }

    protected function getCategory() {
        $dom = $this->htmldom->find('div[id="breadcrumbs"]',1);
        if (empty($dom)){
            return false;
        }
        $category = $dom->innertext;
        $this->data['category'] = $category;
        return true;
    }

    protected function getMedia() {
        $dom = $this->contentdom->find('div[id="media_name"]',0);
        if (empty($dom)){
            return false;
        }
        $media = $dom->innertext;
        $this->data['media'] = $media;
        return true;
    }

    protected function getTime() {
        $dom = $this->contentdom->find('div[id="date"]');
        if (empty($dom)){
            return false;
        }
        $time = $dom->innertext;
        $this->data['time'] = $time;
        return true;
    }

    public function getData(){
        $ret = $this->init();
        if ($ret === false){
            return false;
        }
        $this->getKeywords();
        $this->getDescription();
        $this->getCategory();
        $this->getMedia();
        $this->getTime();
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

    public static function getFileds()
    {
        $fileds = parent::getFileds();
        return $fileds;
    }
}
?>
