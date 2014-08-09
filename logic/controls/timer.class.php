<?php
/**
 * FileName: timer.class.php
 * Description:用户管理工具-公告管理
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-4-1 下午5:49:26
 * Version:1.00
 */
class timer{
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
		$obj = D('game_base');
		//$time = date('Y-m-d H:i:s',time()+8*3600);
		$time = date('Y-m-d H:i:s');
		global $t_conf;
		$point = F($t_conf['zs']['db'], $t_conf['zs']['ip'], $t_conf['zs']['user'], $t_conf['zs']['password'], $t_conf['zs']['port']);
		$list = $point->table('player_table')->select();
		print_R($t_conf);
	}
}
$obj = new timer();