
function toEditAddress(){
	var addressId = $("#consigneeId").val();
	$("#consignee1").hide();
	$("#consignee2").show();
	changeAddress(addressId);
}
function changeAddress(addressId){
	$("#consigneeId").val(addressId);
	if(addressId>=1){
		loadAddress(addressId);
	}else{
		$("#consignee_add_userName").val("");
		$("#consignee_add_address").val("");
		$("#consignee_add_userPhone").val("");
		$("#consignee_add_userTel").val("");
		$("#consignee_add_zipCode").val("");

		$("#consignee_add_provinceId").val(0);
		var html = new Array();
		$("#consignee_add_countyId").val(0);
		var html = new Array();
			html.push("<option value='0'>请选择</option>");
		$("#consignee_add_cityId").html(html.join(""));
		$("#consignee_add_countyId").html(html.join(""));

		$("#consignee_add_communityId").html(html.join(""));
	}
}

function editAddress(addressId){
	$("#seladdress_"+addressId).click();
}

function loadCommunitys(districtId,va){
	//var districtId = obj.value;
	if(districtId<1){
		var html = new Array();
		$("#consignee_add_communityId").empty();
		html.push("<option value='0'>请选择</option>");
		$("#consignee_add_communityId").html(html.join(""));
		return;
	}

	jQuery.post(Think.U('Home/Communitys/getByDistrict') ,{areaId3:districtId},function(rsp){
		var json = WST.toJson(rsp);
		var html = new Array();
		$("#consignee_add_communityId").empty();
		html.push("<option value='0'>请选择</option>");
		if(json.list && json.list.length>0){
			for(var i=0;i<json.list.length;i++){
				html.push("<option value='"+json.list[i].communityId+"' "+(va==json.list[i].communityId?"selected":"")+">"+json.list[i].communityName+"</option>");
			}
		}
		$("#consignee_add_communityId").html(html.join(""));
	});
}

