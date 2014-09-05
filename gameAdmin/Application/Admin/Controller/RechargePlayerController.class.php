<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-7-22
// +----------------------------------------------------------------------
// | Describe: 玩家充值记录查询统计控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;
class RechargePlayerController extends CommonController{
	private $serverid;	//服务器id，用于控制查询数据库
	private $startDate;	//查询条件：开始时间
	private $endDate;	//查询条件：结束时间
	private $condition;	//查询条件
	private $searchValue;	//查询值
	private $pageSize;	//分页大小
	private $curPage;	//当前页码
	
	public function _initialize(){
		//判断用户权限
		if (!in_array('00100200', session('user_code_arr'))){
			$this->display('Public/noauth');exit;
		}
	}
	
	public function getData(){
		if (!IS_POST){
			$this->error('非法请求');
		}
		//获取请求数据
		$this->serverid = I('serverid','1','intval');
		$this->condition = I('condition','-1','intval');
		$this->searchValue = I('searchvalue');
		$this->pageSize = I('pageSize','50','intval');
		$this->curPage = I('curPage','1','intval');
		
		if (!$this->serverid){
			$this->ajaxReturn(array('status'=>0,'info'=>'服务器id错误'));exit;
		}
		if (empty($this->searchValue)){
			$this->ajaxReturn(array('status'=>0,'info'=>'必须填写搜索值'));exit;
		}
		
		$where['c_state'] = 2;
		
		if (!empty($this->searchValue)){
			switch ($this->condition){
				case 0://按角色名查找
					$userList = D('PlayerTable')->getByRoleName($this->serverid,$this->searchValue,false,'join');
					$where['c_openid'] = $userList[0]['account'];
					break;
				case 1://按账号查找
					$where['c_openid'] = $this->searchValue;
					break;
				case 2://按角色id查找
					$userList = D('PlayerTable')->getOne($this->serverid,array('GUID'=>$this->searchValue),'join');
					$where['c_openid'] = $userList[0]['account'];
					break;
			}
		}
		//获取玩家所有充值记录
		$total = D('ChongZhi')->getTotal($where);
		$totalPage = ceil($total/$this->pageSize);
		if ($this->curPage>$totalPage){
			$this->curPage = $totalPage;
		}
		$list = D('ChongZhi')->getPageData($where,$this->curPage,$this->pageSize);
		
		if (!empty($list)){
			$user = array();//用户保存用户角色名信息
			foreach ($list as $k=>$v){
				if (isset($user[$v['c_pid']])){
					$list[$k]['c_rolename'] = $user[$v['c_pid']];
				}else {
					$user[$v['c_pid']] = D('PlayerTable')->getRoleNameById($v['c_sid'],$v['c_pid']);
					$list[$k]['c_rolename'] = $user[$v['c_pid']];
				}
			}
			$sql = "SELECT c_openid,c_pid,c_sid,sum(c_price*c_num) as gold,sum(c_amt) as amt,
				sum(payamt_coins) as coins,sum(pubacct_payamt_coins) as coins1,count(c_id) as count
					FROM `chongzhi` WHERE ( `c_state` = 2 ) AND
					( `c_openid` = '".$where['c_openid']."' ) GROUP BY c_sid,c_pid order by c_sid asc";
			$all = D('ChongZhi')->getBySql($sql);
			foreach ($all as $k=>$v){
				if (isset($user[$v['c_pid']])){
					$all[$k]['c_rolename'] = $user[$v['c_pid']];
				}else {
					$user[$v['c_pid']] = D('PlayerTable')->getRoleNameById($v['c_sid'],$v['c_pid']);
					$all[$k]['c_rolename'] = $user[$v['c_pid']];
				}
			}
			
			$page = new \Common\Controller\AjaxPageController($this->pageSize, $this->curPage, $total, "show", "go","page");
			$pageHtml = $page->getPageHtml();
			$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','result'=>$list,'all'=>$all,'pageHtml'=>$pageHtml,'serverList'=>session('server_list')));
		}else {
			$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','result'=>''));
		}
	}
	
	public function index(){
		$this->assign('serverList',session('server_list'));//服务器列表
		$this->display();
	}
}