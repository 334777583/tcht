<?php
class playersuggest{
	private $user;
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00502200', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	//获取提交记录
	public function getData(){
		$serverId = get_var_value('serverId');//服务器id
		$type = get_var_value('type');//问题类型1bug 2投诉 3建议 4其它
		$status = get_var_value("status");//状态1未回复2已回复
		$startDate = get_var_value('startdate');
		$endDate = get_var_value('enddate');
		$curPage = get_var_value('curPage');//当前页
		$pageSize = get_var_value('pageSize');
	
		if (empty($serverId)){
			echo json_encode(array('status'=>0,'info'=>'参数错误,p37'));exit;
		}
		$startTime = strtotime($startDate);
		$endTime = strtotime($endDate)+24*3600;
		$where = '';//查询条件
		$where.= "ServerID=$serverId and ";
		$where.= "InTime between $startTime and $endTime";
		if (!empty($type)){
			$where.= " and nType=$type";
		}
		
		if (!empty($status)){
			$where.= " and Sig=$status";
		}
		global $t_conf;
		$srever = 's'.$serverId;
		$obj = F($t_conf[$srever]['db'], $t_conf[$srever]['ip'], $t_conf[$srever]['user'], $t_conf[$srever]['password'], $t_conf[$srever]['port']);
			
		$total = $obj->table('gmdial')->where($where)->total();
		
		if (empty($total)){
			echo json_encode(array('status'=>0,'info'=>'参数错误,p37'));exit;
		}
		$totalPage = ceil($total/$pageSize);
		if ($curPage>$totalPage){
			$curPage = $totalPage;
		}
		
		$serverListTem = D('game_base')->table('gamedb')->select();
		foreach ($serverListTem as $v){
			$serverList[$v['g_id']] = $v;
		}
		
		$suggest = $obj->table('gmdial')->field("ServerID,PlayerGUID,PlayerName,DialStr,ID,InTime,Sig,sTitle,nType")
		->where($where)
		-> limit(intval(($curPage-1)*$pageSize),intval($pageSize))
		->order('Sig asc,InTime desc')->select();
		foreach ($suggest as $k=>$v){
			$suggest[$k]['InTime'] = date('Y-m-d H:i:s',$v['InTime']);
			$suggest[$k]['serverName'] = $serverList[$serverId]['g_name'];
			if ($v['Sig']==1){
				$suggest[$k]['Sig'] = '<span style="color:red;">未回复</span>';
			}else {
				$suggest[$k]['Sig'] = '<span style="color:green;">已回复</span>';
			}
			
			switch ($v['nType']){
				case 1:
					$suggest[$k]['nType'] = 'BUG';
					break;
				case 2:
					$suggest[$k]['nType'] = '投诉';
					break;
				case 3:
					$suggest[$k]['nType'] = '建议';
					break;
				default:
					$suggest[$k]['nType'] = '其它';
			}
		}
	
		$page = new autoAjaxPage($pageSize, $curPage, $total, "getData", "go","page");
		$pageHtml = $page->getPageHtml();
		echo json_encode(array('status'=>1,'info'=>'查询成功','list'=>$suggest,'pageHtml'=>$pageHtml));exit;
	}
	
	//获取对应提交与回复内容
	public function getDetail(){
		$serverId = get_var_value('serverId');//服务器id
		$id = get_var_value('id');//后端gmdial表中id
		
		if (empty($serverId)||empty($id)){
			echo 1;exit;
		}
		
		global $t_conf;
		$srever = 's'.$serverId;
		$obj = F($t_conf[$srever]['db'], $t_conf[$srever]['ip'], $t_conf[$srever]['user'], $t_conf[$srever]['password'], $t_conf[$srever]['port']);
		
		
		$question = $obj->table('gmdial')
		->field("ServerID,PlayerGUID,PlayerName,DialStr,ID,InTime,Sig,sTitle,nType")
		->where(array('ID'=>$id))->find();
		
		if (empty($question)){
			echo 2;exit;
		}
		
		$reply = D('game_base')->table('suggest_reply')->where(array('gmdial_id'=>$id,'serverid'=>$serverId))->order('id asc')->select();
		echo json_encode(array('question'=>$question,'reply'=>$reply));exit;
	}
	
	//回复
	public function reply(){
		$serverId = get_var_value('serverId');//服务器id
		$id = get_var_value('id');//后端gmdial表中id
		$title = get_var_value('title');//回复标题
		$content = get_var_value('content');//回复内容
		
		if (empty($serverId)||empty($title)||empty($content)||empty($id)){
			echo 1;exit;
		}
		
		global $t_conf;
		$srever = 's'.$serverId;
		$obj = F($t_conf[$srever]['db'], $t_conf[$srever]['ip'], $t_conf[$srever]['user'], $t_conf[$srever]['password'], $t_conf[$srever]['port']);
		
		$question = $obj->table('gmdial')
		->field("ServerID,PlayerGUID,PlayerName,DialStr,ID,InTime,Sig,sTitle,nType")
		->where(array('ID'=>$id))->find();
		if (empty($question)){
			echo json_encode(array('status'=>0,'info'=>'没有数据,p'.__LINE__));exit;
		}
		
		$cmdArr = array();
		$cmdArr['time'] = time();
		$cmdArr['cmd'] = 'sendmail';
		$cmdArr['name'] = $question['PlayerName'];
		$cmdArr['title'] = $title;
		$cmdArr['content'] = $content;
		$phpCmdId = $obj->table('php_cmd')
		->insert(array('GmCmd'=>addslashes(myjson($cmdArr)),'ServerId'=>$serverId,'stype'=>3,'bHandled'=>0));;
		if (empty($phpCmdId)){
			echo json_encode(array('status'=>0,'info'=>'回复失败,p'.__LINE__));exit;
		}
		$sql = "update gmdial set Sig=2 where ID=$id";
		$obj->table('gmdial')->rquery($sql);
		
		$data = array(
				'serverid'=>$serverId,
				'gmdial_id'=>$id,
				'title'=>$title,
				'content'=>$content,
				'time'=>date('Y-m-d H:i:s')
		);
		D('game_base')->table('suggest_reply')->insert($data);

		echo json_encode(array('status'=>1,'info'=>'回复成功'));exit;
	}
	
	public function del(){
		$serverId = get_var_value('serverId');//服务器id
		$id = get_var_value('id');//后端gmdial表中id
		
		global $t_conf;
		$srever = 's'.$serverId;
		$obj = F($t_conf[$srever]['db'], $t_conf[$srever]['ip'], $t_conf[$srever]['user'], $t_conf[$srever]['password'], $t_conf[$srever]['port']);
		
		$sql = "delete from gmdial where ID=$id and ServerID=$serverId";
		$obj->table('gmdial')->rquery($sql);
		D('game_base')->table('suggest_reply')->where(array('gmdial_id'=>$id,'serverid'=>$serverId))->delete(); 
		echo json_encode(array('status'=>1,'info'=>'删除成功'));exit;
	}
}
	