function loadAddress(addressId){
	$("#address_form").show();
	jQuery.post(Think.U('Home/UserAddress/getUserAddress') ,{addressId:addressId},function(rsp) {
		var rs = WST.toJson(rsp);
		if(rs.status>0){
			var addressInfo = rs.address;
			$("#consignee_add_cityName").val(addressInfo.areaName);
			$("#consignee_add_userName").val(addressInfo.userName);
			$("#consignee_add_address").val(addressInfo.address);
			$("#consignee_add_userPhone").val(addressInfo.userPhone?addressInfo.userPhone:"");
			$("#consignee_add_userTel").val(addressInfo.userTel);
			if(addressInfo.isDefault==1){
			    $("#consignee_add_isDefault_1")[0].checked = true;
			}else{
				$("#consignee_add_isDefault_0")[0].checked = true;
			}

			$("#consignee_add_provinceId").val(addressInfo.areaId1);
			WST.getAreas('consignee_add_cityId',addressInfo.areaId1,addressInfo.areaId2,'consignee_add_cityId,consignee_add_countyId,consignee_add_communityId',function(){
				WST.getAreas('consignee_add_countyId',addressInfo.areaId2,addressInfo.areaId3,'consignee_add_countyId,consignee_add_communityId',function(){
					loadCommunitys(addressInfo.areaId3,addressInfo.communityId);
				});
			});

		}
	});
}
function saveAddress(){
	var seladdress = $('input:radio[name="seladdress"]:checked').val();
	var addressId = $("#consigneeId").val();
	var userName = $("#consignee_add_userName").val();
	var cityId = $("#consignee_add_cityId").val();
	var countyId = $("#consignee_add_countyId").val();
	var communityId = $("#consignee_add_communityId").val();
	var address = $("#consignee_add_address").val();
	var userPhone = $("#consignee_add_userPhone").val();
	var userTel = $("#consignee_add_userTel").val();
    var isDefault = $("#consignee_add_isDefault_1")[0].checked?1:0;
	var params = {};
	params.id = addressId;
	params.userName = jQuery.trim(userName);
	params.areaId2 = cityId;
	params.areaId3 = countyId;
	params.communityId = communityId;
	params.address = jQuery.trim(address);
	params.userPhone = jQuery.trim(userPhone);
	params.userTel = jQuery.trim(userTel);
	params.isDefault = isDefault;
	if(addressId<1 && $("#seladdress_0").attr("checked")==false){
		WST.msg("请选择收货地址", {icon: 5});
		return ;
	}
	if(params.userName==""){
		WST.msg("请输入收货人", {icon: 5});
		return ;
	}
	if(!WST.checkMinLength(params.userName,2)){
		WST.msg("收货人姓名长度必须大于1个汉字", {icon: 5});
		return ;
	}
	if(params.areaId2<1){
		WST.msg("请选择市", {icon: 5});
		return ;
	}
	if(params.areaId3<1){
		WST.msg("请选择区县", {icon: 5});
		return ;
	}
	if(params.communityId<1){
		// WST.msg("请选择社区", {icon: 5});
		// return ;
	}
	if(params.address==""){
		WST.msg("请输入详细地址", {icon: 5});
		return ;
	}
	if(userPhone=="" && userTel==""){
		WST.msg("请输入手机号码或固定电话", {icon: 5});
		return ;
	}
	if(userPhone!="" && !WST.isPhone(params.userPhone)){
		WST.msg("手机号码格式错误", {icon: 5});
		return ;
	}
	if(userTel!="" && !WST.isTel(params.userTel)){
		WST.msg("固定电话格式错误", {icon: 5});
		return ;
	}

	jQuery.post(Think.U('Home/UserAddress/edit') ,params,function(data,textStatus){
		var json = WST.toJson(data);

		if(json.status>0){
			$("#consignee1").show();
			$("#consignee2").hide();
			var addressInfo = new Array();
			addressInfo.push('<div>');
			addressInfo.push('<span style="font-weight: bold;">'+userName+'&nbsp;&nbsp;&nbsp;&nbsp;</span>');
			if(userPhone!=""){
				addressInfo.push(userPhone);
			}else{
				addressInfo.push(userTel);
			}
			addressInfo.push('</div>');
			addressInfo.push('<div>');
			addressInfo.push($("#consignee_add_provinceId").find("option:selected").text());
			addressInfo.push($("#consignee_add_cityName").val());
			addressInfo.push($("#consignee_add_countyId").find("option:selected").text());
			addressInfo.push($("#consignee_add_communityId").find("option:selected").text());
			addressInfo.push(address+"&nbsp;&nbsp;&nbsp;&nbsp;");
			addressInfo.push('</div>');
			$("#showaddinfo").html(addressInfo.join(""));

			if(addressId==0){
				$("#consigneeId").val(json.status);
				var addressInfo = new Array();
				addressInfo.push('<div id="caddress_'+json.status+'">');
				addressInfo.push('<label>');
				addressInfo.push('<input id="seladdress_'+json.status+'" onclick="changeAddress('+json.status+');" name="seladdress" type="radio" checked="checked" value="'+json.status+'"/>');
				addressInfo.push('<span style="font-weight: bold;"  id="radusername_'+json.status+'">'+userName+'&nbsp;&nbsp;&nbsp;&nbsp;</span>');
				addressInfo.push('<span id="radaddress_'+json.status+'">');
				addressInfo.push($("#consignee_add_provinceId").find("option:selected").text());
				addressInfo.push($("#consignee_add_cityName").val());
				addressInfo.push($("#consignee_add_countyId").find("option:selected").text());
				addressInfo.push($("#consignee_add_communityId").find("option:selected").text());
				addressInfo.push(address);
				addressInfo.push("</span>");
				if(userPhone!=""){
					addressInfo.push(userPhone);
				}else{
					addressInfo.push(userTel);
				}

				addressInfo.push('<span class="optionspan" style="padding-left:50px;color: #999999">[<a onclick="javascript:editAddress('+json.status+');">修改</a>]</span>');
				addressInfo.push('<span class="optionspan" style="padding-left:10px;color: #999999">[<a onclick="javascript:delAddress('+json.status+');">删除</a>]</span>');
				addressInfo.push('</label>');
				addressInfo.push('</div>');
				$(addressInfo.join("")).insertAfter("#flagdiv");

			}else{
				$("#radusername_"+addressId).html(params.userName);
				var addressInfo = new Array();
				addressInfo.push("&nbsp;&nbsp;&nbsp;&nbsp;"+$("#consignee_add_cityName").val());
				addressInfo.push($("#consignee_add_countyId").find("option:selected").text());
				addressInfo.push($("#consignee_add_communityId").find("option:selected").text());
				addressInfo.push(params.address);
				$("#radaddress_"+addressId).html(addressInfo.join(""));
			}

		}else{
			WST.msg("收货人信息添加失败", {icon: 5});
		}
	});
}
function addHour(hour){
    var d=new Date();
    d.setHours(d.getHours()+hour);
    var m=d.getMonth()+1;
    var year = d.getFullYear();
    var month = (m>=10?m:'0'+m);

    var day = (d.getDate()>=10)?d.getDate():"0"+d.getDate();
    var h = (d.getHours()>=10)?d.getHours():"0"+d.getHours();
    var min = (d.getMinutes()>=10)?d.getMinutes():"0"+d.getMinutes();
    return (year+'-'+month+'-'+day+" "+h+":"+min+":00");
  }
