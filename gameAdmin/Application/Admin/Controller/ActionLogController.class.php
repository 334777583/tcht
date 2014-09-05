<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-21
// +----------------------------------------------------------------------
// | Describe: 后台用户操作记录查询控制器
// +----------------------------------------------------------------------

namespace Admin\Controller;
class ActionLogController extends CommonController{
	public function _initialize(){
		//判断用户权限
		if (!in_array('00501000', session('user_code_arr'))){
			$this->display('Public/noauth');exit;
		}
	}
	
	//获取后台用户操作记录数据
	public function getActionLogList(){
		$serverId = I('serverId',1,'intval');//服务器id
		$curPage = I('curPage',1,'intval');//当前页
		$pageSize = I('pageSize',25,'intval');//页码
		$operate = I('operate');//用户名
		$startdate = I('startdate');//查询开始时间
		$enddate = I('enddate');//查询结束时间
		
		//检测日期格式
		if (check_date($startdate)&&check_date($enddate)){
			if ($startdate>$enddate){
				$this->ajaxReturn(array('status'=>0,'info'=>'开始时间要大于结束时间！'));
			}
		}else {
			$this->ajaxReturn(array('status'=>0,'info'=>'时间格式不对！'));
		}
		
		//后台操作类型
		$typeArr = array(
				'1'=>'用户操作',
				'2'=>'后台登录游戏',
				'3'=>'邮件',
				'4'=>'公告',
				'5'=>'冻结',
				'6'=>'踢下线',
				'7'=>'道具申请',
				'8'=>'道具审批',
				'9'=>'新手卡',
				'10'=>'GM设置',
				'11'=>'开服设置',
				'12'=>'角色设置',
				'13'=>'模块设置' ,
				'14'=>'禁言',
				'15'=>'登录后台'
		);
		
		$where = array();//查询条件
		$where['serverid'] = array('in',array(0,$serverId));
		$where['datetime'] = array('between',array($startdate,date('Y-m-d',strtotime($enddate)+24*60*60)));
		if (!empty($operate)){
			$where['operate'] = $operate;
		}
		$total = M('action_log')->where($where)->count();
		
		$totalPage = ceil($total/$pageSize);
		if ($curPage>$totalPage){
			$curPage = $totalPage;//当前页大于总页数
		}
		
		$list = M('action_log')->where($where)->order('id desc')->page($curPage,$pageSize)->select();
		$page = new \Common\Controller\AjaxPageController($pageSize, $curPage, $total, "getActionLogList", "go","page");
		$pageHtml = $page->getPageHtml();
		$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','list'=>$list,'pageHtml'=>$pageHtml,'typeArr'=>$typeArr));exit;
	}
	
	//后台用户操作记录查询页面
	public function index(){
		$this->assign('serverList',session('server_list'));//服务器列表
		$this->assign('today',date('Y-m-d',NOW_TIME));
		$this->display();
	}
}