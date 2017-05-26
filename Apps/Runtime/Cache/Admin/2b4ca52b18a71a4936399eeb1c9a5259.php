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
   function editName(obj){
	   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
	   $.post("<?php echo U('Admin/GoodsCats/editName');?>",{id:$(obj).attr('dataId'),catName:obj.value},function(data,textStatus){
			var json = WST.toJson(data);
			if(json.status=='1'){
				Plugins.setWaitTipsMsg({content:'操作成功',timeout:1000});
			}else{
				Plugins.closeWindow();
				Plugins.Tips({title:'信息提示',icon:'error',content:'操作失败!',timeout:1000});
			}
		});
   }



   function toggleIsFloor(t,v){
	   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
	   $.post("<?php echo U('Admin/GoodsCats/editIsFloor');?>",{id:v,isFloor:t},function(data,textStatus){
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








   function toggleIsShow(t,v){
	   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
	   $.post("<?php echo U('Admin/GoodsCats/editiIsShow');?>",{id:v,isShow:t},function(data,textStatus){
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
	   Plugins.confirm({title:'信息提示',content:'您确定要删除该分类吗?',okText:'确定',cancelText:'取消',okFun:function(){
		   Plugins.closeWindow();
		   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
		   $.post("<?php echo U('Admin/GoodsCats/del');?>",{id:id},function(data,textStatus){
				var json = WST.toJson(data);
				if(json.status=='1'){
					Plugins.setWaitTipsMsg({content:'操作成功',timeout:1000,callback:function(){
					    location.reload();
					}});
				}else if(json.status=='0'){
					Plugins.setWaitTipsMsg({content:'该分类下有子类或商品，不可删除',timeout:1000,callback:function(){
						location.reload();
					}});
			   }else{
					Plugins.Tips({title:'信息提示',icon:'error',content:'操作失败!',timeout:1000});
				}
			});
		    return false;
	   }});
   }
   function loadChildTree(obj,pid,objId){
		var str = objId.split("_");
		level = (str.length-2);
		if($(obj).hasClass('glyphicon-minus')){
			$(obj).removeClass('glyphicon-minus').addClass('glyphicon-plus');
			$('tr[class^="'+objId+'"]').hide();
		}else{
			$(obj).removeClass('glyphicon-plus').addClass('glyphicon-minus');
			$('tr[class^="'+objId+'"]').show();
			$('tr[class^="'+objId+'"] > td >.glyphicon-plus').each(function(){
				$(this).removeClass('glyphicon-plus').addClass('glyphicon-minus');
			})
		}
	}
   </script>

   <body class='wst-page'>
       <div class='wst-tbar' style='text-align:right;height:25px;'>
       <?php if(in_array('spfl_01',$WST_STAFF['grant'])){ ?>
       <a class="btn btn-success glyphicon glyphicon-plus" href="<?php echo U('Admin/GoodsCats/toEdit');?>" style='float:right'>新增</a>
       <?php } ?>
       </div>
       <div class='wst-body'> 
        <table class="table table-hover table-striped table-bordered wst-list">
           <thead>
             <tr>
               <th>分类名称</th>
               <th width='80'>排序号</th>
               <th width='80'>是否显示</th>
               <th width='80'>推荐楼层</th>
               <th width='300'>操作</th>
             </tr>
           </thead>
           <tbody>

            <?php if(is_array($List)): $i = 0; $__LIST__ = $List;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><!-- 一级分类start -->
             <tr id='tr_0_<?php echo ($i); ?>' class="tr_0" isLoad='1'>
               <td>
               <span class='glyphicon glyphicon-minus' onclick='javascript:loadChildTree(this,<?php echo ($vo["catId"]); ?>,"tr_0_<?php echo ($i); ?>")' style='margin-right:3px;cursor:pointer'></span>
               <input type='text' value='<?php echo ($vo['catName']); ?>' onchange='javascript:editName(this)' dataId="<?php echo ($vo["catId"]); ?>" class='form-control wst-ipt'/>
               </td>
               <td><?php echo ($vo['catSort']); ?></td>



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
                   <ul class="dropdown-menu" role="menu">
					  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleIsShow(1,<?php echo ($vo['catId']); ?>)">显示</a></li>
					  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleIsShow(0,<?php echo ($vo['catId']); ?>)">隐藏</a></li>
				   </ul>
               </div>
               </td>





               <td>
               <div class="dropdown">
               <?php if($vo['isFloor']==0 ): ?><button class="btn btn-danger dropdown-toggle wst-btn-dropdown"  type="button" data-toggle="dropdown">
					     不推荐
					  <span class="caret"></span>
				   </button>
               <?php else: ?>
                   <button class="btn btn-success dropdown-toggle wst-btn-dropdown" type="button" data-toggle="dropdown">
					     推荐
					  <span class="caret"></span>
				   </button><?php endif; ?>
                   <ul class="dropdown-menu" role="menu">
					  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleIsFloor(1,<?php echo ($vo['catId']); ?>)">推荐</a></li>
					  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleIsFloor(0,<?php echo ($vo['catId']); ?>)">不推荐</a></li>
				   </ul>
               </div>
               </td>






               <td>
               <?php if(in_array('spfl_01',$WST_STAFF['grant'])){ ?>
               <a class="btn btn-default glyphicon glyphicon-plus" href="<?php echo U('Admin/GoodsCats/toEdit',array('parentId'=>$vo['catId']));?>"">新增子分类</a>&nbsp;
               <?php } ?>
               <?php if(in_array('spfl_02',$WST_STAFF['grant'])){ ?>
               <a class="btn btn-default glyphicon glyphicon-pencil" href="<?php echo U('Admin/GoodsCats/toEdit',array('id'=>$vo['catId']));?>">修改</a>&nbsp;
               <?php } ?>
               <?php if(in_array('spfl_03',$WST_STAFF['grant'])){ ?>
               <a class="btn btn-default glyphicon glyphicon-trash" onclick="javascript:del(<?php echo ($vo['catId']); ?>,0)"">刪除</a>
               <?php } ?>
               </td>
             </tr>
             <!-- 一级分类end -->

             <!-- 二级分类start -->
             <?php if($vo['childNum'] > 0): if(is_array($vo['child'])): $i2 = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i2 % 2 );++$i2;?><tr id='tr_0_<?php echo ($i); ?>_<?php echo ($i2); ?>' class="tr_0_<?php echo ($i); ?>" isLoad='1'>
	               <td>
	               <span class='glyphicon glyphicon-minus' onclick='javascript:loadChildTree(this,<?php echo ($vo2["catId"]); ?>,"tr_0_<?php echo ($i); ?>_<?php echo ($i2); ?>")' style='margin-right:3px;margin-left:20px;cursor:pointer'></span>
	               <input type='text' value='<?php echo ($vo2['catName']); ?>' onchange='javascript:editName(this)' dataId="<?php echo ($vo2["catId"]); ?>" class='form-control wst-ipt'/>
	               </td>
	               <td><?php echo ($vo2['catSort']); ?></td>


	               <td>
	               <div class="dropdown">
	               <?php if($vo2['isShow']==0 ): ?><button class="btn btn-danger dropdown-toggle wst-btn-dropdown"  type="button" data-toggle="dropdown">
						     隐藏
						  <span class="caret"></span>
					   </button>
	               <?php else: ?>
	                   <button class="btn btn-success dropdown-toggle wst-btn-dropdown" type="button" data-toggle="dropdown">
						     显示
						  <span class="caret"></span>
					   </button><?php endif; ?>
	                   <ul class="dropdown-menu" role="menu">
						  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleIsShow(1,<?php echo ($vo2['catId']); ?>)">显示</a></li>
						  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleIsShow(0,<?php echo ($vo2['catId']); ?>)">隐藏</a></li>
					   </ul>
	               </div>
	               </td>

				   <td>
	               <div class="dropdown">
	               <?php if($vo2['isFloor']==0 ): ?><button class="btn btn-danger dropdown-toggle wst-btn-dropdown"  type="button" data-toggle="dropdown">
						     不推荐
						  <span class="caret"></span>
					   </button>
	               <?php else: ?>
	                   <button class="btn btn-success dropdown-toggle wst-btn-dropdown" type="button" data-toggle="dropdown">
						     推荐
						  <span class="caret"></span>
					   </button><?php endif; ?>
	                   <ul class="dropdown-menu" role="menu">
						  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleIsFloor(1,<?php echo ($vo2['catId']); ?>)">推荐</a></li>
						  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleIsFloor(0,<?php echo ($vo2['catId']); ?>)">不推荐</a></li>
					   </ul>
	               </div>
	               </td>

	               <td>
	               <?php if(in_array('spfl_01',$WST_STAFF['grant'])){ ?>
	               <a class="btn btn-default glyphicon glyphicon-plus" href="<?php echo U('Admin/GoodsCats/toEdit',array('parentId'=>$vo2['catId']));?>">新增子分类</a>&nbsp;
	               <?php } ?>
	               <?php if(in_array('spfl_02',$WST_STAFF['grant'])){ ?>
	               <a class="btn btn-default glyphicon glyphicon-pencil" href="<?php echo U('Admin/GoodsCats/toEdit',array('id'=>$vo2['catId']));?>">修改</a>&nbsp;
	               <?php } ?>
	               <?php if(in_array('spfl_03',$WST_STAFF['grant'])){ ?>
	               <a class="btn btn-default glyphicon glyphicon-trash" onclick="javascript:del(<?php echo ($vo2['catId']); ?>,0)"">刪除</a>
	               <?php } ?>
	               </td>
	             </tr>
	             <!-- 二级分类end -->

	             <!-- 三级分类start -->
                 <?php if($vo2['childNum'] > 0): if(is_array($vo2['child'])): $i3 = 0; $__LIST__ = $vo2['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo3): $mod = ($i3 % 2 );++$i3;?><tr id='tr_0_<?php echo ($i); ?>_<?php echo ($i2); ?>_<?php echo ($i3); ?>' class="tr_0_<?php echo ($i); ?>_<?php echo ($i2); ?>" isLoad='1'>
		               <td>
		               <span class='glyphicon glyphicon-minus'  style='margin-right:3px;margin-left:40px;cursor:pointer'></span>
		               <input type='text' value='<?php echo ($vo3['catName']); ?>' onchange='javascript:editName(this)' dataId="<?php echo ($vo3["catId"]); ?>" class='form-control wst-ipt'/>
		               </td>
		               <td><?php echo ($vo3['catSort']); ?></td>


		               <td>
		               <div class="dropdown">
		               <?php if($vo3['isShow']==0 ): ?><button class="btn btn-danger dropdown-toggle wst-btn-dropdown"  type="button" data-toggle="dropdown">
							     隐藏
							  <span class="caret"></span>
						   </button>
		               <?php else: ?>
		                   <button class="btn btn-success dropdown-toggle wst-btn-dropdown" type="button" data-toggle="dropdown">
							     显示
							  <span class="caret"></span>
						   </button><?php endif; ?>
		                   <ul class="dropdown-menu" role="menu">
							  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleIsShow(1,<?php echo ($vo3['catId']); ?>)">显示</a></li>
							  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleIsShow(0,<?php echo ($vo3['catId']); ?>)">隐藏</a></li>
						   </ul>
		               </div>
		               </td>


		               <td>
	               <div class="dropdown">
	               <?php if($vo3['isFloor']==0 ): ?><button class="btn btn-danger dropdown-toggle wst-btn-dropdown"  type="button" data-toggle="dropdown">
						     不推荐
						  <span class="caret"></span>
					   </button>
	               <?php else: ?>
	                   <button class="btn btn-success dropdown-toggle wst-btn-dropdown" type="button" data-toggle="dropdown">
						     推荐
						  <span class="caret"></span>
					   </button><?php endif; ?>
	                   <ul class="dropdown-menu" role="menu">
						  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleIsFloor(1,<?php echo ($vo3['catId']); ?>)">推荐</a></li>
						  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleIsFloor(0,<?php echo ($vo3['catId']); ?>)">不推荐</a></li>
					   </ul>
	               </div>
	               </td>
		               <td>
		               <?php if(in_array('spfl_02',$WST_STAFF['grant'])){ ?>
		               <a class="btn btn-default glyphicon glyphicon-pencil" href="<?php echo U('Admin/GoodsCats/toEdit',array('id'=>$vo3['catId']));?>"">修改</a>&nbsp;
		               <?php } ?>
		               <?php if(in_array('spfl_03',$WST_STAFF['grant'])){ ?>
		               <a class="btn btn-default glyphicon glyphicon-trash" href="javascript:del(<?php echo ($vo3['catId']); ?>,0)"">刪除</a>
		               <?php } ?>
		               </td>
		             </tr>
		        <!-- 三级分类end --><?php endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; ?>

           </tbody>
        </table>
       </div>
   </body>
</html>