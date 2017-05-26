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
      <script src="/Public/plugins/formValidator/formValidator-4.1.3.js"></script>
      <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=37f0869604ca86505487639427d52bf6"></script>
   </head>
   <script>
   var map = null;
   $(function () {
	   $.formValidator.initConfig({
		   theme:'Default',mode:'AutoTip',formID:"myform",debug:true,submitOnce:true,onSuccess:function(){
				   edit();
			       return false;
			},onError:function(msg){
		}});
	   $("#communityName").formValidator({onShow:"",onFocus:"社区名称至少要输入1个字符",onCorrect:"输入正确"}).inputValidator({min:1,max:20,onError:"你输入的长度不正确,请确认"});
	   $("#areaId3").formValidator({onFocus:"请选择所属地区"}).inputValidator({min:1,onError: "请选择所属地区"});
	   <?php if($object['communityId'] !=0 ): ?>getAreaList("areaId2",<?php echo ($object["areaId1"]); ?>,0,<?php echo ($object["areaId2"]); ?>);
	   getAreaList("areaId3",<?php echo ($object["areaId2"]); ?>,1,<?php echo ($object["areaId3"]); ?>);<?php endif; ?>
	   mapInit();
   });
   function mapInit(){
	   var opts = {zoom:$('#mapLevel').val(),longitude:$('#longitude').val(),latitude:$('#latitude').val()};
	   if(map)return;
	   map = new AMap.Map('mapContainer', {
			view: new AMap.View2D({
				zoom:opts.zoom
			})
	   });
	   if(opts.longitude!='' && opts.latitude!=''){
		   map.setZoomAndCenter(opts.zoom, new AMap.LngLat(opts.longitude, opts.latitude));
		   var marker = new AMap.Marker({
				position: new AMap.LngLat(opts.longitude, opts.latitude), //基点位置
				icon:"http://webapi.amap.com/images/marker_sprite.png"
		   });
		   marker.setMap(map);
	   }
	   map.plugin(["AMap.ToolBar"],function(){		
			toolBar = new AMap.ToolBar();
			map.addControl(toolBar);	
			toolBar.show();
	   });
	   
	   AMap.event.addListener(map,'click',function(e){
			map.clearMap();
			$('#longitude').val(e.lnglat.getLng());
			$('#latitude').val(e.lnglat.getLat());
			var marker = new AMap.Marker({
				position: e.lnglat, //基点位置
				icon:"http://webapi.amap.com/images/marker_sprite.png"
			});
			marker.setMap(map);
	   });
	   AMap.event.addListener(map,'zoomchange',function(e){
		   $('#mapLevel').val(this.getZoom());
	   })
   }
   function edit(){
	   var params = {};
	   params.id = $('#id').val();
	   params.communityName = $('#communityName').val();
	   params.isShow = $("input[name='isShow']:checked").val();
	   params.isService = $("input[name='isService']:checked").val();
	   params.areaId1 = $('#areaId1').val();
	   params.areaId2 = $('#areaId2').val();
	   params.areaId3 = $('#areaId3').val();
	   params.communitySort = $('#communitySort').val();
	   params.latitude = $('#latitude').val();
	   params.longitude = $('#longitude').val();
	   params.mapLevel = $('#mapLevel').val();
	   Plugins.waitTips({title:'信息提示',content:'正在提交数据，请稍后...'});
	   $.post("<?php echo U('Admin/Communitys/edit');?>",params,function(data,textStatus){
			var json = WST.toJson(data);
			if(json.status=='1'){
				Plugins.setWaitTipsMsg({ content:'操作成功',timeout:1000,callback:function(){
				   location.href='<?php echo U("Admin/Communitys/index");?>';
				}});
			}else{
				Plugins.closeWindow();
				Plugins.Tips({title:'信息提示',icon:'error',content:'操作失败!',timeout:1000});
			}
		});
   }
   function getAreaList(objId,parentId,t,id){
	   var params = {};
	   params.parentId = parentId;
	   $('#'+objId).empty();
	   if(t<1){
		   $('#areaId3').empty();
		   $('#areaId3').html('<option value="">请选择</option>');
	   }else{
		   if(t==1 && $('#areaId2').find("option:selected").text()!=''){
			   map.setZoom(15);
			   map.setCity($('#areaId2').find("option:selected").text());
			   $('#showLevel').val(map.getZoom());
		   }
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
   </script>
   <body class="wst-page">
       <form name="myform" method="post" id="myform" autocomplete="off">
        <input type='hidden' id='id' value='<?php echo ($object["communityId"]); ?>'/>
        <table class="table table-hover table-striped table-bordered wst-form">
           <tr>
             <th width='120' align='right'>社区名称<font color='red'>*</font>：</th>
             <td><input type='text' id='communityName' class="form-control wst-ipt" value='<?php echo ($object["communityName"]); ?>' maxLength='25'/></td>
           </tr>
           <tr>
             <th align='right'>所属地区<font color='red'>*</font>：</th>
             <td>
             <select id='areaId1' onchange='javascript:getAreaList("areaId2",this.value,0)'>
               <option value=''>请选择</option>
               <?php if(is_array($areaList)): $i = 0; $__LIST__ = $areaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo['areaId']); ?>' <?php if($object['areaId1'] == $vo['areaId'] ): ?>selected<?php endif; ?>><?php echo ($vo['areaName']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
             </select>
             <select id='areaId2' onchange='javascript:getAreaList("areaId3",this.value,1)'>
               <option value=''>请选择</option>
             </select>
             <select id='areaId3'>
               <option value=''>请选择</option>
             </select>
             </td>
           </tr>
           <tr>
             <th align='right'>商城配送<font color='red'>*</font>：</th>
             <td>
             <label>
             <input type='radio' id='isService1' name='isService' value='1' <?php if($object['isService'] ==1 ): ?>checked<?php endif; ?> />是&nbsp;&nbsp;
             </label>
             <label>
             <input type='radio' id='isService0' name='isService' value='0' <?php if($object['isService'] ==0 ): ?>checked<?php endif; ?> />否
             </label>
             </td>
           </tr>
           <tr>
             <th>经纬度:</th>
             <td>
             <div id="mapContainer" style='height:400px;width:90%;'>等待地图初始化...</div>
             <div style='display:none'>
             <input type='text' id='latitude' name='latitude' value="<?php echo ($object['latitude']); ?>"/>
             <input type='text' id='longitude' name='longitude' value="<?php echo ($object['longitude']); ?>"/>
             <input type='text' id='mapLevel' name='mapLevel' value="<?php echo ($object['mapLevel']); ?>"/>
             </div>
             </td>
           </tr>
           <tr>
             <th align='right'>是否显示<font color='red'>*</font>：</th>
             <td>
             <label>
             <input type='radio' id='isShow1' name='isShow' value='1' <?php if($object['isShow'] ==1 ): ?>checked<?php endif; ?> />显示&nbsp;&nbsp;
             </label>
             <label>
             <input type='radio' id='isShow0' name='isShow' value='0' <?php if($object['isShow'] ==0 ): ?>checked<?php endif; ?> />隐藏
             </label>
             </td>
           </tr>
           <tr>
             <th align='right'>排序号<font color='red'>*</font>：</th>
             <td>
             <input type='text' id='communitySort' class="form-control wst-ipt" value='<?php echo ($object["communitySort"]); ?>' style='width:80px' onkeypress="return WST.isNumberKey(event)" onkeyup="javascript:WST.isChinese(this,1)" maxLength='8'/>
             </td>
           </tr>
           <tr>
             <td colspan='2' style='padding-left:250px;'>
                 <button type="submit" class="btn btn-success">保&nbsp;存</button>
                 <button type="button" class="btn btn-primary" onclick='javascript:location.href="<?php echo U('Admin/Communitys/index');?>"'>返&nbsp;回</button>
             </td>
           </tr>
        </table>
       </form>
   </body>
</html>