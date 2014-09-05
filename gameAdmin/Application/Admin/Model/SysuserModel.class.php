<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-7-22
// +----------------------------------------------------------------------
// | Describe: 后台用户模型
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;
class SysuserModel extends Model{
	protected $_scope = array (
			'join'=>array(
				'table'=>array('sysuser'=>'a','groups'=>'b'),
				'field'=> array (
						'u_id','u_name','u_realname','u_phone','u_email','u_createtime','u_updatetime','u_flag',
						'g_name'
				), 
				'where'=>array('a.g_id=b.g_id'),
				'order'=>'u_id asc',
			), 
	);
	
	public function getAll(){
		$userList = $this->scope('join')->select();
		return $userList;
	}
	/**
	 * 用户登录检测
	 * @param string $username	用户名
	 * @param string $password	用户密码
	 * @return number|unknown
	 */
	public function checkUser($username='',$password=''){
		$user = $this->where(array('u_name'=>$username))->find();
		if (empty($user)) {
			return -1;//用户不存在
		}elseif ($user['u_password']!=md5($password.C('USER_PASSWORD_KEY'))){
// 			echo $user['u_password'],'<br/>',md5($password);die;
			return -2;//密码错误
		}
			
		if ($user['u_id']==C('SUPER_ADMIN_ID')){
			$userCode = D('GroupCode')->getAll();
		}else {
			$userCode = D('GroupCode')->getUserCode($user['g_id']);
		}
		if (!$userCode) {
			return -3;//用户所在组没有分配权限
		}
		return $userCode;
	}
}