/**
function delAddress(addressId){
	layer.confirm('您确定删除该地址吗？',{icon: 3, title:'系统提示'}, function(tips){
		var ll = layer.msg('数据处理中，请稍候...', {icon: 16,shade: [0.5, '#B3B3B3']});
		jQuery.post('/index.php?m=Home&c=UserAddress&a=del' ,{id:addressId},function(rsp) {
			layer.close(ll);
	    	layer.close(tips);
			if(rsp){
				location.reload();
				$("#caddress_"+addressId).remove();
				$("#consigneeId").val(0);
				$("#seladdress_0").click();
			}else{
				WST.msg("删除失败", {icon: 5});
			}
		});
	});

}
*/
function submitOrder(){
	var flag = true;
	$(".tst").each(function(){
		if($(this).val()==-1){
			flag = false;
		}
	});
	if(!flag){
		WST.msg("抱歉，您的订单金额未达到该店铺的配送订单起步价，不能提交订单。", {icon: 5});
		return;
	}
	var ll = layer.msg('正在提交订单，请稍候...', {icon: 16,shade: [0.5, '#B3B3B3']});
	jQuery.post(Think.U('Home/Goods/checkGoodsStock') ,{},function(data) {
		var goodsInfo = WST.toJson(data);
		layer.close(ll);

		for(var i=0;i<goodsInfo.length;i++){
			var goods = goodsInfo[i];

			if(goods.isSale<1){
				WST.msg('商品'+goods.goodsName+'已下架，请返回重新选购!', {icon: 5});
				return;
			}else if(goods.goodsStock<=0){
				WST.msg('商品'+goods.goodsName+'库存不足，请返回重新选购!', {icon: 5});
				return;
			}else if(goods.shopAtive==0){
				WST.msg('商铺'+goods.shopName+'在休息中，不能下单!', {icon: 5});
				return;
			}

		}

		var params = {};
		params.consigneeId = $("#consigneeId").val();
		if(!$("#consignee2").is(":hidden")){
			WST.msg("请先保存收货人信息",{icon: 5});
			return;
		}
		if(params.consigneeId<1){
			WST.msg("请填写收货人地址", {icon: 5});
			return ;
		}
		params.invoiceClient = $.trim($("#invoiceClient").val());
		var rdate = $("#requestdate").val();
		var rtime = $("#requesttime").val();
		params.requireTime = rdate+" "+rtime+":00";
		params.payway = $('input:radio[name="payway"]:checked').val();
		params.needreceipt = $('input:radio[name="needreceipt"]:checked').val();
		params.isself = $('input:radio[name="isself"]:checked').val();
		params.remarks = $.trim($("#remarks").val());
		var d1 = params.requireTime;
		d1 = d1.replace(/-/g,"/");
		var date1 = new Date(d1);
		var d2 = addHour(1);
		d2 = d2.replace(/-/g,"/");
		var date2 = new Date(d2);
		params.isScorePay = 0;
		if($("#isScorePay").length>0){
			if($("#isScorePay").prop('checked')){
				params.isScorePay = 1;
			}
		}
		if($("#isGoldPay").length>0){
			if($("#isGoldPay").prop('checked')){
				params.isGoldPay = 1;
			}
		}

		if(params.needreceipt==1 && params.invoiceClient==""){
			WST.msg("请输入抬头", {icon: 5});
			return ;
		}
		if(date1<date2){
			WST.msg("亲，期望送达时间必须设定为下单时间1小时后哦！", {icon: 5});
			return ;
		}
		if(!subCheckArea()){
			WST.msg("您选的商品不在配送区域内！", {icon: 5});
			return ;
		}

		var ll = layer.msg('提交订单，请稍候...', {icon: 16,shade: [0.5, '#B3B3B3']});
		jQuery.post(Think.U('Home/Orders/submitOrder') ,params,function(data) {
			 var json = data;
			 if(json.status==1){
				 if(params.payway==1){
					 location.href=Think.U('Home/Payments/toPay');
				 }else{
					 location.href=Think.U('Home/Orders/orderSuccess');
				 }
			 }else{
				 WST.msg(json.msg, {icon: 5});
			 }
		});
	});
}

