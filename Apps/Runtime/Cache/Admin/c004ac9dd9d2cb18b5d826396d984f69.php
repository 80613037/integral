<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?php echo ($CONF['shopTitle']); ?>后台管理中心</title>
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
      <script type="text/javascript" src="/Public/plugins/echarts/echarts.common.min.js"></script>
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
   function queryByMonthAndDays(){
	   var style,option;
	   var params = {};
	   var date = $('#queryDate').val().split(' -> ');
	   params.statType = $('#statType').val();
	   params.startDate = date[0];
	   params.endDate = date[1];
	   params.areaId1 = $('#areaId1').val();
	   params.areaId2 = $('#areaId2').val();
	   params.areaId3 = $('#areaId3').val();
	   var myChart = echarts.init(document.getElementById('container')); 
	   style = {
				  normal: {
		              label: {
		                      show: true,
		                      position: 'top',
		                      formatter: '{c}'
		               }
				  }
	   }
	   $('#container').show();
	   $.post("<?php echo U('Admin/OrderRpts/queryByMonthAndDays');?>",params,function(data,textStatus){
			 var json = WST.toJson(data);
		     if(json.status=='1'){
		    	 if(json.list){
		    		 var days = [];
		    		 var ur = [];
		    		 var sr = [];
		    		 for(var key in json.list){
		    		     days.push(key);
		    			 if(json.list[key]['o_0']){
		    				 ur.push(parseFloat(json.list[key]['o_0'],10));
		    			 }else{
		    				 ur.push(0);
		    			 }
		    		 }
		    		 option = {
		   			        	title : {
		   			        	    text: '订单统计'
		   			        	},
		   			        	tooltip : {
		   			        	    trigger: 'axis'
		   			        	},
		   			        	legend: {
		   			        	    data:['有效订单']
		   			        	},
		   			        	calculable : true,
		   			        	xAxis : [
		   			        	    {
		   			        	         type : 'category',
		   			        	         data : days,
		   			        	         boundaryGap : false
		   			        	    }
		   			        	],
		   			        	yAxis : [
		   			        	    {
		   			        	         type : 'value',
		   			        	         axisLabel : {
							                formatter: '{value}'
							             }
		   			        	    }
		   			        	],
		   			        	series : [
		   			        	    {
		   			        	        name:'订单',
		   			        	        type:'line',
		   			        	        data:ur,
		   			        	        itemStyle:style,
		 			        	        markPoint : {
		   			                       data :ur
		   			                    },
		   			                    markLine : {
		       			                   data : [
		       			                      {type : 'average', name : '平均值'}
		       			                   ]
		       			                }
		   			        	    }
		   			            ]
		   			      }; 
		     			  myChart.setOption(option); 
		    	 }else{
		    		 $('#container').empty();
		    	 }		    	 
		     }
		});
	    
   }
   $(function(){
	   $('#queryDate').daterangepicker({format:'YYYY-MM-DD',separator:' -> '});
	   queryByMonthAndDays();
   });
   </script>
   <body class='wst-page'>
      <form method="post" action='<?php echo U("Admin/Orders/index");?>'>
       <div class='wst-tbar'>
           <label style="display:none;">
             地区：<select name='areaId1' id='areaId1' onchange='javascript:getAreaList("areaId2",this.value,0)'>
             <option value=''>请选择</option>
             <?php if(is_array($areaList)): $i = 0; $__LIST__ = $areaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo['areaId']); ?>' <?php if($areaId1 == $vo['areaId'] ): ?>selected<?php endif; ?>><?php echo ($vo['areaName']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
          </select>
          <select name='areaId2' id='areaId2' onchange='javascript:getAreaList("areaId3",this.value,1);'>
             <option value=''>请选择</option>
          </select>
          <select name='areaId3' id='areaId3'>
             <option value=''>请选择</option>
          </select>
           </label>

          <select id='statType'>
             <option value='0'>按日统计</option>
             <option value='1'>按月统计</option>
          </select>
          <input type='text' id='queryDate' class="form-control" readonly='true' style='width:200px' value='<?php echo ($startDate); ?> -> <?php echo ($endDate); ?>'/>
          <input type="button" class="btn btn-primary glyphicon glyphicon-search" onclick='javascript:queryByMonthAndDays()' value='查询'/>
       </div>
       </form>
       <div class="wst-body"> 
	       <div id="container" style="min-width:700px;height:400px"></div>
       </div>
   </body>
</html>