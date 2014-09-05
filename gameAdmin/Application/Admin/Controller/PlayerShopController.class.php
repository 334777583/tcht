<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆ All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-30
// +----------------------------------------------------------------------
// | Describe: 玩家商城消费查询控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;

class PlayerShopController extends CommonController {
	public function _initialize() {
		// 判断用户权限
		if (! in_array ( '00200100', session ( 'user_code_arr' ) )) {
			$this->display ( 'Public/noauth' );
			exit ();
		}
	}
	
	//查询静态页面
	public function index() {
		$this->assign ( 'serverList', session ( 'server_list' ) ); // 服务器列表
		$this->assign ( 'today', date ( 'Y-m-d', strtotime("-1 day") ) ); // 默认显示今天的数据
		$this->display ();
	}
	
	//获取玩家消费数据
	public function getHistoryData(){
		$serverId = I("serverId",1,'intval');//服务器
		$startdate = I('startdate');//查询开始时间
		$enddate = I('enddate');//查询结束时间
		$moneyType = I('moneyType',1,'intval');//货币类型
		$type = I('type',1,'intval');//查询类型 1为角色id,2为角色名
		$codeValue = I('codeValue');//查询值
		$curPage = I('curPage',1,'intval');//当前页码
		$pageSize = I('pageSize',15,'intval');//每页显示数量
		
		$startdate = $startdate.' 00:00:00';
		$enddate = $enddate.' 23:59:59';
		
		if (empty($serverId)||empty($codeValue)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误'));exit;
		}
		
		if ($type==1){
			$playerInfo = D('PlayerTable')->getById($serverId,$codeValue,'join');
			$guid = $codeValue;
		}elseif ($type==2){
			$playerInfoTem = D('PlayerTable')->getByRoleName($serverId,$codeValue,false,'join');
			$playerInfo = $playerInfoTem[0];
			$guid = $playerInfo['GUID'];
		}
		
		if (empty($playerInfo)){
			$this->ajaxReturn(array('status'=>0,'info'=>'用户不存在'));exit;
		}
		
		$where = array();
		$where['i_playid'] = $guid;
		$where['i_type'] = $moneyType;
		$where['i_date'] = array('between',array($startdate,$enddate));
		$tableName = 'item'.$guid % 15 ;
		
		$total = D('GetObj')->getObj($serverId)->table($tableName)->where($where)->count();
		
		$totalPage = ceil($total/$pageSize);
		if ($curPage>$totalPage){
			$curPage = $totalPage;//当前页大于总页数
		}
		$payData = D('GetObj')->getObj($serverId)->table($tableName)
		->join("tools_detail on tools_detail.t_code=$tableName.i_shopid")
		->where($where)->page($curPage,$pageSize)->select();
		
		$page = new \Common\Controller\AjaxPageController($pageSize, $curPage, $total, "getHistoryData", "go","page");
		$pageHtml = $page->getPageHtml();
		
		$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','list'=>$payData,'playerInfo'=>$playerInfo,'pageHtml'=>$pageHtml));exit;
	}
	
	//获取玩家消费数据
	public function getCurData(){
		$serverId = I("serverId",1,'intval');//服务器
		$moneyType = I('moneyType',1,'intval');//货币类型
		$type = I('type',1,'intval');//查询类型 1为角色id,2为角色名
		$codeValue = I('codeValue');//查询值
	
		if (empty($serverId)||empty($codeValue)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误'));exit;
		}
	
		if ($type==1){
			$playerInfo = D('PlayerTable')->getById($serverId,$codeValue,'join');
			$guid = $codeValue;
		}elseif ($type==2){
			$playerInfoTem = D('PlayerTable')->getByRoleName($serverId,$codeValue,false,'join');
			$playerInfo = $playerInfoTem[0];
			$guid = $playerInfo['GUID'];
		}
	
		if (empty($playerInfo)){
			$this->ajaxReturn(array('status'=>0,'info'=>'用户不存在'));exit;
		}
	
		if (APP_DEBUG){
			$filePath = C('GAME_LOG_PATH') . 'log-type-6.log';
		}else {
			$dbInfo = M ( 'gamedb' )->where (array('g_id'=>$serverId) )->find ();
			$path = C('GAME_LOG_PATH') . $dbInfo ['g_ip'] . '/' . date('Y-m-d',NOW_TIME); // 日志文件所在目录路径
			$filePath = $path . '/log-type-6.log';
		}
		if (!is_file($filePath)){
			$this->ajaxReturn(array('status'=>0,'info'=>'文件不存在!'.$filePath));exit;
		}
		//{'playid':'640002968','commodityid':'430105','price':'5','price_type':'3','num':'1','activity':'1' ,'time':'1406585626'}
		$fp = fopen ( $filePath, "r" ); // 读取日志文件
		$data = array ();//当前玩家的记录
		$goodsIds = array();//物品id一维数组，作为条件查询物品名称
		while ( ! feof ( $fp ) ) {
			$line = fgets ( $fp, 2048 );
			if (! empty ( $line )) {
				$INFO = trim ( substr ( $line, 21 ) );
				$INFO = str_replace ( "'", '"', $INFO );
				$arr = json_decode ( $INFO, true );
				if (is_array ( $arr )&&$arr['playid']==$guid) {
					$data[] = $arr;
					$goodsIds[] = $arr['commodityid'];
				}
			}
		}
		fclose ( $fp ); // 关闭文件指针
		
		//物品信息
		$goods = D('GetObj')->getObj($serverId)->table("tools_detail")->where(array('t_code'=>array('in',$goodsIds)))->select();
		$goodsArr = array();
		foreach ($goods as $k=>$v){
			$goodsArr[$v['t_code']] = $v['t_name'];
		}
		
		$list = array();
		foreach ($data as $k=>$v){
			$list[$k]['i_playid'] = $v['playid'];
			$list[$k]['i_dtype'] = $v['activity'];
			$list[$k]['i_shopid'] = $v['commodityid'];
			$list[$k]['t_name'] = $goodsArr[$v['commodityid']];
			$list[$k]['i_price'] = $v['price'];
			$list[$k]['i_num'] = $v['num'];
			$list[$k]['i_date'] = date('Y-m-d H:i:s',$v['time']);
		}
		
		$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','list'=>$list,'playerInfo'=>$playerInfo));exit;
	}
	
	//根据角色名查找用户角色id
	public function getUserPlayId(){
		$serverId = I('serverId',1,'intval');
		$roleName = I('roleName');
		if (empty($roleName)||empty($serverId)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误'));exit;
		}
		
		$list = D('PlayerTable')->getByRoleName($serverId,$roleName,true,'normal');
		if (count($list)>20){
			$list = array_slice($list,0,20);
		}
		$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','list'=>$list));exit;
	}
}