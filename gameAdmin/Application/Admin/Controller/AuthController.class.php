<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-7-22
// +----------------------------------------------------------------------
// | Describe: 后台权限控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;
class AuthController extends CommonController{
	public function _initialize(){
		//判断用户权限
		if (!in_array('00500600', session('user_code_arr'))){
			$this->display('Public/noauth');exit;
		}
	}
	
	//用户列表
	public function userList(){
// 		$userList = D('Sysuser')->getAll();
		$sql = "SELECT * from sysuser LEFT JOIN groups on sysuser.g_id=groups.g_id";
		$userList = M('')->query($sql);
		$this->assign('userList',$userList);
		$this->display();
	}
	
	//获取用户数据，用于修改显示
	public function getUserData(){
		$uid = I('uid','0','intval');
		if (empty($uid)){
			$this->ajaxReturn(array('status'=>0,'info'=>'用户id错误'));exit;
		}
		$userInfo = M('sysuser')->find($uid);
		$groups = M('groups')->select();
		if ($userInfo){
			$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','userInfo'=>$userInfo,'groups'=>$groups));
		}else {
			$this->ajaxReturn(array('status'=>0,'info'=>'用户不存在'));
		}
	}
	
	//添加、修改用户数据
	public function updateUser(){
		$data['u_id'] = I('uid','0','intval');
		$data['u_name'] = I('username');
		$data['u_password'] = md5(I('password').C("USER_PASSWORD_KEY"));
		$data['u_realname'] = I('realname');
		$data['u_phone'] = I('phone');
		$data['u_email'] = I('email');
		$data['g_id'] = I('gid','0','intval');
		$data['u_updatetime'] = date("Y-m-d H:i:s",NOW_TIME);
		if (empty($data['u_name'])){
			$this->ajaxReturn(array('status'=>0,'info'=>'请正确填写用户名'));exit;
		}
		
		if ($data['u_id']){
			$status = M('sysuser')->save($data);
			//操作记录
			action_log(session('username'),0,1,'添加后台用户（'.$data['u_name'].'）');
		}else {
			$data['u_createtime'] = date("Y-m-d H:i:s",NOW_TIME);
			$status = M('sysuser')->add($data);
			//操作记录
			action_log(session('username'),0,1,'修改用户信息（'.$data['u_name'].'）');
		}
		
		if ($status){
			$this->ajaxReturn(array('status'=>1,'info'=>'操作成功'));exit;
		}else {
			$this->ajaxReturn(array('status'=>0,'info'=>'操作失败'));exit;
		}
	}
	
	//获取所有用户组信息
	public function getGroups(){
		$groups = M('groups')->select();
		if ($groups){
			$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','groups'=>$groups));exit;
		}else {
			$this->ajaxReturn(array('status'=>0,'info'=>'请先添加角色'));exit;
		}
	}
	
	//修改用户状态
	public function changeFlag(){
		$id = I('id','0','intval');
		$flag = I('flag','0','intval');
		$data = array (
				'u_flag' => $flag,
				'u_updatetime' => date ( "Y-m-d H:i:s", NOW_TIME ) 
		);
		$status = M('sysuser')-> where(array('u_id'=>$id))->save($data);
		if ($status){
			//操作记录
			action_log(session('username'),0,1,'修改用户状态（'.$id.'）');
			$this->ajaxReturn(array('status'=>1,'info'=>'修改成功'));
		}else {
			$this->ajaxReturn(array('status'=>0,'info'=>'修改失败'));
		}
	}
	
	//删除用户
	public function deleteUser(){
		$uid = I('uid','0','intval');
		$status = M('sysuser')->where(array('u_id'=>$uid))->delete();
		if ($status){
			//操作记录
			action_log(session('username'),0,1,'删除用户（'.$uid.'）');
			$this->ajaxReturn(array('status'=>1,'info'=>'删除成功'));
		}else {
			$this->ajaxReturn(array('status'=>0,'info'=>'删除失败'));
		}
	}
	
	/**
	 * 角色权限相关
	 */
	public function roleAuthList(){
		//所有用户角色
		$groups = M('groups')->select();
		foreach ($groups as $k=>$v){
			//用户角色所有用户
			$groups[$k]['userList'] = M('sysuser')->where(array('u_flag'=>0,'g_id'=>$v['g_id']))->select();
			//用户角色所有权限代码一维数组
			$ruleListTem = M('group_code')->where(array('g_flag'=>0,'g_id'=>$v['g_id']))->select();
			$arrTem = array();
			foreach ($ruleListTem as $v){
				$arrTem[] = $v['cf_code'];
			}
			$groups[$k]['ruleList'] = $arrTem;
		}
		
		//所有模块节点
		$ruleList = M('code_func')->where(array('cf_flag'=>0))->select();
		$ruleList = D('GroupCode')->list_to_tree($ruleList);
		
		$this->assign('groups',$groups);
		$this->assign('ruleList',$ruleList);
		$this->display();
	}
	
