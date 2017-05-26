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
	   Plugins.confirm({title:'信息提示',content:'您确定要删除该广告位置吗?',okText:'确定',cancelText:'取消',okFun:function(){
		   Plugins.closeWindow();
		   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
		   $.post("<?php echo U('Admin/AdPositions/del');?>",{id:id},function(data,textStatus){
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
   </script>
   <body class='wst-page'>
   	<div style="padding-top: 6px;">
       <div class='wst-tbar' style='height:25px;'>
       <form method='post' action='<?php echo U("Admin/AdPositions/index");?>' autocomplete="off">
   		<div style="float:left;width:950px;">      
		  <!--类型：<select name="positionType">-->
                <!--<option value='-1'>请选择</option>-->
                <!--<option value='0' <?php if($positionType == 0 ): ?>selected<?php endif; ?>>PC版</option>-->
                <!--<?php if($CONF['isOpenWeiXin'] == 1 ): ?>-->
                <!--<option value='2' <?php if($positionType == 2 ): ?>selected<?php endif; ?>>微信版</option>-->
                <!--<?php endif; ?>-->
             <!--</select>-->
                      标题：<input type='text' name='positionName' value='<?php echo ($positionName); ?>' autocomplete="off"/>  
  		<button type="submit" class="btn btn-primary glyphicon glyphicon-search">查询</button> 
		  </form>
       	</div>
		   <div class='wst-tbar' style='text-align:right;height:25px;'>
	       <?php if(in_array('ppgl_01',$WST_STAFF['grant'])){ ?>
	       <a type="button" class="btn btn-success glyphicon glyphicon-plus" href='<?php echo U("Admin/AdPositions/toEdit");?>' style='float:right'>新增</a>
	       <?php } ?>
	       </div>
	       <div style="clear:both;"></div>
       </div>
      
       <div class="wst-body"> 
        <table class="table table-hover table-striped table-bordered wst-list">
           <thead>
             <tr>
               <th width='40'>&nbsp;</th>
               <th width='200'>位置名称</th>
               <th width='60'>宽度</th>
               <th width='60'>高度</th>
               <th width='150'>操作</th>
             </tr>
           </thead>
           <tbody>
            <?php if(is_array($Page['root'])): $i = 0; $__LIST__ = $Page['root'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
               <td><?php echo ($i); ?></td>
               <td><?php echo ($vo['positionName']); ?></td>
               <td><?php echo ($vo['positionWidth']); ?></td>
               <td><?php echo ($vo['positionHeight']); ?></td>
               <td>
               <a class="btn btn-default glyphicon glyphicon-pencil" href='<?php echo U("Admin/AdPositions/toEdit",array('id'=>$vo['positionId']));?>'>修改</a>&nbsp;
               <a class="btn btn-default glyphicon glyphicon-trash" onclick="javascript:del(<?php echo ($vo['positionId']); ?>)">刪除</a>
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