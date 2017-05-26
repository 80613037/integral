<?php
namespace Home\Action;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 订单控制器
 */
class OrdersAction extends BaseAction {
	/**
	 * 获取待付款的订单列表
	 */
    public function queryByPage(){
		$this->isUserLogin();
		$USER = session('WST_USER');
		session('WST_USER.loginTarget','User');
		//判断会员等级
		$rm = D('Home/UserRanks');
		$USER["userRank"] = $rm->getUserRank();
		session('WST_USER',$USER);
		//获取订单列表
		$morders = D('Home/Orders');
		$obj["userId"] = (int)$USER['userId'];
		$orderList = $morders->queryByPage($obj);
		$statusList = $morders->getUserOrderStatusCount($obj);
		$um = D('Home/Users');
		$user = $um->getUserById(array("userId"=>session('WST_USER.userId')));
		$this->assign("userScore",$user['userScore']);
		$this->assign("umark","queryByPage");
		$this->assign("orderList",$orderList);
		$this->assign("statusList",$statusList);
		$this->display("mobile/users/orders/list");
	}
	/**
	 * 获取待付款的订单列表
	 */
	public function queryPayByPage(){
		$this->isUserLogin();
		$USER = session('WST_USER');
		$morders = D('Home/Orders');
		self::WSTAssigns();
		$obj["userId"] = (int)$USER['userId'];
		$payOrders = $morders->queryPayByPage($obj);
		if(I('f') == 'api'){
            $res['data'] = $payOrders['root'];
            $res['total'] = (int)$payOrders['total'];         // 总条数
            $res['pageSize'] = $payOrders['pageSize'];   // 每页条数
            $res['totalPage'] = $payOrders['totalPage']; // 总页数
            $res['currPage'] = (int)$payOrders['currPage'];   // 当前页码
            $res['code'] = 1;
            if(isset($_GET['callback'])){
                exit($_GET['callback'].'(' . json_encode($res) .')');
            }else
		    exit(json_encode($res));
        }

//        var_dump($payOrders);
		$this->assign("umark","queryPayByPage");
		$this->assign("payOrders",$payOrders);
		$this->display("mobile/users/orders/list_pay");
	}
    /**
	 * 获取待发货的订单列表
	 */
	public function queryDeliveryByPage(){
		$this->isUserLogin();
		$USER = session('WST_USER');
		$morders = D('Home/Orders');
		self::WSTAssigns();
		$obj["userId"] = (int)$USER['userId'];
		$deliveryOrders = $morders->queryDeliveryByPage($obj);
		$this->assign("umark","queryDeliveryByPage");
		$this->assign("receiveOrders",$deliveryOrders);
		$this->display("mobile/users/orders/list_delivery");
	}
    /**
	 * 获取退款订单列表
	 */
	public function queryRefundByPage(){
		$this->isUserLogin();
		$USER = session('WST_USER');
		$morders = D('Home/Orders');
		self::WSTAssigns();
		$obj["userId"] = (int)$USER['userId'];
		$refundOrders = $morders->queryRefundByPage($obj);
		$this->assign("umark","queryRefundByPage");
		$this->assign("receiveOrders",$refundOrders);
		$this->display("mobile/users/orders/list_refund");
	}
    /**
	 * 获取收货的订单列表
	 */
	public function queryReceiveByPage(){
		$this->isUserLogin();
		$USER = session('WST_USER');
		$morders = D('Home/Orders');
		self::WSTAssigns();
		$obj["userId"] = (int)$USER['userId'];
		$receiveOrders = $morders->queryReceiveByPage($obj);
        if(I('f') == 'api') {
            $res['data'] = $receiveOrders['root'];
            $res['total'] = (int)$receiveOrders['total'];         // 总条数
            $res['pageSize'] = $receiveOrders['pageSize'];   // 每页条数
            $res['totalPage'] = $receiveOrders['totalPage']; // 总页数
            $res['currPage'] = (int)$receiveOrders['currPage'];   // 当前页码
            $res['code'] = 1;
            if (isset($_GET['callback'])) {
                exit($_GET['callback'] . '(' . json_encode($res) . ')');
            } else
                exit(json_encode($res));
        }
		$this->assign("umark","queryReceiveByPage");
		$this->assign("receiveOrders",$receiveOrders);
		$this->display("mobile/users/orders/list_receive");
	}


    /**
     * 物流信息
     */
	public function transport(){
		$morders = D('Home/Orders');
		$traninfo = $morders->transport();
		$this->assign("traninfo", $traninfo['0']);
	    $this->display("mobile/users/orders/transport");
    }


