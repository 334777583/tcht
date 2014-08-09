<?php
/**
   * FileName		: userloginlog.class.php
   * Description	: 用户登录日记
   * Author	    : zwy
   * Date			: 2014-6-9
   * Version		: 1.00
   */

class userloginlog{
	private $user;
	private $ip;
	private $startDate;
	private $endDate;
	private $code;
	private $codeValue;
	private $pageSize;
	private $curPage;
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00300500', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
		$this->ip = get_var_value('ip');
		$this->startDate = get_var_value('startDate');
		$this->endDate = get_var_value('endDate');
		$this->code = get_var_value('code');
		$this->codeValue = get_var_value('codeValue');
		$this->pageSize = get_var_value('pageSize') == NULL ? 10 : get_var_value('pageSize');
		$this->curPage = get_var_value('curPage') == NULL ? 1 : get_var_value('curPage');
	}
	//查看历史登录日记
	public function getHistoryLog(){
		if ($this->ip&&$this->codeValue){
			global $t_conf;
			$sever = 's'.$this->ip;
			$Server = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
			//用户信息，角色名/账号
			$user = $Server->fquery("SELECT a.GUID,a.RoleName,b.account from player_table as a LEFT JOIN game_user as b on a.AccountId=b.id where GUID=$this->codeValue");
			
			$obj = D('game'.$this->ip);
			$this->startDate .= ' 00:00:00';
			$this->endDate .= ' 23:59:59';
			//总退出记录数量,'o_date >= '=>$this->startDate,'o_date <='=>$this->endDate
			//limit(intval(($this->curPage-1)*$this->pageSize),intval($this->pageSize)) 
			//$total = $obj->table('online_sec')->where(array('o_userid'=>$this->codeValue))->total();
			//退出记录
			$loginOut = $obj -> table('online_sec') -> where(array('o_userid'=>$this->codeValue)) -> order('o_date asc,o_id asc')-> select();
			//登录记录
			$login = $obj -> table('detail_login') -> where(array('d_userid'=>$this->codeValue)) -> order('d_date asc,d_id asc')-> select();
			$list = array();
			$i = 0;
			foreach ($loginOut as $k=>$v){
				if ($v['o_date']>=$this->startDate&&$v['o_date']<=$this->endDate){
					$list[$i]['account'] = $user[0]['account'];//账号
					$list[$i]['rolename'] = $user[0]['RoleName'];//角色名
					$list[$i]['logintime'] = $login[$k]['d_date'];
					$list[$i]['loginip'] = $login[$k]['d_ip'];
					$list[$i]['loginouttime'] = $v['o_date'];
					$i++;
				}
			}
			
			$total = count($list);
			
			$i = 0;
			$start = ($this->curPage-1)*$this->pageSize;
			$end = $start+$this->pageSize;
			foreach ($list as $k=>$v){
				if ($i<$start||$i>=$end){
					unset($list[$k]);
				}
				$i++;
			}
			sort($list);
			$page = new autoAjaxPage($this->pageSize, $this->curPage, $total, "formAjax", "go","page");
			$pageHtml = $page->getPageHtml();
			echo json_encode(array(
					'result' => $list,
					'pageHtml' => $pageHtml
			));
			
		}else {
			echo '1';
		}
	}
	//查看当天登录日记
	public function getCurrentLog(){
		if ($this->ip&&$this->codeValue){
			global $t_conf;
			$sever = 's'.$this->ip;
			$Server = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
			//用户信息，角色名/账号
			$user = $Server->fquery("SELECT a.GUID,a.RoleName,b.account,a.LoginTime,a.LogoutTime,a.bOnline from player_table as a LEFT JOIN game_user as b on a.AccountId=b.id where GUID=$this->codeValue");
			
			$obj = D('game_base');
			$db = $obj -> table('gamedb') -> where("g_flag = 1 and g_id=$this->ip") -> find();
			$path = LPATH . $db['g_ip'] . '/' . date('Y-m-d') . '/';	//日志文件所在目录路径
			
			$file_type1 = $path.'log-type-1.log';
			$file_type2 = $path.'log-type-2.log';
			$loginOut = $this->getFileDate($file_type1,$this->codeValue);
			$login = $this->getFileDate($file_type2,$this->codeValue);
// 			echo json_encode($loginOut);exit();
			$a = empty($loginOut)||empty($login);
			if ($a&&$user[0]['bOnline']==0){
				$list[0]["account"] = $user[0]['account'];
				$list[0]["rolename"] = $user[0]['RoleName'];
				$list[0]["logintime"] = date('Y-m-d H:i:s',$user[0]['LoginTime']);
				$list[0]["loginip"] = '';
				$list[0]["loginouttime"] = date('Y-m-d H:i:s',$user[0]['LogoutTime']);
			}elseif ($a&&$user[0]['bOnline']==1) {
				$list[0]["account"] = $user[0]['account'];
				$list[0]["rolename"] = $user[0]['RoleName'];
				$list[0]["logintime"] = date('Y-m-d H:i:s',$user[0]['LoginTime']);
				$list[0]["loginip"] = '';
				$list[0]["loginouttime"] = '当前在线';
			}elseif(!$a&&$user[0]['bOnline']==1) {
				$count = count($login)-2;
				$j = count($loginOut)-1;
				for ($i=$count;$i>=0;$i--){
					$list[$i]["account"] = $user[0]['account'];
					$list[$i]["rolename"] = $user[0]['RoleName'];
					$list[$i]["logintime"] = date('Y-m-d H:i:s',$login[$i]['time']);
					$list[$i]["loginip"] = $login[$i]['ip'];
					$list[$i]["loginouttime"] = date('Y-m-d H:i:s',$loginOut[$j]['time']);
					$j--;
				}
				$list[$count]["account"] = $user[0]['account'];
				$list[$count]["rolename"] = $user[0]['RoleName'];
				$list[$count]["logintime"] = date('Y-m-d H:i:s',$user[0]['LoginTime']);
				$list[$count]["loginip"] = $login[$count+1]['ip'];;
				$list[$count]["loginouttime"] = '当前在线';
			}elseif (!$a&&$user[0]['bOnline']==0){
				$count = count($login)-1;
				$j = count($loginOut)-1;
				if ($count>$j){
					array_pop($login);//防止play_table表中bOnline字段更新慢，导致用户实际在线时出现在不在线情况
					$count = count($login)-1;
				}
// 				echo json_encode(array($count,$j));exit();
				for ($i=$count;$i>=0;$i--){
					$list[$i]["account"] = $user[0]['account'];
					$list[$i]["rolename"] = $user[0]['RoleName'];
					$list[$i]["logintime"] = date('Y-m-d H:i:s',$login[$i]['time']);
					$list[$i]["loginip"] = $login[$i]['ip'];
					$list[$i]["loginouttime"] = date('Y-m-d H:i:s',$loginOut[$j]['time']);
					$j--;
				}
			}
			sort($list);
			echo json_encode(array('result' => $list));
		}else {
			echo '1';
		}
	}
	
	//获取日记文件数据
	public function getFileDate($path,$playid){
		if (file_exists($path)) {
			$fp = fopen($path, "r");							//读取日志文件
			$log_data = array();								//保存日志分析信息
			while(!feof($fp)) {
				$line = fgets($fp,2048);
				if(!empty($line)) {
					$INFO  = trim(substr($line, 21));
					$INFO  = str_replace("'", '"', $INFO );
					$arr = json_decode($INFO , true);
					if(is_array($arr)&&$arr['playid']==$playid) {
						$log_data[] = $arr;
					}
				}
			}
			fclose($fp);										//关闭文件指针
			return $log_data;
		}else {
			return array();
		}
	}
	
	//根据账号或者角色名查找用户角色id
	public function getUserPlayId(){
		if ($this->codeValue){
			global $t_conf;
			$sever = 's'.$this->ip;
			$Server = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
			
			switch ($this->code){
				case 1:
					$user = $Server->fquery("SELECT a.GUID,a.RoleName,b.account from player_table as a LEFT JOIN game_user as b on a.AccountId=b.id where b.account like '%$this->codeValue%'");
					break;
				case 2:
					$user = $Server->fquery("SELECT GUID,RoleName from player_table where RoleName LIKE '%$this->codeValue%'");
					break;
			}
			if (count($user)>0){
				echo json_encode(array('result'=>$user));
			}else {
				echo '1';
			}
		}else {
			echo '1';
		}
	}
}
 