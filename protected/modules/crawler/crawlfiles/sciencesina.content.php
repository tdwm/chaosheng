<?php
require_once('content.abstract.php');
class sciencesinaContent extends contentCrawlerAbstract 
{
    protected $contentdom = '';

    public function init()
    {
        $dom = $this->htmldom->find('#J_Article_Wrap',0);
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
        $dom = $this->contentdom->find('div[id="artibody"]',0);
        if (empty($dom)){
            return false;
        }
        $content = $dom->innertext;
        $content = trim(strip_tags($content,'<p>,<img>,<br>'));
        $this->data['content'] = $content;
        return true;
    }

    protected function getCategory() {
        $dom = $this->htmldom->find('.blkBreadcrumbLink .a02',0);
        if (empty($dom)){
            return false;
        }
        $category = $dom->innertext;
        $this->data['category'] = $category;
        return true;
    }

    protected function getMedia() {
        $dom = $this->contentdom->find('#media_name',0);
        if (empty($dom)){
            return false;
        }
        $media = strip_tags($dom->innertext);
        $this->data['media'] = $media;
        return true;
    }
	protected function getTime() {
        $dom = $this->contentdom->find('#pub_date',0);
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
        $this->getMedia();
        $this->getTime();
        $this->getCategory();
        $ret = $this->getTitle();
        if ($ret === false){
			return "false title";
        }
        $this->data['title'] = trim($this->data['title']);

        $ret = $this->getContent();
        if ($ret === false){
			return "false content";
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