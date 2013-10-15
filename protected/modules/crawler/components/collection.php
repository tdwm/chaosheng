<?php
Yii::import('ext.*');
Yii::import('application.modules.crawler.components.*');
Yii::import('application.modules.crawler.crawlerfiles.*');
class collection {

    protected static $url,$config;

    /**
     * 采集内容
     * @param string $url    采集地址
     * @param array $config  配置参数
     * @param integer $page  分页采集模式
     */
    public static function get_content($url, $config, $page = 0) {
        $url = str_replace(" ", '%20', trim($url));
        set_time_limit(300);
        static $oldurl = array();
        $page = intval($page) ? intval($page) : 0;
        if ($html = self::get_html_sockt($url, $config)) {
            if ('utf-8' != $config['sourcecharset'] && $config['sourcetype'] != 4) {
                $html = iconv($config['sourcecharset'], 'UTF-8//IGNORE', $html);
            }
            $html = @preg_replace('/<script[^>]*?>(.*?)<\/script>/si', '', $html);
            if (empty($page)) {
                //获取标题
                if ($config['title_rule']) {
                    $title_rule = self::replace_sg($config['title_rule']);
                    $data['title'] = self::replace_item(self::cut_html($html, $title_rule[0], $title_rule[1]), $config['title_html_rule']);
                }

                //获取作者
                if ($config['author_rule']) {
                    $author_rule =  self::replace_sg($config['author_rule']);
                    $data['author'] = self::replace_item(self::cut_html($html, $author_rule[0], $author_rule[1]), $config['author_html_rule']);
                }

                //获取来源
                if ($config['media_rule']) {
                    $media_rule =  self::replace_sg($config['media_rule']);
                    $data['media'] = self::replace_item(self::cut_html($html, $media_rule[0], $media_rule[1]), $config['media_html_rule']);
                    $data['media'] = strip_tags($data['media']);
                }

                //获取时间
                if ($config['time_rule']) {
                    $time_rule =  self::replace_sg($config['time_rule']);
                    $data['time'] = self::replace_item(self::cut_html($html, $time_rule[0], $time_rule[1]), $config['time_html_rule']);
                }
                if (empty($data['time'])) $data['time'] = date('Y-m-d H:i:s');

                //获取keywords
                if ($config['keywords_rule']) {
                    $keywords_rule =  self::replace_sg($config['keywords_rule']);
                    $data['keywords'] = self::replace_item(self::cut_html($html, $keywords_rule[0], $keywords_rule[1]), $config['keywords_html_rule']);
                }

                //对自定义数据进行采集
                if ($config['customize_config'] = myFunc::string2array($config['customize_config'])) {
                    foreach ($config['customize_config'] as $k=>$v) {
                        if (empty($v['rule'])) continue;
                        $rule =  self::replace_sg($v['rule']);
                        $data[$v['en_name']] = self::replace_item(self::cut_html($html, $rule[0], $rule[1]), $v['html_rule']);
                    }
                }
            }

            //获取内容
            if ($config['content_rule']) {
                $content_rule =  self::replace_sg($config['content_rule']);
                $data['content'] = self::replace_item(self::cut_html($html, $content_rule[0], $content_rule[1]), $config['content_html_rule']);
                $data['content'] = strip_tags($data['content'],'<p>,<img>,<br>');
            }


            //处理分页
            if (in_array($page, array(0,2)) && !empty($config['content_page_start']) && !empty($config['content_page_end'])) {
                $oldurl[] = $url;
                $tmp[] = $data['content'];
                $page_html = self::cut_html($html, $config['content_page_start'], $config['content_page_end']);
                //上下页模式
                if ($config['content_page_rule'] == 2 && in_array($page, array(0,2)) && $page_html) {
                    preg_match_all('/<a[^>]*href=[\'"]?([^>\'" ]*)[\'"]?[^>]*>([^<\/]*)<\/a>/i', $page_html, $out);
                    if (!empty($out[1]) && !empty($out[2])) {
                        foreach ($out[2] as $k=>$v) {
                            if (strpos($v, $config['content_nextpage']) === false) continue;
                            if ($out[1][$k] == '#') continue;
                            $out[1][$k] = self::url_check($out[1][$k], $url, $config);
                            if (in_array($out[1][$k], $oldurl)) continue;
                            $oldurl[] = $out[1][$k];
                            $results = self::get_content($out[1][$k], $config, 2);
                            if (!in_array($results['content'], $tmp)) $tmp[] = $results['content'];
                        }
                    }
                }

                //全部罗列模式
                if ($config['content_page_rule'] == 1 && $page == 0 && $page_html) {
                    preg_match_all('/<a[^>]*href=[\'"]?([^>\'" ]*)[\'"]?/i', $page_html, $out);
                    if (is_array($out[1]) && !empty($out[1])) {

                        $out = array_unique($out[1]);
                        foreach ($out as $k=>$v) {
                            if ($out[1][$k] == '#') continue;
                            $v = self::url_check($v, $url, $config);
                            $results = self::get_content($v, $config, 1);
                            if (!in_array($results['content'], $tmp)) $tmp[] = $results['content'];
                        }
                    }

                }
                $data['content'] = $config['content_page'] == 1 ? implode('[page]', $tmp) : implode('', $tmp);
            }
            if ($page == 0) {
                self::$url = $url;
                self::$config = $config;
                $data['content'] = preg_replace('/<img[^>]*src=["\']?([^>\'"\s]*)["\']?[^>]*>/ie', "self::download_img('$0', '$1')", $data['content']);
                //下载内容中的图片到本地
                if (empty($page) && !empty($data['content']) && $config['down_attachment'] == 1) {
                    $attachment = new attachment('collects',$config['nodeid'],'1','uploads');
                    $data['content'] = $attachment->download('content', $data['content'],$config['watermark']);
                }
            }
            return $data;
        }
    }


