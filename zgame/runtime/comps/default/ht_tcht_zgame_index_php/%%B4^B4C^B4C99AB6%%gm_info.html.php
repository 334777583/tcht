<?php /* Smarty version 2.6.18, created on 2014-07-31 09:48:03
         compiled from gmtools/gm_info.html */ ?>
<!DOCTYPE html>
<html>
<head>
<title>运营工具-用户信息查询</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?php echo $this->_tpl_vars['res']; ?>
/css/skin.css" rel="stylesheet" type="text/css">
<link href="<?php echo $this->_tpl_vars['res']; ?>
/css/jquery-ui.css" rel="stylesheet" type="text/css">

<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EEF2FB;
	font-size: 12px;
}
.mytable td {
	font-size: 12px;
}
-->
</style>
</head>
<body>
	<div>
		<div>
			<div>
				<table class="explain">
					<thead>
					</thead>
					<tbody style="font-family:Mingliu">
						<tr>
							<td width="5%"  class="tableleft"><b>说明：</b></td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">1、可根据玩家帐号、角色名、角色ID查询<font color = "red"><b>玩家基本信息</b></font>、<font color = "red"><b>装备信息</b></font>、<font color = "red"><b>背包信息</b></font>等：</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="topinfo">
				<div>
					<span>服务器:</span>
					<select id="sip">
						<?php $_from = $this->_tpl_vars['ipList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ip']):
?>
							<option value="<?php echo $this->_tpl_vars['ip']['s_id']; ?>
"><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</option>
						<?php endforeach; endif; unset($_from); ?>
						<option value="0" >全部</option>
					</select>
					<select id="type" style="margin-left:50px">
						<option value="2">角色名</option>
						<option value="0">账号名</option>
						<option value="1">玩家ID</option>
					</select>
					<input type="text" class="input1" id="qt"/>
					<input type="button" value="查询" id="querybtn" style="margin-left:20px"/>
				</div>
			</div>
			<div style="clear:both"></div>
			<div id="tabs-1" class="tabitem">
				<div>
					<table  class="mytable">
						<thead>
							<tr>
								<th>玩家ID</th>
								<th>账号名</th>
								<th>角色名</th>
								<th>性别</th>
								<th>等级</th>
								<th>职业</th>
								<th>创建时间</th>
								<th>最近在线时间</th>
								<th>最近登录IP</th>
								<th>总在线时长</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody id="infota">
						</tbody>
					</table>
				</div>
				<div id="pagehtml" style="float:right;margin-right:20px"></div>
			</div>
			<div id="tabs-2" class="tabitem" style="display:none">
				<div style="display:none">
					<table width="40%" border="0" cellspacing="0" cellpadding="0" class="tran">
						<thead>
							<tr>
								<th>传送门：</th>
								<th>玩家消费记录</th>
								<th>元宝消费记录</th>
								<th>给玩家发邮件</th>
								<th>玩家登录日志</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				<div style="display:block" id="detial">
					<div>
						<table  class="mytable" cellspacing="0" >
							<thead>
								<tr>
									<th colspan="15" style="text-align: left">玩家详细信息</th>
								</tr>
							</thead>
							<tbody>
								<tr class="tblod">
									<td>账号</td>
									<td>角色ID</td>
									<td>角色名</td>
									<td>性别</td>
									<td>等级</td>
									<td>vip等级</td>
									<td>职业</td>
									<td>所在帮会</td>
									<td>战斗力</td>
									<td>所在地图</td>
									<td>总充值金额</td>
									<td>元宝</td>
									<td>绑定元宝</td>
									<td>铜钱</td>
									<td>绑定铜钱</td>
								</tr>
								<tr id="detailInfo">
								</tr>
								<tr class="tblod">
									<td colspan="2">创建时间</td>
									<td colspan="2">最近在线时间</td>
									<td colspan="2">最近登录IP</td>
									<td colspan="2">总在线时长</td>
									<td>在线状态</td>
									<td>账号状态</td>
									<td colspan="5">&nbsp;</td>
								</tr>
								<tr id="detailInfo2">
									<td colspan="3">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="2">操作</td>
									<td colspan="13" style="text-align: left">
										<input type="button" value="禁言" id="jybt"/>
										<input type="button" value="解禁" id="jjbt" style="display:none"/>
										<input type="button" value="冻结" id="djbt"/>
										<input type="button" value="解冻" id="jdbt" style="display:none"/>
										<input type="button" value="强制下线" id="xxbt"/>
										<!--<input type="button" value="登录该账号" id="login">-->
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div style="margin-top:20px">
						<table  class="mytable" cellspacing="0" align="center" >
							<thead>
								<tr>
									<th colspan='15'>
										<span class="b_sq">玩家装备信息</span>
										<span class="sqzk" id="zbclose"><img height="20px" src='<?php echo $this->_tpl_vars['res']; ?>
/images/zk.png'/></span>
										<span class="sqzk" id="zbopen" style="display:none"><img height="20px" src='<?php echo $this->_tpl_vars['res']; ?>
/images/sq.png'/></span>
									</th>
								</tr>
							</thead>
							<tbody id="zbbody"|>
								<tr class="tblod">
									<td>部位</td>
									<td>武器</td>
									<td>头部</td>
									<td>衣服</td>
									<td>腰带</td>
									<td>项链1</td>
									<td>项链2</td>
									<td>护腕</td>
									<td>戒指1</td>
									<td>戒指2</td>
									<td>鞋子</td>
									<td>裤子</td>
									<td>武器时装</td>
									<td>衣服时装</td>
								</tr>
								<!--<tr id="name">
									<td>名称</td>
									<td id="name_WEAPONS">空</td>
									<td id="name_HEAD">空</td>
									<td id="name_CLOTHES">空</td>
									<td id="name_CLOAK">空</td>
									<td id="name_NECKLACE">空</td>
									<td id="name_BRACERS">空</td>
									<td id="name_RING">空</td>
									<td id="name_SHOES">空</td>
									<td id="name_MOUNTS">空</td>
								</tr>
								<tr id="slevel">
									<td>强化等级</td>
									<td id="clevel_WEAPONS">0</td>
									<td id="clevel_HEAD">0</td>
									<td id="clevel_CLOTHES">0</td>
									<td id="clevel_CLOAK">0</td>
									<td id="clevel_NECKLACE">0</td>
									<td id="clevel_BRACERS">0</td>
									<td id="clevel_RING">0</td>
									<td id="clevel_SHOES">0</td>
									<td id="clevel_MOUNTS">0</td>
								</tr>-->
							</tbody>
						</table>
					</div>
					
					<div style="margin-top:20px">
						<table  class="mytable" cellspacing="0" align="center" >
							<thead>
								<tr>
									<th colspan="32">
										<span class="b_sq">武将信息</span>
										<span class="sqzk" id="wjopen"><img  height="20px" src='<?php echo $this->_tpl_vars['res']; ?>
/images/zk.png'/></span>
										<span class="sqzk" id="wjclose" style="display:none"><img height="20px" src='<?php echo $this->_tpl_vars['res']; ?>
