<?php
/**
 * FileName: gmnew.class.php
 * Description:用户管理工具-公告管理
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-4-1 下午5:49:26
 * Version:1.00
 */
class gmnew{
	/**
	 * 服务器IP
	 * @var string
	 */
	public $ip;
	
	/**
	 * gm接口类
	 * @var class
	 */
	public $gm;
	
	/**
	 * 用户数据
	 * @var Array
	 */
	public $user;
	
	/**
	 * 每页显示记录数
	 * @var int
	 */
	private $pageSize = 10;
	
	/**
	 * 当前页
	 * @var int
	 */
	private $curPage = 1;
	
	/**
	 * 消息内容
	 * @var string
	 */
	private $content;
	
	/**
	 * 开始时间
	 * @var string
	 */
	private $starttime;
	
	/**
	 * 结束时间
	 * @var string
	 */
	private $endtime;
	
	/**
	 * 消息类型
	 * @var int
	 */
	private $type;
	
	/**
	 * 时间间隔
	 * @var int
	 */
	private $interval;
	
	/**
	 * 滚动公告ID
	 * @var int
	 */
	private $gid;
	
	/**
	 * 定时发送时间
	 * @var string
	 */
	private $tasktime;
	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00500200', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
		$this->gm = new autogm();
		$this->ip =  get_var_value('ip') == NULL?-1:get_var_value('ip');
		$this->pageSize = get_var_value('pageSize') == NULL?10:get_var_value('pageSize');
		$this->curPage =  get_var_value('curPage') == NULL?1:get_var_value('curPage');
		$this->type = get_var_value('type') == NULL?8:get_var_value('type');
		$this->interval =  get_var_value('interval') == NULL?0:get_var_value('interval');
		$this->starttime = get_var_value('starttime') == NULL?date('Y-m-d H:i:s'):get_var_value('starttime');
		$this->endtime =  get_var_value('endtime') == NULL?date('Y-m-d H:i:s',time()+60):get_var_value('endtime');
		$this->content =  get_var_value('content', false) == NULL?'' : get_var_value('content', false);
		$this->gid =  get_var_value('gid') == NULL?'':get_var_value('gid');
		$this->tasktime = get_var_value('tasktime') == NULL?date('Y-m-d H:i:s'):get_var_value('tasktime');
	}
	
	/**
	 * 发布公告
	 */
	public function sendNew(){
		if ($this->ip){
			$newsId = $status = $this->sendNewTrue($this->ip);
		}elseif ($this->ip==0){
			$GameBase = D('game_base');
			$dbList = $GameBase -> table('gamedb') -> where('g_flag = 1') -> select();
			foreach ($dbList as $k=>$v){
				$newsId = $this->sendNewTrue($v['g_id']);
			}
		}
		if ($newsId){
			echo json_encode ( array('status'=>1,'id'=>$newsId,'info'=>'添加成功') );
		}else {
			echo json_encode ( array('status'=>1,'info'=>'添加失败') );
		}
// 		//非定时、循环发送
// 		if ($this->tasktime==0&&$this->interval==0){
// 			$json = '{"cmd":"sysbroadtext","content":"' . $this->content . '","type":' . $this->type . '}';
// 			$insql = "INSERT INTO php_cmd(GmCmd,ServerId,time) VALUES ('" . addslashes ( $json ) . "','" . $this->ip . "','" . strtotime ( "now" ) . "')";
// 			$ins = $Server->rquery ( $insql );
// 			if ($ins != false) {
// 				$Gamebase->table ( 'news' )->where(array('id'=>$newsId))->update(array('n_callstatus'=>1));
// 			}else {
// 				$Gamebase->table ( 'news' )->where(array('id'=>$newsId))->update(array('n_callstatus'=>2));
// 			}
// 			echo json_encode ( $ins );
// 		}else{
// 			//当不是定时任务且开始时间为当前，定时任务且定时时间为当前
// 			if (($this->tasktime==0&&$this->starttime<=$time)||($this->tasktime!=0&&$this->tasktime<=$time)){
// 				$json = '{"cmd":"sysbroadtext","content":"' . $this->content . '","type":' . $this->type . '}';
// 				$insql = "INSERT INTO php_cmd(GmCmd,ServerId,time) VALUES ('" . addslashes ( $json ) . "','" . $this->ip . "','" . strtotime ( "now" ) . "')";
// 				$ins = $Server->rquery ( $insql );
// 				if ($ins != false) {
// 					$Gamebase->table ( 'news' )->where(array('id'=>$newsId))->update(array('n_callstatus'=>1));
// 				}else {
// 					$Gamebase->table ( 'news' )->where(array('id'=>$newsId))->update(array('n_callstatus'=>2));
// 				}
// 				echo json_encode ( $ins );
// 			}
// 		}
		exit ();
	}
	
	protected function sendNewTrue($ip){
		$time = date('Y-m-d H:i:s');
		global $t_conf;
		$srever = 's'.$ip;
		$Server = F($t_conf[$srever]['db'], $t_conf[$srever]['ip'], $t_conf[$srever]['user'], $t_conf[$srever]['password'], $t_conf[$srever]['port']);
		$Gamebase = D('game_base');
		if ($this->tasktime!=0){
			$this->starttime = $this->tasktime;
			//如果为定时任务，确保结束时间比开始时间多120秒，以免定时查询时查询不到
			if ((strtotime($this->endtime)-strtotime($this->starttime))<120){
				$this->endtime = date('Y-m-d H:i:s', strtotime($this->starttime)+120);
			}
		}
		if ($this->endtime<$this->starttime){
			$this->endtime = date('Y-m-d H:i:s', strtotime($this->starttime)+120);
		}
		$newsId = $Gamebase->table ( 'news' )->insert ( array (
				'n_ip' => $ip,
				'n_status' => $this->type,
				'n_starttime' => $this->starttime,
				'n_endtime' => $this->endtime,
				'n_interval' => $this->interval*60,
				'n_content' => $this->content,
				'n_date' => date ( 'Y-m-d H:i:s' ),
				'n_operaor' => $this->user ['username'],
				'n_callstatus' =>0,
				'n_inserttime' => date ( 'Y-m-d H:i:s' )
		));
		//当不是定时任务且开始时间为当前，定时任务且定时时间为当前
		if (($this->tasktime==0&&$this->starttime<=$time)||($this->tasktime!=0&&$this->tasktime<=$time)){
			//倒计时
			if (substr_count($this->content, '{countdown}')>0){
				$countdown = ceil((strtotime($this->endtime)-time())/60).'分后';
				$this->content = str_replace('{countdown}',$countdown,$this->content);
			}
			$json = '{"cmd":"sysbroadtext","content":"' . $this->content . '","type":' . $this->type . '}';
			$insql = "INSERT INTO php_cmd(GmCmd,ServerId,time) VALUES ('" . addslashes ( $json ) . "','" . $ip . "','" . strtotime ( "now" ) . "')";
			$ins = $Server->rquery ( $insql );
			if ($this->interval==0){
				if ($ins != false) {
					$Gamebase->table ( 'news' )->where(array('n_id'=>$newsId))->update(array('n_callstatus'=>1));
				}else {
					$Gamebase->table ( 'news' )->where(array('n_id'=>$newsId))->update(array('n_callstatus'=>2));
				}
			}
		}
		return $newsId;
	}
	
	//中止公告
	public function suspendTask(){
		$id = get_var_value('n_id');
		$Gamebase = D('game_base');
		$news = $Gamebase -> table('news') -> where(array('n_id'=>$id)) -> update(array('n_callstatus'=>-1));
		if ($news){
			echo json_encode(array('status'=>1,'info'=>'中止成功'));
		}else {
			echo json_encode(array('status'=>0,'info'=>'中止失败'));
		}
	}
	
	/**
	 * 获取公告操作数据库信息
	 */
	 
	public function getNewInfo(){
		$total = 0;//记录总数
		$Gamebase = D('game_base');
		$total = $Gamebase -> table('news') -> where("n_ip = ".$this->ip ) -> order('n_id desc') -> total();
		$ipList = autoConfig::getIPS();		//获取服务器信息
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'newAjax','ngo','npage');
		$pageHtml = $page -> getPageHtml();
		// $sqllist = $obj->table('php_cmd')-> where("ServerId = ".$this->ip)->order('id desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
		$sqllist = $Gamebase->table('news')-> where(array('n_ip' => $this->ip))->order('n_id desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
		$list = array();
		foreach($sqllist as $k => $v){
			if($v['n_status'] == 9){
				$list[$k]['stype'] = '滚动公告';
			}else if($v['n_status'] == 10){
				$list[$k]['stype'] = '弹窗公告';
			}else {
				$list[$k]['stype'] = '系统公告';
			}
			$list[$k]["n_id"] = $v['n_id'];
			$list[$k]["content"] = $v['n_content'];
			$list[$k]['startdate'] = $v['n_starttime'];
			$list[$k]['enddate'] = $v['n_endtime'];
			$list[$k]['date'] = $v['n_inserttime'];
			$list[$k]['serverid'] = $v['n_ip'];
			$list[$k]['uname'] = $v['n_operaor'];
			$list[$k]['time'] = $v['n_interval'].'s';
			$list[$k]['state'] = $v['n_callstatus'];
		}
		$result = array(
				'list'=>$list,
				//'uname' =>$this->user['username'],
				'pageHtml'=>$pageHtml,
				'ipList' => $ipList
		);
		echo json_encode($result);
		exit;
// 		$total = 0;//记录总数
// 		global $t_conf;
// 		$obj = F($t_conf['zs']['db'], $t_conf['zs']['ip'], $t_conf['zs']['user'], $t_conf['zs']['password'], $t_conf['zs']['port']);
// 		$total = $obj -> table('php_cmd') -> where("ServerId = ".$this->ip ) -> order('id desc') -> total();
// 		$ipList = autoConfig::getIPS();		//获取服务器信息
// 		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'newAjax','ngo','npage');
// 		$pageHtml = $page -> getPageHtml();
// 		// $sqllist = $obj->table('php_cmd')-> where("ServerId = ".$this->ip)->order('id desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
// 		$sqllist = $obj->table('php_cmd')-> where(array('ServerId' => $this->ip))->order('id desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
// 		$list = array();
// 		foreach($sqllist as $k => $v){
// 			$st[$k] = json_decode($sqllist[$k]['gmcmd'],true);
// 			if($st[$k]['cmd'] == 'sysbroadtext'){
// 				$list[$k] = $st[$k];
// 				$list[$k]['date'] = date('Y-m-d',$sqllist[$k]['time']);
// 				if($list[$k]['type'] == 9){
// 					$list[$k]['stype'] = '滚动公告';
// 				}else if($list[$k]['type'] == 10){
// 					$list[$k]['stype'] = '弹窗公告';
// 				}else {
// 					$list[$k]['stype'] = '系统公告';
// 				}
// 				$list[$k]['time'] = '0s';
// 				$list[$k]['n_id'] = $sqllist[$k]['serverid'];
// 				$list[$k]['uname'] = $this->user['username'];
// 				if($sqllist[$k]['bhandled'] == 0){
// 					$list[$k]['state'] = '已发送';
// 				}else if($sqllist[$k]['bhandled'] == 1){
// 					$list[$k]['state'] = '处理成功';
// 				}else{
// 					$list[$k]['state'] = '处理失败';
// 				}
// 			}
// 		}
// 		$result = array(
// 				'list'=>$list,
// 				//'uname' =>$this->user['username'],
// 				'pageHtml'=>$pageHtml,
// 				'ipList' => $ipList
// 		);
// 		echo json_encode($result);
// 		exit;
	}
	
	/**
	 * 获取当前滚动公告
	 */
	 /*
	public function getCurInfo(){
		$obj = D('game_base');
		$list = $obj -> table('current_news') 
					 -> where(array(
								'c_endtime >=' => date('Y-m-d H:i:s'),
								'c_isdel' => 0,
								'c_ip' => $this->ip
							))
					 -> select();
					 
		foreach($list as $k => $v){
			$list[$k]['c_content'] = htmlspecialchars_decode($v['c_content']);
		}			 
					 		 		 			 		 
		$ipList = autoConfig::getIPS();		//获取服务器信息
		$result = array(
				'list'=>$list,
				'ipList' => $ipList
		);
		echo json_encode($result);
		exit;
	}
	*/
	/**
	 * 删除滚动公告
	 */
	 /*
	public function deleteNew(){
		list($ip, $port, $loginName) = autoConfig::getConfig($this->ip);
		
		$obj = D('game_base');
		$info = array();
		$info['ids'] = array($this->gid);	
		$callReasult = $this -> gm -> gm1007($info,$ip,$port,$loginName);	//调用gm接口
		if($callReasult == 'error'){
			sleep(1);
			$callReasult = $this -> gm -> gm1007($info,$ip,$port,$loginName);
			if($callReasult == 'error') {
				echo "{'error':'远程超时无响应！'}";
				exit;
			}
		}
		
		$arr = explode('|', $callReasult);
		if(isset($arr[1])){
			$result = json_decode($arr[1],true);
			if(isset($result['result'])){
				if($result['result'] == 0){		//成功
					$obj -> table('current_news') -> where('c_id = '.$this->gid) -> update('c_isdel = 1');
				}	
			}
		}
			
		echo json_encode('success');
		exit;
	}
	*/
}