<?php
/**
 * FileName: rechargeport.class.php
 * Description:充值接口
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-7-5 11:52:01
 * Version:1.00
 */
class rechargeport{
	/**
	 * 登录用户信息
	 */
	private $user;

	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00100600', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}

    public function add(){
        $roleName = get_var_value('roleName');
        $serverId = get_var_value('serverId');
        $money = get_var_value('money');

        if(empty($serverId)||empty($roleName)||empty($money)){
            echo json_encode(array('status'=>0,'info'=>'参数错误！'));exit;
        }

        global $t_conf;
        $srever = 's'.$serverId;
        $point = F($t_conf[$srever]['db'], $t_conf[$srever]['ip'], $t_conf[$srever]['user'], $t_conf[$srever]['password'], $t_conf[$srever]['port']);

        $info = '';
        $time = time();
        $roleName = explode(';',$roleName);
        foreach($roleName as $k=>$v){
            $data = array();
            $cmd = array();

            $player = $point->table('player_table')->field("GUID,RoleName,ServerId")->where("ServerId=$serverId and RoleName='{$v}'")->find();
            if(!$player){
                $info .= $v."用户不存在！";
                continue;
            }

            $in = array(
                'ServerID'=>$serverId,
                'PlayerGUID'=> $player['GUID'],
                'RMB'=>$money,
                'FakeRMB2'=>$money,
                'Charge_time'=>$time
            );
            $in_arr = $point->table('charge_list')->insert($in);
            if(!$in_arr){
                $info .= $v."用户充值失败！";
                continue;
            }
            $php_cmd = array();
            $php_cmd['cmd'] = "charge";
            $php_cmd['GUID'] = $player['GUID'];
            $php_cmd['time'] = $time;
            $php_cmd = addslashes(json_encode($php_cmd));
            $cmd = array('GmCmd'=>$php_cmd,'ServerId'=>$serverId,'time'=>$time,'bHandled'=>0);
            $point->table('php_cmd')->insert($cmd);

            $data = array(
                'ServerID'=>$serverId,
                'PlayerGUID'=> $player['GUID'],
                'RoleName'=>$player['RoleName'],
                'RMB'=>$money,
                'FakeRMB2'=>$money,
                'chargeListId'=>$in_arr,
                'Charge_time'=>$time
            );
            D('game_base')->table('charge_list')->insert($data);
        }

        if(empty($info)){
            $info = "充值成功！";
        }
        echo json_encode(array('status'=>1,'info'=>$info));exit;
    }
    
    //获取充值记录
    public function getData(){
        $this->getResult();//获取后端处理结果

        $pageSize = 10;
        $curPage = get_var_value('curPage') == NULL ? 1 : get_var_value('curPage');
        $obj = D('game_base');

        $total = $obj->table('charge_list')->total();
        $list = $obj->table('charge_list')->field('playerguid,rolename,serverid,rmb,charge_time,result')->limit(intval(($curPage-1)*$pageSize),intval($pageSize))->select();

        $page = new autoAjaxPage($pageSize, $curPage, $total, "getData", "go","page");
        $pageHtml = $page->getPageHtml();

        $tem = $obj->table('gamedb')->select();
        foreach($tem as $k=>$v){
            $ipList[$v['g_id']] = $v;
        }

        foreach($list as $k=>$v){
            $list[$k]['servername'] = $ipList[$v['serverid']]['g_name'];
            $list[$k]['charge_time'] = date('Y-m-d H:i:s',$v['charge_time']);
            if($v['result']==-1){
                $list[$k]['result'] = '处理中';
            }elseif($v['result']==1){
                $list[$k]['result'] = '充值成功';
            }else {
                $list[$k]['result'] = '充值失败';
            }
        }

        echo json_encode(array(
            'list' => $list,
            'pageHtml' => $pageHtml,
        ));exit;
    }
    //获取后端处理结果
    private function getResult(){
        $obj = D('game_base');
        $list = $obj->table('charge_list')->field('id,playerguid,rolename,serverid,rmb,charge_time,result,chargelistid')->where('result=-1')->select();
        if(!empty($list)){
            global $t_conf;
            foreach($list as $k=>$v){
                $srever = 's'.$v['serverid'];
                $point = F($t_conf[$srever]['db'], $t_conf[$srever]['ip'], $t_conf[$srever]['user'], $t_conf[$srever]['password'], $t_conf[$srever]['port']);
                $re = $point->table('charge_list')->where('id='.$v['chargelistid'])->find();
                if($re['result']!=$v['result']){
                    $obj->table('charge_list')->where('id='.$v['id'])->update(array('result'=>$re['result']));
                }
            }
        }
    }
	
}