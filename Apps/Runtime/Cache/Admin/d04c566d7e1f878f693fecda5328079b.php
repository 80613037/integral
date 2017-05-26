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
      <script src="/Public/plugins/formValidator/formValidator-4.1.3.js"></script>
      <script src="/Public/plugins/kindeditor/kindeditor.js"></script>
      <script src="/Public/plugins/kindeditor/lang/zh-CN.js"></script>
      <script type="text/javascript" src="/Apps/Admin/View/js/upload.js"></script>
      <script type="text/javascript" src="/Public/plugins/webuploader/webuploader.js"></script>
      <script src="/Public/plugins/layer/layer.min.js"></script>
   </head>
   <script>
   var editor1;
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
			},onError:function(msg){
		}});
	   $("#brandName").formValidator({onShow:"",onFocus:"品牌名称不能为空",onCorrect:"输入正确"}).inputValidator({min:1,max:20,onError:"你输入的长度不正确,请确认"});
	   $(":checkbox[name='catId']").formValidator({tipID:"catIdTips",onShow:"",onFocus:"",onCorrect:""}).inputValidator({min:1,max:20,onError:"请选择品牌所属的分类"});
	  
	    KindEditor.ready(function(K) {
			editor1 = K.create('textarea[name="brandDesc"]', {
				height:'350px',
				allowFileManager : false,
				allowImageUpload : true,
				items:[
				        'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
				        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
				        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
				        'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
				        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
				        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|','image','table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
				        'anchor', 'link', 'unlink', '|', 'about'
				],
				afterBlur: function(){ this.sync(); }
			});
		});
	    
	    var uploading = null;
		uploadFile({
	    	  server:"<?php echo U('Admin/Brands/uploadPic');?>",
	    	  pick:'#filePicker',
	    	  formData: {dir:'brands'},
	    	  callback:function(f){
	    		  layer.close(uploading);
	    		  var json = WST.toJson(f);
	    		  $('#preview').attr('src',ThinkPHP.DOMAIN+"/"+json.file.savepath+json.file.savethumbname);
	    		  $('#brandIco').val(json.file.savepath+json.file.savename);
	    		  $('#preview').show();
		      },
		      progress:function(rate){
		  		 uploading = WST.msg('正在上传图片，请稍后...');
		      }
	    });
   });
   function edit(){
	   var params = {};
	   params.id = $('#id').val();
	   var ids = [];
	   $('input[name="catId"]:checked').each(function(){
		   ids.push($(this).val());
	   })
	   params.catIds = ids.join(',');
	   params.brandName = $('#brandName').val();
	   params.brandIco = $('#brandIco').val();
	   params.brandDesc = $('#brandDesc').val();
	   if(params.brandIco==''){
		   Plugins.Tips({title:'信息提示',icon:'error',content:'请上传品牌图标!',timeout:1000});
		   return;
	   }
	   if(params.brandDesc==''){
		   Plugins.Tips({title:'信息提示',icon:'error',content:'请输入品牌介绍!',timeout:1000});
		   return;
	   }
	   Plugins.waitTips({title:'信息提示',content:'正在提交数据，请稍后...'});
	   $.post("<?php echo U('Admin/Brands/edit');?>",params,function(data,textStatus){
			var json = WST.toJson(data);
			if(json.status=='1'){
				Plugins.setWaitTipsMsg({ content:'操作成功',timeout:1000,callback:function(){
				   location.href='<?php echo U("Admin/Brands/index");?>';
				}});
			}else if(json.status=='-2'){
				Plugins.closeWindow();
				Plugins.Tips({title:'信息提示',icon:'error',content:'所取消的分类下有该品牌的商品!',timeout:1000});
			}else{
				Plugins.closeWindow();
				Plugins.Tips({title:'信息提示',icon:'error',content:'操作失败!',timeout:1000});
			}
		});
   }
   </script>
   <body class="wst-page" style="position:relative;">
   		
       <form name="myform" method="post" id="myform" autocomplete="off">
        <input type='hidden' id='id' value='<?php echo ($object["brandId"]); ?>'/>
        
        <table class="table table-hover table-striped table-bordered wst-form">
           <tr>
             <th width='120' align='right'>品牌名称<font color='red'>*</font>：</th>
             <td><input type='text' id='brandName' class="form-control wst-ipt" value='<?php echo ($object["brandName"]); ?>' maxLength='25'/></td>
           </tr>
           <tr>
             <th width='120' align='right'>所属分类<font color='red'>*</font>：</th>
             <td>
             <?php if(is_array($cats)): $i = 0; $__LIST__ = $cats;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label>
             <input type='checkbox' id='cat<?php echo ($vo["catId"]); ?>' name='catId' value='<?php echo ($vo["catId"]); ?>' <?php if($object['catBrands_'.$vo["catId"]]==1)echo "checked"; ?> >&nbsp;<?php echo ($vo["catName"]); ?>&nbsp;
             </label><?php endforeach; endif; else: echo "" ;endif; ?>
             <span id='catIdTips'></span>
             </td>
           </tr>
           <tr style='height:60px;'>
             <th align='right'>品牌图标<font color='red'>*</font>：</th>
             <td>
             	<div>
		          	<div id="filePicker" style='margin-left:0px;float:left'>上传图片</div>
             	    <div style='margin-left:5px;float:left'>图片大小:150 x 150 (px)，格式为 gif, jpg, jpeg, png</div>
             	    <input id="brandIco" name="brandIco" class="text wstipt" tabindex="3" autocomplete="off" type="hidden" value="<?php echo ($object["brandIco"]); ?>"/>
             	    <div style="clear:both;"></div>
             	</div>
             </td>
           </tr>
           <tr >
             <th align='right'>预览图：</th>
             <td >
             	
             	<div id="preview_Filedata">
               	<img id='preview' src='/<?php echo ($object["brandIco"]); ?>' height='152' <?php if($object['brandIco'] =='' ): ?>style='display:none'<?php endif; ?>/>
               	</div>
             </td>
           </tr>
           <tr>
             <th align='right'>品牌介绍<font color='red'>*</font>：</th>
             <td>
             <textarea id='brandDesc' name='brandDesc' class="form-control wst-ipt" style='width:80%;height:400px'><?php echo ($object["brandDesc"]); ?></textarea>
             </td>
           </tr>
           <tr>
             <td colspan='2' style='padding-left:250px;'>
                 <button type="submit" class="btn btn-success">保&nbsp;存</button>
                 <button type="button" class="btn btn-primary" onclick='javascript:location.href="<?php echo U('Admin/Brands/index');?>"'>返&nbsp;回</button>
             </td>
           </tr>
        </table>
       </form>
   </body>
</html>