function submitOrder_new(payway){
    var consigneeId = $("#consigneeId").val();
    if(consigneeId == undefined){
        layer.msg("请填写收货人地址", {icon: 5});
        return ;
    }
    var flag = true;
    $(".tst").each(function(){
        if($(this).val()==-1){
            flag = false;
        }
    });
    // var ll = layer.msg('正在提交订单，请稍候...', {icon: 16,shade: [0.5, '#B3B3B3']});
        var params = {};
        params.coupid = GetQueryString('coupid');
        params.totalMoney = $('#totalMoney').val();
		params.consigneeId = $('#consigneeId').val();
        params.invoiceClient = $.trim($("#invoiceClient").val());
        var rdate = $("#requestdate").val();
        var rtime = $("#requesttime").val();
        params.requireTime = rdate+" "+rtime+":00";
        // params.payway = $('input:radio[name="payway"]:checked').val();
		params.payway = payway;
        params.needreceipt = $('input:radio[name="needreceipt"]:checked').val();
        params.isself = $('input:radio[name="isself"]:checked').val();
        params.remarks = $.trim($("#remarks").val());
        params.shopId = $("#shopid").val();
        params.goodsId = $("#goodsId").val();
        params.goodsPrice = $("#goodsPrice").val();

        var d1 = params.requireTime;
        d1 = d1.replace(/-/g,"/");
        var date1 = new Date(d1);
        var d2 = addHour(1);
        d2 = d2.replace(/-/g,"/");
        var date2 = new Date(d2);
        params.isScorePay = 0;
        if($("#isScorePay").length>0){
            if($("#isScorePay").prop('checked')){
                params.isScorePay = 1;
            }
        }
        if($("#isGoldPay").length>0){
            if($("#isGoldPay").prop('checked')){
                params.isGoldPay = 1;
            }
        }
        params.leaderid=$("#leaderid").val();
        // params.f=GetQueryString('f');

        // var ll = layer.msg('提交订单，请稍候...', {icon: 16,shade: [0.5, '#B3B3B3']});
        jQuery.post(Think.U('Home/Orders/submitOrder_pt') ,params,function(data) {
			if (data.code == 0) {
				//WST.msg('该商品库存不足，请返回重新选购!', {icon: 5});
				layer.msg('该商品库存不足，请返回重新选购!');
				setTimeout("window.location.href='/index.php?m=Home&c=Index&a=ptIndex'", 2000);
				return;
			}else if (data.code == 1) { // 下单成功, 去支付
				console.info(data.orderId);
				payyue(data.orderId);
				return;




                layer.msg('恭喜，购买成功!');
                setTimeout("window.location.href='/index.php?m=Home&c=Users&a=myPintuan'", 2000);
                return;
			}else {
                layer.msg('购买失败，请稍后重试!');
                setTimeout("window.location.href='/index.php?m=Home&c=Index&a=ptIndex'", 2000);
                return;
            }
        });
}

