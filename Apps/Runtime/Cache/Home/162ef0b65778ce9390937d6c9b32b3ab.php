<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="shortcut icon" href="favicon.ico"/>
  		<title>卖家中心 - <?php echo ($CONF['mallTitle']); ?></title>
  		<meta name="keywords" content="<?php echo ($CONF['mallKeywords']); ?>" />
      	<meta name="description" content="<?php echo ($CONF['mallDesc']); ?>,卖家中心" />
  		<meta http-equiv="Expires" content="0">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Cache-control" content="no-cache">
		<meta http-equiv="Cache" content="no-cache">
  		<link rel="stylesheet" href="/Apps/Home/View/default/css/common.css" />
    	<link rel="stylesheet" href="/Apps/Home/View/default/css/shop.css">
    	<link rel="stylesheet" type="text/css" href="/Public/plugins/webuploader/webuploader.css" />
		<?php echo WSTLoginTarget(1);?>
    </head>
    <body>
        <div class="wst-wrap">
          <div class='wst-header'>
			<script src="/Public/js/jquery.min.js"></script>
<script src="/Public/plugins/lazyload/jquery.lazyload.min.js?v=1.9.1"></script>
<script src="/Public/plugins/layer/layer.min.js"></script>
<script type="text/javascript">
var WST = ThinkPHP = window.Think = {
        "ROOT"   : "",
        "APP"    : "/index.php",
        "PUBLIC" : "/Public",
        "DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>",
        "MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
        "VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"],
        "DOMAIN" : "<?php echo WSTDomain();?>",
        "CITY_ID" : "<?php echo ($currArea['areaId']); ?>",
        "CITY_NAME" : "<?php echo ($currArea['areaName']); ?>",
        "DEFAULT_IMG": "<?php echo WSTDomain();?>/<?php echo ($CONF['goodsImg']); ?>",
        "MALL_NAME" : "<?php echo ($CONF['mallName']); ?>",
        "SMS_VERFY"  : "<?php echo ($CONF['smsVerfy']); ?>",
        "PHONE_VERFY"  : "<?php echo ($CONF['phoneVerfy']); ?>",
        "IS_LOGIN" :"<?php echo ($WST_IS_LOGIN); ?>"
}
    $(function() {
    	$('.lazyImg').lazyload({ effect: "fadeIn",failurelimit : 10,threshold: 200,placeholder:WST.DEFAULT_IMG});
    });
</script>
<script src="/Public/js/think.js"></script>
<div id="wst-shortcut">
	<div class="w">
		<ul class="fl lh" style='float:left;width:700px; display:none;'>
			<li class="fore1 ld"><b></b><a href="javascript:addToFavorite()"
				rel="nofollow">收藏<?php echo ($CONF['mallName']); ?></a></li>
			<li class="fore3 ld menu" id="app-jd" data-widget="dropdown">
				<span class="outline"></span> <span class="blank"></span> 
				<a href="<?php echo U('WebApp/Index/index');?>" target="_blank"><img src="/Apps/Home/View/default/images/icon_top_02.png"/>&nbsp;<?php echo ($CONF['mallName']); ?> 手机版</a>
			</li>

			<li class="fore4" id="biz-service" data-widget="dropdown" style='padding:0;'>&nbsp;<span style="color:#ddd;">|</span>&nbsp;&nbsp;&nbsp;
				所在城市
				【<span class='wst-city'>&nbsp;<?php echo ($currArea["areaName"]); ?>&nbsp;</span>】
				<img src="/Apps/Home/View/default/images/icon_top_03.png"/>	
				&nbsp;&nbsp;<a href="javascript:;" onclick="toChangeCity();">切换城市</a><div class="wst-downicon"><i class="triangle"></i></div>
			</li>
		</ul>
	
		<ul class="fr lh" style='float:right;'>
			<li class="fore1" id="loginbar"><a href="javascript:void();"><span style='color:blue'><?php echo ($WST_USER['userName']?$WST_USER['userName']:$WST_USER['loginName']); ?></span></a> 欢迎您来到 <a href='javascript:void();'><?php echo ($CONF['mallName']); ?></a>！<s></s>&nbsp;
			<span>
				<?php if($WST_USER['userId'] > 0): ?><a href="javascript:logout();">[退出]</a><?php endif; ?>
			</span>
			</li>
			<li class="fore2 ld">
			<?php if(session('WST_USER.userId')>0){ ?>
				<?php if(session('WST_USER.userType')==0){ ?>
				    <a href="<?php echo U('Home/Shops/toOpenShopByUser');?>" rel="nofollow">我要开店</a>
				<?php }else{ ?>
				    <?php if(session('WST_USER.loginTarget')==0){ ?>
				        <a href="<?php echo U('Home/Shops/index');?>" rel="nofollow">卖家中心</a>
				    <?php }else{ ?>
				        <!--<a href="<?php echo U('Home/Users/index');?>" rel="nofollow">买家中心</a>-->
				    <?php } ?>
				<?php } ?>
			<?php }else{ ?>
			    <a href="<?php echo U('Home/Shops/toOpenShop');?>" rel="nofollow">我要开店</a>
			<?php } ?>
			</li>
		</ul>
		<span class="clr"></span>
	</div>
