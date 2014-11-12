<?php
/**
   * FileName		: playerTrad.class.php
   * Description	: 玩家击杀boss查询
   * Author	    : zwy
   * Date			: 2014-11-5
   */
class playertrad{
	private $user;
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00301200', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}

	public function getData(){
		$serverId = get_var_value('serverId');
		$roleName = get_var_value('rolename');
		$goodsName = get_var_value('goodsname');
	
		$queryType = get_var_value('querytype');
		$pageSize = get_var_value('pageSize',15,'intval');
		$curPage = get_var_value('curPage',1,'intval');
	
		if (empty($serverId)){
			echo '0';exit;
		}
	
		if ($queryType==1){
			$where = 'sub_type=2005';//市场交易查询
		}elseif ($queryType==2){
			$where = 'sub_type=2006';//玩家交易查询
		}else {
			echo '1';exit;
		}
		
		global $t_conf;
		$srever = 's'.$serverId;
		$point = DF($t_conf[$srever]);
		
		$obj = D("game".$serverId);
	
		$map = '';
		if (!empty($roleName)){
			if ($queryType==1){
				$map .= "target_playname like '%{$roleName}%'";
	
				$sql = "select GUID,RoleName from player_table where ServerId=$serverId and RoleName like '%$roleName%'";
				$player = $point->fquery($sql);
				if (!empty($player)){
					$guid = array();
					$user = array();
					foreach ($player as $v){
						$user[$v['GUID']] = $v['RoleName'];
						$guid[] = $v['GUID'];
					}
					unset($player);
					$guid = implode(',', $guid);
					$map .= "or playid in ($guid)";
				}
			}elseif ($queryType==2){
				$map = "send_name like '%{$roleName}%' or recieve_name like '%{$roleName}%'";
			}
		}
	
		//匹配物品信息
		$goodsArr = array();
		if (!empty($goodsName)){
			$goods = $obj->table('tools_detail')->where("t_code like '%{$goodsName}%' or t_name like '%{$goodsName}%'")->select();
			if (!empty($goods)){
				$goodsIds = array();
				foreach ($goods as $v){
					$goodsIds[] = $v['t_code'];
					$goodsArr[$v['t_code']] = $v['t_name'];
				}
				unset($goods);
				if (empty($map)){
					$map .= " item_id in (".implode(',', $goodsIds).")";
				}else {
					$map .= " or item_id in (".implode(',', $goodsIds).")";
				}
			}
		}
	
		if (!empty($map)){
			$where .= " and ($map)";
		}
		
		$total = $obj->table("water_log")->where($where)->total();
		if (empty($total)){
			echo '3';exit;
		}
		$totalPage = ceil($total/$pageSize);
		if ($curPage>$totalPage){
			$curPage = $totalPage;
		}
		
		$list = $obj->table("water_log")->where($where)
		-> limit(intval(($curPage-1)*$pageSize),intval($pageSize))
		->order("time desc")->select();
		
		//物品信息
		if (empty($goodsArr)){
			$goods = $obj->table('tools_detail')->select();
			$goodsArr = array();
			foreach ($goods as $v){
				$goodsArr[$v['t_code']] = $v['t_name'];
			}
			unset($goods);
		}
		
		//市场交易匹配买方玩家角色名
		if ($queryType==1 && empty($player)){
			$guid = array();
			foreach ($list as $v){
				$guid[] = $v['playid'];
			}
			$sql = "select GUID,RoleName from player_table where ServerId=$serverId and GUID in (".implode(',', $guid).")";
			$player = $point->fquery($sql);
			if (!empty($player)){
				foreach ($player as $v){
					$user[$v['GUID']] = $v['RoleName'];
				}
				unset($player);
			}
		}
		//匹配物品名称
		foreach ($list as $k=>$v){
			$list[$k]['datetime'] = date('Y-m-d H:i:s',$v['time']);
				
			if (isset($goodsArr[$v['item_id']])){
				$list[$k]['goodsname'] = $goodsArr[$v['item_id']];
			}else {
				$list[$k]['goodsname'] = $v['item_id'];
			}
				
			if ($v['sub_type']==2005){
				if (isset($user[$v['playid']])){
					$list[$k]['send_name'] = $user[$v['playid']];
				}else {
					$list[$k]['send_name'] = $v['playid'];
				}
				$list[$k]['recieve_name'] = $v['target_playname'];
	
				$list[$k]['type'] = '市场交易';
			}elseif ($v['sub_type']==2006){
				$list[$k]['type'] = '玩家交易';
			}else {
				$list[$k]['type'] = '未知';
			}
		}
		
		$page = new autoAjaxPage($pageSize, $curPage, $total, "getData", "go","page");
		$pageHtml = $page->getPageHtml();
		echo json_encode(array(
				'list' => $list,
				'pageHtml' => $pageHtml
		));exit;
	}
}