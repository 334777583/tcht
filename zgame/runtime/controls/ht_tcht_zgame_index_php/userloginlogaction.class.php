<?php
/**
   * FileName		: userloginlog.class.php
   * Description	: 用户登录日记
   * Author	    : zwy
   * Date			: 2014-6-9
   * Version		: 1.00
   */
class userloginlogAction extends Common {
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
			if(!in_array("00300500", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}

	public function show(){
		$ipList = parent::getIpList();
		$this->assign("ipList",$ipList);
		$this->assign('startdate', date("Y-m-d",strtotime("-7 day")));	//结束时间，默认今天
		$this->assign('enddate', date("Y-m-d",strtotime("-1 day")));	//结束时间，默认今天
		$this->display("user/loginlog");
	}
}

 