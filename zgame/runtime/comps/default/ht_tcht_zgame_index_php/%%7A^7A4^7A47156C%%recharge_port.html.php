<?php /* Smarty version 2.6.18, created on 2014-08-01 15:58:18
         compiled from recharge/recharge_port.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>充值记录查询</title>
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
							<td width="95%" class="tableleft">1、此页面用于给特定玩家充值</td>
						</tr>
						<tr>
							<td>
								<hr/>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div>
				<table style="margin:0 auto;width:400px;" >
					<tr>
						<td class="tableright">
							<span>账号：</span>
						</td>
						<td>
							<span><input type="text" id="account"/></span>
						</td>
					</tr>
					<tr>
						<td class="tableright">
							<span>角色ID：</span>
						</td>
						<td>
							<span><input type="text" id="roleid"/></span>
						</td>
					</tr>
					<tr>
						<td class="tableright">
							<span>服数：</span>
						</td>
						<td>
							<span><input type="text" id="fushu"/></span>
						</td>
					</tr>
					<tr>
						<td class="tableright">
							<span>平台：</span>
						</td>
						<td>
							<span><input type="text" id="platform"/></span>
						</td>
					</tr>
					<tr>
						<td class="tableright">
							<span>充值金额：</span>
						</td>
						<td>
							<span><input type="text" id="money" /></span>
						</td>
					</tr>
					<tr>
						<td colspan='2' style="text-align:center">
							<input type="button" value="提交" />
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	
	
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/function.js" type="text/javascript"></script> 	
	<script type="text/javascript">
		var recharge_port = {
			INIT : function() {
				var self = this;
				
			}
		}
		$(document).ready(function(){
			recharge_port.INIT();
			showTitle("充值相关查询:充值接口");
		})
	</script>
</body>
</html>