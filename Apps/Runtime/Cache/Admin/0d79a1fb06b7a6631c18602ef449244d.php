<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?php echo ($CONF['mallTitle']); ?>后台管理中心</title>
      <link href="/Public/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <link href="/Apps/Admin/View/css/AdminLTE.css" rel="stylesheet" type="text/css" />
      <!--[if lt IE 9]>
      <script src="/Public/js/html5shiv.min.js"></script>
      <script src="/Public/js/respond.min.js"></script>
      <![endif]-->
      <script src="/Public/js/jquery.min.js"></script>
      <script src="/Public/plugins/bootstrap/js/bootstrap.min.js"></script>
      <script src="/Public/js/common.js"></script>
      <script src="/Public/plugins/plugins/plugins.js"></script>
      <script src="/Public/plugins/kindeditor/kindeditor.js"></script>
      <script src="/Public/plugins/kindeditor/lang/zh-CN.js"></script>
   </head>
   <style>
    .wst-tab-box{width:100%; height:auto; margin:0px auto;}
	.wst-tab-nav{margin:0; padding:0; height:25px; line-height:24px;position: relative;top:2px;left:3px;}
	.wst-tab-nav li{cursor:pointer;float:left; margin:0 0px; list-style:none; border:1px solid #ddd; border-bottom:none; height:24px; width:100px; text-align:center; background:#eeeeee;color:#000000;}
	.wst-tab-nav .on{background:#ffffff;color:#000000;border-bottom:0 none;}
	.wst-tab-content{padding:5px;width:99%; height:auto; border:1px solid #ddd;background:#FFF;}
    .wst-gallery-imgs{width:770px;height:auto;}
    .wst-gallery-img{width:140px;height:100px;float:left;overflow:hidden;margin:10px 5px 5px 5px;}
   </style>
   <script>
   $(function () {
	   $('#tab').TabPanel({tab:0});
	   KindEditor.ready(function(K) {
			editor1 = K.create('textarea[name="goodsDesc"]', {
				height:'250px',
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
   });
   
   
   function changeStatus(id,v){
	   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
	   $.post("<?php echo U('Admin/Goods/changeGoodsStatus');?>",{id:id,status:v},function(data,textStatus){
				var json = WST.toJson(data);
				if(json.status=='1'){
					Plugins.setWaitTipsMsg({ content:'操作成功',timeout:1000,callback:function(){
						location.href="<?php echo U('Admin/Goods/index');?>";
					}});
				}else{
					Plugins.closeWindow();
					Plugins.Tips({title:'信息提示',icon:'error',content:'操作失败!',timeout:1000});
				
				}
	   });
   }
   </script>
   <body class="wst-page">
       <form name="myform" method="post" id="myform">
       <div id='tab' class="wst-tab-box">
		<ul class="wst-tab-nav">
	    	<li>商品信息</li>
	    	<li>商品属性</li>
	        <li>商品相册</li>
	    </ul>
    	<div class="wst-tab-content" style='width:98%;'>
    	<div class='wst-tab-item'>
	        <form name="myform" method="post" id="myform">
	        <input type='hidden' id='id' value='<?php echo ($object["goodsId"]); ?>'/>
	        <input type='hidden' id='shopId' value='<?php echo ($object["shopId"]); ?>'/>
	        <table class="table table-hover table-striped table-bordered wst-form">
	           <tr>
	             <th width='120'>商品编号：</th>
	             <td width='300'>
	             <?php echo ($object["goodsSn"]); ?>
	             </td>
	             <td rowspan='6' style='padding:5px;'>
	             <img id='goodsImgPreview' src='<?php if($object['goodsImg'] =='' ): ?>/Apps/Home/View/default/img/store_default_sign.png<?php else: ?>/<?php echo ($object['goodsImg']); endif; ?>' width='160' height='160'/><br/>
	             </td>
	           </tr>
	           <tr>
	             <th width='120'>商品名称<font color='red'>*</font>：</th>
	             <td><?php echo ($object["goodsName"]); ?></td>
	           </tr>
	            <tr>
	             <th width='120'>市场价<font color='red'>*</font>：</th>
	             <td><?php echo ($object["marketPrice"]); ?></td>
	           </tr>
	            <tr>
	             <th width='120'>商户价格<font color='red'>*</font>：</th>
	             <td><?php echo ($object["shopPrice"]); ?></td>
	           </tr>
	            <tr>
	             <th width='120'>商品库存<font color='red'>*</font>：</th>
	             <td><?php echo ($object["goodsStock"]); ?></td>
	           </tr>
	            <tr>
	             <th width='120'>单位<font color='red'>*</font>：</th>
	             <td><?php echo ($object["goodsUnit"]); ?></td>
	           </tr>
	           <tr>
	             <th width='120'>商品信息：</th>
	             <td colspan='3'>
	             <?php echo ($object["goodsSpec"]); ?>
	             </td>
	           </tr>
	           <tr>
	             <th width='120'>商品状态<font color='red'>*</font>：</th>
	             <td colspan='3'>
	             <?php if($object['isSale'] ==1 ): ?>上架<?php endif; ?>
	             <?php if($object['isSale'] ==0 ): ?>下架<?php endif; ?>
	             </td>
	           </tr>
	           <tr>
	             <th width='120'>所属分类<font color='red'>*</font>：</th>
	             <td colspan='3'><?php echo ($object["goodsCats"]["goodsName1"]); ?>-><?php echo ($object["goodsCats"]["goodsName2"]); ?>-><?php echo ($object["goodsCats"]["goodsName3"]); ?></td>
	           </tr>
	           <tr>
	             <th width='120'>商户分类<font color='red'>*</font>：</th>
	             <td colspan='3'><?php echo ($object["shopCats"]["goodsName1"]); ?>-><?php echo ($object["shopCats"]["goodsName2"]); ?></td>
	           </tr>
	           <tr>
	             <th width='120'>商品描述<font color='red'>*</font>：</th>
	             <td colspan='3'>
	             <?php echo (htmlspecialchars_decode($object["goodsDesc"])); ?>
	             </td>
	           </tr>
				<tr>
					<th width='120'>所属活动<font color='red'>*</font>：</th>
					<td colspan='3'>
						<?php if($object['isScore'] == 1): ?>积分换购商品，所需积分（<?php echo ($object["exchangeScore"]); ?>）<?php elseif($object['isMiaosha'] == 1): ?>限时秒杀商品，开始时间（<?php echo ($object["ismiaoshatime"]); ?>点）<?php elseif($object['ispintuan'] == 1): ?>拼团商品，拼团所需人数（<?php echo ($object["ptrs"]); ?>人）<?php else: ?>普通商品<?php endif; ?>
					</td>
				</tr>
	           <tr>
	             <td colspan='3' style='padding-left:250px;'>
	                 <button type="button" class="btn btn-danger" onclick='javascript:changeStatus(<?php echo ($object['goodsId']); ?>,0)'>禁&nbsp;售</button>
	                 <button type="button" class="btn btn-primary" onclick='javascript:location.href="<?php echo U('Admin/Goods/index');?>"'>返&nbsp;回</button>
	             </td>
	           </tr>
	        </table>
	       </form>
	      </div>
	      <div class='wst-tab-item'>
	        <table class="table table-hover table-striped table-bordered wst-form">
	           <tr>
	             <th width='120'>商品分类：</th>
	             <td><?php echo ($object["attrCatName"]); ?>&nbsp;</td>
	           </tr>
	           <tr>
	             <th width='120'>价格属性：</th>
	             <td style='margin:0px;'>
	             <?php if( count($object.priceAttrs) > 0): ?><table class="table wst-list" style='margin:0px;border-top:1px solid #ddd;'>
					<thead>
					<tr>
					  <th style='background:#f5f5f5'>属性</th>
					  <th style='background:#f5f5f5'>规格</th>
					  <th style='background:#f5f5f5'>价格</th>
					  <th style='background:#f5f5f5'>推荐</th>
					  <th style='background:#f5f5f5'>库存</th>
					</tr>
					</thead>
					<?php if(is_array($object["priceAttrs"])): $i = 0; $__LIST__ = $object["priceAttrs"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr style='margin:0px;'>
						<td nowrap width='100px'><?php echo ($object["priceAttrName"]); ?></td>
						<td><?php echo ($vo['attrVal']); ?></td>
						<td><?php echo ($vo['attrPrice']); ?></td>
						<td><?php if($vo["isRecomm"] == 1): ?>是<?php endif; ?></td>
						<td><?php echo ($vo['attrStock']); ?></td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
					</table><?php endif; ?>
	             </td>
	           </tr>
	           <tr>
	             <th width='120' valign='top'>展示属性：</th>
	             <td style='margin:0px;'>
	             <?php if( count($object.attrs) > 0): ?><table class="table  wst-form" style='margin:0px;'>
					<?php if(is_array($object["attrs"])): $i = 0; $__LIST__ = $object["attrs"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo['attrContent'] !='' ): ?><tr style='margin:0px;border:0px;'>
						<th nowrap width='100px'><?php echo ($vo['attrName']); ?>：</th>
						<td><?php echo ($vo['attrContent']); ?></td>
					</tr><?php endif; endforeach; endif; else: echo "" ;endif; ?>
					</table><?php endif; ?>
	             </td>
	           </tr>
	        </table>
	      </div>
	      <div class='wst-tab-item'>
	       <div id='galleryImgs' class='wst-gallery-imgs'>
	           <?php if(is_array($object['gallery'])): $i = 0; $__LIST__ = $object['gallery'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="wst-gallery-img">
			       <img class="gallery-img" width='140' height='100' iv="<?php echo ($vo["goodsThumbs"]); ?>" v="<?php echo ($vo["goodsImg"]); ?>" src="/<?php echo ($vo["goodsThumbs"]); ?>"/>
		       </div><?php endforeach; endif; else: echo "" ;endif; ?>
	       </div>
	       <div style='clear:both;'></div>
	      </div>
       </div>
       </div>
       </form>
   </body>
</html>