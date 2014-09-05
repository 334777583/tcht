<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-5
// +----------------------------------------------------------------------
// | Describe: 后台操作游戏用户控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;
class ForbidController extends CommonController{
	public function _initialize(){
		//判断用户权限
		if (!in_array('00500100', session('user_code_arr'))){
			$this->display('Public/noauth');exit;
		}
	}
	
	//禁言
	public function forbidChat(){
		$this->assign('serverList',session('server_list'));//服务器列表
		$this->display();
	}
	
	//获取禁言操作列表
	public function getForbidChatList(){
		$serverId = I('serverId',0,'intval');
		$pageSize = I('pageSize',10,'intval');
		$curPage = I('curPage',1,'intval');
		if (empty($serverId)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误,刷新后重试'));exit;
		}
		
		//获取后端处理结果
		D('PhpCmd')->getCmdDelResult('stop_speak',array('s_roleStatus'=>0),'s_id','s_ip','s_uid','s_roleStatus');
		
		$where = array('s_ip'=>$serverId);
		$total = M('stop_speak')->where($where)->count();
		$totalPage = ceil($total/$pageSize);
		if ($curPage>$totalPage){
			$curPage = $totalPage;
		}
		$page = new \Common\Controller\AjaxPageController($pageSize, $curPage, $total, "getForbidChatList", "go","page");
		$pageHtml = $page->getPageHtml();
		
		$list = M('stop_speak')->where($where)->order('s_id desc')->page($curPage,$pageSize)->select();
		$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','list'=>$list,'pageHtml'=>$pageHtml,'serverList'=>session('server_list')));
	}
	
	//禁言操作
	public function forbidChatAction(){
		$serverId = I('serverId',0,'intval');
		$rolename = I('rolename');
		$stoptime = I('stoptime');
		$reason = I('reason');
		if (empty($serverId)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误,刷新后重试'));exit;
		}
		$rolenameArr = explode(';',$rolename);//需要发送的用户角色名一维数组
		$info = '';
		foreach ($rolenameArr as $v){
			$userStatus = D('PlayerTable')->getByRoleName($serverId,$v,false,'normal');
			if (!$userStatus){
				$info .= '<'.$v.'用户不存在,处理失败>';
				continue;
// 				$this->ajaxReturn(array('status'=>0,'info'=>$rolename.'用户不存在，请确认后再提交'));exit;
			}
			$forbidTime = NOW_TIME+$stoptime;
			$cmdArr['cmd'] = "forbidchat";
			$cmdArr['name'] = $v;
			if ($stoptime){
				$cmdArr['time'] = $forbidTime;//禁言
				$s_status = 1;
				//操作记录
				action_log(session('username'),$serverId,14,'禁言（'.$v.'）');
			}else {
				$cmdArr['time'] = 0;//解禁
				$s_status = 2;
				//操作记录
				action_log(session('username'),$serverId,14,'解除禁言（'.$v.'）');
			}
			$status = D('PhpCmd')->insertCMD($serverId,$cmdArr);
			if ($status){
				$data = array (
						"s_uid" => $status,
						"s_ip" => $serverId,
						"s_role_id" => $userStatus[0]['GUID'],
						"s_role_name" => $v,
						"s_status" => $s_status,//状态(1:禁言；2：解禁)
						"s_time" => date('Y-m-d H:i:s',$forbidTime),
						"s_secends" => $stoptime,
						"s_reason" => $reason,
						"s_operaor" => session("username"),
						"s_callstatus" => 2,
						"s_roleStatus" => 0,
						"s_inserttime" => date ( "Y-m-d H:i:s" ,NOW_TIME)
				);
				M('stop_speak')->add($data);
			}else {
				$info .= '<'.$v.'处理失败>';
				continue;
// 				$this->ajaxReturn(array('status'=>0,'info'=>'处理失败'));exit;
			}
		}
		if (empty($info)){
			$this->ajaxReturn(array('status'=>1,'info'=>'处理成功'));exit;
		}else {
			$this->ajaxReturn(array('status'=>0,'info'=>$info));exit;
		}
	}
	
	//禁止登录
	public function forbidLogin(){
		$this->assign('serverList',session('server_list'));//服务器列表
		$this->display();
	}
	
	//获取禁止登录操作列表
	public function getForbidLoginList(){
		$serverId = I('serverId',0,'intval');
		$pageSize = I('pageSize',10,'intval');
		$curPage = I('curPage',1,'intval');
		if (empty($serverId)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误,刷新后重试'));exit;
		}
		
		$where = array('f_ip'=>$serverId);
		$total = M('freeze')->where($where)->count();
		$totalPage = ceil($total/$pageSize);
		if ($curPage>$totalPage){
			$curPage = $totalPage;
		}
		$page = new \Common\Controller\AjaxPageController($pageSize, $curPage, $total, "getKickPlayerList", "go","page");
		$pageHtml = $page->getPageHtml();
		
		$list = M('freeze')->where($where)->order('f_id desc')->page($curPage,$pageSize)->select();
		$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','list'=>$list,'pageHtml'=>$pageHtml,'serverList'=>session('server_list')));
	}
	
