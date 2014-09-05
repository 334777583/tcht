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
class PlayerTableModel extends Model{
	// protected $tablePrefix;// 定义模型对应数据表的前缀，如果未定义则获取配置文件中的DB_PREFIX参数
	// protected $tableName;//不包含表前缀的数据表名称，一般情况下默认和模型名称相同，只有当你的表名和当前的模型类的名称不同的时候才需要定义。
	// protected $trueTableName;// 包含前缀的数据表名称，也就是数据库中的实际表名，该名称无需设置，只有当上面的规则都不适用的情况或者特殊情况下才需要设置。
	// protected $dbName;// 定义模型当前对应的数据库名称，只有当你当前的模型类对应的数据库名称和配置文件不同的时候才需要定义。
	// protected $fields = array('id', 'username', 'email', 'age','_pk'=>'id');
	// 命名范围normal
	
	protected $_scope = array (
			'normal' => array (
					'field' => array (
							'GUID','AccountId','ServerId','RoleName','Level','Coin','BindCoin','BindGold','Gold','Sex',
							'CreateTime','LoginTime','LogoutTime','IsDel','DelTime','VipLevel','RMB','bOnline' 
					), 
			),
			'join'=>array(
				'table'=>array('player_table'=>'a','game_user'=>'b'),
				'field'=> array (
							'a.GUID','a.AccountId','a.ServerId','a.RoleName','a.Level','a.Coin','a.BindCoin','a.BindGold','a.Gold','a.Sex',
							'a.CreateTime','a.LoginTime','a.LogoutTime','a.IsDel','a.DelTime','a.VipLevel','a.RMB','a.bOnline',
							'b.account','b.LastLogoutTime','b.InviteId','b.InviteTime','b.is_yellow_year_vip','b.yellow_vip_level'
						
				), 
				'where'=>array('a.accountId=b.id'),
			), 
	);
	protected $obj;
	
	/**
	 * 
	 * 根据角色名查找用户角色信息
	 * @param string $roleName	角色名
	 * @param string $isFuzzy		是否为模糊查询,默认为模糊查询
	 * @param string $filed	要查询的字段
	 * @return Ambigous <\Think\mixed, string, boolean, NULL, multitype:, mixed, object>
	 */
	public function getByRoleName($serverId=1,$roleName='',$isFuzzy=true,$filed='normal'){
		$gameDatabase = C('GAME_DATA_PREFIX').$serverId;//数据库配置下标
		$this->obj = $this->db($gameDatabase,getDbConfig(C($gameDatabase)));//切换数据库
		$where = array();
// 		$where['ServerId'] = $serverId;
		if ($isFuzzy&&!empty($roleName)){
			$where['RoleName'] = array('like',"%$roleName%");
		}elseif (!empty($roleName)) {
			$where['RoleName'] = $roleName;
		}
		$data = $this->obj->scope($filed)->where($where)->order('GUID asc')->select();
		return $data;
	}
	
	/**
	 * 根据条件获取分页数据
	 * @param number $serverId		服务器id
	 * @param array $where			条件
	 * @param number $curPage		当前页
	 * @param number $pageSize	页码
	 * @param string $filed		命令范围
	 * @param string $order		排序
	 * @return unknown
	 */
	public function getPageData($serverId=1,$where=array(),$curPage=1,$pageSize=15,$filed='normal',$order='GUID asc'){
		$gameDatabase = C('GAME_DATA_PREFIX').$serverId;//数据库配置下标
		$this->obj = $this->db($gameDatabase,getDbConfig(C($gameDatabase)));//切换数据库
// 		$where = array_merge($where,array('ServerId'=>$serverId));
		$data = $this->obj->scope($filed)->where($where)->order($order)->page($curPage,$pageSize)->select();
		return $data;
	}
	
	/**
	 * 根据条件获取所有数据
	 * @param number $serverId
	 * @param unknown $where
	 * @param string $filed
	 * @param string $order
	 * @return unknown
	 */
	public function getAll($serverId=1,$where=array(),$filed='normal',$order='GUID asc'){
		$gameDatabase = C('GAME_DATA_PREFIX').$serverId;//数据库配置下标
		$this->obj = $this->db($gameDatabase,getDbConfig(C($gameDatabase)));//切换数据库
// 		$where = array_merge($where,array('ServerId'=>$serverId));
		$data = $this->obj->scope($filed)->where($where)->order($order)->select();
		return $data;
	}
	
	/**
	 * 通过条件查找一条数据
	 * @param number $serverId
	 * @param unknown $where
	 * @param string $filed
	 * @return Ambigous <\Think\mixed, boolean, NULL, multitype:, mixed, unknown, string, object>
	 */
	public function getOne($serverId=1,$where=array(),$filed='normal'){
		$gameDatabase = C('GAME_DATA_PREFIX').$serverId;//数据库配置下标
		$this->obj = $this->db($gameDatabase,getDbConfig(C($gameDatabase)));//切换数据库
// 		$where = array_merge($where,array('ServerId'=>$serverId));
		$data = $this->obj->scope($filed)->where($where)->select();
		return $data;
	}
	
	/**
	 * 通过角色id获取角色名
	 * @param int $id		角色id
	 * @param int $serverId		服务器id
	 * @return unknown
	 */
	public function getRoleNameById($serverId=1,$id=''){
		if (empty($id)){
			return '';
		}
		$gameDatabase = C('GAME_DATA_PREFIX').$serverId;//数据库配置下标
		$this->obj = $this->db($gameDatabase,getDbConfig(C($gameDatabase)));//切换数据库
		$roleName = $this->obj->where(array('GUID'=>$id))->getField('RoleName');
		return $roleName;
	}
	
	/**
	 * 通过角色id获取数据
	 * @param int $id		角色id
	 * @param int $serverId		服务器id
	 * @return unknown
	 */
	public function getById($serverId=1,$id='',$field='normal'){
		if (empty($id)){
			return '';
		}
		$gameDatabase = C('GAME_DATA_PREFIX').$serverId;//数据库配置下标
		$this->obj = $this->db($gameDatabase,getDbConfig(C($gameDatabase)));//切换数据库
		$player = $this->obj->scope($field)->where(array('GUID'=>$id))->find();
		return $player;
	}
	
	/**
	 * 根据条件获取总记录数
	 * @param unknown $serverId
	 * @param unknown $where
	 * @return unknown
	 */
	public function getTotal($serverId,$where){
		$gameDatabase = C('GAME_DATA_PREFIX').$serverId;//数据库配置下标
		$this->obj = $this->db($gameDatabase,getDbConfig(C($gameDatabase)));//切换数据库
// 		$where = array_merge($where,array('ServerId'=>$serverId));
		$count = $this->obj->where($where)->count();
		return $count;
	}
}