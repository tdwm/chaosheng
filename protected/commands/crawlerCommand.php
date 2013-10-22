<?php
error_reporting(E_ALL & ~E_NOTICE);
Yii::import('application.modules.crawler.components.*');
class crawlerCommand extends CConsoleCommand {

    public function actionImport($pid, $id=null){

        //如果是导入某个col_id
        if ($id != null) {
            $one = CollectsContent::model()->findByPk($id);
            $pid = $one->nodeid;
        }
        $program = CollectsProgram::model()->findByPk($pid);
        if(!isset($program->config['map'])) {
            return false;
        }
        $config = myFunc::string2array($program->config);
        $node = CollectsNode::model()->findByPk($program->nodeid);


        //如果是导入某个col_id
        if ($id != null) {
            $colcontents = CollectsContent::model()->findAll("id = :id and status <> :status",array(
                ':id'=>$id,':status'=>5
            ));
        //根据program id全部导入
        }else{
            /*
            $colcontents = CollectsContent::model()->findAll("nodeid = :nodeid and status = :status",array(
                ':nodeid'=>$program->nodeid,':status'=>1
            ));
             */
            $colcontents = CollectsContent::model()->findAll(array(
                'condition'=>"nodeid = :nodeid and status = :status",
                'params'=>array( ':nodeid'=>$program->nodeid,':status'=>1),
                'order'=>'id desc',
                ));
        }

        $model = $this->getContentModel($program->catid);
        $push = $model->canpush();

        foreach($colcontents as $k => $colcontent) {
            $contentmodel = $model->model()->find('col_id=:col_id',array(':col_id'=>$colcontent->id));
            if ($contentmodel == false){
                $contentmodel  = $this->getContentModel($program->catid);
            }
            //测试
            $data = myFunc::string2array($colcontent['data']);

            $contentmodel->cid = $program->catid;
            $contentmodel->col_id = $colcontent->id;
            $contentmodel->col_url = $colcontent->url;
            $contentmodel->slug = md5($colcontent->url);
            foreach($config['map']  as $field=>$ck){
                //以后处理ck
                $value = trim($data[$ck]);
                if(array_key_exists($field,$push) ) {
                    if ( $field != 'col_content' && $node->contentcrawlbyfile == 0) {
                            $contentmodel->$field = myFunc::DeleteHtml($value);
                    } else {
                        $contentmodel->{$field} = $value;
                    }
                }
                if($config['add_introduce'] == 1 && $ck == 'content'){
                    $contentmodel->col_description = mb_substr(myFunc::DeleteHtml($value),0,$config['introcude_length']*2,'UTF-8');
                }
            }
            if($contentmodel->col_content == '' || $contentmodel->col_title == ''){
                $colcontent->updateByPk($colcontent->id,array('status' => 5)); 
                continue;
            }
            
            /*
            if($titlemodel->save() && $contentmodel->save()){
                echo "yes:". $colcontent->id."<br>\n";
                $colcontent->updateByPk($colcontent->id,array('status' => 2)); 
            }else{
                echo "no:". $colcontent->id."<br>\n";
                $titlemodel = $this->getCatTitleModel($program->catid);
                $contentmodel = $this->getCatContentModel($program->catid);
                $titlemodel->deleteAll("col_id=:col_id",array(':col_id'=>$colcontent->id));
                $contentmode->deleteAll("col_id=:col_id",array(':col_id'=>$colcontent->id));
            }
             */
            try {
                if($contentmodel->save()){
                    echo "yes:". $colcontent->id."<br>\n";
                    $colcontent->updateByPk($colcontent->id,array('status' => 2)); 
                }else {
                    echo "no:". $colcontent->id."<br>\n";
                    $model->model()->deleteAll("col_id=:col_id",array(':col_id'=>$colcontent->id));
                    //$contentmodel->model()->deleteAll("col_id=:col_id",array(':col_id'=>$colcontent->id));
                }
            }catch(Exception $e) {
                echo "error:". $colcontent->id."<br>\n".$e->getMessage();
                $colcontent->updateByPk($colcontent->id,array('status' => 6)); 
                $model->model()->deleteAll("col_id=:col_id",array(':col_id'=>$colcontent->id));
                //$contentmodel->model()->deleteAll("col_id=:col_id",array(':col_id'=>$colcontent->id));
            }
        }
    }

