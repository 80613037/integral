$(function(){
	$('#chkRememberMe').change(function(){
		if($('#chkRememberMe:checked').val()){
			$("#rememberPwd").val(1);
		}else{
			$("#rememberPwd").val(0);
		}
	});
	$(document).keypress(function(e) { 
		   if(e.which == 13) {  
			   checkLoginInfo();  
		   } 
	});
})

function checkLoginInfo(){
	var loginName = $.trim($('#phone').val());
	var loginPwd = $.trim($('#pwd').val());
    var from = GetQueryString("from");
	if(loginName == null || loginName == ""){
		layer.msg("请输入手机号！");
		return;
	}
	if(!(/^1[34578]\d{9}$/.test(loginName))){ layer.msg("手机号码有误，请重新输入！"); return false; }
	if(loginPwd == null || loginPwd == ""){
		layer.msg("请输入密码！");
		return;
	}
	
	$.post('/index.php?m=Home&c=Users&a=checkLogin',{loginName:loginName,loginPwd:loginPwd,from:from},function(data,textStatus){
		var json = WST.toJson(data);
		if(json.status=='1'){
			layer.msg("登录成功！");
			window.location.href=json.refer;
		}else if(json.status=='-2'){
			layer.msg("您输入的密码错误，请重新输入！");
		}else if(json.status=='-3'){
			layer.msg("您的手机号未注册，请注册!");
		}
	});
	return true;
}

/**
 * 参数
 * @param name
 * @returns {null}
 * @constructor
 */
function GetQueryString(name) {
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if(r!=null)return  unescape(r[2]); return null;
}

function logout(){
    jQuery.post(('index.php?m=Home&c=Users&a=logout'),{},function(rsp) {
        layer.msg("成功退出！");
        setTimeout("window.location.href='index.php?m=Home&c=Users&a=login'",3000);
        //location.reload();
    });
}
