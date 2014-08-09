<?php
/**
 * FileName: gmtoolspass.class.php
 * Description:用户管理工具(GM)-道具申请审批
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-4-1 下午4:35:38
 * Version:1.00
 */
class gmtoolspass{
	/**
	 * 用户数据
	 * @var array
	 */
	private $user;
	
	
	/**
	 * gm接口类
	 * @var class
	 */
	private $gm;
	
	/**
	 * 开始时间
	 * @var sting
	 */
	private $startdate;
	
	/**
	 * 结束时间
	 * @var string
	 */	
	private $enddate;
	
	
	/**
	 * 主键ID
	 * @var int
	 */
	private $id;
	
	/**
	 * 服务器IP
	 * @var string
	 */
	private $ip;
	
	/**
	 * 道具列表
	 * @var array
	 */
	private $toolList = array();
	
	/**
	 * 角色列表
	 * @var string
	 */
	private $roleName;
	
	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00500500', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
		
		$this->gm = new autogm();
		$this->id =  get_var_value('id') == NULL? -1 : get_var_value('id');
		$this->ip =  get_var_value('ip') == NULL? -1 : get_var_value('ip');
		$this->toolList = get_var_value('toolList') == NULL? array(): get_var_value('toolList');
		$this->startdate = get_var_value('startdate') == NULL? '': get_var_value('startdate');
		$this->enddate =  get_var_value('enddate') == NULL? '' : get_var_value('enddate');
		$this->pageSize = get_var_value('pageSize') == NULL? 10: get_var_value('pageSize');
		$this->curPage =  get_var_value('curPage') == NULL? 1 : get_var_value('curPage');
		$this->roleName =  get_var_value('roleName') == NULL? '' : get_var_value('roleName');
	}
	
	/**
	* Description:检查道具发送是否处理完成
	* function : check_st
	* Parames : Null
	* Ruturn : Null
	* Author : xiaochengcheng
	* Date:2013年7月29日11:09:36
	*/
	private function check_dj(){
		$obj = D("game_base");
		$arr = $obj ->table('tools_ask') -> field('t_uid')  -> where('t_status=-2') -> select();

		if(!empty($arr) && count($arr) > 0){
		
			//global $gm_db;

			$str = '';
			foreach($arr as $val){
				$str .= $val['t_uid'].',';
			}
			$str = rtrim($str ,',');
			//$point = F($gm_db['db'], $gm_db['ip'], $gm_db['user'], $gm_db['password']);
			
			global $t_conf;
			$sever = 's'.$this->ip;
			$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
			$arr_result = $point -> table('php_cmd') -> where('id in ('.$str.') AND stype=3') -> select();
			$point -> table('php_cmd') -> where('id in ('.$str.') AND stype=3 AND bhandled != 0 and phandled = 0') -> update(array('phandled' => 1));

			$fail = array();
			$succ = '';
			if(!empty($arr_result) && count($arr_result) > 0){
				foreach($arr_result as $val){
					if($val['bhandled'] == 2){
						$fail[]['id'] = $val['id'];
						continue;
					}
					if($val['bhandled'] == 1){
						$succ .= $val['id'].',';
						continue;
					}
					if($val['bhandled'] == 0){
						continue;
					}
				}
				if(!empty($fail) && count($fail) > 0){
					foreach($fail as $val){
						@$obj -> table('tools_ask') -> where("t_uid = ".$val['id']) ->update(array('t_status' => 3, 't_auditor' => $this->user["username"], "t_audittime" => date("Y-m-d H:i:s")));
					}
				}
				if(!empty($succ) && count($succ) > 0){
					$succ = rtrim($succ ,',');
					@$obj -> table('tools_ask') -> where("t_uid in (".$succ.")") ->update(array('t_status' => 4, 't_auditor' => $this->user["username"], "t_audittime" => date("Y-m-d H:i:s")));
				}
			}
		}else{
			return true;
		}
	}
	
	
	/**
	 * 获取道具申请
	 */
	public function getPassTable(){
		$this->check_dj();
	
		$total = 0;							//记录总数
		$stype = get_var_value('stype');	//审核状态
		
		$obj = D('game_base');
		if(empty($this->startdate) && empty($this->enddate)){
			if($stype == 10) {
				$total = $obj->table('tools_ask')->where('t_ip ='.$this->ip .' and t_status != -1')->total();
			} else {
				$total = $obj->table('tools_ask')->where('t_ip ='.$this->ip .' and t_status = '.$stype)->total();
			}
		}else{
			if($this->startdate == $this->enddate){
				if($stype == 10) {
					$total = $obj->table('tools_ask')->where(array('t_inserttime like' => $this->startdate.'%','t_ip' => $this->ip, 't_status !=' => '-1'))->total();
				} else {
					$total = $obj->table('tools_ask')->where(array('t_inserttime like' => $this->startdate.'%','t_ip' => $this->ip, 't_status =' => $stype))->total();
				}
			}else{
				if($stype == 10) {
					$total = $obj->table('tools_ask')->where(array('t_inserttime >= ' => $this->startdate,'t_inserttime < ' => date("Y-m-d",strtotime($this->enddate)+86400), 't_ip' => $this->ip, 't_status !=' => '-1'))->total();
				} else {
					$total = $obj->table('tools_ask')->where(array('t_inserttime >= ' => $this->startdate,'t_inserttime < ' => date("Y-m-d",strtotime($this->enddate)+86400), 't_ip' => $this->ip, 't_status =' => $stype))->total();
				}	
			}
		}
		
		$ipList = autoConfig::getIPS();		//获取服务器信息		
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'pageAjax','go','page');
		$pageHtml = $page->getPageHtml();
		if(empty($this->startdate) && empty($this->enddate)){
			if($stype == 10) {
				$list = $obj->table('tools_ask')->where('t_ip ='.$this->ip .' and t_status != -1')->order('t_id desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
			} else {
				$list = $obj->table('tools_ask')->where('t_ip ='.$this->ip .' and t_status = '.$stype)->order('t_id desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
			}	
		}else{
			if($this->startdate == $this->enddate){
				if($stype == 10) {
					$list = $obj->table('tools_ask')->where(array('t_inserttime like' => $this->startdate.'%', 't_ip' => $this->ip, 't_status !=' => '-1'))->order('t_id desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
				} else {
					$list = $obj->table('tools_ask')->where(array('t_inserttime like' => $this->startdate.'%', 't_ip' => $this->ip, 't_status =' => $stype))->order('t_id desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
				}	
			}else{
				if($stype == 10) {
					$list = $obj->table('tools_ask')->where(array('t_inserttime >= ' => $this->startdate,'t_inserttime < ' => date("Y-m-d",strtotime($this->enddate)+86400), 't_ip' => $this->ip, 't_status !=' => '-1'))->order('t_id desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
				} else {
					$list = $obj->table('tools_ask')->where(array('t_inserttime >= ' => $this->startdate,'t_inserttime < ' => date("Y-m-d",strtotime($this->enddate)+86400), 't_ip' => $this->ip, 't_status =' => $stype))->order('t_id desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
				}
			}
		}
		
		$result = array(
				'list'=>$list,
				'pageHtml'=>$pageHtml,
				'ipList' => $ipList
		);
		echo json_encode($result);
		exit;
	}
	
	/**
	 * 审核通过(1：申请中；2：申请不通过；3：已通过但发送失败；4：已通过但发送成功, -1:申请取消, -2: 正在处理)
	 */
	public function changeStatus(){
		$obj = D("game_base");
		
		$ipList = autoConfig::getConfig($this->ip);	//设置C++服务器对应的服
		
		global $t_conf;
		$sever = 's'.$this->ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$mintime = date('Y-m-d H:i:s',time()-3600);
		$maxtime = date('Y-m-d H:i:s');
		
		$bo = $obj->table('tools_ask')->where(array("t_id"=>$this->id,"t_tasktme >="=>$mintime,"t_tasktme <="=> $maxtime,"t_result"=>0))->find();
		if(isset($bo['t_status']) && $bo['t_status'] != 1 && $bo['t_status'] != 3) {	//如果多人编辑时，该记录已经被修改过了，直接查询返回
			$result = array(
					'id' => $this->id,
					'status' => $bo['t_status'],
					'auditor' => $bo['t_auditor']
				);
			echo json_encode($result);
			exit;
		}
		
		$moneyList = array();			//金钱列表
		$toolList = array();			//道具列表
		$ask = $obj->table('tools_ask')->where('t_id = '. $this->id)->find();
		$list = $obj->table('tools_list')->where('t_ta_id = '.$this->id)->select();
		
		
		if(is_array($list)){
			$toolList = $list;
		}
		if(is_array($ask)){
			$moneyList = $ask;
		}
		//$info['cmd'] = 'sendmail';
		$info['time'] = time();
		if($this->roleName == '全服'){	//全服邮件发送
			$info['cmd'] = 'sendmailtoall';
			// $info['name'] = '全服';
			// $info['type'] = '2';
			$info['level_min']=$moneyList['t_minlv'];
			if($moneyList['t_endtime'] ==0){
				$info['out_date']=0;
			}else{
				$info['out_date']= strtotime("now") + $moneyList['t_endtime']/1000;
			}
			if($moneyList['t_maxlv']== 0 ){
				$info['level_max']= 100;
			}else{
				$info['level_max']=$moneyList['t_maxlv'];
			}
		}else{							//单人发邮件发送
			$info['cmd'] = 'sendmail';
			$info['name'] = $this->roleName;
			//$info['type'] = '1';
			
			
			//处理充值统计
			if ($ask['t_gold']>0&&$ask['fakermb']){
				$guid = $point -> table('player_table')->field("GUID")->where('RoleName="'.$this->roleName.'"') -> find();
				if (!empty($guid['GUID'])){
					$cm11 = array();
					$cm11['cmd'] = "charge";
					$cm11['GUID'] = $guid['GUID'];
					$cm11['time'] = time();
					$cm11 = addslashes(json_encode($cm11));
					$cmd21 = array('GmCmd'=>$cm11,'ServerId'=>$this->ip,'time'=>time(),'bHandled'=>0);
					$point -> table('php_cmd') -> insert($cmd21);
					$chargeList = array (
							'ServerID' => $this->ip,
							'PlayerGUID' => $guid['GUID'],
							'RMB' => 0,
							'FakeRMB'=>$ask['t_gold'],
							'Charge_time' => time()
					);
					$point -> table('charge_list') -> insert($chargeList);
				}
			}
			
		}

		$info['title'] = $moneyList['t_title'];
		$info['content'] = htmlspecialchars_decode($moneyList['t_content']);

		$monArr = array();				//金钱类型：数量   1 铜钱, 2 绑定铜钱, 3 元宝, 4 绑定元宝 
		$toolArr = array();				//道具列表
		if(!empty($moneyList)){
			// if($moneyList['t_copper'] > 0){
				// $t_copper = $moneyList['t_copper'];
			// }else{
				// $t_copper = 0;
			// }
			// if($moneyList['t_gold'] >0){
				// $t_gold = $moneyList['t_gold'];
			// }else{
				// $t_gold = 0;
			// }
			
			// $copper_arr = array(
				// 'type' => '1', 
				// 'count' => $t_copper
			// );
			
			// $gold_arr = array(
				// 'type' => '3', 
				// 'count' => $t_gold
			// );
			
			if($moneyList['t_copper'] > 0){
				$copper_arr = array(
					'type' => '1', 
					'count' => $moneyList['t_copper']
				);
			}
			if($moneyList['t_gold'] >0){
				$gold_arr = array(
					'type' => '3', 
					'count' => $moneyList['t_gold']
				);
			}
			
			
			
			
			if(!empty($copper_arr)){
				$monArr[] = $copper_arr;
			}
			if(!empty($gold_arr)){
				$monArr[] = $gold_arr;
			}

		}
		
		if(!empty($toolList)){
			foreach($toolList as $tool){
				$itemArr = array(
					'id' =>	$tool['t_tid'],					//道具的配置表ID
					"count"	=> $tool['t_num'],				//发送道具的数量
					"bind" =>  $tool['t_bstatus']						//绑定状态	
				);
				$toolArr[] = $itemArr;
			}
		}
		
		if(!empty($toolArr)) {
			$info['item'] = $toolArr;
		}
		
		if(!empty($monArr )) {
			$info['money'] = $monArr;
		}
		
		// print_r(json_encode($info));
		// exit;
		
		if($moneyList['t_state'] == 1 ){
			$uid = $point -> table('php_cmd') -> insert(array('GmCmd'=>addslashes(myjson($info)),'ServerId'=>$ipList['2'],'stype'=>3,'time'=>time(),'bHandled'=>0));
		}else{
			if($moneyList['t_state'] == 2){
				$rolename = $point ->table('player_table')->where('bonline = 1')->select();
			}else if($moneyList['t_state'] == 3){
				$rolename = $point ->table('player_table')->where('bonline = 0')->select();
			}
			$info['cmd'] = 'sendmail';
			foreach($rolename as $key => $value){
				$info['name'] = $value['rolename'];
				$uid = $point -> table('php_cmd') -> insert(array('GmCmd'=>addslashes(myjson($info)),'ServerId'=>$ipList['2'],'stype'=>3,'time'=>time(),'bHandled'=>0));
				@$obj -> table('timer') -> where(array("runtime >= " =>$mintime,"runtime <=" => $maxtime,"t_result"=>0)) -> update(array('state' => 1));
			}
		}
		if(!empty($uid)) {
			@$obj -> table('tools_ask') -> where("t_id = ".$this->id) ->update(array('t_uid' => $uid,'t_status' => 4,'t_auditor' => $this->user["username"],'t_result'=>1));
			$result = array(
					'id' => $this->id,
					'status' => '4',
					'auditor' => $this->user["username"]
				);
			echo json_encode($result);
			exit;
		} else {
			@$obj -> table('tools_ask') -> where("t_id = ".$this->id) ->update(array('t_uid' => $uid,'t_status' => -2,'t_auditor' => $this->user["username"],'t_result'=>1));
			$result = array(
					'id' => $this->id,
					'status' => '-2',
					'auditor' => $this->user["username"]
			);
			echo json_encode("error");
			exit;
		}
	}

	
	/**
	 * 审核不通过(1：申请中；2：申请不通过；3：已通过但发送失败；4：已通过但发送成功)
	 */
	public function nopass(){
		$obj = D('game_base');
		if($this->id == 0){
			echo json_encode("error");
			exit;
		}
		
		$state = $obj->table('tools_ask')->where(array("t_id"=>$this->id))->update(array(
				"t_status" => 2,
				"t_auditor" => $this->user["username"],
				"t_audittime" => date("Y-m-d H:i:s")
		));
		if($state !=  false){
			$result = array(
					'id' => $this->id,
					'status' => 2,
					'auditor' => $this->user["username"]
				);
			echo json_encode($result);
			exit;
		}else{
			echo json_encode("error");
			exit;
		}
	}
}