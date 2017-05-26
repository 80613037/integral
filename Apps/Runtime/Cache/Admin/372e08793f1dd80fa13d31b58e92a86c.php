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
	   Plugins.confirm({title:'信息提示',content:'您确定要卸载该支付方式吗?',okText:'确定',cancelText:'取消',okFun:function(){
		   Plugins.closeWindow();
		   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
		   $.post("<?php echo U('Admin/Payments/del');?>",{id:id},function(data,textStatus){
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
		        return false;
		}});
   }
   </script>
   <body class="wst-page">

       <div class='wst-body'> 
        <table class="table table-hover table-striped table-bordered wst-list">
           <thead>
             <tr>
               <th width='40'>&nbsp;</th>
               <th>名称 </th>
               <th>描述 </th>
               <th>状态  </th>
               <th>排序号</th>
               <th width='200'>操作</th>
             </tr>
           </thead>
           <tbody>
            <?php if(is_array($Page['root'])): $i = 0; $__LIST__ = $Page['root'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr height="80">
               <td><?php echo ($i); ?></td>
               <td width="220"><?php echo ($vo['payName']); ?></td>
               <td><?php echo ($vo['payDesc']); ?></td>
               <td width="120">
               		<?php if($vo['enabled'] == 1): ?>启用
              		<?php else: ?>
              		关闭<?php endif; ?>
               </td>
               <td width="120"><?php echo ($vo['payOrder']); ?></td>
               <td width="120">
            		<?php if($vo['enabled'] == 1): if(in_array('zfgl_02',$WST_STAFF['grant'])){ ?>
               			<a class="btn btn-default glyphicon glyphicon-pencil" href="<?php echo U('Admin/Payments/toEdit',array('id'=>$vo['id'],'payCode'=>$vo['payCode']));?>"">编辑</a>&nbsp;
              		<?php } ?>
              		<?php if(in_array('zfgl_03',$WST_STAFF['grant'])){ ?>
              			<a class="btn btn-default glyphicon glyphicon-trash" onclick="javascript:del(<?php echo ($vo['id']); ?>)">卸载</a>
              		<?php } ?>
              		<?php else: ?>
              		<?php if(in_array('zfgl_01',$WST_STAFF['grant'])){ ?>
              		<a class="btn btn-default glyphicon glyphicon-pencil" href="<?php echo U('Admin/Payments/toEdit',array('id'=>$vo['id'],'payCode'=>$vo['payCode']));?>"">安装</a>&nbsp;
               		<?php } endif; ?>
               </td>
             </tr><?php endforeach; endif; else: echo "" ;endif; ?>
             <tr>
                <td colspan='6' align='center'><?php echo ($Page['pager']); ?></td>
             </tr>
           </tbody>
        </table>
        </div>
   </body>
</html>