    public static function get_content_file($url, $config)
    {
        $url = str_replace(" ", '%20', trim($url));
        set_time_limit(300);
        static $oldurl = array();
        $page = intval($page) ? intval($page) : 0;
        if ($html = self::get_html_sockt($url, $config)) {
            if ('utf-8' != $config['sourcecharset'] && $config['sourcetype'] != 4) {
                $html = iconv($config['sourcecharset'], 'UTF-8//IGNORE', $html);
            }
            require_once('simple_html_dom_node.php');
            $dom = str_get_html($html);
            //$dom = new simple_html_dom_node($html);
            $className = CollectsNode::model()->getCrawlerFile($config['sign'],'content','');
            $crawler = new $className($url,$dom);
            $data = $crawler->getData();
            if ($page == 0) {
                self::$url = $url;
                self::$config = $config;
                $data['content'] = preg_replace('/<img[^>]*src=["\']?([^>\'"\s]*)["\']?[^>]*>/ie', "self::download_img('$0', '$1')", $data['content']);
                //下载内容中的图片到本地
                if (empty($page) && !empty($data['content']) && $config['down_attachment'] == 1) {
                    $attachment = new attachment('collects',$config['nodeid'],'1','uploads');
                    $data['content'] = $attachment->download('content', $data['content'],$config['watermark']);
                }
            }
            return $data;
        }
    }

    /**
     * 转换图片地址为绝对路径，为下载做准备。
     * @param array $out 图片地址
     */
    protected static function download_img($old, $out) {
        $old = str_replace('\"','',$old);
        $old = str_replace('\'','',$old);
        if (!empty($old) && !empty($out) && strpos($out, '://') === false) {
            return str_replace($out, self::url_check($out, self::$url, self::$config), $old);
        } else {
            return $old;
        }
    }

    /**
     * 得到需要采集的网页列表页
     * @param array $config 配置参数
     * @param integer $num  返回数
     */
    public static function url_list(&$config, $num = '') {
        $url = array();
        switch ($config['sourcetype']) {
        case '1'://序列化
            $num = empty($num) ? $config['pagesize_end'] : $num;
            for ($i = $config['pagesize_start']; $i <= $num; $i = $i + $config['par_num']) {
                $url[] = str_replace('(*)', $i, $config['urlpage']);
            }
            break;
        case '2'://多网址
            $url = explode("\r\n", $config['urlpage']);
            break;
        case '3'://单一网址
            case '4'://RSS
                $url[] = $config['urlpage'];
                break;
        }
        return $url;
    }

