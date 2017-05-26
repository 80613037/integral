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
      <script src="/Public/plugins/formValidator/formValidator-4.1.3.js"></script>
      <script src="/Public/js/common.js"></script>
      <script src="/Public/plugins/plugins/plugins.js"></script>
      <script type="text/javascript" src="/Public/plugins/raty/jquery.raty.min.js"></script>
   </head>
   <script>
   $(function () {
	   var options = {
				hints         : ['很不满意', '不满意', '一般', '满意', '非常满意'],
				width:200,
				targetKeep: true,
				starHalf:'/Public/plugins/raty/img/star-half-big.png',
				starOff:'/Public/plugins/raty/img/star-off-big.png',
				starOn:'/Public/plugins/raty/img/star-on-big.png',
				cancelOff: '/Public/plugins/raty/img/cancel-off-big.png',
			    cancelOn: '/Public/plugins/raty/img/cancel-on-big.png'
	    }
	  options.target='#goodsScore_hint';
	  options.score='<?php echo ($object['goodsScore']); ?>';
	  $('.goodsScore').raty(options);
	  options.target='#timeScore_hint';
	  options.score='<?php echo ($object['timeScore']); ?>';
	  $('.timeScore').raty(options);
	  options.target='#serviceScore_hint';
	  options.score='<?php echo ($object['serviceScore']); ?>';
	  $('.serviceScore').raty(options);
	   $.formValidator.initConfig({
		   theme:'Default',mode:'AutoTip',formID:"myform",debug:true,submitOnce:true,onSuccess:function(){
				   edit();
			       return false;
			},onError:function(msg){
		}});
	   $("#content").formValidator({onShow:"",onFocus:"评价内容为3-50个字 !",onCorrect:"输入正确"}).inputValidator({min:3,max:150,onError:"评价内容为3-50个字 !"});
	   
   });
   function edit(){
	   var params = {};
	   params.id = $('#id').val();
	   params.isShow = document.getElementById('isShow1').checked?1:0;
	   params.goodsScore = $('.goodsScore > input[name="score"]').val();
	   if(params.goodsScore==0){
		   Plugins.Tips({title:'信息提示',icon:'error',content:'选择商品评分!',timeout:1000});
		   return;
	   }
		
	   params.timeScore = $('.timeScore > input[name="score"]').val();
	   if(params.timeScore==0){
		   Plugins.Tips({title:'信息提示',icon:'error',content:'请选择时效得分 !',timeout:1000});
		   return;
	   }
		
	   params.serviceScore = $('.serviceScore > input[name="score"]').val();
	   if(params.serviceScore==0){
		   Plugins.Tips({title:'信息提示',icon:'error',content:'请选择服务得分 !',timeout:1000});
		   return;
	   }
		
	   params.content = $.trim($('#content').val());
	   if(params.content.length<3 || content.length>50){
		   Plugins.Tips({title:'信息提示',icon:'error',content:'评价内容为3-50个字 !',timeout:1000});
		   return;
	   }
	   Plugins.waitTips({title:'信息提示',content:'正在提交数据，请稍后...'});
	   $.post("<?php echo U('Admin/GoodsAppraises/edit');?>",params,function(data,textStatus){
		    var json = WST.toJson(data);
			if(json.status=='1'){
				Plugins.setWaitTipsMsg({ content:'操作成功',timeout:1000,callback:function(){
				   location.href='<?php echo U("Admin/GoodsAppraises/index");?>';
				}});
			}else{
				Plugins.closeWindow();
				Plugins.Tips({title:'信息提示',icon:'error',content:'操作失败!',timeout:1000});
			}
	  });
   }
   </script>
   <body class="wst-page">
       <form name="myform" method="post" id="myform" autocomplete="off">
        <input type='hidden' id='id' value='<?php echo ($object["id"]); ?>'/>
        <table class="table table-hover table-striped table-bordered wst-form">
          <tbody>
           <tr>
             <th width='120' align='right'>商品：</th>
             <td><img src='/<?php echo ($object['goodsThums']); ?>' width='50'/>&nbsp;<?php echo ($object['goodsName']); ?></td>
           </tr>
           <tr>
             <th width='120' align='right'>所属订单：</th>
             <td><?php echo ($object['orderNo']); ?></td>
           </tr>
           <tr>
             <th width='120' align='right'>用户：</th>
             <td><?php echo ($object['loginName']); ?></td>
           </tr>
           <tr>
             <th width='120' align='right'>评价：</th>
             <td>
             <div style='width:500px;'>
                <div style='float:left;width:70px;'>商品评分：</div>
                <div style='float:left;width:430px;'>
                <div class="goodsScore"  style='float:left'></div>
				<div id="goodsScore_hint"  style='float:left'></div>
				</div>
			 </div>
			 <div style='width:500px;'>
                <div style='float:left;width:70px;'> 时效评分：</div>
                <div style='float:left;width:430px;'>
                <div class="timeScore"  style='float:left'></div>
				<div id="timeScore_hint" style='float:left'></div>
				</div>
			 </div>
			 <div style='width:500px;'>
                <div style='float:left;width:70px;'>服务评分：</div>
                <div style='float:left;width:430px;'>
                <div class="serviceScore"  style='float:left'></div>
				<div id="serviceScore_hint"  style='float:left'></div>
				</div>
			 </div>
             </td>
           </tr>
           <tr>
             <th width='120' align='right'>状态：</th>
             <td>
             <input type='radio' id='isShow1' name='isShow' value='1' <?php if($object['isShow'] == 1 ): ?>checked<?php endif; ?>/>显示
             <input type='radio' id='isShow0' name='isShow' value='0' <?php if($object['isShow'] == 0 ): ?>checked<?php endif; ?>/>隐藏
             </td>
           </tr>
           <tr>
             <th width='120' align='right'>评语：</th>
             <td><textarea id='content' name='content' style='width:450px;height:100px;'><?php echo ($object['content']); ?></textarea></td>
           </tr>
           <tr>
             <td colspan='2' style='padding-left:250px;'>
                 <button type="submit" class="btn btn-success">保&nbsp;存</button>
                 <button type="button" class="btn btn-primary" onclick='javascript:location.href="<?php echo U('Admin/GoodsAppraises/index');?>"'>返&nbsp;回</button>
             </td>
           </tr>
           </tbody>
        </table>
       </form>
   </body>
</html>