	/**
	 * 获取已取消订单
	 */
	public function queryCancelOrders(){
		$this->isUserLogin();
		$USER = session('WST_USER');
		$morders = D('Home/Orders');
		self::WSTAssigns();
		$obj["userId"] = (int)$USER['userId'];
		$receiveOrders = $morders->queryCancelOrders($obj);
		$this->assign("umark","queryCancelOrders");
		$this->assign("receiveOrders",$receiveOrders);
		$this->display("default/users/orders/list_cancel");
	}

	/**
	 * 获取待评价订单
	 */
    public function queryAppraiseByPage(){
    	$this->isUserLogin();
    	$USER = session('WST_USER');
    	$morders = D('Home/Orders');
    	self::WSTAssigns();
    	$obj["userId"] = (int)$USER['userId'];
		$appraiseOrders = $morders->queryAppraiseByPage($obj);
        if(I('f') == 'api') {
            $res['data'] = $appraiseOrders['root'];
            $res['total'] = (int)$appraiseOrders['total'];         // 总条数
            $res['pageSize'] = $appraiseOrders['pageSize'];   // 每页条数
            $res['totalPage'] = $appraiseOrders['totalPage']; // 总页数
            $res['currPage'] = (int)$appraiseOrders['currPage'];   // 当前页码
            $res['code'] = 1;
            if (isset($_GET['callback'])) {
                exit($_GET['callback'] . '(' . json_encode($res) . ')');
            } else
                exit(json_encode($res));
        }
		$this->assign("umark","queryAppraiseByPage");
		$this->assign("appraiseOrders",$appraiseOrders);
		$this->display("mobile/users/orders/list_appraise");
	}

	/**
	 * 获取待评价订单
	 */
	public function queryCompleteOrders(){
		$this->isUserLogin();
		$USER = session('WST_USER');
		$morders = D('Home/Orders');
		self::WSTAssigns();
		$obj["userId"] = (int)$USER['userId'];
		$appraiseOrders = $morders->queryCompleteOrders($obj);
		$this->assign("umark","queryCompleteOrders");
		$this->assign("appraiseOrders",$appraiseOrders);
		$this->display("default/users/orders/list_complete");
	}

	/**
	 * 订单詳情-买家专用
	 */
	public function getOrderInfo(){
		$this->isUserLogin();
		$USER = session('WST_USER');
		$morders = D('Home/Orders');
		$obj["userId"] = (int)$USER['userId'];
		$obj["orderId"] = (int)I("orderId");
		$rs = $morders->getOrderDetails($obj);
		$data["orderInfo"] = $rs;
		$this->assign("orderInfo",$rs);
		$this->display("default/order_details");
	}

	/**
	 * 取消订单
	 */
    public function orderCancel(){
    	$this->isUserLogin();
    	$USER = session('WST_USER');
    	$morders = D('Home/Orders');
    	$obj["userId"] = (int)$USER['userId'];
    	$obj["orderId"] = (int)I("orderId");
		$rs = $morders->orderCancel($obj);
		$this->ajaxReturn($rs);
	}

	/**
	 * 用户确认收货订单
	 */
    public function orderConfirm(){
    	$this->isUserLogin();
    	$USER = session('WST_USER');
    	$morders = D('Home/Orders');
    	$obj["userId"] = (int)$USER['userId'];
    	$obj["orderId"] = (int)I("orderId");
    	$obj["type"] = (int)I("type");
		$rs = $morders->orderConfirm($obj);
		$this->ajaxReturn($rs);
	}