    /**
     * 获取文章网址
     * @param string $url           采集地址
     * @param array $config         配置
     */
    public static function get_url_lists($url, &$config) {
        $data = array();
        $i = 0;
        if(is_array($url)){
            foreach($url as $l){
                $htmls[$l] = self::get_html_sockt($l,$config);
            }
        }else{ 
            $htmls[$url] = self::get_html_sockt($url,$config);
        }

        if ($htmls){
            foreach ($htmls as  $fetchurl => $html) {
                if ('utf-8' != $config['urlcharset'] && $config['sourcetype'] != 4) {
                    $html = iconv($config['urlcharset'], 'UTF-8//IGNORE', $html);
                }
                $i++;
                if ($config['sourcetype'] == 4) { //RSS
                    $xml = pc_base::load_sys_class('xml');
                    $html = $xml->xml_unserialize($html);
                    if (pc_base::load_config('system', 'charset') == 'gbk') {
                        $html = array_iconv($html, 'utf-8', 'gbk');
                    }
                    if (is_array($html['rss']['channel']['item']))foreach ($html['rss']['channel']['item'] as $k=>$v) {
                        $data[]= array(
                            'url' => $v['link'],  
                            'title' => $v['title'],
                        );
                    }
                } elseif ($config['ifstring'] == 1) { //ifstring
                    $html = self::cut_html($html, $config['url_start'], $config['url_end']);
                    $html = str_replace(array("\r", "\n"), '', $html);
                    $html = stripslashes($html);
                    $preg_url_rule = '/'.str_replace('[内容]','([^\'"]*)',$config['string_url_rule']).'/i';
                    $preg_title_rule = '/'.str_replace('[内容]','([^\'"]*)',$config['string_title_rule']).'/i';
                    preg_match_all($preg_url_rule, $html, $out);
                    $urls = array_unique($out[1]);
                    if (trim($config['string_title_rule'])) {
                        preg_match_all($preg_title_rule, $html, $out);
                        $titles = array_unique($out[1]);
                    } else {
                        $titles = range(0,count($urls));
                    }
                    foreach ($urls as $k=>$v) {
                        if ($config['url_contain']) {
                            if (strpos($v, $config['url_contain']) === false) {
                                continue;
                            } 
                        }
                        if ($config['url_except']) {
                            if (strpos($v, $config['url_except']) !== false) {
                                continue;
                            } 
                        }
                        $data[]= array(
                            'url' => $v,  
                            'title' => $titles[$k],
                        );
                    }
                } else {
                    $html = self::cut_html($html, $config['url_start'], $config['url_end']);
                    $html = str_replace(array("\r", "\n"), '', $html);
                    $html = str_replace(array("</a>", "</A>"), "</a>\n", $html);
                    preg_match_all('/<a([^>]*)>([^\/a>].*)<\/a>/i', $html, $out);
                    $out[1] = array_unique($out[1]);
                    $out[2] = array_unique($out[2]);
                    foreach ($out[1] as $k=>$v) {
                        if (preg_match('/href=[\'"]?([^\'" ]*)[\'"]?/i', $v, $match_out)) {
                            if ($config['url_contain']) {
                                if (strpos($match_out[1], $config['url_contain']) === false) {
                                    continue;
                                } 
                            }

                            if ($config['url_except']) {
                                if (strpos($match_out[1], $config['url_except']) !== false) {
                                    continue;
                                } 
                            }
                            $url2 = $match_out[1];
                            $url2 = self::url_check($url2, $fetchurl , $config);

                            $data[]= array(
                                'url' => $url2,
                                'title' => strip_tags($out[2][$k]),
                            );
                        } else {
                            continue;
                        }
                    }
                }
            }
            return $data;
        } else {
            return false;
        } 
    }

