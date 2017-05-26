<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?php echo ($CONF['mallTitle']); ?>后台管理中心</title>
      <link href="/Public/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <link href="/Apps/Admin/View/css/AdminLTE.css" rel="stylesheet" type="text/css" />
      <link rel="stylesheet" href="/Apps/Admin/View/css/daterangepicker/daterangepicker-bs3.css">
      <!--[if lt IE 9]>
      <script src="/Public/js/html5shiv.min.js"></script>
      <script src="/Public/js/respond.min.js"></script>
      <![endif]-->
      <script src="/Public/js/jquery.min.js"></script>
      <script src="/Public/plugins/bootstrap/js/bootstrap.min.js"></script>
      <script src="/Apps/Admin/View/js/daterangepicker.js"></script>
      <script src="/Public/js/common.js"></script>
      <script src="/Public/plugins/plugins/plugins.js"></script>
   </head>
   <script>
   function query(id){
	   var date = $('#logDate').val().split(' -> ');
	   $('#startDate').val(date[0]);
	   $('#endDate').val(date[1]);
       $('#form1').submit();   
   }
   $(function(){
	   $('#logDate').daterangepicker({format:'YYYY-MM-DD',separator:' -> '});
   })
   </script>
   <body class='wst-page'>
     <form method='post' action='<?php echo U("Admin/LogLogins/index");?>'>
       <div class='wst-tbar' style='height:25px;'>
       <input type='hidden' id='startDate' name='startDate' value='<?php echo ($startDate); ?>'/>
       <input type='hidden' id='endDate' name='endDate' value='<?php echo ($endDate); ?>'/>
       登录日期：<input type='text' id='logDate' class="form-control" readonly='true' style='width:200px' value='<?php echo ($startDate); ?> -> <?php echo ($endDate); ?>'/>
       <input type='text' id='key' name='key' value='<?php echo ($key); ?>'/>
       <button type="submit" class="btn btn-primary glyphicon glyphicon-search" onclick='javascript:query()'>查询</button> 
       </div>
       </form>
       <div class="wst-body"> 
        <table class="table table-hover table-striped table-bordered wst-list">
           <thead>
             <tr>
               <th width='40'>序号</th>
               <th width='80'>账号</th>
               <th width='80'>姓名</th>
               <th width='150'>登录时间</th>
               <th width='150'>登录IP</th>
             </tr>
           </thead>
           <tbody>
            <?php if(is_array($Page['root'])): $i = 0; $__LIST__ = $Page['root'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
               <td><?php echo ($i); ?></td>
               <td><?php echo ($vo['loginName']); ?></td>
               <td><?php echo ($vo['staffName']); ?></td>
               <td><?php echo ($vo['loginTime']); ?></td>
               <td><?php echo ($vo['loginIp']); ?></td>   
             </tr><?php endforeach; endif; else: echo "" ;endif; ?>
             <tr>
                <td colspan='5' align='center'><?php echo ($Page['pager']); ?></td>
             </tr>
           </tbody>
        </table>
       </div>
   </body>
</html>