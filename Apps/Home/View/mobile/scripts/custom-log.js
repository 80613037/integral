//注册页面发送手机验证码
var countdown = 60;
function settime(obj) {
    if (countdown == 0) {   	
        obj.removeAttribute("disabled");
        $(obj).val( "获取验证码" );
        countdown = 60;
        return;
    } else {
        obj.setAttribute("disabled", true);
        $(obj).val("重新发送(" + countdown + ")");
        countdown--;
    }
    setTimeout(function () {
        settime(obj)
    }, 1000)
}

//注册页面判断手机号，密码，验证码
		function check(){
			if($('#loginName').val() == null || $('#loginName').val() == "" || $('#loginName').val().length != 11){
				layer.msg("请输入11位手机号！");
				return;
			}
            if(!(/^1[34578]\d{9}$/.test($('#loginName').val()))){ layer.msg("手机号码有误，请重填!"); return false; }
            if($('#loginPwd').val() == null || $('#loginPwd').val() == ""){
				layer.msg("请输入密码！");
				return;
			}
			if($('#mobileCode').val() == null || $('#mobileCode').val() == ""){
				layer.msg("请输入短信验证码！");
				return;
			}

			$.get('/index.php?m=Home&c=Users&a=toRegist&phone='+$('#loginName').val()+'&code='+$('#mobileCode').val()+'&pwd='+$('#loginPwd').val(),function(data){
                var json = WST.toJson(data);
				if(json.status == 1){
                    layer.msg("注册成功！");
                    setTimeout('location.href="/index.php?m=Home&c=Users&a=login"', 2000);
				}else if(json.status == -2){
                    layer.msg("该手机号已经注册！");
                }else if(json.status == -4){
                    layer.msg("验证码错误！");
                }else{
					layer.msg("请稍后再试!");
				}
			});
		}
//登录页面判断手机号，密码
		function checklog(){
			if($('#phone').val() == null || $('#phone').val() == ""){
				layer.msg("请输入手机号!!!");
				return;
			}
			if($('#pwd').val() == null || $('#pwd').val() == ""){
				layer.msg("请输入密码");
				return;
			}
				if(!(/^1[34578]\d{9}$/.test($('#phone').val()))){ layer.msg("手机号码有误，请重填"); return false; }			
		}
//忘记密码页面判断手机号，密码
		function checkreset(){
			if($('#phone').val() == null || $('#phone').val() == ""){
                layer.msg("请输入11位手机号！");
				return;
			}
            if(!(/^1[34578]\d{9}$/.test($('#phone').val()))){ layer.msg("手机号码有误，请重填!"); return false; }
			if($('#resetcode').val() == null || $('#resetcode').val() == ""){
				layer.msg("请输入短信验证码！");
				return;
			}
            $.get('/index.php?m=Home&c=Users&a=findPass&phone='+$('#phone').val()+'&resetcode='+$('#resetcode').val()+'&step='+$('#step').val(),function(data){
            	var json = WST.toJson(data);
                if(json.status == 1){
                    // layer.msg("下一步！");
                    location.href="/index.php?m=Home&c=Users&a=forgetPass&ss=2&phone="+$('#phone').val();
                }else if(json.status == -3){
                    layer.msg("该手机号未注册！");
                }else if(json.status == -4){
                    layer.msg("验证码错误！");
                }else{
                    layer.msg("请稍后再试!");
                }
            });
		}
//重置密码页面判断手机号，密码
		function checkconfirm(){
			$pwdlength = $('#pwd').val().length;
            $pwdlength1 = $('#pwd1').val().length;
            if($('#pwd').val() == null || $('#pwd').val() == ""){
				layer.msg("请输入密码！");
				return;
			}
			if(($pwdlength < 6) || ($pwdlength > 16)){
                layer.msg("请输入6-16位数字或字母！");
                return;
            }
            if($('#pwd1').val() == null || $('#pwd1').val() == ""){
				layer.msg("请再次输入密码！");
				return;
			}
			if($('#pwd1').val()!==$('#pwd').val()){
				layer.msg("两次密码不一致！");
				return;				
			}
            $.get('/index.php?m=Home&c=Users&a=findPass&phone='+$('#loginName').val()+'&pwd='+$('#pwd').val()+'&step='+$('#step').val(),function(data){
                var json = WST.toJson(data);
                if(json.status == 1){
                    layer.msg("修改成功！");
                    setTimeout('location.href="/index.php?m=Home&c=Users&a=login"', 2000);
                }else{
                    layer.msg("请稍后再试!");
                }
            });
		}		
//勾选注册协议的效果
$(".reginf i").click(function(){
	$(this).toggleClass("sel");
	if($(this).hasClass("sel")){
		$(".verifi-code-btn").addClass("sel");
		$(".submit-bar .submit-btn").addClass("sel");
		$('input.verifi-code-btn').removeAttr("disabled");
		$('button.submit-btn').removeAttr("disabled");
	}else{
		$(".verifi-code-btn").removeClass("sel");
		$(".submit-bar .submit-btn").removeClass("sel");
		$('input.verifi-code-btn').attr("disabled","disabled");
		$('button.submit-btn').attr("disabled","disabled");
	}
});