    /**
     * 获取远程HTML
     * @param string $url    获取地址
     * @param array $config  配置
     */
    protected static function get_html_sockt($url, &$config)
    {
        $bits = parse_url($url);  
        $query = isset($bits['query']) ? $bits['query'] : '';  
        $path = isset($bits['path']) ? $bits['path'] : '/';  
        $query = isset($bits['query'])? $bits['query']:'';

        $client = new HttpClient($bits['host']);
        $client->setMaxRedirects(3);
        //$client->setDebug(true);
        if (!$client->get($path.'?'.$query)) {
            throw new CHttpException(400,'URL:'.$l.' ERROR:' . $client->getError());
        }
        if ($client->getStatus() != '200') {
            throw new CHttpException(400,'URL:'.$l.' ERROR: not 200 status ' . $client->getError());
        }
        $html = $client->getContent();
        $html = @preg_replace('/<script[^>]*?>(.*?)<\/script>/si', '', $html);
        if (!empty($url) && !empty($html)) {
            return $html;
        } else {
            return false;
        }
    }

    protected static function get_html_use_header($url,&$config)
    {
    //url = http://roll.finance.qq.com/interface/roll.php?0.894680823199451&cata=&site=finance&date=&page=2&mode=1&of=json
        $bits = parse_url($url);  
        $query = isset($bits['query']) ? $bits['query'] : '';  
        $path = isset($bits['path']) ? $bits['path'] : '/';  
        $query = isset($bits['query'])? $bits['query']:'';

        $client = new HttpClient($bits['host']);
        //$client->setDebug(true);
        if (!$client->get($path.'?'.$query)) {
            throw new CHttpException(400,'URL:'.$l.' ERROR:' . $client->getError());
        }
        if ($client->getStatus() != '200') {
            throw new CHttpException(400,'URL:'.$l.' ERROR: not 200 status ' . $client->getError());
        }
        $html = $client->getContent();
        if (!empty($url) && !empty($html)) {
            return $html;
        } else {
            return false;
        }
    
    }

    /**
     * 
     * HTML切取
     * @param string $html    要进入切取的HTML代码
     * @param string $start   开始
     * @param string $end     结束
     */
    protected static function cut_html($html, $start, $end) {
        if (empty($html)) return false;
        $html = str_replace(array("\r", "\n"), "", $html);
        $start = str_replace(array("\r", "\n"), "", $start);
        $end = str_replace(array("\r", "\n"), "", $end);
        if(empty($start)) return $html;
        $html = explode(trim($start), $html);
        if(empty($end)) return $html[1];
        if(is_array($html)) $html = explode(trim($end), $html[1]);
        return $html[0];
    }

    /**
     * 过滤代码
     * @param string $html  HTML代码
     * @param array $config 过滤配置
     */
    protected static function replace_item($html, $config) {
        if (empty($config)) return $html;
        $config = explode("\n", $config);
        $patterns = $replace = array();
        $p = 0;
        foreach ($config as $k=>$v) {
            if (empty($v)) continue;
            $c = explode('[|]', $v);
            $patterns[$k] = '/'.str_replace('/', '\/', $c[0]).'/is';
            $replace[$k] = trim($c[1]);
            $p = 1;
        }
        $html = @preg_replace('/<script[^>]*?>(.*?)<\/script>/si', '', $html);
        return $p ? trim(@preg_replace($patterns, $replace, $html)) : false;
    }

    /**
     * 替换采集内容
     * @param $html 采集规则
     */
    protected static function replace_sg($html) {
        $list = explode('[内容]', $html);
        if (is_array($list)) foreach ($list as $k=>$v) {
            $list[$k] = str_replace(array("\r", "\n"), '', trim($v));
        }
        return $list;
    }

    /**
     * URL地址检查
     * @param string $url      需要检查的URL
     * @param string $baseurl  基本URL
     * @param array $config    配置信息
     */
    protected static function url_check($url, $baseurl, $config) {
        $urlinfo = parse_url($baseurl);
        $baseurl = $urlinfo['scheme'].'://'.$urlinfo['host'].(substr($urlinfo['path'], -1, 1) === '/' ? substr($urlinfo['path'], 0, -1) : str_replace('\\', '/', dirname($urlinfo['path']))).'/';
        if (strpos($url, '://') === false) {
            if ($url[0] == '/') {
                $url = $urlinfo['scheme'].'://'.$urlinfo['host'].$url;
            } else {
                if ($config['page_base']) {
                    $url = $config['page_base'].$url;
                } else {
                    $url = $baseurl.$url;
                }
            }
        }
        return $url;
    }
}
