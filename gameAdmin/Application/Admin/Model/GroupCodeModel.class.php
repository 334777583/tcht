<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-7-22
// +----------------------------------------------------------------------
// | Describe: 用户权限模型
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;
class GroupCodeModel extends Model{
	/**
	 * 获取用户权限(菜单)
	 * @param int $groupId	用户所属用户组id
	 * @return boolean|multitype:unknown
	 */
	public function getUserCode($groupId){
		$userCode = $this->field('cf_id,cf_pid,code_func.cf_code,code_func.cf_name,code_func.cf_url')
		->join('code_func on code_func.cf_code=group_code.cf_code')
		->join('groups on group_code.g_id=groups.g_id')
		->where(array('group_code.g_id'=>$groupId,'group_code.g_flag'=>0,'code_func.cf_flag'=>0,'groups.g_flag'=>0))
		->order('code_func.cf_code asc')->select();
		if (empty($userCode)) {
			return false;
		}
		$codeList = $this->list_to_tree($userCode);
		return $codeList;
	}
	
	/**
	 * 获取所有权限,用于开发以及超级用户
	 * @return boolean|Ambigous <\Admin\Model\multitype:unknown, multitype:unknown >
	 */
	public function getAll(){
		$userCode = M('code_func')->order('cf_code asc')->select();
		if (empty($userCode)) {
			return false;
		}
		$codeList = $this->list_to_tree($userCode);
		return $codeList;
	}
	
	/**
	 * 生成菜单数组，并把权限代码存到session
	 * @param unknown $list
	 * @param string $pk
	 * @param string $pid
	 * @param string $child
	 * @param number $root
	 * @return multitype:unknown
	 */
	public function list_to_tree($list, $pk='cf_id', $pid = 'cf_pid', $child = 'child', $root = 0) {
		// 创建Tree
		$tree = array();
		if(is_array($list)) {
			// 创建基于主键的数组引用
			$refer = array();
			$userCodeArr = array();//权限代码一维数组
			foreach ($list as $key => $data) {
				$refer[$data[$pk]] =& $list[$key];
				$userCodeArr[] = $data['cf_code'];
			}
			session('user_code_arr',$userCodeArr);
			foreach ($list as $key => $data) {
				// 判断是否存在parent
				$parentId =  $data[$pid];
				if ($root == $parentId) {
					$tree[] =& $list[$key];
				}else{
					if (isset($refer[$parentId])) {
						$parent =& $refer[$parentId];
						$parent[$child][] =& $list[$key];
					}
				}
			}
		}
		return $tree;
	}
}