	public function saveRule(){
		$gid = I('id','0','intval');
		$codeString = I('codeString');
		$userString = I('userString');
		
		if (empty($gid)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误'));exit;
		}
		M('group_code')->where(array("g_id"=>$gid))->delete();//先删除对应的权限关系
		
		if (!empty($codeString)){
			$data = array();
			$codeList = explode(",",$codeString);
			foreach ( $codeList as $code ) {
				$data [] = array (
						'g_id' => $gid,
						'cf_code' => $code,
						'gc_createtime' => date ( "Y-m-d H:i:s", NOW_TIME )
				);
			}
			M('group_code')->addAll($data);
		}
		
		if (!empty($userString)){
			$userList = explode(",",$userString);
			foreach ($userList as $userId){
				M('sysuser')->where(array("u_id"=>$userId))->save(array("g_id"=>0));
			}
		}
		//操作记录
		action_log(session('username'),0,12,'修改用户组权限（'.$gid.'）');
		$this->ajaxReturn(array('status'=>1,'info'=>'操作成功'));
	}
	
	//添加用户组
	public function addGroup(){
		$group = I('group');
		if($group){
			$status = M('groups')->add(array(
					"g_name" => $group,
					"g_flag" => 0,
					"g_createtime" =>date ( "Y-m-d H:i:s", NOW_TIME ),
					"g_updatetime" => date ( "Y-m-d H:i:s", NOW_TIME )
						
			));
			if ($status) {
				//操作记录
				action_log(session('username'),0,12,'添加用户组（'.$status.'）');
				$this->ajaxReturn(array('status'=>1,'info'=>'添加成功'));
			} else {
				$this->ajaxReturn(array('status'=>0,'info'=>'添加失败'));
			}
		} else {
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误'));
		}
	}
	
	//删除用户组
	public function deleteGroup(){
		$id = I('id','0','intval');
		if (empty($id)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误'));exit;
		}
		M('groups') -> where(array('g_id'=>$id)) -> delete();
		M('sysuser') -> where(array('g_id'=>$id)) -> save(array('g_id'=>0));
		M('group_code') -> where(array('g_id'=>$id)) -> delete();
		//操作记录
		action_log(session('username'),0,12,'删除用户组（'.$id.'）');
		$this->ajaxReturn(array('status'=>1,'info'=>'删除成功'));
	}
	
	//修改用户组状态
	public function changeGroupFlag(){
		$id = I('id','0','intval');
		$flag = I('flag','0','intval');
		$data = array (
				'g_id'=>$id,
				'g_flag' => $flag,
				'g_updatetime' => date ( "Y-m-d H:i:s", NOW_TIME )
		);
		$status = M('groups')->save($data);
		if ($status){
			//操作记录
			action_log(session('username'),0,12,'修改用户组状态（'.$id.'）');
			$this->ajaxReturn(array('status'=>1,'info'=>'修改成功'));
		}else {
			$this->ajaxReturn(array('status'=>0,'info'=>'修改失败'));
		}
	}
	
	/**
	 * 模块节点相关
	 */
	
	//修改模块节点状态
	public function changeCodeFlag(){
		$cid = I('cid','0','intval');
		$flag = I('flag','0','intval');
		$data = array (
				'cf_id'=>$cid,
				'cf_flag' => $flag,
				'cf_updatetime' => date ( "Y-m-d H:i:s", NOW_TIME )
		);
		$status = M('code_func')->save($data);
		if ($status){
			//操作记录
			action_log(session('username'),0,13,'修改模块状态（'.$cid.'）');
			$this->ajaxReturn(array('status'=>1,'info'=>'修改成功'));
		}else {
			$this->ajaxReturn(array('status'=>0,'info'=>'修改失败'));
		}
	}
	
	//添加模块节点
	public function updateCode(){
		$data = array();
		$data['cf_id'] = I('cid','0','intval');
		$data['cf_code'] = I('mcode');
		$data['cf_name'] = I('mname');
		$data['cf_url'] = I('cfUrl');
		$data['cf_pid'] = I('cfPid','0','intval');
		$data['cf_flag'] = I('cfFlag','0','intval');
		$data['cf_updatetime'] = date("Y-m-d H:i:s",NOW_TIME);
		
		if ($data['cf_id']){
			$status = M('code_func')->save($data);
			//操作记录
			action_log(session('username'),0,13,'修改模块（'.$data['cf_name'].'）');
		}else {
			$data['cf_createtime'] = date("Y-m-d H:i:s",NOW_TIME);
			$status = M('code_func')->add($data);
			//操作记录
			action_log(session('username'),0,13,'添加模块（'.$data['cf_name'].'）');
		}
		
		if ($status){
			$this->ajaxReturn(array('status'=>1,'info'=>'操作成功'));
		}else {
			$this->ajaxReturn(array('status'=>0,'info'=>'操作失败'));
		}
	}
	
	//删除模块节点
	public function deleteCode(){
		$cid = I('cid','0','intval');
		$status = M('code_func')->where(array('cf_id'=>$cid))->delete();
		if ($status){
			//操作记录
			action_log(session('username'),0,13,'删除模块（'.$cid.'）');
			$this->ajaxReturn(array('status'=>1,'info'=>'操作成功'));
		}else {
			$this->ajaxReturn(array('status'=>0,'info'=>'操作失败'));
		}
	}
	
	//获取模块节点信息用于修改
	public function getCode(){
		$cid = I('cid','0','intval');
		$code = M('code_func')->find($cid);
		if ($code){
			$this->ajaxReturn(array('status'=>1,'info'=>'操作成功','code'=>$code));
		}else {
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误'));
		}
	}
	
	//模块节点列表
	public function ruleList(){
		$code = M('code_func')->order('cf_code asc')->select();
		$this->assign('code',$code);
		$this->display();
	}
}