</div>


			<div class="wst-shop-nav">
				<div class="wst-nav-box">
					<li class="liselect"  style="float:left;"><a href="<?php echo U('Home/Shops/index');?>" style='color:#FFFFFF;'>我是卖家</a></li>
					<div class="wst-clear"></div>
				</div>
			</div>
			<div class="wst-clear;"></div>
		</div>
          <div class='wst-nav'></div>
          <div class='wst-main'>
            <div class='wst-menu'>
            	<span class='wst-menu-title'><span></span>交易管理</span>
            	<ul>
              	<li onclick="goBack(this)" data='<?php echo U("Home/Orders/toShopOrdersList");?>' <?php if($umark == "toShopOrdersList"): ?>class='liselect'<?php endif; ?>>订单管理<span id="wst_orders_cnt" style="display:none;" class="wst-msg-tips-box"></span></li>
				<!--<li onclick="goBack(this)" data="<?php echo U('Home/Orders/toShopPintuanOrdersList');?>" <?php if($umark == "toShopPintuanOrdersList"): ?>class='liselect'<?php endif; ?>>拼团订单</li>-->
				<li onclick="goBack(this)" data="<?php echo U('Home/OrderComplains/queryShopComplainByPage');?>" <?php if($umark == "queryShopComplainByPage"): ?>class='liselect'<?php endif; ?>>投诉订单</li>
            	<li onclick="goBack(this)" data="<?php echo U('Home/OrderSettlements/toSettlementIndex');?>" <?php if($umark == "toSettlementIndex"): ?>class='liselect'<?php endif; ?>>订单结算</li>
            	</ul>
            	<span class='wst-menu-title'><span></span>商品管理</span>
            	<ul>
               	<li onclick="goBack(this)" data="<?php echo U('Home/ShopsCats/index');?>" <?php if($umark == "index"): ?>class='liselect'<?php endif; ?>>商品分类</li>
              	<li onclick="goBack(this)" data="<?php echo U('Home/Goods/queryOnSaleByPage');?>" <?php if($umark == "queryOnSaleByPage"): ?>class='liselect'<?php endif; ?>>所有商品（在售）</li>
