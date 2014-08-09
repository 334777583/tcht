<?php
/**
   * FileName		: gmchat.class.php
   * Description	: 充值等级查询
   * Author	    : zwy
   * Date			: 2014-6-6
   * Version		: 1.00
   */
class gmchat{
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
			if(!in_array('00501900', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	/**
	 * 获取数据
	 */
	public function getChatContents(){
		$ip = get_var_value('ip');
		$chatnum = get_var_value('chatnum');
		$chatType = get_var_value('chatType');
		$Gamebase = D('game_base');
		$g_folder = $Gamebase->field('g_ip')->table('gamedb')->where('g_id = '.$ip)->find();
		$path = LPATH . $g_folder['g_ip'] . '/' . date('Y-m-d') . '/';	//日志文件所在目录路径
		$chatFilePath = $path.'/log-type-18.log';
		if (!is_file($chatFilePath)){
			echo '1';exit;//文件不存在退出
		}
		
		$channel = array('私聊','队伍','帮派','世界','喇叭','系统','中央屏幕','好友聊天','陌生人消息','场景聊天','国家聊天','传闻(强化)','组队招募广播');
		
		$fp = fopen($chatFilePath, "r");
		$line = $chatnum;
		$pos = -2;
		$t = " ";
		$data = array();
		$arr = array();
		while ($line > 0) {
			while ($t != "\n") {
				$a = fseek($fp, $pos, SEEK_END);
				$t = fgetc($fp);
				$pos --;
				if ($a==-1){
					break;
				}
			}
			if ($a==-1){
				break;
			}
			$t = " ";
			$linedata = fgets ( $fp, 2048 );
			if (! empty ( $linedata )) {
				$INFO = trim ( substr ( $linedata, 21 ) );
				$INFO = strip_tags($INFO);
				$INFO = str_replace ( "'", '"', $INFO );
				$arr = json_decode ( $INFO, true );
// 				$tem[] = $INFO;
				if (is_array($arr)&&in_array($arr['chat_type'], $chatType)){
					if ($arr['chat_type']==2){
// 						$match1 = "/[很]{1}[高][兴][认][识][你][，][以][后][去][打][B][O][S][S][下][多][人][副][本][记][得][叫][上][我][哦][！][~]/";
						$match1 = "/(很高兴认识你，以后去打BOSS下多人副本记得叫上我哦！~){1}/";
						$match2 = "/(哟，新人啊，以后由我照着你了。){1}/";
						$match3 = "/(我正在想要送你鲜花 ，你先给爷笑一个。){1}/";
// 						echo json_encode(preg_match_all($match1,"很高兴认识你，以后去打BOSS下多人副本记得叫上我哦！~"));exit;
						if (preg_match($match1,$arr['content'])){
							continue;
						}elseif (preg_match($match2,$arr['content'])){
							continue;
						}elseif (preg_match($match3,$arr['content'])){
							continue;
						}
					}
					$data[] = $arr;
					$line --;
				}
			}
		}
		fclose ($fp);
// 		echo json_encode($tem);exit;
		$data = array_reverse($data);
		
// 		$filesize = filesize($chatFilePath);
// 		$start = $filesize-1024*2;
// 		if ($start<0){
// 			$start = 0;
// 		}
// 		$handle = fopen ( $chatFilePath, "r" ); // 读取日志文件
// 		fseek($handle, $start,SEEK_SET);
// 		$data = array();
// 		while ( ! feof ( $handle ) ) {
// 			$line = fgets ( $handle, 2048 );
// 			if (! empty ( $line )) {
// 				$INFO = trim ( substr ( $line, 21 ) );
// 				$INFO = str_replace ( "'", '"', $INFO );
// 				$arr = json_decode ( $INFO, true );
// 				if (is_array($arr)){
// 					$data[] = $arr;
// 				}
// 			}
// 		}
// 		fclose ( $handle ); // 关闭文件指针
		$list = array();
		foreach ($data as $k=>$v){
			$list[$k] = $v;
			$list[$k]['channel'] = $channel[$v['chat_type']];
			$list[$k]['date'] = date('Y-m-d H:i:s',$v['time']);
		}
		echo json_encode($list);exit;
	}
}

 