	/**
	 * 核对订单信息
	 */
	public function checkOrderInfo(){
		$this->isUserLogin();
		$maddress = D('Home/UserAddress');
		$mcart = D('Home/Cart');
		$rdata = $mcart->getPayCart();
	    if($rdata["cartnull"]==1){
			$this->assign("fail_msg",'不能提交空商品的订单!');
			$this->display('default/order_fail');
			exit();
		}
		$catgoods = $rdata["cartgoods"];
// dump($catgoods);
		$shopColleges = $rdata["shopColleges"];
		$distributAll = $rdata["distributAll"];
		$startTime = $rdata["startTime"];
		$endTime = $rdata["endTime"];
		$gtotalMoney = $rdata["gtotalMoney"];//商品总价（去除配送费）
		$totalMoney = $rdata["totalMoney"];//商品总价（含配送费）
		$totalCnt = $rdata["totalCnt"];
		$userId = session('WST_USER.userId');
		//获取地址列表
        $areaId2 = $this->getDefaultCity();
		$addressList = $maddress->queryByUserAndCity($userId,$areaId2);
		$this->assign("addressList",$addressList);
		$this->assign("areaId2",$areaId2);
		//支付方式
		$pm = D('Home/Payments');
		$payments = $pm->getList();
		$this->assign("payments",$payments);

		//获取当前市的县区
		$m = D('Home/Areas');
		$provinces = $m->getProvinceList();
		$this->assign("provinces",$provinces);

		if($endTime==0){
			$endTime = 24;
			$cstartTime = (floor($startTime))*4;
			$cendTime = (floor($endTime))*4;
		}else{
			$cstartTime = (floor($startTime)+1)*4;
			$cendTime = (floor($endTime)+1)*4;
		}
		if(floor($startTime)<$startTime){
			$cstartTime = $cstartTime + 2;
		}
		if(floor($endTime)<$endTime){
			$cendTime = $cendTime + 2;
		}
		$baseScore = WSTOrderScore();
		$baseMoney = WSTScoreMoney();
        $baseGold = WSTOrderGold();
		$this->assign("startTime",$cstartTime);
		$this->assign("endTime",$cendTime);
		$this->assign("shopColleges",$shopColleges);
		$this->assign("distributAll",$distributAll);
		$this->assign("catgoods",$catgoods);
//		$this->assign("gtotalMoney",$gtotalMoney);
//		$this->assign("totalMoney",$totalMoney);
		$um = D('Home/Users');
		$user = $um->getUserById(array("userId"=>session('WST_USER.userId')));
		$this->assign("userScore",$user['userScore']);
		$useScore = $baseScore*floor($user["userScore"]/$baseScore);
		$scoreMoney = $baseMoney*floor($user["userScore"]/$baseScore);
		if($totalMoney<$scoreMoney){//订单金额小于积分金额
			$useScore = $baseScore*floor($totalMoney/$baseMoney);
			$scoreMoney = $baseMoney*floor($totalMoney/$baseMoney);
		}

        $this->assign("userGold",$user['userSignGold']);
//        $useGold = $baseGold*floor($user["userSignGold"]/$baseGold);
        $goldMoney = $baseMoney*floor($user["userSignGold"]/$baseGold);
//        $this->assign("canUserGold",$useGold);
        $this->assign("goldMoney",$goldMoney);


        // 读取用户满减代金券信息 ADD BY YANG
        /**
        $ordersM = D('Home/Orders');
        $haveCoup = $ordersM->getUserCoup($userId,1,1);
        if($haveCoup != array()) {
            // 是否符合当前订单价格状态（满减额度）
            if($totalMoney >= $haveCoup['0']['man']){ // 有且可用
                $haveCoup['0']['msg'] = 1;
                $coup = $haveCoup['0'];
            }else{
                $coup = array('msg'=>0); // 有但不可用
            }
        }else{
            $coup = array('msg'=>2); // 无
        }
        */

        $coup_pay = I('coup_pay') ? number_format(I('coup_pay'),2) : '0.00';
        $this->assign("coupid", I('coupid'));
        $this->assign("coup_pay", $coup_pay); // 代金券抵扣金额
        // 用户选择使用金币抵扣现金 的金币数量
        // session('gold_pay',null);
        $gold_pay = I('gold_pay');
        $gold_pay = $gold_pay ? $gold_pay : 0;
        $canUseGold = number_format(($gold_pay/$baseGold),2); // 按照系统设置比例折算
        $this->assign("gold_pay", $canUseGold); // 金币抵扣金额
        $this->assign("gold", I('gold_pay'));
		$this->assign("canUserScore",$useScore);
		$this->assign("scoreMoney",$scoreMoney);
        $this->assign("gtotalMoney",($gtotalMoney-(float)$canUseGold) - $coup_pay);
        $this->assign("totalMoney",($totalMoney-(float)$canUseGold) - $coup_pay);
        $this->display('mobile/check_order');
	}

