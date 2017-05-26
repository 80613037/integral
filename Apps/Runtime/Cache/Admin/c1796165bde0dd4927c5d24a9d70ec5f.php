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
   function toEdit(id,pid){
	   var url = "<?php echo U('Admin/ArticleCats/toEdit',array('id'=>'__0','parentId'=>'__1'));?>";
	   url = WST.replaceURL(url,[id,pid]);
       location.href=url;     
   }
   function editName(obj){
	   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
	   $.post("<?php echo U('Admin/ArticleCats/editName');?>",{id:$(obj).attr('dataId'),catName:obj.value},function(data,textStatus){
			var json = WST.toJson(data);
			if(json.status=='1'){
				Plugins.setWaitTipsMsg({content:'操作成功',timeout:1000});
			}else{
				Plugins.closeWindow();
				Plugins.Tips({title:'信息提示',icon:'error',content:'操作失败!',timeout:1000});
			}
		});
   }
   function toggleIsShow(t,v){
	   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
	   $.post("<?php echo U('Admin/ArticleCats/editiIsShow');?>",{id:v,isShow:t},function(data,textStatus){
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
	   Plugins.confirm({title:'信息提示',content:'您确定要删除该文章分类吗?',okText:'确定',cancelText:'取消',okFun:function(){
		   Plugins.closeWindow();
		   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
		   $.post("<?php echo U('Admin/ArticleCats/del');?>",{id:id},function(data,textStatus){
					var json = WST.toJson(data);
					if(json.status=='1'){
						Plugins.setWaitTipsMsg({content:'操作成功',timeout:1000,callback:function(){
						   location.reload();
						}});
					}else{
						Plugins.Tips({title:'信息提示',icon:'error',content:'操作失败!',timeout:1000});
					}
				});
	   }});
   }
   function loadChildTree(obj,pid,objId){
	   
		var str = objId.split("_");
		level = (str.length-2);
		if($(obj).hasClass('glyphicon-minus')){
			$(obj).removeClass('glyphicon-minus').addClass('glyphicon-plus');
			$('tr[class^="'+objId+'"]').hide();
		}else{
			if($(obj).attr('isLoad')){
				$(obj).removeClass('glyphicon-plus').addClass('glyphicon-minus');
				$('tr[class^="'+objId+'"]').show();
				$('tr[class^="'+objId+'"]').each(function(){
					//$(this).find('img').attr('src','../images/nolines_minus.gif');
				});
			}else{
				$.post("<?php echo U('Admin/ArticleCats/queryByList');?>",{id:pid},function(data,textStatus){
					$(obj).attr("isLoad",1);
					var json = WST.toJson(data);
					if(json.list && json.list.length>0){
						json = json.list;
						var html = [];
						var line = "";
						for(var i=0;i<level;i++){
							line+="&nbsp;&nbsp;&nbsp;&nbsp;";
						}
						for(var i=0;i<json.length;i++){
							var showhtml = '<div class="dropdown"><button class="btn btn-success dropdown-toggle wst-btn-dropdown" id="btn_'+json[i].catId+'" type="button" data-toggle="dropdown">显示<span class="caret"></span></button>';
							var hidehtml = '<div class="dropdown"><button class="btn btn-danger dropdown-toggle wst-btn-dropdown" id="btn_'+json[i].catId+'" type="button" data-toggle="dropdown">隐藏<span class="caret"></span></button>'
						    var dropdownhtml = '<ul class="dropdown-menu" role="menu" aria-labelledby="btn_'+json[i].catId+'">'
										  +'<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleIsShow(1,'+json[i].catId+')">显示</a></li>'
										  +'<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleIsShow(0,'+json[i].catId+')">隐藏</a></li>'
										  +'</ul></div>';
							var img = "<span class='glyphicon glyphicon-plus' onclick='javascript:loadChildTree(this,"+json[i].catId+",\""+objId+"_"+json[i].catId+"\")' style='margin-right:3px;cursor:pointer'></span>";
							html.push("<tr id='"+objId+"_"+json[i].catId+"' class="+objId+">");
							html.push("<td>"+line+(i+1)+"</td>");
							html.push("<td align='left'>"+line+img+"<input type='text' onchange='javascript:editName(this)' dataId='"+json[i].catId+"' value='"+json[i].catName+"' class='form-control wst-ipt'/></td>");
							html.push("<td>"+json[i].catSort+"</td>");
							html.push("<td>"+((json[i].isShow==1)?showhtml:hidehtml)+dropdownhtml+"</td>");
							html.push("<td>");
							<?php if(in_array('wzfl_01',$WST_STAFF['grant'])){ ?>
							html.push((level>1)?"":"<button type='button' class='btn btn-default glyphicon glyphicon-plus' onclick='javascript:toEdit(0,"+json[i].catId+")'>新增子分类</button>&nbsp;&nbsp;");
							<?php } ?>
							<?php if(in_array('wzfl_02',$WST_STAFF['grant'])){ ?>
							html.push("<button type='button' class='btn btn-default glyphicon glyphicon-pencil' onclick='javascript:toEdit("+json[i].catId+",0)'>修改</button>&nbsp;&nbsp;");
							<?php } ?>
							<?php if(in_array('wzfl_03',$WST_STAFF['grant'])){ ?>
							if(json[i].catType==0){
							    html.push("<button type='button' class='btn btn-default glyphicon glyphicon-trash' onclick='javascript:del("+json[i].catId+",\""+objId+"_"+json[i].catId+"\")'>删除</button>");
							}
							<?php } ?>
							html.push("</td>");
							html.push("</tr>");
						}
						$("#"+objId).after(html.join(''));
					}
					$(obj).removeClass('glyphicon-plus').addClass('glyphicon-minus');
				});
			}
		}
	}
   </script>
   <body class='wst-page'>
       <div class='wst-tbar' style='text-align:right;height:25px;'>
       <?php if(in_array('wzfl_01',$WST_STAFF['grant'])){ ?>
       <a class="btn btn-success glyphicon glyphicon-plus" href="<?php echo U('Admin/ArticleCats/toEdit');?>" style='float:right'>新增</a>
       <?php } ?>
       </div>
       <div class="wst-body"> 
        <table class="table table-hover table-striped table-bordered wst-list">
           <thead>
             <tr>
               <th width='40'>序号</th>
               <th>分类名称</th>
               <th width='80'>排序号</th>
               <th width='80'>是否显示</th>
               <th width='300'>操作</th>
             </tr>
           </thead>
           <tbody>
            <?php if(is_array($List)): $i = 0; $__LIST__ = $List;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr id='tr_0_<?php echo ($i); ?>' class="tr_0">
               <td><?php echo ($i); ?></td>
               <td>
               <span class='glyphicon glyphicon-plus' onclick='javascript:loadChildTree(this,<?php echo ($vo["catId"]); ?>,"tr_0_<?php echo ($i); ?>")' style='margin-right:3px;cursor:pointer'></span>
               <input type='text' value='<?php echo ($vo['catName']); ?>' onchange='javascript:editName(this)' dataId="<?php echo ($vo["catId"]); ?>" class='form-control wst-ipt'/>
               </td>
               <td><?php echo ($vo['catSort']); ?></td>
               <td>
               <div class="dropdown">
               <?php if($vo['isShow']==0 ): ?><button class="btn btn-danger dropdown-toggle wst-btn-dropdown" id='btn_<?php echo ($vo['catId']); ?>' type="button" data-toggle="dropdown">
					     隐藏
					  <span class="caret"></span>
				   </button>
               <?php else: ?>
                   <button class="btn btn-success dropdown-toggle wst-btn-dropdown" id='btn_<?php echo ($vo['catId']); ?>' type="button" data-toggle="dropdown">
					     显示
					  <span class="caret"></span>
				   </button><?php endif; ?>
               <?php if(in_array('wzfl_02',$WST_STAFF['grant'])){ ?>
                   <ul class="dropdown-menu" role="menu" aria-labelledby="btn_<?php echo ($vo['catId']); ?>">
					  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleIsShow(1,<?php echo ($vo['catId']); ?>)">显示</a></li>
					  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleIsShow(0,<?php echo ($vo['catId']); ?>)">隐藏</a></li>
				   </ul>
			   <?php } ?>
               </div>
               </td>
               <td>
               <?php if(in_array('wzfl_01',$WST_STAFF['grant'])){ ?>
               <a class="btn btn-default glyphicon glyphicon-plus" href="<?php echo U('Admin/ArticleCats/toEdit',array('parentId'=>$vo['catId']));?>">新增子分类</a>&nbsp;
               <?php } ?>
               <?php if(in_array('wzfl_02',$WST_STAFF['grant'])){ ?>
               <a class="btn btn-default glyphicon glyphicon-pencil" href="<?php echo U('Admin/ArticleCats/toEdit',array('id'=>$vo['catId']));?>">修改</a>&nbsp;
               <?php } ?>
               <?php if(in_array('wzfl_03',$WST_STAFF['grant'])){ ?>
               <?php if($vo['catType']==0 ): ?><button type="button" class="btn btn-default glyphicon glyphicon-trash" onclick="javascript:del(<?php echo ($vo['catId']); ?>,0)"">刪除</button><?php endif; ?>
               <?php } ?>
               </td>
             </tr><?php endforeach; endif; else: echo "" ;endif; ?>
           </tbody>
        </table>
        </div>
       </div>
   </body>
</html>