/images/sq.png'/></span>
									</th>
								</tr>
							</thead>
							<tbody id="generals" style="display:none"> 
								<tr class="tblod" >
									<td>序号</td>
									<td>武将名称</td>
									<td>武将等级</td>
									<td>武将战力</td>
									<td>转生次数</td>
									<td>资质</td>
									<td>成长</td>
									<td>生命值</td>
									<td>物理攻击</td>
									<td>物理防御</td>
									<td>法术攻击</td>
									<td>法术防御</td>
									<td>命中</td>
									<td>闪避</td>
									<td>暴击</td>
									<td>坚韧</td>
									<td>力量</td>
									<td>敏捷</td>
									<td>体质</td>
									<td>智力</td>
									<td>精神</td>
									<td>兵书评分</td>
									<td>兵书名称等级</td>
									<td>技能个数</td>
									<td>天赋技能1名称</td>
									<td>天赋技能2名称</td>
									<td>通用技能名称等级</td>
									<td>玉佩名称等级</td>
									<td>明珠名称星级</td>
									<td>护符名称星级</td>
									<td>令牌名称星级</td>
									<td>宝镜名称星级</td>
									
								</tr>
							</tbody>
						</table>
					</div>
					
					<div style="margin-top:20px">
						<table  class="mytable" cellspacing="0" align="center" >
							<thead>
								<tr>
									<th colspan='8'>
										<span class="b_sq">背包信息</span>
										<span class="sqzk" id="bagopen"><img height="20px" src='<?php echo $this->_tpl_vars['res']; ?>
/images/zk.png'/></span>
										<span class="sqzk" id="bagclose" style="display:none"><img height="20px" src='<?php echo $this->_tpl_vars['res']; ?>
/images/sq.png'/></span>
									</th>
								</tr>
							</thead>
							<tbody id="bag" style="display:none"> 
								<tr class="tblod" >
									<td>道具ID</td>
									<td>道具名</td>
									<td style="width:30px">品质</td>
									<td>数量</td>
									<td>绑定/未绑定</td>
									<td>位置</td>
									<td>强化级别</td>
									<td>获得时间</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div style="margin-top:20px">
						<table  class="mytable" cellspacing="0" align="center" >
							<thead>
								<tr>
									<th colspan='16'>
										<span class="b_sq">坐骑信息</span>
										<span class="sqzk" id="zjopen"><img  height="20px" src='<?php echo $this->_tpl_vars['res']; ?>
/images/zk.png'/></span>
										<span class="sqzk" id="zjclose" style="display:none"><img  height="20px" src='<?php echo $this->_tpl_vars['res']; ?>
/images/sq.png'/></span>
									</th>
								</tr>
							</thead>
							<tbody id="zjbody" style="display:none"> 
								<tr class="tblod" >
									<td>坐骑等级</td>
									<td>坐骑技能总和</td>
									<td >践踏等级</td>
									<td>冲锋等级</td>
									<td>千里奔袭等级</td>
									<td>疾风破等级</td>
									<td>惊雷破等级</td>
									<td>天炎破等级</td>
									<td>金戈铁马等级</td>
									<td>八门金锁等级</td>
									<td>一气化千等级</td>
									<td>疾行等级</td>
									<td>归行等级</td>
									<td>御风等级</td>
									<td>幻化个数</td>
									<td>内丹等级</td>
								</tr>
							</tbody>
						</table>
					</div>
					
					<div style="margin-top:20px">
						<table  class="mytable" cellspacing="0" align="center" >
							<thead>
								<tr>
									<th colspan="8">
										<span class="b_sq">仓库信息</span>
										<span class="sqzk" id="ckopen"><img  height="20px" src='<?php echo $this->_tpl_vars['res']; ?>
/images/zk.png'/></span>
										<span class="sqzk" id="ckclose" style="display:none"><img height="20px" src='<?php echo $this->_tpl_vars['res']; ?>
/images/sq.png'/></span>
									</th>
								</tr>
							</thead>
							<tbody id="storage" style="display:none"> 
								<tr class="tblod" >
									<td>道具ID</td>
									<td>道具名</td>
									<td style="width:30px">品质</td>
									<td>数量</td>
									<td>绑定/未绑定</td>
									<td>位置</td>
									<td>强化级别</td>
									<td>获得时间</td>
								</tr>
							</tbody>
						</table>
					</div>
					
					<div style="margin-top:20px">
						<table  class="mytable" cellspacing="0" align="center" >
							<thead>
								<tr>
									<th colspan="18">
										<span class="b_sq">战斗属性</span>
										<span class="sqzk" id="btopen"><img  height="20px" src='<?php echo $this->_tpl_vars['res']; ?>
/images/zk.png'/></span>
										<span class="sqzk" id="btclose" style="display:none"><img height="20px" src='<?php echo $this->_tpl_vars['res']; ?>
/images/sq.png'/></span>
									</th>
								</tr>
							</thead>
							<tbody id="battle" style="display:none"> 
								<tr class="tblod" >
									<td>战力</td>
									<td>生命值</td>
									<td >法力值</td>
									<td>物理攻击</td>
									<td>物理防御</td>
									<td>法术防御</td>
									<td>法术攻击</td>
									<td>力量</td>
									<td>敏捷</td>
									<td>体质</td>
									<td>智力</td>
									<td>精神</td>
									<td>命中</td>
									<td>闪避</td>
									<td>暴击</td>
									<td>神兵灵魄</td>
									<td>坚韧</td>
									<td>减伤</td>
								</tr>
							</tbody>
						</table>
					</div>
					
					<div style="margin-top:20px">
						<table  class="mytable" cellspacing="0" align="center" >
							<thead>
								<tr>
									<th colspan="9">
										<span class="b_sq">星宿信息</span>
										<span class="sqzk" id="xxopen"><img  height="20px" src='<?php echo $this->_tpl_vars['res']; ?>
/images/zk.png'/></span>
										<span class="sqzk" id="xxclose" style="display:none"><img height="20px" src='<?php echo $this->_tpl_vars['res']; ?>
/images/sq.png'/></span>
									</th>
								</tr>
							</thead>
							<tbody id="stars" style="display:none"> 
								<tr class="tblod" >
									<td>星宿点亮最高星级</td>
									<td>命格一星等级</td>
									<td >命格二星等级</td>
									<td>命格三星等级</td>
									<td>命格四星等级</td>
									<td>命格五星等级</td>
									<td>命格六星等级</td>
									<td>命格七星等级</td>
									<td>命格八星等级</td>
									
								</tr>
							</tbody>
						</table>
					</div>
					
					<div style="margin-top:20px">
						<table  class="mytable" cellspacing="0" align="center" >
							<thead>
								<tr>
									<th colspan="9">
										<span class="b_sq">神兵信息</span>
										<span class="sqzk" id="sbopen"><img  height="20px" src='<?php echo $this->_tpl_vars['res']; ?>
/images/zk.png'/></span>
										<span class="sqzk" id="sbclose" style="display:none"><img height="20px" src='<?php echo $this->_tpl_vars['res']; ?>
/images/sq.png'/></span>
									</th>
								</tr>
							</thead>
							<tbody id="magic" style="display:none"> 
								<tr class="tblod" >
									<td>神兵等级</td>
									<td>赑屃灵件1注灵进度</td>
									<td>赑屃灵件2注灵进度</td>
									<td>螭吻灵件1注灵进度</td>
									<td>螭吻灵件2注灵进度</td>
									<td>睚眦灵件1注灵进度</td>
									<td>睚眦灵件2注灵进度</td>
									<td>当前命脉</td>
									<td>神兵灵魄</td>
									
								</tr>
							</tbody>
						</table>
					</div>
					
					<div style="margin-top:20px">
						<table  class="mytable" cellspacing="0" align="center" >
							<thead>
								<tr>
									<th colspan="5">
										<span class="b_sq">国家谋略</span>
										<span class="sqzk" id="mlopen"><img  height="20px" src='<?php echo $this->_tpl_vars['res']; ?>
/images/zk.png'/></span>
										<span class="sqzk" id="mlclose" style="display:none"><img height="20px" src='<?php echo $this->_tpl_vars['res']; ?>
/images/sq.png'/></span>
									</th>
								</tr>
							</thead>
							<tbody id="strategy" style="display:none"> 
								<tr class="tblod" >
									<td>生命增强等级</td>
									<td>命中增强等级</td>
									<td>闪避增强等级</td>
									<td>暴击增强等级</td>
									<td>坚韧增强等级</td>
									
								</tr>
							</tbody>
						</table>
					</div>
				</div>	
			</div>
			<div style="clear:both"></div>
			<input type="hidden" id="last_ip"/>
			<input type="hidden" id="sum_sec"/>
		</div>
		<input type="hidden" id="roleid" value=""/>
	</div>
	
