<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?php echo ($CONF['shopTitle']['fieldValue']); ?>后台管理中心</title>
      <link href="/Public/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <link href="/Apps/Admin/View/css/AdminLTE.css" rel="stylesheet" type="text/css" />
      <link href="/Apps/Admin/View/css/upload.css" rel="stylesheet" type="text/css" />
      <link rel="stylesheet" type="text/css" href="/Public/plugins/webuploader/webuploader.css" />
      <!--[if lt IE 9]>
      <script src="/Public/js/html5shiv.min.js"></script>
      <script src="/Public/js/respond.min.js"></script>
      <![endif]-->
      <script src="/Public/js/jquery.min.js"></script>
      <script src="/Public/plugins/bootstrap/js/bootstrap.min.js"></script>
      <script src="/Public/js/common.js"></script>
      <script src="/Public/plugins/plugins/plugins.js"></script>
      <script src="/Public/plugins/formValidator/formValidator-4.1.3.js"></script>
      <script type="text/javascript" src="/Apps/Admin/View/js/upload.js"></script>
      <script type="text/javascript" src="/Public/plugins/webuploader/webuploader.js"></script>
      <script src="/Public/plugins/layer/layer.min.js"></script>
   </head>
   <script>
   var ThinkPHP = window.Think = {
	        "ROOT"   : "",
	        "PUBLIC" : "/Public",
	        "DOMAIN" : "<?php echo WSTDomain();?>"
	};

   $(function () {
	   $.formValidator.initConfig({
		   theme:'Default',mode:'AutoTip',formID:"myform",debug:true,submitOnce:true,onSuccess:function(){
				   edit();
			       return false;
			}});
	   
		$("#userPhone").inputValidator({min:0,max:25,onError:"你输入的手机号码非法,请确认"}).regexValidator({
			regExp:"mobile",dataType:"enum",onError:"手机号码格式错误"
		}).ajaxValidator({
			dataType : "json",
			async : true,
			url : "<?php echo U('Admin/Users/checkLoginKey');?>",
			success : function(data){
				var json = WST.toJson(data);
	            if( json.status == "1" ) {
	                return true;
				} else {
	                return false;
				}
				return "该手机号码已被使用";
			},
			buttons: $("#dosubmit"),
			onError : "该手机号码已存在。",
			onWait : "请稍候..."
		}).defaultPassed().unFormValidator(true);
		$("#userEmail").inputValidator({min:0,max:25,onError:"你输入的邮箱长度非法,请确认"}).regexValidator({
		       regExp:"email",dataType:"enum",onError:"邮箱格式错误"
			}).ajaxValidator({
				dataType : "json",
				async : true,
				url : "<?php echo U('Admin/Users/checkLoginKey');?>",
				success : function(data){
					var json = WST.toJson(data);
		            if( json.status == "1" ) {
		                return true;
					} else {
		                return false;
					}
					return "该电子邮箱已被使用";
				},
				buttons: $("#dosubmit"),
				onError : "该账号已存在。",
				onWait : "请稍候..."
			}).defaultPassed().unFormValidator(true);

		$("#userQQ").formValidator({
			empty:true,onShow:"",onFocus:"QQ号码只能是数字"
			}).regexValidator({
				regExp:"num1",dataType:"enum",onError:"QQ号码格式错误"
			});
		
		$("#userPhone").blur(function(){
			  if($("#userPhone").val()==''){
				  $("#userPhone").unFormValidator(true);
			  }else{
				  $("#userPhone").unFormValidator(false);
			  }
		});
		$("#userEmail").blur(function(){
			  if($("#userEmail").val()==''){
				  $("#userEmail").unFormValidator(true);
			  }else{
				  $("#userEmail").unFormValidator(false);
			  }
		});
		$("#userScore").formValidator({
			empty:true,onShow:"",onFocus:"当前积分只能是正整数（会员剩余积分）"
			}).functionValidator({
				fun:function(val,elem){
					if(parseInt(val,10)<=parseInt($("#userTotalScore").val(),10)){
					    return true;
				    }else{
					    return "会员积分不能大于历史积分";
				    }
				}
		});
		$("#userTotalScore").formValidator({
			empty:true,onShow:"",onFocus:"当前积分只能是正整数（会员消费后所得总积分）"
			}).functionValidator({
				fun:function(val,elem){
					if(parseInt(val,10)>=parseInt($("#userScore").val(),10)){
					    return true;
				    }else{
					    return "历史积分不能小于用户积分";
				    }
				}
			});
		var uploading = null;
		uploadFile({
	    	  server:"<?php echo U('Admin/Users/uploadPic');?>",
	    	  pick:'#filePicker',
	    	  formData: {dir:'users'},
	    	  callback:function(f){
	    		  layer.close(uploading);
	    		  var json = WST.toJson(f);
	    		  $('#preview').attr('src',ThinkPHP.DOMAIN+"/"+json.file.savepath+json.file.savethumbname);
	    		  $('#userPhoto').val(json.file.savepath+json.file.savename);
	    		  $('#preview').show();
		      },
		      progress:function(rate){
		  		 uploading = WST.msg('正在上传图片，请稍后...');
		      }
	    });
	
   });
   function edit(){
	   var params = {}; 
	   params.userName = $.trim($('#userName').val());
	   params.userQQ = $.trim($('#userQQ').val());
       params.userMoney = $.trim($('#userMoney').val());
	   params.userScore = $.trim($('#userScore').val());
	   params.userTotalScore = $.trim($('#userTotalScore').val());
	   params.userSex = $('input[name="userSex"]:checked').val();
	   params.userPhone = $.trim($('#userPhone').val());
	   params.userPhoto = $.trim($('#userPhoto').val());
	   params.userEmail = $.trim($('#userEmail').val());  
	   params.id = $('#id').val();
	   
	   Plugins.waitTips({title:'信息提示',content:'正在提交数据，请稍后...'});
		$.post("<?php echo U('Admin/Users/edit');?>",params,function(data,textStatus){
			var json = WST.toJson(data);
			if(json.status=='1'){
				Plugins.setWaitTipsMsg({ content:'操作成功',timeout:1000,callback:function(){
				   location.href='<?php echo U("Admin/Users/index");?>';
				}});
			}else if(json.status=='-2'){
				Plugins.setWaitTipsMsg({content:'用户手机号码或邮箱已存在!',timeout:1000});
			}else{
				Plugins.closeWindow();
				Plugins.Tips({title:'信息提示',icon:'error',content:'操作失败!',timeout:1000});
			}
		});
   }
   </script>
   <body class="wst-page">
   			
       <form name="myform" method="post" id="myform" autocomplete="off">
        <input type='hidden' id='id' value='<?php echo ($object["userId"]); ?>'/>
 
        <table class="table table-hover table-striped table-bordered wst-form">
           <tr>
             <th width='120' align='right'>账号：</th>
             <td><?php echo ($object["loginName"]); ?></td>
             <td rowspan='6'>
             	<div id="preview_Filedata">
                 <img id='preview' src='<?php if($object['userPhoto'] =='' ): ?>/Apps/Admin/View/img/staff.png<?php else: ?>/<?php echo ($object['userPhoto']); endif; ?>' height='160'/><br/>
	            </div>
             </td>
           </tr>
           <tr>
             <th align='right'>用户名：</th>
             <td>
             <input type='text' id='userName' class="form-control wst-ipt" value='<?php echo ($object["userName"]); ?>' maxLength='20'/>
             </td>
           </tr>
           <tr>
             <th align='right'>性别：</th>
             <td>
             <label>
             <input type='radio' id='userSex1' name='userSex' <?php if($object['userSex'] == 1 ): ?>checked<?php endif; ?> value='1'/>男
             </label>
             <label>
             <input type='radio' id='userSex2' name='userSex' <?php if($object['userSex'] == 2 ): ?>checked<?php endif; ?> value='2'/>女
             </label>
             <label>
             <input type='radio' id='userSex0' name='userSex' <?php if($object['userSex'] == 0 ): ?>checked<?php endif; ?> value='0'/>保密
             </label>
             </td>
           </tr>
           <tr>
             <th align='right'>手机号码：</th>
             <td>
             <input type='text' id='userPhone' name='userPhone' class="form-control wst-ipt" value='<?php echo ($object["userPhone"]); ?>' maxLength='11'/>
             </td>
           </tr>
           <tr>
             <th align='right'>电子邮箱：</th>
             <td>
             <input type='text' id='userEmail' name='userEmail' class="form-control wst-ipt"  value='<?php echo ($object["userEmail"]); ?>' maxLength='25'/>
             </td>
           </tr>
            <tr>
                <th align='right'>会员余额<font color='red'>*</font>：</th>
                <td >
                    <input type='text' id='userMoney' class="form-control wst-ipt-10"  value='<?php echo ($object["userMoney"]); ?>' onkeypress="return WST.isNumberKey(event)" onkeyup="javascript:WST.isChinese(this,1)" maxLength='8'/>
                </td>
            </tr>
           <tr>
             <th align='right'>会员积分<font color='red'>*</font>：</th>
             <td >
             <input type='text' id='userScore' class="form-control wst-ipt-10"  value='<?php echo ($object["userScore"]); ?>' onkeypress="return WST.isNumberKey(event)" onkeyup="javascript:WST.isChinese(this,1)" maxLength='8'/>
             </td>
           </tr>
           <tr>
             <th align='right'>会员历史积分<font color='red'>*</font>：</th>
             <td>
             <input type='text' id='userTotalScore' class="form-control wst-ipt-10"  value='<?php echo ($object["userTotalScore"]); ?>' onkeypress="return WST.isNumberKey(event)" onkeyup="javascript:WST.isChinese(this,1)" maxLength='8'/>
             </td>
           </tr>
           <tr>
             <th align='right'>QQ：</th>
             <td colspan='2'>
             <input type='text' id='userQQ' class="form-control wst-ipt"  value='<?php echo ($object["userQQ"]); ?>' onkeypress="return WST.isNumberKey(event)" onkeyup="javascript:WST.isChinese(this,1)" maxLength='15'/>
             </td>
           </tr>
            <tr style="height:80px;">
	             <th align='right'>用户头像：</th>
	             <td colspan='2'>
	             	<div>
		          		<div id="filePicker" style='margin-left:0px;float:left'>上传头像</div>
             	    	<div style='margin-left:5px;float:left'>建议图片大小(1:1):150 x 150 (px)，格式为 gif, jpg, jpeg, png</div>
             	    	<input id="userPhoto" name="userPhoto" class="text wstipt" tabindex="3" autocomplete="off" type="hidden" value="<?php echo ($object["userPhoto"]); ?>"/>
             		</div>
	             </td>
	           </tr>
            <tr>
           <tr>
             <td colspan='3' style='padding-left:250px;'>
                 <button type="submit" class="btn btn-success">保&nbsp;存</button>
                 <button type="button" class="btn btn-primary" onclick='javascript:location.href="<?php echo U('Admin/Users/index');?>"'>返&nbsp;回</button>
             </td>
           </tr>
        </table>

        <table class="table table-hover table-striped table-bordered wst-form" style="width:40%;">
           <?php if(is_array($cityList)): $i = 0; $__LIST__ = $cityList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
               <td >
                   <b>收货地址：</b><?php if($vo['isDefault'] == 1): ?>(默认)<?php endif; echo ($vo['areaName1']); echo ($vo['areaName2']); echo ($vo['areaName3']); echo ($vo['communityName']); echo ($vo['address']); ?>

               </td>
               <td width='300'><b>收货人：</b><?php echo ($vo['userName']); ?>&nbsp;<br/><b>联系电话：</b><?php echo ($vo['userTel']); ?>&nbsp;<?php echo ($vo['userPhone']); ?></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </table>
       </form>
   </body>
</html>