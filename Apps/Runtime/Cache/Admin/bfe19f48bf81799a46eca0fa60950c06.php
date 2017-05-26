<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?php echo ($CONF['shopTitle']['fieldValue']); ?>后台管理中心</title>
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
   function getAreaList(objId,parentId,t,id){
	   var params = {};
	   params.parentId = parentId;
	   $('#'+objId).empty();
	   if(t<1){
		   $('#areaId3').empty();
		   $('#areaId3').html('<option value="">请选择</option>');
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
   $(function(){
	   getAreaList("areaId2",'<?php echo ($areaId1); ?>',0,'<?php echo ($areaId2); ?>');
	   getAreaList("areaId3",'<?php echo ($areaId2); ?>',1,'<?php echo ($areaId3); ?>');
   });
   function refund(url){
	   Plugins.Modal({url:url,title:'订单退款',width:600});
   }
   </script>
   <body class='wst-page'>
     <form method='post' action="<?php echo U('Admin/OrderComplains/index');?>">
       <div class='wst-tbar' style="display:none;">
          地区：<select id='areaId1' name='areaId1' onchange='javascript:getAreaList("areaId2",this.value,0)'>
             <option value=''>请选择</option>
             <?php if(is_array($areaList)): $i = 0; $__LIST__ = $areaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo['areaId']); ?>' <?php if($areaId1 == $vo['areaId'] ): ?>selected<?php endif; ?>><?php echo ($vo['areaName']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
          </select>
          <select id='areaId2' name='areaId2' onchange='javascript:getAreaList("areaId3",this.value,1);getCommunitys()'>
             <option value=''>请选择</option>
          </select>
          <select id='areaId3' name='areaId3'>
             <option value=''>请选择</option>
          </select>
       </div>
       <div class='wst-tbar'> 
       订单：<input type='text' id='orderNo' name='orderNo' value='<?php echo ($orderNo); ?>'/>
       投诉处理状态 ：<select id='complainStatus' name='complainStatus'>
             <option value='-1'>全部</option>
             <option value='0' <?php if($complainStatus ==0 ): ?>selected<?php endif; ?>>未处理</option>
             <option value='1' <?php if($complainStatus ==1 ): ?>selected<?php endif; ?>>等待应诉人回应</option>
             <option value='2' <?php if($complainStatus ==2 ): ?>selected<?php endif; ?>>应诉人回应</option>
             <option value='3' <?php if($complainStatus ==3 ): ?>selected<?php endif; ?>>等待仲裁</option>
             <option value='4' <?php if($complainStatus ==4 ): ?>selected<?php endif; ?>> 已仲裁</option>
         </select>
       <button type="submit" class="btn btn-primary glyphicon glyphicon-search">查询</button> 
       </div>
       </form>
       <div class="wst-body"> 
        <table class="table table-hover table-striped table-bordered wst-list">
           <thead>
             <tr>
                <th width='40'>序号</th>
                <th>投诉人</th>
                <th width='100'>投诉订单号</th>
                <th>被投诉人</th>
                <th width='150'>投诉类型</th>
                <th width='150'>投诉时间</th>
                <th width='120'>状态</th>
                <th width='130'>操作</th>
             </tr>
           </thead>
           <?php if(is_array($Page['root'])): $key = 0; $__LIST__ = $Page['root'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?><tbody>
             <tr>
               <td><?php echo ($key); ?></td>
               <td><?php if($vo['userName'] != ''): echo ($vo['userName']); else: echo ($vo['loginName']); endif; ?></td>
               <td><?php echo ($vo['orderNo']); ?></td>
               <td><?php echo ($vo['shopName']); ?></td>
               <td>
               <?php if($vo['complainType'] == 1): ?>承诺的没有做到
              <?php elseif($vo['complainType'] == 2): ?>
                                          未按约定时间发货
              <?php elseif($vo['complainType'] == 3): ?>
                                          未按成交价格进行交易
              <?php elseif($vo['complainType'] == 4): ?>
                                          恶意骚扰<?php endif; ?>
               </td>
               <td><?php echo ($vo["complainTime"]); ?></td>
               <td>
               <?php if($vo['complainStatus'] == 0): ?>等待处理
              <?php elseif($vo['complainStatus'] == 1): ?>
                                          等待应诉人回应
              <?php elseif($vo['complainStatus'] == 2): ?>
                                          应诉人回应
              <?php elseif($vo['complainStatus'] == 3): ?>
                                          等待仲裁
              <?php elseif($vo['complainStatus'] == 4): ?>
                                           已仲裁<?php endif; ?>
               </td>
               <td>
               <a class="btn btn-primary glyphicon" href="<?php echo U('Admin/OrderComplains/toView',array('id'=>$vo['complainId']));?>">查看</a>
               <?php if($vo['complainStatus'] < 4): if(in_array('ddts_04',$WST_STAFF['grant'])){ ?>
               <?php if(in_array($vo['complainStatus'],array(0,1,2,3))): ?><a class="btn btn-primary glyphicon" href='<?php echo U('Admin/OrderComplains/toHandle',array('id'=>$vo['complainId']));?>'>处理</a><?php endif; ?>
               <?php } endif; ?>
               </td>
             </tr><?php endforeach; endif; else: echo "" ;endif; ?>
             <tr>
                <td colspan='8' align='center'><?php echo ($Page['pager']); ?></td>
             </tr>
           </tbody>
        </table>
       </div>
   </body>
</html>