<?php if($isSelf == 1): ?><li onclick="goBack(this)" data="<?php echo U('Home/Goods/queryOnSaleByPage',array('score'=>1));?>" <?php if($umark == "score"): ?>class='liselect'<?php endif; ?>>积分商品（在售）</li><?php endif; ?>
				<li onclick="goBack(this)" data="<?php echo U('Home/Goods/queryOnSaleByPage',array('miaos'=>1));?>" <?php if($umark == "miaos"): ?>class='liselect'<?php endif; ?>>秒杀商品（在售）</li>
				<li onclick="goBack(this)" data="<?php echo U('Home/Goods/queryOnSaleByPage',array('pintuan'=>1));?>" <?php if($umark == "pintuan"): ?>class='liselect'<?php endif; ?>>拼团商品（在售）</li>

               	<li onclick="goBack(this)" data="<?php echo U('Home/Goods/queryPenddingByPage');?>" <?php if($umark == "queryPenddingByPage"): ?>class='liselect'<?php endif; ?>>待审核商品</li>
               	<li onclick="goBack(this)" data="<?php echo U('Home/Goods/queryUnSaleByPage');?>" <?php if($umark == "queryUnSaleByPage"): ?>class='liselect'<?php endif; ?>>仓库中的商品</li>
               	<li onclick="goBack(this)" data="<?php echo U('Home/Goods/toEdit/',array('umark'=>'toEditGoods'));?>" <?php if($umark == "toEditGoods"): ?>class='liselect'<?php endif; ?>>新增商品</li>
               	<li onclick="goBack(this)" data="<?php echo U('Home/GoodsAppraises/index');?>" <?php if($umark == "GoodsAppraises"): ?>class='liselect'<?php endif; ?>>评价管理</li>

               	<li onclick="goBack(this)" data="<?php echo U('Home/AttributeCats/index');?>" <?php if($umark == "AttributeCats"): ?>class='liselect'<?php endif; ?>>商品类型</li>
               	<!--<li onclick="goBack(this)" data="<?php echo U('Home/Imports/index');?>" <?php if($umark == "Imports"): ?>class='liselect'<?php endif; ?>>数据导入</li>-->
				</ul>
              	<span class='wst-menu-title'><span></span>网店设置</span>
              	<ul>
            	<li onclick="goBack(this)" data="<?php echo U('Home/Shops/toEdit/');?>" <?php if($umark == "toEdit"): ?>class='liselect'<?php endif; ?>>商户资料</li>
              	<li onclick="goBack(this)" data="<?php echo U('Home/Shops/toShopCfg/');?>" <?php if($umark == "setShop"): ?>class='liselect'<?php endif; ?>>商户设置</li>
              	<li onclick="goBack(this)" data="<?php echo U('Home/Messages/queryByPage/');?>" id='li_queryMessageByPage' <?php if($umark == "queryMessageByPage"): ?>class='liselect'<?php endif; ?>>商城消息<span style="display:none;" class="wst-msg-tips-box"></span></li>
              	<li onclick="goBack(this)" data="<?php echo U('Home/Shops/toEditPass');?>" <?php if($umark == "toEditPass"): ?>class='liselect'<?php endif; ?>>修改密码</li>
           		</ul>
            </div>
            <div class='wst-content'>
            