    public function checkOrderInfo_gotopay(){
        $this->isUserLogin();
        $maddress = D('Home/UserAddress');
        $userId = session('WST_USER.userId');
        $morder = D('Home/Orders');
        //获取地址列表
        $areaId2 = $this->getDefaultCity();
        $addressList = $maddress->queryByUserAndCity($userId,$areaId2);

        $obj["orderId"] = I('orderid',0);
//        $obj["orderType"] = 3;
        $orderInfo = $morder->getPayOrders_new($obj);
        $count = (count($orderInfo[I('orderid',0)]));

        foreach($orderInfo[I('orderid',0)] as $key=>$value){
            $sumMoney[] = $value['goodsPrice'];
            $sumMoneys[] = (int)($value['goodsPrice'] * $value['goodsNums']);
        }
        if($count == 1){
            $totalMoney = (array_sum($sumMoney)*$orderInfo[I('orderid',0)]['0']['goodsNums'] + (int)$value['deliverMoney']);
        }elseif($count > 1){
            $totalMoney = (array_sum($sumMoneys) + (int)$value['deliverMoney']);
        }

        $baseGold = WSTOrderGold();
        $gold_pay = I('gold_pay');
        $gold_pay = $gold_pay ? $gold_pay : 0;
        $canUseGold = number_format(($gold_pay/$baseGold),2); // 按照系统设置比例折算
        $this->assign("gold_pay", $canUseGold); // 金币抵扣金额
        $this->assign("gold", I('gold_pay'));
        $coup_pay = $morder->getCoup($orderInfo[I('orderid',0)][0]['orderId']);
        $this->assign("coup_pay", $coup_pay[0]['jian']); // 优惠券抵扣金额
        $this->assign('orderInfo', $orderInfo[I('orderid',0)]);

        $this->assign('orderid', I('orderid',0));
        $this->assign("addressList",$addressList);
        $this->assign("gtotalMoney",(array_sum($sumMoneys)-(float)$canUseGold) - $coup_pay[0]['jian']);
        $this->assign("totalMoney",($totalMoney-(float)$canUseGold) - $coup_pay[0]['jian']);
        $this->display('mobile/check_order_gotopay');
    }


	public function checkUseScore(){
		$mcart = D('Home/Cart');
		$rdata = $mcart->getPayCart();
		if((int)I("isself")){
			$totalMoney = $rdata["gtotalMoney"];//商品总价（去除配送费）
		}else{
			$totalMoney = $rdata["totalMoney"];//商品总价（含配送费）
		}

		$baseScore = WSTOrderScore();
		$baseMoney = WSTScoreMoney();
		$um = D('Home/Users');
		$user = $um->getUserById(array("userId"=>session('WST_USER.userId')));
		$useScore = $baseScore*floor($user["userScore"]/$baseScore);
		$scoreMoney = $baseMoney*floor($user["userScore"]/$baseScore);
		if($totalMoney<$scoreMoney){//订单金额小于积分金额
			$useScore = $baseScore*floor($totalMoney/$baseMoney);
			$scoreMoney = $baseMoney*floor($totalMoney/$baseMoney);
		}
		$rs["canUserScore"] = $useScore;
		$rs["scoreMoney"] = $scoreMoney;
		$this->ajaxReturn($rs);
	}

	/**
	 * 提交订单信息
	 *
	 */
	public function submitOrder(){
		$this->isUserLogin();
		session("WST_ORDER_UNIQUE",null);
		$morders = D('Home/Orders');
		$rs = $morders->submitOrder();
		$this->ajaxReturn($rs);
	}

    /**
     * 拼团订单
     */
    public function submitOrder_pt(){
        $this->isUserLogin();
        session("WST_ORDER_UNIQUE",null);
        $morders = D('Home/Orders');
        $rs = $morders->submitOrder_pt();
        $this->ajaxReturn($rs);
    }
	/**
	 * 显示下单结果
	 */
	public function orderSuccess(){
		$this->isUserLogin();
		$morders = D('Home/Orders');
		$this->assign("orderInfos",$morders->getOrderListByIds());
		$this->display('default/order_success');
	}

	/**
	 * 检查是否已支付
	 */
	public function checkOrderPay(){
		$morders = D('Home/Orders');
		$USER = session('WST_USER');
		$obj["userId"] = (int)$USER['userId'];
		$rs = $morders->checkOrderPay($obj);
		$this->ajaxReturn($rs);
	}


	/**
	 * 订单詳情
	 */
	public function getOrderDetails(){
		$this->isUserLogin();
		$USER = session('WST_USER');
		$morders = D('Home/Orders');
		$obj["userId"] = (int)$USER['userId'];
		$obj["shopId"] = (int)$USER['shopId'];
		$obj["orderId"] = (int)I("orderId");
		$rs = $morders->getOrderDetails($obj);
		$data["orderInfo"] = $rs;
        if(($rs['order']['orderStatus']==3) || ($rs['order']['orderStatus']==4) || ($rs['order']['orderStatus']==5)){
            $transInfo = $morders->getTransForOrderDetails($rs['order']['orderId']);
            $this->assign('transInfo', $transInfo['0']);
        }
		$this->assign("orderInfo",$rs);
		$this->display("default/users/orders/details");
	}

