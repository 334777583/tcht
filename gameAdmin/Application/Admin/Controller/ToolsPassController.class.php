<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-4
// +----------------------------------------------------------------------
// | Describe: 后台道具申请通过控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;
class ToolsPassController extends CommonController{
	public function _initialize(){
		//判断用户权限
		if (!in_array('00500500', session('user_code_arr'))){
			$this->display('Public/noauth');exit;
		}
	}
	
	public function index(){
		$this->assign('serverList',session('server_list'));//服务器列表
		$this->display();
	}
	
	//通过申请
	public function toolsAskPass(){
		$tid = I('tid','0','intval');
		if(empty($tid)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误,刷新后重试'));exit;
		}
		$toolsAsk = M('tools_ask')->find($tid);
		if (!in_array($toolsAsk['t_status'], array(1,3))){
			$this->ajaxReturn(array('status'=>1,'info'=>'该条记录已被审核'));exit;
		}
		//当是定时任务且定时时间大于当前时间插入定时任务表,否则直接发送
		if ($toolsAsk['t_tasktme']>date('Y-m-d H:i:s',NOW_TIME)){
				$timerId = M('timer')->add(array(
				'runtime'=>$toolsAsk['t_tasktme'],
				'task'=>'email',
				'createtime'=>date('Y-m-d H:i:s',NOW_TIME),
				'state'=>0
				));
				if ($timerId){
					M('tools_ask')->save(array('t_id'=>$tid,'t_status'=>-2));
					$this->ajaxReturn(array('status'=>1,'info'=>'定时任务审核通过'));exit;
				}else {
					$this->ajaxReturn(array('status'=>0,'info'=>'插入定时任务表中失败'));exit;
				}
		}
		//不是定时任务开始发放奖励
		$cmd = array();
		$cmd['title'] = $toolsAsk['t_title'];
		$cmd['time'] = NOW_TIME;
		$cmd['content'] = $toolsAsk['t_content'];
		if ($toolsAsk['t_gold']){
			$cmd['money'][] = array('type'=>1,'count'=>$toolsAsk['t_gold']);
		}
		if($toolsAsk['t_copper']){
			$cmd['money'][] = array('type'=>3,'count'=>$toolsAsk['t_copper']);
		}
		//道具信息
		$toolsList = M('tools_list')->where(array('t_ta_id'=>$tid))->select();
		if (! empty ( $toolsList )) {
			foreach ( $toolsList as $tool ) {
				$tem = array (
						'id' => $tool ['t_tid'], // 道具的配置表ID
						"count" => $tool ['t_num'], // 发送道具的数量
						"bind" => $tool ['t_bstatus']  // 绑定状态
								);
				$toolArr [] = $tem;
			}
			$cmd['item'] = $toolArr;
		}
		if ($toolsAsk['t_role_name']=='全服'&&$toolsAsk['t_state']==1){
			$cmd['cmd'] = 'sendmailtoall';
			$cmd['level_min'] = $toolsAsk['t_minlv'];
			$cmd['level_max'] = $toolsAsk['t_maxlv'];
			$cmd['out_date'] = $toolsAsk['t_endtime'];
			$status = D('PhpCmd')->insertCMD($toolsAsk['t_ip'],$cmd,3);
		}elseif($toolsAsk['t_role_name']=='全服'&&$toolsAsk['t_state']!=1){
			$cmd['cmd'] = 'sendmail';
			if ($toolsAsk['t_state']==2){
				$userList = D('PlayerTable')->getAll($toolsAsk['t_ip'],array('bOnline'=>1,'ServerId'=>$toolsAsk['t_ip']));
			}elseif ($toolsAsk['t_state']==3){
				$userList = D('PlayerTable')->getAll($toolsAsk['t_ip'],array('bOnline'=>0,'ServerId'=>$toolsAsk['t_ip']));
			}
			foreach ($userList as $v){
				$cmd['name'] = $v['RoleName'];
				$status = D('PhpCmd')->insertCMD($toolsAsk['t_ip'],$cmd,3);
			}
		}else {
			//处理充值统计
			if ($toolsAsk['t_gold']>0&&$toolsAsk['fakermb']){
				$player = D('PlayerTable')->getByRoleName($toolsAsk['t_ip'],$toolsAsk['t_role_name'],false,'normal');
				$chargeList = array (
						'ServerID' => $toolsAsk['t_ip'],
						'PlayerGUID' => $player[0]['GUID'],
						'RMB' => 0,
						'FakeRMB'=>$toolsAsk['t_gold'],
						'Charge_time' => NOW_TIME
				);
				D('ChargeList')->addData($toolsAsk['t_ip'],$chargeList);
				
				$cm11 = array();
				$cm11['cmd'] = "charge";
				$cm11['GUID'] = $player[0]['GUID'];
				$cm11['time'] = NOW_TIME;
				D('PhpCmd')->insertCMD($toolsAsk['t_ip'],$cm11,5);
			}
			
			$cmd['cmd'] = 'sendmail';
			$cmd['name'] = $toolsAsk['t_role_name'];
			$status = D('PhpCmd')->insertCMD($toolsAsk['t_ip'],$cmd,3);
		}
		if ($status){
			$data = array (
					't_id' => $tid,
					't_uid' => $status,
					't_status' => 4,
					't_result'=>0,
					't_auditor'=>session('username'),
					"t_audittime" => date ( "Y-m-d H:i:s", NOW_TIME ) 
			);
			M('tools_ask')->save($data);
			//操作记录
			action_log(session('username'),$toolsAsk['t_ip'],8,'道具审批通过（'.$tid.'）');
			$this->ajaxReturn(array('status'=>1,'info'=>'审核通过，发送成功'));exit;
		}else {
			$data = array (
					't_id' => $tid,
					't_status' => 3,
					't_result' =>0,
					't_auditor'=>session('username'),
					"t_audittime" => date ( "Y-m-d H:i:s", NOW_TIME )
			);
			M('tools_ask')->save($data);
			$this->ajaxReturn(array('status'=>1,'info'=>'审核通过，发送失败'));exit;
		}
	}
	
