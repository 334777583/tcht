<?php
/**
   * FileName		: gmlogingame.class.php
   * Description	: 游戏登入
   * Author	    : zwy
   * Date			: 2014-6-12
   * Version		: 1.00
   */
class gmlogingame{
	private $user;
	private $ip;
	private $roleName;
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
			if(!in_array('00501800', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
		$this->ip = get_var_value('ip');
		$this->roleName = get_var_value('rolename');
		$this->pageSize = get_var_value('pageSize') == NULL ? 10 : get_var_value('pageSize');
		$this->curPage = get_var_value('curPage') == NULL ? 1 : get_var_value('curPage');
	}
	
	//登录跳转
	public function loginJump(){
		define("DOMAIN","s2.app1101514477.qqopenapp.com");
		header("Location:".'http://'.DOMAIN.'/index.php?'.'adminisornot=1&seqid=0c182b4483356f3be02916fc1003f11a&openid='.$_GET['openid'].'&openkey=E3BFBE9CD664B4D9CC791B8BBB6E76AD&pf=qzone&serverid='.($_GET['sid']+1).'&pfkey=8b84f22eb855813b1c74011779e1aaa5');
	}
	
	//根据角色名查找用户信息
	public function getUserList(){
		global $t_conf;
		$sever = 's'.$this->ip;
		$Server = F($t_conf[$sever]['db'], $t_conf[$sever]['ip'], $t_conf[$sever]['user'], $t_conf[$sever]['password'], $t_conf[$sever]['port']);
		
		$start = intval(($this->curPage-1)*$this->pageSize);
		if (empty($this->roleName)){
			$count = $Server->fquery("SELECT GUID from player_table");
			$total = count($count);
			$user = $Server->fquery("SELECT a.GUID,a.RoleName,b.account,a.LoginTime,a.LogoutTime,a.bOnline from player_table as a LEFT JOIN game_user as b on a.AccountId=b.id limit $start,$this->pageSize");
		}else {
			$count = $Server->fquery("SELECT GUID from player_table where RoleName like '{$this->roleName}'");
			$total = count($count);
			$user = $Server->fquery("SELECT a.GUID,a.RoleName,b.account,a.LoginTime,a.LogoutTime,a.bOnline from player_table as a LEFT JOIN game_user as b on a.AccountId=b.id where RoleName like '%{$this->roleName}%' limit $start,$this->pageSize");
		}
		
		foreach ($user as $k=>$v){
			$user[$k]['logintime'] = date('Y-m-d H:i:s',$v['LoginTime']);
			$user[$k]['loginouttime'] = date('Y-m-d H:i:s',$v['LogoutTime']);
			if ($v['bOnline']){
				$user[$k]['status'] = '<span style="color:red;">在线</span>';
			}else {
				$user[$k]['status'] = '离线';
			}
		}
		
		$page = new autoAjaxPage($this->pageSize, $this->curPage, $total, "formAjax", "go","page");
		$pageHtml = $page->getPageHtml();
		echo json_encode(array(
				'user'=>$user,
				'pageHtml' => $pageHtml
		));
	}
}
 