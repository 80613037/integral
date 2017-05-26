<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?php echo ($CONF['mallTitle']); ?>后台管理中心</title>
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
      <script type="text/javascript" src="/Apps/Admin/View/js/upload.js"></script>
	  <script type="text/javascript" src="/Public/plugins/webuploader/webuploader.js"></script>
      <script src="/Public/plugins/layer/layer.min.js"></script>
      
   <script>
   var ThinkPHP = window.Think = {
	        "ROOT"   : "",
	        "PUBLIC" : "/Public",
	        "DOMAIN" : "<?php echo WSTDomain();?>"
	};
   function getAreaList(parentId){
	   var params = {};
	   params.parentId = parentId;
	   var id = $('#defaultCity_hidden').val();
	   $('#defaultCity').empty();
	   var html = [];
	   $.post("<?php echo U('Admin/Areas/queryByList');?>",params,function(data,textStatus){
		    html.push('<option value="">请选择</option>');
			var json = WST.toJson(data);
			if(json.status=='1' && json.list.length>0){
				var opts = null;
				for(var i=0;i<json.list.length;i++){
					opts = json.list[i];
					html.push('<option value="'+opts.areaId+'" '+((id==opts.areaId)?'selected':'')+'>'+opts.areaName+'</option>');
				}
			}
			$('#defaultCity').html(html.join(''));
	   });
   }

      $(function(){
    	  $('#myTab a').click(function (e) {
    		  e.preventDefault();
    		  $(this).tab('show');
    	  });
    	  <?php if($areaId1!=0){ ?>
     	   getAreaList('<?php echo ($areaId1); ?>');
     	  <?php } ?>  
    	  <?php if($CONF['mallLicense']==''){ ?>
    	  //$('#mallCopyright').attr('disabled',true);
    	  <?php } ?>
    	  <?php foreach($configs as $key =>$cf){ foreach($cf as $key =>$vo){ if($vo['fieldType']=='upload'){ ?>
    	  <?php }}} ?>
    	  
    	var uploading = null;
  		uploadFile({
  	    	  server:"<?php echo U('Admin/Index/uploadPic');?>",
  	    	  pick:'#filePicker',
  	    	  formData: {dir:'mall'},
  	    	  callback:function(f){
  	    		  layer.close(uploading);
  	    		  var json = WST.toJson(f);
  	    		  $('#preview').attr('src',ThinkPHP.DOMAIN+"/"+json.file.savepath+json.file.savethumbname);
  	    		  $('#friendlinkIco').val(json.file.savepath+json.file.savename);
  	    		  $('#preview').show();
  		      },
  		      progress:function(rate){
  		  		 uploading = WST.msg('正在上传图片，请稍后...');
  		      }
  	    });
  		
      })
      function save(){
    		var params = {};
    		<?php foreach($configs as $key =>$cf){ foreach($cf as $key =>$vo){ if($vo['fieldType']=='radio'){ ?>
    			params.<?php echo ($vo['fieldCode']); ?> = $("input:radio[name='<?php echo ($vo['fieldCode']); ?>']:checked").val();
    		<?php }else{ ?>
    			params.<?php echo ($vo['fieldCode']); ?> = $.trim($('#<?php echo ($vo['fieldCode']); ?>').val());
    		<?php }}} ?>
    		if(params.defaultCity==''){
//    			Plugins.Tips({title:'信息提示',icon:'error',content:'请选择商城的默认城市!',timeout:1000});
//    			return;
    		}
    		Plugins.waitTips({title:'信息提示',content:'正在提交数据，请稍后...'});
    		$.post("<?php echo U('Admin/index/saveMallConfig');?>",params,function(data,textStatus){
    			var json = WST.toJson(data);
    			if(json.status=='1'){
    				Plugins.setWaitTipsMsg({content:'操作成功',timeout:1000,callback:function(){}});
    			}else{
    				Plugins.closeWindow();
    				Plugins.Tips({title:'信息提示',icon:'error',content:'操作失败!',timeout:1000});
    			}
    		});
    	}
      </script>
   </head>
   <body>
      <ul id="myTab" class="nav nav-tabs wst-tab" role="tablist">
	      <li class="active"><a href="#tabc0" role="tab" data-toggle="tab">商城设置</a></li>
	      <!--<li><a href="#tabc1" role="tab" data-toggle="tab">邮件设置</a></li>-->
	      <li><a href="#tabc2" role="tab" data-toggle="tab">短信设置</a></li>
	      <li><a href="#tabc3" role="tab" data-toggle="tab">运营设置</a></li>
	      <!--<li><a href="#tabc4" role="tab" data-toggle="tab">登录设置</a></li>-->
	      <li><a href="#tabc5" role="tab" data-toggle="tab">微信公众号设置</a></li>
	  </ul>
      <div class='tab-content wst-tab-content'>
      	  <iframe name="upload" style="display:none"></iframe>
          <?php if(is_array($configs)): foreach($configs as $k=>$vo): ?><div class='tab-pane <?php if($k == 0): ?>active in<?php endif; ?> fade wst-tab-pane' id='tabc<?php echo ($k); ?>'>
          <?php if($k==0): endif; ?>
            
               <table class='table table-hover table-striped table-bordered wst-form'>
               <?php if(is_array($vo)): foreach($vo as $key=>$v): ?><tr>
                  <th width='150'><?php echo ($v['fieldName']); ?>：</th>
                  <td>
                     <?php if($v['fieldType']=='text'){ ?>
                     <input type='text' autocomplete="off" class="form-control wst-ipt" id='<?php echo ($v["fieldCode"]); ?>' name='<?php echo ($v["code"]); ?>' value="<?php echo ($v['fieldValue']); ?>" /><?php echo ($v['fieldTips']); ?>
                     <?php }else if($v['fieldType']=='radio'){ foreach($v['txt'] as $kt => $vt){ ?>
                       <label>
			           <input type='radio' id='<?php echo ($v["val"][$kt]); ?>_<?php echo ($kt); ?>' autocomplete="off" name='<?php echo ($v["fieldCode"]); ?>' value='<?php echo ($v["val"][$kt]); ?>' size='30' <?php if($v["fieldValue"]==$v["val"][$kt]){ echo "checked";} ?> /><?php echo ($vt); ?>&nbsp;&nbsp;
			           </label>
			         <?php } echo ($v['fieldTips']); ?>
                     <?php }else if($v['fieldType']=='textarea'){ ?>
                     <textarea id='<?php echo ($v["fieldCode"]); ?>' autocomplete="off" name='<?php echo ($vo["fieldCode"]); ?>' style='width:400px;height:100px;'><?php echo ($v['fieldValue']); ?></textarea><?php echo ($v['fieldTips']); ?>
                     <?php }else if($v['fieldType']=='other'){ ?>
                     <?php if($v['fieldCode']=='defaultCity'){ ?>
                     <select name='areaId1' id='areaId1' onchange='javascript:getAreaList(this.value)'>
			             <option value=''>请选择</option>
			             <?php if(is_array($areaList)): $i = 0; $__LIST__ = $areaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo['areaId']); ?>' <?php if($areaId1 == $vo['areaId'] ): ?>selected<?php endif; ?>><?php echo ($vo['areaName']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
			         </select>
			         <select name='defaultCity' id='defaultCity'>
			             <option value=''>请选择</option>
			          </select>
			          <input id='defaultCity_hidden' type='hidden' value='<?php echo ($v['fieldValue']); ?>'/>
                     <?php } ?>
                     <?php }else if($v['fieldType']=='upload'){ ?>
                     	
                     	<script type="text/javascript">
                     	$(function(){
					    	var uploading = null;
					    	
					  		uploadFile({
					  	    	  server:"<?php echo U('Admin/Index/uploadPic');?>",
					  	    	  pick:'#<?php echo ($v["fieldCode"]); ?>_picker',
					  	    	  formData: {dir:'mall'},
					  	    	  callback:function(f){
					  	    		  layer.close(uploading);
					  	    		  var json = WST.toJson(f);
					  	    		  
					  	    		  $('#<?php echo ($v["fieldCode"]); ?>_view').attr('src',ThinkPHP.DOMAIN+"/"+json.file.savepath+json.file.savethumbname);
					  	    		  
					  	    		  $('#<?php echo ($v["fieldCode"]); ?>').val(json.file.savepath+json.file.savename);
					  	    		  $('#<?php echo ($v["fieldCode"]); ?>_view').show();
					  		      },
					  		      progress:function(rate){
					  		  		 uploading = WST.msg('正在上传图片，请稍后...');
					  		      }
					  	    });
					  		
					      })
                     	</script>
                       	
						<div style="position:relative;">
							<input id="<?php echo ($v['fieldCode']); ?>"  name="<?php echo ($v['fieldCode']); ?>" class="form-control wst-ipt" type="hidden" value="<?php echo ($v['fieldValue']); ?>" />
							<div>
								<div id="<?php echo ($v['fieldCode']); ?>_picker" >上传图片</div>
							</div>
							<div style="clear:both;"></div>
							<div ><?php echo ($v['fieldTips']); ?></div>
						</div>
                     	<div id="preview_<?php echo ($v['fieldCode']); ?>">
                     		<img id='<?php echo ($v["fieldCode"]); ?>_view' src='/<?php echo ($v['fieldValue']); ?>' width="152" height="152"/>
                     	</div>
                     <?php } ?>
                  </td>
               </tr><?php endforeach; endif; ?>
               <tr>
                 <td style='padding-left:250px;' colspan='2'>
                    <button type="button" class="btn btn-primary" onclick='javascript:save()'>保&nbsp;存</button>
                    <button type="reset" class="btn btn-primary">重&nbsp;置</button>
                 </td>
               </tr>
               </table>
            
          </div><?php endforeach; endif; ?>
      </div>
   </body>
</html>