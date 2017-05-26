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
   </head>
   <script>
   function del(id){
	   Plugins.confirm({title:'信息提示',content:'您确定要删除该广告吗?',okText:'确定',cancelText:'取消',okFun:function(){
		   Plugins.closeWindow();
		   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
		   $.post("<?php echo U('Admin/Ads/del');?>",{id:id},function(data,textStatus){
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
	   }});
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
						html.push("<option value='-"+json.list[i].positionId+"'>"+json.list[i].positionName+"</option>");
					}
					 $("#adPositionId").append(html.join(''));
				}
				$('#adPositionId option').each(function(){
					if($(this).attr('value')==id)$(this).prop('checked',true);
				});
			});
	   }else{
		   $('#adPositionId option').each(function(){
			   if(parseInt($(this).attr('value'),10)<0)$(this).remove();
		   });
	   }
   }
   getAdPositions(<?php echo ($positionType); ?>,'<?php echo ($adPositionId); ?>');
   </script>
   <body class='wst-page'>
   	<div style="padding-top: 6px;">
       <div class='wst-tbar' style='height:25px;'>
       <form method='post' action='<?php echo U("Admin/Ads/index");?>' autocomplete="off">
   		<div style="float:left;width:950px; display:none;">
   		  广告类型：<select name="positionType" name='positionType' onchange='javascript:getAdPositions(this.value)'>
   		          <option value='-1'>请选择</option>
   		          <option value='0' <?php if($positionType == 0 ): ?>selected<?php endif; ?>>PC版</option>
   		          <?php if($CONF['isOpenWeiXin'] == 1 ): ?><option value='2' <?php if($positionType == 2 ): ?>selected<?php endif; ?>>微信版</option><?php endif; ?>
   		       </select>     
		  广告位置：<select name="adPositionId" id="adPositionId">
                <option value=''>请选择</option>
                <?php if(is_array($goodsCatList)): $i = 0; $__LIST__ = $goodsCatList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo['catId']); ?>' <?php if($adPositionId == $vo['catId'] ): ?>selected<?php endif; ?>>(商品分类)<?php echo ($vo['catName']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
             </select> 
  		<button type="submit" class="btn btn-primary glyphicon glyphicon-search">查询</button> 
		  </form>
       	</div>
		   <div class='wst-tbar' style='text-align:right;height:25px;'>
	       <?php if(in_array('ppgl_01',$WST_STAFF['grant'])){ ?>
	       <a type="button" class="btn btn-success glyphicon glyphicon-plus" href='<?php echo U("Admin/Ads/toEdit");?>' style='float:right'>新增</a>
	       <?php } ?>
	       </div>
	       <div style="clear:both;"></div>
       </div>
      
       <div class="wst-body"> 
        <table class="table table-hover table-striped table-bordered wst-list">
           <thead>
             <tr>
               <th width='40'>&nbsp;</th>
               <th width='120'>广告标题</th>
               <th>广告位置</th>
               <th>广告网址</th>
               <th width=''>图标</th>
               <th width=''>操作</th>
             </tr>
           </thead>
           <tbody>
            <?php if(is_array($Page['root'])): $i = 0; $__LIST__ = $Page['root'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
               <td><?php echo ($i); ?></td>
               <td><?php echo ($vo['adName']); ?></td>
               <td><?php echo ($vo['positionName']); ?></td>
               <td><?php echo ($vo['adURL']); ?></td>
               <td><img src='/<?php echo ($vo['adFile']); ?>' width='60' height='30'></td>
               <td>
               <a class="btn btn-default glyphicon glyphicon-pencil" href='<?php echo U("Admin/Ads/toEdit",array('id'=>$vo['adId']));?>'>修改</a>&nbsp;
               <a class="btn btn-default glyphicon glyphicon-trash" onclick="javascript:del(<?php echo ($vo['adId']); ?>)">刪除</a>
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