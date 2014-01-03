<?php
require_once('content.abstract.php');
class qqscienceContent extends contentCrawlerAbstract 
{

    protected $contentdom = '';

    public function init()
    {
        $dom = $this->htmldom->find('div[id="C-Main-Article-QQ"]',0);
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
        $dom = $this->contentdom->find('div[id="Cnt-Main-Article-QQ"]',0);
        if (empty($dom)){
            return false;
        }
        $content = $dom->innertext;
        $content = trim(strip_tags($content,'<p>,<img>,<br>,<style>'));
        $this->data['content'] = $content;
        return true;
    }

    protected function getCategory() {
        $dom = $this->htmldom->find('span[bosszone="crumbNav"] a',2);
        if (empty($dom)){
            return false;
        }
        $category = strip_tags($dom->innertext);
        $this->data['category'] = $category;
        return true;
    }

    protected function getMedia() {
        $dom = $this->contentdom->find('span[bosszone="jgname"]',0);
        if (empty($dom)){
            return false;
        }
        $media = strip_tags($dom->innertext);
        $this->data['media'] = $media;
        return true;
    }

    protected function getTime() {
        $dom = $this->contentdom->find('span.pubTime',0);
        if (empty($dom)){
            return false;
        }
        $time = $dom->innertext;
        $this->data['time'] = $time;
        return true;
    }

    public function getData(){
		$this->checkPage();
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
	public function checkPage()
	{
		if($this->htmldom->find('#ArtPLink',0)){
			$href = $this->htmldom->find('#ArtPLink',0)->find('li[bosszone="showAll"] a',0)->href;
			//echo $href;exit;
			$pagelink = $this->url_check($href,'http://finance.qq.com/');
			$this->htmldom = file_get_html($pagelink);
		}
	}
    public static function getFileds()
    {
        $fileds = parent::getFileds();
        return $fileds;
    }
}
?>