<!-- 禁言弹出框 -->	
<div id="jyform"  style="display:none">
	<div class="ajaxform">
		<table width="80%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
			<tbody>
				<tr>
					<td align="right" width="35%">角色名：</td>
					<td width="65%">
						<span id="rname"></span>
					</td>
				</tr>
				<tr>
					<td align="right" width="35%">禁言时长：</td>
					<td width="65%">
						<select id="stoptime">
							<option value="315360000">永久</option>
							<option value="60">1分钟</option>
							<option value="300">5分钟</option>
							<option value="600">10分钟</option>
							<option value="1800">30分钟</option>
							<option value="3600">1小时</option>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right" width="35%">操作原因：</td>
					<td width="65%">
						<textarea class="input1" cols="20" rows="6" id="reason">玩家因发布不文明信息，禁言</textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<!-- 解禁弹出框 -->	
<div id="jjform"  style="display:none">
	<div class="ajaxform">
		<table width="80%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
			<tbody>
				<tr>
					<td align="right" width="35%">角色名：</td>
					<td width="65%">
						<span id="rname11"></span>
					</td>
				</tr>
				<tr>
					<td align="right" width="35%">操作原因：</td>
					<td width="65%">
						<textarea class="input1" cols="20" rows="6" id="reason11">解禁</textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<!-- 冻结弹出框 -->	
<div id="djform"  style="display:none">
	<div class="ajaxform">
		<table width="80%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
			<tbody>
				<tr>
					<td align="right" width="35%">角色名：</td>
					<td width="65%">
						<span id="rname2"></span>
					</td>
				</tr>
				<tr>
					<td align="right" width="35%">冻结时长：</td>
					<td width="65%">
						<select id="freezetime">
							<option value="315360000">永久</option>
							<option value="60">1分钟</option>
							<option value="300">5分钟</option>
							<option value="600">10分钟</option>
							<option value="1800">30分钟</option>
							<option value="3600">1小时</option>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right" width="35%">操作原因：</td>
					<td width="65%">
						<textarea class="input1" cols="20" rows="6" id="freezereason">玩家开挂，冻结</textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<!-- 解冻弹出框 -->	
<div id="jdform"  style="display:none">
	<div class="ajaxform">
		<table width="80%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
			<tbody>
				<tr>
					<td align="right" width="35%">角色名：</td>
					<td width="65%">
						<span id="rname22"></span>
					</td>
				</tr>
				<tr>
					<td align="right" width="35%">操作原因：</td>
					<td width="65%">
						<textarea class="input1" cols="20" rows="6" id="freezereason22">解冻</textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>


<!-- 强制下线弹出框 -->	
<div id="xxform"  style="display:none">
	<div class="ajaxform">
		<table width="80%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
			<tbody>
				<tr>
					<td align="right" width="35%">角色名：</td>
					<td width="65%">
						<span id="rname3"></span>
					</td>
				</tr>
				<tr>
					<td align="right" width="35%">操作原因：</td>
					<td width="65%">
						<textarea class="input1" cols="20" rows="6" id="offreason">玩家因发布不文明信息，强制下线</textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<!-- 登录弹出框 -->	