	public function FobidLoginAction(){
		$serverId = I('serverId',0,'intval');
		$rolename = I('rolename');
		$freezetime = I('freezetime');
		$reason = I('reason');
		$action = I('action');
		if (empty($serverId)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误,刷新后重试'));exit;
		}
		$rolenameArr = explode(';',$rolename);//需要发送的用户角色名一维数组
		$info = '';
		foreach ($rolenameArr as $v){
			$userStatus = D('PlayerTable')->getByRoleName($serverId,$v,false,'join');
			if (!$userStatus){
				$info .= '<'.$v.'用户不存在,处理失败>';
				continue;
			}
			if ($action==1){
				//冻结
				$status = D('ForbidLogin')->addData($serverId,array('Account'=>$userStatus[0]['account'],'forbid_time'=>NOW_TIME+$freezetime));
			}elseif ($action==2){
				//解冻
				$freezetime = 0;
				$status = D('ForbidLogin')->delete($serverId,$userStatus[0]['account']);
			}else {
				$status = 0;
			}
			if ($status){
				$data = array (
						"f_uid" => $status,
						"f_ip" => $serverId,
						"f_role_id" => $userStatus [0] ['GUID'],
						"f_role_name" => $v,
						"f_status" => $action,
						"f_time" => date ( 'Y-m-d H:i:s', $freezetime + NOW_TIME ),
						"f_secends" => $freezetime,
						"f_reason" => $reason,
						"f_operaor" => session ( 'username' ),
						"f_callstatus" => 2,
						"f_roleStatus" => 1,
						"f_inserttime" => date ( "Y-m-d H:i:s", NOW_TIME ) 
				);
				M ( 'freeze' )->add ( $data );
			}else {
				$info .= '<'.$v.'处理失败>';
				continue;
			}
		}
		if (empty($info)){
			if ($action==1){
				//操作记录
				action_log(session('username'),$serverId,5,'冻结（'.$v.'）');
			}elseif ($action==2){
				//操作记录
				action_log(session('username'),$serverId,5,'解冻（'.$v.'）');
			}
			$this->ajaxReturn(array('status'=>1,'info'=>'处理成功'));exit;
		}else {
			$this->ajaxReturn(array('status'=>0,'info'=>$info));exit;
		}
	}
	
	//踢人下线
	public function kickPlayer(){
		$this->assign('serverList',session('server_list'));//服务器列表
		$this->display();
	}
	
	public function kickPlayerAction(){
		$serverId = I('serverId',0,'intval');
		$rolename = I('rolename');
		$reason = I('reason');
		if (empty($serverId)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误,刷新后重试'));exit;
		}
		$rolenameArr = explode(';',$rolename);//需要发送的用户角色名一维数组
		$info = '';
		foreach ($rolenameArr as $v){
			$userStatus = D('PlayerTable')->getByRoleName($serverId,$v,false,'normal');
			if (!$userStatus){
				$info .= '<'.$v.'用户不存在,处理失败>';
				continue;
				// 				$this->ajaxReturn(array('status'=>0,'info'=>$rolename.'用户不存在，请确认后再提交'));exit;
			}
			$cmdArr['cmd'] = "kickplayer";
			$cmdArr['name'] = $v;
			$cmdArr['info'] = $reason;
			$status = D('PhpCmd')->insertCMD($serverId,$cmdArr);
			if ($status){
				$data = array(
						'f_uid' 		=> $status,
						"f_ip"			=> $serverId,
						"f_role_id"		=> $userStatus[0]['GUID'],
						"f_role_name"	=> $v,
						"f_status"		=> 1,
						"f_time" 		=> date("Y-m-d H:i:s",NOW_TIME),
						"f_reason" 		=> $reason,
						"f_operaor" 	=> session("username"),
						"f_callstatus" 	=> 2,
						"f_roleStatus" 	=> 0,
						"f_inserttime" 	=> date("Y-m-d H:i:s",NOW_TIME)
									
				);
				M('offline')->add($data);
				//操作记录
				action_log(session('username'),$serverId,6,'下线（'.$v.'）');
			}else {
				$info .= '<'.$v.'处理失败>';
				continue;
				// 				$this->ajaxReturn(array('status'=>0,'info'=>'处理失败'));exit;
			}
		}
		if (empty($info)){
			$this->ajaxReturn(array('status'=>1,'info'=>'处理成功'));exit;
		}else {
			$this->ajaxReturn(array('status'=>0,'info'=>$info));exit;
		}
	}
	
	//获取踢人下线操作列表
	public function getKickPlayerList(){
		$serverId = I('serverId',0,'intval');
		$pageSize = I('pageSize',10,'intval');
		$curPage = I('curPage',1,'intval');
		if (empty($serverId)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误,刷新后重试'));exit;
		}
		
		//获取后端处理结果
		D('PhpCmd')->getCmdDelResult('offline',array('f_roleStatus'=>0),'f_id','f_ip','f_uid','f_roleStatus');
		
		$where = array('f_ip'=>$serverId);
		$total = M('offline')->where($where)->count();
		$totalPage = ceil($total/$pageSize);
		if ($curPage>$totalPage){
			$curPage = $totalPage;
		}
		$page = new \Common\Controller\AjaxPageController($pageSize, $curPage, $total, "getKickPlayerList", "go","page");
		$pageHtml = $page->getPageHtml();
		
		$list = M('offline')->where($where)->order('f_id desc')->page($curPage,$pageSize)->select();
		$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','list'=>$list,'pageHtml'=>$pageHtml,'serverList'=>session('server_list')));
	}
}