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
   function toggleStatus(t,v){
	   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
	   $.post("<?php echo U('Admin/Staffs/editStatus');?>",{id:v,staffStatus:t},function(data,textStatus){
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
	   Plugins.confirm({title:'信息提示',content:'您确定要删除该职员吗?',okText:'确定',cancelText:'取消',okFun:function(){
		   Plugins.closeWindow();
		   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
		   $.post("<?php echo U('Admin/Staffs/del');?>",{id:id},function(data,textStatus){
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
     <form method='post' action="<?php echo U('Admin/Staffs/index');?>">
       <div class='wst-tbar'> 
       账号：<input type='text' id='loginName' name='loginName' class='form-control wst-ipt-10' value='<?php echo ($loginName); ?>'/> 
       姓名：<input type='text' id='staffName' name='staffName' class='form-control wst-ipt-10' value='<?php echo ($staffName); ?>'/>   
  <button type="submit" class="btn btn-primary glyphicon glyphicon-search">查询</button> 
       <?php if(in_array('zylb_01',$WST_STAFF['grant'])){ ?>
       <a class="btn btn-success glyphicon glyphicon-plus" href="<?php echo U('Admin/Staffs/toEdit');?>" style='float:right'>新增</a>
       <?php } ?>
       </div>
       </form>
       <div class="wst-body">
        <table class="table table-hover table-striped table-bordered wst-list">
           <thead>
             <tr>
               <th width='30'>序号</th>
               <th width='80'>账号</th>
               <th width='80'>姓名</th>
               <th width='80'>角色</th> 
               <th width='60'>编号</th>
               <th width='60'>工作状态</th>
               <th width='120'>最后登录时间</th>
               <th width='80'>最后登录IP</th>
               <th width='40'>状态</th>
               <th width='120'>操作</th>
             </tr>
           </thead>
           <tbody>
            <?php if(is_array($Page['root'])): $i = 0; $__LIST__ = $Page['root'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
               <td><?php echo ($i); ?></td>
               <td><?php echo ($vo['loginName']); ?></td>
               <td><?php echo ($vo['staffName']); ?>&nbsp;</td>
               <td><?php echo ($vo['roleName']); ?>&nbsp;</td>
               <td><?php echo ($vo['staffNo']); ?>&nbsp;</td>
               <td>
               <?php if($object['workStatus'] ==1 ): ?>在职<?php endif; ?>
               <?php if($object['workStatus'] ==0 ): ?>在职<?php endif; ?>&nbsp;
               </td>
               <td><?php echo ($vo['lastTime']); ?>&nbsp;</td>
               <td><?php echo ($vo['lastIP']); ?>&nbsp;</td>
               <td>
               <div class="dropdown">
               <?php if($vo['staffStatus']==0 ): ?><button class="btn btn-danger dropdown-toggle wst-btn-dropdown"  type="button" data-toggle="dropdown">
					    停用
					  <span class="caret"></span>
				   </button>
               <?php else: ?>
                   <button class="btn btn-success dropdown-toggle wst-btn-dropdown" type="button" data-toggle="dropdown">
					     启用
					  <span class="caret"></span>
				   </button><?php endif; ?>
               <?php if(in_array('zylb_02',$WST_STAFF['grant'])){ ?>
                   <ul class="dropdown-menu" role="menu">
					  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleStatus(1,<?php echo ($vo['staffId']); ?>)">显示</a></li>
					  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleStatus(0,<?php echo ($vo['staffId']); ?>)">隐藏</a></li>
				   </ul>
				<?php } ?>
               </div>
               </td>
               <td>
               <?php if(in_array('zylb_02',$WST_STAFF['grant'])){ ?>
               <a class="btn btn-default glyphicon glyphicon-pencil" href="<?php echo U('Admin/Staffs/toEdit',array('id'=>$vo['staffId']));?>">修改</a>&nbsp;
               <?php } ?>
               <?php if(in_array('zylb_03',$WST_STAFF['grant'])){ ?>
               <?php if($vo['staffId'] != $_SESSION['STAFF']['staffId']): ?><button type="button" class="btn btn-default glyphicon glyphicon-trash" onclick="javascript:del(<?php echo ($vo['staffId']); ?>)">刪除</buttona><?php endif; ?>
               <?php } ?>
               </td>
             </tr><?php endforeach; endif; else: echo "" ;endif; ?>
             <tr>
                <td colspan='11' align='center'><?php echo ($Page['pager']); ?></td>
             </tr>
           </tbody>
        </table>
       </div>
   </body>
</html>