<style>
.ATRoot{height:22px;line-height:22px;margin-left:5px;clear:both;cursor:pointer;}
.ATNode{margin-left:5px;line-height:22px;margin-left:17px;clear:both;cursor:pointer;}
.Hide{display:none;}
dl.areaSelect{padding:0 5px; display: inline-block; width:100%; margin-bottom: 0;/*border:1px solid #eee;*/}
dl.areaSelect:hover{border:1px solid #E5CD29;}
dl.areaSelect:hover dd{display: block;}
dl.areaSelect dd{ float: left; margin-left: 20px; cursor: pointer;}
</style>

<script src="/Public/plugins/kindeditor/kindeditor.js"></script>
<script src="/Public/plugins/kindeditor/lang/zh-CN.js"></script>
<script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=37f0869604ca86505487639427d52bf6"></script>

<script>
var relateCommunity = "<?php echo ($object['relateCommunity']); ?>".split(',');
var relateArea = "<?php echo ($object['relateArea']); ?>".split(',');
var areaId = '<?php echo ($object['areaId2']); ?>';
var shopMap = null;
var toolBar = null;
$(function () {
	   //展开按钮
	   $("#expendAll").click(function(){
			if ($(this).prop('checked')==true) {$("dl.areaSelect dd").removeClass('Hide')}
			else{$("dl.areaSelect dd").addClass('Hide')}
	   });
	   $("input[name='isDistributAll']").click(function(){
		   if($(this).val()==1){
			   $("#delivery_tr").hide();
		   }else{
			   $("#delivery_tr").show();
		   }
	   });
	   $.formValidator.initConfig({
		   theme:'Default',mode:'AutoTip',formID:"myform",debug:true,submitOnce:true,onSuccess:function(){
			   editShop();
			   return false;
			},onError:function(msg){
		}});
	    $("#shopName").formValidator({onShow:"",onFocus:"商户名称不能超过20个字符",onCorrect:"输入正确"}).inputValidator({min:1,max:20,onError:"商户名称不符合要求,请确认"});
		$("#userName").formValidator({onShow:"",onFocus:"请输入店主姓名",onCorrect:"输入正确"}).inputValidator({min:1,max:20,onError:"店主姓名不能为空,请确认"});
		$("#shopCompany").formValidator({onShow:"",onFocus:"请输入公司名称",onCorrect:"输入正确"}).inputValidator({min:1,max:50,onError:"公司名称不能为空,请确认"});
		$("#shopTel").formValidator({onShow:"",onFocus:"请输入商户电话",onCorrect:"输入正确"}).inputValidator({min:1,max:50,onError:"商户电话不能为空,请确认"});
		$("#shopAddress").formValidator({onShow:"",onFocus:"请输入公司地址",onCorrect:"输入正确"}).inputValidator({min:1,max:50,onError:"公司地址不能为空,请确认"});
		$("#bankNo").formValidator({onShow:"",onFocus:"请输入银行卡号",onCorrect:"输入正确"}).inputValidator({min:1,max:50,onError:"银行卡号不能为空,请确认"});
		$("#bankUserName").formValidator({onShow:"",onFocus:"请输入银行卡所有人名称",onCorrect:"输入正确"}).inputValidator({min:1,max:50,onError:"银行卡号所有人名称不能为空,请确认"});
		$("#deliveryStartMoney").formValidator({onShow:"",onFocus:"请输入订单配送起步价",onCorrect:"输入正确"});
		$("#deliveryFreeMoney").formValidator({onShow:"",onFocus:"请输入包邮起步价",onCorrect:"输入正确"});
		$("#deliveryMoney").formValidator({onShow:"",onFocus:"请输入邮费",onCorrect:"输入正确"});
		$("#deliveryCostTime").formValidator({onShow:"",onFocus:"请输入平均配送时间",onCorrect:"输入正确"});
		$("#avgeCostMoney").formValidator({onShow:"",onFocus:"请输入平均消费金额",onCorrect:"输入正确"});


		getCommunitysForShopEdit();
		initTime('serviceStartTime','<?php echo ($object['serviceStartTime']); ?>');
		initTime('serviceEndTime','<?php echo ($object['serviceEndTime']); ?>');
		var uploading = null;
		uploadFile({
	    	  server:Think.U('Home/Shops/uploadPic'),pick:'#filePicker',
	    	  formData: {dir:'shops'},
	    	  accept: {
	    	        title: 'Images',
	    	        extensions: 'gif,jpg,jpeg,png',
	    	        mimeTypes: 'image/*'
	    	  },
	    	  callback:function(f){
	    		  layer.close(uploading);
	    		  var json = WST.toJson(f);
	    		  $('#preview').attr('src',WST.DOMAIN+"/"+json.file.savepath+json.file.savethumbname);
	    		  $('#shopImg').val(json.file.savepath+json.file.savename);
	    		  $('#preview').show();
		      },
		      progress:function(rate){
		    	  uploading = WST.msg('正在上传图片，请稍后...');
		      }
	    });
		ShopMapInit();
        KindEditor.ready(function(K) {
            editor1 = K.create('textarea[name="shopDesc"]', {
                height:'250px',
                width:"800px",
                allowFileManager : false,
                allowImageUpload : true,
                items:[
                    'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'cut', 'copy', 'paste',
                    'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
                    'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                    'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                    'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                    'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|','image','multiimage','table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
                    'anchor', 'link', 'unlink', '|', 'about'
                ],
                afterBlur: function(){ this.sync(); }
            });
        });
});
function ShopMapInit(option){
	   var opts = {zoom:$('#mapLevel').val(),longitude:$('#longitude').val(),latitude:$('#latitude').val()};
	   if(shopMap)return;
	   $('#shopMap').show();
	   shopMap = new AMap.Map('mapContainer', {
			view: new AMap.View2D({
				zoom:opts.zoom
			})
	   });
	   if(opts.longitude!='' && opts.latitude){
		   shopMap.setZoomAndCenter(opts.zoom, new AMap.LngLat(opts.longitude, opts.latitude));
		   var marker = new AMap.Marker({
				position: new AMap.LngLat(opts.longitude, opts.latitude), //基点位置
				icon:"http://webapi.amap.com/images/marker_sprite.png"
		   });
		   marker.setMap(shopMap);
	   }
	   shopMap.plugin(["AMap.ToolBar"],function(){		
			toolBar = new AMap.ToolBar();
			shopMap.addControl(toolBar);		
	   });
	   toolBar.show();
}
</script>
   <div class="wst-body"> 
       <div class='wst-page-header'>卖家中心 > 商户资料</div>
       <div class='wst-page-content' style="position:relative;">
       <form name="myform" method="post" id="myform" autocomplete="off">
        <input type='hidden' id='id' value='<?php echo ($object["shopId"]); ?>'/>
        <input type='hidden' id='shopImg' value='<?php echo ($object["shopImg"]); ?>'/>
        <table class="table table-hover table-striped table-bordered wst-form">
           <tr>
             <th width='150' align='right'>商户名称<font color='red'>*</font>：</th>
             <td><input type='text' id='shopName' class="form-control wst-ipt" value='<?php echo ($object["shopName"]); ?>' style='width:250px;' maxLength='25'/></td>
           </tr>
           <tr>
             <th align='right'>店主姓名<font color='red'>*</font>：</th>
             <td><input type='text' id='userName' class="form-control wst-ipt" value='<?php echo ($object["userName"]); ?>' style='width:250px;' maxLength='25'/></td>
           </tr>
           <tr>
             <th align='right'>公司名称<font color='red'>*</font>：</th>
             <td><input type='text' id='shopCompany' class="form-control wst-ipt" value='<?php echo ($object["shopCompany"]); ?>' style='width:250px;' maxLength='25'/></td>
           </tr>
           <tr style="height:80px;">
             <th align='right'>商户图标<font color='red'>*</font>：</th>
             <td>
	           <div>
             	    <div id="filePicker" style='margin-left:0px;float:left'>上传商户图标</div>
             	    <div style='margin-left:5px;float:left'>建议图片大小：（1:1），格式为 gif, jpg, jpeg, png</div>
             	</div>
             	<div style='clear:both;'>
	             	<?php if($object['shopImg'] !='' ): ?><img height="100" id='preview' class='lazyImg' data-original='/<?php echo ($object["shopImg"]); ?>'><?php endif; ?>
	             </div>
             </td>
           </tr>
           
           <tr>
             <th align='right'>商户电话<font color='red'>*</font>：</th>
             <td><input type='text' id='shopTel' class="form-control wst-ipt" value='<?php echo ($object["shopTel"]); ?>' maxLength='25'/></td>
           </tr>
           <tr>
             <th align='right'>QQ：</th>
             <td><input type='text' id='qqNo' class="form-control wst-ipt" value='<?php echo ($object["qqNo"]); ?>' maxLength='25'/></td>
           </tr>
           <tr>
             <th align='right'>商户地址<font color='red'>*</font>：</th>
             <td><input type='text' id='shopAddress' class="form-control wst-ipt" value='<?php echo ($object["shopAddress"]); ?>' style='width:350px;' maxLength='100'/></td>
           </tr>


           <tr>
             <th align='right'>营业状态<font color='red'>*</font>：</th>
             <td>
             <label>
             <input type='radio' name='shopAtive' value='1' <?php if($object['shopAtive'] ==1 ): ?>checked<?php endif; ?> />营业中&nbsp;&nbsp;
             </label>
             <label>
             <input type='radio' name='shopAtive' value='0' <?php if($object['shopAtive'] ==0 ): ?>checked<?php endif; ?> />休息中
             </label>
             </td>
           </tr>
           <tr style="display: none;">
             <th align='right'>配送范围<font color='red'>*</font>：</th>
             <td>
             <label>
             <input type='radio' name='isDistributAll' value='0' <?php if($object['isDistributAll'] ==0 ): endif; ?> />区域配送&nbsp;&nbsp;
             </label>
             <label>
             <input type='radio' name='isDistributAll' value='1' checked />全国配送
             </label>
             </td>
           </tr>
           <tr id="delivery_tr" style="display: <?php echo ($object['isDistributAll'] ==1?'none':''); ?>">
             <th align='right'>配送区域<font color='red'>*</font>：</th>
             <td id="wst_shop_area">
             <div class="text-gray Hide">展开全部：<input type="checkbox" id="expendAll" checked <?php if($WST_USER['isSelf'] == 1): ?>disabled<?php endif; ?> ></div>
             <div id='areaTree'></div>
             </td>
           </tr>
           <tr style="display: none;">
             <th align='right'>订单配送起步价(元)<font color='red'>*</font>：</th>
             <td><input type='text' id='deliveryStartMoney' class="form-control wst-ipt" value='<?php echo ($object["deliveryStartMoney"]); ?>' onkeypress="return WST.isNumberdoteKey(event)" onkeyup="javascript:WST.isChinese(this,1)" maxLength='6'/></td>
           </tr>
           <tr>
             <th align='right'>包邮起步价(元)<font color='red'>*</font>：</th>
             <td><input type='text' id='deliveryFreeMoney' class="form-control wst-ipt" value='<?php echo ($object["deliveryFreeMoney"]); ?>' onkeypress="return WST.isNumberdoteKey(event)" onkeyup="javascript:WST.isChinese(this,1)" maxLength='6'/></td>
           </tr>
           <tr>
             <th align='right'>邮费(元)<font color='red'>*</font>：</th>
             <td><input type='text' id='deliveryMoney' class="form-control wst-ipt" value='<?php echo ($object["deliveryMoney"]); ?>' onkeypress="return WST.isNumberdoteKey(event)" onkeyup="javascript:WST.isChinese(this,1)" maxLength='25'/></td>
           </tr>
           <tr style="display: none;">
             <th align='right'>平均配送时间(分钟)<font color='red'>*</font>：</th>
             <td><input type='text' id='deliveryCostTime' class="form-control wst-ipt" value='<?php echo ($object["deliveryCostTime"]); ?>' onkeypress="return WST.isNumberKey(event)" onkeyup="javascript:WST.isChinese(this,1)" maxLength='6'/></td>
           </tr>
           <tr style="display: none;">
             <th align='right'>平均消费金额(元)<font color='red'>*</font>：</th>
             <td><input type='text' id='avgeCostMoney' class="form-control wst-ipt" value='<?php echo ($object["avgeCostMoney"]); ?>' onkeypress="return WST.isNumberdoteKey(event)" onkeyup="javascript:WST.isChinese(this,1)" maxLength='25'/></td>
           </tr>
           <tr>
             <th align='right'>能否开发票<font color='red'>*</font>：</th>
             <td>
             <label>
             <input type='radio' id='isInvoice1' name='isInvoice' value='1' onclick='javascript:isInvoce(true)' <?php if($object['isInvoice'] ==1 ): ?>checked<?php endif; ?> />能&nbsp;&nbsp;
             </label>
             <label>
             <input type='radio' id='isInvoice0' name='isInvoice' value='0' onclick='javascript:isInvoce(false)' <?php if($object['isInvoice'] ==0 ): ?>checked<?php endif; ?> />否
             </label>
             </td>
           </tr>
           <tr id='invoiceRemarkstr' <?php if($object['isInvoice'] ==0 ): ?>style='display:none'<?php endif; ?>>
             <th align='right'>发票说明：</th>
             <td><input type='text' id='invoiceRemarks' class="form-control wst-ipt" value='<?php echo ($object["invoiceRemarks"]); ?>' style='width:350px;' maxLength='100'/></td>
           </tr>
           <tr>
             <th width='120' align='right'>转账银行<font color='red'>*</font>：</th>
             <td>
             <select id='bankId'>
                <option value=''>请选择</option>
                <?php if(is_array($bankList)): $i = 0; $__LIST__ = $bankList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option <?php if($vo['bankId'] == $object['bankId']): ?>selected<?php endif; ?> value='<?php echo ($vo['bankId']); ?>'><?php echo ($vo['bankName']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
             </select>
             </td>
           </tr>
           <tr>
             <th width='120' align='right'>银行卡卡号<font color='red'>*</font>：</th>
             <td><input type='text' id='bankNo' value='<?php echo ($object["bankNo"]); ?>' maxLength='25' size='50'/></td>
           </tr>
           <tr>
             <th width='120' align='right'>银行卡所有人名称<font color='red'>*</font>：</th>
             <td><input type='text' id='bankUserName' value='<?php echo ($object["bankUserName"]); ?>' maxLength='25' size='50'/></td>
           </tr>
           <tr>
             <th align='right'>营业时间<font color='red'>*</font>：</th>
             <td>
             <select id='serviceStartTime'>
                <option>请选择</option>
             </select>
             至
             <select id='serviceEndTime'>
                <option>请选择</option>
             </select>
             </td>
           </tr>
            <tr>
                <th width='120'>商户描述<font color='red'>*</font>：</th>
                <td colspan='3'>
                    <textarea rows="2" cols="60" id='shopDesc' class='wstipt' name='shopDesc'><?php echo ($object["shopDesc"]); ?></textarea>
                </td>
            </tr>
           <tr>
             <td colspan='2' style='text-align:center;padding:20px;'>
                 <button type="submit" class='wst-btn-query'>保&nbsp;存</button>&nbsp;&nbsp;
                 <button type="button" class='wst-btn-query' onclick='javascript:location.reload();'>重&nbsp;置</button>
             </td>
           </tr>
        </table>
       </form>
       </div>
   </div>
   <script type="text/javascript">
   var isSelf = "<?php echo ($WST_USER['isSelf']); ?>"; 
	</script>

            </div>
          </div>
          <div style='clear:both;'></div>
          <br/>
          <div class='wst-footer'>
          	<div class="wst-footer-fl-box">
	<div class="wst-footer" >
		<div class="wst-footer-cld-box">
			<div class="wst-footer-fl">友情链接：</div>
			<div style="padding-left:30px;">
				<?php if(is_array($friendLikds)): $k = 0; $__LIST__ = $friendLikds;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><div style="float:left;"><a href="<?php echo ($vo["friendlinkUrl"]); ?>" target="_blank"><?php echo ($vo["friendlinkName"]); ?></a>&nbsp;&nbsp;</div><?php endforeach; endif; else: echo "" ;endif; ?>
				<div class="wst-clear"></div>
			</div>
		</div>
	</div>
</div>

<div class="wst-footer-hp-box" style="display:none;">
	<div class="wst-footer">
		<div class="wst-footer-hp-ck1">
			<?php if(is_array($helps)): $k1 = 0; $__LIST__ = $helps;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($k1 % 2 );++$k1;?><div class="wst-footer-wz-ca">
				<div class="wst-footer-wz-pt">
				    <img src="/Apps/Home/View/default/images/a<?php echo ($k1); ?>.jpg" height="18" width="18"/>
					<span class="wst-footer-wz-pn"><?php echo ($vo1["catName"]); ?></span>
					<div style='margin-left:30px;'>
						<?php if(is_array($vo1['articlecats'])): $k2 = 0; $__LIST__ = $vo1['articlecats'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($k2 % 2 );++$k2;?><a href="<?php echo U('Home/Articles/index/',array('articleId'=>$vo2['articleId']));?>"><?php echo ($vo2['articleTitle']); ?></a><br/><?php endforeach; endif; else: echo "" ;endif; ?>
					</div>
				</div>
			</div><?php endforeach; endif; else: echo "" ;endif; ?>
			
			<div class="wst-footer-wz-clt">
				<div style="padding-top:5px;line-height:25px;">
				    <img src="/Apps/Home/View/default/images/a6.jpg" height="18" width="18"/>
					<span class="wst-footer-wz-kf">联系客服</span>
					<div style='margin-left:30px;'>
						<a href="#">联系电话</a><br/>
						<?php if($CONF['phoneNo'] != ''): ?><span class="wst-footer-pno"><?php echo ($CONF['phoneNo']); ?></span><br/><?php endif; ?>
						<?php if($CONF['qqNo'] != ''): ?><a href="tencent://message/?uin=<?php echo ($CONF['qqNo']); ?>&Site=QQ交谈&Menu=yes">
						<img border="0" src="http://wpa.qq.com/pa?p=1:<?php echo ($CONF['qqNo']); ?>:7" alt="QQ交谈" width="71" height="24" />
						</a><br/><?php endif; ?>
						
					</div>
				</div>
			</div>
			<div class="wst-clear"></div>
		</div>

	</div>
</div>

          </div>
        </div>
        <object id="player" height="360" width="300" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" style="display:none;">
			<param name="AUTOSTART" value="0"/>
			<param name="SHUFFLE" value="0"/>
			<param name="PREFETCH" value="0"/>
			<param name="NOLABELS" value="0"/>
			<param name="url" value=""/>
			<param name="CONTROLS" value="ImageWindow"/>
			<param name="CONSOLE" value="Clip1"/>
			<param name="LOOP" value="0"/>
			<param name="NUMLOOP" value="0"/>
			<param name="CENTER" value="0"/>
			<param name="MAINTAINASPECT" value="0"/>
			<param name="BACKGROUNDCOLOR" value="#000000"/>
		</object>
    </body>
      	<script src="/Public/plugins/formValidator/formValidator-4.1.3.js"></script>
     	<script src="/Public/js/common.js"></script>
      	<script src="/Apps/Home/View/default/js/shopcom.js"></script>
      	<script src="/Apps/Home/View/default/js/head.js"></script>
      	<script src="/Apps/Home/View/default/js/common.js"></script>
      	<script src="/Public/plugins/layer/layer.min.js"></script>

      	<script type="text/javascript" src="/Public/plugins/webuploader/webuploader.js"></script>
      	<script type="text/javascript" src="/Apps/Home/View/default/js/goodsbatchupload.js"></script>
	<script src="/Apps/Home/View/default/js/audio.js"></script>
</html>