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
            

<script src="/Public/plugins/kindeditor/kindeditor.js"></script>
<script src="/Public/plugins/kindeditor/lang/zh-CN.js"></script>

<link rel="stylesheet" type="text/css" href="/Public/plugins/webuploader/style.css" />
<style>


</style>   
<script>
var ablumInit = false;
$(function () {
	   $('#tab').TabPanel({tab:0,callback:function(no){
		    if(no==2 && !ablumInit)uploadAblumInit();
	   }});
	   $.formValidator.initConfig({
		   theme:'Default',mode:'AutoTip',formID:"myform",debug:true,submitOnce:true,onSuccess:function(){
			       editGoods('<?php echo ($umark); ?>');
			       return false;
			},onError:function(msg){
		}});
	   $("#goodsSn").formValidator({onShow:"",onFocus:"",onCorrect:"输入正确"}).inputValidator({min:1,max:50,onError:"请输入商品编号"});
	   $("#goodsName").formValidator({onShow:"",onFocus:"",onCorrect:"输入正确"}).inputValidator({min:1,max:200,onError:"请输入商品名称"});
	   $("#marketPrice").formValidator({onShow:"",onFocus:"",onCorrect:"输入正确"}).inputValidator({min:1,max:50,onError:"请输入市场价格"});
	   $("#shopPrice").formValidator({onShow:"",onFocus:"",onCorrect:"输入正确"}).inputValidator({min:1,max:50,onError:"请输入商户价格"});
	   $("#goodsStock").formValidator({onShow:"",onFocus:"",onCorrect:"输入正确"}).inputValidator({min:1,max:50,onError:"请输入库存"});
//	   $("#goodsUnit").formValidator({onShow:"",onFocus:"",onCorrect:"输入正确"}).inputValidator({min:1,max:50,onError:"请输入商品单位"});
	   $("#goodsCatId3").formValidator({onFocus:"请选择商城分类"}).inputValidator({min:1,onError: "请选择完整商城分类"});
	   $("#shopCatId2").formValidator({onFocus:"请选择本店分类"}).inputValidator({min:1,onError: "请选择完整本店分类"});
	   
	   KindEditor.ready(function(K) {
			editor1 = K.create('textarea[name="goodsDesc"]', {
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
	   <?php if($object['goodsId'] !=0 ): ?>getCatListForEdit("goodsCatId2",<?php echo ($object["goodsCatId1"]); ?>,0,<?php echo ($object["goodsCatId2"]); ?>);
	   getCatListForEdit("goodsCatId3",<?php echo ($object["goodsCatId2"]); ?>,1,<?php echo ($object["goodsCatId3"]); ?>);
	   getShopCatListForEdit(<?php echo ($object["shopCatId1"]); ?>,<?php echo ($object["shopCatId2"]); ?>,"shopCatId2");<?php endif; ?>
	   var uploading = null;
	   uploadFile({
	    	  server:Think.U('Home/Goods/uploadPic'),pick:'#goodImgPicker',
	    	  formData: {dir:'goods'},
	    	  accept: {
	    	        title: 'Images',
	    	        extensions: 'gif,jpg,jpeg,png',
	    	        mimeTypes: 'image/*'
	    	  },
	    	  callback:function(f){
	    		  layer.close(uploading);
	    		  var json = WST.toJson(f);
	    		  $('#goodsImgPreview').attr('src',WST.DOMAIN+"/"+json.file.savepath+json.file.savethumbname);
	    		  $('#goodsImg').val(json.file.savepath+json.file.savename);
	    		  $('#goodsThums').val(json.file.savepath+json.file.savethumbname);
	    		  $('#goodsImgPreview').show();
		      },
		      progress:function(rate){
		    	  uploading = WST.msg('正在上传图片，请稍后...');
		      }
	   });

	loadMsTimeAndExchangeScore();


});

function imglimouseover(obj){
	if(!$(obj).find('.file-panel').html()){
		$(obj).find('.setdel').addClass('trconb');
		$(obj).find('.setdel').css({"display":""});
	}
}

function imglimouseout(obj){
	
	$(obj).find('.setdel').removeClass('trconb');
	$(obj).find('.setdel').css({"display":"none"});
}

function imglidel(obj){
	if (confirm('是否删除图片?')) {
		$(obj).parent().remove("li");
		return;
	}
}

function imgmouseover(obj){
	$(obj).find('.wst-gallery-goods-del').show();
}
function imgmouseout(obj){
	$(obj).find('.wst-gallery-goods-del').hide();
}
function delImg(obj){
    $(obj).parent().remove();
}
</script>
       <div class="wst-body">
       <div class='wst-page-header'>卖家中心 > <?php if($object['goodsId'] ==0 ): ?>新增<?php else: ?>编辑<?php endif; ?>商品资料</div>
       <div class='wst-page-content'>
       <div id='tab' class="wst-tab-box">
		<ul class="wst-tab-nav">
	    	<li>商品信息</li>
	    	<li>属性</li>
	        <li>商品相册</li>
	        <!--<li>优惠套餐</li>-->
	    </ul>
    	<div class="wst-tab-content" style='width:99%;margin-bottom: 10px;'>
    	 
    	
    	<!-- 商品基础信息 -->
    	<div class='wst-tab-item' style="position: relative;style='display:none'">
	       <form name="myform" method="post" id="myform" autocomplete="off">
	        <input type='hidden' id='id' class='wstipt' value='<?php echo ($object["goodsId"]); ?>'/>
	      
	        <input type='hidden' id='goodsThumbs' value='<?php echo ($object["goodsThums"]); ?>'/>
	        <table class="wst-form" >
	           <tr>
	             <th width='120'>商品编号<font color='red'>*</font>：</th>
	             <td width='300'>
	             <input type='text' id='goodsSn' name='goodsSn' class="wst-ipt wstipt" value='<?php echo ($object["goodsSn"]); ?>' maxLength='25' placeholder="未填写"/>
	             </td>
	             <td rowspan='6' valign='top'>
	               <div>
		           <img id='goodsImgPreview' class='lazyImg' data-original='<?php if($object['goodsImg'] =='' ): ?>/<?php echo ($CONF['goodsImg']); else: ?>/<?php echo ($object['goodsImg']); endif; ?>' height='152'/><br/>
	               </div>
	               <input type='hidden' id='goodsImg' class='wstipt' value='<?php echo ($object["goodsImg"]); ?>'/>
	               <input type='hidden' id='goodsThums' class='wstipt' value='<?php echo ($object["goodsThums"]); ?>'/>
             	   <div id="goodImgPicker" style='margin-left:0px;margin-top:5px;height:30px;overflow:hidden;width:100px;'>上传商品图片</div>
             	   <div>图片大小:150 x 150 (px)，格式为 gif, jpg, jpeg, png</div>
	             </td>
	           </tr>
	           <tr>
	             <th width='120'>商品名称<font color='red'>*</font>：</th>
	             <td><input type='text' id='goodsName' name='goodsName' class="wst-ipt wstipt" value='<?php echo ($object["goodsName"]); ?>' maxLength='' placeholder="未填写"/></td>
	           </tr>
	            <tr>
	             <th width='120'>市场价格<font color='red'>*</font>：</th>
	             <td>
	             	<input type='text' id='marketPrice' name='marketPrice' class="wstipt wst-ipt" value='<?php echo ($object["marketPrice"]); ?>' onkeypress="return WST.isNumberdoteKey(event)" onkeyup="javascript:WST.isChinese(this,1)" maxLength='10'/>
	             </td>
	           </tr>
	            <tr>
	             <th width='120'>商户价格<font color='red'>*</font>：</th>
	             <td>
	             	<?php if($object["recommPrice"] > 0): ?><input type='text' id='shopPrice' name='shopPrice' disabled="disabled" class="wstipt wst-ipt" value='<?php echo ($object["recommPrice"]); ?>' onkeypress="return WST.isNumberdoteKey(event)" onkeyup="javascript:WST.isChinese(this,1)" maxLength='10'/>
	             	<?php else: ?>
	             		<input type='text' id='shopPrice' name='shopPrice' class="wstipt wst-ipt" value='<?php echo ($object["shopPrice"]); ?>' onkeypress="return WST.isNumberdoteKey(event)" onkeyup="javascript:WST.isChinese(this,1)" maxLength='10'/><?php endif; ?>
	             </td>
	           </tr>
				<tr>
					<th width='120'>分销分成百分比<font color='red'>*</font>：</th>
					<td>
						<input type='text' id='goodsRate' name='goodsRate' class="wstipt wst-ipt" value='<?php echo ($object["goodsRate"]); ?>' maxLength='5' placeholder="如10%，此处填写10"/>
					%</td>
				</tr>
	            <tr>
	             <th width='120'>商品库存<font color='red'>*</font>：</th>
	             <td><input type='text' id='goodsStock' name='goodsStock' class="wstipt wst-ipt" value='<?php echo ($object["goodsStock"]); ?>' onkeypress="return WST.isNumberKey(event)" onkeyup="javascript:WST.isChinese(this,1)" maxLength='25' <?php if(count($object['priceAttrs']) > 0 ): ?>disabled<?php endif; ?> /></td>
	           </tr>
				<tr>
					<th width='120'>最低库存预警值<font color='red'>*</font>：</th>
					<td><input type='text' id='warnStock' name='warnStock' class="wstipt wst-ipt" value='<?php echo ($object["warnStock"]); ?>' onkeypress="return WST.isNumberKey(event)" onkeyup="javascript:WST.isChinese(this,1)" maxLength='25' <?php if(count($object['priceAttrs']) > 0 ): ?>disabled<?php endif; ?> /></td>
				</tr>
	           <!--<tr>-->
	             <!--<th width='120'>商品信息：</th>-->
	             <!--<td colspan='3'>-->
	             <!--<textarea rows="2" style="width:788px" id='goodsSpec' class='wstipt' name='goodsSpec'><?php echo ($object["goodsSpec"]); ?></textarea>-->
	             <!--</td>-->
	           <!--</tr>-->
				<tr>
					<th width='120'>商品所属活动<font color='red'>*</font>：</th>
					<td colspan='3'>
						<label>
							<input type='radio' onclick="javascript:show(0);" id='isComm' name='isActive' class='wstipt' checked value='0'/>普通商品
						</label>
						<label>
							<input type='radio' onclick="javascript:show(1);" id='isMiaosha' name='isActive' class='wstipt' <?php if($object['isMiaosha'] == 1 ): ?>checked<?php endif; ?> value='1'/>限时特惠
						</label>
						<label>
							<?php if($isSelf == 1): ?><input type='radio' onclick="javascript:show(2);" id='isScore' name='isActive' class='wstipt' <?php if($object['isScore'] ==1 ): ?>checked<?php endif; ?> value='2'/>积分换购
							<?php else: endif; ?>
						</label>
						<label>
							<?php if($isSelf == 1): ?><input type='radio' onclick="javascript:show(4);" id='isRebate' name='isActive' class='wstipt' <?php if($object['isRebate'] ==1 ): ?>checked<?php endif; ?> value='4'/>返利商品
								<?php else: endif; ?>
						</label>
						<!--<label>
							<input type='radio' onclick="javascript:show(3);" id='ispintuan' name='isActive' class='wstipt' <?php if($object['ispintuan'] ==1 ): ?>checked<?php endif; ?> value='3'/>拼团商品
						</label>-->
					</td>
				</tr>
				<tr id="mstime" style="display:;">
					<th width='120'><font color='red'>限时特惠时间段<font color='red'>*</font>：</th>
					<td colspan='3'>
						<label>
							<input type='radio' id='is12' name='msTime' class='wstipt' <?php if($object['ismiaoshatime'] ==12 ): ?>checked<?php endif; ?> value='12'/>12:00
						</label>
						<label>
							<input type='radio' id='is16' name='msTime' class='wstipt' <?php if($object['ismiaoshatime'] ==16 ): ?>checked<?php endif; ?> value=16'/>16:00
						</label>
						<label>
							<input type='radio' id='is20' name='msTime' class='wstipt' <?php if($object['ismiaoshatime'] ==20 ): ?>checked<?php endif; ?> value='20'/>20:00
						</label>
					</td>
				</tr>

				<tr id="changeScore" style="display:;">
					<th width='120'><font color='red'>兑换所需积分<font color='red'>*</font>：</th>
					<td colspan='3'>
						<label>
							<input type='text' id='exchangeScore' name='exchangeScore' class='wstipt' value='<?php echo ($object["exchangeScore"]); ?>'/>（注：积分兑换商品，只能用积分兑换，商品价格项不起做用！）
						</label>
					</td>
				</tr>

				<tr id="pintuanrs" style="display:;">
					<th width='120'><font color='red'>开团人数<font color='red'>*</font>：</th>
					<td colspan='3'>
						<label>
							<input type='text' id='ptrs' name='ptrs' class='wstipt' value='<?php echo ($object["ptrs"]); ?>'/>
						</label>
					</td>
				</tr>
				<tr id="pintuansj" style="display:;">
					<th width='120'><font color='red'>拼团结束时间<font color='red'>*</font>：</th>
					<td colspan='3'>
						<label>
							<input class="wstipt" id="ptlastdate" name='ptrs' type="text" value='<?php echo ($object["ptlastdate"]); ?>' placeholder="请选择结束时间" onClick="jeDate({dateCell:'#ptlastdate',isTime:true,format:'YYYY-MM-DD hh:mm:ss'})" style="cursor: pointer;" readonly>
						</label>
					</td>
				</tr>

				<tr id="rebate" style="display:;">
					<th width='120'><font color='red'>返利积分<font color='red'>*</font>：</th>
					<td colspan='3'>
						<label>
							<input type='text' id='rebateScore' name='rebateScore' class='wstipt' value='<?php echo ($object["rebateScore"]); ?>'/>（注：购买后所得积分！）
						</label>
					</td>
				</tr>
				<script src="/Public/plugins/jeDate/jedate.js"></script>
				<script type="text/javascript">
					//jeDate.skin('gray');
					jeDate({
						dateCell:"#indate",//isinitVal:true,
						format:"YYYY-MM",
						isTime:false, //isClear:false,
						minDate:"2015-10-19 00:00:00",
						maxDate:"2016-11-8 00:00:00"
					})
					jeDate({
						dateCell:"#dateinfo",
						format:"YYYY年MM月DD日 hh:mm:ss",
						isinitVal:true,
						isTime:true, //isClear:false,
						minDate:"2014-09-19 00:00:00",
						okfun:function(val){alert(val)}
					})
				</script>
	           <tr>
	             <th width='120'>商品状态<font color='red'>*</font>：</th>
	             <td colspan='3'>
	             <label>
	             <input type='radio' id='isSale1' name='isSale' class='wstipt' <?php if($object['isSale'] ==1 ): ?>checked<?php endif; ?> value='1'/>上架
	             </label>
	             <label>
	             <input type='radio' id='isSale0' name='isSale' class='wstipt' <?php if($object['isSale'] ==0 ): ?>checked<?php endif; ?> value='0'/>下架
	             </label>
	             </td>
	           </tr>
	           <tr>
	             <th width='120'>商品属性：</th>
	             <td colspan='3'>
	             <label>
	             <input type='checkbox' id='isRecomm' name='isRecomm' class='wstipt' <?php if($object['isRecomm'] ==1 ): ?>checked<?php endif; ?> value='1'/>推荐
	             </label>
	             <label>
	             <input type='checkbox' id='isBest' name='isBest' class='wstipt' <?php if($object['isBest'] ==1 ): ?>checked<?php endif; ?> value='1'/>精品
	             </label>
	             <label>
	             <input type='checkbox' id='isNew' name='isNew' class='wstipt' <?php if($object['isNew'] ==1 ): ?>checked<?php endif; ?> value='1'/>新品
	             </label>
	             <label>
	             <input type='checkbox' id='isHot' name='isHot' class='wstipt' <?php if($object['isHot'] ==1 ): ?>checked<?php endif; ?> value='1'/>热销
	             </label>
	             </td>
	           </tr>
	           <tr>
	             <th width='120'>商城分类<font color='red'>*</font>：</th>
	             <td colspan='3'>
	             <select id='goodsCatId1' class='wstipt' onchange='javascript:getCatListForEdit("goodsCatId2",this.value,0)'>
	                <option value=''>请选择</option>
	                <?php if(is_array($goodsCatsList)): $i = 0; $__LIST__ = $goodsCatsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo['catId']); ?>' <?php if($object['goodsCatId1'] == $vo['catId'] ): ?>selected<?php endif; ?>><?php echo ($vo['catName']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	             </select>
	             <select id='goodsCatId2' class='wstipt' onchange='javascript:getCatListForEdit("goodsCatId3",this.value,1);'>
	                <option value=''>请选择</option>
	             </select>
	             <select id='goodsCatId3' class='wstipt'>
	                <option value=''>请选择</option>
	             </select>
	             </td>
	           </tr>
	           <tr>
	             <th width='120'>本店分类<font color='red'>*</font>：</th>
	             <td colspan='3'>
	             <select id='shopCatId1' class='wstipt' onchange='javascript:getShopCatListForEdit(this.value,"<?php echo ($object[shopCatId2]); ?>","shopCatId2")'>
	                <option value='0'>请选择</option>
	                <?php if(is_array($shopCatsList)): $i = 0; $__LIST__ = $shopCatsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo['catId']); ?>' <?php if($object['shopCatId1'] == $vo['catId'] ): ?>selected<?php endif; ?>><?php echo ($vo['catName']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	             </select>
	             <select id='shopCatId2' class='wstipt'>
	                <option value='0'>请选择</option>
	             </select>
	             </td>
	           </tr>
	           <tr>
	             <th width='120' align='right'>品牌：</th>
	             <td>
	             <select id='brandId' class='wstipt' dataVal='<?php echo ($object["brandId"]); ?>'>
	                <option value='0'>请选择</option>
	             </select>
	             </td>
	           </tr>
	           <tr>
	             <th width='120'>商品描述<font color='red'>*</font>：</th>
	             <td colspan='3'>
	             <textarea rows="2" cols="60" id='goodsDesc' class='wstipt' name='goodsDesc'><?php echo ($object["goodsDesc"]); ?></textarea>
	             </td>
	           </tr>
	           <tr>
	             <td colspan='3' style='padding-left:320px;'>
	                 <button class='wst-btn-query' type="submit">保&nbsp;存</button>
	                 <?php if($umark !='toEdit' ): ?><button class='wst-btn-query' type="button" onclick='javascript:location.href="<?php echo U('Home/Goods/'.$umark);?>"'>返&nbsp;回</button><?php endif; ?>
	             </td>
	           </tr>
	        </table>
	        </form>
	      </div>
	     
	      <div class='wst-tab-item'>
	      商品类型：<select id='attrCatId' class='wstipt' onchange='javascript:getAttrList(this.value)'>
	         <option value='0'>请选择</option>
	         <?php if(is_array($attributeCatsCatsList)): $i = 0; $__LIST__ = $attributeCatsCatsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo["catId"]); ?>' <?php if($object['attrCatId'] == $vo['catId'] ): ?>selected<?php endif; ?>><?php echo ($vo["catName"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	      </select>
	      <div>
	        <fieldset id='priceContainer' class='wst-goods-fieldset' <?php if(count($object['priceAttrs']) > 0): ?>style='display:block'<?php endif; ?>>
			    <legend>价格类型</legend>
			    <input type='hidden' class="hiddenPriceAttr" dataId='<?php echo ($object["priceAttrId"]); ?>' dataNo="<?php echo (count($object['priceAttrs'])); ?>" value='<?php echo ($object["priceAttrName"]); ?>'/>
			    <table class="wst-form wst-goods-price-table">
	             <thead><tr><th>属性</th><th>规格</th><th>价格</th><th>推荐</th><th>库存</th><th>操作</th></tr></thead>
	             <tbody id="priceConent">
	             <?php if(is_array($object['priceAttrs'])): $i = 0; $__LIST__ = $object['priceAttrs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr id='attr_<?php echo ($i); ?>'>
		              <td style="text-align:right"><?php echo ($vo['attrName']); ?>：</td>
		              <td><input type="text" id="price_name_<?php echo ($vo['attrId']); ?>_<?php echo ($i); ?>" value="<?php echo ($vo['attrVal']); ?>"/></td>
		              <td><input type="text" id="price_price_<?php echo ($vo['attrId']); ?>_<?php echo ($i); ?>" value="<?php echo ($vo['attrPrice']); ?>" onblur="checkAttPrice(<?php echo ($vo['attrId']); ?>,<?php echo ($i); ?>);" onkeypress="return WST.isNumberdoteKey(event)" onkeyup="javascript:WST.isChinese(this,1)" maxLength="10"/></td>
		              <td><input type="radio" id="price_isRecomm_<?php echo ($vo['attrId']); ?>_<?php echo ($i); ?>" name="price_isRecomm" onclick="checkAttPrice(<?php echo ($vo['attrId']); ?>,<?php echo ($i); ?>);" <?php if($vo['isRecomm'] == 1): ?>checked<?php endif; ?>/></td>
		              <td><input type="text" id="price_stock_<?php echo ($vo['attrId']); ?>_<?php echo ($i); ?>" onblur="getTstock();" value="<?php echo ($vo['attrStock']); ?>" onblur="javascript:statGoodsStaock()" onkeypress="return WST.isNumberdoteKey(event)" onkeyup="javascript:WST.isChinese(this,1)" maxLength="10"/></td>
		              <td>
		              <?php if($i == 1): ?><a title="新增" class="add btn" href="javascript:addPriceAttr()"></a>
		              <?php else: ?>
		              <a title="删除" class="del btn" href="javascript:delPriceAttr(<?php echo ($i); ?>)"></a><?php endif; ?>
		              </td>
		           </tr><?php endforeach; endif; else: echo "" ;endif; ?>
	             </tbody>
	            </table>
			</fieldset>
			<fieldset id='attrContainer' class='wst-goods-fieldset' <?php if(count($object['attrs']) > 0): ?>style='display:block'<?php endif; ?>>
			    <legend>属性类型</legend>
			    <table class="wst-form" style='width:100%'>
	              <tbody id='attrConent'>
	              <?php if(is_array($object['attrs'])): $i = 0; $__LIST__ = $object['attrs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
		              <td style="width:80px;text-align:right" nowrap><?php echo ($vo['attrName']); ?>：</td>
		              <td>
		              <?php if($vo['attrType']==0){ ?>
		              <input type="text" style='width:70%;' class="attrList" id="attr_name_<?php echo ($vo['attrId']); ?>_<?php echo ($i); ?>" value="<?php echo ($vo['attrVal']); ?>" dataId="<?php echo ($vo['attrId']); ?>"/>
		              <?php }else if($vo['attrType']==2){ ?>
		              <select class="attrList" id="attr_name_<?php echo ($vo['attrId']); ?>_<?php echo ($i); ?>" dataId="<?php echo ($vo['attrId']); ?>">
		              <?php if(is_array($vo['opts']['txt'])): $i = 0; $__LIST__ = $vo['opts']['txt'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$attrvo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($attrvo); ?>' <?php if($attrvo == $vo['attrVal']): ?>selected<?php endif; ?> ><?php echo ($attrvo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		              </select>
		              <?php }else if($vo['attrType']==1){ ?>
		              <input type='hidden' class="attrList" dataId='<?php echo ($vo['attrId']); ?>' dataType="1"/>
		              <?php if(is_array($vo['opts']['txt'])): $i = 0; $__LIST__ = $vo['opts']['txt'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$attrvo): $mod = ($i % 2 );++$i;?><label><input type='checkbox' name="attrTxtChk_<?php echo ($vo['attrId']); ?>" value="<?php echo ($attrvo); ?>" <?php if($vo['opts']['val'][$attrvo] == 1): ?>checked<?php endif; ?>/><?php echo ($attrvo); ?></label>&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
		              <?php } ?>
		              </td>
		             </tr><?php endforeach; endif; else: echo "" ;endif; ?>
	              </tbody>
	            </table>
			</fieldset>
			<div style='width:100%;text-align:center;'>
			<button class='wst-btn-query' type="button" onclick='javascript:$("#myform").submit()'>保&nbsp;存</button>
	        <?php if($umark !='toEdit' ): ?><button class='wst-btn-query' type="button" onclick='javascript:location.href="/index.php/Home/Goods/<?php echo ($umark); ?>"'>返&nbsp;回</button><?php endif; ?>
			</div>
	      </div>
	      </div>
	      
	      <!-- 相册 -->
	      <div class='wst-tab-item' style='display:none'>
	      <!-- 
	       <div><input type='text' id='galleryImgUpload'/></div>
	        -->
	       <div id='galleryImgs' class='wst-gallery-imgs'>
                  <div id="tt"></div>
                  <?php if(count($object['gallery']) == 0): ?><div id="wrapper">
                           <div id="container">
            <!--头部，相册选择和格式选择-->
			                 <div style="border-bottom:1px solid #dadada;padding:10px; ">
			                  		图片大小:800 x 800 (px)(格式为 gif, jpg, jpeg, png)
			           		 </div>
                              <div id="uploader">
                               <div class="queueList">
                                   <div id="dndArea" class="placeholder">
                                      <div id="filePicker"></div>
                                      </div>
                                   <ul class="filelist"></ul>
                               </div>
                             <div class="statusBar" style="display:none">
                               <div class="progress">
                                    <span class="text">0%</span>
                                    <span class="percentage"></span>
                               </div>
                                    <div class="info"></div>
                               <div class="btns">
                                 <div id="filePicker2" class="webuploader-containe webuploader-container"></div><div class="uploadBtn state-finish">开始上传</div>
                               </div>
                            </div>
                         </div>
                      </div>
                   </div>
               <?php else: ?>
               	<div id="wrapper">
                       <div id="container">
                          <div id="uploader">
                             <div class="queueList">
                                 <div id="dndArea" class="placeholder element-invisible">
                                    <div id="filePicker" class="webuploader-container"></div>
                                    </div>
                                 <ul class="filelist">
                                 	<?php if(is_array($object['gallery'])): $i = 0; $__LIST__ = $object['gallery'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li style="border: 1px solid rgb(59, 114, 165)" order="100" onmouseover="imglimouseover(this)" onmouseout="imglimouseout(this)">
	                                 		<input type="hidden" class="gallery-img" iv="<?php echo ($vo["goodsThumbs"]); ?>" v="<?php echo ($vo["goodsImg"]); ?>" />
	                                 		<img width="152" height="152" class='lazyImg' data-original="/<?php echo ($vo["goodsThumbs"]); ?>"><span class="setdef" style="display:none">默认</span><span class="setdel" onclick="imglidel(this)" style="display:none">删除</span>
                                 		</li><?php endforeach; endif; else: echo "" ;endif; ?>
                                 </ul>
                            </div>
                            <div class="statusBar" style="">
                               <div class="progress">
                                    <span class="text"></span>
                                    <span class="percentage"></span>
                               </div>
                               <div class="info"></div>
                               <div class="btns">
                                  <div id="filePicker2" class="webuploader-containe webuploader-container"></div>
                                  <div class="uploadBtn state-finish">开始上传</div>
                               </div>
                            </div>
                        </div>
                    </div>
                 </div><?php endif; ?>
	       </div>
	       <div style='clear:both;'></div>
	      </div>
	      
	      <!-- 优惠套餐 -->
	      <div class='wst-tab-item' style='display:none'>
	      	<table class="wst-packages-tab" style="width: 100%;" cellpadding='0' cellspacing='0'>
	     		<tbody id="packages_list">
	     			<?php if(is_array($packages)): $key1 = 0; $__LIST__ = $packages;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key1 % 2 );++$key1;?><tr id='package_<?php echo ($key+1); ?>' packageId="<?php echo ($vo['packageId']); ?>">
						<td onclick='getCurrPackage(this)' package='<?php echo ($key1); ?>' width='35'><span class='package_num'><?php echo ($key1); ?></span></td>
						<td onclick='getCurrPackage(this)' package='<?php echo ($key1); ?>' width='150'><input type='hidden' class='package_name' value="<?php echo ($vo['packageName']); ?>"/><?php echo ($vo['packageName']); ?></td>
						<td onclick='getCurrPackage(this)' package='<?php echo ($key1); ?>'><input type='hidden' class='package_goodsIds' value="<?php echo ($vo['goodsIds']); ?>"/>
							<?php if(is_array($vo['glist'])): $i = 0; $__LIST__ = $vo['glist'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i; echo ($vo2['goodsName']); ?>【差价：<?php echo ($vo2['diffPrice']); ?>】
								<?php if($key < count($vo['glist'])-1): ?><span style='color:red'>|&nbsp;</span><?php endif; endforeach; endif; else: echo "" ;endif; ?>
						</td>
						<td width='60' class='wst-align-c'><input type='hidden' class='package_goodsDiffPrices' value="<?php echo ($vo['diffPrices']); ?>" /><span onclick='delPackage(this)' package='<?php echo ($key1); ?>'>删除</span></td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
	     		</tbody>
	     	</table>
	     	<table class="wst-packages-tbox">
	     		<tbody>
	     			<tr>
	     				<td colspan="3">
	     					<select class="wst-pk-catsel" onchange="getGoodsByCat(this.value)">
				                <option value='-1'>请选择</option>
				                <option value='0'>所有商品分类</option>
				                <?php if(is_array($pkShopCats)): $i = 0; $__LIST__ = $pkShopCats;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo['catId']); ?>">&nbsp;&nbsp;&nbsp;┆┄ <?php echo ($vo['catName']); ?></option>
					                <?php if(is_array($vo['child'])): $i = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo2['catId']); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;┆┄ <?php echo ($vo2['catName']); ?></option><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
				             </select>
	     				</td>
	     			</tr>
	     			<tr>
	     				<td colspan="3">
	     					<input id="packageId" type="hidden" />
	     					<input id="packageName" type="text" class="packageName" maxlength="10" placeholder="套餐名称"/>
	     				</td>
	     			</tr>
	     			<tr>
	     				<td class="wst-col-fst">
	     					<select id="pk_l_goods" size="15" class="sel" onclick="selGoods('pk_l_goods')" ondblclick="optTo('pk_l_goods','pk_r_goods');">
	     						
	     					</select>
	     				</td>
	     				<td class="wst-col-snd">
	     					<div class="price">门店价：<span id="show_price">~</span></div>
	     					<div>
	     						套餐差价：<input id="diffPrice" type="text" style="width:35px;" placeholder="套餐差价（元）" value="0" onkeypress="return WST.isNumberdoteKey(event)" onkeyup="javascript:WST.isChinese(this,1)" maxLength='10'/>
	     					</div>
	     					<div>
	     						<button class="bnt" onclick="optTo('pk_l_goods','pk_r_goods');">==></button>
	     					</div>
	     					<div>
	     						<button class="bnt" onclick="optTo('pk_r_goods','pk_l_goods');"><==</button>
	     					</div>
	     				</td>
	     				<td class="wst-col-thd">
	     					<select id="pk_r_goods" size="15" class="sel" onclick="selGoods('pk_r_goods')" ondblclick="optTo('pk_r_goods','pk_l_goods');">
	     						
	     					</select>
	     				</td>
	     			</tr>
	     			<tr class='wst-align-c'>
	     				<td colspan="3">
	     					<div style="line-height: 50px;"><button class="sbnt wst-btn-query" onclick="editGoodsPackages();">添&nbsp;&nbsp;加</button></div>
	     				</td>
	     			</tr>
	     		</tbody>
	     	</table>
	      </div>
       </div>
       </div>
       
       </div>
       <div style='clear:both;'></div>
       </div>
    <script>
		var mstime = document.getElementById('mstime');
		var isScore = document.getElementById('isScore');
		var isMiaosha = document.getElementById('isMiaosha');
		var isComm = document.getElementById('isComm');
		var changeScore = document.getElementById('changeScore');
		var ispintuan = document.getElementById('ispintuan');
		var pintuanrs = document.getElementById('pintuanrs');
		var pintuansj = document.getElementById('pintuansj');
		mstime.style.display = "none"; changeScore.style.display = "none"; pintuanrs.style.display = "none"; pintuansj.style.display = "none";
		function show(s){
			if(s == 0){
				document.getElementById('mstime').style.display = "none"; document.getElementById('changeScore').style.display = "none";
				document.getElementById('pintuanrs').style.display = "none"; document.getElementById('pintuansj').style.display = "none";
                document.getElementById('rebateScore').style.display = "none";
			}
			if(s == 1){
				document.getElementById('mstime').style.display = ""; document.getElementById('changeScore').style.display = "none";
				document.getElementById('pintuanrs').style.display = "none"; document.getElementById('pintuansj').style.display = "none";
                document.getElementById('rebateScore').style.display = "none";
			}
			if(s == 2){
				document.getElementById('mstime').style.display = "none"; document.getElementById('changeScore').style.display = "";
				document.getElementById('pintuanrs').style.display = "none"; document.getElementById('pintuansj').style.display = "none";
                document.getElementById('rebateScore').style.display = "none";
			}
			if(s == 3){
				document.getElementById('mstime').style.display = "none"; document.getElementById('changeScore').style.display = "none";
				document.getElementById('pintuanrs').style.display = ""; document.getElementById('pintuansj').style.display = "";
                document.getElementById('rebateScore').style.display = "none";
			}
            if(s == 4){
                document.getElementById('mstime').style.display = "none"; document.getElementById('changeScore').style.display = "none";
                document.getElementById('pintuanrs').style.display = "none"; document.getElementById('pintuansj').style.display = "none";
                document.getElementById('rebateScore').style.display = "";
            }
		}
		function loadMsTimeAndExchangeScore(){
			if(document.getElementById('isMiaosha').checked){
				show(1);
			}
			if(document.getElementById('isScore').checked){
				show(2);
			}
			if(document.getElementById('ispintuan').checked){
				show(3);
			}
		}
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