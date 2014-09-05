<?php
/**
   * FileName		: gmlogingame.class.php
   * Description	: 
   * Author	    : zwy
   * Date			: 2014-6-27
   * Version		: 1.00
   */
class gmlogingame{
	/**
	 * 登录用户信息
	 */
	private $user;

	/**
	 * 初始化数据
	 */
	public function init(){
		$userobj = D("sysuser");
		if($this->user = $userobj->isLogin()){
			if(!in_array("00501800", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}

	/**
	 * gm登录玩家游戏界面
	 */
	public function show(){
		$ipList = parent::getIpList();
		$this->assign("ipList",$ipList);
		$this->display("gmtools/logingame");
	}


}

