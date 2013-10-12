<?php

/**
 * 通用的树型类，可以生成任何树型结�?
 */
class mytree2 {

	/**
	 * 生成树型结构所需要的2维数�?
	 * @var array
	 */
	public $arr = array();

	/**
	 * 生成树型结构所需修饰符号，可以换成图�?
	 * @var array
	 */
    public $icon = array('│','├','└');
	public $nbsp = "&nbsp;";

	/**
	 * @access private
	 */
	public $ret = '';

	/**
	 * 构造函数，初始化类
	 * @param array 2维数组，
	 * array(
	 *      1 => array('id'=>'1','parentid'=>0,'name'=>'一级栏目一'),
	 *      2 => array('id'=>'2','parentid'=>0,'name'=>'一级栏目二'),
	 *      3 => array('id'=>'3','parentid'=>1,'name'=>'二级栏目一'),
	 *      4 => array('id'=>'4','parentid'=>1,'name'=>'二级栏目'),
	 *      5 => array('id'=>'5','parentid'=>2,'name'=>'二级栏目'),
	 *      6 => array('id'=>'6','parentid'=>3,'name'=>'三级栏目'),
	 *      7 => array('id'=>'7','parentid'=>3,'name'=>'三级栏目?)
	 *      )
	 */
	public function init($arr=array()) {
		$this->arr = $arr;
		$this->ret = '';
		return is_array($arr);
	}

	/**
	 * 得到父级数组
	 * @param int
	 * @return array
	 */
	public function get_parent($myid) {
		$newarr = array();
		if (!isset($this->arr[$myid]))
			return false;
		$pid = $this->arr[$myid]['parentid'];
		$pid = $this->arr[$pid]['parentid'];
		if (is_array($this->arr)) {
			foreach ($this->arr as $id => $a) {
				if ($a['parentid'] == $pid)
					$newarr[$id] = $a;
			}
		}
		return $newarr;
	}

	/**
	 * 得到子级数组
	 * @param int
	 * @return array
	 */
	public function get_child($myid) {
		$a = $newarr = array();
		if (is_array($this->arr)) {
			foreach ($this->arr as $id => $a) {
				if ($a['parentid'] == $myid)
					$newarr[$id] = $a;
			}
		}
		return $newarr ? $newarr : false;
	}

	/**
	 * 得到当前位置数组
	 * @param int
	 * @return array
	 */
	public function get_pos($myid, &$newarr) {
		$a = array();
		if (!isset($this->arr[$myid]))
			return false;
		$newarr[] = $this->arr[$myid];
		$pid = $this->arr[$myid]['parentid'];
		if (isset($this->arr[$pid])) {
			$this->get_pos($pid, $newarr);
		}
		if (is_array($newarr)) {
			krsort($newarr);
			foreach ($newarr as $v) {
				$a[$v['id']] = $v;
			}
		}
		return $a;
	}

	/**
	 * 得到树型结构
	 * @param int ID，表示获得这个ID下的所有子�?
	 * @param string 生成树型结构的基本代码，例如�?<option value=\$id \$selected>\$spacer\$name</option>"
	 * @param int 被选中的ID，比如在做树型下拉框的时候需要用�?
	 * @return string
	 */
	public function get_tree($myid, $str, $sid = 0, $adds = '', $str_group = '') {
		$number = 1;
		$child = $this->get_child($myid);
		if (is_array($child)) {
			$total = count($child);
			foreach ($child as $id => $a) {
				$j = $k = '';
				if ($number == $total) {
					$j .= $this->icon[2];
				} else {
					$j .= $this->icon[1];
					$k = $adds ? $this->icon[0] : '';
				}
				$spacer = $adds ? $adds . $j : '';
				$selected = $id == $sid ? 'selected' : '';
				@extract($a);
				$parentid == 0 && $str_group ? eval("\$nstr = \"$str_group\";") : eval("\$nstr = \"$str\";");
				$this->ret .= $nstr;
				$nbsp = $this->nbsp;
				$this->get_tree($id, $str, $sid, $adds . $k . $nbsp, $str_group);
				$number++;
			}
		}
		return $this->ret;
	}

