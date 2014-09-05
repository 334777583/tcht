<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-7-22
// +----------------------------------------------------------------------
// | Describe: 玩家角色信息模型
// +----------------------------------------------------------------------

namespace Common\Model;
use Think\Model;
class PhpCmdModel extends Model{
	protected $tableName = 'php_cmd';
	
	/**
	 * 获取后后端cmd指令处理结果
	 * @param string $table	后台记录表
	 * @param string $where		没有获取到后端处理状态的条件
	 * @param string $id		后台记录表主键id字段名称
	 * @param string $serverId	后台记录表记录服务器id字段名称
	 * @param string $cmdId		对应cmd指令表中的id字段名称
	 * @param string $status		后台记录表中对应后端状态字段名称
	 * @param string $CmCmdResult	后端处理失败原因
	 */
	public function getCmdDelResult($table='',$where=array(),$id='',$serverId='',$cmdId='',$status='',$CmCmdResult='CmCmdResult'){
		if ($table&&$where&&$id&&$serverId&&$cmdId&&$status){
			$Table = M($table);
			$List = $Table->where($where)->select();
			if ($List){
				foreach ($List as $k=>$v){
					$cmdStatus = array();
					$cmd = $this->getById($v[$serverId],$v[$cmdId]);//获取cmd指令状态
					if ($cmdStatus!=0){
						$Table->save(array($id=>$v[$id],$status=>$cmd['bHandled'],$CmCmdResult=>$cmd['CmCmdResult']));
					}
				}
			}
			return true;
		}else {
			return false;
		}
	}
	/**
	 * 通过id获取cmd指令记录
	 * @param int $serverId
	 * @param int $id
	 * @return Ambigous <\Think\mixed, boolean, NULL, multitype:, mixed, unknown, string, object>
	 */
	protected function getById($serverId=1,$id=0){
		$gameDatabase = C('GAME_DATA_PREFIX').$serverId;//数据库配置下标
		$obj = $this->db($gameDatabase,getDbConfig(C($gameDatabase)));//切换数据库
		return $obj->where(array('id'=>$id))->find();
	}
	
	/**
	 * 插入cmd游戏命令
	 * @param number $serverId		服务器id
	 * @param unknown $cmdArr	cmd指令
	 * @param number $type		格式：1禁止登录，2踢人，3发邮件，4禁止聊天,5充值
	 * @return unknown
	 */
	public function insertCMD($serverId=1,$cmdArr,$type=0){
		$gameDatabase = C('GAME_DATA_PREFIX').$serverId;//数据库配置下标
		$obj = $this->db($gameDatabase,getDbConfig(C($gameDatabase)));//切换数据库
		$data = array(
				'GmCmd'=>$this->chineseJSON($cmdArr),
				'time'=>NOW_TIME,
				'ServerId'=>$serverId,
				'stype'=>$type
		);
		$status = $obj->add($data);
		return $status;
	}
	
	//生成中文不被编码的json格式
	protected function chineseJSON($arr = array()){
		$arr = json_encode($this->urlencodeArr($arr));
		return urldecode($arr);
	}
	
	protected function urlencodeArr($data) {
		if(is_array($data)) {
			foreach($data as $key=>$val) {
				$data[$key] = $this->urlencodeArr($val);
			}
			return $data;
		} else {
			return urlencode($data);
		}
	}
}