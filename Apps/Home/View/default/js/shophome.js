
 /** ***********************start************************ */
$(function() {
	//head 弹出菜单部分
	$(".pcate").click(function(){
		var cname = $(this).attr("data");
			if($("#"+cname+"-s").attr("class")=="span2"){
				$("#"+cname+"-s").attr("class","span1");
			}else{
				$("#"+cname+"-s").attr("class","span2");
			}
		$("."+cname+"-c").toggle(100);
		
	});
	
});

function searchShopsGoods(obj){
	var msort = $("#msort").val(obj);
	var mdesc = $('#mdesc').val();
	var params = new Array();
	params.push("msort=" + $("#msort").val());
	params.push("mdesc=" + ((mdesc=="0")?"1":"0"));
	params.push("sprice=" + $("#sprice").val());
	params.push("eprice=" + $("#eprice").val());
	params.push("shopId=" + $("#shopId").val());
	params.push("ct1=" + $("#ct1").val());
	params.push("ct2=" + $("#ct2").val());
	params.push("goodsName=" + $("#goodsName").val());
	document.location.href = Think.U('Home/Shops/toShopHome',params.join('&'));

}

function searchwst(){
	var goodsName = $.trim($("#goodsName").val());
	window.location = Think.U('Home/goods/getGoodsList','searchType=2&keyWords='+encodeURIComponent(goodsName));
}
