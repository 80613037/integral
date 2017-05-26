$(function(){
	//限时秒杀详情页面的购物车加减效果
	//加的效果
	$(".add").click(function () {
		var n = $(this).prev().val();
		var num = parseInt(n) + 1;
		if (num == 0) { return; }
		if (num > 1) { 
			$("span.reduce").addClass("on");
		}else{
			$("span.reduce").removeClass("on");
		}		
		$(this).prev().val(num);
	});
	//减的效果
	$(".reduce").click(function () {
		var n = $(this).next().val();
		var num = parseInt(n) - 1;
		if (num == 0) { return }
		if (num > 1) { 
			$("span.reduce").addClass("on");
		}else{
			$("span.reduce").removeClass("on");			
		}
		$(this).next().val(num);
	});	
	//限时秒杀详情页面的商品详情和商品评价的切换 tab选项卡
	$(".dt-hdtab ul li").click(function(){
		var n=$(this).index();
		$(".dt-hdtab ul li").eq(n).addClass("on").siblings().removeClass("on");
		$(".dt-bdtab .dt-bdtab-inf").eq(n).addClass("on").siblings().removeClass("on");
	});
	//商家店铺的切换 tab选项卡
	$(".shop-tab-hd-ul li").click(function(){
		var n=$(this).index();
		$(".shop-tab-hd-ul li").eq(n).addClass("on").siblings().removeClass("on");
		$(".shop-pro-wrap").eq(n).addClass("on").siblings().removeClass("on");
	});	
	//兑换记录的切换 tab选项卡
	$(".duihuan-hd ul li").click(function(){
		var n=$(this).index();
		$(".duihuan-hd ul li").eq(n).addClass("on").siblings().removeClass("on");
		$(".lands-pro-wrap").eq(n).addClass("on").siblings().removeClass("on");
	});	
	//我的团的切换 tab选项卡
	$(".tab-switch-hd ul li").click(function(){
		var n=$(this).index();
		$(".tab-switch-hd ul li").eq(n).addClass("on").siblings().removeClass("on");
		$(".tab-switch-bd").eq(n).addClass("on").siblings().removeClass("on");
	});		
	//新建地址设为默认地址
	$(".default-address").click(function(){
		$(this).find(".sel-icon").toggleClass("sel");
	});
	//弹框
	$(".wx-pay").click(function(){
		$(".tc-bd").show();
	});
	$(".tishi-btn").click(function(){
		$(".tc-bd").show();
	});	
	$(".quxiao-btn").click(function(){
		$(".tc-bd").hide();
	});	
	//拼团详情去除最后一个元素的边框
	$(".goto-tour:last").css("border","none");
	$(".single-row:last").css("border","none");
	$(".single-row.single-row1:last").css("border","none");
	//参团提示弹框
	$(".prompt-wrap").height($(document).height());
	//众筹详情页面
	$(".single-rad").click(function(){
		$(this).toggleClass("sel");
	});
	$(".zk-btn").click(function(){
		$(this).toggleClass("on");
		$(this).parent().parent().next(".view-panel").toggle();
	});	
	//商城首页--商品分类--搜索
	$(".inner-ser-bar").click(function(){
		$(this).animate({"width":"7rem"},500);
		$(this).css({"text-align":"left"});
		$(this).find("span.zs").css("display","none");
		$(this).next(".btn-ser").css("display","block");
		$(this).find("input.ser-input").css("display","inline-block");
		$(this).find("i").css("float","left");
	});
	//********************************商城首页--商品分类
	//自适应高度
	var cah=$(document).height();
	/*$(".cate-menu").height(cah-1.333+"rem");
	$(".inner-cate-con").height(cah-2.533+"rem");*/
	//点击菜单效果
	$(".cate-menu ul li").click(function(){
		$(this).addClass("on").siblings().removeClass("on");
	});
	//排序
	$(".sort-bar span").click(function(){
		$(this).toggleClass("on");		
	});
	//************************点击弹出半透明框
	$(".eject-btn").click(function(){
		$(".eje-black-wrap").css("display","block");
	});
});

