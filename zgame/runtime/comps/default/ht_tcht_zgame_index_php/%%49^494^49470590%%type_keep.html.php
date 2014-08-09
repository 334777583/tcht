<?php /* Smarty version 2.6.18, created on 2014-07-02 13:54:05
         compiled from money/type_keep.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>渠道留存</title>
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
							<td width="95%" class="tableleft">1、该数据从开服第3天开始显示</td>
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
" attr="<?php echo $this->_tpl_vars['ip']['g_domain']; ?>
"><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
					<option value="0" >全部</option>
					</select>
					<span style="margin-left: 20px">日期:</span>
					<input type="text" id="enddate" class="input1" "/>
					<input type="button" value="查询" id="querybtn"/>&nbsp;&nbsp;
					<input type="button" value="导出Excel" id="exportbtn"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div>
				<table  class="mytable" cellspacing="0" align="center" id="dtable">
					<thead>
						<tr>
							<th>渠道ID</th>
							<th>注册用户（去重）</th>
							<th>次日留存</th>
							<th>三日留存</th>
							<th>七日留存</th>
							<th>双周留存</th>
							<th>双周留存均值</th>
						</tr>
					</thead>
					<tbody id="dtatr_body">
					</tbody>
				</table>
			</div>
			<div style="float:right;margin-right:20px;" id="pagehtml">
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
		var user_pay = {
			INIT : function(){
				var self = this;
				
				//时间插件
				$("#startdate").datepicker();
				$("#enddate").datepicker();
				page.listen();
				
				showTitle("游戏数据统计:渠道留存");
				
				//导出excel
			$("#exportbtn").click(function(){
				var ip = $("#sip").val();
				var startdate = $("#startdate").val();
				var enddate = $("#enddate").val();
				window.location.href = "<?php echo $this->_tpl_vars['logicApp']; ?>
/propanalysis/writeExcel/ip/"+ip+"/startdate/"+startdate+"/enddate/"+enddate;
			});
				
				$("#querybtn").click(function(){
					self.showRole();
				});
				self.showRole();
				
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
			
			showRole : function() {
				$.ajax({
					'type' : 'GET',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/typekeep/keepList',
					dataType : 'json',
					data : {
						enddate : $("#enddate").val(),
						ip : $("#sip").val()
					},
					beforeSend : function() {
						$("#dtatr_body").html("<tr><td colspan='8'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
					},
					success : function (data) {
					
						if(typeof(data.list) != 'undefined'){
						var list = data.list;
						}
						$("#pagehtml").show();
						var fields = ['tyid', 'count', 'two', 'thr','sev', 'twee','twesuv'];
						page.INIT(10, list, fields, '#dtatr_body');
						$("#home_page").trigger('click');
						$("#enddate").val(data.endDate);
					},
					error : function () {
						$("#pagehtml").hide();
						$("#dtatr_body").html("<tr><td colspan='8'>没有数据！</td></tr>");
					}
				})
			},
			
		}
		$(document).ready(function(){
			user_pay.INIT();
		})
		
	</script>
</body>
</html>