    /**
     * 商户后台查看订单详情 _接口
     */
    public function getOrderDetailsApi(){
        $morders = D('Home/Orders');
        $obj["shopId"] = (int)I('shopId');
        $obj["orderId"] = (int)I("orderId");
        $rs = $morders->getOrderDetails($obj);
        $data["orderInfo"] = $rs;
        if (isset($_GET['callback'])) {
            exit($_GET['callback'] . '(' . json_encode($rs) . ')');
        } else{
            exit(json_encode($rs));
        }
    }

    /**
     * 手机端查看订单详情
     */
    public function getOrderDetailsFromMobile(){
        $this->isUserLogin();
        $USER = session('WST_USER');
        $morders = D('Home/Orders');
        $obj["userId"] = (int)$USER['userId'];
        $obj["shopId"] = (int)$USER['shopId'];
        $obj["orderId"] = (int)I("orderId");
        $rs = $morders->getOrderDetails($obj);
        $mc = D('Admin/Coupon');
//        dump($rs);
        $coupon = $mc->get($rs['order']['coupId']);
        $rs['order']['coupId'] = $coupon['id'];
        $rs['order']['man'] = $coupon['man'];
        $rs['order']['jian'] = $coupon['jian'];

        $data["orderInfo"] = $rs;
        $this->assign("orderInfo",$rs);
        $this->display("mobile/users/orders/details");
    }

	/*************************************************************************/
	/********************************商家訂單管理*****************************/
	/*************************************************************************/
	/**
	 * 跳转到商家订单列表
	*/
	public function toShopOrdersList(){
		$this->isShopLogin();
		$morders = D('Home/Orders');
        $isSelf = D('Home/Goods')->isSelf();
        $this->assign('isSelf',$isSelf['isSelf']);
		$this->assign("umark","toShopOrdersList");
		$this->display("default/shops/orders/list");
	}

	/*
	 * 拼团订单列表
	 */
    public function toShopPintuanOrdersList(){
        /**
        $this->isShopLogin();
        $USER = session('WST_USER');
        $obj["userId"] = (int)$USER['userId'];
        $obj["shopId"] = (int)$USER['shopId'];
        $m = D('Home/Orders');
        $orders = $m->queryShopPtordersByPage($obj);

        $this->ajaxReturn($orders);

        $this->assign("umark","toShopPintuanOrdersList");
        $this->assign("Page",$Page);
        $this->display("default/shops/orders/ptlist");

         */
    }



	/**
	 * 获取商家订单列表
	*/
	public function queryShopOrders(){
		$this->isShopLogin();
		$USER = session('WST_USER');
		$morders = D('Home/Orders');
		$obj["shopId"] = (int)$USER["shopId"];
		$obj["userId"] = (int)$USER['userId'];
        if(I("f")=='api'){
            $orders = $morders->OrdersListsApi(I('shopid'));
            if(isset($_GET['callback'])){
                exit($_GET['callback']."(".json_encode($orders).")");
            }else{
               $this->ajaxReturn($orders);
            }
        }else{
            $orders = $morders->queryShopOrders($obj);
            $this->ajaxReturn($orders);
        }
	}

    /**
     * 核销订单 接口
     */
	public function changeOrderApi(){
        $morders = D('Home/Orders');
        if(I("f")=='api') {
            $ret = $morders->changeOrderApi();
            if (isset($_GET['callback']) && (I("f") == 'api')) {
                exit($_GET['callback'] . "(" . json_encode($ret) . ")");
            } else {
                $this->ajaxReturn($ret);
            }
        }
    }