	/**
	 * 同上一方法类似,但允许多�?
	 */
	public function get_tree_multi($myid, $str, $sid = 0, $adds = '') {
		$number = 1;
		$child = $this->get_child($myid);
		if (is_array($child)) {
			$total = count($child);
			foreach ($child as $id => $a) {
				$j = $k = '';
				if ($number == $total) {
					$j .= $this->icon[2];
				} else {
					$j .= $this->icon[1];
					$k = $adds ? $this->icon[0] : '';
				}
				$spacer = $adds ? $adds . $j : '';

				$selected = $this->have($sid, $id) ? 'selected' : '';
				@extract($a);
				eval("\$nstr = \"$str\";");
				$this->ret .= $nstr;
				$this->get_tree_multi($id, $str, $sid, $adds . $k . '&nbsp;');
				$number++;
			}
		}
		return $this->ret;
	}

	/**
	 * @param integer $myid 要查询的ID
	 * @param string $str   第一种HTML代码方式
	 * @param string $str2  第二种HTML代码方式
	 * @param integer $sid  默认选中
	 * @param integer $adds 前缀
	 */
	public function get_tree_category($myid, $str, $str2, $sid = 0, $adds = '') {
		$number = 1;
		$child = $this->get_child($myid);
		if (is_array($child)) {
			$total = count($child);
			foreach ($child as $id => $a) {
				$j = $k = '';
				if ($number == $total) {
					$j .= $this->icon[2];
				} else {
					$j .= $this->icon[1];
					$k = $adds ? $this->icon[0] : '';
				}
				$spacer = $adds ? $adds . $j : '';

				$selected = $this->have($sid, $id) ? 'selected' : '';
				@extract($a);
				if (empty($html_disabled)) {
					eval("\$nstr = \"$str\";");
				} else {
					eval("\$nstr = \"$str2\";");
				}
				$this->ret .= $nstr;
				$this->get_tree_category($id, $str, $str2, $sid, $adds . $k . '&nbsp;');
				$number++;
			}
		}
		return $this->ret;
	}

	/**
	 * 同上一类方法，jquery treeview 风格，可伸缩样式（需要treeview插件支持�?
	 * @param $myid 表示获得这个ID下的所有子�?
	 * @param $effected_id 需要生成treeview目录数的id
	 * @param $str 末级样式
	 * @param $style 目录样式 默认 filetree 可增加其他样式如'filetree treeview-famfamfam'
	 * @param $recursion 递归使用 外部调用时为FALSE
	 */
    function get_treeview_heartadmin($myid,$effected_id='example',$str="<a href='\$url' class='\$current \$mstclass' title='\$name'>\$name</a>", $str2="<a href='\$url' class='\$current nav-top-item \$submenu_class' title='\$name'>\$name</a>" ,$showlevel = 0 ,$style='main-nav ' , $currentlevel = 1,$recursion=FALSE) {
        $child = $this->get_child($myid);
        if(!defined('EFFECTED_INIT')){
           $effected = ' id="'.trim($style).'"';
           define('EFFECTED_INIT', 1);
        } else {
           $effected = '';
        }
		$placeholder = 	'<ul><li><span class="placeholder"></span></li></ul>';
        if(!$recursion) $this->str .='<ul'.$effected.'  class="'.$style.'">';
        $mstclass = $current = $submenu_class = '';
        foreach($child as $id=>$a) {
        	@extract($a);
        	if($currentlevel == 1) $mstclass = 'nav-top-item no-submenu';//1级变换CLASS
        	if($currentlevel == 2) $submenu_class = 'nav-top-item_sub';//2级变换CLASS
			if($showlevel > 0 && $showlevel == $currentlevel && $this->get_child($id)) $folder = 'hasChildren'; //如设置显示层级模式@2011.07.01
        	$floder_status = isset($folder) ? ' class="'.$folder.'"' : '';		

            $this->str .= $recursion ? '<ul><li'.$floder_status.' id=\''.$id.'\'>' : '<li'.$floder_status.' id=\''.$id.'\'>';
            $recursion = FALSE;
            if($this->get_child($id)){
            	eval("\$nstr = \"$str2\";");
            	$this->str .= $nstr;
                if($showlevel == 0 || ($showlevel > 0 && $showlevel > $currentlevel)) {
					$this->get_treeview_heartadmin($id, $effected_id, $str, $str2, $showlevel, $style, $currentlevel+1, TRUE);
				} elseif($showlevel > 0 && $showlevel == $currentlevel) {
					$this->str .= $placeholder;
				}
            } else {
                eval("\$nstr = \"$str\";");
                $this->str .= $nstr;
            }
            $this->str .=$recursion ? '</li></ul>': '</li>';
        }
        if(!$recursion)  $this->str .='</ul>';
        return $this->str;
    }
	
