<?php /* Smarty version 2.6.18, created on 2014-08-07 17:37:27
         compiled from gmtools/gm_grow.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>用户成长日志</title>
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
							<td width="95%" class="tableleft">1、服务器所有40级以上的角色属性</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">2、角色等级排行前10名、前50名、前100名、前500名，所有玩家的角色信息</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">3、当天未上线的角色不参与统计。</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="topinfo">
				<div>
					<label>
						<span>服务器:</span>
						<select id="sip">
							<?php $_from = $this->_tpl_vars['ipList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ip']):
?>
								<option value="<?php echo $this->_tpl_vars['ip']['s_id']; ?>
" id="<?php echo $this->_tpl_vars['ip']['s_id']; ?>
"><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</option>
							<?php endforeach; endif; unset($_from); ?>
							<option value="0" >全部</option>
						</select>
					</label>
					<label>
						<select id="code" name="code">
							<!--<option value="1">成长日志选择</option>-->
							<option value="1">角色概况</option>
							<option value="2">战斗属性</option>
							<option value="3">武将属性</option>
							<option value="4">装备概况</option>
							<option value="5">坐骑概况</option>
							<option value="6">星宿信息</option>
							<option value="7">神兵信息</option>
							<option value="8">国家谋略</option>
						</select>
					</label>
					
					<span>时间:</span><input type="text" class="input1" id="startdate" value="<?php echo $this->_tpl_vars['startDate']; ?>
"/>至<input type="text" class="input1" id="enddate" value="<?php echo $this->_tpl_vars['endDate']; ?>
"/>
					<label>
						<select id="ran" name="ran">
							<option value="1">前10名</option>
							<option value="2">前50名</option>
							<option value="3">前100名</option>
							<option value="4">前500名</option>
						</select>
					</label>					
					<input type="button" value="查询" id="querybtn" style="margin-left:20px"/>
					<input type="button" value="导出Excel" id="export"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<!--角色概况-->
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
			
			<!--战斗属性-->
			<div id="tabs-2" style="display:none" class="tabitem">
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
													<th>账号</th>
													<th>角色id</th>
													<th>角色名称</th>
													<th>战力</th>
													<th>生命值</th>
													<th>物理攻击</th>
													<th>物理防御</th>
													<th>法术攻击</th>
													<th>法术防御</th>
													<th>力量</th>
													<th>敏捷</th>
													<th>体质</th>
													<th>智力</th>
													<th>精神</th>
													<th>命中</th>
													<th>闪避</th>
													<th>暴击</th>
													<th>坚韧</th>
													<th>减伤</th>
												</tr>
											</thead>
											<tbody id="battle_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml2" style="float:right;margin-right:20px"></div>
										<div id="example_length2" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu2" name="example_length" size="1" aria-controls="example">
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
			
			<!--武将属性-->
			<div id="tabs-3" style="display:none" class="tabitem">
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
													<th>账号</th>
													<th>角色id</th>
													<th>角色名称</th>
													<th>武将名称</th>
													<th>序号</th>
													<th>武将等级</th>
													<th>武将战力</th>
													<th>转生次数</th>
													<th>资质</th>
													<th>成长</th>
													<th>生命值</th>
													<th>物理攻击</th>
													<th>物理防御</th>
													<th>法术攻击</th>
													<th>法术防御</th>
													<th>命中</th>
													<th>闪避</th>
													<th>暴击</th>
													<th>坚韧</th>
													<th>力量</th>
													<th>敏捷</th>
													<th>体质</th>
													<th>智力</th>
													<th>精神</th>
													<th>兵书个数</th>
													<th>兵书评分</th>
													<th>太公兵法等级</th>
													<th>六韬兵书等级</th>
													<th>尉缭子等级</th>
													<th>黄帝内经等级</th>
													<th>论语等级</th>
													<th>战国策等级</th>
													<th>技能个数</th>
													<th>天赋技能1名称等级</th>
													<th>天赋技能2名称等级</th>
													<th>通用技能名称等级</th>
												</tr>
											</thead>
											<tbody id="pie_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml3" style="float:right;margin-right:20px"></div>
										<div id="example_length3" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu3" name="example_length" size="1" aria-controls="example">
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
			
			<!--装备概况-->
			<div id="tabs-4" style="display:none" class="tabitem">
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
													<th>账号</th>
													<th>角色id</th>
													<th>角色名称</th>
													<th>武器颜色名称强化等级</th>
													<th>护手颜色名称强化等级</th>
													<th>衣服颜色名称强化等级</th>
													<th>裤子颜色名称强化等级</th>
													<th>腰带颜色名称强化等级</th>
													<th>鞋子颜色名称强化等级</th>
													<th>项链颜色名称强化等级</th>
													<th>戒指颜色名称强化等级</th>
													<th>武器颜色名称附加属性</th>
													<th>头盔附加属性</th>
													<th>护手附加属性</th>
													<th>衣服附加属性</th>
													<th>裤子附加属性</th>
													<th>腰带附加属性</th>
													<th>鞋子附加属性</th>
													<th>项链附加属性</th>
													<th>戒指附加属性</th>
												</tr>
											</thead>
											<tbody id="equip_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										
										<div id="pagehtml4" style="float:right;margin-right:20px"></div>
										<div id="example_length4" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu4" name="example_length" size="1" aria-controls="example">
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
			
			<!--坐骑概况-->
			<div id="tabs-5" style="display:none" class="tabitem">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable5" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>角色</th>
													<th>坐骑技能总和</th>
													<th>践踏等级</th>
													<th>冲锋等级</th>
													<th>千里奔袭等级</th>
													<th>疾风破等级</th>
													<th>惊雷破等级</th>
													<th>天火破等级</th>
													<th>金戈铁马等级</th>
													<th>八门金锁等级</th>
													<th>一气化千等级</th>
													<th>疾行等级</th>
													<th>归元等级</th>
													<th>御风等级</th>
													<th>幻化个数</th>
													<th>内丹等级</th>
												</tr>
											</thead>
											<tbody id="skill_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml5" style="float:right;margin-right:20px"></div>
										<div id="example_length5" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu5" name="example_length" size="1" aria-controls="example">
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
			
			<!--星宿信息-->
			<div id="tabs-6" style="display:none" class="tabitem">
			<table width="100%"  cellpadding="0" cellspacing="0">
				<tr>
					<td valign="top" bgcolor="#F7F8F9">
						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td>
									<table class="mytable" id="mytable5" cellspacing="0" align="center">
										<thead>
											<tr>
												<th>角色</th>
												<th>命格一星等级</th>
												<th>命格二星等级</th>
												<th>命格三星等级</th>
												<th>命格四星等级</th>
												<th>命格五星等级</th>
												<th>命格六星等级</th>
												<th>命格七星等级</th>
												<th>命格八星等级</th>
											</tr>
										</thead>
										<tbody id="star_body">
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<div id="pagehtml5" style="float:right;margin-right:20px"></div>
									<div id="example_length5" class="dataTables_length"  style="display:none">
										<label>每页显示
											<select id="menu5" name="example_length" size="1" aria-controls="example">
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
		
			<!--神兵信息-->
			<div id="tabs-7" style="display:none" class="tabitem">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable5" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>神兵等级</th>
													<th>赑屃灵件1注灵进度</th>
													<th>赑屃灵件2注灵进度</th>
													<th>螭吻灵件1注灵进度</th>
													<th>螭吻灵件2注灵进度</th>
													<th>睚眦灵件1注灵进度</th>
													<th>睚眦灵件2注灵进度</th>
													<th>当前命脉</th>
													<th>命脉技能</th>
													<th>神兵灵魄</th>
												</tr>
											</thead>
											<tbody id="magic_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml5" style="float:right;margin-right:20px"></div>
										<div id="example_length5" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu5" name="example_length" size="1" aria-controls="example">
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
			
			<!--国家谋略-->
			<div id="tabs-8" style="display:none" class="tabitem">
				<table width="100%"  cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" bgcolor="#F7F8F9">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table class="mytable" id="mytable5" cellspacing="0" align="center">
											<thead>
												<tr>
													<th>角色</th>
													<th>生命增强等级</th>
													<th>命中增强等级</th>
													<th>闪避增强等级</th>
													<th>暴击增强等级</th>
													<th>坚韧增强等级</th>
												</tr>
											</thead>
											<tbody id="trick_body">
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div id="pagehtml5" style="float:right;margin-right:20px"></div>
										<div id="example_length5" class="dataTables_length"  style="display:none">
											<label>每页显示
												<select id="menu5" name="example_length" size="1" aria-controls="example">
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

			<div style="float:right;margin-right:20px;display:none;" id="pagehtml">
				<div class="pages">
					<a id="home_page" href="javascript:void(0)">首页</a>&nbsp;&nbsp;
					<a id="pre_page" href="javascript:void(0)">上一页</a>&nbsp;&nbsp;
					<a id="next_page" href="javascript:void(0)">下一页</a>&nbsp;&nbsp;
					<a id="last_page" href="javascript:void(0)">尾页</a>&nbsp;&nbsp;
					<span>第<span id="cur_page">1</span>/<span id="total_page">1</span>页&nbsp;&nbsp;</span>
					转到<input type="text" class="text" size="3"  id="page" value="1"/>
					<a id="go" class="go" href="javascript:void(0);"></a>页
				</div>
			</div>
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
	var user_grow = {	
		INIT : function(){
			var self = this;
			
			//时间插件
			$("#startdate").datepicker();
			$("#enddate").datepicker();
			page.listen();
			
			//根据条件查询
			$("#querybtn").click(function(){
				var id  = $("#code").val();
				if(validator("startdate", "enddate")){
					if(id == 1){
						self.getData(1);
					}else if(id == 2){
						self.getBattle(2);
					}else if(id == 3){
						self.getpie(3);
					}else if(id == 4){
						self.getEqui(4);
					}else if(id == 5){
						self.getSkill(5);
					}else if(id == 6){
						self.getStar(6);
					}else if(id == 7){
						self.getMagic(7);
					}else if(id == 8){
						self.getTrick(8);
					}
				}
			});
			
			//每页显示
			$("#menu").change(function(){
				self.getdata(1);
			});
			
			$("#sip").change(function(){
				$("#startdate").attr('value','');//开始日期初始化
				//self.getData();
				self.getdata(1);
			});
			
			//导出excel
			$("#export").click(function(){
				var id  = $("#code").val();
				var ip = $("#sip").val();
				var ran = $("#ran").val();
				var startdate = $("#startdate").val();
				var enddate = $("#enddate").val();
				if(id == 1){
					window.location.href = "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmgrow/taskexcel/ip/"+ip+"/ran/"+ran+"/startdate/"+startdate+"/enddate/"+enddate;
				}else if(id == 2){
					window.location.href = "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmgrow/battleexcel/ip/"+ip+"/ran/"+ran+"/startdate/"+startdate+"/enddate/"+enddate;
				}else if(id == 3){
					window.location.href = "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmgrow/pieexcel/ip/"+ip+"/ran/"+ran+"/startdate/"+startdate+"/endDate/"+enddate;
				}else if(id == 4){
					window.location.href = "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmgrow/equiexcel/ip/"+ip+"/ran/"+ran+"/startdate/"+startdate+"/enddate/"+enddate;
				}else if(id == 5){
					window.location.href = "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmgrow/skillexcel/ip/"+ip+"/ran/"+ran+"/startdate/"+startdate+"/enddate/"+enddate;
				}else if(id == 6){
					window.location.href = "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmgrow/starexcel/ip/"+ip+"/ran/"+ran+"/startdate/"+startdate+"/enddate/"+enddate;
				}else if(id == 7){
					window.location.href = "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmgrow/magicexcel/ip/"+ip+"/ran/"+ran+"/startdate/"+startdate+"/enddate/"+enddate;
				}else if(id == 8){
					window.location.href = "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmgrow/trickexcel/ip/"+ip+"/ran/"+ran+"/startdate/"+startdate+"/enddate/"+enddate;
				}	
			})
			
			
			//页面加载，显示图表
			self.getData(1);
			showTitle("游戏数据统计:玩家成长日志");
			
			
			//切换标签
			$("#code").change(function(){
				$(".tabitem").css("display","none");
				var id  = "#tabs-"+$(this).val();
				
				if(1 == $(this).val()){ 
					$(this).removeClass("user-gray");
					$("#tabs-1").show();
					self.getData(1);
					
				}else if(2 == $(this).val()){ 
					$(this).removeClass("user-gray");
					$("#tabs-2").show(2);
					
					var today =  curentDate();
					$("#qdate").val(today);
					self.getBattle(2);
					
				}else if(3 == $(this).val()){ 
					$(this).removeClass("user-gray");
					$("#tabs-3").show();
					self.getpie(3);
					
				}else if(4== $(this).val()){ 
					$(this).removeClass("user-gray");
					
					$("#tabs-4").show();
					self.getEqui(4);
					
				}else if(5 == $(this).val()){ 
					$(this).removeClass("user-gray");
					$("#tabs-5").show();
					self.getSkill(5);
					
				}else if(6 == $(this).val()){ 
					$(this).removeClass("user-gray");
					$("#tabs-6").show();
					self.getStar(6);
					
				}else if(7 == $(this).val()){ 
					$(this).removeClass("user-gray");
					$("#tabs-7").show();
					self.getMagic(7);
					
				}else if(8 == $(this).val()){ 
					$(this).removeClass("user-gray");
					$("#tabs-8").show();
					self.getTrick(8);
					
				}
				$(id).css("display","block");
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
			
		
		},

		//获取角色概况
		getData : function(id){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/gmgrow/getrole',
					dataType : 'json',
					data : {
						id : id,
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						//pageSize : $("#menu").val(),
						num : $("#ran").val()
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
		},
		
		//战斗属性
		getBattle : function(id){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/gmgrow/getbattle',
					dataType : 'json',
					data : {
						id :id,
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val(),
						num : $("#ran").val()
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#chartdiv").html("<div style='margin-top:200px;width:100%;display:block;text-align:center'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></div>");
						$("#mbody").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						
						//chartData  = [];//清除数据，防止不断叠加
						
						var list = [];
						list = data.list;
						var html = "";
						
						if(list.length >0){
							$("#export_div").show();
							$("#example_length").show();
							$("#pagehtml").show();
							var fields = ['o_date', 'accountid', 'uid', 'u_name','BattlePower','HP','PhyAttack','PhyDefense','MagicAttack','MagicDefense','Power','Nimble','Physical','Intelligence','Spirit','Hit','Dodge','Cruel','Tenacity','Minus'];
							page.INIT(25, list, fields, '#battle_body');
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
						
						}else{
							
							$("#battle_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
						
						$( "#startdate").val(data.startDate);
						$("#enddate").val(data.endDate);
					},
					error : function(){
						$("#battle_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		},
		
		//装备概况
		getEqui : function(page){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/gmgrow/getequip',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val(),
						num : $("#ran").val(),
						curPage : page
					},
					success : function(data){
						$( "#startdate").val(data.startDate);
						$("#enddate").val(data.endDate);
						//chartData  = [];//清除数据，防止不断叠加
						if(data.list.length >0){
							var chartData  = [];//清除数据，防止不断叠加
							var list = [];
							list = data.list;
							var html = "";
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate)
							for(var i in list){
								if(list[i]["uid"]){
									html += "<tr>";
									html += "<td>" + list[i]["o_date"] + "</td>";
									html += "<td>" + list[i]["accountid"] + "</td>";
									html += "<td>" + list[i]["uid"] + "</td>";
									html += "<td>" + list[i]["u_name"] + "</td>";
									html += "<td>" + list[i]["arm"]+"--"+ list[i]["armlevel"]+ "级</td>";
									html += "<td>" + list[i]["bracers"]+"--"+ list[i]["bracerslevel"]+ "级</td>";
									html += "<td>" + list[i]["clothes"]+"--"+ list[i]["clotheslevel"]+ "级</td>";
									html += "<td>" + list[i]["pant"]+"--"+ list[i]["pantlevel"]+ "级</td>";
									html += "<td>" + list[i]["belt"]+"--"+ list[i]["beltlevel"]+ "级</td>";
									html += "<td>" + list[i]["shoes"]+"--"+ list[i]["shoeslevel"]+ "级</td>";
									html += "<td>" + list[i]["necklace"]+"--"+ list[i]["necklacelevel"]+ "级</td>";
									html += "<td>" + list[i]["ring"]+"--"+ list[i]["ringlevel"]+ "级</td>";
									html += "<td>" + list[i]["armlevel"] + "</td>";
									html += "<td>" + list[i]["headlevel"] + "</td>";
									html += "<td>" + list[i]["bracerslevel"] + "</td>";
									html += "<td>" + list[i]["clotheslevel"] + "</td>";
									html += "<td>" + list[i]["pantlevel"] + "</td>";
									html += "<td>" + list[i]["beltlevel"] + "</td>";
									html += "<td>" + list[i]["shoeslevel"] + "</td>";
									html += "<td>" + list[i]["necklacelevel"] + "</td>";
									html += "<td>" + list[i]["ringlevel"] + "</td>";
									html += "<tr>";
								}
							}
							$("#equip_body").html(html);
						}else{
							$("#equip_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#chartdiv").html("<div style=\"text-align:center\">没有记录！</div>");
						$("#equip_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		},
		
		//武将属性
		getpie : function(id){
			var self = this;
			$.ajax({
					id : id,
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/gmgrow/getpie',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val(),
						num : $("#ran").val()
					},
					success : function(data){
						var list = [];
						list = data.list;
						var html = "";
						
						//if(list.length >0){
							/*$("#export_div").show();
							$("#example_length").show();
							$("#pagehtml").show();
							var fields = ['creattime', 'h_code', 'u_id', 'u_name','h_name','base','excell'];
							page.INIT(25, list, fields, '#hebody');
							$("#home_page").trigger('click');*/
							//$("#startdate").val(data.startDate);
						
							
							var chartDate = [];
							for(var i in list){
								for(var s in list[i]['info']){
									if(list[i]["info"][s]["NO"] > 0 && list[i]["account"]>0){
									html += "<tr>";
									html += "<td>" + list[i]["p_date"] + "</td>";
									html += "<td>" + list[i]["account"] + "</td>";
									html += "<td>" + list[i]["playid"] + "</td>";
									html += "<td>" + list[i]["rolename"] + "</td>";
									html += "<td>" + list[i]["info"][s]["templateid"] + "</td>";
									html += "<td>" + list[i]["info"][s]["NO"] + "</td>";
									html += "<td>" + list[i]["info"][s]["Level"] + "</td>";
									html += "<td>" + list[i]["info"][s]["BattlePower"] + "</td>";
									html += "<td>" + list[i]["info"][s]["TrunCount"] + "</td>";
									html += "<td>" + list[i]["info"][s]["Aptitude"] + "</td>";
									html += "<td>" + list[i]["info"][s]["PetGrow"] + "</td>";
									html += "<td>" + list[i]["info"][s]["HP"] + "</td>";
									html += "<td>" + list[i]["info"][s]["PhyAttack"] + "</td>";
									html += "<td>" + list[i]["info"][s]["PhyDefense"] + "</td>";
									html += "<td>" + list[i]["info"][s]["MagicAttack"] + "</td>";
									html += "<td>" + list[i]["info"][s]["MagicDefense"] + "</td>";
									html += "<td>" + list[i]["info"][s]["Hit"] + "</td>";
									html += "<td>" + list[i]["info"][s]["Dodge"] + "</td>";
									html += "<td>" + list[i]["info"][s]["Cruel"] + "</td>";
									html += "<td>" + list[i]["info"][s]["DeCruel"] + "</td>";
									html += "<td>" + list[i]["info"][s]["Power"] + "</td>";
									html += "<td>" + list[i]["info"][s]["Nimble"] + "</td>";
									html += "<td>" + list[i]["info"][s]["Physical"] + "</td>";
									html += "<td>" + list[i]["info"][s]["Intelligence"] + "</td>";
									html += "<td>" + list[i]["info"][s]["Spirit"] + "</td>";
									html += "<td>" + "暂无" + "</td>";
									html += "<td>" + "暂无" + "</td>";
									html += "<td>" + "暂无" + "</td>";
									html += "<td>" + "暂无" + "</td>";
									html += "<td>" + "暂无" + "</td>";
									html += "<td>" + "暂无" + "</td>";
									html += "<td>" + "暂无" + "</td>";
									html += "<td>" + "暂无" + "</td>";
									html += "<td>" + "暂无" + "</td>";
									html += "<td>" + list[i]["info"][s]["gif1"] + "-" + list[i]["info"][s]["gif1level"] + "级</td>";
									html += "<td>" + list[i]["info"][s]["gif2"] + "-" + list[i]["info"][s]["gif2level"] + "级</td>";
									html += "<td>" + list[i]["info"][s]["bskill1"] + "</td>";
									html += "</tr>";
									}
								//$(".mytable").data('excel',data.filename);//保存excel数据
								}

							}
							
							$("#pie_body").html(html);
							$("#pagehtml4").html(data.pageHtml);		//分页
							
							//table单双行交叉样式
							$("#pie_body tr:odd").css("background-color", "#edf2f7"); 
							$("#pie_body tr:even").css("background-color","#e0f0f0"); 
						
						//}
						
						$( "#startdate").val(data.startDate);
						$("#enddate").val(data.endDate);
						
					},
					error : function(){
						$("#chartdiv").html("<div style=\"text-align:center\">没有记录！</div>");
						$("#pie_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		},
		
		//坐骑概况
		getSkill : function(id){
			var self = this;
			$.ajax({
					id : id,
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/gmgrow/getskill',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val(),
						num : $("#ran").val()
					},
					success : function(data){
						chartData  = [];//清除数据，防止不断叠加
						
						var list = [];
						list = data.list;
						$("#pagehtml").show();
						var fields = ['level', 'count', 'qtlevel', 'cflevel','qllevel','jflevel','jllevel','thlevel','jglevel','bmlevel','yqhqlevel','jxlevel','gylevel','yqlevel','hh','nd'];
						page.INIT(50, list, fields, '#skill_body');
						$("#home_page").trigger('click');
						$("#total_page").html(1);
						$( "#startdate").val(data.startDate);
						$("#enddate").val(data.endDate);
						
						
					},
					error : function(){
						$("#chartdiv").html("<div style=\"text-align:center\">没有记录！</div>");
						$("#skill_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		},
		
		//星宿信息
		getStar : function(id){
			var self = this;
			$.ajax({
					id : id,
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/gmgrow/getstar',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val(),
						num : $("#ran").val()
					},
					success : function(data){
						chartData  = [];//清除数据，防止不断叠加
						var list = [];
						list = data.list;
						$("#pagehtml").show();
						var fields = ['u_name', 'one', 'two', 'three','four','five','six','seven','eight'];
						page.INIT(25, list, fields, '#star_body');
						$("#home_page").trigger('click');
						
						$( "#startdate").val(data.startDate);
						$("#enddate").val(data.endDate);
						
					},
					error : function(){
						$("#chartdiv").html("<div style=\"text-align:center\">没有记录！</div>");
						$("#star_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		},
		
		//神兵信息
		getMagic : function(id){
			var self = this;
			$.ajax({
					id : id,
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/gmgrow/getmagic',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val(),
						num : $("#ran").val()
					},
					success : function(data){
						chartData  = [];//清除数据，防止不断叠加
						
						var list = [];
						list = data.list;
						$("#pagehtml").show();
						
						var html = "";
							for(var i in list){
								if(typeof(list[i]['one']) != 'undefined'){
									var one = list[i]['one'];
								}else{
									var one = 0;
								}if(typeof(list[i]['two']) != 'undefined'){
									var two = list[i]['two'];
								}else{
									var two = 0;
								
								}if(typeof(list[i]['three']) != 'undefined'){
									var three = list[i]['three'];
								}else{
									var three = 0;
								
								}if(typeof(list[i]['four']) != 'undefined'){
									var four = list[i]['four'];
								}else{
									var four = 0;
								
								}if(typeof(list[i]['five']) != 'undefined'){
									var five = list[i]['five'];
								}else{
									var five = 0;
								
								}if(typeof(list[i]['six']) != 'undefined'){
									var six = list[i]['six'];
								}else{
									var six = 0;
								}
							
								html += "<tr>";
								html += "<td>" + list[i]["slevel"] + "</td>";
								html += "<td>" + one + "</td>";
								html += "<td>" + two + "</td>";
								html += "<td>" + three + "</td>";
								html += "<td>" + four + "</td>";
								html += "<td>" + five + "</td>";
								html +=	"<td>" + six + "</td>";
								html +=	"<td>" + list[i]["ming"] + "</td>";
								html += "<td>" + list[i]["mskill"] + "</td>";
								html += "<td>" + list[i]["ling"] + "</td>";
								html += "</tr>";
								$(".mytable").data('excel',data.filename);//保存excel数据

							}
							
							$("#magic_body").html(html);
							$("#pagehtml5").html(data.pageHtml);		//分页
							
							//table单双行交叉样式
							$("#magic_body tr:odd").css("background-color", "#edf2f7"); 
							$("#magic_body tr:even").css("background-color","#e0f0f0"); 
						
						$( "#startdate").val(data.startDate);
						$("#enddate").val(data.endDate);
						
					},
					error : function(){
						$("#chartdiv").html("<div style=\"text-align:center\">没有记录！</div>");
						$("#magic_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		},
		
		//国家谋略
		getTrick : function(id){
			var self = this;
			$.ajax({
					id : id,
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/gmgrow/gettrick',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val(),
						num : $("#ran").val()
					},
					success : function(data){
						chartData  = [];//清除数据，防止不断叠加
						
						var list = [];
						list = data.list;
						$("#pagehtml").show();
						var fields = ['u_name', 'sm', 'mz', 'sb','bj','jr'];
						page.INIT(25, list, fields, '#trick_body');
						$("#home_page").trigger('click');
						
						$( "#startdate").val(data.startDate);
						$("#enddate").val(data.endDate);
						
					},
					error : function(){
						$("#trick_body").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		},
		
	}
	
	var flag = true;	//标志，用于防止重复执行在线信息

	$(document).ready(function(){
		user_grow.INIT();
	})
	//跳到相应页面 
		var go = function(){
			var pagenum = $("#page").val();
			alert(pagenum);
			if(pagenum=='' || isNaN(pagenum) || pagenum <= 0){
				alert('请输入一个正整数！');
				$("#page").val(1);
			}else{
				user_grow.getEqui(pagenum);
			}
		}
		
		//分页ajax函数
		var formAjax = function(page){
			user_grow.getEqui(page);
		}
	
	</script>
</body>
</html>