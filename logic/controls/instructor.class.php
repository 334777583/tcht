<?php
/**
   * FileName		: dailyreport.class.php
   * Description	: 游戏指导员设置
   * Author	    : zwy
   * Date			: 2014-9-26
   * Version		: 1.00
   */
class instructor{
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo "not available!";
			exit();
		}else{
			if(!in_array("00502100", $this->user["code"])){
				echo "not available!";
				exit();
			}
		}
	}
	
	/**
	 * 添加指导员
	 */
	public function add(){
		$ip = get_var_value('ip');
		$rolename = get_var_value('rolename');
		
		if (empty($rolename)){
			echo json_encode(array('status'=>0,'info'=>'角色名不能为空！'));exit;
		}
		
		if (empty($ip)){
			echo json_encode(array('status'=>0,'info'=>'请选择服务器！'));exit;
		}
		
		
		global $t_conf;
		$sever = 's'.$ip;
		$Server = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$sql = "select account,guid,rolename,serverid from player_table left join game_user on 
		player_table.accountid=game_user.id  where rolename='$rolename' and serverid=$ip";
		$player = $Server->fquery($sql);
		if (empty($player)){
			echo json_encode(array('status'=>0,'info'=>'角色不存在！'));exit;
		}

		$obj = D('game_base');
		$or = $obj->table('instructor')->where(array('playerid'=>$player[0]['guid'],'status'=>1))->select();
		if (!empty($or)){
			echo json_encode(array('status'=>0,'info'=>'该玩家已经是指导员,请不要重复添加！'));exit;
		}
		
// 		{"cmd":"addguider","addtype":"1","guid":"0000","time":"8"}
// 		addtype : 0删除指导员，1增加指导员
		$cmd = array();
		$cmd['cmd'] = 'addguider';
		$cmd['addtype'] = 1;
		$cmd['guid'] = $player[0]['guid'];
		$cmd['time'] = time();
		$data = array (
				'GmCmd' => addslashes(json_encode($cmd)),
				'ServerId' => $ip,
				'time' => time (),
				'bHandled' => 0 
		);
		$status = $Server->table('php_cmd')->insert($data);
		if (empty($status)){
			echo json_encode(array('status'=>0,'info'=>'指令插入失败！'));exit;
		}
		
		$data1 = array(
				'account'=>$player[0]['account'],
				'playerid'=>$player[0]['guid'],
				'rolename'=>$player[0]['rolename'],
				'serverid'=>$ip,
				'cmdid'=>$status,
				'insert_time'=>date('Y-m-d H:i:s'),
				'update_time'=>date('Y-m-d H:i:s')
		);
		
		$status1 = $obj->table('instructor')->insert($data1);
		echo json_encode(array('status'=>1,'info'=>'添加成功！'));exit;
	}
	
	public function update(){
		$ip = get_var_value('ip');
		$guid = get_var_value('guid');
		$addtype = get_var_value('addtype');
		
		if (empty($guid)||empty($ip)){
			echo json_encode(array('status'=>0,'info'=>'参数错误！'));exit;
		}
		
		// 		{"cmd":"addguider","addtype":"1","guid":"0000","time":"8"}
		// 		addtype : 0删除指导员，1增加指导员
		$cmd = array();
		$cmd['cmd'] = 'addguider';
		$cmd['addtype'] = $addtype;
		$cmd['guid'] = $guid;
		$cmd['time'] = time();
		$data = array (
				'GmCmd' => addslashes(json_encode($cmd)),
				'ServerId' => $ip,
				'time' => time (),
				'bHandled' => 0 
		);
		global $t_conf;
		$sever = 's'.$ip;
		$Server = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$status = $Server->table('php_cmd')->insert($data);
		if (empty($status)){
			echo json_encode(array('status'=>0,'info'=>'指令插入失败！'));exit;
		}
		$data1 = array (
				'cmdid'=>$status,
				'del_status'=>0,
				'CmCmdResult'=>'',
				'update_time' => date ( 'Y-m-d H:i:s' ) 
		);
		if ($addtype==0){
			$data1['status'] = -1;
		}
		$where = array('serverid'=>$ip,'playerid'=>$guid);
		$status1 = D ( 'game_base' )->table ( 'instructor' )->where($where)->update( $data1 );
		echo json_encode ( array (
				'status' => 1,
				'info' => '处理成功！' 
		) );
		exit ();
	}
	
	/**
	 * 查询指导员
	 */
	public function getData(){
		$ip = get_var_value('ip');
		$curPage = get_var_value('curPage');
		$pageSize = get_var_value('pageSize');
		
		$this->checkCMD();
		
		$where0 = "(status=-1 and del_status=2)";//撤消失败显示用来重复发送撤消命令
		$where1 = "status =1";
		if ($ip!=0){
			$where1 .= " and serverid=$ip";
		}
		
		$where = $where0." or ($where1)";
		
		$total = 0;		//记录总数
		$obj = D('game_base');
		$total = $obj->table('instructor')->where($where)->total();
		if (empty($total)){
			echo json_encode(array('status'=>0,'info'=>'没有数据'));exit();
		}
		
		$ipList = autoConfig::getIPS();		//获取服务器信息
		
		$page = new autoAjaxPage($pageSize,$curPage,$total,'show','go','page');
		$pageHtml = $page->getPageHtml();
		$list = $obj->table('instructor')->where($where)->order('id desc')->limit(intval(($curPage-1)*$pageSize),intval($pageSize))->select();
		
		global $t_conf;
		
		$data = array();
		$sex_type = array('0' => '无性别', '1' => '男', '2' => '女');
		$career_type = array('0' => '无职业', '1' => '猛将', '2' => '神弓', '3' => '谋士');
		
		foreach($list as $k => $v){
			$data[$k] = $v;
			$data[$k]['servername'] = $ipList[$v['serverid']];
			
			$sever = 's'.$v['serverid'];
			$Server = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
			$player = $Server->fquery("select level,sex,carrer,logintime from player_table where guid=".$v['playerid']);
			
			$data[$k]['level'] = $player[0]['level'];
			$data[$k]['sex'] = $sex_type[$player[0]['sex']];
			$data[$k]['carrer'] = $career_type[$player[0]['carrer']];
			$data[$k]['nearlogin'] = date('Y-m-d H:i:s',$player[0]['logintime']);
			
			$objTem = D("game".$v['serverid']);
			
			$lastIp = '';	//最近登录IP
			$sumSec = 0	;	//总在线时长
			$lt = $objTem->table('detail_login') -> where('d_userid ='.$v['playerid']) -> order('d_date desc') -> find();
			if($lt != '') {
				$ss = $objTem -> table('online_sec') -> field('sum(o_second) as sum') ->where('o_userid ='.$v['playerid']) -> find();
					
				if (isset($lt['d_ip'])) {
					$lastIp = $lt['d_ip'];
					$o = new autoipsearchdat();
					$area = $o->findIp($lastIp);
					if($area) {
						$lastIp .= '(' . $area . ')';
					}
				}
					
				if (isset($ss['sum'])) {
					$sumSec = $ss['sum'];
				}
			}
			
			$data[$k]['lastip'] = $lastIp;
			$data[$k]['sumtime'] = $sumSec;
			
		}
		$result = array(
				'status'=>1,
				'list'=>$data,
				'pageHtml'=>$pageHtml
		);

		echo json_encode($result);exit;
	}
	
	private function checkCMD(){
		$list = D('game_base')->table('instructor')->where(array('del_status'=>0))->select();
		global $t_conf;
		if (!empty($list)){
			foreach ($list as $k=>$v){
				$sever = 's'.$v['serverid'];
				$Server = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
				$cmd = $Server->table("php_cmd")->where("bhandled !=0 and id=".$v['cmdid'])->find();
				if (!empty($cmd)){
					$data = array('del_status'=>$cmd['bhandled'],'CmCmdResult'=>$cmd['cmcmdresult']);
					D('game_base')->table("instructor")->where(array('id'=>$v['id']))->update($data);
				}
			}
		}
	}
}