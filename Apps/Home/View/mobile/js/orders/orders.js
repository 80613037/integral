/**
 * Created by Yang on 2016/11/15.
 */

function orderCancel(id,type){
    if(type==1 || type==2){
        var w = layer.open({
            type: 1,
            title:"取消原因",
            shade: [0.6, '#000'],
            border: [0],
            content: '<textarea id="rejectionRemarks" rows="8" style="width:96%" maxLength="100"></textarea>',
            area: ['500px', '260px'],
            btn: ['提交', '关闭窗口'],
            yes: function(index, layero){
                var rejectionRemarks = $.trim($('#rejectionRemarks').val());
                if(rejectionRemarks==''){
                    WST.msg('请输入取消原因 !', {icon: 5});
                    return;
                }
                var ll = layer.load('数据处理中，请稍候...');
                $.post(Think.U('Home/Orders/orderCancel'),{orderId:id,type:1,rejectionRemarks:rejectionRemarks},function(data){
                    layer.close(w);
                    layer.close(ll);
                    var json = WST.toJson(data);
                    if(json.status>0){
                        window.location.reload();
                    }else if(json.status==-1){
                        WST.msg('操作失败，订单状态已发生改变，请刷新后再重试 !', {icon: 5});
                    }else{
                        WST.msg('操作失败，请与商城管理员联系 !', {icon: 5});
                    }
                });
            }
        });
    }else{
        layer.confirm('您确定要取消该订单吗？',{icon: 3, title:'提示'}, function(tips){
            var ll = layer.load('数据处理中，请稍候...');
            $.post("/index.php?m=Home&c=Orders&a=orderCancel",{orderId:id},function(data){
                layer.close(ll);
                layer.close(tips);
                var json = WST.toJson(data);
                if(json.status>0){
                    window.location.reload();
                }else if(json.status==-1){
                    WST.msg('操作失，订单状态已发生改变，请刷新后再重试 !', {icon: 5});
                }else{
                    WST.msg('操作失，请与商城管理员联系 !', {icon: 5});
                }
            });
        });
    }
}


function toPay(id){
    var params = {};
    params.orderId = id;
    jQuery.post('/index.php?m=Home&c=Orders&a=checkOrderPay' ,params,function(data) {
        var json = WST.toJson(data);
        console.info(json);
        if(json.status==1){
            window.location.href='/index.php?m=Home&c=Payments&a=toPay&orderId='+params.orderId;
        }else if(json.status==-2){
            var rlist = json.rlist;
            var garr = new Array();
            for(var i=0;i<rlist.length;i++){
                garr.push(rlist[i].goodsName+rlist[i].goodsAttrName);
            }
            WST.msg('订单中商品【'+garr.join("，")+'】库存不足，不能进行支付。', {icon: 5});
        }else{
            WST.msg('您的订单已支付!', {icon: 5});
            setTimeout(function(){
                window.location.href = '/index.php?m=Home&c=Orders&a=queryDeliveryByPage';
            },1500);
        }
    });
}