<div id="loform"  style="display:none">
	<div class="ajaxform">
		<table width="80%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
			<tbody>
				<tr>
					<td align="right" width="35%">角色名：</td>
					<td width="65%">
						<span id="loname"></span>
					</td>
				</tr>
				<tr>
					<td align="right" width="35%">操作原因：</td>
					<td width="65%">
						<textarea class="input1" cols="20" rows="6" id="loginreason">问题账号</textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<!-- 服务器 -->
	<div id="dform"  style="display:none">
		<div class="ajaxform">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
				
				<?php $_from = $this->_tpl_vars['ipList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['ip']):
?>
					<?php if ($this->_tpl_vars['key']%5 == '0'): ?>
						<br/><br/>
					<?php endif; ?>
						<input type="radio" name="db" value='<?php echo $this->_tpl_vars['ip']['s_id']; ?>
'  class="cbox"/><span><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php endforeach; endif; unset($_from); ?>
			</table>
		</div>
	</div>
<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery-ui.js" type="text/javascript"></script> 
<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/function.js" type="text/javascript"></script>
<script type="text/javascript">
	var flag = false;//全局变量，防止重复提交
	
	showTitle("玩家数据查询:用户查询");
	
	//查询
	$("#querybtn").click(function(){
		$("#tabs-2").hide();
		$("#tabs-1").show();
		getdata(1);
	})
	
	$("#sip").change(function() {
		if($("#sip").val() == 0){
			$("#dform").dialog({
				height: 500,
				width: 700,
				buttons :{
					"确认": function(){
						var item = $(':radio[name="db"]:checked').val();
						$("#sip").val(item);
						$(this).dialog("close");
					},
					"关闭" : function(){
						$(this).dialog("close");
					}
				}
			})
		}
	})

	//table单双行交叉样式
	$(".mytable tr:odd").css("background-color", "#edf2f7"); 
	$(".mytable tr:even").css("background-color","#e0f0f0"); 

	//获取玩家详细信息
	var getDetail = function(id){
		var ip = $("#sip").val();
		var lastIp = $("#last_ip").val();
		var sumsec = $("#sum_sec").val();
		$.ajax({
			type:"GET",
			url:"<?php echo $this->_tpl_vars['logicApp']; ?>
/gminfo/getDetailInfo",
			data:{
				ip : ip,
				gid : id
			},
			cache : false,
			async : false,
			dataType:"json",
			beforeSend:function(){
				$("#detailInfo").html("<td colspan='15'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td>");
				$("#detailInfo2").html("<td colspan='15'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td>");
			},
			success:function(data){
		
				var  dhtml = "";
				dhtml += "<td id='code'>"+data.player["accountCode"]+"</td>";
				dhtml += "<td>"+data.player["id"]+"</td>";
				dhtml += "<td id='roleName'>"+data.player["name"]+"</td>";
				dhtml += "<td>"+data.player["sex"]+"</td>";
				dhtml += "<td>"+data.player["level"]+"</td>";
				dhtml += "<td>"+data.player["viplevel"]+"</td>";
				dhtml += "<td>"+data.player["profession"]+"</td>";
				dhtml += "<td>"+data.player["guildid"]+"</td>";
				dhtml += "<td>"+data.player["attack"]+"</td>";
				dhtml += "<td>"+data.player["mapId"]+"</td>";
				dhtml += "<td>"+data.player["recharge"]+"</td>";
				dhtml += "<td>"+data.player["gold"]+"</td>";
				dhtml += "<td>"+data.player["bindGold"]+"</td>";
				dhtml += "<td>"+data.player["coin"]+"</td>";
				dhtml += "<td>"+data.player["bindcoin"]+"</td>";
				$("#detailInfo").html(dhtml);
				
				var dhtml2 = "";
				dhtml2 += "<td colspan='2'>"+data.player["createTime"]+"</td>";
				dhtml2 += "<td colspan='2'>"+data.player["lastOnTime"]+"</td>";
				dhtml2 += "<td colspan='2'>"+lastIp+"</td>";
				dhtml2 += "<td colspan='2'>"+sumsec+"</td>";
				dhtml2 += "<td id='isOnLine'>"+data.player["isOnline"]+"</td>";
				dhtml2 += "<td>"+data.player["accountState"]+"</td>";
				dhtml2 += "<td colspan='5'>&nbsp;</td>";
				$("#detailInfo2").html(dhtml2);
				 
				//玩家装备信息
				if(typeof(data.equips) != 'undefined'){
					for(var i in data.equips){
						var body = data.equips[i]["body"];
						$("#name_"+body).html(data.equips[i]["name"]);
						$("#clevel_"+body).html(data.equips[i]["enLvl"]);
					}
				}
			},
			error:function(data){
				$("#detailInfo2").html("<td colspan='15'>没有数据!</td>");
				$("#detailInfo").html("<td colspan='15'>没有数据!</td>");
			}
		});
	}

	//获取背包信息
	var getBag = function(id){
		var ip = $("#sip").val();
		$.ajax({
			type:"GET",
			url:"<?php echo $this->_tpl_vars['logicApp']; ?>
/gminfo/getBagInfo",
			data:{
				ip : ip,
				gid : id
			},
			async:true,
			dataType:"json",
			beforeSend:function(){
				$("#bag tr").not(":first").remove();		//清除table，防止不断叠加
				$("#bag tr:last").after("<tr><td colspan='15'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
			},
			success:function(data){
				$("#bag tr").not(":first").remove();		//清除table，防止不断叠加
				if(typeof(data.bag) != 'undefined' && data.bag.length > 0){
					var html = "";
					var bag = data.bag;
					var tool = data.tool;
					for(var i in bag){
						if(bag[i]["name"]){
							if(bag[i]["Bind"] == true){
								var bind = '绑定';
							}else{
								var bind = '未绑定';
							}
		
							html += "<tr>";
							html += "<td>"+bag[i]["TemplateID"]+"</td>";
							html += "<td>"+bag[i]["name"]+"</td>";
							html += "<td>"+bag[i]["quality"]+"</td>";
							html += "<td>"+bag[i]["Count"]+"</td>";
							html += "<td>"+bind+"</td>";
							html += "<td>"+bag[i]["CellID"]+"</td>";
							html += "<td>"+0+"</td>";
							html += "<td>"+bag[i]["DataVersion"]+"</td>";
							html += "</tr>";
						}
					}
					$("#bag tr:last").after(html);
				} else {
					$("#bag tr:last").after("<tr><td colspan='15'>没有数据!</td></tr>");
				}
			},
			error:function(data){
				$("#bag tr:last").after("<tr><td colspan='15'>没有数据!</td></tr>");
			}
		});
	}
	
	//获取坐骑信息
	var getHorse = function(id){
		var ip = $("#sip").val();
		$.ajax({
			type:"GET",
			url:"<?php echo $this->_tpl_vars['logicApp']; ?>
/gminfo/getHorseInfo",
			data:{
				ip : ip,
				gid : id
			},
			async:true,
			dataType:"json",
			beforeSend:function(){
				$("#zjbody tr").not(":first").remove();		//清除table，防止不断叠加
				$("#zjbody tr:last").after("<tr><td colspan='15'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
			},
			success:function(data){
				$("#zjbody tr").not(":first").remove();		//清除table，防止不断叠加
				if(typeof(data.horse) != 'undefined' ){
					var html = "";
					var horse = data.horse;
					for(var i in horse){
						
						html += "<tr>";
						if(horse[i]["level"]){
						html += "<td>"+horse[i]["level"]+"阶</td>";
						}else{
							html += "<td>"+"1"+"</td>";
						}
						if(horse[i]["count"]){
						html += "<td>"+horse[i]["count"]+"</td>";
						}else{
							html += "<td>"+"0"+"</td>";
						}
						if(horse[i]["qtlevel"]){
						html += "<td>"+horse[i]["qtlevel"]+"</td>";
						}else{
							html += "<td>"+"0"+"</td>";
						}
						if(horse[i]["cflevel"]){
						html += "<td>"+horse[i]["cflevel"]+"</td>";
						}else{
							html += "<td>"+"0"+"</td>";
						}
						if(horse[i]["qllevel"]){
						html += "<td>"+horse[i]["qllevel"]+"</td>";
						}else{
							html += "<td>"+"0"+"</td>";
						}
						if(horse[i]["jflevel"]){
						html += "<td>"+horse[i]["jflevel"]+"</td>";
						}else{
							html += "<td>"+"0"+"</td>";
						}
						if(horse[i]["jllevel"]){
						html += "<td>"+horse[i]["jllevel"]+"</td>";
						}else{
							html += "<td>"+"0"+"</td>";
						}
						if(horse[i]["thlevel"]){
						html += "<td>"+horse[i]["thlevel"]+"</td>";
						}else{
							html += "<td>"+"0"+"</td>";
						}
						if(horse[i]["jglevel"]){
						html += "<td>"+horse[i]["jglevel"]+"</td>";
						}else{
							html += "<td>"+"0"+"</td>";
						}
						if(horse[i]["bmlevel"]){
						html += "<td>"+horse[i]["bmlevel"]+"</td>";
						}else{
							html += "<td>"+"0"+"</td>";
						}
						if(horse[i]["yqlevel"]){
						html += "<td>"+horse[i]["yqlevel"]+"</td>";
						}else{
							html += "<td>"+"0"+"</td>";
						}
						if(horse[i]["jxlevel"]){
						html += "<td>"+horse[i]["jxlevel"]+"</td>";
						}else{
							html += "<td>"+"0"+"</td>";
						}
						if(horse[i]["gylevel"]){
						html += "<td>"+horse[i]["gylevel"]+"</td>";
						}else{
							html += "<td>"+"0"+"</td>";
						}
						if(horse[i]["yqlevel"]){
						html += "<td>"+horse[i]["yqlevel"]+"</td>";
						}else{
							html += "<td>"+"0"+"</td>";
						}
						if(horse[i]["skn"]){
							html += "<td>"+horse[i]["skn"]+"</td>";
						}else{
							html += "<td>"+"0"+"</td>";
						}
						if(horse[i]["nd"]){
							html += "<td>"+horse[i]["nd"]+"</td>";
						}else{
							html += "<td>"+"0"+"</td>";
						}
						html += "</tr>";
					}
					$("#zjbody tr:last").after(html);
				} else {
					$("#zjbody tr:last").after("<tr><td colspan='16'>没有数据!</td></tr>");
				}
			},
			error:function(data){
				$("#zjbody tr:last").after("<tr><td colspan='16'>没有数据!</td></tr>");
			}
		});
	}
	
	//获取仓库信息
	var getstorage = function(id){
		var ip = $("#sip").val();
		$.ajax({
			type:"GET",
			url:"<?php echo $this->_tpl_vars['logicApp']; ?>
/gminfo/getdepotInfo",
			data:{
				ip : ip,
				gid : id
			},
			async:true,
			dataType:"json",
			beforeSend:function(){
				$("#storage tr").not(":first").remove();		//清除table，防止不断叠加
				$("#storage tr:last").after("<tr><td colspan='15'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
			},
			success:function(data){
				$("#storage tr").not(":first").remove();		//清除table，防止不断叠加
				if(typeof(data.bag) != 'undefined' && data.bag.length > 0){
					var html = "";
					var bag = data.bag;
					var tool = data.tool;
					for(var i in bag){
						switch(bag[i]["bind"]){
							case 0: bag[i]["bind"] = "绑定";break;
							case 1: bag[i]["bind"] = "未绑定";break;
							default: bag[i]["bind"] = "未知";
						}
	
						html += "<tr>";
						html += "<td>"+bag[i]["TemplateID"]+"</td>";
						if(typeof(tool[bag[i]["TemplateID"]]) != 'undefined') {
							html += "<td>"+tool[bag[i]["TemplateID"]]+"</td>";
						} else {
							html += "<td >"+"未放物品"+"</td>";
						}
						html += "<td >"+"无"+"</td>";
						html += "<td>"+bag[i]["Count"]+"</td>";
						if(bag[i]["Bind"] == true){
							html += "<td>"+"绑定"+"</td>";
						}else{
							html += "<td>"+"未绑定"+"</td>";
						}
						html += "<td>"+bag[i]["CellID"]+"</td>";
						if(bag[i]["StrengthenNum"]){
							html += "<td>"+bag[i]["StrengthenNum"]+"</td>";
						}else{
							html += "<td>"+"0"+"</td>";
						}
						html += "<td>"+bag[i]["DataVersion"]+"</td>";
						html += "</tr>";
					}
					$("#storage tr:last").after(html);
				} else {
					$("#storage tr:last").after("<tr><td colspan='8'>没有数据!</td></tr>");
				}
			},
			error:function(data){
				$("#storage tr:last").after("<tr><td colspan='8'>没有数据!</td></tr>");
			}
		});
	}
	
	//获取星宿信息
	var getstars = function(id){
		var ip = $("#sip").val();
		$.ajax({
			type:"GET",
			url:"<?php echo $this->_tpl_vars['logicApp']; ?>
/gminfo/getstarsInfo",
			data:{
				ip : ip,
				gid : id
			},
			async:true,
			dataType:"json",
			beforeSend:function(){
				$("#stars tr").not(":first").remove();		//清除table，防止不断叠加
				$("#stars tr:last").after("<tr><td colspan='15'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
			},
			success:function(data){
				$("#stars tr").not(":first").remove();		//清除table，防止不断叠加
				if(typeof(data.bag) != 'undefined' && data.bag.length > 0){
					var html = "";
					var bag = data.bag;
					var tool = data.tool;
					for(var i in bag){
						html += "<tr>";
						html += "<td >"+bag[i]["max"]+"</td>";
						html += "<td>"+bag[i]["one"]+"</td>";
						html += "<td>"+bag[i]["two"]+"</td>";
						html += "<td>"+bag[i]["three"]+"</td>";
						html += "<td>"+bag[i]["four"]+"</td>";
						html += "<td>"+bag[i]["five"]+"</td>";
						html += "<td>"+bag[i]["six"]+"</td>";
						html += "<td>"+bag[i]["seven"]+"</td>";
						html += "<td>"+bag[i]["eight"]+"</td>";
						html += "</tr>";
					}
					$("#stars tr:last").after(html);
				} else {
					$("#stars tr:last").after("<tr><td colspan='9'>没有数据!</td></tr>");
				}
			},
			error:function(data){
				$("#stars tr:last").after("<tr><td colspan='9'>没有数据!</td></tr>");
			}
		});
	}
	
	//获取神兵信息
	var getmagic = function(id){
		var ip = $("#sip").val();
		$.ajax({
			type:"GET",
			url:"<?php echo $this->_tpl_vars['logicApp']; ?>
/gminfo/getmagicInfo",
			data:{
				ip : ip,
				gid : id
			},
			async:true,
			dataType:"json",
			beforeSend:function(){
				$("#magic tr").not(":first").remove();		//清除table，防止不断叠加
				$("#magic tr:last").after("<tr><td colspan='15'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
			},
			success:function(data){
				$("#magic tr").not(":first").remove();		//清除table，防止不断叠加
				if(typeof(data.bag) != 'undefined' && data.bag.length > 0){
					var html = "";
					var bag = data.bag;
					var tool = data.tool;
					for(var i in bag){
						if(typeof(bag[i]['one']) != 'undefined'){
							var one = bag[i]['one'];
						}else{
							var one = 0;
						}if(typeof(bag[i]['two']) != 'undefined'){
							var two = bag[i]['two'];
						}else{
							var two = 0;
						
						}if(typeof(bag[i]['three']) != 'undefined'){
							var three = bag[i]['three'];
						}else{
							var three = 0;
						
						}if(typeof(bag[i]['four']) != 'undefined'){
							var four = bag[i]['four'];
						}else{
							var four = 0;
						
						}if(typeof(bag[i]['five']) != 'undefined'){
							var five = bag[i]['five'];
						}else{
							var five = 0;
						
						}if(typeof(bag[i]['six']) != 'undefined'){
							var six = bag[i]['six'];
						}else{
							var six = 0;
						
						}
						html += "<tr>";
						html += "<td >"+bag[i]['slevel']+"</td>";
						html += "<td >"+ one +"</td>";
						html += "<td >"+ two +"</td>";
						html += "<td >"+ three +"</td>";
						html += "<td >"+ four +"</td>";
						html += "<td >"+ five +"</td>";
						html += "<td >"+ six +"</td>";
						html += "<td >"+bag[i]['mskill']+"</td>";
						html += "<td >"+bag[i]['ling']+"</td>";
						html += "</tr>";
					}
					$("#magic tr:last").after(html);
				} else {
					$("#magic tr:last").after("<tr><td colspan='9'>没有数据!</td></tr>");
				}
			},
			error:function(data){
				$("#magic tr:last").after("<tr><td colspan='9'>没有数据!</td></tr>");
			}
		});
	}
	
	//获取国家谋略
	var getstrategy = function(id){
		var ip = $("#sip").val();
		$.ajax({
			type:"GET",
			url:"<?php echo $this->_tpl_vars['logicApp']; ?>
/gminfo/getstrategyInfo",
			data:{
				ip : ip,
				gid : id
			},
			async:true,
			dataType:"json",
			beforeSend:function(){
				$("#strategy tr").not(":first").remove();		//清除table，防止不断叠加
				$("#strategy tr:last").after("<tr><td colspan='15'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
			},
			success:function(data){
				$("#strategy tr").not(":first").remove();		//清除table，防止不断叠加
				if(typeof(data.bag) != 'undefined' && data.bag.length > 0){
					var html = "";
					var bag = data.bag;
					var tool = data.tool;
					for(var i in bag){
						html += "<tr>";
						html += "<td >"+bag[i]["sm"]+"</td>";
						html += "<td >"+bag[i]["mz"]+"</td>";
						html += "<td >"+bag[i]["sb"]+"</td>";
						html += "<td >"+bag[i]["bj"]+"</td>";
						html += "<td >"+bag[i]["jr"]+"</td>";
						html += "</tr>";
					}
					$("#strategy tr:last").after(html);
				} else {
					$("#strategy tr:last").after("<tr><td colspan='5'>没有数据!</td></tr>");
				}
			},
			error:function(data){
				$("#strategy tr:last").after("<tr><td colspan='5'>没有数据!</td></tr>");
			}
		});
	}
	
	//获取战斗属性
	var getbattle = function(id){
		var ip = $("#sip").val();
		$.ajax({
			type:"GET",
			url:"<?php echo $this->_tpl_vars['logicApp']; ?>
/gminfo/getbattleInfo",
			data:{
				ip : ip,
				gid : id
			},
			async:true,
			dataType:"json",
			beforeSend:function(){
				$("#battle tr").not(":first").remove();		//清除table，防止不断叠加
				$("#battle tr:last").after("<tr><td colspan='15'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
			},
			success:function(data){
				$("#battle tr").not(":first").remove();		//清除table，防止不断叠加
				if(typeof(data.bag) != 'undefined' && data.bag.length > 0){
					var html = "";
					var bag = data.bag;
					var tool = data.tool;
					for(var i in bag){
						html += "<tr>";
						html += "<td >"+bag[i]["BattlePower"]+"</td>";
						html += "<td >"+bag[i]["HP"]+"</td>";
						html += "<td >"+bag[i]["MP"]+"</td>";
						html += "<td >"+bag[i]["PhyAttack"]+"</td>";
						html += "<td >"+bag[i]["PhyDefense"]+"</td>";
						html += "<td >"+bag[i]["MagicDefense"]+"</td>";
						html += "<td >"+bag[i]["MagicAttack"]+"</td>";
						html += "<td >"+bag[i]["Power"]+"</td>";
						html += "<td >"+bag[i]["Nimble"]+"</td>";
						html += "<td >"+bag[i]["Physical"]+"</td>";
						html += "<td >"+bag[i]["Intelligence"]+"</td>";
						html += "<td >"+bag[i]["Spirit"]+"</td>";
						html += "<td >"+bag[i]["Hit"]+"</td>";
						html += "<td >"+bag[i]["Dodge"]+"</td>";
						html += "<td >"+bag[i]["Cruel"]+"</td>";
						html += "<td >"+bag[i]["GodWakan"]+"</td>";
						html += "<td >"+bag[i]["Tenacity"]+"</td>";
						html += "<td >"+bag[i]["Minus"]+"</td>";
						html += "</tr>";
					}
					$("#battle tr:last").after(html);
				} else {
					$("#battle tr:last").after("<tr><td colspan='18'>没有数据!</td></tr>");
				}
			},
			error:function(data){
				$("#battle tr:last").after("<tr><td colspan='18'>没有数据!</td></tr>");
			}
		});
	}
	
	//获取玩家装备
	var getEquipment = function(id){
		var ip = $("#sip").val();
		$.ajax({
			type:"GET",
			url:"<?php echo $this->_tpl_vars['logicApp']; ?>
/gminfo/getequipmentInfo",
			data:{
				ip : ip,
				gid : id
			},
			async:true,
			dataType:"json",
			beforeSend:function(){
				$("#zbbody tr").not(":first").remove();		//清除table，防止不断叠加
				//$("#zbbody tr:last").after("<tr><td colspan='15'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
			},
			success:function(data){
				$("#battle tr").not(":first").remove();		//清除table，防止不断叠加
				if(typeof(data.bag) != 'undefined' && data.bag.length > 0){
					var html = "";
					var bag = data.bag;
					var tool = data.tool;
					for(var i in bag){
						html += "<tr>";
						html += "<td >"+"武器名称"+"</td>";
						html += "<td >"+bag[i]["arm"]+"</td>";
						html += "<td >"+bag[i]["head"]+"</td>";
						html += "<td >"+bag[i]["clothes"]+"</td>";
						html += "<td >"+bag[i]["belt"]+"</td>";
						html += "<td >"+bag[i]["necklace"]+"</td>";
						html += "<td >"+bag[i]["necklace2"]+"</td>";
						html += "<td >"+bag[i]["bracers"]+"</td>";
						html += "<td >"+bag[i]["ring"]+"</td>";
						html += "<td >"+bag[i]["ring2"]+"</td>";
						html += "<td >"+bag[i]["shoes"]+"</td>";
						html += "<td >"+bag[i]["trousers"]+"</td>";
						html += "<td >"+bag[i]["weaponsfashion"]+"</td>";
						html += "<td >"+bag[i]["clothesfashion"]+"</td>";
						html += "</tr>";
						html += "<tr>";
						html += "<td >"+"强化等级"+"</td>";
						html += "<td >"+bag[i]["armlevel"]+"</td>";
						html += "<td >"+bag[i]["headlevel"]+"</td>";
						html += "<td >"+bag[i]["clotheslevel"]+"</td>";
						html += "<td >"+bag[i]["beltlevel"]+"</td>";
						html += "<td >"+bag[i]["necklacelevel"]+"</td>";
						html += "<td >"+bag[i]["necklace2level"]+"</td>";
						html += "<td >"+bag[i]["bracerslevel"]+"</td>";
						html += "<td >"+bag[i]["ringlevel"]+"</td>";
						html += "<td >"+bag[i]["ring2level"]+"</td>";
						html += "<td >"+bag[i]["shoeslevel"]+"</td>";
						html += "<td >"+bag[i]["trouserslevel"]+"</td>";
						html += "<td >"+bag[i]["weaponsfashionlevel"]+"</td>";
						html += "<td >"+bag[i]["clothesfashionlevel"]+"</td>";
						html += "</tr>";
					}
					$("#zbbody tr:last").after(html);
				} else {
					$("#zbbody tr:last").after("<tr><td colspan='18'>没有数据!</td></tr>");
				}
			},
			error:function(data){
				$("#zbbody tr:last").after("<tr><td colspan='18'>没有数据!</td></tr>");
			}
		});
	}
	
	//获取武将属性
	var getpetlist = function(id){
		var ip = $("#sip").val();
		$.ajax({
			type:"GET",
			url:"<?php echo $this->_tpl_vars['logicApp']; ?>
/gminfo/getpetInfo",
			data:{
				ip : ip,
				gid : id
			},
			async:true,
			dataType:"json",
			beforeSend:function(){
				$("#generals tr").not(":first").remove();		//清除table，防止不断叠加
				$("#generals tr:last").after("<tr><td colspan='15'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
			},
			success:function(data){
				$("#generals tr").not(":first").remove();		//清除table，防止不断叠加
				if(typeof(data.bag) != 'undefined' && data.bag.length > 0){
					var html = "";
					var bag = data.bag;
					var tool = data.tool;
					for(var i in bag){
						if(bag[i]["gif1"] != '无'){
							var gif1 = bag[i]["gif1"]+"--"+bag[i]["gif1level"]+"级";
							var c1 = 1;
						}else{
							var gif1 = '无';
							var c1 = 0;
						}
						if(bag[i]["gif2"] != '无'){
							var gif2 = bag[i]["gif2"]+"--"+bag[i]["gif2level"]+"级";
							var c2 = 1;
						}else{
							var gif2 = '无';
							var c2 = 0;
						}
						var tagif = c1 + c2;
						html += "<tr>";
						html += "<td >"+bag[i]["NO"]+"</td>";
						html += "<td >"+bag[i]["templateid"]+"</td>";
						html += "<td >"+bag[i]["Level"]+"</td>";
						html += "<td >"+bag[i]["BattlePower"]+"</td>";
						html += "<td >"+bag[i]["TrunCount"]+"</td>";
						html += "<td >"+bag[i]["Aptitude"]+"</td>";
						if(bag[i]["Grow"]){
							html += "<td >"+bag[i]["Grow"]+"</td>";
						}else{
							html += "<td >"+"0"+"</td>";
						}
						html += "<td >"+bag[i]["HP"]+"</td>";
						html += "<td >"+bag[i]["PhyAttack"]+"</td>";
						html += "<td >"+bag[i]["PhyDefense"]+"</td>";
						html += "<td >"+bag[i]["MagicAttack"]+"</td>";
						html += "<td >"+bag[i]["MagicDefense"]+"</td>";
						html += "<td >"+bag[i]["Hit"]+"</td>";
						html += "<td >"+bag[i]["Dodge"]+"</td>";
						html += "<td >"+bag[i]["Cruel"]+"</td>";
						html += "<td >"+bag[i]["DeCruel"]+"</td>";
						html += "<td >"+bag[i]["Power"]+"</td>";
						html += "<td >"+bag[i]["Nimble"]+"</td>";
						html += "<td >"+bag[i]["Physical"]+"</td>";
						html += "<td >"+bag[i]["Intelligence"]+"</td>";
						html += "<td >"+bag[i]["Spirit"]+"</td>";
						if( bag[i]["b_oneL"]){
							
							html += "<td >"+bag[i]["bspf"]*10+"</td>";
						}else{
							html += "<td >"+"0"+"</td>";
						}
						if( bag[i]["b_oneL"]){
							html += "<td >"+bag[i]["b_oneL"]+"级</td>";
						}else{
							
							//html += "<td >"+bag[i]["book"]+'--'+bag[i]["nLevel"]+"级</td>";
							html += "<td >"+"0"+"级</td>";
						}
						html += "<td >"+tagif+"</td>";
						html += "<td >"+gif1+"</td>";
						html += "<td >"+gif2+"</td>";
						html += "<td >"+bag[i]["bskill1"]+"</td>";
						if(bag[i]["ypei"]){
							html += "<td >"+bag[i]["ypei"]+bag[i]["ypeiL"]+"级</td>";
						}else{
							html += "<td >"+"0"+"</td>";
						}
						if(bag[i]["mzhu"]){
							html += "<td >"+bag[i]["mzhu"]+bag[i]["mzhuL"]+"级</td>";
						}else{
							html += "<td >"+"0"+"</td>";
						}
						if(bag[i]["hfu"]){
							html += "<td >"+bag[i]["hfu"]+bag[i]["hfuL"]+"级</td>";
						}else{
							html += "<td >"+"0"+"</td>";
						}
						if(bag[i]["lpai"]){
							html += "<td >"+bag[i]["lpai"]+bag[i]["lpaiL"]+"级</td>";
						}else{
							html += "<td >"+"0"+"</td>";
						}
						if(bag[i]["bjing"]){
							html += "<td >"+bag[i]["bjing"]+bag[i]["bjingL"]+"级</td>";
						}else{
							html += "<td >"+"0"+"</td>";
						}
						html += "</tr>";
					}
					$("#generals tr:last").after(html);
				} else {
					$("#generals tr:last").after("<tr><td colspan='32'>没有数据!</td></tr>");
				}
			},
			error:function(data){
				$("#generals tr:last").after("<tr><td colspan='32'>没有数据!</td></tr>");
			}
		});
	}


	//更多
	$(".more").live("click",function(){
		//背包默认收起
		$("#bagclose").hide();
		$("#bagopen").show();
		$("#bag").hide();
		
		//坐骑默认收起
		$("#zjopen").show();
		$("#zjclose").hide();
		$("#zjbody").hide();
		
		//仓库默认收起
		$("#ckopen").show();
		$("#ckclose").hide();
		$("#storage").hide();
		
		//武将默认收起
		$("#wjopen").show();
		$("#wjclose").hide();
		$("#generals").hide();
		
		//战斗默认收起
		$("#btopen").show();
		$("#btclose").hide();
		$("#battle").hide();
		
		//星宿默认收起
		$("#xxopen").show();
		$("#xxclose").hide();
		$("#stars").hide();
		
		//神兵默认收起
		$("#sbopen").show();
		$("#sbclose").hide();
		$("#magic").hide();
		
		//国家默认收起
		$("#mlopen").show();
		$("#mlclose").hide();
		$("#strategy").hide();
		
		//玩家装备默认收起
		$("#zbbody").hide();
		$("#zbclose").hide();
		$("#zbopen").show();
		
		var id = $(this).parent().attr("id");
		var lastIp = $(this).find("span").attr("id");
		var sumsec = $(this).find("span").attr("sec");
		$("#last_ip").val(lastIp);
		$("#sum_sec").val(sumsec);
		$("#roleid").val(id);
		$("#tabs-1").hide();
		$("#tabs-2").show();
		getDetail(id);
	})

	//快捷禁言
	$("#jybt").click(function(){
		if($("#isOnLine").html() == "不在线"){
			alert("当前玩家不在线，无法禁言");
			return false;
		}
		var id = $("#roleid").val();
		var rolename = $("#roleName").html();
		var ip = $("#sip").val();
		var stoptime = $("#stoptime").val();
		var reason = $("#reason").val();
		var time = Date.parse(new Date());
		$("#rname").html(rolename);
		$("#jyform").dialog({
			height: 300,
			width: 500,
			buttons: {
				'确定' : function(){
					$.ajax({
						type  : "post",
						url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmoperate/stoptalk",
						data :
						{
							ip : ip,
							rolename : rolename,
							stoptime :stoptime,
							reason : reason,
							time : time
						},
						dataType : 'json',
						success : function(data){
							if(typeof(data.error) != 'undefined')
							{
								if(data.error != '')
								{
									alert(data.error);
								}
							}else{
								alert("禁言成功");
								$("#jyform").dialog("close");
								getDetail(id);
							}
						},
						error : function(){
							alert('error');
						}
					})
				},
				'取消' : function(){
					$(this).dialog("close");
				}
			}
		})
	})
	
	//强制登录 
	$("#login").click(function(){
		if($("#isOnLine").html() == "在线"){
			alert("当前玩家在线！");
			return false;
		}
		var id = $("#code").html();
		var rolename = $("#roleName").html();
		var ip = $("#sip").val();
		var reason = $("#loginreason").val();
		$("#loname").html(rolename);
		$("#loform").dialog({
			height: 300,
			width: 500,
			buttons: {
				'确定' : function(){
					$.ajax({
						type  : "post",
						//url : "../../../login_sign.php",
						url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/login_sign/gmlogin",
						data :
						{
							ip : ip,
							id : id,
							rolename : rolename,
							reason : reason
						},
						cache : false,
						dataType : 'json',
						success : function(data){
							// window.location.href="http://cswm.aofyx.com/test/login.php?"+data;
							 window.open("http://cswm.aofyx.com/test/login.php?"+data);
							},
						error : function(){
							alert('error');
						}
					})
				},
				'取消' : function(){
					$(this).dialog("close");
				}
			}
		})	
	})	

	//快捷解禁
	$("#jjbt").click(function(){
		if($("#isOnLine").html() == "不在线"){
			alert("当前玩家不在线，无法解禁");
			return false;
		}
		var id = $("#roleid").val();
		var rolename = $("#roleName").html();
		var ip = $("#sip").val();
		var reason = $("#reason11").val();
		$("#rname11").html(rolename);
		$("#jjform").dialog({
			height: 300,
			width: 500,
			buttons: {
				'确定' : function(){
					$.ajax({
						type  : "post",
						url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmoperate/allowtalk",
						data :
						{
							ip : ip,
							rolename : rolename,
							reason : reason
						},
						cache : false,
						dataType : 'json',
						success : function(data){
							if(typeof(data.error) != 'undefined')
							{
								if(data.error != '')
								{
									alert(data.error);
								}
							}else{
								alert("解禁成功");
								$("#jjform").dialog("close");
								getDetail(id);
							}
						},
						error : function(){
							alert('error');
						}
					})
				},
				'取消' : function(){
					$(this).dialog("close");
				}
			}
		})
	})

	//快捷冻结
	$("#djbt").click(function(){
		var id = $("#roleid").val();
		var rolename = $("#roleName").html();
		var ip = $("#sip").val();
		var freezetime = $("#freezetime").val();
		var reason = $("#freezereason").val();
		$("#rname2").html(rolename);
		$("#djform").dialog({
			height: 300,
			width: 500,
			buttons: {
				'确定' : function(){
					$.ajax({
						type  : "post",
						url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmoperate/freeze",
						data :
						{
							ip : ip,
							rolename : rolename,
							freezetime :freezetime,
							reason : reason
						},
						cache : false,
						dataType : 'json',
						success : function(data){
							if(typeof(data.error) != 'undefined')
							{
								if(data.error != '')
								{
									alert(data.error);
								}
							}else{
								alert("冻结成功");
								$("#djform").dialog("close");
								getDetail(id);
							}
						},
						error : function(){
							alert('error');
						}
					})
				},
				'取消' : function(){
					$(this).dialog("close");
				}
			}
		})
	})

	//快捷解冻
	$("#jdbt").click(function(){
		var id = $("#roleid").val();
		var rolename = $("#roleName").html();
		var ip = $("#sip").val();
		var reason = $("#freezereason22").val();
		$("#rname22").html(rolename);
		$("#jdform").dialog({
			height: 300,
			width: 500,
			buttons: {
				'确定' : function(){
					$.ajax({
						type  : "post",
						url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmoperate/unfreeze",
						data :
						{
							ip : ip,
							rolename : rolename,
							reason : reason
						},
						cache : false,
						dataType : 'json',
						success : function(data){
							if(typeof(data.error) != 'undefined')
							{
								if(data.error != '')
								{
									alert(data.error);
								}
							}else{
								alert("解冻成功");
								$("#jdform").dialog("close");
								getDetail(id);
							}
						},
						error : function(){
							alert('error');
						}
					})
				},
				'取消' : function(){
					$(this).dialog("close");
				}
			}
		})
	})

	//强制下线 
	$("#xxbt").click(function(){
		if($("#isOnLine").html() == "不在线"){
			alert("当前玩家已经不在线！");
			return false;
		}
		var id = $("#roleid").val();
		var rolename = $("#roleName").html();
		var ip = $("#sip").val();
		var reason = $("#offreason").val();
		$("#rname3").html(rolename);
		$("#xxform").dialog({
			height: 300,
			width: 500,
			buttons: {
				'确定' : function(){
					$.ajax({
						type  : "post",
						url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmoperate/offline",
						data :
						{
							ip : ip,
							rolename : rolename,
							reason : reason
						},
						cache : false,
						dataType : 'json',
						success : function(data){
							if(typeof(data.error) != 'undefined')
							{
								if(data.error != '')
								{
									alert(data.error);
								}
							}else{
								alert("强制下线成功");
								$("#xxform").dialog("close");
								getDetail(id);
							}
						},
						error : function(){
							alert('error');
						}
					})
				},
				'取消' : function(){
					$(this).dialog("close");
				}
			}
		})	
	})	


	//赠送道具
	$("#zsdjbt").click(function(){
		alert("功能还没实现！");
	})


	//背包展开
	$("#bagopen").live("click",function(){
		var id = $("#roleid").val();
		$("#bagopen").hide();
		$("#bagclose").show();
		$("#bag").show();
		getBag(id);
	})

	//背包收起
	/$("#bagclose").live("click",function(){
		$("#bagclose").hide();
		$("#bagopen").show();
		$("#bag").hide();
	}) 

	//玩家装备收起
	$("#zbclose").live("click",function(){
		$("#zbbody").hide();
		$("#zbclose").hide();
		$("#zbopen").show();
	})

	//玩家装备展开
	$("#zbopen").live("click",function(){
		var id = $("#roleid").val();
		$("#zbbody").show();
		$("#zbclose").show();
		$("#zbopen").hide();
		getEquipment(id);
	})

	//玩家坐骑展开
	$("#zjopen").live("click",function(){
		var id = $("#roleid").val();
		$("#zjclose").show();
		$("#zjopen").hide();
		$("#zjbody").show();
		getHorse(id);
	})

	//玩家坐骑收起
	$("#zjclose").live("click",function(){
		$("#zjopen").show();
		$("#zjclose").hide();
		$("#zjbody").hide();
	})

	
	//玩家仓库展开
	$("#ckopen").live("click",function(){
		var id = $("#roleid").val();
		$("#storage").show();
		$("#ckclose").show();
		$("#ckopen").hide();
		getstorage(id);
	})

	//玩家仓库收起
	$("#ckclose").live("click",function(){
		$("#ckopen").show();
		$("#ckclose").hide();
		$("#storage").hide();
	})

	//玩家武将展开
	$("#wjopen").live("click",function(){
		var id = $("#roleid").val();
		$("#generals").show();
		$("#wjclose").show();
		$("#wjopen").hide();
		getpetlist(id);
	})

	//玩家武将收起
	$("#wjclose").live("click",function(){
		$("#wjopen").show();
		$("#wjclose").hide();
		$("#generals").hide();
	})

	//玩家战斗展开
	$("#btopen").live("click",function(){
		var id = $("#roleid").val();
		$("#battle").show();
		$("#btclose").show();
		$("#btopen").hide();
		getbattle(id);
	})

	//玩家战斗收起
	$("#btclose").live("click",function(){
		$("#btopen").show();
		$("#btclose").hide();
		$("#battle").hide();
	})

	//玩家星宿展开
	$("#xxopen").live("click",function(){
		var id = $("#roleid").val();
		$("#stars").show();
		$("#xxclose").show();
		$("#xxopen").hide();
		getstars(id);
	})

	//玩家星宿收起
	$("#xxclose").live("click",function(){
		$("#xxopen").show();
		$("#xxclose").hide();
		$("#stars").hide();
	})

	//玩家神兵展开
	$("#sbopen").live("click",function(){
		var id = $("#roleid").val();
		$("#sbclose").show();
		$("#magic").show();
		$("#sbopen").hide();
		getmagic(id);
	})

	//玩家神兵收起
	$("#sbclose").live("click",function(){
		$("#sbopen").show();
		$("#sbclose").hide();
		$("#magic").hide();
	})

	//玩家国家展开
	$("#mlopen").live("click",function(){
		var id = $("#roleid").val();
		$("#strategy").show();
		$("#mlclose").show();
		$("#mlopen").hide();
		getstrategy(id);
	})

	//玩家国家收起
	$("#mlclose").live("click",function(){
		$("#mlopen").show();
		$("#mlclose").hide();
		$("#strategy").hide();
	})


	

	//获取玩家基本信息
	var getdata = function(page){
		var ip = $("#sip").val();
		var type = $("#type").val();
		var text = $("#qt").val();
		var pageSize = 10;
		var curPage = page;
		var time =  Date.parse(new Date());
		$.ajax({
			type:"GET",
			url:"<?php echo $this->_tpl_vars['logicApp']; ?>
/gminfo/get",
			data:{
				ip : ip,
				type : type,
				text : text,
				pageSize : pageSize,
				curPage : curPage,
				time : time
			},
			dataType:"json",
			beforeSend:function(){
				flag = false;
				$("#infota").html("<tr><td colspan='12'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
			},
			complete:function(data){
				flag = true;
			},
			success:function(data){
				if(typeof(data.error) != 'undefined')//错误
				{
					if(data.error != '')
					{
						$("#infota").html("<tr><td colspan='12'>没有记录！</td></tr>");
						return false;
					}
				 }
				var list = [];
				if(data.plays){
					list = data.plays;
				}
				if(list.length > 0 ){
					$("#infota").html("");	//清除table
					$("#pagehtml").html("");//清除分页 
					var html = "";
					for(var i in list){
						html += "<tr id="+list[i]["id"]+">";
						html += "<td>"+list[i]["id"]+"</td>";
						html +=	"<td>"+list[i]["accountCode"]+"</td>";
						html += "<td>"+list[i]["name"]+"</td>";
						html += "<td>"+list[i]["sex"]+"</td>";
						html += "<td>"+list[i]["level"]+"</td>";
						html += "<td>"+list[i]["profession"]+"</td>";
						html += "<td>"+list[i]["createTime"]+"</td>";
						html +=	"<td>"+list[i]["lastTime"]+"</td>";
						html +=	"<td>"+list[i]["lastIp"]+"</td>";
						html +=	"<td>"+toformat(list[i]["sumSec"])+"</td>";
						html += "<td class='more'>"+"<span style='color:blue;cursor:pointer' id='"+list[i]["lastIp"]+"' sec='"+toformat(list[i]["sumSec"])+"'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/more.gif'/></span>"+"</td>";
						html += "</tr>";
					}
					$("#infota").html(html); 
					$("#pagehtml").html(data.pageHtml);
				}else{
					$("#infota").html("<tr><td colspan='12'>没有记录！</td></tr>");
				}
			},
			error:function(data){
				$("#infota").html("<tr><td colspan='12'>没有记录！</td></tr>");
			}
		});
	}
	
	//分页ajax函数
	var formAjax = function(page){
		if(flag) {
			getdata(page);
			//setTimeout("getdata("+page+")",2000);
		}	
	}

	//跳到相应页面 
	var go = function(){
		var pagenum = $("#page").val();
		if(pagenum=='' || isNaN(pagenum) || pagenum <= 0){
			alert('请输入一个正整数！');
			$("#page").val(1);
		}else{
			formAjax(pagenum);
		}
	}
</script>

</body>
</html>