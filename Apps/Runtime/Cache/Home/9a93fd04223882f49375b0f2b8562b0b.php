<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
	<title><?php echo ($CONF['mallTitle']); ?></title>
	<!--字体图标-->
	<link rel="stylesheet" href="/Apps/Home/View/mobile/styles/iconfont/iconfont.css">
	<!--公共样式-->
	<link rel="stylesheet" href="/Apps/Home/View/mobile/styles/NormalizeFile.css">
	<link rel="stylesheet" href="/Apps/Home/View/mobile/styles/public.css">
	<!--自定义样式-->
	<link rel="stylesheet" href="/Apps/Home/View/mobile/styles/style.css">
	<!--轮播-->
	<link rel="stylesheet" href="/Apps/Home/View/mobile/styles/trundle.css">
	<!--上拉加载更多-->
	<link rel="stylesheet" href="/Apps/Home/View/mobile/styles/scrollbar.css">
	<!--jquery库有此轮播的需要引用低版本的库-->
	<script src="/Apps/Home/View/mobile/scripts/jquery-1.8.3.min.js"></script>
	<!--rem自适应-->
	<script src="/Apps/Home/View/mobile/scripts/flexible.js"></script>
	<!--需要放在头部，否则倒计时不执行-->
	<script type="text/javascript" src="/Apps/Home/View/mobile/scripts/custom-index.js"></script>
	<!--上拉加载更多-->
	<script type="text/javascript" src="/Apps/Home/View/mobile/scripts/iscroll.js"></script>
	<script type="text/javascript" src="/Apps/Home/View/mobile/scripts/iscrollfresh-sc.js"></script>
</head>
<body style="background: #f4f6f9;">
<!--主体部分begin-->
<div class="wrap pad-bot" id="wrapper">
	<div id="scroller">
		<div id="pullDown">
			<span class="pullDownIcon"></span><span class="pullDownLabel">下拉刷新...</span>
		</div>
		<div id="thelist">
			<!--轮播begin 建议上传图片最佳比例2:1-->
			<div class="main_visual">
				<div class="flicking_con">
					<?php $_result=getAds(1);if(is_array($_result)): $k = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><a href="#"><?php echo ($k); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
				</div>
				<div class="main_image">
					<ul>
						<?php $_result=getAds(1);if(is_array($_result)): $k = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li><span class="img_2"><a href="<?php echo ($vo['adURL']); ?>"><img src="/<?php echo ($vo['adFile']); ?>"  title="<?php echo ($vo['adName']); ?>"></a></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
					<a href="javascript:;" id="btn_prev"></a>
					<a href="javascript:;" id="btn_next"></a>
				</div>
			</div>
			<!--轮播end-->
			<!--商城头部信息begin-->
			<div class="sc-dtinf-panel">
				<ul class="box-bar">
					<li class="box-bar-list">
						<div class="hd">账户余额</div>
						<div class="bd">￥<?php echo ((isset($userinfo["userMoney"]) && ($userinfo["userMoney"] !== ""))?($userinfo["userMoney"]):'0.00'); ?></div>
					</li>
					<li class="box-bar-list">
						<div class="hd">积分余额</div>
						<div class="bd"><?php echo ((isset($userinfo["userScore"]) && ($userinfo["userScore"] !== ""))?($userinfo["userScore"]):'0'); ?></div>
					</li>
					<li class="box-bar-list">
						<a href="<?php echo U('Home/Goods/getGoodsList');?>">
						<div class="hd">商品分类</div>
						<div class="bd">特品  凡品</div></a>
					</li>
					<li class="box-bar-list">
						<a href="/index.php?m=Home&c=Index&a=jfmall" class="gt-change">去兑换 ></a>
					</li>
				</ul>
			</div>
			<!--商城头部信息end-->
			<div class="seckill-hd  groups-hd xsth-hd" onclick="window.location.href='<?php echo U('Home/Index/msIndex');?>'"></div>
			<div class="pro-wrap">
				<!--商品循环部分(建议图片200*190)begin-->
				<?php if(is_array($goodsMs)): $k = 0; $__LIST__ = $goodsMs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><div class="pro-list" onClick="window.location.href='<?php echo U('Home/Goods/getGoodsDetails', array(goodsId=>$vo['goodsId']));?>'">
						<div class="pro-img-pannel table-wrap">
							<img src="<?php echo ($vo["goodsThums"]); ?>" width="200" height="190">
						</div>
						<div class="img-title-bar">
							<?php echo ($vo["goodsName"]); ?>
						</div>
						<div class="img-price">
							<span class="discount-price">￥<?php echo ($vo["shopPrice"]); ?></span>
							<del class="cost-price">￥<?php echo ($vo["marketPrice"]); ?></del>
						</div>
					</div><?php endforeach; endif; else: echo "" ;endif; ?>
				<!--商品循环部分end-->
			</div>

			<!--推荐标题begin-->
			<div class="seckill-hd  groups-hd sptj-hd"></div>
			<!--推荐标题end-->
			<!--推荐商品展示begin-->
			<div class="pro-wrap">
				<!--商品循环部分(建议图片200*190)begin-->
				<?php if(is_array($goodsListTj)): $i = 0; $__LIST__ = $goodsListTj;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gs): $mod = ($i % 2 );++$i;?><div class="pro-list">
				<a href="<?php echo U('Home/Goods/getGoodsDetails/',array('goodsId'=>$gs['goodsId']));?>">
					<div class="pro-img-pannel table-wrap">
						<img src="/<?php echo ($gs["goodsThums"]); ?>" width="200" height="190">
					</div>
					<div class="img-title-bar">
						<?php echo ($gs["goodsName"]); ?>
					</div>
					<div class="img-price">
						<span class="discount-price">￥<?php echo ($gs["shopPrice"]); ?></span>
						<span class="group-num">已售：<?php echo ($gs["saleCount"]); ?></span>
					</div>
				</a>
				</div><?php endforeach; endif; else: echo "" ;endif; ?>
				<!--商品循环部分end-->
			</div>
			<!--推荐商品展示end-->
		</div>
		<div id="pullUp">
			<span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多...</span>
		</div>
	</div>
