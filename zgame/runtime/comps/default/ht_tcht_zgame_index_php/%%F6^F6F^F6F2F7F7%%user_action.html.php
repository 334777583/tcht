<?php /* Smarty version 2.6.18, created on 2014-08-01 17:32:40
         compiled from stickiness/user_action.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>用户行为数据统计</title>
<meta http-equiv="Content-Type" content="text/html; chartset=utf-8" />
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
							<td width="95%" class="tableleft"><a href="javascript:void" style="color:#000" id="ac_exp">1.只记录等级35级以上的玩家行为</a></td>
						</tr>
						<tr>
							<td width="95%" class="tableleft"><a href="javascript:void" style="color:#000" id="ac_exp">2.每天平均每个玩家的操作次数=操作总次数/当天活跃玩家数</a></td>
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

					<span style="margin-left:20px">内容分类:</span>
					<select id="table">
						<option value="1">日常副本</option>
						<option value="2">日常任务</option>
						<option value="3">日常活动</option>
						<option value="4">横扫千军</option>
						<option value="5">点将台</option>
						<option value="6">摸金</option>
						<option value="7">钱庄</option>
						<option value="8">挑战</option>
						<option value="9">炼化</option>
						<option value="10">宝石</option>
						<option value="11">打造</option>
						<option value="12">神兵</option>
						<option value="13">武将</option>
						<option value="14">星宿</option>
						<option value="15">坐骑</option>
						<option value="16">国家</option>
						<option value="17">邮件</option>
						<option value="18">队伍</option>
						<option value="19">好友</option>
						<option value="20">其他</option>
						<option value="0">全部</option>
					</select>

					<span style="margin-left: 20px">日期:</span>
					<input type="text" value="<?php echo $this->_tpl_vars['startsDate']; ?>
" id="startdate" class="input1"/>至<input type="text" id="enddate" class="input1" value="<?php echo $this->_tpl_vars['endDate']; ?>
"/>
					<input type="button" value="查询" id="querybtn"/>
				
				</div>
			</div>
			<!--日常副本-->
			<div id="tabs-1" class="tabitem">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable1" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>温酒斩华雄</th>
													<th>董府地宫</th>
													<th>血战吕布单人</th>
													<th>血战吕布组队</th>
													<th>千里走单骑单人</th>
													<th>千里走单骑组队</th>
													<th>火烧新野城</th>
													<th>战将台挑战次数</th>
													<th>赤壁之战</th>
													<th>玲珑棋局</th>
													<th>空城计单人</th>
													<th>空城计组队</th>
												</tr>
											</thead>
											<tbody id="fb_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menul" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<!--日常任务-->
			<div id="tabs-2" class="tabitem" style="display:none">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable2" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>护送任务</th>
													<th>讨伐任务</th>
													<th>巡边任务</th>
													<th>悬赏任务</th>
													<th>考古任务</th>
													<th>收集物质</th>
													<th>国家刺探</th>
													<!--<th>南蛮入侵（玩家数）</th>
													<th>南蛮入侵（任务完成数）</th>-->
												</tr>
											</thead>
											<tbody id="rw_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu1" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<!--日常活动-->
			<div id="tabs-3" class="tabitem" style="display:none">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable3" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>南蛮入侵（玩家数）</th>
													<th>南蛮入侵（完成任务数）</th>
													<th>疯狂赛马（玩家数）</th>
													<th>煮酒论英雄（玩家数）</th>
													<th>魔窟秘境（玩家数）</th>
													<th>铁血战场</th>
												</tr>
											</thead>
											<tbody id="hd_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu1" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<!--横扫千军-->
			<div id="tabs-4" class="tabitem" style="display:none">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable4" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>购买挑战次数</th>
													<th>鼓舞次数</th>
													<th>清除CD次数</th>
													<th>挑战次数</th>
												</tr>
											</thead>
											<tbody id="hs_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu1" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<!--点将台-->
			<div id="tabs-5" class="tabitem" style="display:none">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable5" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>点将挑战次数</th>
													<th>点将台购买次数</th>
												</tr>
											</thead>
											<tbody id="dj_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu1" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<!--摸金-->
			<div id="tabs-6" class="tabitem" style="display:none">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable6" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>春秋摸金1次</th>
													<th>春秋摸金10次</th>
													<th>春秋摸金50次</th>
													<th>大周古墓摸金1次</th>
													<th>大周古墓摸金10次</th>
													<th>大周古墓摸金50次</th>
												</tr>
											</thead>
											<tbody id="mj_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu1" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<!--钱庄-->
			<div id="tabs-7" class="tabitem" style="display:none">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable7" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>投资月卡钱庄次数</th>
													<th>投资升级钱庄次数</th>
												</tr>
											</thead>
											<tbody id="qz_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu1" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<!--挑战-->
			<div id="tabs-8" class="tabitem" style="display:none">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable8" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>挑战鬼将精英次数</th>
													<th>挑战神将boss次数</th>
												</tr>
											</thead>
											<tbody id="tz_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu1" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<!--炼化-->
			<div id="tabs-9" class="tabitem" style="display:none">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable9" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>炼化装备次数</th>
													<th>炼化宝石次数</th>
													<th>炼化武将次数</th>
													<th>炼化药品次数</th>
													<th>炼化其他次数</th>
												</tr>
											</thead>
											<tbody id="lh_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu1" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<!--宝石-->
			<div id="tabs-10" class="tabitem" style="display:none">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable10" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>宝石镶嵌次数</th>
													<th>宝石融合次数</th>
													<th>宝石转换次数</th>
													<th>宝石纯炼次数</th>
												</tr>
											</thead>
											<tbody id="bs_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu1" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<!--打造-->
			<div id="tabs-11" class="tabitem" style="display:none">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable11" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>强化次数</th>
													<th>洗煤次数</th>
													<th>分解次数</th>
													<th>升级次数</th>
													<th>熔炼次数</th>
													<th>精炼次数</th>
												</tr>
											</thead>
											<tbody id="cr_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu1" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<!--神兵-->
			<div id="tabs-12" class="tabitem" style="display:none">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable12" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>神兵晋升次数</th>
													<th>神兵注灵次数</th>
													<th>神兵五行变化次数</th>
													<th>神兵聚灵清除CD次数</th>
													<th>神兵聚灵次数</th>
												</tr>
											</thead>
											<tbody id="sb_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu1" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<!--武将-->
			<div id="tabs-13" class="tabitem" style="display:none">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable13" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>武将成长次数</th>
													<th>武将技能刷新次数</th>
													<th>武将配饰升星次数</th>
													<th>武将转生次数</th>
													<th>武将继承次数</th>
													<th>武将兵书拜访次数</th>
													<th>武将兵书寻访次数</th>
												</tr>
											</thead>
											<tbody id="wj_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu1" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<!--星宿-->
			<div id="tabs-14" class="tabitem" style="display:none">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable14" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>星宿清除冷却CD次数</th>
													<th>星宿点亮次数</th>
													<th>星宿占星次数</th>
													<th>观星收获次数</th>
													<th>观星逆天改命次数</th>
												</tr>
											</thead>
											<tbody id="xx_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu1" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<!--坐骑-->
			<div id="tabs-15" class="tabitem" style="display:none">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable15" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>坐骑丹药喂养次数</th>
													<th>坐骑道具喂养次数</th>
													<th>坐骑突破内丹次数</th>
													<!--<th>坐骑技能学习次数</th>-->
													<th>坐骑技能升级次数</th>
													<th>坐骑幻化次数</th>
												</tr>
											</thead>
											<tbody id="zq_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu1" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<!--国家-->
			<div id="tabs-16" class="tabitem" style="display:none">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable16" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>国家商户购买次数</th>
													<th>国家谋略学习次数</th>
												</tr>
											</thead>
											<tbody id="gj_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu1" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<!--邮件-->
			<div id="tabs-17" class="tabitem" style="display:none">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable17" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>邮件发送次数</th>
												</tr>
											</thead>
											<tbody id="em_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu1" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<!--队伍-->
			<div id="tabs-18" class="tabitem" style="display:none">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable18" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>创建队伍次数</th>
												</tr>
											</thead>
											<tbody id="dw_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu1" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<!--好友-->
			<div id="tabs-19" class="tabitem" style="display:none">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable19" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>送花次数</th>
													<th>好友添加次数</th>
													<th>好友删除次数</th>
													<th>好友祝福次数</th>
												</tr>
											</thead>
											<tbody id="fr_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu1" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<!--其他-->
			<div id="tabs-20" class="tabitem" style="display:none">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable20" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>签到领奖次数</th>
													<th>补偿50%领取次数</th>
													<th>补偿100%领取次数</th>
													<th>商城购买次数</th>
												</tr>
											</thead>
											<tbody id="ot_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu1" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<!--全部-->
			<div id="tabs-21" class="tabitem" style="display:none">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="totable1" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>日期</th>
													<th>账号</th>
													<th>角色id</th>
													<th>角色名称</th>
													<th>职业</th>
													<th>国家</th>
													<th>性别</th>
													<th>角色等级</th>
													<th>vip等级</th>
													<th>称号</th>
													<th>帮派</th>
													<th>击杀世界boss数</th>
													<th>好友数量</th>
													<th>击杀玩家数量</th>
													<th>被击杀次数</th>
												</tr>
											</thead>
											<tbody id="role_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml1" style="float:right;margin-right:20px"></div>
										<div id="example_length1" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu1" name="example_length" size="1" aria-controls="example">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
												</select> 条记录
											</label>
											
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<div id="chartdiv1" style="width:100%; height:500px;"></div>
			
			<div style="clear:both"></div>
		</div>
		
		<div style="height:50px">&nbsp;</div>
		
		
		
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
/js/amcharts.js" type="text/javascript"></script>
<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/function.js" type="text/javascript"></script>	
<script type="text/javascript">
	$(function(){


		//时间插件
		$("#startdate").datepicker();
		$("#enddate").datepicker();
		$("#todate").datepicker();
		
		showTitle("游戏数据统计:行为数据统计");
		
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
				
		//切换标签
		$("#querybtn").click(function(){
			var self = this;
			$(".tabitem").css("display","none");
			var id  = $("#table").val();
				if(validator("startdate", "enddate")){
					if(id == 1){
						$("#tabs-1").show();
						getcarbon(1);
						showIMG();
					}else if(id == 2){
						$("#tabs-2").show();
						gettask(2);
						showIMG();
					}else if(id == 3){
						$("#tabs-3").show();
						getact(3);
						showIMG();
					}else if(id == 4){
						$("#tabs-4").show();
						getsweep(4);
						showIMG();
					}else if(id == 5){
						$("#tabs-5").show();
						getpoint(5);
						showIMG();
					}else if(id == 6){
						$("#tabs-6").show();
						getgold(6);
						showIMG();
					}else if(id == 7){
						$("#tabs-7").show();
						getbank(7);
						showIMG();
					}else if(id == 8){
						$("#tabs-8").show();
						getdekaron(8);
						showIMG();
					}else if(id == 9){
						$("#tabs-9").show();
						getsmelt(9);
						showIMG();
					}else if(id == 10){
						$("#tabs-10").show();
						getgem(10);
						showIMG();
					}else if(id == 11){
						$("#tabs-11").show();
						getcreate(11);
						showIMG();
					}else if(id == 12){
						$("#tabs-12").show();
						getmagic(12);
						showIMG();
					}else if(id == 13){
						$("#tabs-13").show();
						getpie(13);
						showIMG();
					}else if(id == 14){
						$("#tabs-14").show();
						getstar(14);
						showIMG();
					}else if(id == 15){
						$("#tabs-15").show();
						gethorse(15);
						showIMG();
					}else if(id == 16){
						$("#tabs-16").show();
						getcity(16);
						showIMG();
					}else if(id == 17){
						$("#tabs-17").show();
						getemail(17);
						showIMG();
					}else if(id == 18){
						$("#tabs-18").show();
						getteam(18);
						showIMG();
					}else if(id == 19){
						$("#tabs-19").show();
						getfriend(19);
						showIMG();
					}else if(id == 20){
						$("#tabs-20").show();
						getother(20);
						showIMG();
					}else if(id == 0){
						$("#tabs-21").show();
						getall(0);
						showIMG();
					}
				}
			});
		
		//副本
		function getcarbon(id){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/getcarbon',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					sync : true,
					cache : true,
					beforeSend : function(){
						$("#fb_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						showIMG(data.result);
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								html += "<tr>";
								html += "<td>" + list[i]["a_createtime"] + "</td>";
								html += "<td>" + list[i]["zhx"] + "</td>";
								html += "<td>" + list[i]["ddg"] + "</td>";
								html += "<td>" + list[i]["dlb"] + "</td>";
								html += "<td>" + list[i]["slb"] + "</td>";
								html += "<td>" + list[i]["ddq"] + "</td>";
								html += "<td>" + list[i]["sdq"] + "</td>";
								html += "<td>" + list[i]["sxy"] + "</td>";
								html += "<td>" + list[i]["djt"] + "</td>";
								html += "<td>" + list[i]["cbz"] + "</td>";
								html += "<td>" + list[i]["zlq"] + "</td>";
								html += "<td>" + list[i]["dkc"] + "</td>";
								html += "<td>" + list[i]["skc"] + "</td>";
								html += "<tr>";
							}
							$("#fb_body").html(html);
							
						}else{
							$("#fb_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#fb_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
		
		//任务
		function gettask(){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/gettask',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#rw_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								html += "<tr>";
								html += "<td>" + list[i]["a_createtime"] + "</td>";
								html += "<td>" + list[i]["hs"] + "</td>";
								html += "<td>" + list[i]["tf"] + "</td>";
								html += "<td>" + list[i]["xb"] + "</td>";
								html += "<td>" + list[i]["xs"] + "</td>";
								html += "<td>" + list[i]["kg"] + "</td>";
								html += "<td>" + list[i]["sj"] + "</td>";
								html += "<td>" + list[i]["ct"] + "</td>";
								html += "<tr>";
							}
							showIMG(data.result);
							$("#rw_body").html(html);
						}else{
							$("#rw_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#rw_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
			
		//活动	
		function getact(){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/getact',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#role_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								html += "<tr>";
								html += "<td>" + list[i]["a_createtime"] + "</td>";
								html += "<td>" + list[i]["nm_player"] + "</td>";
								html += "<td>" + list[i]["nm_num"] + "</td>";
								html += "<td>" + list[i]["sm_player"] + "</td>";
								html += "<td>" + list[i]["zj_player"] + "</td>";
								html += "<td>" + list[i]["mk_player"] + "</td>";
								html += "<td>" + list[i]["tx_player"] + "</td>";
								html += "<tr>";
							}
							$("#hd_body").html(html);
							showIMG(data.result);
						}else{
							$("#hd_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#hd_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
			
		//横扫	
		function getsweep(){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/getsweep',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#hs_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								html += "<tr>";
								html += "<td>" + list[i]["a_createtime"] + "</td>";
								html += "<td>" + list[i]["gmtz"] + "</td>";
								html += "<td>" + list[i]["guwu"] + "</td>";
								html += "<td>" + list[i]["qccd"] + "</td>";
								html += "<td>" + list[i]["tzcs"] + "</td>";
								html += "<tr>";
							}
							$("#hs_body").html(html);
							showIMG(data.result);
						}else{
							$("#hs_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#hs_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
			
		//点将	
		function getpoint(){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/getpoint',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#dj_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								html += "<tr>";
								html += "<td>" + list[i]["a_createtime"] + "</td>";
								html += "<td>" + list[i]["djt"] + "</td>";
								html += "<td>" + list[i]["djtzj"] + "</td>";
								html += "<tr>";
							}
							$("#dj_body").html(html);
							showIMG(data.result);
						}else{
							$("#dj_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#dj_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
			
		//摸金	
		function getgold(){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/getgold',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#mj_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								html += "<tr>";
								html += "<td>" + list[i]["a_createtime"] + "</td>";
								html += "<td>" + list[i]["mjone"] + "</td>";
								html += "<td>" + list[i]["mjten"] + "</td>";
								html += "<td>" + list[i]["mjfifty"] + "</td>";
								html += "<td>" + list[i]["dzmjone"] + "</td>";
								html += "<td>" + list[i]["dzmjten"] + "</td>";
								html += "<td>" + list[i]["dzmjfifty"] + "</td>";
								html += "<tr>";
							}
							$("#mj_body").html(html);
							showIMG(data.result);
						}else{
							$("#mj_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#mj_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
			
		//钱庄	
		function getbank(){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/getbank',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#qz_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								html += "<tr>";
								html += "<td>" + list[i]["a_createtime"] + "</td>";
								html += "<td>" + list[i]["tzyk"] + "</td>";
								html += "<td>" + list[i]["tzqz"] + "</td>";
								html += "<tr>";
							}
							$("#qz_body").html(html);
							showIMG(data.result);
						}else{
							$("#qz_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#qz_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
			
		//挑战	
		function getdekaron(){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/getdekaron',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#tz_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								html += "<tr>";
								html += "<td>" + list[i]["a_createtime"] + "</td>";
								html += "<td>" + list[i]["tzgj"] + "</td>";
								html += "<td>" + list[i]["tzsj"] + "</td>";
								html += "<tr>";
							}
							$("#tz_body").html(html);
							showIMG(data.result);
						}else{
							$("#tz_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#tz_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
			
		//炼化	
		function getsmelt(){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/getsmelt',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#lh_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								html += "<tr>";
								html += "<td>" + list[i]["a_createtime"] + "</td>";
								html += "<td>" + list[i]["zb"] + "</td>";
								html += "<td>" + list[i]["bs"] + "</td>";
								html += "<td>" + list[i]["wj"] + "</td>";
								html += "<td>" + list[i]["yp"] + "</td>";
								html += "<td>" + list[i]["other"] + "</td>";
								html += "<tr>";
							}
							$("#lh_body").html(html);
							showIMG(data.result);
						}else{
							$("#lh_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#lh_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
			
		//宝石	
		function getgem(){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/getgem',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#bs_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								html += "<tr>";
								html += "<td>" + list[i]["a_createtime"] + "</td>";
								html += "<td>" + list[i]["bssq"] + "</td>";
								html += "<td>" + list[i]["bsrh"] + "</td>";
								html += "<td>" + list[i]["bszh"] + "</td>";
								html += "<td>" + list[i]["bscl"] + "</td>";
								html += "<tr>";
							}
							$("#bs_body").html(html);
							showIMG(data.result);
						}else{
							$("#bs_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#bs_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
			
		//打造	
		function getcreate(){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/getcreate',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#cr_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								html += "<tr>";
								html += "<td>" + list[i]["a_createtime"] + "</td>";
								html += "<td>" + list[i]["qh"] + "</td>";
								html += "<td>" + list[i]["xl"] + "</td>";
								html += "<td>" + list[i]["fj"] + "</td>";
								html += "<td>" + list[i]["sj"] + "</td>";
								html += "<td>" + list[i]["rl"] + "</td>";
								html += "<td>" + list[i]["jl"] + "</td>";
								html += "<tr>";
							}
							$("#cr_body").html(html);
							showIMG(data.result);
						}else{
							$("#cr_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#cr_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
			
		//神兵	
		function getmagic(){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/getmagic',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#sb_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								html += "<tr>";
								html += "<td>" + list[i]["a_createtime"] + "</td>";
								html += "<td>" + list[i]["sbjs"] + "</td>";
								html += "<td>" + list[i]["sbzl"] + "</td>";
								html += "<td>" + list[i]["sbwx"] + "</td>";
								html += "<td>" + list[i]["sbcd"] + "</td>";
								html += "<td>" + list[i]["sbjl"] + "</td>";
								html += "<tr>";
							}
							$("#sb_body").html(html);
							showIMG(data.result);
						}else{
							$("#sb_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#sb_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
			
		//武将	
		function getpie(){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/getpie',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#wj_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								html += "<tr>";
								html += "<td>" + list[i]["a_createtime"] + "</td>";
								html += "<td>" + list[i]["wjlg"] + "</td>";
								html += "<td>" + list[i]["wjjn"] + "</td>";
								html += "<td>" + list[i]["wjps"] + "</td>";
								html += "<td>" + list[i]["wjzs"] + "</td>";
								html += "<td>" + list[i]["wjjc"] + "</td>";
								html += "<td>" + list[i]["bsbf"] + "</td>";
								html += "<td>" + list[i]["bsxf"] + "</td>";
								html += "<tr>";
							}
							$("#wj_body").html(html);
							showIMG(data.result);
						}else{
							$("#wj_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#wj_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
			
		//星宿	
		function getstar(){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/getstar',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#xx_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								html += "<tr>";
								html += "<td>" + list[i]["a_createtime"] + "</td>";
								html += "<td>" + list[i]["xxcd"] + "</td>";
								html += "<td>" + list[i]["xxdl"] + "</td>";
								html += "<td>" + list[i]["xxzx"] + "</td>";
								html += "<td>" + list[i]["gxsh"] + "</td>";
								html += "<td>" + list[i]["gxnt"] + "</td>";
								html += "<tr>";
							}
							$("#xx_body").html(html);
							showIMG(data.result);
						}else{
							$("#xx_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#xx_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
				
		//坐骑
		function gethorse(){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/gethorse',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#zq_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								html += "<tr>";
								html += "<td>" + list[i]["a_createtime"] + "</td>";
								html += "<td>" + list[i]["zjdy"] + "</td>";
								html += "<td>" + list[i]["zjdj"] + "</td>";
								html += "<td>" + list[i]["zjtp"] + "</td>";
								html += "<td>" + list[i]["zjjn"] + "</td>";
								html += "<td>" + list[i]["zjhh"] + "</td>";
								html += "<tr>";
							}
							$("#zq_body").html(html);
							showIMG(data.result);
						}else{
							$("#zq_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#zq_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
			
		//国家	
		function getcity(){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/getcity',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#gj_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								html += "<tr>";
								html += "<td>" + list[i]["a_createtime"] + "</td>";
								html += "<td>" + list[i]["gjsd"] + "</td>";
								html += "<td>" + list[i]["gjml"] + "</td>";
								html += "<tr>";
							}
							$("#gj_body").html(html);
							showIMG(data.result);
						}else{
							$("#gj_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#gj_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
				
		//邮件
		function getemail(){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/getemail',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#em_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								html += "<tr>";
								html += "<td>" + list[i]["a_createtime"] + "</td>";
								html += "<td>" + list[i]["yjfs"] + "</td>";
								html += "<tr>";
							}
							$("#em_body").html(html);
							showIMG(data.result);
						}else{
							$("#em_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#em_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
				
		//队伍
		function getteam(){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/getteam',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#dw_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								html += "<tr>";
								html += "<td>" + list[i]["a_createtime"] + "</td>";
								html += "<td>" + list[i]["dw"] + "</td>";
								html += "<tr>";
							}
							$("#dw_body").html(html);
							showIMG(data.result);
						}else{
							$("#dw_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#dw_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
				
		//好友
		function getfriend(){
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/getfriend',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#fr_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								html += "<tr>";
								html += "<td>" + list[i]["a_createtime"] + "</td>";
								html += "<td>" + list[i]["shcs"] + "</td>";
								html += "<td>" + list[i]["hytj"] + "</td>";
								html += "<td>" + list[i]["hysc"] + "</td>";
								html += "<td>" + list[i]["hyzf"] + "</td>";
								html += "<tr>";
							}
							$("#fr_body").html(html);
							showIMG(data.result);
						}else{
							$("#fr_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#fr_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
				
		//其他
		function getother(){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/getother',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#ot_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								html += "<tr>";
								html += "<td>" + list[i]["a_createtime"] + "</td>";
								html += "<td>" + list[i]["qdlj"] + "</td>";
								html += "<td>" + list[i]["bc1"] + "</td>";
								html += "<td>" + list[i]["bc2"] + "</td>";
								html += "<td>" + list[i]["scgm"] + "</td>";
								html += "<tr>";
							}
							$("#ot_body").html(html);
							showIMG(data.result);
						}else{
							$("#ot_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#ot_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
			
		//全部
		function getall(){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/useraction/getall',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#role_body").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						var chartData  = [];//清除数据，防止不断叠加
						
						var list = [];
						list = data.list;
						$("#pagehtml").show();
						var fields = ['on_time', 'accountid', 'guid', 'rolename','carrer','country','sex','level','viplevel','rolename','guildid','boss','fried','kill','die'];
						page.INIT(25, list, fields, '#role_body');
						$("#home_page").trigger('click');
						$("#startdate").val(data.startDate);
						$("#enddate").val(data.endDate);
						
					},
					error : function(){
						$("#role_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		}
		
		function showIMG(obj){
				  var chart = new AmCharts.AmSerialChart();
					chart.dataProvider = obj;
					chart.categoryField = "name";
					chart.startDuration = 0;
					chart.balloon.color = "#000000";

					// AXES
					// category
					var categoryAxis = chart.categoryAxis;
					categoryAxis.fillAlpha = 1;
					categoryAxis.fillColor = "#FAFAFA";
					categoryAxis.gridAlpha = 0;
					categoryAxis.axisAlpha = 0;
					categoryAxis.gridPosition = "start";
					//categoryAxis.position = "top";
					categoryAxis.position = "bottom";

					// value
					var valueAxis = new AmCharts.ValueAxis();
					valueAxis.title = "";
					valueAxis.dashLength = 5;
					valueAxis.axisAlpha = 0;
					//valueAxis.minimum = 1;
					//valueAxis.maximum = 40000;
					valueAxis.integersOnly = true;
					valueAxis.gridCount = 10;
					valueAxis.reversed = false; // this line makes the value axis reversed
					chart.addValueAxis(valueAxis);

					// United Kingdom graph
					var graph = new AmCharts.AmGraph();
					graph.title = "数据显示";
					graph.valueField = "num";
					graph.balloonText = "[[category]]: [[value]]";
					graph.bullet = "round";
					chart.addGraph(graph);

					/*var chartCursor = new AmCharts.ChartCursor();
					chartCursor.cursorPosition = "mouse";
					chartCursor.zoomable = false;
					chartCursor.cursorAlpha = 0;
					chart.addChartCursor(chartCursor); */

					 // LEGEND
					var legend = new AmCharts.AmLegend();
					legend.markerType = "circle";
					chart.addLegend(legend);

					// WRITE
					chart.write("chartdiv1");
			}
		
		getcarbon();     
	});
</script>
</body>

</html>