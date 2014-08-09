<?php
class login_sign{
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo "not available!";
			exit();
		}else{
			if(!in_array("00501000", $this->user["code"])){
				echo "not available!";
				exit();
			}
		}
	}

	function gmlogin(){
		$flag = 1;
		//$gqid = '26396379';//用户id
		$uid = get_var_value('id') == NULL?1:get_var_value("id");
		$uip = get_var_value('ip') == NUll?1:get_var_value("ip");
		$name = get_var_value('rolename');
		$reason = get_var_value('reason');
		$sql="";
		//$con = mysql_connect('192.168.0.14','root','jcmysql2012!@#',true);//14服
		global $t_conf;
		$sever = 's'.$uip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$rs = $point->field('account')->table('game_user')->where(array('id'=>$uid))->find();
		if(is_array($rs)){
			$gqid = $rs['account'];
		}else{
			$gqid = 1;
		}
		$gsid = 'S0';//服务器id
		$gpt = '49you';//平台
		require_once(LOPATH.'/login/config.inc.php');
		
		if(!isset($GLOBALS['key'][$gpt]) && !isset($GLOBALS['setting'][$gpt][$gsid])){
			echo 'server_key fail';
		exit;
		}
		//$qid = empty($_GET['qid'])?$gqid:$_GET['qid'];//用户id
		$qid = $gqid;
		$sid = empty($_GET['sid'])?$gsid:$_GET['sid'];//服务器id
		$pingtai = empty($_GET['pt'])?$gpt:$_GET['pt'];//平台
		$times = time();
		$server_key = $GLOBALS['key'][$gpt];//生成的唯一key
		$sign = MD5("qid={$qid}&time={$times}&server_id={$sid}&pt={$pingtai}{$server_key}");//登录签名
		
		
		$obj = D('game_base');
		$res = $obj->table('back_login')->insert(array('l_ip'=>$uip,
								  'l_role_id'=>$uid,
								  'l_role_name'=>$name,
								  'l_reason'=>$reason,
								  'l_operaor'=>$this->user['username'],
								  'l_time'=>date('Y-m-d H:i:s'),
								  'l_user_id'=>$gqid
		));
		$url = "qid={$qid}&sid={$sid}&time={$times}&pt={$pingtai}&sign={$sign}&fcm=1";
		echo json_encode($url);
		/*
		http://www.a.com/login/login.php?qid=222&sid=S1&time=1382063661&pt=49you&sign=422f7d465c0fec90b91004c3f9340dbb&fcm=1
		*/
	}
	
	function getRole(){
		$obj = D('game_base');
		
		$time = $obj->table('back_login')->field('l_time')->order('l_time asc')->find();
		$startdate = get_var_value('startdate') == NULL?$time['l_time']:get_var_value('startdate');
		$enddate = get_var_value('enddate')== NULL?date("Y-m-d"):get_var_value('enddate');
		$sip = get_var_value('ip');
		$gmtype = get_var_value('gmtype');
		$edate = $enddate.' 59:59:59';
		if($gmtype == 1){ 		//一键登录
			$where = array(
				'l_time >='=>$startdate,
				'l_time <='=>$edate
			);
			$order = 'l_id';
			$loginlist = $obj->table('back_login')->where($where)->order($order.' desc')->select();
			foreach($loginlist as $key => $value){
				$list[$key]['type'] = '一键登录';
				$list[$key]['role_name'] = $value['l_role_name'];
				$list[$key]['reason'] = $value['l_reason'];
				$list[$key]['operaor'] = $value['l_operaor'];
				$list[$key]['time'] = $value['l_time'];
			}
		}
		if($gmtype == 2){		//发送邮件
			$where = array(
				'e_time >='=>$startdate,
				'e_time <='=>$edate
			);
			$order = 'e_id';
			$emaillist = $obj->table('email')->where($where)->order($order.' desc')->select();
			foreach($emaillist as $key => $value){
				$list[$key]['type'] = '发送邮件';
				$list[$key]['role_name'] = $value['e_name'];
				$list[$key]['reason'] = $value['e_reason'];
				$list[$key]['operaor'] = $value['e_operaor'];
				$list[$key]['time'] = $value['e_time'];
			}
		}
		if($gmtype == 3){			//发送公告
			$where = array(
				'n_date >='=>$startdate,
				'n_date <='=>$edate
			);
			$order = 'n_id';
			$newlist = $obj->table('news')->where($where)->order($order.' desc')->select();
			foreach($newlist as $key => $value){
				$list[$key]['type'] = '发送公告';
				$list[$key]['role_name'] = '即时公告';
				$list[$key]['reason'] = $value['n_content'];
				$list[$key]['operaor'] = $value['n_operaor'];
				$list[$key]['time'] = $value['n_date'];
			}
		}
		if($gmtype == 4){			//冻结
			$where = array(
				'f_inserttime >='=>$startdate,
				'f_inserttime <='=>$edate
			);
			$order = 'f_id';
			$freelist = $obj->table('freeze')->where($where)->order($order.' desc')->select();
			foreach($freelist as $key => $value){
				$list[$key]['type'] = '冻结';
				$list[$key]['role_name'] = $value['f_role_name'];
				$list[$key]['reason'] = $value['f_reason'];
				$list[$key]['operaor'] = $value['f_operaor'];
				$list[$key]['time'] = $value['f_inserttime'];
			}
		}
		if($gmtype == 5){			//下线
			$where = array(
				'f_inserttime >='=>$startdate,
				'f_inserttime <='=>$edate
			);
			$order = 'f_id';
			$linelist = $obj->table('offline')->where($where)->order($order.' desc')->select();
			foreach($linelist as $key => $value){
				$list[$key]['type'] = '踢下线';
				$list[$key]['role_name'] = $value['f_role_name'];
				$list[$key]['reason'] = $value['f_reason'];
				$list[$key]['operaor'] = $value['f_operaor'];
				$list[$key]['time'] = $value['f_inserttime'];
			}
		}
		if($gmtype == 6){			//申请
			$where = array(
				't_inserttime >='=>$startdate,
				't_inserttime <='=>$edate
			);
			$order = 't_id';
			$linelist = $obj->table('tools_ask')->where($where)->order($order.' desc')->select();
			foreach($linelist as $key => $value){
				$list[$key]['type'] = '道具申请';
				$list[$key]['role_name'] = $value['t_role_name'];
				$list[$key]['reason'] = $value['t_reason'];
				$list[$key]['operaor'] = $value['t_operaor'];
				$list[$key]['time'] = $value['t_inserttime'];
			}
		}
		if($gmtype == 7){			//审批
			$where = array(
				't_audittime >='=>$startdate,
				't_audittime <='=>$edate
			);
			$order = 't_id';
			$linelist = $obj->table('tools_ask')->where($where)->order($order.' desc')->select();
			foreach($linelist as $key => $value){
				$list[$key]['type'] = '道具审批';
				$list[$key]['role_name'] = $value['t_role_name'];
				$list[$key]['reason'] = $value['t_reason'];
				$list[$key]['operaor'] = $value['t_auditor'];
				$list[$key]['time'] = $value['t_audittime'];
			}
		}
		if($gmtype == 8){			//新手卡
			$where = array(
				'instime >='=>$startdate,
				'instime <='=>$edate
			);
			$order = 'instime';
			$linelist = $obj->table('new_gift')->where($where)->order($order.' desc')->select();
			foreach($linelist as $key => $value){
				$list[$key]['type'] = '新手卡';
				$list[$key]['role_name'] = $value['player_id'];
				$list[$key]['reason'] = '新手激励';
				$list[$key]['operaor'] = $value['operaor'];
				$list[$key]['time'] = $value['instime'];
			}
		}
		if($gmtype == 9){			//GM设置
			$where = array(
				'o_instime >='=>$startdate,
				'o_instime <='=>$edate,
				'o_type >='=>1,
				'o_type <='=>3
			);
			$order = 'o_id';
			$linelist = $obj->table('operate')->where($where)->order($order.' desc')->select();
			foreach($linelist as $key => $value){
				$list[$key]['type'] = 'GM设置';
				$list[$key]['role_name'] = $value['o_name'];
				if($value['o_type'] ==1){
					$list[$key]['reason'] = '新增用户服务';
				}else if($value['o_type'] == 2){
					$list[$key]['reason'] = '编辑用户服务';
				}else{
					$list[$key]['reason'] = '删除用户服务';
				}
				$list[$key]['operaor'] = $value['o_operate'];
				$list[$key]['time'] = $value['o_instime'];
			}
		}
		if($gmtype == 10){			//开服设置
			$where = array(
				'o_instime >='=>$startdate,
				'o_instime <='=>$edate,
				'o_type >='=>4,
				'o_type <='=>6
			);
			$order = 'o_id';
			$linelist = $obj->table('operate')->where($where)->order($order.' desc')->select();
			foreach($linelist as $key => $value){
				$list[$key]['type'] = '开服设置';
				$list[$key]['role_name'] = $value['o_name'];
				if($value['o_type'] ==4){
					$list[$key]['reason'] = '新增游戏服';
				}else if($value['o_type'] == 5){
					$list[$key]['reason'] = '编辑游戏服';
				}else{
					$list[$key]['reason'] = '删除游戏服';
				}
				$list[$key]['operaor'] = $value['o_operate'];
				$list[$key]['time'] = $value['o_instime'];
			}
		}
		if($gmtype == 11){			//权限管理
			$where = array(
				'o_instime >='=>$startdate,
				'o_instime <='=>$edate,
				'o_type >='=>7,
				'o_type <='=>9
			);
			$order = 'o_id';
			$linelist = $obj->table('operate')->where($where)->order($order.' desc')->select();
			foreach($linelist as $key => $value){
				$list[$key]['type'] = '权限管理';
				$list[$key]['role_name'] = $value['o_name'];
				if($value['o_type'] ==7){
					$list[$key]['reason'] = '新增管理组';
				}else if($value['o_type'] == 8){
					$list[$key]['reason'] = '编辑管理组';
				}else{
					$list[$key]['reason'] = '删除管理组';
				}
				$list[$key]['operaor'] = $value['o_operate'];
				$list[$key]['time'] = $value['o_instime'];
			}
		}
		echo json_encode(array(
			'result'=>$list,
			'startDate'=>$startdate,
			'endDate'=>$enddate
		));
		
	}
}
?>