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
      <script src="/Public/plugins/formValidator/formValidator-4.1.3.js"></script>
      <script src="/Public/plugins/plugins/plugins.js"></script>
   </head>
   <script>
   function toggleStatus(t,v){
	   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
	   $.post("<?php echo U('Admin/Users/editUserStatus');?>",{id:v,userStatus:t},function(data,textStatus){
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
   function toEdit(id){
	   var url = "<?php echo U('Admin/Users/toEditAccount',array('id'=>'__0'));?>".replace('__0',id);
	   Plugins.Modal({url:url,title:'编辑账号信息',width:500});
   }
   </script>
   <body class='wst-page'>
     <form method='post' action='<?php echo U("Admin/Users/queryAccountByPage");?>'>
       <div class='wst-tbar'>
       会员账号：<input type='text' id='loginName' name='loginName' class='form-control wst-ipt-10' value='<?php echo ($loginName); ?>'/>
       会员类型：<select id='userType' name='userType' class="form-control wst-ipt-10">
           <option value='-1' <?php if( $userType == -1 ): ?>selected<?php endif; ?>>全部</option>
           <option value='0' <?php if( $userType == 0 ): ?>selected<?php endif; ?>>普通会员</option>
           <option value='1' <?php if( $userType == 1 ): ?>selected<?php endif; ?>>商家会员</option>
       </select>  
         账号状态：<select id='userStatus' name='userStatus' class="form-control wst-ipt-10">
           <option value='-1' <?php if( $userStatus == -1 ): ?>selected<?php endif; ?>>全部</option>
           <option value='1' <?php if( $userStatus == 1 ): ?>selected<?php endif; ?>>启用</option>
           <option value='0' <?php if( $userStatus == 0 ): ?>selected<?php endif; ?>>停用</option>
       </select>   
  <button type="submit" class="btn btn-primary glyphicon glyphicon-search">查询</button> 
       </div>
       </form>
       <div class="wst-body">
        <table class="table table-hover table-striped table-bordered wst-list">
           <thead>
             <tr>
               <th width='10'>&nbsp;</th>
               <th width='80'>账号</th>
               <th width='30'>安全码</th>
               <th width='80'>用户名</th>
               <th width='90'>手机号码</th>
               <th width='100'>电子邮箱</th> 
               <th width='100'>最后登录时间</th>
               <th width='40'>状态</th>
               <th width='40'>操作</th>
             </tr>
           </thead>
           <tbody>
            <?php if(is_array($Page['root'])): $i = 0; $__LIST__ = $Page['root'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
               <td><?php echo ($i); ?></td>
               <td><?php echo ($vo['loginName']); ?></td>
               <td><?php echo ($vo['loginSecret']); ?>&nbsp;</td>
               <td><?php echo ($vo['userName']); ?>&nbsp;</td>
               <td><?php echo ($vo['userPhone']); ?>&nbsp;</td>
               <td><?php echo ($vo['userEmail']); ?>&nbsp;</td>
               <td><?php echo ($vo['lastTime']); ?>&nbsp;</td>
               <td>
               <div class="dropdown">
               <?php if($vo['userStatus']==0 ): ?><button class="btn btn-danger dropdown-toggle wst-btn-dropdown"  type="button" data-toggle="dropdown">
					     停用
					  <span class="caret"></span>
				   </button>
               <?php else: ?>
                   <button class="btn btn-success dropdown-toggle wst-btn-dropdown" type="button" data-toggle="dropdown">
					     启用
					  <span class="caret"></span>
				   </button><?php endif; ?>
               <?php if(in_array('hyzh_04',$WST_STAFF['grant'])){ ?>
                   <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
					  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleStatus(1,<?php echo ($vo['userId']); ?>)">启用</a></li>
					  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleStatus(0,<?php echo ($vo['userId']); ?>)">停用</a></li>
				   </ul>
			   <?php } ?>
               </div>
               </td>
               <td>
               <?php if(in_array('hyzh_04',$WST_STAFF['grant'])){ ?>
               <a class="btn btn-default glyphicon glyphicon-pencil" href="javascript:toEdit(<?php echo ($vo['userId']); ?>)">修改</a>&nbsp;
               <?php } ?>
               </td>
             </tr><?php endforeach; endif; else: echo "" ;endif; ?>
             <tr>
                <td colspan='12' align='center'><?php echo ($Page['pager']); ?></td>
             </tr>
           </tbody>
        </table>
       </div>
   </body>
</html>