	/**
	 * 商家受理订单
	 */
    public function shopOrderAccept(){
    	$this->isShopLogin();
    	$USER = session('WST_USER');
    	$morders = D('Home/Orders');
    	$obj["userId"] = (int)$USER['userId'];
    	$obj["shopId"] = (int)$USER['shopId'];
    	$obj["orderId"] = (int)I("orderId");
		$rs = $morders->shopOrderAccept($obj);
		$this->ajaxReturn($rs);
	}
    /**
	 * 商家批量受理订单
	 */
    public function batchShopOrderAccept(){
    	$this->isShopLogin();
    	$morders = D('Home/Orders');
		$rs = $morders->batchShopOrderAccept($obj);
		$this->ajaxReturn($rs);
	}
	/**
	 * 商家生产订单
	 */
    public function shopOrderProduce(){
    	$this->isShopLogin();
    	$USER = session('WST_USER');
    	$morders = D('Home/Orders');
    	$obj["userId"] = (int)$USER['userId'];
    	$obj["shopId"] = (int)$USER['shopId'];
    	$obj["orderId"] = (int)I("orderId");
		$rs = $morders->shopOrderProduce($obj);
		$this->ajaxReturn($rs);
	}
	public function batchShopOrderProduce(){
    	$this->isShopLogin();
    	$morders = D('Home/Orders');
		$rs = $morders->batchShopOrderProduce($obj);
		$this->ajaxReturn($rs);
	}
	/**
	 * 商家发货配送订单
	 */
    public function shopOrderDelivery(){
    	$this->isShopLogin();
    	$USER = session('WST_USER');
    	$morders = D('Home/Orders');
    	$obj["userId"] = (int)$USER['userId'];
    	$obj["shopId"] = (int)$USER['shopId'];
    	$obj["orderId"] = (int)I("orderId");
		$rs = $morders->shopOrderDelivery($obj);
		$this->ajaxReturn($rs);
	}

    /**
     * 填写快递单号 并发货
     */
    public function toWriteTransport(){
        $this->isShopLogin();
        $orderid = I('orderId');
        // 获取快递列表
        $o = D('Home/Orders');
        $res = $o->kuaidiByPage();
        if(I("f")=='api'){
            $ret['data'] = $res['root'];
            $ret['code'] = 1;
            die(json_encode($ret));
        }
        $this->assign('orderid', $orderid);
        $this->assign('kuaidiList', $res['root']);
        $this->display("default/users/orders/towrite_trans");
    }
    public function toPostTransport(){
        $this->isShopLogin();
        $o = D('Home/Orders');
        $rs = $o->toPostTransport();
        $this->ajaxReturn($rs);
    }


    /**
	 * 商家发货配送订单
	 */
    public function batchShopOrderDelivery(){
    	$this->isShopLogin();
    	$morders = D('Home/Orders');
		$rs = $morders->batchShopOrderDelivery($obj);
		$this->ajaxReturn($rs);
	}

	/**
	 * 商家确认收货订单
	 */
    public function shopOrderReceipt(){
    	$this->isShopLogin();
    	$USER = session('WST_USER');
    	$morders = D('Home/Orders');
    	$obj["userId"] = (int)$USER['userId'];
    	$obj["shopId"] = (int)$USER['shopId'];
    	$obj["orderId"] = (int)I("orderId");
		$rs = $morders->shopOrderReceipt($obj);
		$this->ajaxReturn($rs);
	}

	/**
	 * 商家同意拒收/不同意拒收
	 */
	public function shopOrderRefund(){
		$this->isShopLogin();
		$USER = session('WST_USER');
    	$morders = D('Home/Orders');
    	$obj["userId"] = (int)$USER['userId'];
    	$obj["shopId"] = (int)$USER['shopId'];
    	$obj["orderId"] = (int)I("orderId");
		$rs = $morders->shopOrderRefund($obj);
		$this->ajaxReturn($rs);
	}

	/**
	 * 获取用户订单消息提示
	 */
	public function getUserMsgTips(){
		$this->isUserLogin();
		$morders = D('Home/Orders');
		$USER = session('WST_USER');
		$obj["userId"] = (int)$USER['userId'];
		$statusList = $morders->getUserOrderStatusCount($obj);
		$this->ajaxReturn($statusList);
	}

	/**
	 * 获取店铺订单消息提示
	 */
	public function getShopMsgTips(){
		$this->isShopLogin();
		$morders = D('Home/Orders');
		$USER = session('WST_USER');
		$obj["shopId"] = (int)$USER['shopId'];
		$obj["userId"] = (int)$USER['userId'];
		$statusList = $morders->getShopOrderStatusCount($obj);
		$this->ajaxReturn($statusList);
	}