</div>
<!--主体部分end-->
<!--固定的底部begin-->
<footer class="footer">
    <ul>

        <?php if($s == 'mall'): ?><li class="mall on"><?php else: ?><li class="mall"><?php endif; ?>
            <a href="<?php echo U('Home/Index/mall/');?>">
                <span></span>
                <p>商城</p>
            </a>
        </li>
        <?php if($s == 'cart'): ?><li class="cart on"><?php else: ?><li class="cart"><?php endif; ?>
            <a href="<?php echo U('Home/Cart/getCartInfo');?>">
                <span></span>
                <p>购物车</p>
            </a>
        </li>
        <?php if($s == 'user'): ?><li class="user on"><?php else: ?><li class="user"><?php endif; ?>
            <a href="<?php echo U('Home/Users/index');?>">
                <span></span>
                <p>我的</p>
            </a>
        </li>
    </ul>
</footer>
<!--固定的底部end-->
</body>

<!--轮播begin-->
<script type="text/javascript" src="/Apps/Home/View/mobile/scripts/trundle.js"></script>
<script type="text/javascript" src="/Apps/Home/View/mobile/scripts/jquery.event.drag-1.5.min.js"></script>
<script type="text/javascript" src="/Apps/Home/View/mobile/scripts/jquery.touchSlider.js"></script>
<script src="/Apps/Home/View/mobile/js/common.js"></script>
<script src="/Public/js/common.js"></script>
<script src="/Public/plugins/layer/layer.min.js"></script>
<!--轮播end-->
<script type="text/javascript">
	mobile = (/mmp|symbian|smartphone|midp|wap|phone|xoom|iphone|ipad|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));
	if (!mobile) {
//		alert('请使用手机打开本商城！');
		WST.msg("请使用手机打开本商城", {icon: 5});
		setTimeout('window.history.go(-1);',2000);
	}
</script>
</html>