<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>后台系统管理中心</title>
<link rel="stylesheet" href="/Apps/Admin/View/css/login_new.css">
<link href="/Public/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/Apps/Admin/View/css/style.css" />

<script src="/Public/js/jquery.js"></script>
<script src="/Public/js/Particleground.js"></script>
<!--[if lt IE 9]>
<script src="/Public/js/html5shiv.min.js"></script>
<script src="/Public/js/respond.min.js"></script>
<![endif]-->
<script src="/Public/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="/Public/js/common.js"></script>
<script src="/Public/plugins/plugins/plugins.js"></script>
<script>
var ThinkPHP = window.Think = {
    "ROOT"   : "",
    "APP"    : "/index.php",
    "PUBLIC" : "/Public",
    "DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>",
    "MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
    "VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
}
</script>
<script src="/Public/js/think.js"></script>

</head>
<body>
<div class="loginbox">
    <div class="logo"></div>
	<form action="" method="post" class="loginForm" id="loginform">
      <ul>
        <li><label>用户名</label><input type="text" name='loginName' id='loginName' autocomplete="off" size="35" style="float:left;"></li>
        <li><label>密码</label><input type="password" name='loginPwd' id='loginPwd' autocomplete="off" size="35" style="float:left;"></li>
        <li><label>&nbsp;</label><input type="checkbox" value="1" name="remember" id="remember" style="float:left; display:none;">
		<font style="float:left; padding:0 5px; display:none;" >记住密码</font></li>
        <li><input type="button" value="立即登录" class="submit_btn form-actions signin" value="进入管理中心" /></li>
      </ul>
        <input type="hidden" name="act" value="signin">
    </form>
</div>

<script>
   $(function(){
     // getVerify();
     $('.form-actions').click(function(){
        login();
     });
     $(document).keypress(function(e) { 
       if(e.which == 13) {  
         login();  
       } 
     }); 
   })
   function login(){
     var params = {};
     params.loginName = $.trim($('#loginName').val());
     params.loginPwd = $.trim($('#loginPwd').val());
     params.verify = $.trim($('#verfyCode').val());
     params.id = $('#id').val();
     // console.log(params);
     if(params.loginName==''){
       Plugins.Tips({title:'信息提示',icon:'error',content:'请输入账号!',timeout:1000});
       $('#loginName').focus();
       return;
     }
     if(params.loginPwd==''){
       Plugins.Tips({title:'信息提示',icon:'error',content:'请输入密码!',timeout:1000});
       $('#loginPwd').focus();
       return;
     }
//     if(params.verify==''){
//       Plugins.Tips({title:'信息提示',icon:'error',content:'请输入验证码!',timeout:1000});
//       $('#verfyCode').focus();
//       return;
//     }
     Plugins.waitTips({title:'信息提示',content:'正在登录，请稍后...'});
    $.post(Think.U("Admin/index/login"),params,function(data,textStatus){
      var json = WST.toJson(data);
      if(json.status=='1'){
        Plugins.setWaitTipsMsg({ content:'登录成功',timeout:1000,callback:function(){
          location.href=Think.U("Admin/Index/index");
        }});
      }else if(json.status=='-2'){
//        Plugins.closeWindow();
//        Plugins.Tips({title:'信息提示',icon:'error',content:'验证码错误!',timeout:1000});
//        getVerify();
      }else{
        Plugins.closeWindow();
        Plugins.Tips({title:'信息提示',icon:'error',content:'账号或密码错误!',timeout:1000});
        getVerify();
      }
    });
   }
   function getVerify() {
     $('.verifyImg').attr('src',Think.U('Admin/Index/getVerify','rnd='+Math.random()));
   }
</script>
<div id="cli_dialog_div"></div></body></html>