    public function payYue(){
        $u = A('Users');
        $o = D('Orders');
        $uinfo = $u->userInfo();
        $uMoney = $uinfo['userMoney'];
        if(C('isDevelop')){ WLog('gold_pay','Mark',I('gold').'-'.I('totalMoney'));}
        if($uMoney < (int)I('totalMoney')){
            if(C('isDevelop')){WLog('#648','1', $uMoney.'<-->'.(int)I('totalMoney'));}
            $data['msg'] = '您的余额不足，请使用微信支付！';
            $data['code'] = 0;
        }elseif($uMoney >= (int)I('totalMoney')){
            $res = $o->setUserMoney($uinfo['userId'],$uMoney,I('totalMoney')); // 减少余额
            $o->setUserGold($uinfo['userId'],$uinfo['userSignGold'],I('gold')); // 减少签到所得金币
            if(C('isDevelop')){WLog('#654','1', $res);}
            if($res){
                D("Home/Users")->log_money($uinfo['userId'], 3, '2', '支付订单', 0, I('totalMoney'), I('oids'), I('gold'), '0');
                D("Home/Users")->log_gold($uinfo['userId'], I('gold'), '消费抵扣-'.I('gold').'金币',-1, '消费抵扣');
                if(C('isDevelop')){WLog('#658','1', $res);}
                $data['msg'] = '支付成功！';
                $data['code'] = 1;
            }else{
                if(C('isDevelop')){WLog('#662','1', 'false');}
                $data['msg'] = '支付失败，请稍后重试！';
                $data['code'] = 2;
            }
        }
        $this->ajaxReturn($data);
    }

    public function changeOrdertatusAndorderLog(){
        $orderids = I('orderids');
        $order = M('orders')->where("orderId in ($orderids)")->save(array('orderStatus'=>1)); // 修改订单状态
        $order_log = M('log_orders')->where("orderId in ($orderids)")->save(array('logContent'=>'下单成功，用户余额支付')); // 修改订单状态
//        WLog('orders','order', M('orders')->getLastSql());
//        WLog('log_orders','order', M('log_orders')->getLastSql());
        if(!$order OR !$order_log){
            WLog('orderLog', 'orderId', $orderids);
        }else{
            echo 1;
        }
    }

    /**
     * 下订单 支付之前选择使用金币数量
     */
    public function gold_pay(){
        $umodel = D("Home/Users");
        if(I('gold_pay')){
            $this->checkOrderInfo();
        }else{
            // 获取金币与金钱比例
            $s = M("sys_configs")->where(array("configId"=>62))->getField("fieldValue");
            $uinfo = $umodel->getUserById(session('WST_USER'));

            $mcart = D('Home/Cart');
            $rdata = $mcart->getPayCart();
            // 拼团商品不走购物车
            if($rdata["totalMoney"] != 0){
                if($uinfo["userSignGold"] < ($rdata["totalMoney"]*100)){
                    $canUse = $uinfo["userSignGold"];
                }else{
                    $canUse = ($rdata["totalMoney"]*100);
                }
            }else{
                $canUse = $uinfo["userSignGold"];
            }

            $this->assign("canUse", $canUse);
            $this->assign("s", $s);
            $this->assign("Totalgold", $uinfo["userSignGold"]);
            $this->display("mobile/users/gold_pay");
        }
    }


    /**
     * 下订单 支付之前选择使用代金券
     */
    public function coup_pay(){
        $umodel = D("Home/Users");
        if(I('coup_pay')){
            $this->checkOrderInfo();
        }else{
            //$uinfo = $umodel->getUserById(session('WST_USER'));
            //$mcart = D('Home/Cart');
            //$rdata = $mcart->getPayCart();
            $uid = session('WST_USER.userId');
            $totalMoney = I('totalMoney');
            $u = D('Home/Users');
            $list = $u->userCoup($uid);
            $d = $list['0']['getdate'];
            $days = $list['0']['expire'];
            $endDate = date("Y-m-d",strtotime("$d   +$days   day"));   //日期天数相加函数 // 失效日期
            $list['0']['enddate'] = $endDate;
            $minus = strtotime($endDate) - time();
            if(($minus > 0) && ($list['0']['ucstatus'] == 1)){
                $this->assign('listWsy',$list);
            }
//            dump($list);
            $this->assign("totalMoney", $totalMoney);
            $this->display("mobile/users/coup_pay");
        }
    }




