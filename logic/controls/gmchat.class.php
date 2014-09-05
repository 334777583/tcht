<?php
/**
   * FileName		: gmchat.class.php
   * Description	: 充值等级查询
   * Author	    : zwy
   * Date			: 2014-6-6
   * Version		: 1.00
   */
class gmchat{
	/**
	 * 登录用户信息
	 */
	private $user;

	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00501900', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	/**
	 * 获取数据
	 */
	public function getChatContents(){
		$ip = get_var_value('ip');
		$chatnum = get_var_value('chatnum');
		$chatType = get_var_value('chatType');
		$date = get_var_value('startdate');
		if ($date>date('Y-m-d')){
			echo 1;exit;
		}
		
		$Gamebase = D('game_base');
		$g_folder = $Gamebase->field('g_ip')->table('gamedb')->where('g_id = '.$ip)->find();
		$path = LPATH . $g_folder['g_ip'] . '/' . $date . '/';	//日志文件所在目录路径
 		$chatFilePath = $path.'/log-type-18.log';
		//$chatFilePath = LPATH.'192.168.0.64/2014-07-29/log-type-18.log';
		if (!is_file($chatFilePath)){
			echo '1';exit;//文件不存在退出
		}
		
		$channel = array('私聊','队伍','帮派','世界','喇叭','系统','中央屏幕','好友聊天','陌生人消息','场景聊天','国家聊天','传闻(强化)','组队招募广播');
		
		$fp = fopen($chatFilePath, "r");
		$line = $chatnum;
		$pos = -2;
		$t = " ";
		$data = array();
		$arr = array();
		while ($line > 0) {
			while ($t != "\n") {
				$a = fseek($fp, $pos, SEEK_END);
				$t = fgetc($fp);
				$pos --;
				if ($a==-1){
					break;
				}
			}
			if ($a==-1){
				break;
			}
			$t = " ";
			$linedata = fgets ( $fp, 2048 );
			if (! empty ( $linedata )) {
				$INFO = trim ( substr ( $linedata, 21 ) );
				$INFO = strip_tags($INFO);
				$INFO = str_replace ( "'", '"', $INFO );
				$arr = json_decode ( $INFO, true );
// 				$tem[] = $INFO;
				if (is_array($arr)&&in_array($arr['chat_type'], $chatType)){
					if ($arr['chat_type']==2){
// 						$match1 = "/[很]{1}[高][兴][认][识][你][，][以][后][去][打][B][O][S][S][下][多][人][副][本][记][得][叫][上][我][哦][！][~]/";
						$match1 = "/(很高兴认识你，以后去打BOSS下多人副本记得叫上我哦！~){1}/";
						$match2 = "/(哟，新人啊，以后由我照着你了。){1}/";
						$match3 = "/(我正在想要送你鲜花 ，你先给爷笑一个。){1}/";
// 						echo json_encode(preg_match_all($match1,"很高兴认识你，以后去打BOSS下多人副本记得叫上我哦！~"));exit;
						if (preg_match($match1,$arr['content'])){
							continue;
						}elseif (preg_match($match2,$arr['content'])){
							continue;
						}elseif (preg_match($match3,$arr['content'])){
							continue;
						}
					}
					$data[] = $arr;
					$line --;
				}
			}
		}
		fclose ($fp);
// 		echo json_encode($tem);exit;
		$data = array_reverse($data);
		
// 		$filesize = filesize($chatFilePath);
// 		$start = $filesize-1024*2;
// 		if ($start<0){
// 			$start = 0;
// 		}
// 		$handle = fopen ( $chatFilePath, "r" ); // 读取日志文件
// 		fseek($handle, $start,SEEK_SET);
// 		$data = array();
// 		while ( ! feof ( $handle ) ) {
// 			$line = fgets ( $handle, 2048 );
// 			if (! empty ( $line )) {
// 				$INFO = trim ( substr ( $line, 21 ) );
// 				$INFO = str_replace ( "'", '"', $INFO );
// 				$arr = json_decode ( $INFO, true );
// 				if (is_array($arr)){
// 					$data[] = $arr;
// 				}
// 			}
// 		}
// 		fclose ( $handle ); // 关闭文件指针
		$list = array();
		foreach ($data as $k=>$v){
			$list[$k] = $v;
			$list[$k]['channel'] = $channel[$v['chat_type']];
			$list[$k]['date'] = date('Y-m-d H:i:s',$v['time']);
			
			//查看玩家冻结状态
			$status = $Gamebase->table('freeze')->where(array('f_role_id'=>$v['playid']))->order('f_id desc')->find();
			if ($status&&$status['f_status'] ==1){
				$list[$k]['status'] = 1;
			}else {
				$list[$k]['status'] = 0;
			}
		}
		echo json_encode($list);exit;
	}
	
	//冻结并下线
	public function killPlayer(){
		$ip = get_var_value('ip');
		$roleid = get_var_value('roleid');
		$rolename = get_var_value('rolename');
		
		global $t_conf;
		$sever = 's'.$ip;
		$point = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		$user = $point->fquery("SELECT game_user.account from player_table LEFT JOIN game_user on game_user.id=player_table.AccountId where GUID=$roleid");
		if (empty($user)){
			echo json_encode(array('status'=>0,'msg'=>'用户不存在'));exit;
		}
		$gamebase = D('game_base');
		
		$freezeData = array('Account'=>$user[0]['account'],'forbid_time'=>time()+315360000);
		$point->table('forbid_login')->insert($freezeData);
		$status0 = $point->table('forbid_login')->where(array('Account'=>$user[0]['account']))->find();
		if ($status0){
			$data0 = array (
					"f_uid" => 1,
					"f_ip" => $ip,
					"f_role_id" => $roleid,
					"f_role_name" => $rolename,
					"f_status" => 1,
					"f_time" => date ( 'Y-m-d H:i:s', 315360000 + time() ),
					"f_secends" => 315360000,
					"f_reason" => '在聊天监控中，查到玩家发送非法信息',
					"f_operaor" => $this->user["username"],
					"f_callstatus" => 2,
					"f_roleStatus" => 2,
					"f_inserttime" => date ( "Y-m-d H:i:s", time() )
			);
			$gamebase->table ( 'freeze' )->insert ( $data0 );
		}else {
			echo json_encode(array('status'=>0,'msg'=>'冻结失败，刷新后重试'));exit;
		}	
		
		$cmdArr['cmd'] = "kickplayer";
		$cmdArr['name'] = $rolename;
		$cmdArr['info'] = '玩家因发布不文明信息，强制下线';
		$offineData = array(
				'GmCmd' => addslashes ( myjson ( $cmdArr ) ),
				'ServerId' => $ip,
				'stype' => 2,
				'bHandled' => 0 
		);
		$status1 = $point->table('php_cmd')->insert($offineData);
		if ($status1){
			$data1 = array(
					'f_uid' 		=> $status1,
					"f_ip"			=> $ip,
					"f_role_id"		=> $roleid,
					"f_role_name"	=> $rolename,
					"f_status"		=> 1,
					"f_time" 		=> date("Y-m-d H:i:s"),
					"f_reason" 		=> '在聊天监控中，查到玩家发送非法信息',
					"f_operaor" 	=> $this->user["username"],
					"f_callstatus" 	=>1,
					"f_roleStatus" 	=>1,
					"f_inserttime" 	=> date("Y-m-d H:i:s")
			);
			$gamebase->table ('offline')->insert($data1);
			echo json_encode(array('status'=>1,'msg'=>'已冻结'));exit;
		}else {
			echo json_encode(array('status'=>1,'msg'=>'冻结成功，下线失败'));exit;
		}	
	}
}

 