	//申请不通过
	public function toolsAskNoPass(){
		$tid = I('tid','0','intval');
		if(empty($tid)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误,刷新后重试'));exit;
		}
		$serverId = M('tools_ask')->where(array('t_id'=>$tid))->getField('t_ip');
		$status = M('tools_ask')->save(array(
				"t_id"=>$tid,
				"t_status" => 2,
				"t_auditor" => session('username'),
				"t_audittime" => date("Y-m-d H:i:s",NOW_TIME),
				't_result'=>2,
				'CmCmdResult'=>'申请不通过'
		));
		if($status){
			//操作记录
			action_log(session('username'),$serverId,8,'道具审批不通过（'.$tid.'）');
			$this->ajaxReturn(array('status'=>1,'info'=>'审核不通过处理成功'));exit;
		}else{
			$this->ajaxReturn(array('status'=>0,'info'=>'审核不通过处理失败'));exit;
		}
	}
	
	//申请列表
	public function getApplyList(){
		$pageSize = I('pageSize','15','intval');
		$curPage = I('curPage','1','intval');
		$startdate = I('startdate');
		$enddate = I('enddate');
		$serverId = I('serverId','0','intval');
		$stype = I('stype');
		if(empty($serverId)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误'));exit;
		}
		
		//获取后端处理结果
		D('PhpCmd')->getCmdDelResult('tools_ask',array('t_status'=>4,'t_result'=>0),'t_id','t_ip','t_uid','t_result');
		
		$where = array();
		$where['t_ip'] = $serverId;
		if (in_array($stype, array(1,2,3,4))){
			$where['t_status'] = $stype;
		}
		
		if (!empty($startdate)&&empty($enddate)){
			$where['t_inserttime'] = array('egt',$startdate);
		}elseif (empty($startdate)&&!empty($enddate)){
			$enddate = date("Y-m-d",strtotime($enddate)+24*60*60);
			$where['t_inserttime'] = array('elt',$enddate);
		}elseif (!empty($startdate)&&!empty($enddate)){
			$enddate = date("Y-m-d",strtotime($enddate)+24*60*60);
			$where['t_inserttime'] = array('between',array($startdate,$enddate));
		}
		$total = M('tools_ask')->where($where)->count();
		$totalPage = ceil($total/$pageSize);
		if ($curPage>$totalPage){
			$curPage = $totalPage;
		}
		$list =  M('tools_ask')->where($where)->order('t_id desc')->page($curPage,$pageSize)->select();
		$page = new \Common\Controller\AjaxPageController($pageSize, $curPage, $total, "getApplyList", "go","page");
		$pageHtml = $page->getPageHtml();
		$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','list'=>$list,'pageHtml'=>$pageHtml,'serverList'=>session('server_list')));exit;
	}
}