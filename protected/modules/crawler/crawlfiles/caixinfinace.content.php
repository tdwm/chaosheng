<?php
require_once('content.abstract.php');
class caixinfinaceContent extends contentCrawlerAbstract 
{

    protected $contentdom = '';

    public function init()
    {
        $dom = $this->htmldom->find('div[id="the_content"]',0);
        if (empty($dom)){
            return false;
        }
        $this->contentdom = $dom;
        return true;

    }

    protected function getTitle() {
        $dom = $this->htmldom->find('h1',0);
        if (empty($dom)){
            return false;
        }
        $this->data['title'] = $dom->plaintext;
        return true;
    }

    protected function getContent() {
        $dom = $this->contentdom->find('#Main_Content_Val',0);
        if (empty($dom)){
            return false;
        }
        $content = $dom->innertext;
        $content = trim(strip_tags($content,'<p>,<img>,<br>'));
        $this->data['content'] = $content;
        return true;
    }

    protected function getCategory() {
        $dom = $this->htmldom->find('title',0);
        if (empty($dom)){
            return false;
        }
		$temp = explode('_',$dom->innertext);
        $category = $temp[1];
        $this->data['category'] = $category;
        return true;
    }

    protected function getMedia() {
        $dom = $this->htmldom->find('#artInfo',0)->find('a',0);
        if (empty($dom)){
            return false;
        }
        $media = $dom->innertext;
        $this->data['media'] = $media;
        return true;
    }

    protected function getTime() {
        $dom = $this->htmldom->find('#artInfo',0);
        if (empty($dom)){
            return false;
        }
        $time = mb_substr(trim(strip_tags($dom->plaintext)),0,25);
        $this->data['time'] = trim($time);
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