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
	   Plugins.confirm({title:'信息提示',content:'您确定要删除该商家吗?',okText:'确定',cancelText:'取消',okFun:function(){
		   Plugins.closeWindow();
		   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
		   $.post("<?php echo U('Admin/Shops/del');?>",{id:id},function(data,textStatus){
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
   $(document).ready(function(){
	    <?php if(!empty($areaId1)): ?>getAreaList("areaId2",'<?php echo ($areaId1); ?>',0,'<?php echo ($areaId2); ?>');<?php endif; ?>
  });
   </script>
   <body class='wst-page'>
      <form method='post' action="<?php echo U('Admin/Shops/queryPeddingByPage');?>">
       <div class='wst-tbar'>
           <p style="display:none;">
    地区：<select id='areaId1' name='areaId1' onchange='javascript:getAreaList("areaId2",this.value,0)'>
               <option value=''>请选择</option>
               <?php if(is_array($areaList)): $i = 0; $__LIST__ = $areaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo['areaId']); ?>' <?php if($areaId1 == $vo['areaId'] ): ?>selected<?php endif; ?>><?php echo ($vo['areaName']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
             </select>
             <select id='areaId2' name='areaId2'>
               <option value=''>请选择</option>
             </select>
           </p>
       商户名称：<input type='text' id='shopName' name='shopName' class='form-control wst-ipt-10' value='<?php echo ($shopName); ?>'/>
       商户编号：<input type='text' id='shopSn' name='shopSn' class='form-control wst-ipt-10' value='<?php echo ($shopSn); ?>'/>
      商户状态 ：<select id='shopStatus' name='shopStatus'>
             <option value='-999'>全部</option>
             <option value='0' <?php if($shopStatus ==0 ): ?>selected<?php endif; ?>>待审核</option>
             <option value='-1' <?php if($shopStatus ==-1 ): ?>selected<?php endif; ?>>拒绝</option>
             <option value='-2' <?php if($shopStatus ==-2 ): ?>selected<?php endif; ?>>已停止</option>
         </select>
  <button type="submit" class="btn btn-primary glyphicon glyphicon-search">查询</button> 
       <a class="btn btn-success glyphicon glyphicon-plus" href="<?php echo U('Admin/Shops/toEdit');?>" style='float:right'>新增</a>
       </div>
       </form>
       <div class='wst-page'>
        <table class="table table-hover table-striped table-bordered wst-list">
           <thead>
             <tr>
               <th width='30'>序号</th>
               <th width='80'>商户名称</th>
               <th width='80'>商户编号</th>
               <th width='80'>店主</th>
               <th width='80'>分类</th>
               <th width='60'>营业状态</th>
               <th width='60'>商户状态</th>
               <th width='60'>申请时间</th>
               <th width='120'>操作</th>
             </tr>
           </thead>
           <tbody>
            <?php if(is_array($Page['root'])): $i = 0; $__LIST__ = $Page['root'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
               <td><?php echo ($i); ?></td>
               <td><?php echo ($vo['shopName']); ?></td>
               <td><?php echo ($vo['shopSn']); ?>&nbsp;</td>
               <td><?php echo ($vo['userName']); ?>&nbsp;</td>
               <td><?php echo ($vo['catName']); ?>&nbsp;</td>
               <td>
               <?php if($object['shopAtive'] ==1 ): ?><span class='label label-success'>营业中</span><?php endif; ?>
               <?php if($object['shopAtive'] ==0 ): ?><span class='label label-warning'>休息中</span><?php endif; ?>&nbsp;
               </td>
               <td>
               <?php if($vo['shopStatus'] == -2): ?><span class='label label-danger wst-label'>已停止</span><?php endif; ?>
               <?php if($vo['shopStatus'] == -1): ?><span class='label label-danger wst-label'>拒绝</span><?php endif; ?>
               <?php if($vo['shopStatus'] == 0): ?><span class='label label-primary wst-label'>未审核</span><?php endif; ?>
               <?php if($vo['shopStatus'] == 1): ?><span class='label label-success wst-label'>已审核</span><?php endif; ?>
               </td>
               <td>
                   <?php echo ($vo['createTime']); ?>
               </td>
               <td>
               <?php if(in_array('dplb_02',$WST_STAFF['grant'])){ ?>
               <a class="btn btn-default glyphicon glyphicon-pencil" href="<?php echo U('Admin/Shops/toEdit',array('id'=>$vo['shopId'],'src'=>'queryPeddingByPage'));?>">修改</a>&nbsp;
               <?php } ?>
               <?php if(in_array('dplb_03',$WST_STAFF['grant'])){ ?>
               <button type="button" class="btn btn-default glyphicon glyphicon-trash" onclick="javascript:del(<?php echo ($vo['shopId']); ?>)">刪除</buttona>
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