    function actiongetcount($id)
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'id,url';
        $criteria->addCondition("nodeid=".$id);
        $criteria->addCondition("status=0");
        $count = CollectsContent::model()->count($criteria);
        echo $count;
    }

    function actioncolcontent($id){
        $data=CollectsNode::model()->findByPk($id);
        //更新附件状态
        $attach_status = false;
        $size = 1;
        $criteria = new CDbCriteria;
        $criteria->select = 'id,url';
        $criteria->addCondition("nodeid=".$id);
        $criteria->addCondition("status=0");
        $criteria->limit = $size;
        $list = CollectsContent::model()->find($criteria);
        //echo $total;
        //echo (2+(2-1)*2)/$total*100;exit;
        //$list = array();
        // $list[] = array('url'=>'http://fashion.sina.com.cn/s/ce/2013-08-05/065918681.shtml','id'=>1);
        if (!empty($list) ) {
            CollectsContent::model()->updateByPk($list['id'],array('status' => 4)); 
            $GLOBALS['downloadfiles'] = array();
            if ($data->contentcrawlbyfile) {
                $html = collection::get_content_file($list['url'], $data);
            } else {
                $html = collection::get_content($list['url'], $data);
            }
            //更新附件状态
            if($attach_status) {
                //$this->attachment_db->api_update($GLOBALS['downloadfiles'],'cj-'.$v['id'],1);
            }
            $contentModel=CollectsContent::model()->findByPk($list['id']);
            $contentModel->data = myFunc::array2string($html);
            if($contentModel->title == ''){
                $contentModel->title = $html['title'];
            }
            $contentModel->status = 1;
            if($html['content']=='' || $html['title']=='') $contentModel->status = 5;
            $contentModel->save();

            echo "yes:$id-".$list['id']."<br>\n";
            Yii::log("$list[id]","info","cool.crawler");
        } else {
          //  Yii::app()->user->setFlash('success',"采集成功,共采集内容{$total}个");
        }
        //$data->updateByPk($id,array('lastdate' => new CDbExpression('NOW()'))); 
    }

    public function actionColurllist($id){
        $data=CollectsNode::model()->findByPk($id);
        if($data == null){
            echo 400,"参数错误.<br>\n";
            exit;
        }
        $urls = collection::url_list($data);
        $total_page = count($urls);
        if ($total_page > 0) {
            $url = collection::get_url_lists($urls[0], $data);
            $total = count($url);
            $re = 0;
            if (is_array($url) && !empty($url)) foreach ($url as $v) {
                if (empty($v['url']) || empty($v['title'])) continue;
                $v['title'] = strip_tags($v['title']);
                //判断
                if($this->redundance($v,$id)) {
                    $re++;
                    continue;
                }
                //入库
                $transaction = Yii::app()->db->beginTransaction();
                try{
                    $signurlmode = new CollectsSignUrl();
                    $signurlmode->md5 = md5($v['url']);
                    $signurlmode->nodeid = $id;
                    $signurlmode->save();

                    $signtitlemode = new CollectsSignTitle();
                    $signtitlemode->md5 = md5($v['title']);
                    $signtitlemode->nodeid = $id;
                    $signtitlemode->save();

                    $contentmode = new CollectsContent();
                    $contentmode->url = $v['url'];
                    $contentmode->title = $v['title'];
                    $contentmode->nodeid = $id;
                    $contentmode->save();

                    $transaction->commit();
                    //exit;
                } catch(Exception $e){
                    $transaction->rollback(); 
                    // throw new CHttpException(400,'采集入库失败.');
                }
            }
            $data->lastdate = new CDbExpression('NOW()');
            $data->save();
            $str= "采集成功,共采集网址{$total}个,重复{$re}个,入库".($total-$re)."个<br>\n";
            echo $str;
        } else {
            throw new CHttpException(400,'没有采集.');
        }
    }
    function redundance($data,$id){
        if(isset($data['url'])){
            $have = CollectsSignUrl::model()->findByAttributes(array('md5'=>md5($data['url']),'nodeid'=>$id)); 
            if ($have) return true;
        } 
        if(isset($data['title'])){
            $have = CollectsSignTitle::model()->findByAttributes(array('md5'=>md5($data['title']))); 
            if ($have) return true;
        }
        if(isset($data['content'])){
            // $have = CollectsSignTitle::model()->findByAttributes('md5'=>md5($data['url'])); 
            // if ($have) return true;
        }
        return false;
    }
    public function getContentModel($catid)
    {
       return new Contents(); 
    }
}
