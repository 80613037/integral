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
      <style type="text/css">
		#preview{border:1px solid #cccccc; background:#CCC;color:#fff; padding:5px; display:none; position:absolute;}
	  </style>
   </head>
   <script>
   function batchChangeStatus(v)
   {
   	   var ids = [];
	   $('.chk').each(function(){
		   if($(this).prop('checked'))ids.push($(this).val());
	   })
	   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
	   $.post("<?php echo U('Admin/Goods/changePenddingGoodsStatus');?>",{id:ids.join(','),status:v},function(data,textStatus){
				var json = WST.toJson(data);
				if(json.status=='1'){
					Plugins.setWaitTipsMsg({content:'操作成功',timeout:1000,callback:function(){
					    location.reload();
					}});
				}else{
					Plugins.closeWindow();
					Plugins.Tips({title:'信息提示',icon:'error',content:'操作失败!',timeout:1000});
				
				}
	   });
   }



   function changeStatus(id,v){
	   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
	   $.post("<?php echo U('Admin/Goods/changePenddingGoodsStatus');?>",{id:id,status:v},function(data,textStatus){
				var json = WST.toJson(data);
				if(json.status=='1'){
					Plugins.setWaitTipsMsg({content:'操作成功',timeout:1000,callback:function(){
					    location.reload();
					}});
				}else{
					Plugins.closeWindow();
					Plugins.Tips({title:'信息提示',icon:'error',content:'操作失败!',timeout:1000});
				
				}
	   });
   }




   $.fn.imagePreview = function(options){
		var defaults = {}; 
		var opts = $.extend(defaults, options);
		var t = this;
		xOffset = 5;
		yOffset = 20;
		if(!$('#preview')[0])$("body").append("<div id='preview'><img width='200' src=''/></div>");
		$(this).hover(function(e){
			   $('#preview img').attr('src',"/"+$(this).attr('img'));      
			   $("#preview").css("top",(e.pageY - xOffset) + "px").css("left",(e.pageX + yOffset) + "px").show();      
		  },
		  function(){
			$("#preview").hide();
		}); 
		$(this).mousemove(function(e){
			   $("#preview").css("top",(e.pageY - xOffset) + "px").css("left",(e.pageX + yOffset) + "px");
		});
	}
   function getAreaList(objId,parentId,t,id){
	   var params = {};
	   params.parentId = parentId;
	   $('#'+objId).empty();
	   if(t<1){
		   $('#areaId2').html('<option value="">请选择</option>');
		   if(parentId==0)return;
	   }
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
			$('#'+objId).html(html.join(''));
	   });
   }
   function getCatList(objId,parentId,t,id){
	   var params = {};
	   params.id = parentId;
	   $('#'+objId).empty();
	   if(t<1){
		   $('#goodsCatId3').empty();
		   $('#goodsCatId3').html('<option value="0">请选择</option>');
		   if(parentId==0){
			   $('#goodsCatId2').html('<option value="0">请选择</option>');
			   return;
		   }
	   }
	   var html = [];
	   $.post("<?php echo U('Home/GoodsCats/queryByList');?>",params,function(data,textStatus){
		    html.push('<option value="0">请选择</option>');
			var json = WST.toJson(data);
			if(json.status=='1' && json.list){
				var opts = null;
				for(var i=0;i<json.list.length;i++){
					opts = json.list[i];
					html.push('<option value="'+opts.catId+'" '+((id==opts.catId)?'selected':'')+'>'+opts.catName+'</option>');
				}
			}
			$('#'+objId).html(html.join(''));
	   });
  }
  $(document).ready(function(){
	    $('.imgPreview').imagePreview();
	    <?php if(!empty($areaId1)): ?>getAreaList("areaId2",'<?php echo ($areaId1); ?>',0,'<?php echo ($areaId2); ?>');<?php endif; ?>
		<?php if($goodsCatId1 != 0 ): ?>getCatList("goodsCatId2",<?php echo ($goodsCatId1); ?>,0,<?php echo ($goodsCatId2); ?>);<?php endif; ?>
		<?php if($goodsCatId2 != 0 ): ?>getCatList("goodsCatId3",<?php echo ($goodsCatId2); ?>,1,<?php echo ($goodsCatId3); ?>);<?php endif; ?>
  });
   </script>
   <body class='wst-page'>
    <form method='post' action='<?php echo U("Admin/Goods/queryPenddingByPage");?>'>
       <div class='wst-tbar'> 
      <!--地区：<select id='areaId1' name='areaId1' onchange='javascript:getAreaList("areaId2",this.value,0)'>
               <option value=''>请选择</option>
               <?php if(is_array($areaList)): $i = 0; $__LIST__ = $areaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo['areaId']); ?>' <?php if($areaId1 == $vo['areaId'] ): ?>selected<?php endif; ?>><?php echo ($vo['areaName']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
             </select>
             <select id='areaId2' name='areaId2'>
               <option value=''>请选择</option>
             </select>-->
  商城分类：<select id='goodsCatId1' name='goodsCatId1' onchange='javascript:getCatList("goodsCatId2",this.value,0)'>
	                <option value='0'>请选择</option>
	                <?php if(is_array($goodsCatsList)): $i = 0; $__LIST__ = $goodsCatsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo['catId']); ?>' <?php if($goodsCatId1 == $vo['catId'] ): ?>selected<?php endif; ?>><?php echo ($vo['catName']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	             </select>
	             <select id='goodsCatId2' name='goodsCatId2' onchange='javascript:getCatList("goodsCatId3",this.value,1);'>
	                <option value='0'>请选择</option>
	             </select>
	             <select id='goodsCatId3' name='goodsCatId3'>
	                <option value='0'>请选择</option>
	             </select>
   </div>
   <div class='wst-tbar'>             
       所属商户：<input type='text' id='shopName' name='shopName' value='<?php echo ($shopName); ?>'/>
       商品：<input type='text' id='goodsName' name='goodsName' value='<?php echo ($goodsName); ?>'/> 
  <button type="submit" class="btn btn-primary glyphicon glyphicon-search">查询</button> 
       </div>
       </form>
       <div class='wst-body'>
       	<div class='wst-tbar'> 
        <button type="button" class="btn btn-primary glyphicon" onclick='javascript:batchChangeStatus(1)'>批量通过</button>
        </div>
        <table class="table table-hover table-striped table-bordered wst-list">
           <thead>
             <tr>
               <th width='2'><input type='checkbox' name='chk' onclick='javascript:WST.checkChks(this,".chk")'/></th>
               <th width='20'>序号</th>
               <th width='180'>商品名称</th>
               <th width='60'>商品编号</th>
               <th width='40'>价格</th>
               <th width='40'>商户</th>
               <th width='100'>商城分类</th>
               <th width='40'>销量</th>
               <th width='40'>所属活动</th>
               <th width='50'>操作</th>
             </tr>
           </thead>
           <tbody>
            <?php if(is_array($Page['root'])): $i = 0; $__LIST__ = $Page['root'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
               <td><input type='checkbox' class='chk' name='chk_<?php echo ($vo['goodsId']); ?>' value='<?php echo ($vo['goodsId']); ?>'/></td>
               <td><?php echo ($i); ?></td>
               <td img='<?php echo ($vo['goodsThums']); ?>' class='imgPreview'>
               <img src='/<?php echo ($vo['goodsThums']); ?>' width='50'/>
               <?php echo ($vo['goodsName']); ?>
               </td>
               <td><?php echo ($vo['goodsSn']); ?>&nbsp;</td>
               <td><?php echo ($vo['shopPrice']); ?>&nbsp;</td>
               <td><a href="<?php echo U('Admin/Goods/queryPenddingByPage',array('shopName'=>$vo['shopName']));?>"><?php echo ($vo['shopName']); ?></a>&nbsp;</td>
               <td><?php echo ($vo['catName']); ?>&nbsp;</td>
               <td><?php echo ($vo['saleCount']); ?></td>
               <td><?php if($vo["isScore"] == 1): ?>积分换购<?php elseif($vo["isMiaosha"] == 1): ?>限时秒杀<?php elseif($vo["ispintuan"] == 1): ?>拼团商品<?php else: ?>普通商品<?php endif; ?></td>
               <td>
               <a class="btn btn-primary glyphicon" href='<?php echo U("Admin/Goods/toPenddingView",array("id"=>$vo["goodsId"]));?>'>查看</a> 
               <?php if(in_array('spsh_04',$WST_STAFF['grant'])){ ?>
               <?php if($vo['goodsStatus']==1 ): ?><button type="button" class="btn btn-danger glyphicon glyphicon-pencil" onclick="javascript:changeStatus(<?php echo ($vo['goodsId']); ?>,0)">不通过</button>&nbsp;
               <?php else: ?>
               <button type="button" class="btn btn-success glyphicon glyphicon-pencil" onclick="javascript:changeStatus(<?php echo ($vo['goodsId']); ?>,1)">通过</button>&nbsp;<?php endif; ?>
               <?php } ?>
               </td>
             </tr><?php endforeach; endif; else: echo "" ;endif; ?>
             <tr>
                <td colspan='9' align='center'><?php echo ($Page['pager']); ?></td>
             </tr>
           </tbody>
        </table>
       </div>
   </body>
</html>