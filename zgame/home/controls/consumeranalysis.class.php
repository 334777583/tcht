<?php
/**
 * FileName: consumerAnalysis.class.php
 * Description:用户行为消耗分析
 * Author: BestWell
 * Date:2013-11-13
 * Version:1.00
 */
class consumeranalysis{


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
			if(!in_array("00401200", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}

	public function show(){
		$ipList = parent::getIpList();
		$this->assign("ipList",$ipList);
		$this->assign('startDate', date('Y-m-d',strtotime('-7 day')));	//开始时间，默认当天的前七天
		$this->assign('endDate', date("Y-m-d"));//,strtotime('-1 day')));	//结束时间，默认今天
		$this->display("money/consumerAnalysis");
	}
	
	
}
?>