	public function checkPtOrderInfo(){
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if ((strpos($user_agent, 'MicroMessenger') === false) && !C('isDevelop')){
            echo "<script>alert('请使用微信打开！'); window.location.href='./index.php';</script>";
        }
		$this->isUserLogin();
		$maddress = D('Home/UserAddress');
		$goodsId = (int)I('goodsId');
		$goodsInfo = M("goods")->where(array('goodsId'=>$goodsId))->select();
		$shopInfo = M('shops')->where(array('shopId'=>$goodsInfo['0']['shopId']))->select();
		$catgoods['0']['shopgoods'] = $goodsInfo;
		$catgoods['0']['shopName'] = $shopInfo['0']['shopName'];
        $catgoods['0']['shopId'] = $shopInfo['0']['shopId'];

		$shopColleges = $rdata["shopColleges"];
		$distributAll = $rdata["distributAll"];
		$startTime = $rdata["startTime"];
		$endTime = $rdata["endTime"];
		$gtotalMoney = $goodsInfo['0']['shopPrice'];//商品总价（去除配送费）
        $deliverMoney = ($gtotalMoney < $shopInfo['0']["deliveryFreeMoney"]) ? $shopInfo['0']["deliveryMoney"] : 0;
        if(C('isDevelop')){WLog('755','s', $deliverMoney);};
		$totalMoney = $goodsInfo['0']['shopPrice']+$deliverMoney; // 商品总价（未达到满包邮的话则含配送费）
		$totalCnt = 1; // $rdata["totalCnt"];


		$userId = session('WST_USER.userId');
		//获取地址列表
        $areaId2 = $this->getDefaultCity();
		$addressList = $maddress->queryByUserAndCity($userId,$areaId2);
		$this->assign("addressList",$addressList);
		$this->assign("areaId2",$areaId2);
		//支付方式
		$pm = D('Home/Payments');
		$payments = $pm->getList();
		$this->assign("payments",$payments);

		//获取当前市的县区
		$m = D('Home/Areas');
		$provinces = $m->getProvinceList();
		$this->assign("provinces",$provinces);

		if($endTime==0){
			$endTime = 24;
			$cstartTime = (floor($startTime))*4;
			$cendTime = (floor($endTime))*4;
		}else{
			$cstartTime = (floor($startTime)+1)*4;
			$cendTime = (floor($endTime)+1)*4;
		}
		if(floor($startTime)<$startTime){
			$cstartTime = $cstartTime + 2;
		}
		if(floor($endTime)<$endTime){
			$cendTime = $cendTime + 2;
		}
		$baseScore = WSTOrderScore();
		$baseMoney = WSTScoreMoney();
        $baseGold = WSTOrderGold();
        $this->assign('leaderid',I('leaderid', 0));
		$this->assign("startTime",$cstartTime);
		$this->assign("endTime",$cendTime);
		$this->assign("shopColleges",$shopColleges);
		$this->assign("distributAll",$distributAll);
		$this->assign("catgoods",$catgoods);
//		$this->assign("gtotalMoney",$gtotalMoney);
//		$this->assign("totalMoney",$totalMoney);
		$um = D('Home/Users');
		$user = $um->getUserById(array("userId"=>session('WST_USER.userId')));
		$this->assign("userScore",$user['userScore']);
		$useScore = $baseScore*floor($user["userScore"]/$baseScore);
		$scoreMoney = $baseMoney*floor($user["userScore"]/$baseScore);
		if($totalMoney<$scoreMoney){//订单金额小于积分金额
			$useScore = $baseScore*floor($totalMoney/$baseMoney);
			$scoreMoney = $baseMoney*floor($totalMoney/$baseMoney);
		}

        $this->assign("userGold",$user['userSignGold']);
//        $useGold = $baseGold*floor($user["userSignGold"]/$baseGold);
        $goldMoney = $baseMoney*floor($user["userSignGold"]/$baseGold);
//        $this->assign("canUserGold",$useGold);
        $this->assign("goldMoney",$goldMoney);

        $coup_pay = I('coup_pay') ? number_format(I('coup_pay'),2) : '0.00';
        $this->assign("coup_pay", $coup_pay); // 代金券抵扣金额
        // 用户选择使用金币抵扣现金 的金币数量
        // session('gold_pay',null);
        $gold_pay = I('gold_pay');
        $gold_pay = $gold_pay ? $gold_pay : 0;
        $canUseGold = number_format(($gold_pay/$baseGold),2); // 按照系统设置比例折算
        $this->assign("gold_pay", $canUseGold); // 金币抵扣金额
        $this->assign("gold", I('gold_pay'));
		$this->assign("canUserScore",$useScore);
		$this->assign("scoreMoney",$scoreMoney);
        $this->assign("gtotalMoney",($gtotalMoney-(float)$canUseGold) - $coup_pay);
        $this->assign("totalMoney",($totalMoney-(float)$canUseGold) - $coup_pay);
        $this->display('mobile/check_pt_order');
	}

} // END class