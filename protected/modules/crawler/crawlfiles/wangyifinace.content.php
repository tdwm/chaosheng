<?php
require_once('content.abstract.php');
class wangyifinaceContent extends contentCrawlerAbstract 
{

    protected $contentdom = '';

    public function init()
    {
        $dom = $this->htmldom->find('.ep-content-main',0);
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
        $dom = $this->contentdom->find('div[id="endText"]',0);
        if (empty($dom)){
            return false;
        }
		$dom->find('div.ep-source',0)->innertext = '';
		$content = '';
		//$dom->find('div')->innertext = null;
		foreach($dom->find('p') as $p)
		{
			$p->style = "TEXT-INDENT: 2em";
			$content .= $p->outertext;
		}
        $content = trim(strip_tags($content,'<p>,<img>,<br>,<b>'));
        $this->data['content'] = $content;
        return true;
    }

    protected function getCategory() {
        $dom = $this->htmldom->find('span.ep-crumb a',2);
        if (empty($dom)){
            return false;
        }
        $category = $dom->innertext;
        $this->data['category'] = $category;
        return true;
    }

    protected function getMedia() {
        $dom = $this->contentdom->find('.ep-info a',0);
        if (empty($dom)){
            return false;
        }
        $media = $dom->innertext;
		$replace = array(
			'(www.wind.com.cn)',
			'www.gemag.com.cn',
		);
		$media = str_replace($replace,'',$media);
        $this->data['media'] = trim($media);
        return true;
    }
	protected function getTime() {
        $dom = $this->contentdom->find('.ep-info',0);
        if (empty($dom)){
            return false;
        }
	   $time = mb_substr(trim(strip_tags(trim($dom->innertext))),0,22);
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