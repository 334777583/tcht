/**
 * FileName: function.js
 * Description:公用函数
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-4-1 下午4:35:38
 * Version:1.00
 */

/**
 * @name: curentTime
 * @description: 获取当前时间
 * @param: null
 * @return: string（YYYY-MM-DD HH:MM:SS）
 * @author: xiaochengcheng
 * @create: 2013-05-04 14:26:50
**/
function curentTime()
{ 
    var now = new Date();
   
    var year = now.getFullYear();       //年
    var month = now.getMonth() + 1;     //月
    var day = now.getDate();            //日
   
    var hh = now.getHours();            //时
    var mm = now.getMinutes();          //分
    var ss = now.getSeconds();			//秒
   
    var clock = year + "-";
   
    if(month < 10)
        clock += "0";
   
    clock += month + "-";
   
    if(day < 10)
        clock += "0";
       
    clock += day + " ";
   
    if(hh < 10)
        clock += "0";
       
    clock += hh + ":";
    if (mm < 10) clock += '0'; 
    clock += mm + ":"; 
    
    if(ss < 10)
    clock += "0";
    
    clock += ss;
    return(clock); 
} 

/**
 * @name: curentByTime
 * @description: 根据时间戳获取当前时间
 * @param: int
 * @return: string（YYYY-MM-DD HH:MM:SS）
 * @author: xiaochengcheng
 * @create: 2013-05-04 14:26:50
**/
function curentByTime(time)
{ 
    var now = new Date(time);
    
    var year = now.getFullYear();       //年
    var month = now.getMonth() + 1;     //月
    var day = now.getDate();            //日
   
    var hh = now.getHours();            //时
    var mm = now.getMinutes();          //分
    var ss = now.getSeconds();			//秒
   
    var clock = year + "-";
   
    if(month < 10)
        clock += "0";
   
    clock += month + "-";
   
    if(day < 10)
        clock += "0";
       
    clock += day + " ";
   
    if(hh < 10)
        clock += "0";
       
    clock += hh + ":";
    if (mm < 10) clock += '0'; 
    clock += mm + ":"; 
    
    if(ss < 10)
    clock += "0";
    
    clock += ss;
    return(clock); 
} 


/**
 * @name: curentDate
 * @description: 获取当前日期
 * @param: null
 * @return: string（YYYY-MM-DD）
 * @author: xiaochengcheng
 * @create: 2013-05-04 14:26:50
**/
function curentDate()
{ 
    var now = new Date();
   
    var year = now.getFullYear();       //年
    var month = now.getMonth() + 1;     //月
    var day = now.getDate();            //日
   
    var hh = now.getHours();            //时
    var mm = now.getMinutes();          //分
   
    var clock = year + "-";
   
    if(month < 10)
        clock += "0";
   
    clock += month + "-";
   
    if(day < 10)
        clock += "0";
       
    clock += day;
   
    return(clock); 
} 


/**
 * @name: parseDate
 * @description: 把字符串转换成Date对象
 * @param: string(参数格式:YYYY-MM-DD)
 * @return: Date
 * @author: xiaochengcheng
 * @create: 2013-05-04 14:26:50
**/
var parseDate = function(dataString){
	var arr = new Array();
	arr =  dataString.split("-")
    var date = new Date(Number(arr[0]), Number(arr[1]) - 1, Number(arr[2]));
    return date;
}


/**
 * @name: validator
 * @description: 验证开始和结束时间
 * @param: startdate(开始时间ID)
 * @param: enddate(结束时间ID)
 * @return: boolen
 * @author: xiaochengcheng
 * @create: 2013-05-04 14:26:50
**/
function validator(startdate,enddate){
	var isok = true;
	var reg = /\d{4}-\d{2}-\d{2}/;
	var start = $("#"+startdate).val();
	var end = $("#"+enddate).val();
	if(start == ""){
		alert("请输入开始时间！");
		isok = false;
	}else if(end == ""){
		alert("请输入结束时间！");
		isok = false;
	}else if(!reg.test(start) || !reg.test(end)){
		alert("请输入格式为YYYY-MM-DD的时间");
		isok = false;
	}else if(start>end){
		alert("结束时间要大于开始时间！");
		isok = false;
	}
	return isok;
}


/**
 * @name: check_date
 * @description: 验证开始和结束时间(允许为空)
 * @param: startdate(开始时间ID)
 * @param: enddate(结束时间ID)
 * @return: boolen
 * @author: xiaochengcheng
 * @create: 2013-05-04 14:26:50
**/
function check_date(startdate,enddate){
	var isok = true;
	var reg = /\d{4}-\d{2}-\d{2}/;
	var start = $("#"+startdate).val();
	var end = $("#"+enddate).val();
	if(start != ""){
		if(end == ""){
			alert("请输入结束时间！");
			isok = false;
		}else if(!reg.test(start) || !reg.test(end)){
			alert("请输入格式为YYYY-MM-DD的时间");
			isok = false;
		}else if(start>end){
			alert("结束时间要大于开始时间！");
			isok = false;
		}
	}
	return isok;
}
 
/**
 * @name: float_n
 * @description: 取小数点后n位
 * @param: num,n
 * @return: float
 * @author: xiaochengcheng
 * @create: 2013-05-04 14:26:50
**/
function float_n(num,n)
{
    var pos = num.indexOf(".");
	if( pos == -1){
		pos = 0;
	}
    var four = parseFloat(num.substr(0,pos+n+1));
	
	if(! parseFloat(four)){
		n += 1;
		return float_n(num,n);
	}
	return four;
} 

/**
 * @name: showTitle
 * @description: 显示当前页面栏目位置
 * @param: s(string)
 * @return: null
 * @author: xiaochengcheng
 * @create: 2013-05-04 14:26:50
**/
function showTitle(s) {
	var text = "";
	if(s){
		text = s;
	}
	$(window.parent.document).find("#tab_info").html(text);
}

/**
 * @name: toformat
 * @description: 将单位为秒的时间转化成HH:mm:ss的格式
 * @param: s(string)
 * @return: HH:mm:ss
 * @author: xiaochengcheng
 * @create: 2013-6-17 11:19:41
**/
function toformat(s) {
	var s = parseInt(s);
	var hour = 0;
	var min = 0;
	var second = 0;
	var delimiter = ":";
	if (s >= 3600) {      			//小时    
		hour = Math.floor(s/3600);
		s = s - 3600 * hour;	
	} 
	if (s >= 60 && s < 3600) {  	//分钟	     
		min = Math.floor(s/60);
		s = s - 60 * min;
	}

	second = s;						//秒
	
	if(hour < 10) {
		hour = "0" + hour;
	}
	
	if(min < 10) {
		min = "0" + min;
	}

	if(second < 10) {
		second = "0" + second;
	}	
	
	return hour+delimiter+min+delimiter+second;

}