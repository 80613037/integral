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
   function toggleIsShow(t,v){
	   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
	   $.post("<?php echo U('Admin/Areas/editiIsShow');?>",{id:v,isShow:t},function(data,textStatus){
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
   function del(id){
	   Plugins.confirm({title:'信息提示',content:'您确定要删除该地区吗?',okText:'确定',cancelText:'取消',okFun:function(){
		   Plugins.closeWindow();
		   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
		   $.post("<?php echo U('Admin/Areas/del');?>",{id:id},function(data,textStatus){
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
       <input type='hidden' id='parentId' value='<?php echo ($pArea[areaId]); ?>'/>
       <div class='wst-tbar' style='height:25px;'>
       <?php if($pArea['areaId'] !=0 ): ?>上级地区：<?php echo ($pArea['areaName']); endif; ?>
       <?php if($pArea['areaId'] !=0 ): ?><a class="btn glyphicon btn-success" href="<?php echo U('Admin/Areas/index',array('parentId'=>$pArea['parentId']));?>" style='float:right;margin-left:5px;'>返回</a><?php endif; ?>
       <?php if(in_array('dqlb_01',$WST_STAFF['grant'])){ ?>
       <a class="btn btn-success glyphicon glyphicon-plus" href="<?php echo U('Admin/Areas/toEdit',array('parentId'=>$pArea['areaId']));?>" style='float:right'>新增</a>
       <?php } ?>
       </div>
       <div class="wst-body"> 
        <table class="table table-hover table-striped table-bordered wst-list">
           <thead>
             <tr>
               <th width='40'>序号</th>
               <th>地区</th>
               <th width='80'>是否显示</th>
               <th width='80'>排序号</th>
               <th width='250'>操作</th>
             </tr>
           </thead>
           <tbody>
            <?php if(is_array($Page['root'])): $i = 0; $__LIST__ = $Page['root'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
               <td><?php echo ($i); ?></td>
               <td><?php echo ($vo['areaName']); ?></td>
               <td>
               <div class="dropdown">
               <?php if($vo['isShow']==0 ): ?><button class="btn btn-danger dropdown-toggle wst-btn-dropdown"  type="button" data-toggle="dropdown">
					     隐藏
					  <span class="caret"></span>
				   </button>
               <?php else: ?>
                   <button class="btn btn-success dropdown-toggle wst-btn-dropdown" type="button" data-toggle="dropdown">
					     显示
					  <span class="caret"></span>
				   </button><?php endif; ?>
               <?php if(in_array('dqlb_02',$WST_STAFF['grant'])){ ?>
                   <ul class="dropdown-menu" role="menu">
					  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleIsShow(1,<?php echo ($vo['areaId']); ?>)">显示</a></li>
					  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleIsShow(0,<?php echo ($vo['areaId']); ?>)">隐藏</a></li>
				   </ul>
			   <?php } ?>   
               </div>
               </td>
               <td><?php echo ($vo['areaSort']); ?></td>
               <td>
               <?php if($pArea['areaType'] < 1 ): ?><a class="btn btn-default glyphicon glyphicon-pencil" href="<?php echo U('Admin/Areas/index',array('parentId'=>$vo['areaId']));?>">查看</a>&nbsp;<?php endif; ?>
               <?php if(in_array('dqlb_02',$WST_STAFF['grant'])){ ?>
               <a class="btn btn-default glyphicon glyphicon-pencil" href="<?php echo U('Admin/Areas/toEdit',array('id'=>$vo['areaId'],'parentId'=>$vo['parentId']));?>"">修改</a>&nbsp;
               <?php } ?>
               <?php if(in_array('dqlb_02',$WST_STAFF['grant'])){ ?>
               <button type="button" class="btn btn-default glyphicon glyphicon-trash" onclick="javascript:del(<?php echo ($vo['areaId']); ?>)"">刪除</button>
               <?php } ?>
               </td>
             </tr><?php endforeach; endif; else: echo "" ;endif; ?>
             <tr>
                <td colspan='5' align='center'><?php echo ($Page['pager']); ?></td>
             </tr>
           </tbody>
        </table>
       </div>
   </body>
</html>