function payyue(oids){
	layer.confirm('您确定使用余额支付吗？',{icon: 3, title:'系统提示'}, function(tips){
		var ll = layer.msg('数据处理中，请稍候...', {icon: 16,shade: [0.5, '#B3B3B3']});
		jQuery.post(Think.U('Home/Orders/payYue') ,{'oids':oids, 'totalMoney':$('#totalMoney').val(), 'gold':$('#gold').val()},function(data) {
		    layer.close(ll);
			layer.close(tips);

			if(data){
				if(data.code==0){
					// 余额不足
                    WST.msg('您的可用余额不足，请返回重新选购并使用微信支付！!', {icon: 5}, function(){
						location.href= Think.U('Home/Index/ptIndex','o=del&oid='+oids);
                    });
				}else if(data.code==1){
					// 支付成功
					WST.msg(data.msg, {icon: 6});
					jQuery.post(Think.U('Home/Orders/changeOrdertatusAndorderLog') ,{orderids:oids},function(json) {
						if(json==1){
							setTimeout('window.location.href="/index.php?m=Home&c=Users&a=myPintuan";',2000);
						}
					})

				}else{
					// 支付失败
					WST.msg(data.msg, {icon: 5});
					setTimeout('window.location.href="/index.php?m=Home&c=Users&a=index";',2000);
				}
			}else{
				WST.msg("支付失败，请返回重新选购！", {icon: 5});
				setTimeout('window.location.href="/index.php?m=Home&c=Index&a=ptIndex";',2000);
			}
		});
	});
}

function getOrderInfo(orderId){
	window.location = Think.U('Home/orders/getOrderInfo','orderId='+orderId);
}

function getPayUrl(){

	var params = {};
	params.orderId = $.trim($("#orderId").val());
	params.payCode = $.trim($("#payCode").val());
	params.needPay = $.trim($("#needPay").val());
	if(params.payCode==""){
		WST.msg('请先选择支付方式', {icon: 5});
		return;
	}
	jQuery.post(Think.U('Home/Payments/get'+params.payCode+"URL") ,params,function(data) {
		var json = WST.toJson(data);
		if(json.status==1){
			if(params.payCode=="weixin"){
				location.href = json.url;
			}else{
				window.open(json.url);
			}
		}else if(json.status==-2){
			var rlist = json.rlist;
			var garr = new Array();
			for(var i=0;i<rlist.length;i++){
				garr.push(rlist[i].goodsName+rlist[i].goodsAttrName);
				rlist[i].goodsAttrName
			}
			WST.msg('订单中商品【'+garr.join("，")+'】库存不足，不能进行支付。', {icon: 5});

		}else{
			WST.msg('您的订单已支付!', {icon: 5});
			setTimeout(function(){
				window.location = Think.U('Home/orders/queryDeliveryByPage');
			},1500);
		}
	});
}

