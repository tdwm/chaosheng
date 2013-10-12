<?php
/**
 * TbUEditor.php
 *
 * Supports new UEditor 1.2.6.1 
 *
 * @author: wanzi <wangchao.logn@gmail.com>
 * Date: 13/10/06
 * Time: 6:23 PM
 'labelMap'=>array(
     'anchor'=>'锚点', 'undo'=>'撤销', 'redo'=>'重做', 'bold'=>'加粗', 'indent'=>'首行缩进','snapscreen'=> '截图',
     'italic'=>'斜体', 'underline'=>'下划线', 'strikethrough'=>'删除线', 'subscript'=>'下标',
     'superscript'=>'上标', 'formatmatch'=>'格式刷', 'source'=>'源代码', 'blockquote'=>'引用',
     'pasteplain'=>'纯文本粘贴模式', 'selectall'=>'全选', 'print'=>'打印', 'preview'=>'预览',
     'horizontal'=>'分隔线', 'removeformat'=>'清除格式', 'time'=>'时间', 'date'=>'日期',
     'unlink'=>'取消链接', 'insertrow'=>'前插入行', 'insertcol'=>'前插入列', 'mergeright'=>'右合并单元格', 'mergedown'=>'下合并单元格',
     'deleterow'=>'删除行', 'deletecol'=>'删除列', 'splittorows'=>'拆分成行', 'splittocols'=>'拆分成列', 'splittocells'=>'完全拆分单元格',
     'mergecells'=>'合并多个单元格', 'deletetable'=>'删除表格', 'insertparagraphbeforetable'=>'表格前插行', 'cleardoc'=>'清空文档',
     'fontfamily'=>'字体', 'fontsize'=>'字号', 'paragraph'=>'段落格式', 'insertimage'=>'图片', 'inserttable'=>'表格', 'link'=>'超链接',
     'emotion'=>'表情', 'spechars'=>'特殊字符', 'searchreplace'=>'查询替换', 'map'=>'Baidu地图', 'gmap'=>'Google地图',
     'insertvideo'=>'视频', 'help'=>'帮助', 'justifyleft'=>'居左对齐', 'justifyright'=>'居右对齐', 'justifycenter'=>'居中对齐',
     'justifyjustify'=>'两端对齐', 'forecolor'=>'字体颜色', 'backcolor'=>'背景色', 'insertorderedlist'=>'有序列表',
     'insertunorderedlist'=>'无序列表', 'fullscreen'=>'全屏', 'directionalityltr'=>'从左向右输入', 'directionalityrtl'=>'从右向左输入',
     'RowSpacingTop'=>'段前距', 'RowSpacingBottom'=>'段后距','highlightcode'=>'插入代码', 'pagebreak'=>'分页', 'insertframe'=>'插入Iframe', 'imagenone'=>'默认',
     'imageleft'=>'左浮动', 'imageright'=>'右浮动','attachment'=>'附件', 'imagecenter'=>'居中', 'wordimage'=>'图片转存',
     'lineheight'=>'行间距', 'customstyle'=>'自定义标题','autotypeset'=> '自动排版','webapp'=>'百度应用'
 );
 */
class TbUEditor extends CInputWidget
{
	/**
	 * @var TbActiveForm when created via TbActiveForm, this attribute is set to the form that renders the widget
	 * @see TbActionForm->inputRow
	 */
	public $form;

	/**
	 * @var array the UEditor options
	 * @see http://docs.cksource.com/
	 * @since 10/30/12 10:40 AM the Editor used is CKEditor 4 Beta will be updated as final version is done
	 */
	public $editorOptions = array();

	/**
	 * Display editor
	 */
	public function run()
	{

        //$this->editorOptions['UEDITOR_HOME_URL'] = Yii::app()->bootstrap->getAssetsUrl().'js/ueditor/' ;
       // var_dump($this->editorOptions);exit;
		list($name, $id) = $this->resolveNameID();

		$this->registerClientScript($id);

		$this->htmlOptions['id'] = $id;

		// Do we have a model?
		if ($this->hasModel())
		{
			if($this->form)
				$html = $this->form->textArea($this->model, $this->attribute, $this->htmlOptions);
			else
				$html = CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
		} else
		{
			//$html = CHtml::textArea($name, $this->value, $this->htmlOptions);
            $html ='<script type="text/plain" id="'.$id.'" name="'.$name.'">'.$this->value.'</script>';
		}
		echo $html;
	}

	/**
	 * Registers required javascript
	 * @param $id
	 */
	public function registerClientScript($id)
	{
        $homeurl = $this->editorOptions['UEDITOR_HOME_URL'] = Yii::app()->bootstrap->getAssetsUrl().'/js/ueditor/' ;
		Yii::app()->bootstrap->registerAssetJs('ueditor/ueditor.config.js');
		Yii::app()->bootstrap->registerAssetJs('ueditor/ueditor.all.js');
        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile($homeurl.'/themes/iframe.css');
        $cs->registerCssFile($homeurl.'/themes/default/css/ueditor.css');
        /*
        if($this->editorOptions['sourceEditor'])
        {
            Yii::app()->bootstrap->registerAssetJs('ueditor/third-party/SyntaxHighlighter/shCore.js');
            $cs->registerCssFile($homeurl.'third-party/SyntaxHighlighter/shCoreDefault.css');
        }
         */

		$options = !empty($this->editorOptions)? CJavaScript::encode($this->editorOptions) : '{}';
		$options = CJSON::encode($this->editorOptions);
		Yii::app()->clientScript->registerScript(__CLASS__.'#'.$this->getId(), "UE.getEditor( '$id', $options);");
        /*
        if($this->editorOptions['sourceEditor'])
        {
            Yii::app()->clientScript->registerScript(__CLASS__.'#'.$this->getId()."_1", "SyntaxHighlighter.all();");
        }
         */
	}
}
