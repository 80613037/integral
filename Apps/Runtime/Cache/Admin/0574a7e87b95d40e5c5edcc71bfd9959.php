<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?php echo ($CONF['mallTitle']); ?>后台管理中心</title>
      <link href="/Public/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <link href="/Apps/Admin/View/css/AdminLTE.css" rel="stylesheet" type="text/css" />
      <link rel="stylesheet" href="/Apps/Admin/View/css/daterangepicker/daterangepicker-bs3.css">
      <link href="/Apps/Admin/View/css/upload.css" rel="stylesheet" type="text/css" />
      <link rel="stylesheet" type="text/css" href="/Public/plugins/webuploader/webuploader.css" />
      <!--[if lt IE 9]>
      <script src="/Public/js/html5shiv.min.js"></script>
      <script src="/Public/js/respond.min.js"></script>
      <![endif]-->
      <script src="/Public/js/jquery.min.js"></script>
      <script src="/Public/plugins/bootstrap/js/bootstrap.min.js"></script>
      <script src="/Apps/Admin/View/js/daterangepicker.js"></script>
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
	   changAdCat(document.getElementById('adPositionId'));
	   $.formValidator.initConfig({
		   theme:'Default',mode:'AutoTip',formID:"myform",debug:true,submitOnce:true,onSuccess:function(){
				   edit();
			       return false;
			},onError:function(msg){
		}});
	   
	   $("#adPositionId").formValidator({onFocus:"请选择广告位置"}).inputValidator({min:1,onError: "请选择广告位置"});
	   $("#adName").formValidator({empty:false,onFocus:"请输入广告标题"}).inputValidator({min:1,onError: "请输入广告标题"});
	   $('#adDateRange').daterangepicker({format:'YYYY-MM-DD',separator:' 至 '});
	   <?php if($object['adId'] !=0 ): ?>getAreaList("areaId2",<?php echo ($object["areaId1"]); ?>,0,<?php echo ($object["areaId2"]); ?>,function(){
		   getAreaList("areaId3",'<?php echo ($object["areaId2"]); ?>',1,'<?php echo ($object["areaId3"]); ?>',function(){
			   getCommunitys('<?php echo ($object["communityId"]); ?>');
		   });
	   });
	   getAdPositions(<?php echo ($object["positionType"]); ?>,'<?php echo ($object["adPositionId"]); ?>');<?php endif; ?>
	   var uploading = null;
	   uploadFile({
	    	  server:"<?php echo U('Admin/Shops/uploadPic');?>",
	    	  pick:'#filePicker',
	    	  formData: {dir:'adspic'},
	    	  callback:function(f){
	    		  layer.close(uploading);
	    		  var json = WST.toJson(f);
	    		  $('#preview').attr('src',ThinkPHP.DOMAIN+"/"+json.file.savepath+json.file.savethumbname);
	    		  $('#adFile').val(json.file.savepath+json.file.savename);
	    		  $('#preview').show();
		      },
		      progress:function(rate){
		    	  uploading = WST.msg('正在上传图片，请稍后...');
		      }
	    });
   });
   function getAreaList(objId,parentId,t,id,callback){
	   var params = {};
	   params.parentId = parentId;
	   $('#'+objId).empty();
	   if(t<1)$('#areaId3 option[value!=""]').remove();
	   $('#communityId option[value!=""]').remove();
	   
	   var html = [];
	   $.post("<?php echo U('Admin/Areas/queryShowByList');?>",params,function(data,textStatus){
		    html.push('<option value="">请选择</option>');
			var json = WST.toJson(data);
			if(json.status=='1' && json.list && json.list.length>0){
				var opts = null;
				for(var i=0;i<json.list.length;i++){
					opts = json.list[i];
					html.push('<option value="'+opts.areaId+'" '+((id==opts.areaId)?'selected':'')+'>'+opts.areaName+'</option>');
				}
			}
			$('#'+objId).html(html.join(''));
			if(callback)callback();
	   });
	   
   }
   function getCommunitys(id){
		var v = $('#areaId3').val();
		if(v && v!=''){
			$('#communityId option[value!=""]').remove();
			$.post("<?php echo U('Admin/Communitys/queryByList');?>",{areaId3:v},function(data,textStatus){
				var json = data;
				if(json.list){
					var html = [];
					json = json.list;
					for(var i=0;i<json.length;i++){
						opts = json[i];
						html.push('<option value="'+opts.communityId+'" '+((id==opts.communityId)?'selected':'')+'>'+opts.communityName+'</option>');
				    }
					$('#communityId').append(html.join(''));
				}
			});
		}else{
			$('#communityId option[value!=""]').remove();
		}
	}
   function edit(){
	   var params = WST.fillForm('.ipt');
	   var date = $('#adDateRange').val().split(' 至 ');
	   params.adStartDate = date[0];
	   params.adEndDate = date[1];
	   if(params.adFile==''){
		   Plugins.Tips({title:'信息提示',icon:'error',content:'请上传广告图片!',timeout:1000});
		   return;
	   }
	   Plugins.waitTips({title:'信息提示',content:'正在提交数据，请稍后...'});
		$.post("<?php echo U('Admin/Ads/edit');?>",params,function(data,textStatus){
			var json = WST.toJson(data);
			if(json.status=='1'){
				Plugins.setWaitTipsMsg({ content:'操作成功',timeout:1000,callback:function(){
				   location.href='<?php echo U("Admin/Ads/index");?>';
				}});
			}else{
				Plugins.closeWindow();
				Plugins.Tips({title:'信息提示',icon:'error',content:'操作失败!',timeout:1000});
			}
		});
   }
   function getAdPositions(v,id){
	   if(v>-1){
		   $('#adPositionId option').each(function(){
			   if(parseInt($(this).attr('value'),10)<0)$(this).remove();
		   });
		   $.post("<?php echo U('Admin/AdPositions/queryByList');?>",{positionType:v},function(data,textStatus){
				var json = WST.toJson(data);
				if(json.status=='1' && json.list){
					var html = [];
					for(var i=0;i<json.list.length;i++){
						html.push("<option value='-"+json.list[i].positionId+"' w='"+json.list[i].positionWidth+"' h='"+json.list[i].positionHeight+"' "+((id==("-"+json.list[i].positionId))?"selected":"")+">"+json.list[i].positionName+"</option>");
					}
					$("#adPositionId").append(html.join(''));
				}
			});
	   }else{
		   $('#adPositionId option').each(function(){
			   if(parseInt($(this).attr('value'),10)<0)$(this).remove();
		   });
	   }
    }
   
   function changAdCat(obj){
	    if($('#adPositionId option:selected').val()=='0'){
	    	$("#img_size").html("");
	    	return;
	    }
		if(parseInt($('#adPositionId').val(),10)>0){
			if($('#positionType').val()==0){
				$("#img_size").html("210 x 275");
			}else{
				$("#img_size").html("");
			}
		}else{
			$("#img_size").html($('#adPositionId option:selected').attr('w')+"x"+$('#adPositionId option:selected').attr('h'));
		}
  }
   </script>
   <body class="wst-page">
   
       <form name="myform" method="post" id="myform" autocomplete="off">
        <input type='hidden' id='id' class='ipt' value='<?php echo ($object["adId"]); ?>'/>
        <table class="table table-hover table-striped table-bordered wst-form">
           <tr style="display:none;">
             <th align='right'>位置类型<font color='red'>*</font>：</th>
             <td>
             <select id='positionType' class='ipt' onchange='javascript:getAdPositions(this.value)'>
                <option value='-1'>请选择</option>
                <option value='0' <?php if($object['positionType'] == 0 ): ?>selected<?php endif; ?>>PC版</option>
                <?php if($CONF['isOpenWeiXin'] == 1 ): ?><option value='2' <?php if($object['positionType'] == 2 ): ?>selected<?php endif; ?>>微信版</option><?php endif; ?>
             </select>
             </td>
           </tr>
           <tr>
             <th align='right'>广告位置<font color='red'>*</font>：</th>
             <td>
             <select id='adPositionId' class='ipt' onchange="changAdCat(this)">
                <option value='0'>请选择</option>
                <?php if(is_array($positionList['root'])): $i = 0; $__LIST__ = $positionList['root'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo['positionId']); ?>' <?php if($object['adPositionId'] == '-'.$vo['positionId'] ): ?>selected<?php endif; ?>><?php echo ($vo['positionName']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
             </select>
             </td>
           </tr>
           <tr style="display: none;">
             <th align='right'>广告城市：</th>
             <td>
             <select id='areaId1' class='ipt' onchange='javascript:getAreaList("areaId2",this.value,0)'>
                <option value=''>请选择</option>
                <?php if(is_array($areaList)): $i = 0; $__LIST__ = $areaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo['areaId']); ?>' <?php if($object['areaId1'] == $vo['areaId'] ): ?>selected<?php endif; ?>><?php echo ($vo['areaName']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
             </select>
             <select id='areaId2' class='ipt' onchange='javascript:getAreaList("areaId3",this.value,1);'>
               <option value=''>请选择</option>
             </select>
             <select id='areaId3' class='ipt' onchange='javascript:getCommunitys();'>
               <option value=''>请选择</option>
             </select>
             <select id='communityId' class='ipt' >
               <option value=''>请选择</option>
             </select>
             (不选则默认整个商城)
             </td>
           </tr>
           <tr>
             <th width='120' align='right'>广告标题<font color='red'>*</font>：</th>
             <td><input type='text' id='adName' class="form-control wst-ipt ipt" value='<?php echo ($object["adName"]); ?>' maxLength='25'/></td>
           </tr>
           <tr style="height:70px;">
             <th align='right'>广告图片<font color='red'>*</font>：</th>
             <td>
             <div>
             	<div id="filePicker" style='margin-left:0px;float:left'>上传图片</div>
             	    <div style='margin-left:5px;float:left'>图片大小:<span id='img_size'></span> (px)，格式为 gif, jpg, jpeg, png</div>
             	</div>
             	
             </td>
           </tr>
           <tr>
             <th align='right'>预览图：</th>
             <td height='40'>
             	<div>
             	<?php if($object['adFile'] !='' ): ?><img height="100" id='preview' src='/<?php echo ($object["adFile"]); ?>'>
	            <?php else: ?>
                <img id='preview' src='' ref='' width='100' height='100' style='display:none'/><?php endif; ?>
                <input id="adFile" name="adFile" class="text wstipt ipt" tabindex="3" autocomplete="off" style="" type="hidden" value="<?php echo ($object["adFile"]); ?>"/>
                </div>
             	
             </td>
           </tr>
           <tr>
             <th align='right'>广告网址：</th>
             <td>
             <input type='text' id='adURL' class="form-control wst-ipt ipt" value='<?php echo ($object["adURL"]); ?>' placeholder="例：http://www.sjzhtkj.com"/>
             </td>
           </tr>
           <tr style="display:none;">
             <th align='right'>广告日期<font color='red'>*</font>：</th>
             <td>
             <input type='text' id='adDateRange' class="form-control" readonly='true' style='width:300px' value='2016-01-01 至 2060-01-01'/>
             </td>
           </tr>
           <tr>
             <th align='right'>广告排序号：</th>
             <td>
             <input type='text' id='adSort' class="form-control ipt"  value='<?php echo ($object["adSort"]); ?>' style='width:80px' onkeypress="return WST.isNumberKey(event)" onkeyup="javascript:WST.isChinese(this,1)" maxLength='8'/>
             </td>
           </tr>
           <tr>
             <td colspan='2' style='padding-left:250px;'>
                 <button type="submit" class="btn btn-success">保&nbsp;存</button>
                 <button type="button" class="btn btn-primary" onclick='javascript:location.href="<?php echo U('Admin/Ads/index');?>"'>返&nbsp;回</button>
             </td>
           </tr>
        </table>
       </form>
   </body>
</html>