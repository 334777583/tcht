<?php /* Smarty version 2.6.18, created on 2014-08-07 16:56:28
         compiled from recharge/dailyreport.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>每日报表</title>
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
							<td width="95%" class="tableleft">1、**********</td>
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
"><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</option>
							<?php endforeach; endif; unset($_from); ?>
							<option value="0" >全部</option>
						</select>
					</label>
					<span>时间:</span>
					<select id="date">
						<option value='2014-07'>2014-07</option>
						<option value='2014-08'>2014-08</option>
						<option value='2014-09'>2014-09</option>
						<option value='2014-10'>2014-10</option>
						<option value='2014-11'>2014-11</option>
						<option value='2014-12'>2014-12</option>
					</select>
					<input type="button" value="查询" id="jishi" style="margin-left:20px"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div id="tabs-1" class="tabitem">	
				<div>
					<table width="100%" cellspacing="0" align="center">
						<tr>
							<td>
								<table  class="mytable" cellspacing="0" align="center">
									<thead>
										<tr>
											<th>日期</th>
											<th>首充人数</th>
											<th>首充元宝</th>
											<th>首充金额（Q点*游戏币*金券）</th>
											<th>ARPU</th>
										</tr>
										<tbody id="dtatr_body">
										</tbody>
									</thead>
								</table>
							</td>
							<td>&nbsp;</td>
							<td>
								<table  class="mytable" cellspacing="0" align="center">
									<thead>
										<tr>
											<th>总金额</th>
											<th colspan="3" id="money">&nbsp;</th>
											<th>总元宝数</th>
											<th colspan="3" id="moneyother">&nbsp;</th>
										</tr>
										<tr>
											<th>日期</th>
											<th>上线人数</th>
											<th>充值人数</th>
											<th>充值次数</th>
											<th>元宝总数</th>
											<th>金钱总额(Q点*游戏币*金券)</th>
											<th>ARPU</th>
											<th>付费率</th>
										</tr>
									</thead>
									<tbody id="dtatr_body1">
									</tbody>
								</table>
							</td>
						</tr>
					</table>
					
				</div>
				
			</div>
			
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
/js/function.js" type="text/javascript"></script> 	
	<script type="text/javascript">
		var recharge_query = {
			INIT : function(){
				var self = this;
				showTitle("充值数据分析:每日报表");
				
				$("#jishi").click(function(){
					self.show();
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
			
			show : function() {
				$.ajax({
					type : 'POST',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/dailyreport/getDailyReport',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						date : $('#date').val(),
						endDate :	$('#enddate').val()
					},
					beforeSend : function() {
						$("#dtatr_body").html("<tr><td colspan='10'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
						$("#dtatr_body1").html("<tr><td colspan='10'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
					},
					success : function (data) {
						var first = data.first;
						if(typeof(first) != 'undefined' && first.length > 0) {
							var tbody = "";
							for(var i in first) {
								tbody += "<tr>";
								tbody += "<td>" + first[i]["date"] + "</td>";
								tbody += "<td>" + first[i]["num"] + "</td>";
								tbody += "<td>" + first[i]["money"] + "</td>";
								tbody += "<td>" + first[i]["RMB"]+'*'+ first[i]["coins"]+'*'+first[i]["pubacce"]+ "</td>";
								tbody += "<td>" + Math.round(first[i]["money"]*100/first[i]["num"])/100 + "</td>";
								tbody += "</tr>";
							}
							$("#dtatr_body").html(tbody);
						}else {
							$("#dtatr_body").html("<tr><td colspan='5'>没有数据！</td></tr>");
						}
						
						var allList = data.allList;
						if(typeof(allList) != 'undefined'||typeof(allList) != '') {
							var tbody1 = "";
							var moneyAll = 0;
							var c_amtAll = 0;
							var payamt_coins_all = 0;
							var pubacct_payamt_coins_all = 0;
							for(var i in allList) {
								moneyAll += parseInt(allList[i]["money"]);
								c_amtAll += parseInt(allList[i]["c_amt"]);
								payamt_coins_all += parseInt(allList[i]["payamt_coins"]);
								pubacct_payamt_coins_all += parseInt(allList[i]["pubacct_payamt_coins"]);
								tbody1 += "<tr>";
								tbody1 += "<td>" + allList[i]["date"] + "</td>";
								tbody1 += "<td>" + allList[i]["loginnum"] + "</td>";
								tbody1 += "<td>" + allList[i]["paypeoplenum"] + "</td>";
								tbody1 += "<td>" + allList[i]["paynum"] + "</td>";
								tbody1 += "<td>" + allList[i]["money"] + "</td>";
								tbody1 += "<td>" + allList[i]["c_amt"]+'*'+ allList[i]["payamt_coins"] + '*' +allList[i]["pubacct_payamt_coins"]+ "</td>";
								tbody1 += "<td>" + Math.round(allList[i]["money"]*100/allList[i]["paypeoplenum"])/100  + "</td>";
								if(allList[i]["loginnum"]==0){
									tbody1 += "<td>0</td>";
								}else {
									tbody1 += "<td>" + Math.round(allList[i]["money"]*100/allList[i]["loginnum"])/100 + "</td>";
								}
								tbody1 += "</tr>";
							}
							$("#dtatr_body1").html(tbody1);
							$("#money").html(moneyAll);
							$("#moneyother").html(c_amtAll+'*'+payamt_coins_all+'*'+pubacct_payamt_coins_all);
						}else {
							$("#dtatr_body1").html("<tr><td colspan='8'>没有数据！</td></tr>");
						}
					},
					error : function () {
						$("#dtatr_body1").html("<tr><td colspan='8'>没有数据！</td></tr>");
					}
				});
			
			
			}
		}
		
		
		$(document).ready(function(){
			recharge_query.INIT();
		});
	</script>
</body>
</html>