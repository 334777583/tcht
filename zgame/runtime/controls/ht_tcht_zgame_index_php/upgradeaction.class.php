<?php
/**
   * FileName		: upgrade.class.php
   * Description	: 
   * Author	    : zwy
   * Date			: 2014-6-12
   * Version		: 1.00
   */
class upgradeAction extends Common {
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
			if(!in_array("00300700", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}

	/**
	 * 升级日志
	 */
	public function show(){
		$ipList = parent::getIpList();
		$this->assign("ipList",$ipList);
		$this->assign('startdate',date('Y-m-d',strtotime("-8 day")));
		$this->assign('enddate',date('Y-m-d',strtotime("-1 day")));
		$this->display("user/upgrade");
	}


}
 