$(function() {
	$('input:radio[name="needreceipt"]').click(function(){
		if($(this).val()==1){
			$("#invoiceClientdiv").show();
		}else{
			$("#invoiceClientdiv").hide();
		}
	});

	$("#wst-order-details").click(function(){
		$("#wst-orders-box"). toggle(100);
	});


	$(".wst-payCode").click(function(){
		$(".wst-payCode-curr").removeClass().addClass("wst-payCode");
		$(this).removeClass().addClass("wst-payCode-curr");
		$("#payCode").val($(this).attr("data"));
	});

	$("#isScorePay").click(function(){
		var isself = $('input:radio[name="isself"]:checked').val();
		var totalMoney = (isself==1)?$(this).attr("gtotalMoney"):$(this).attr("totalMoney");
		if($("#isScorePay").prop('checked')){

			var scoreMoney = $(this).attr("scoreMoney");
			$("#totalMoney_span").html((totalMoney-scoreMoney).toFixed(2));
		}else{
			$("#totalMoney_span").html(totalMoney);
		}
	});

	$('input:radio[name="isself"]').click(function(){
		$("#isScorePay").attr("disabled",true);
		if($(this).val()==0){//送货上门
			$("#totalMoney_span").html($("#totalMoney").val());
			$("[id^=tst_]").val("-1");
			$("[id^=showwarnmsg_]").show();
			$("[id^=deliveryMoney_span_]").each(function(){
				var dvids = $(this).attr("id").split("deliveryMoney_span_");
				$(this).html($("#deliveryMoney_"+dvids[1]).val());
			});
		}else{//自提
			$("#totalMoney_span").html($("#gtotalMoney").val());
			$("[id^=tst_]").val("1");
			$("[id^=showwarnmsg_]").hide();
			$("[id^=deliveryMoney_span_]").each(function(){
				var dvids = $(this).attr("id").split("deliveryMoney_span_");
				$(this).html("¥0");
			});
		}
		jQuery.post(Think.U("Home/Orders/checkUseScore"),{'isself':$(this).val()},function(data) {

			var json = WST.toJson(data);
			if(json.scoreMoney==0){
				$("#scorePayLab").hide();
			}else{
				$("#scorePayLab").show();
			}
			$("#isScorePay").attr("scoremoney",json.scoreMoney);
			$("#canUserScore").html(json.canUserScore);
			$("#scoreMoney").html(json.scoreMoney);
			$("#isScorePay").attr("disabled",false);
			$("#isScorePay").attr("checked",false);
		});
	});

	// ADD  BY  YANG
	// 签到金币
	$("#isGoldPay").click(function(){
		var isself = $('input:radio[name="isself"]:checked').val();
		var totalMoney = (isself==1)?$(this).attr("gtotalMoney"):$(this).attr("totalMoney");
		if($("#isGoldPay").prop('checked')){

			var goldMoney = $(this).attr("goldMoney");
			$("#totalMoney_span").html((totalMoney-goldMoney).toFixed(2));
		}else{
			$("#totalMoney_span").html(totalMoney);
		}
	});

	$('input:radio[name="isself"]').click(function(){
		$("#isScorePay").attr("disabled",true);
		if($(this).val()==0){//送货上门
			$("#totalMoney_span").html($("#totalMoney").val());
			$("[id^=tst_]").val("-1");
			$("[id^=showwarnmsg_]").show();
			$("[id^=deliveryMoney_span_]").each(function(){
				var dvids = $(this).attr("id").split("deliveryMoney_span_");
				$(this).html($("#deliveryMoney_"+dvids[1]).val());
			});
		}else{//自提
			$("#totalMoney_span").html($("#gtotalMoney").val());
			$("[id^=tst_]").val("1");
			$("[id^=showwarnmsg_]").hide();
			$("[id^=deliveryMoney_span_]").each(function(){
				var dvids = $(this).attr("id").split("deliveryMoney_span_");
				$(this).html("¥0");
			});
		}
		jQuery.post(Think.U("Home/Orders/checkUseScore"),{'isself':$(this).val()},function(data) {

			var json = WST.toJson(data);
			if(json.scoreMoney==0){
				$("#scorePayLab").hide();
			}else{
				$("#scorePayLab").show();
			}
			$("#isScorePay").attr("scoremoney",json.scoreMoney);
			$("#canUserScore").html(json.canUserScore);
			$("#scoreMoney").html(json.scoreMoney);
			$("#isScorePay").attr("disabled",false);
			$("#isScorePay").attr("checked",false);
		});
	});



});


function GetQueryString(name){
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if(r!=null)return  unescape(r[2]); return null;
}

