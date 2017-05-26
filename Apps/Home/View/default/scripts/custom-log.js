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
			if($('#phone').val() == null || $('#phone').val() == ""){
				alert("请输入手机号!");
				return;
			}
			if($('#pwd').val() == null || $('#pwd').val() == ""){
				alert("请输入密码");
				return;
			}
			if($('#code').val() == null || $('#code').val() == ""){
				alert("请输入短信验证码");
				return;
			}
				if(!(/^1[34578]\d{9}$/.test($('#phone').val()))){ alert("手机号码有误，请重填"); return false; }
			$.get('${basePath}/wx/user/cC/'+$('#phone').val()+'/'+$('#code').val(),function(data){
				if(data.success){
					if(data.code == 1){
						if($('#phone').val() == null || $('#phone').val() == ""){
							alert("请输入手机号!!");
							return;
						}
						if($('#pwd').val() == null || $('#pwd').val() == ""){
							alert("请输入密码");
							return;
						}
						if($('#code').val() == null || $('#code').val() == ""){
							alert("请输入短信验证码");
							return;
						}
						if(!(/^1[34578]\d{9}$/.test($('#phone').val()))){ alert("手机号码有误，请重填"); return false; }
						if(!$(".reginf i").hasClass("sel")){
							alert("请先同意用户注册协议!");
							return;
						}
						alert("注册成功");
						$("#form1").submit();
					}else{
						alert("验证码错误");
					}
				}else{
					alert("请稍后再试");
				}
			});
		}
//登录页面判断手机号，密码
		function checklog(){
			if($('#phone').val() == null || $('#phone').val() == ""){
				alert("请输入手机号3");
				return;
			}
			if($('#pwd').val() == null || $('#pwd').val() == ""){
				alert("请输入密码");
				return;
			}
				if(!(/^1[34578]\d{9}$/.test($('#phone').val()))){ alert("手机号码有误，请重填"); return false; }			
		}
//忘记密码页面判断手机号，密码
		function checkreset(){
			if($('#phone').val() == null || $('#phone').val() == ""){
				alert("请输入手机号4");
				return;
			}
			if($('#code').val() == null || $('#code').val() == ""){
				alert("请输入短信验证码");
				return;
			}
				if(!(/^1[34578]\d{9}$/.test($('#phone').val()))){ alert("手机号码有误，请重填"); return false; }			
		}
//重置密码页面判断手机号，密码
		function checkconfirm(){
			if($('#pwd').val() == null || $('#pwd').val() == ""){
				alert("请输入密码");
				return;
			}	
			if($('#pwd1').val() == null || $('#pwd1').val() == ""){
				alert("请再次输入密码");
				return;
			}	
			if($('#pwd1').val()!==$('#pwd').val()){
				alert("两次密码不一致");
				return;				
			}
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