//秒杀商品评价加载更多
var i,
    str='<li class="dtcmc-li">';
    str+='<div class="buyers-inf">';
	str+='<span class="head"><img src="../images/sy_1banner_02.jpg" alt=""></span>';
	str+='<span class="name">您的昵称</span>';
	str+='<span class="time">2016-10-26</span>';
    str+='</div>';
	str+='<div class="remark-inf">收到的时候感觉真心不错，10斤苹果分2个的泡沫箱包装，每箱9个，每个个头差不多大</div>';
	str+='<div class="shopmanreply">';
	str+='<span class="hd">店家回复：</span>';
    str+='<span class="bd">谢谢您的支持！</span>';
	str+='</div>';
	str+='</li>';				
$(".loadmore").click(function(){
	for(i=0;i<3;i++){
		$(".evaluate-wrap").append(str);
	}
});
//加入购物车提示框然后自动关闭
var layer=document.createElement("div");
layer.id="layer";
function func()
{
    var style=
    {
        left:38+"%",
        top:40+"%"
    }
    for(var i in style)
        layer.style[i]=style[i];   
    if(document.getElementById("layer")==null)
    {
        document.body.appendChild(layer);
        setTimeout("document.body.removeChild(layer)",2000)
    }
    $(layer).text("已加入购物车");
}
//去支付已取消
function funcqx()
{
    var style=
    {
        left:38+"%",
        top:40+"%"
    }
    for(var i in style)
        layer.style[i]=style[i];   
    if(document.getElementById("layer")==null)
    {
        document.body.appendChild(layer);
        setTimeout("document.body.removeChild(layer)",2000)
    }
    $(layer).text("已取消");
    $(".tc-bd").hide();
}
//待评价--申请退款
function funcsq()
{
    var style=
    {
        left:38+"%",
        top:40+"%"
    }
    for(var i in style)
        layer.style[i]=style[i];   
    if(document.getElementById("layer")==null)
    {
        document.body.appendChild(layer);
        setTimeout("document.body.removeChild(layer)",2000)
    }
    $(layer).text("提交成功");
    $(".tc-bd").hide();
}
//账户余额--充值
function funczf()
{
    var style=
    {
        left:38+"%",
        top:40+"%"
    }
    for(var i in style)
        layer.style[i]=style[i];   
    if(document.getElementById("layer")==null)
    {
        document.body.appendChild(layer);
        setTimeout("document.body.removeChild(layer)",2000)
    }
    $(layer).text("充值成功");
}
//积分商城-详情-兑换-兑换后
function funcdh()
{
    var style=
    {
        left:38+"%",
        top:40+"%"
    }
    for(var i in style)
        layer.style[i]=style[i];   
    if(document.getElementById("layer")==null)
    {
        document.body.appendChild(layer);
        setTimeout("document.body.removeChild(layer)",2000)
    }
    $(layer).text("兑换成功");
}
//我的订单-待收货-申请退款
function funcsq()
{
    var style=
    {
        left:38+"%",
        top:40+"%"
    }
    for(var i in style)
        layer.style[i]=style[i];   
    if(document.getElementById("layer")==null)
    {
        document.body.appendChild(layer);
        setTimeout("document.body.removeChild(layer)",2000)
    }
    $(layer).text("请先确认收货");
}
//首页限时秒杀倒计时
var intDiff = parseInt(60);//倒计时总秒数量

function timer(intDiff){
	window.setInterval(function(){
	var day=0,
		hour=0,
		minute=0,
		second=0;//时间默认值		
	if(intDiff > 0){
		day = Math.floor(intDiff / (60 * 60 * 24));
		hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
		minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
		second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
	}
	if (minute <= 9) minute = '0' + minute;
	if (second <= 9) second = '0' + second;
	$('#day_show').html(day+"天");
	$('#hour_show').html('<s id="h"></s>'+hour+'');
	$('#minute_show').html('<s></s>'+minute+'');
	$('#second_show').html('<s></s>'+second+'');
	intDiff--;
	}, 1000);
} 

$(function(){
	timer(intDiff);
});	


