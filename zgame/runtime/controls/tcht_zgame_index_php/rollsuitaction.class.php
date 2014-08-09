<?php
/**
   * FileName		: rollsuit.class.php
   * Description	: 滚服统计
   * Author	    : zwy
   * Date			: 2014-7-12
   * Version		: 1.00
   */

class rollsuitAction extends Common {
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
			if(!in_array("00401800", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}

	public function show(){
		$ipList = parent::getIpList();
		$this->assign("ipList",$ipList);
		$this->display("system/rollsuit");
	}

}