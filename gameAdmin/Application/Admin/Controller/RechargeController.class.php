<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-7-22
// +----------------------------------------------------------------------
// | Describe: 充值记录实时查询控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;
class RechargeController extends CommonController{
	private $serverid;	//服务器id，用于控制查询数据库
	private $startDate;	//查询条件：开始时间
	private $endDate;	//查询条件：结束时间
	private $condition;	//查询条件
	private $searchValue;	//查询值
	private $pageSize;	//分页大小
	private $curPage;	//当前页码
	
	public function _initialize(){
		//判断用户权限
		if (!in_array('00100100', session('user_code_arr'))){
			$this->display('Public/noauth');exit;
		}
	}
	
	public function index(){
		$this->assign('serverList',session('server_list'));//服务器列表
		$this->assign('today',date('Y-m-d',NOW_TIME));//默认显示今天的数据
		$this->display();
	}
	
	public function getAllBySid(){
		if (!IS_POST){
			$this->error('非法请求');
		}
		//获取请求数据
		$this->serverid = I('serverid','1','intval');
		$this->startDate = I('startDate');
		$this->endDate = I('endDate');
		$this->condition = I('condition','-1','intval');
		$this->searchValue = I('searchvalue');
		$this->pageSize = I('pageSize','50','intval');
		$this->curPage = I('curPage','1','intval');
			
		if (!(check_date($this->startDate) && check_date($this->endDate))){
			$this->ajaxReturn(array('status'=>0,'info'=>'日期格式错误'));exit;
		}
		if (!$this->serverid){
			$this->ajaxReturn(array('status'=>0,'info'=>'服务器id错误'));exit;
		}
		
		$endDate = date('Y-m-d',strtotime($this->endDate)+24*60*60);//结束时间加一天用户区间查询
		$where['c_time'] = array('between',array($this->startDate,$endDate));
		$where['c_sid'] = $this->serverid;
		$where['c_state'] = array('gt',0);
		$user = array();//用户保存用户角色名信息
		if (!empty($this->searchValue)){
			switch ($this->condition){
				case 0://按角色名查找
					$userList = D('PlayerTable')->getByRoleName($this->serverid,$this->searchValue,true,'normal');
					$playerIdArr = array();
					foreach ($userList as $k=>$v){
						$playerIdArr[] = $v['GUID'];//要查询的用户的角色id一维数组
						$user[$v['GUID']] = $v['RoleName'];//用户信息：角色id=>角色名
					}
					$where['c_pid'] = array('in',$playerIdArr);
					break;
				case 1://按账号查找
					$where['c_openid'] = $this->searchValue;
					$userList = D('PlayerTable')->getOne($this->serverid,array('account'=>$this->searchValue),'join');
					break;
				case 2://按角色id查找
					$where['c_pid'] = $this->searchValue;
					$userList = D('PlayerTable')->getOne($this->serverid,array('GUID'=>$this->searchValue),'normal');
					break;
				case 3://按订单号查找
					$where['token_id'] = $this->searchValue;
					break;
			}
		}
		
		//符合条件的充值记录
		$total = D('ChongZhi')->getTotal($where);
		$totalPage = ceil($total/$this->pageSize);
		if ($this->curPage>$totalPage){
			$this->curPage = $totalPage;
		}
		$list = D('ChongZhi')->getPageData($where,$this->curPage,$this->pageSize);
		
		if (!empty($list)){
			if (!$userList){
				$userList = D('PlayerTable')->getAll($this->serverid);
			}
			//匹配用户角色名
			foreach ($userList as $k=>$v){
				$user[$v['GUID']] = $v['RoleName'];//用户信息：角色id=>角色名
			}
			foreach ($list as $k=>$v){
				if (isset($user[$v['c_pid']])){
					$list[$k]['c_rolename'] = $user[$v['c_pid']];
				}else {
					$list[$k]['c_rolename'] = '';
				}
			}
			$page = new \Common\Controller\AjaxPageController($this->pageSize, $this->curPage, $total, "show", "go","page");
			$pageHtml = $page->getPageHtml();
			$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','result'=>$list,'pageHtml'=>$pageHtml));
		}else {
			$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','result'=>''));
		}
	}
}