	function get_ztree($nodeid){
		$child = $this->get_child($nodeid);
		if(is_array($child)) {
			foreach($child as $_c=>$_v){
				$this->tree_data[$_c]['id'] = $_v['id'];
				$this->tree_data[$_c]['pId'] = $_v['pid'];
				$this->tree_data[$_c]['name'] = $_v['name'];
				if($_v['url']) $this->tree_data[$_c]['url'] = $_v['url'];
				if($_v['target']) $this->tree_data[$_c]['target'] = $_v['target'];
				if($_v['open']) $this->tree_data[$_c]['open'] = $_v['open'] ? 1 : 0;//记录 treeNode 节点�?展开 / 折叠 状�?
				if($_v['icon']) $this->tree_data[$_c]['icon'] = $_v['icon'];//节点自定义图标的 URL 路径
				if($_v['iconclose']) $this->tree_data[$_c]['iconClose'] = $_v['iconclose'];//父节点自定义折叠时图标的 URL 路径
				if($_v['iconopen']) $this->tree_data[$_c]['iconOpen'] = $_v['iconopen'];//父节点自定义展开时图标的 URL 路径
				if($_v['iconskin']) $this->tree_data[$_c]['iconSkin'] = $_v['iconskin'];//节点自定义图标的 className
				if($_v['isparent']) $this->tree_data[$_c]['isParent'] = $_v['isparent'];//节点自定义图标的 className
				if($this->get_child($_v['id'])) {
					$this->get_ztree($_v['id']);
				}
			}
		}
		$tree_data = json_encode(array_values($this->tree_data));
		$js_data = 	'<SCRIPT type="text/javascript">'."\n\t";
		$js_data .= 	'var setting = {data: {simpleData: {enable: true}}};'."\n\t";
		$js_data .= 'var zNodes ='."\n\t";
		$js_data .= $tree_data;
		$js_data .= ';'."\n\t";
		$js_data .= '$(document).ready(function(){'."\n\t";
		$js_data .= "\t".'$.fn.zTree.init($("#treeDemo"), setting, zNodes);'."\n\t";
		$js_data .= '});'."\n\t";
		$js_data .= '</SCRIPT>';
		return $js_data;
	}

	/**
	 * 获取子栏目json
	 * Enter description here ...
	 * @param unknown_type $myid
	 */
	public function creat_sub_json($myid, $str='') {
		$sub_cats = $this->get_child($myid);
		$n = 0;
		if(is_array($sub_cats)) foreach($sub_cats as $c) {			
			$data[$n]['id'] = $c['categoryid'];
			if($this->get_child($c['categoryid'])) {
				$data[$n]['liclass'] = 'hasChildren';
				$data[$n]['children'] = array(array('text'=>'&nbsp;','classes'=>'placeholder'));
				$data[$n]['classes'] = 'folder';
				$data[$n]['text'] = $c['title'];
			} else {				
				if($str) {
					@extract($c);
					eval("\$data[$n]['text'] = \"$str\";");
				} else {
					$data[$n]['text'] = $c['title'];
				}
			}
			$n++;
		}
		return json_encode($data);		
	}
	private function have($list, $item) {
		return(strpos(',,' . $list . ',', ',' . $item . ','));
	}

}

?>
