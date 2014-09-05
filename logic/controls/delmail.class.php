<?php
/**
 * FileName: timer.class.php
 * Description:用户管理工具-公告管理
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-4-1 下午5:49:26
 * Version:1.00
 */
class delmail{
	/**
	 * 用户数据
	 * @var Array
	 */
	public $user;

	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		
		$this->select();
	}
	
	public function select(){
		//$obj = D('game_base');
		global $t_conf;
		$point = F($t_conf['zs']['db'], $t_conf['zs']['ip'], $t_conf['zs']['user'], $t_conf['zs']['password'], $t_conf['zs']['port']);
		$dellist = $point->table('mail')->where('WholeSrvMailId > 0')->select();
		print_R($dellist);
		exit;
		foreach($dellist as $item){
			$info['cmd'] = "delmailtoall";
			$info['wholesrvmailid'] = $item['wholesrvmailid'];
			$in ='{"cmd":"'.$info['cmd'].'" , "WholeSrvMailId":"'.$info['wholesrvmailid'].'" }';
			$uid = $point -> table('php_cmd') -> insert(array('GmCmd'=>addslashes($in),'ServerId'=>1,'stype'=>3,'bHandled'=>0));
			echo $uid;
		}
		
						
	}	
}