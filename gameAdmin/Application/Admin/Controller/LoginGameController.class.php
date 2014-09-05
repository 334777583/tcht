<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆ All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-22
// +----------------------------------------------------------------------
// | Describe: 后台用户登录游戏用户控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;
class LoginGameController extends CommonController {
	public function _initialize() {
		// 判断用户权限
		if (! in_array ( '00501800', session ( 'user_code_arr' ) )) {
			$this->display ( 'Public/noauth' );
			exit ();
		}
	}
	
	// 后台服务器列表查询页面
	public function index() {
		$this->assign ( 'serverList', session('server_list') ); // 服务器列表
		$this->display ();
	}
	
	//登录跳转
	public function loginJump(){
		$parameter = "adminisornot=1&seqid=0c182b4483356f3be02916fc1003f11a";
		$parameter.= "&openkey=E3BFBE9CD664B4D9CC791B8BBB6E76AD&pf=qzone";
		$parameter.= "&pfkey=8b84f22eb855813b1c74011779e1aaa5";
		$parameter.= "&serverid=".($_GET['sid']+1);
		$parameter.= "&openid=".$_GET['openid'];
		header("Location:".C('GAME_LOGIN_ENTRANCE')."?$parameter");
	}
	
	//根据角色名查找用户信息
	public function getUserList(){
		$serverId = I('serverId',1,'intval');
		$rolename = I('rolename');
		$pageSize = I('pageSize',25,'intval');
		$curPage = I('curPage',1,'intval');
		
		$where = array();
		if (!empty($rolename)){
			$where['RoleName'] = $rolename;
		}
		
		$total = D('PlayerTable')->getTotal($serverId,$where);
		
		$totalPage = ceil($total/$pageSize);
		if ($curPage>$totalPage){
			$curPage = $totalPage;//当前页大于总页数
		}
		
		$list = D('PlayerTable')->getPageData($serverId,$where,$curPage,$pageSize,'join','LoginTime desc');
		foreach ($list as $k=>$v){
			$list[$k]['logintime'] = date('Y-m-d H:i:s',$v['LoginTime']);
			$list[$k]['loginouttime'] = date('Y-m-d H:i:s',$v['LogoutTime']);
			if ($v['bOnline']){
				$list[$k]['status'] = '<span style="color:red;">在线</span>';
			}else {
				$list[$k]['status'] = '离线';
			}
		}
		$page = new \Common\Controller\AjaxPageController($pageSize, $curPage, $total, "show", "go","page");
		$pageHtml = $page->getPageHtml();
		$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','list'=>$list,'pageHtml'=>$pageHtml));exit;
	}
}