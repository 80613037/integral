<?php
namespace Home\Model;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 订单服务类
 */
class OrdersModel extends BaseModel {
	/**
	 * 获以订单列表
	 */
	public function getOrdersList($obj){
		$userId = $obj["userId"];
		$m = M('orders');
		$sql = "SELECT * FROM __PREFIX__orders WHERE orderFlag=1 and userId = $userId AND orderStatus <>-1 order by createTime desc";		
		return $m->pageQuery($sql);
	}
	
	/**
	 * 取消订单记录 
	 */
	public function getcancelOrderList($obj){		
		$userId = $obj["userId"];
		$m = M('orders');
		$sql = "SELECT * FROM __PREFIX__orders WHERE orderFlag=1 and userId = $userId AND orderStatus =-1 order by createTime desc";		
		return $m->pageQuery($sql);
		
	}

	/**
	 * 获取订单详情
	 */
	public function getOrdersDetails($obj){		
		$orderId = $obj["orderId"];
		$sql = "SELECT od.*,sp.shopName 
				FROM __PREFIX__orders od, __PREFIX__shops sp 
				WHERE orderFlag=1 and od.shopId = sp.shopId And orderId = $orderId ";		
		$rs = $this->query($sql);;	
		return $rs;
		
	}
	
	/**
	 * 获取订单商品信息
	 */
	public function getOrdersGoods($obj){	
			
		$orderId = $obj["orderId"];
		$sql = "SELECT g.*,og.goodsNums as ogoodsNums,og.goodsPrice as ogoodsPrice 
				FROM __PREFIX__order_goods og, __PREFIX__goods g 
				WHERE og.orderId = $orderId AND og.goodsId = g.goodsId ";		
		$rs = $this->query($sql);	
		return $rs;
		
	}
	
	/**
	 * 
	 * 获取订单商品详情
	 */
	public function getOrdersGoodsDetails($obj){	
			
		$orderId = $obj["orderId"];
		$sql = "SELECT g.*,og.goodsNums as ogoodsNums,og.goodsPrice as ogoodsPrice ,ga.id as gaId
				FROM __PREFIX__order_goods og, __PREFIX__goods g 
				LEFT JOIN __PREFIX__goods_appraises ga ON g.goodsId = ga.goodsId AND ga.orderId = $orderId
				WHERE og.orderId = $orderId AND og.goodsId = g.goodsId";		
		$rs = $this->query($sql);	
		return $rs;
		
	}
	
	/**
	 *
	 * 获取订单商品详情
	 */
	public function getPayOrders($obj){
		$orderType = (int)$obj["orderType"];
		$orderId = 0;
		$orderunique = 0;
		if($orderType>0){//来在线支付接口
			$uniqueId = $obj["uniqueId"];
			if($orderType==1){
				$orderId = (int)$uniqueId;
			}else{
				$orderunique = WSTAddslashes($uniqueId);
			}
		}else{
			$orderId = (int)$obj["orderId"];
			$orderunique = session("WST_ORDER_UNIQUE");
		}
		
		if($orderId>0){
			$sql = "SELECT o.orderId, o.orderNo, o.deliverMoney, g.goodsId, g.goodsName ,og.goodsAttrName , og.goodsNums ,og.goodsPrice
				FROM __PREFIX__order_goods og, __PREFIX__goods g, __PREFIX__orders o
				WHERE o.orderId = og.orderId AND og.goodsId = g.goodsId  AND orderFlag =1 AND o.isPay=0 AND o.needPay>0 AND o.orderStatus = -2 AND o.orderId =$orderId";
		}else{
			$sql = "SELECT o.orderId, o.orderNo, o.deliverMoney, g.goodsId, g.goodsName ,og.goodsAttrName , og.goodsNums ,og.goodsPrice
				FROM __PREFIX__order_goods og, __PREFIX__goods g, __PREFIX__orders o
				WHERE o.orderId = og.orderId AND og.goodsId = g.goodsId  AND orderFlag =1 AND o.isPay=0 AND o.needPay>0 AND o.orderStatus = -2 AND o.orderunique ='$orderunique'";
		}
		$rslist = $this->query($sql);
		
		$orders = array();
		foreach ($rslist as $key => $order) {
			$orders[$order["orderNo"]][] = $order;
		}
		if($orderId>0){
			$sql = "SELECT SUM(needPay) needPay FROM __PREFIX__orders WHERE orderId = $orderId AND isPay=0 AND payType=1 AND needPay>0 AND orderStatus = -2 AND orderFlag =1";
		}else{
			$sql = "SELECT SUM(needPay) needPay FROM __PREFIX__orders WHERE orderunique = '$orderunique' AND isPay=0 AND payType=1 AND needPay>0 AND orderStatus = -2 AND orderFlag =1";
		}
		$payInfo = self::queryRow($sql);
		$data["orders"] = $orders;
		$data["needPay"] = $payInfo["needPay"];
		return $data;
	
	}

    public function getPayOrders_new($obj){
        $orderType = (int)$obj["orderType"];
        $orderId = 0;
        $orderunique = 0;
        if($orderType>0){//来在线支付接口
            $uniqueId = $obj["uniqueId"];
            if($orderType==1){
                $orderId = (int)$uniqueId;
            }else{
                $orderunique = WSTAddslashes($uniqueId);
            }
        }else{
            $orderId = (int)$obj["orderId"];
            $orderunique = session("WST_ORDER_UNIQUE");
        }

        if($orderId>0){
            $sql = "SELECT o.orderId, o.orderNo, o.deliverMoney, g.goodsId, g.goodsName ,og.goodsAttrName , og.goodsNums, og.goodsThums, og.goodsPrice
				FROM __PREFIX__order_goods og, __PREFIX__goods g, __PREFIX__orders o
				WHERE o.orderId = og.orderId AND og.goodsId = g.goodsId  AND orderFlag =1 AND o.isPay=0 AND o.needPay>0 AND o.orderStatus = -2 AND o.orderId =$orderId";
        }else{
            $sql = "SELECT o.orderId, o.orderNo, o.deliverMoney, g.goodsId, g.goodsName ,og.goodsAttrName , og.goodsNums, og.goodsThums,og.goodsPrice
				FROM __PREFIX__order_goods og, __PREFIX__goods g, __PREFIX__orders o
				WHERE o.orderId = og.orderId AND og.goodsId = g.goodsId  AND orderFlag =1 AND o.isPay=0 AND o.needPay>0 AND o.orderStatus = -2 AND o.orderunique ='$orderunique'";
        }
        $rslist = $this->query($sql);

        $orders = array();
        foreach ($rslist as $key => $order) {
            $orders[$order["orderId"]][] = $order;
        }
        if($orderId>0){
            $sql = "SELECT SUM(needPay) needPay FROM __PREFIX__orders WHERE orderId = $orderId AND isPay=0 AND payType=1 AND needPay>0 AND orderStatus = -2 AND orderFlag =1";
        }else{
            $sql = "SELECT SUM(needPay) needPay FROM __PREFIX__orders WHERE orderunique = '$orderunique' AND isPay=0 AND payType=1 AND needPay>0 AND orderStatus = -2 AND orderFlag =1";
        }
        $payInfo = self::queryRow($sql);
        $data["orders"] = $orders;
        return $orders;

    }

    public function getCoup($oid){
        //$rs = M('orders')->field('jian')->where(array('orderId'=>$oid))->find();
        $sql = "SELECT cou.jian FROM __PREFIX__orders o LEFT JOIN __PREFIX__coupon cou ON o.coupId=cou.id WHERE o.orderId=".$oid;
        if(C('isDevelop')){WLog('datass','coup_sql:', $sql);}
        return M()->query($sql);
    }
	
	/**
	 * 下单
	 */
	public function submitOrder(){
		$rd = array('status'=>-1);
		$USER = session('WST_USER');
		$goodsmodel = D('Home/Goods');
		$morders = D('Home/Orders');
		$totalMoney = 0;
		$totalCnt = 0;
		$userId = (int)session('WST_USER.userId');
		$consigneeId = (int)I("consigneeId");
		$payway = (int)I("payway");
		$isself = (int)I("isself");
		$needreceipt = (int)I("needreceipt");
		$orderunique = WSTGetMillisecond().$userId;
		$sql = "select count(cartId) cnt from __PREFIX__cart where userId = $userId and isCheck=1 and goodsCnt>0";
		$rcnt = $this->queryRow($sql);
		$cartgoods = array();	
		$order = array();
		if($rcnt['cnt']==0){
            $rd['status'] = 33;
			$rd['msg'] = '购物车为空!';
			return $rd;
		}else{
			$sql = "select * from __PREFIX__cart where userId = $userId and packageId>0 group by batchNo";
			$shopcart = $this->query($sql);
			$batchNos = array();
			for($i=0;$i<count($shopcart);$i++){
				$cgoods = $shopcart[$i];
				$package = array();
				$batchNo = $cgoods["batchNo"];
				$package["batchNo"] = $batchNo;
				$batchNos[] = $batchNo;
				$pkgShopPrice = 0;
				$pckMinStock = 0;
				$sql = "select * from __PREFIX__cart where userId = $userId and batchNo=$batchNo";
				$pkgList = $this->query($sql);
				for($j=0;$j<count($pkgList);$j++){
					$pgoods = $pkgList[$j];
					$packageId = $pgoods["packageId"];
					$goodsId = (int)$pgoods["goodsId"];
					$package["packageId"] = $packageId;
					$package["goodsCnt"] = (int)$pgoods["goodsCnt"];
			
					$sql = "select p.shopId, p.packageName, gp.diffPrice from __PREFIX__goods_packages gp, __PREFIX__packages p where p.packageId =$packageId and gp.packageId=p.packageId and gp.goodsId = $goodsId";
					$pkg = $this->queryRow($sql);
			
					$diffPrice = (float)$pkg["diffPrice"];
					if($pkg["shopId"]>0){
						$package["packageName"] = $pkg["packageName"];
						$package["shopId"] = $pkg["shopId"];
					}
					$goodsAttrId = (int)$pgoods["goodsAttrId"];
					$goods = $goodsmodel->getGoodsSimpInfo($goodsId,$goodsAttrId);
					
					//核对商品是否符合购买要求
					if(empty($goods)){
						$rd['msg'] = '找不到指定的商品!';
						return $rd;
					}
					if($goods['goodsStock']<$package["goodsCnt"]){
						$rd['msg'] = '对不起，商品'.$goods['goodsName'].'库存不足!';
						return $rd;
					}
					if($goods['isSale']!=1){
						$rd['msg'] = '对不起，商品库'.$goods['goodsName'].'已下架!';
						return $rd;
					}
			
					$goods['oshopPrice'] = $goods['shopPrice'];
					$goods['shopPrice'] = ($goods['shopPrice']>$diffPrice)?($goods['shopPrice']-$diffPrice):$goods['shopPrice'];
					$pckMinStock = ($pckMinStock==0 || $goods['goodsStock']<$pckMinStock)?$goods['goodsStock']:$pckMinStock;
					$pkgShopPrice += $goods['shopPrice'];
			
					$goods["cnt"] = $pgoods["goodsCnt"];
					$goods["ischk"] = $pgoods["isCheck"];
					$totalMoney += $goods["cnt"]*$goods["shopPrice"];
					$cartgoods[$goods["shopId"]]["ischk"] = 1;
					$package["goods"][] = $goods;
			
					$cartgoods[$goods["shopId"]]["deliveryFreeMoney"] = $goods["deliveryFreeMoney"];//店铺免运费最低金额
					$cartgoods[$goods["shopId"]]["deliveryMoney"] = $goods["deliveryMoney"];//店铺配送费
					$cartgoods[$goods["shopId"]]["totalCnt"] = $cartgoods[$goods["shopId"]]["totalCnt"]+$cgoods["goodsCnt"];
					$cartgoods[$goods["shopId"]]["totalMoney"] = $cartgoods[$goods["shopId"]]["totalMoney"]+($goods["cnt"]*$goods["shopPrice"]);
				}

				$package["goodsStock"] = $pckMinStock;
				$package["shopPrice"] = $pkgShopPrice;
				$cartgoods[$goods["shopId"]]["packages"][] = $package;
			}
			
			$sql = "select * from __PREFIX__cart where userId = $userId and isCheck=1 and goodsCnt>0 and packageId=0";
			$shopcart = $this->query($sql);
			// 整理及核对购物车数据
			$cartIds = array();
			for($i=0;$i<count($shopcart);$i++){
				$cgoods = $shopcart[$i];
				$goodsId = (int)$cgoods["goodsId"];
				$goodsAttrId = (int)$cgoods["goodsAttrId"];
				
				$goods = $goodsmodel->getGoodsSimpInfo($goodsId,$goodsAttrId);
				//核对商品是否符合购买要求
				if(empty($goods)){
					$rd['msg'] = '找不到指定的商品v!';
					return $rd;
				}
				if($goods['goodsStock']<=0){
					$rd['msg'] = '对不起，商品'.$goods['goodsName'].'库存不足!';
					return $rd;
				}
				if($goods['isSale']!=1){
					$rd['msg'] = '对不起，商品库'.$goods['goodsName'].'已下架!';
					return $rd;
				}
				$goods["cnt"] = $cgoods["goodsCnt"];
				$cartgoods[$goods["shopId"]]["shopgoods"][] = $goods;
				$cartgoods[$goods["shopId"]]["deliveryFreeMoney"] = $goods["deliveryFreeMoney"];//店铺免运费最低金额
				$cartgoods[$goods["shopId"]]["deliveryMoney"] = $goods["deliveryMoney"];//店铺免运费最低金额
				$cartgoods[$goods["shopId"]]["totalCnt"] = $cartgoods[$goods["shopId"]]["totalCnt"]+$cgoods["goodsCnt"];
				$cartgoods[$goods["shopId"]]["totalMoney"] = $cartgoods[$goods["shopId"]]["totalMoney"]+($goods["cnt"]*$goods["shopPrice"]);
				$cartIds[] = $cgoods["cartId"];
				
			}
			$morders->startTrans();
            $coupid = I('coupid', 0);
			try{
				$ordersInfo = $this->addOrders($userId,$consigneeId,$payway,$needreceipt,$cartgoods,$orderunique,$isself,$coupid);
                $morders->commit();
				if(count($cartIds)>0){
					$sql = "delete from __PREFIX__cart where userId = $userId and cartId in (".implode(",",$cartIds).")";
					$this->execute($sql);
				}
				if(count($batchNos)>0){
					$sql = "delete from __PREFIX__cart where userId = $userId and batchNo in (".implode(",",$batchNos).")";
					$this->execute($sql);
				}
				$rd['orderIds'] = implode(",",$ordersInfo["orderIds"]);
				$rd['status'] = 1;
                $rd['code'] = $ordersInfo['code'];
                $rd['msg'] = $ordersInfo['msg'];
				session("WST_ORDER_UNIQUE",$orderunique);
			}catch(Exception $e){
				$morders->rollback();
				$rd['msg'] = '下单出错，请联系管理员!';
			}
			return $rd;
		}		
	}




    public function submitOrder_pt(){
        $rd = array('status'=>-1);
        $USER = session('WST_USER');
        $goodsmodel = D('Home/Goods');
        $morders = D('Home/Orders');
        $totalMoney = 0;
        $totalCnt = 0;
        $userId = (int)session('WST_USER.userId');
        $consigneeId = (int)I("consigneeId");
        $payway = (int)I("payway");
        $isself = (int)I("isself");
        $shopId = I("shopId");
        $coupid = I("coupid", 0);
        $goodsId = I("goodsId");
        $goodsInfo = M("goods")->where('goodsId='.$goodsId)->find();
        $addressInfo = UserAddressModel::getAddressDetails($consigneeId);
            $m = M('orderids');
            $deliveryInfo = M('shops')->field('deliveryMoney,deliveryFreeMoney')->where('shopId='.$shopId)->find();

            //生成订单ID
            $orderSrcNo = $m->add(array('rnd'=>time()));
            $orderNo = $orderSrcNo."".(fmod($orderSrcNo,7));
            //创建订单信息
            $data = array();
            $data["orderNo"] = $orderNo;
            $data["shopId"] = $shopId;
            if((int)I('leaderid') != 0){
                $data["leaderid"] = (int)I('leaderid');
                $data["buyerid"] = $userId; // 开团的人
            }else{
                $data["leaderid"] = $data["buyerid"] = $userId; // 开团的人
            }
            if(C('isDevelop')){
                WLog('333','ddddd', json_encode($data));
            }
            $data["goodsid"] = $goodsId;
            $data["orderFlag"] = 1;
            $data["totalMoney"] = I("goodsPrice");
            $deliverMoney = ($data["totalMoney"] < $deliveryInfo["deliveryFreeMoney"]) ? $deliveryInfo["deliveryMoney"] : 0;

            $data["deliverMoney"] = $deliverMoney; // 运费
            $data["payType"] = $payway;
            $data["deliverType"] = 1;  // '配送方式	0:商城配送 1:门店配送'

            $data["userName"] = $addressInfo["userName"];
            $data["areaId1"] = $addressInfo["areaId1"];
            $data["areaId2"] = $addressInfo["areaId2"];
            $data["areaId3"] = $addressInfo["areaId3"];
            $data["communityId"] = $addressInfo["communityId"];
            $data["userAddress"] = $addressInfo["paddress"]." ".$addressInfo["address"];
            $data["userTel"] = $addressInfo["userTel"];
            $data["userPhone"] = $addressInfo["userPhone"];

            $data['orderScore'] = floor(I('goodsPrice',0));
            $data["isInvoice"] = 0;
            $data["orderRemarks"] = '';
            $data["requireTime"] = I("requireTime");
            $data["invoiceClient"] = I("invoiceClient");
            $data["isAppraises"] = 0;
            $data["isSelf"] = $isself;

            $isScorePay = (int)I("isScorePay",0);
            $scoreMoney = 0;
            $useScore = 0;

            if($GLOBALS['CONFIG']['poundageRate']>0){
                $data["poundageRate"] = (float)$GLOBALS['CONFIG']['poundageRate'];
                $data["poundageMoney"] = WSTBCMoney($data["totalMoney"] * $data["poundageRate"] / 100,0,2);
            }else{
                $data["poundageRate"] = 0;
                $data["poundageMoney"] = 0;
            }

            $coupjian = M('coupon')->field('jian')->where('id='.$coupid)->find();
            $data["needPay"] = $data["totalMoney"] + $deliverMoney;
            $data["realTotalMoney"] = $data["totalMoney"] + $deliverMoney - $scoreMoney - $coupjian['jian'];
            $data["createTime"] = $data["startdate"] = date("Y-m-d H:i:s");
            $data["enddate"] = $goodsInfo['ptlastdate']; // 拼团结束时间
            if($payway==1 || $payway==3){
                $data["orderStatus"] = -2; // 下单后 为未支付订单
            }else{
                $data["orderStatus"] = -2;
            }

            $data["isPay"] = 0;
            if($data["needPay"]==0){
                $data["isPay"] = 1;
            }

            $mc = D('Admin/Coupon');
            $coupon = $mc->get($coupid);
            if($coupon['status'] == 1){
//                if(C('isDevelop')){WLog('401','1', $coupid);}
                $data['coupId'] = $coupid; // 使用的优惠券
            }

            $data['pintuanStatus'] = 1; // 拼团状态
            $morders_pt = M('orders_pintuan');
            if($goodsInfo['goodsStock'] > 0){
                $orderId = $morders_pt->add($data);
                if(C('isDevelop')){
                    WLog('999','sql', $morders_pt->getLastSql());
                    WLog('999','s', $orderId);
                }
                if($orderId){
                    // 修改库存
                    $sql="update __PREFIX__goods set goodsStock=(goodsStock-1) where goodsId=".$goodsId;
                    $this->execute($sql);
                    // 代金券状态
                    M('user_coup')->where(array('uid'=>$userId,'cid'=>$coupid))->save(array('status'=>0));
//                    $sql="update __PREFIX__users set userSignGold=(userSignGold-".$gold.") where userId=".$userId;
//                    $this->execute($sql);
                    // 支付

                    $ret['orderId'] = $orderId;
                    $ret['msg'] = '下单成功';
                    $ret['code'] = 1;
                }else{
                    $ret['msg'] = '下单失败';
                    $ret['code'] = -1;
                }
            }else{
                $ret['msg'] = '没有库存';
                $ret['code'] = 0;
            }

            return $ret;
    }

    /**
     * 删除失效拼团订单
     */
    public function delOrders(){
        M('orders_pintuan')->where('orderId='.I('oid'))->delete();
    }
	
	/**
	 * 生成订单
	 */
	public function addOrders($userId,$consigneeId,$payway,$needreceipt,$catgoods,$orderunique,$isself,$coupid){
		$orderInfos = array();
		$orderIds = array();
		$orderNos = array();
		$remarks = I("remarks");
		$addressInfo = UserAddressModel::getAddressDetails($consigneeId);
		foreach ($catgoods as $key=> $shopgoods){
			$m = M('orderids');
			//生成订单ID
			$orderSrcNo = $m->add(array('rnd'=>time()));
			$orderNo = $orderSrcNo."".(fmod($orderSrcNo,7));
			//创建订单信息
			$data = array();
			$packages = $shopgoods["packages"];
			$shopId = (int)$packages[0]["shopId"];
			$deliverType = intval($packages[0]["deliveryType"]);
			
			$pshopgoods = $shopgoods["shopgoods"];
			$shopId = ($shopId>0)?$shopId:($pshopgoods[0]["shopId"]);
			
			$data["orderNo"] = $orderNo;
			$data["shopId"] = $shopId;	
			$deliverType = ($deliverType>0)?$deliverType:intval($pshopgoods[0]["deliveryType"]);
			$data["userId"] = $userId;	
				
			$data["orderFlag"] = 1;
			$data["totalMoney"] = $shopgoods["totalMoney"];
			if($isself==1){//自提
				$deliverMoney = 0;
			}else{
				$deliverMoney = ($shopgoods["totalMoney"]<$shopgoods["deliveryFreeMoney"])?$shopgoods["deliveryMoney"]:0;
			}
			$data["deliverMoney"] = $deliverMoney;
			$data["payType"] = $payway;
			$data["deliverType"] = $deliverType;
			$data["userName"] = $addressInfo["userName"];
			$data["areaId1"] = $addressInfo["areaId1"];
			$data["areaId2"] = $addressInfo["areaId2"];
			$data["areaId3"] = $addressInfo["areaId3"];
			$data["communityId"] = $addressInfo["communityId"];
			$data["userAddress"] = $addressInfo["paddress"]." ".$addressInfo["address"];
			$data["userTel"] = $addressInfo["userTel"];
			$data["userPhone"] = $addressInfo["userPhone"];
			
			$data['orderScore'] = floor($data["totalMoney"]);
			$data["isInvoice"] = $needreceipt;
			$data["orderRemarks"] = $remarks;
			$data["requireTime"] = I("requireTime");
			$data["invoiceClient"] = I("invoiceClient");
			$data["isAppraises"] = 0;
			$data["isSelf"] = $isself;
			
			$isScorePay = (int)I("isScorePay",0);
			$scoreMoney = 0;
			$useScore = 0;

			if($GLOBALS['CONFIG']['poundageRate']>0){
				$data["poundageRate"] = (float)$GLOBALS['CONFIG']['poundageRate'];
				$data["poundageMoney"] = WSTBCMoney($data["totalMoney"] * $data["poundageRate"] / 100,0,2);
			}else{
				$data["poundageRate"] = 0;
				$data["poundageMoney"] = 0;
			}
			if($GLOBALS['CONFIG']['isOpenScorePay']==1 && $isScorePay==1){//积分支付
				$baseScore = WSTOrderScore();
				$baseMoney = WSTScoreMoney();
				$sql = "select userId,userScore from __PREFIX__users where userId=$userId";
				$user = $this->queryRow($sql);
				$useScore = $baseScore*floor($user["userScore"]/$baseScore);
				$scoreMoney = $baseMoney*floor($user["userScore"]/$baseScore);
				$orderTotalMoney = $shopgoods["totalMoney"]+$deliverMoney;
				if($orderTotalMoney<$scoreMoney){//订单金额小于积分金额
					$useScore = $baseScore*floor($orderTotalMoney/$baseMoney);
					$scoreMoney = $baseMoney*floor($orderTotalMoney/$baseMoney);
				}
				$data["useScore"] = $useScore;
				$data["scoreMoney"] = $scoreMoney;
			}

            $coupjian = M('coupon')->field('jian')->where('id='.$coupid)->find();
			// $data["realTotalMoney"] = $shopgoods["totalMoney"]+$deliverMoney - $scoreMoney;
            $data["realTotalMoney"] = $shopgoods["totalMoney"]+$deliverMoney - $scoreMoney - $coupjian['jian'];
			$data["needPay"] = $shopgoods["totalMoney"]+$deliverMoney - $scoreMoney;
			
			$data["createTime"] = date("Y-m-d H:i:s");
			if($payway==1 || $payway==3){
				$data["orderStatus"] = -2;
			}else{
				$data["orderStatus"] = 0;
			}
			
			$data["orderunique"] = $orderunique;
			$data["isPay"] = 0;
			if($data["needPay"]==0){
				$data["isPay"] = 1;
			}

            $mc = D('Admin/Coupon');
            $coupon = $mc->get($coupid);
            if($coupon['status'] == 1){
                if(C('isDevelop')){WLog($key, '1', $coupon['status']);}
                $data['coupId'] = $coupid; // 使用的优惠券
            }
			$morders = M('orders');
			$orderId = $morders->add($data);
			M('user_coup')->where(array('uid'=>$userId,'cid'=>$coupid))->save(array('status'=>0));
            if(C('isDevelop')){WLog('coupstatus','sql',M('user_coup')->getLastSql());}
            // 余额支付
            /**
            if($payway == 3){
                $payResult = $this->payYue();
                if($payResult['code'] == 1){ // 支付成功
                    $res = $morders->where(array('orderId'=>$orderId))->save(array('orderStatus'=>1)); // 修改订单状态
                }
            }
            */


			//订单创建成功则建立相关记录
			if($orderId>0){
				if($GLOBALS['CONFIG']['isOpenScorePay']==1 && $isScorePay==1 && $useScore>0){//积分支付
					$sql = "UPDATE __PREFIX__users set userScore=userScore-".$useScore." WHERE userId=".$userId;
					$rs = $this->execute($sql);
					$data = array();
					$m = M('user_score');
					$data["userId"] = $userId;
					$data["score"] = $useScore;
					$data["dataSrc"] = 1;
					$data["dataId"] = $orderId;
					$data["dataRemarks"] = "订单支付-扣积分";
					$data["scoreType"] = 2;
					$data["createTime"] = date('Y-m-d H:i:s');
					$m->add($data);
				}
				$orderIds[] = $orderId;
				//建立订单商品记录表
				$mog = M('order_goods');
				foreach ($packages as $key=> $package){
					foreach ($package['goods'] as $key2=> $sgoods){
						$data = array();
						$data["orderId"] = $orderId;
						$data["goodsId"] = $sgoods["goodsId"];
						$data["goodsAttrId"] = (int)$sgoods["goodsAttrId"];
						if($sgoods["attrVal"]!='')$data["goodsAttrName"] = $sgoods["attrName"].":".$sgoods["attrVal"];
						$data["goodsNums"] = $sgoods["cnt"];
						$data["goodsPrice"] = $sgoods["shopPrice"];
						$data["goodsName"] = $sgoods["goodsName"];
						$data["goodsThums"] = $sgoods["goodsThums"];
						$mog->add($data);
					}
				}
				
				foreach ($pshopgoods as $key=> $sgoods){
					$data = array();
					$data["orderId"] = $orderId;
					$data["goodsId"] = $sgoods["goodsId"];
					$data["goodsAttrId"] = (int)$sgoods["goodsAttrId"];
					if($sgoods["attrVal"]!='')$data["goodsAttrName"] = $sgoods["attrName"].":".$sgoods["attrVal"];
					$data["goodsNums"] = $sgoods["cnt"];
					$data["goodsPrice"] = $sgoods["shopPrice"];
					$data["goodsName"] = $sgoods["goodsName"];
					$data["goodsThums"] = $sgoods["goodsThums"];
					$mog->add($data);
				}
			
				if($payway==0 || $payway==3){ // 货到付款或者余额支付
					//建立订单记录
					$data = array();
					$data["orderId"] = $orderId;

//                    if($payway == 3){
//                        if($payResult['code'] == 0){
//                            $data["logContent"] = "下单成功，用户余额不足，支付失败";
//                        }elseif($payResult['code'] == 1){
//                            $data["logContent"] = "下单成功，用户余额支付";
//                        }else{
//                            $data["logContent"] = "下单成功，支付失败";
//                        }
//                    }else{
                        $data["logContent"] = ($deliverType==0)? "下单成功":"下单成功等待支付";
//                    }
					$data["logUserId"] = $userId;
					$data["logType"] = 0;
					$data["logTime"] = date('Y-m-d H:i:s');
					$mlogo = M('log_orders');
					$mlogo->add($data);
					//建立订单提醒
					$sql ="SELECT userId,shopId,shopName FROM __PREFIX__shops WHERE shopId=$shopId AND shopFlag=1  ";
					$users = $this->query($sql);
					$morm = M('order_reminds');
					for($i=0;$i<count($users);$i++){
						$data = array();
						$data["orderId"] = $orderId;
						$data["shopId"] = $shopId;
						$data["userId"] = $users[$i]["userId"];
						$data["userType"] = 0;
						$data["remindType"] = 0;
						$data["createTime"] = date("Y-m-d H:i:s");
						$morm->add($data);
					}
					
					//修改库存
					foreach ($packages as $key=> $package){
						foreach ($package['goods'] as $key2=> $sgoods){
							$sql="update __PREFIX__goods set goodsStock=goodsStock-".$sgoods['cnt']." where goodsId=".$sgoods["goodsId"];
							$this->execute($sql);
							if((int)$sgoods["goodsAttrId"]>0){
								$sql="update __PREFIX__goods_attributes set attrStock=attrStock-".$sgoods['cnt']." where id=".$sgoods["goodsAttrId"];
								$this->execute($sql);
							}
						}
					}
					
					foreach ($pshopgoods as $key=> $sgoods){
						$sql="update __PREFIX__goods set goodsStock=goodsStock-".$sgoods['cnt']." where goodsId=".$sgoods["goodsId"];
						$this->execute($sql);
						if((int)$sgoods["goodsAttrId"]>0){
							$sql="update __PREFIX__goods_attributes set attrStock=attrStock-".$sgoods['cnt']." where id=".$sgoods["goodsAttrId"];
							$this->execute($sql);
						}
					}
					
				}
				else{
					$data = array();
					$data["orderId"] = $orderId;
					$data["logContent"] = "订单已提交，等待支付";
					$data["logUserId"] = $userId;
					$data["logType"] = 0;
					$data["logTime"] = date('Y-m-d H:i:s');
					$mlogo = M('log_orders');
					$mlogo->add($data);
				}
			}
		}
        $payResult['orderIds'] = $orderIds;
		return $payResult;
		
	}

    /**
     * 余额支付
     */
    public function payYue(){
        $datas = array();
        $uid = (int)session('WST_USER.userId');
        $uMoney = M('users')->where(array('userId'=>$uid))->getField('userMoney');

        if($uMoney < (int)I('totalMoney')){
            $datas['msg'] = '余额不足，请用微信支付！';
            $datas['code'] = 0;
        }elseif($uMoney >= (int)I('totalMoney')){
            $res = $this->setUserMoney($uid,$uMoney,I('totalMoney'));
            if($res){
                $datas['msg'] = '支付成功！';
                $datas['code'] = 1;
            }else{
                $datas['msg'] = '支付失败，请稍后重试！';
                $datas['code'] = 2;
            }
        }
       return $datas;
    }
	
	/**
	 * 获取订单参数
	 */
	public function getOrderListByIds(){
		 $orderunique = session("WST_ORDER_UNIQUE");
		 $orderInfos = array('totalMoney'=>0,'isMoreOrder'=>0,'list'=>array());
		 $sql = "select orderId,orderNo,totalMoney,deliverMoney,realTotalMoney
		         from __PREFIX__orders where userId=".(int)session('WST_USER.userId')." 
		         and orderunique='".$orderunique."' and orderFlag=1 ";
	     $rs = $this->query($sql);
	     if(!empty($rs)){
	     	$totalMoney = 0;
	     	$realTotalMoney = 0;
	     	foreach ($rs as $key =>$v){
	     		$orderInfos['list'][] = array('orderId'=>$v['orderId'],'orderNo'=>$v['orderNo']);
	     		$totalMoney += $v['totalMoney'] + $v['deliverMoney'];
	     		$realTotalMoney += $v['realTotalMoney'];
	     	}
	     	$orderInfos['totalMoney'] = $totalMoney;
	     	$orderInfos['realTotalMoney'] = $realTotalMoney;
	     	$orderInfos['isMoreOrder'] = (count($rs)>0)?1:0;
	     }
	     return $orderInfos;
	}
	
	/**
	 * 获取待付款订单
	 */
	public function queryByPage($obj){
		$userId = $obj["userId"];
		$pcurr = (int)I("pcurr",0);
		$sql = "SELECT o.* FROM __PREFIX__orders o
				WHERE userId = $userId AND orderFlag=1 order by orderId desc";
		$pages = $this->pageQuery($sql,$pcurr);	
		$orderList = $pages["root"];
		if(count($orderList)>0){
			$orderIds = array();
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$orderIds[] = $order["orderId"];
			}
			//获取涉及的商品
	        $sql = "SELECT og.goodsId,og.goodsName,og.goodsThums,og.orderId FROM __PREFIX__order_goods og
					WHERE og.orderId in (".implode(',',$orderIds).")";	
	        $glist = $this->query($sql);
			$goodslist = array();
			for($i=0;$i<count($glist);$i++){
				$goods = $glist[$i];
				$goodslist[$goods["orderId"]][] = $goods;
			}
			//放回分页数据里
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$order["goodslist"] = $goodslist[$order['orderId']];
				$pages["root"][$i] = $order;
			}
		}
		return $pages;
	}

	/**
	 * 获取待付款订单
	 */
	public function queryPayByPage($obj){
		$userId = (int)$obj["userId"];
		$orderNo = WSTAddslashes(I("orderNo"));
		$orderStatus = (int)I("orderStatus",0);
		$goodsName = WSTAddslashes(I("goodsName"));
		$shopName = WSTAddslashes(I("shopName"));
		$userName = WSTAddslashes(I("userName"));
		$pcurr = (int)I("pcurr",0);
//		$sql = "SELECT o.orderId,o.orderNo,o.shopId,o.orderStatus,o.userName,o.totalMoney,o.realTotalMoney,
//		        o.createTime,o.payType,o.isRefund,o.isAppraises,sp.shopName
//		        FROM __PREFIX__orders o,__PREFIX__shops sp
//		        WHERE o.orderFlag=1 and o.userId = $userId AND o.orderStatus =-2 AND o.isPay = 0 AND o.payType = 1 AND o.shopId=sp.shopId ";

        $sql = "SELECT o.orderId,o.orderNo,o.shopId,o.orderStatus,o.userName,o.totalMoney,o.realTotalMoney,o.deliverMoney,
		        o.createTime,o.payType,o.isRefund,o.isAppraises,sp.shopName 
		        FROM __PREFIX__orders o,__PREFIX__shops sp 
		        WHERE o.orderFlag=1 and o.userId = $userId AND o.orderStatus =-2 AND o.isPay = 0 AND o.shopId=sp.shopId ";
        if($orderNo!=""){
			$sql .= " AND o.orderNo like '%$orderNo%'";
		}
		if($userName!=""){
			$sql .= " AND o.userName like '%$userName%'";
		}
		if($shopName!=""){
			$sql .= " AND sp.shopName like '%$shopName%'";
		}
		$sql .= " order by o.orderId desc";
		$pages = $this->pageQuery($sql,$pcurr);	
		$orderList = $pages["root"];
		if(count($orderList)>0){
			$orderIds = array();
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$orderIds[] = $order["orderId"];
			}
			//获取涉及的商品
	        $sql = "SELECT og.goodsId,og.goodsName,og.goodsThums,og.orderId,og.goodsPrice,og.goodsNums FROM __PREFIX__order_goods og
					WHERE og.orderId in (".implode(',',$orderIds).")";	
			$glist = $this->query($sql);
			$goodslist = array();
			for($i=0;$i<count($glist);$i++){
				$goods = $glist[$i];
				$goodslist[$goods["orderId"]][] = $goods;
			}
			//放回分页数据里
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$order["goodslist"] = $goodslist[$order['orderId']];
				$pages["root"][$i] = $order;
			}
		}
		return $pages;
	}
	
	
	
	/**
	 * 获取待确认收货
	 */
	public function queryReceiveByPage($obj){
		$userId = (int)$obj["userId"];
		$orderNo = WSTAddslashes(I("orderNo"));
		$orderStatus = (int)I("orderStatus",0);
		$goodsName = WSTAddslashes(I("goodsName"));
		$shopName = WSTAddslashes(I("shopName"));
		$userName = WSTAddslashes(I("userName"));
		$pcurr = (int)I("pcurr",0);

		$sql = "SELECT o.orderId,o.orderNo,o.shopId,o.orderStatus,o.userName,o.totalMoney,o.realTotalMoney,o.deliverMoney,
		        o.createTime,o.payType,o.isRefund,o.isAppraises,sp.shopName 
		        FROM __PREFIX__orders o,__PREFIX__shops sp WHERE o.orderFlag=1 and o.userId = $userId AND o.orderStatus =3 AND o.shopId=sp.shopId ";
		if($orderNo!=""){
			$sql .= " AND o.orderNo like '%$orderNo%'";
		}
		if($userName!=""){
			$sql .= " AND o.userName like '%$userName%'";
		}
		if($shopName!=""){
			$sql .= " AND sp.shopName like '%$shopName%'";
		}
		$sql .= " order by o.orderId desc";
		$pages = $this->pageQuery($sql,$pcurr);	
		$orderList = $pages["root"];
		if(count($orderList)>0){
			$orderIds = array();
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$orderIds[] = $order["orderId"];
			}
			//获取涉及的商品
	        $sql = "SELECT og.goodsId,og.goodsName,og.goodsThums,og.orderId,og.goodsNums,og.goodsPrice FROM __PREFIX__order_goods og
					WHERE og.orderId in (".implode(',',$orderIds).")";	
			$glist = $this->query($sql);
			$goodslist = array();
			for($i=0;$i<count($glist);$i++){
				$goods = $glist[$i];
				$goodslist[$goods["orderId"]][] = $goods;
			}
			//放回分页数据里
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$order["goodslist"] = $goodslist[$order['orderId']];
				$pages["root"][$i] = $order;
			}
		}
		return $pages;
	}
    /**
	 * 获取待发货订单
	 */
	public function queryDeliveryByPage($obj){
		$userId = (int)$obj["userId"];
		$orderNo = WSTAddslashes(I("orderNo"));
		$orderStatus = (int)I("orderStatus",0);
		$goodsName = WSTAddslashes(I("goodsName"));
		$shopName = WSTAddslashes(I("shopName"));
		$userName = WSTAddslashes(I("userName"));
		$pcurr = (int)I("pcurr",0);

		$sql = "SELECT o.orderId,o.orderNo,o.shopId,o.orderStatus,o.userName,o.totalMoney,o.realTotalMoney, o.deliverMoney,
		        o.createTime,o.payType,o.isRefund,o.isAppraises,sp.shopName 
		        FROM __PREFIX__orders o,__PREFIX__shops sp 
		        WHERE o.orderFlag=1 and o.userId = $userId AND o.payType!=9 AND o.orderStatus in ( 0,1,2 ) AND o.shopId=sp.shopId ";
		if($orderNo!=""){
			$sql .= " AND o.orderNo like '%$orderNo%'";
		}
		if($userName!=""){
			$sql .= " AND o.userName like '%$userName%'";
		}
		if($shopName!=""){
			$sql .= " AND sp.shopName like '%$shopName%'";
		}
		$sql .= " order by o.orderId desc";
		$pages = $this->pageQuery($sql,$pcurr);
		$orderList = $pages["root"];
		if(count($orderList)>0){
			$orderIds = array();
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$orderIds[] = $order["orderId"];
			}
			//获取涉及的商品
	        $sql = "SELECT og.goodsId,og.goodsName,og.goodsThums,og.orderId,og.goodsNums,og.goodsPrice FROM __PREFIX__order_goods og
					WHERE og.orderId in (".implode(',',$orderIds).")";	
			$glist = $this->query($sql);
			$goodslist = array();
			for($i=0;$i<count($glist);$i++){
				$goods = $glist[$i];
				$goodslist[$goods["orderId"]][] = $goods;
			}
			//放回分页数据里
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$order["goodslist"] = $goodslist[$order['orderId']];
				$pages["root"][$i] = $order;
			}
		}
		return $pages;
	}
    /**
	 * 获取退款
	 */
	public function queryRefundByPage($obj){
		$userId = (int)$obj["userId"];
		$orderNo = WSTAddslashes(I("orderNo"));
		$orderStatus = (int)I("orderStatus",0);
		$goodsName = WSTAddslashes(I("goodsName"));
		$shopName = WSTAddslashes(I("shopName"));
		$userName = WSTAddslashes(I("userName"));
		$sdate = WSTAddslashes(I("sdate"));
		$edate = WSTAddslashes(I("edate"));
		$pcurr = (int)I("pcurr",0);
		//必须是在线支付的才允许退款

		$sql = "SELECT o.orderId,o.orderNo,o.shopId,o.orderStatus,o.userName,o.totalMoney,o.realTotalMoney,o.deliverMoney,
		        o.createTime,o.payType,o.isRefund,o.isAppraises,sp.shopName ,oc.complainId
		        FROM __PREFIX__orders o left join __PREFIX__order_complains oc on oc.orderId=o.orderId,__PREFIX__shops sp 
		        WHERE o.orderFlag=1 and o.userId = $userId AND (o.orderStatus in (-3,-4,-5,5) or (o.orderStatus in (-1,-4,-6,-7) and payType =1 AND o.isPay =1)) AND o.shopId=sp.shopId ";
		if($orderNo!=""){
			$sql .= " AND o.orderNo like '%$orderNo%'";
		}
		if($userName!=""){
			$sql .= " AND o.userName like '%$userName%'";
		}
		if($shopName!=""){
			$sql .= " AND sp.shopName like '%$shopName%'";
		}
		$sql .= " order by o.orderId desc";
		$pages = $this->pageQuery($sql,$pcurr);	
		$orderList = $pages["root"];
		if(count($orderList)>0){
			$orderIds = array();
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$orderIds[] = $order["orderId"];
			}
			//获取涉及的商品
	        $sql = "SELECT og.goodsId,og.goodsName,og.goodsThums,og.orderId,og.goodsNums,og.goodsPrice FROM __PREFIX__order_goods og
					WHERE og.orderId in (".implode(',',$orderIds).")";	
			$glist = $this->query($sql);
			$goodslist = array();
			for($i=0;$i<count($glist);$i++){
				$goods = $glist[$i];
				$goodslist[$goods["orderId"]][] = $goods;
			}
			//放回分页数据里
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$order["goodslist"] = $goodslist[$order['orderId']];
				$pages["root"][$i] = $order;
			}
		}
		return $pages;
	}
	
	/**
	 * 获取取消的订单
	 */
	public function queryCancelOrders($obj){
		$userId = (int)$obj["userId"];
		$orderNo = WSTAddslashes(I("orderNo"));
		$orderStatus = (int)I("orderStatus",0);
		$goodsName = WSTAddslashes(I("goodsName"));
		$shopName = WSTAddslashes(I("shopName"));
		$userName = WSTAddslashes(I("userName"));
		$pcurr = (int)I("pcurr",0);

		$sql = "SELECT o.orderId,o.orderNo,o.shopId,o.orderStatus,o.userName,o.totalMoney,o.realTotalMoney,
		        o.createTime,o.payType,o.isRefund,o.isAppraises,sp.shopName 
		        FROM __PREFIX__orders o,__PREFIX__shops sp 
		        WHERE o.orderFlag=1 and o.userId = $userId AND o.orderStatus in (-1,-6,-7) AND o.shopId=sp.shopId ";
		if($orderNo!=""){
			$sql .= " AND o.orderNo like '%$orderNo%'";
		}
		if($userName!=""){
			$sql .= " AND o.userName like '%$userName%'";
		}
		if($shopName!=""){
			$sql .= " AND sp.shopName like '%$shopName%'";
		}
		$sql .= " order by o.orderId desc";
		$pages = $this->pageQuery($sql,$pcurr);	
		$orderList = $pages["root"];
		if(count($orderList)>0){
			$orderIds = array();
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$orderIds[] = $order["orderId"];
			}
			//获取涉及的商品
	        $sql = "SELECT og.goodsId,og.goodsName,og.goodsThums,og.orderId FROM __PREFIX__order_goods og
					WHERE og.orderId in (".implode(',',$orderIds).")";	
			$glist = $this->query($sql);
			$goodslist = array();
			for($i=0;$i<count($glist);$i++){
				$goods = $glist[$i];
				$goodslist[$goods["orderId"]][] = $goods;
			}
			//放回分页数据里
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$order["goodslist"] = $goodslist[$order['orderId']];
				$pages["root"][$i] = $order;
			}
		}
		return $pages;
	}
	
	/**
	 * 获取待评价交易
	 */
	public function queryAppraiseByPage($obj){
		$userId = (int)$obj["userId"];
		$orderNo = WSTAddslashes(I("orderNo"));
		$goodsName = WSTAddslashes(I("goodsName"));
		$shopName = WSTAddslashes(I("shopName"));
		$userName = WSTAddslashes(I("userName"));
		$pcurr = (int)I("pcurr",0);
		$sql = "SELECT o.orderId,o.orderNo,o.shopId,o.orderStatus,o.userName,o.totalMoney,o.realTotalMoney,o.deliverMoney,
		        o.createTime,o.payType,o.isRefund,o.isAppraises,sp.shopName ,oc.complainId
		        FROM __PREFIX__orders o left join __PREFIX__order_complains oc on oc.orderId=o.orderId,__PREFIX__shops sp WHERE o.orderFlag=1 and o.isAppraises=0 and o.userId = $userId AND o.shopId=sp.shopId ";	
		if($orderNo!=""){
			$sql .= " AND o.orderNo like '%$orderNo%'";
		}
		if($userName!=""){
			$sql .= " AND o.userName like '%$userName%'";
		}
		if($shopName!=""){
			$sql .= " AND sp.shopName like '%$shopName%'";
		}
		$sql .= " AND o.orderStatus = 4";
		$sql .= " order by o.orderId desc";
		$pages = $this->pageQuery($sql,$pcurr);	
		$orderList = $pages["root"];
		if(count($orderList)>0){
			$orderIds = array();
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$orderIds[] = $order["orderId"];
			}
			//获取涉及的商品
	        $sql = "SELECT og.goodsId,og.goodsName,og.goodsThums,og.orderId,og.goodsPrice,og.goodsNums FROM __PREFIX__order_goods og
					WHERE og.orderId in (".implode(',',$orderIds).")";	
			$glist = $this->query($sql);
			$goodslist = array();
			for($i=0;$i<count($glist);$i++){
				$goods = $glist[$i];
				$goodslist[$goods["orderId"]][] = $goods;
			}
			//放回分页数据里
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$order["goodslist"] = $goodslist[$order['orderId']];
				$pages["root"][$i] = $order;
			}
		}
		return $pages;
	}
	
	/**
	 * 获取已完成交易
	 */
	public function queryCompleteOrders($obj){
		$userId = (int)$obj["userId"];
		$orderNo = WSTAddslashes(I("orderNo"));
		$goodsName = WSTAddslashes(I("goodsName"));
		$shopName = WSTAddslashes(I("shopName"));
		$userName = WSTAddslashes(I("userName"));
		$pcurr = (int)I("pcurr",0);
		$sql = "SELECT o.orderId,o.orderNo,o.shopId,o.orderStatus,o.userName,o.totalMoney,o.realTotalMoney,
				o.createTime,o.payType,o.isRefund,o.isAppraises,sp.shopName ,oc.complainId
				FROM __PREFIX__orders o left join __PREFIX__order_complains oc on oc.orderId=o.orderId,__PREFIX__shops sp WHERE o.orderFlag=1 and o.isAppraises=1 and o.userId = $userId AND o.shopId=sp.shopId ";
		if($orderNo!=""){
		$sql .= " AND o.orderNo like '%$orderNo%'";
		}
		if($userName!=""){
			$sql .= " AND o.userName like '%$userName%'";
		}
		if($shopName!=""){
			$sql .= " AND sp.shopName like '%$shopName%'";
		}
		$sql .= " AND o.orderStatus = 4";
		$sql .= " order by o.orderId desc";
		$pages = $this->pageQuery($sql,$pcurr);
		$orderList = $pages["root"];
		if(count($orderList)>0){
			$orderIds = array();
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$orderIds[] = $order["orderId"];
			}
			//获取涉及的商品
			$sql = "SELECT og.goodsId,og.goodsName,og.goodsThums,og.orderId FROM __PREFIX__order_goods og
					WHERE og.orderId in (".implode(',',$orderIds).")";
			$glist = $this->query($sql);
			$goodslist = array();
			for($i=0;$i<count($glist);$i++){
				$goods = $glist[$i];
				$goodslist[$goods["orderId"]][] = $goods;
			}
			//放回分页数据里
			for($i=0;$i<count($orderList);$i++){
				$order = $orderList[$i];
				$order["goodslist"] = $goodslist[$order['orderId']];
				$pages["root"][$i] = $order;
			}
		}
		return $pages;
	}
	
	/**
	 * 取消订单
	 */
	public function orderCancel($obj){		
		$userId = (int)$obj["userId"];
		$orderId = (int)$obj["orderId"];
		$rsdata = array('status'=>-1);
		//判断订单状态，只有符合状态的订单才允许改变
		$sql = "SELECT orderId,orderNo,orderStatus,useScore FROM __PREFIX__orders WHERE orderFlag=1 and orderId = $orderId and orderFlag = 1 and userId=".$userId;		
		$rsv = $this->queryRow($sql);
		$cancelStatus = array(0,1,2,-2);//未受理,已受理,打包中,待付款订单
		if(!in_array($rsv["orderStatus"], $cancelStatus))return $rsdata;
		//如果是未受理和待付款的订单直接改为"用户取消【受理前】"，已受理和打包中的则要改成"用户取消【受理后-商家未知】"，后者要给商家知道有这么一回事，然后再改成"用户取消【受理后-商家已知】"的状态
		$orderStatus = -6;//取对商家影响最小的状态
		if($rsv["orderStatus"]==0 || $rsv["orderStatus"]==-2)$orderStatus = -1;
		if($orderStatus==-6 && I('rejectionRemarks')=='')return $rsdata;//如果是受理后取消需要有原因
		$sql = "UPDATE __PREFIX__orders set orderStatus = ".$orderStatus." WHERE orderFlag=1 and orderId = $orderId and userId=".$userId;	
		$rs = $this->execute($sql);		
		
		$sql = "select ord.deliverType, ord.orderId, og.goodsId ,og.goodsId, og.goodsNums 
				from __PREFIX__orders ord , __PREFIX__order_goods og 
				WHERE ord.orderFlag=1 and ord.orderId = og.orderId AND ord.orderId = $orderId";
		$ogoodsList = $this->query($sql);
		//获取商品库存
		for($i=0;$i<count($ogoodsList);$i++){
			$sgoods = $ogoodsList[$i];
			$sql="update __PREFIX__goods set goodsStock=goodsStock+".$sgoods['goodsNums']." where goodsId=".$sgoods["goodsId"];
			$this->execute($sql);
		}
		$sql="Delete From __PREFIX__order_reminds where orderId=".$orderId." AND remindType=0";
		$this->execute($sql);
		
		if($rsv["useScore"]>0){
			$sql = "UPDATE __PREFIX__users set userScore=userScore+".$rsv["useScore"]." WHERE userId=".$userId;
			$this->execute($sql);
			
			$data = array();
			$m = M('user_score');
			$data["userId"] = $userId;
			$data["score"] = $rsv["useScore"];
			$data["dataSrc"] = 3;
			$data["dataId"] = $orderId;
			$data["dataRemarks"] = "取消订单返还";
			$data["scoreType"] = 1;
			$data["createTime"] = date('Y-m-d H:i:s');
			$m->add($data);
		}
		$data = array();
		$m = M('log_orders');
		$data["orderId"] = $orderId;
		$data["logContent"] = "用户已取消订单".(($orderStatus==-6)?"：".I('rejectionRemarks'):"");
		$data["logUserId"] = $userId;
		$data["logType"] = 0;
		$data["logTime"] = date('Y-m-d H:i:s');
		$ra = $m->add($data);
		$rsdata["status"] = $ra;
		return $rsdata;
		
	}
	/**
	 * 用户确认收货
	 */
	public function orderConfirm ($obj){		
		$userId = (int)$obj["userId"];
		$orderId = (int)$obj["orderId"];
		$type = (int)$obj["type"];
		$rsdata = array();
		$sql = "SELECT orderId,orderNo,orderScore,orderStatus,poundageRate,poundageMoney,shopId,useScore,scoreMoney FROM __PREFIX__orders WHERE orderFlag=1 and orderId = $orderId and userId=".$userId;		
		$rsv = $this->queryRow($sql);
		if($rsv["orderStatus"]!=3){
			$rsdata["status"] = -1;
			return $rsdata;
		}		
        //收货则给用户增加积分
        if($type==1){
        	$sql = "UPDATE __PREFIX__orders set orderStatus = 4,receiveTime='".date("Y-m-d H:i:s")."'  WHERE orderFlag=1 and orderId = $orderId and userId=".$userId;			
        	$this->execute($sql);
        	//修改商品销量
        	$sql = "select og.goodsId, sum(og.goodsNums) gcnt FROM __PREFIX__order_goods og, __PREFIX__orders o WHERE o.orderFlag=1 and og.orderId = o.orderId AND o.orderId=$orderId AND o.userId=".$userId." group by og.goodsId";
        	$ogoods = $this->query($sql);
        	for ($i = 0; $i < count($ogoods); $i++) {
        		$row = $ogoods[$i];
        		$sql = "UPDATE __PREFIX__goods SET saleCount=saleCount+".$row['gcnt']." WHERE goodsId=".$row['goodsId'];
        		$this->execute($sql);
        	}
        	// ADD BY YANG  // 推荐人收益
            $sql_fx = "SELECT valueRange FROM __PREFIX__sys_configs WHERE fieldCode = 'fx' ";
            $res = $this->queryRow($sql_fx);
            // 分销是否开启
            if($res['valueRange'] == 1){
                WLog('fenxiao', '1021' , '111');
                $sql_yiji = "SELECT fieldValue FROM __PREFIX__sys_configs WHERE fieldCode = 'fx_1' "; $sql_erji = "SELECT fieldValue FROM __PREFIX__sys_configs WHERE fieldCode = 'fx_2' "; $sql_sanji = "SELECT fieldValue FROM __PREFIX__sys_configs WHERE fieldCode = 'fx_3' ";
                $yi = $this->queryRow($sql_yiji);  $er = $this->queryRow($sql_erji);  $san = $this->queryRow($sql_sanji);
                $yiji = $yi['fieldValue']; $erji = $er['fieldValue']; $sanji = $san['fieldValue'];
                WLog('fenxiao', 'yi' , $yi);
                $sql_goods = "SELECT g.goodsRate,g.shopPrice FROM `fys_goods` AS g LEFT JOIN `fys_order_goods` AS og ON g.goodsId=og.goodsId WHERE og.orderId=".$orderId;
                $goodsRate = $this->queryRow($sql_goods);
                $tjshouyi1 = $goodsRate['shopPrice']*($goodsRate['goodsRate']/100) * ($yiji/100);
                $tjshouyi2 = $goodsRate['shopPrice']*($goodsRate['goodsRate']/100) * ($erji/100);
                $tjshouyi3 = $goodsRate['shopPrice']*($goodsRate['goodsRate']/100) * ($sanji/100);
                // 推荐人获得收益
//                $sql_tjid1 = "SELECT tjrId,tjshouyi1,tjshouyi2,tjshouyi3 FROM __PREFIX__users WHERE userId=".$userId;
//                $tjInfos = $this->queryRow($sql_tjid1);
//                $sql_tj1 = "UPDATE __PREFIX__users set tjshouyi1=".$tjInfos['tjshouyi1']."+".$tjshouyi1." WHERE userId=".$userId;
//                $this->execute($sql_tj1);
//                WLog('fenxiao', 'tjinfos1036', $tjInfos); WLog('fenxiao', 'sql_tj1', $sql_tj1);
//                $sql_tj3 = "UPDATE __PREFIX__users set tjshouyi3=tjshouyi3+".$tjshouyi3." WHERE userId=(SELECT tjrId FROM __PREFIX__users WHERE userId=".$userId.")";
//                $this->execute($sql_tj3);
                $sql_tjid1 = "SELECT tjrId,tjshouyi1,tjshouyi2,tjshouyi3 FROM __PREFIX__users WHERE userId=".$userId;
                $tjInfos = $this->queryRow($sql_tjid1);
                $userInfo3 = M('users')->where(array('userId'=>$tjInfos['tjrId']))->field('tjrId')->find();
                M('users')->where(array('userId'=>$userId))->setInc('tjshouyi1',$tjshouyi1);  // 一级
                M('users')->where(array('userId'=>$tjInfos['tjrId']))->setInc('tjshouyi2',$tjshouyi2); // 二级
                M('users')->where(array('userId'=>$userInfo3['tjrId']))->setInc('tjshouyi3',$tjshouyi3); // 三级
//                WLog('fenxiao', 'sql22' , M('users')->getLastSql());
//                WLog('fenxiao', 'userInfo3' , $userInfo3);
                //  更新缓存中用户信息
                // session('WST_USER',$us);
            }

        	//修改积分
        	if($GLOBALS['CONFIG']['isOrderScore']==1 && $rsv["orderScore"]>0){
	        	$sql = "UPDATE __PREFIX__users set userScore=userScore+".$rsv["orderScore"].",userTotalScore=userTotalScore+".$rsv["orderScore"]." WHERE userId=".$userId;
	        	$this->execute($sql);
	        	
	        	$data = array();
	        	$m = M('user_score');
	        	$data["userId"] = $userId;
	        	$data["score"] = $rsv["orderScore"];
	        	$data["dataSrc"] = 1;
	        	$data["dataId"] = $orderId;
	        	$data["dataRemarks"] = "交易获得";
	        	$data["scoreType"] = 1;
	        	$data["createTime"] = date('Y-m-d H:i:s');
	        	$m->add($data);
        	}
        	//积分支付支出
        	if($rsv["scoreMoney"]>0){
        		$data = array();
        		$m = M('log_sys_moneys');
        		$data["targetType"] = 0;
        		$data["targetId"] = $userId;
        		$data["dataSrc"] = 2;
        		$data["dataId"] = $orderId;
        		$data["moneyRemark"] = "订单【".$rsv["orderNo"]."】支付 ".$rsv["useScore"]." 个积分，支出 ￥".$rsv["scoreMoney"];
        		$data["moneyType"] = 2;
        		$data["money"] = $rsv["scoreMoney"];
        		$data["createTime"] = date('Y-m-d H:i:s');
        		$data["dataFlag"] = 1;
        		$m->add($data);
        	}
        	//收取订单佣金
        	if($rsv["poundageMoney"]>0){
        		$data = array();
        		$m = M('log_sys_moneys');
        		$data["targetType"] = 1;
        		$data["targetId"] = $rsv["shopId"];
        		$data["dataSrc"] = 1;
        		$data["dataId"] = $orderId;
        		$data["moneyRemark"] = "收取订单【".$rsv["orderNo"]."】".$rsv["poundageRate"]."%的佣金 ￥".$rsv["poundageMoney"];
        		$data["moneyType"] = 1;
        		$data["money"] = $rsv["poundageMoney"];
        		$data["createTime"] = date('Y-m-d H:i:s');
        		$data["dataFlag"] = 1;
        		$m->add($data);
        	}
        	
        }else{
        	if(I('rejectionRemarks')=='')return $rsdata;//如果是拒收的话需要填写原因
        	$sql = "UPDATE __PREFIX__orders set orderStatus = -3 WHERE orderFlag=1 and orderId = $orderId and userId=".$userId;			
        	$this->execute($sql);
        }
        //增加记录
		$data = array();
		$m = M('log_orders');
		$data["orderId"] = $orderId;
		$data["logContent"] = ($type==1)?"用户已收货":"用户拒收：".I('rejectionRemarks');
		$data["logUserId"] = $userId;
		$data["logType"] = 0;
		$data["logTime"] = date('Y-m-d H:i:s');
		$ra = $m->add($data);
		$rsdata["status"] = $ra;;
		return $rsdata;
	}
	
    /**
     * 获取订单详情
     */
	public function getOrderDetails($obj){
		$userId = (int)$obj["userId"];
		$shopId = (int)$obj["shopId"];
		$orderId = (int)$obj["orderId"];
		$data = array();
		$sql = "SELECT * FROM __PREFIX__orders WHERE orderFlag=1 and orderId = $orderId and (userId=".$userId." or shopId=".$shopId.")";	
		$order = $this->queryRow($sql);
		if(empty($order))return $data;
		$data["order"] = $order;
		$sql = "select og.orderId, og.goodsId ,g.goodsSn, og.goodsNums, og.goodsName , og.goodsPrice shopPrice,og.goodsThums,og.goodsAttrName,og.goodsAttrName 
				from __PREFIX__goods g , __PREFIX__order_goods og 
				WHERE g.goodsId = og.goodsId AND og.orderId = $orderId";
		$goods = $this->query($sql);
		$data["goodsList"] = $goods;

		$sql = "SELECT * FROM __PREFIX__log_orders WHERE orderId = $orderId ";	
		$logs = $this->query($sql);
		$data["logs"] = $logs;
		
		return $data;
		
	}
	/**
	 * 获取用户指定状态的订单数目
	 */
	public function getUserOrderStatusCount($obj){
		$userId = (int)$obj["userId"];
		$data = array();
		$sql = "select orderStatus,COUNT(*) cnt from __PREFIX__orders WHERE orderStatus in (0,1,2,3) and orderFlag=1 and userId = $userId GROUP BY orderStatus";
		$olist = $this->query($sql);
		$data = array('-3'=>0,'-2'=>0,'2'=>0,'3'=>0,'4'=>0);
		for($i=0;$i<count($olist);$i++){
			$row = $olist[$i];
			if($row["orderStatus"]==0 || $row["orderStatus"]==1 || $row["orderStatus"]==2){
				$row["orderStatus"] = 2;
			}
			$data[$row["orderStatus"]] = $data[$row["orderStatus"]]+$row["cnt"];
		}
		//获取未支付订单
		$sql = "select COUNT(*) cnt from __PREFIX__orders WHERE orderStatus = -2 and isRefund=0 and payType=1 and orderFlag=1 and isPay = 0 and needPay >0 and userId = $userId";
		$olist = $this->query($sql);
		$data[-2] = $olist[0]['cnt'];
		
		//获取退款订单
		$sql = "select COUNT(*) cnt from __PREFIX__orders WHERE orderStatus in (-3,-4,-6,-7) and isRefund=0 and payType=1 and orderFlag=1 and userId = $userId";
		$olist = $this->query($sql);
		$data[-3] = $olist[0]['cnt'];
		//获取待评价订单
		$sql = "select COUNT(*) cnt from __PREFIX__orders WHERE orderStatus =4 and isAppraises=0 and orderFlag=1 and userId = $userId";
		$olist = $this->query($sql);
		$data[4] = $olist[0]['cnt'];
		
		//获取商城信息
		$sql = "select count(*) cnt from __PREFIX__messages WHERE  receiveUserId=".$userId." and msgStatus=0 and msgFlag=1 ";
		$olist = $this->query($sql);
		$data[100000] = empty($olist)?0:$olist[0]['cnt'];
		
		return $data;
		
	}
	
	/**
	 * 获取用户指定状态的订单数目
	 */
	public function getShopOrderStatusCount($obj){
		$shopId = (int)$obj["shopId"];
		$rsdata = array();
		//待受理订单
		$sql = "SELECT COUNT(*) cnt FROM __PREFIX__orders WHERE orderFlag=1 and shopId = $shopId AND orderStatus = 0 ";
		$olist = $this->queryRow($sql);
		$rsdata[0] = $olist['cnt'];
		
		//取消-商家未知的 / 拒收订单
		$sql = "SELECT COUNT(*) cnt FROM __PREFIX__orders WHERE orderFlag=1 and shopId = $shopId AND orderStatus in (-3,-6)";
		$olist = $this->queryRow($sql);
		$rsdata[5] = $olist['cnt'];
		$rsdata[100] = $rsdata[0]+$rsdata[5];
		
		//获取商城信息
		$sql = "select count(*) cnt from __PREFIX__messages WHERE receiveUserId=".(int)$obj["userId"]." and msgStatus=0 and msgFlag=1 ";
		$olist = $this->query($sql);
		$rsdata[100000] = empty($olist)?0:$olist[0]['cnt'];
		
		return $rsdata;
	
	}
	
	
	/**
	 * 获取商家订单列表
	 */
	public function queryShopOrders($obj){
		$userId = (int)$obj["userId"];
		$shopId = (int)$obj["shopId"];
		$pcurr = (int)I("pcurr",0);
		$orderStatus = (int)I("statusMark");
		
		$orderNo = WSTAddslashes(I("orderNo"));
		$userName = WSTAddslashes(I("userName"));
		$userAddress = WSTAddslashes(I("userAddress"));
		$rsdata = array();

        //$sql = "SELECT orderNo,orderId,userId,userName,userAddress,totalMoney,realTotalMoney,orderStatus,createTime FROM __PREFIX__orders WHERE  orderFlag=1 and shopId = $shopId ";

        $sql = "SELECT o.orderNo,o.orderId,o.userId,o.userName,o.userAddress,o.totalMoney,o.realTotalMoney,o.orderStatus,o.createTime,g.goodsId,g.goodsName,g.ispintuan,g.ptrs,g.ptlastdate,g.isScore,g.exchangeScore,g.isMiaosha,g.ismiaoshatime FROM __PREFIX__orders o LEFT JOIN __PREFIX__order_goods og ON o.orderId=og.orderId LEFT JOIN __PREFIX__goods g ON og.goodsId=g.goodsId WHERE o.orderFlag=1 and o.shopId = $shopId ";

		if($orderStatus==5){
			$sql.=" AND orderStatus in (-3,-4,-5,-6,-7)";
		}else{
			$sql.=" AND orderStatus = $orderStatus ";	
		}
		if($orderNo!=""){
			$sql .= " AND orderNo like '%$orderNo%'";
		}
		if($userName!=""){
			$sql .= " AND userName like '%$userName%'";
		}
		if($userAddress!=""){
			$sql .= " AND userAddress like '%$userAddress%'";
		}
		$sql.=" order by orderId desc ";
		$data = $this->pageQuery($sql,$pcurr);

		//获取取消/拒收原因
		$orderIds = array();
		$noReadrderIds = array();
		foreach ($data['root'] as $key => $v){	
			if($v['orderStatus']==-6)$noReadrderIds[] = $v['orderId'];
			$sql = "select logContent from __PREFIX__log_orders where orderId =".$v['orderId']." and logType=0 and logUserId=".$v['userId']." order by logId desc limit 1";
			$ors = $this->query($sql);
			$data['root'][$key]['rejectionRemarks'] = $ors[0]['logContent'];
//			dump($data['root'][$key]);
		}
		
		//要对用户取消【-6】的状态进行处理,表示这一条取消信息商家已经知道了
		if($orderStatus==5 && count($noReadrderIds)>0){
			$sql = "UPDATE __PREFIX__orders set orderStatus=-7 WHERE orderFlag=1 and shopId = $shopId AND orderId in (".implode(',',$noReadrderIds).")AND orderStatus = -6 ";
			$this->execute($sql);
		}
		return $data;
	}

    /**
     * 订单列表接口 _NEW
     * @param $obj
     * @return array
     */
    public function OrdersListsApi($shopId){
        $pcurr = (int)I("pcurr",0);
        $orderStatus = (int)I("statusMark");
        $orderNo = WSTAddslashes(I("orderNo"));
        $userName = WSTAddslashes(I("userName"));
        $userAddress = WSTAddslashes(I("userAddress"));
        $rsdata = array();
        //$sql = "SELECT orderNo,orderId,userId,userName,userAddress,totalMoney,realTotalMoney,orderStatus,createTime FROM __PREFIX__orders WHERE  orderFlag=1 and shopId = $shopId ";
        $sql = "SELECT o.orderNo,o.orderId,o.userId,o.userName,o.userAddress,o.totalMoney,o.realTotalMoney,o.orderStatus,o.createTime,g.goodsId,g.goodsName,g.ispintuan,g.ptrs,g.ptlastdate,g.isScore,g.exchangeScore,g.isMiaosha,g.ismiaoshatime FROM __PREFIX__orders o LEFT JOIN __PREFIX__order_goods og ON o.orderId=og.orderId LEFT JOIN __PREFIX__goods g ON og.goodsId=g.goodsId WHERE o.orderFlag=1 and o.shopId = $shopId ";
        // 订单状态：（-2:待支付、3：未使用、4:完成）
        if($orderStatus==5){
            $sql.=" AND orderStatus in (-3,-4,-5,-6,-7)";
        }elseif($orderStatus==3){
            $sql.=" AND orderStatus in (0,1,2,3)";
        }elseif($orderStatus==4){
            $sql.=" AND orderStatus = 4 ";
        }elseif($orderStatus==-2){
            $sql.=" AND orderStatus = -2 ";
        }
        if($orderNo!=""){
            $sql .= " AND orderNo like '%$orderNo%'";
        }
        if($userName!=""){
            $sql .= " AND userName like '%$userName%'";
        }
        if($userAddress!=""){
            $sql .= " AND userAddress like '%$userAddress%'";
        }
        $sql.=" order by orderId desc ";
        if(I('sort')=='sc'){
            $sql.=" limit ".I('limit',3);
            $data['root'] = $this->query($sql);
        }else
        $data = $this->pageQuery($sql,$pcurr);
        if(C('isDevelop')){WLog('log.log','sql', $this->getLastSql());}
        //获取取消/拒收原因
        $orderIds = array();
        $noReadrderIds = array();
        foreach ($data['root'] as $key => $v){
            if($v['orderStatus']==-6)$noReadrderIds[] = $v['orderId'];
            $sql = "select logContent from __PREFIX__log_orders where orderId =".$v['orderId']." and logType=0 and logUserId=".$v['userId']." order by logId desc limit 1";
            $ors = $this->query($sql);
            $data['root'][$key]['rejectionRemarks'] = $ors[0]['logContent'];
//			dump($data['root'][$key]);
        }

        //要对用户取消【-6】的状态进行处理,表示这一条取消信息商家已经知道了
        if($orderStatus==5 && count($noReadrderIds)>0){
            $sql = "UPDATE __PREFIX__orders set orderStatus=-7 WHERE orderFlag=1 and shopId = $shopId AND orderId in (".implode(',',$noReadrderIds).")AND orderStatus = -6 ";
            $this->execute($sql);
        }
        return $data;
    }

    /**
     * 核销订单接口
     */
    public function changeOrderApi(){
        $data = array('orderStatus'=>4);
        $res = M('orders')->where(array('orderId'=>I('orderid')))->save($data);
        if($res){
            $data = array('code'=>1,'msg'=>'成功！');
        }else{
            $data = array('code'=>0,'msg'=>'失败！');
        }
        return $data;
    }

	
	/**
	 * 商家受理订单-只能受理【未受理】的订单
	 */
	public function shopOrderAccept ($obj){		
		$userId = (int)$obj["userId"];
		$orderId = (int)$obj["orderId"];
		$shopId = (int)$obj["shopId"];
		$rsdata = array();
		$sql = "SELECT orderId,orderNo,orderStatus FROM __PREFIX__orders WHERE orderFlag=1 and orderId = $orderId AND orderFlag=1 and shopId=".$shopId;		
		$rsv = $this->queryRow($sql);
		if($rsv["orderStatus"]!=0){
			$rsdata["status"] = -1;
			return $rsdata;
		}

		$sql = "UPDATE __PREFIX__orders set orderStatus = 1 WHERE orderFlag=1 and orderId = $orderId and shopId=".$shopId;		
		$rs = $this->execute($sql);		

		$data = array();
		$m = M('log_orders');
		$data["orderId"] = $orderId;
		$data["logContent"] = "商家已受理订单";
		$data["logUserId"] = $userId;
		$data["logType"] = 0;
		$data["logTime"] = date('Y-m-d H:i:s');
		$ra = $m->add($data);
		$rsdata["status"] = $ra;
		return $rsdata;
	}
	
    /**
	 * 商家批量受理订单-只能受理【未受理】的订单
	 */
	public function batchShopOrderAccept(){		
		$USER = session('WST_USER');
		$userId = (int)$USER["userId"];
		$orderIds = self::formatIn(",", I("orderIds"));
		$shopId = (int)$USER["shopId"];
		if($orderIds=='')return array('status'=>-2);
		$orderIds = explode(',',$orderIds);
		$orderNum = count($orderIds);
		$editOrderNum = 0;
		foreach ($orderIds as $orderId){
			if($orderId=='')continue;//订单号为空则跳过
			$sql = "SELECT orderId,orderNo,orderStatus FROM __PREFIX__orders WHERE orderId = $orderId AND orderFlag=1 and shopId=".$shopId;		
			$rsv = $this->queryRow($sql);
			if($rsv["orderStatus"]!=0)continue;//订单状态不符合则跳过
			$sql = "UPDATE __PREFIX__orders set orderStatus = 1 WHERE orderFlag=1 and orderId = $orderId and shopId=".$shopId;		
			$rs = $this->execute($sql);		
	
			$data = array();
			$m = M('log_orders');
			$data["orderId"] = $orderId;
			$data["logContent"] = "商家已受理订单";
			$data["logUserId"] = $userId;
			$data["logType"] = 0;
			$data["logTime"] = date('Y-m-d H:i:s');
			$ra = $m->add($data);
			$editOrderNum++;
		}
		if($editOrderNum==0)return array('status'=>-1);//没有符合条件的执行操作
		if($editOrderNum<$orderNum)return array('status'=>-2);//只有部分订单符合操作
		return array('status'=>1);
	}
	
	/**
	 * 商家打包订单-只能处理[受理]的订单
	 */
	public function shopOrderProduce ($obj){		
		$userId = (int)$obj["userId"];
		$shopId = (int)$obj["shopId"];
		$orderId = (int)$obj["orderId"];
		$rsdata = array();
		$sql = "SELECT orderId,orderNo,orderStatus FROM __PREFIX__orders WHERE orderId = $orderId AND orderFlag =1 and shopId=".$shopId;		
		$rsv = $this->queryRow($sql);
		if($rsv["orderStatus"]!=1){
			$rsdata["status"] = -1;
            $rsdata['code']=0;
			return $rsdata;
		}

		$sql = "UPDATE __PREFIX__orders set orderStatus = 2 WHERE orderFlag=1 and orderId = $orderId and shopId=".$shopId;		
		$rs = $this->execute($sql);
		if($rs){$rsdata['code']=1;}else{ $rsdata['code']=0;}
		$data = array();
		$m = M('log_orders');
		$data["orderId"] = $orderId;
		$data["logContent"] = "订单打包中";
		$data["logUserId"] = $userId;
		$data["logType"] = 0;
		$data["logTime"] = date('Y-m-d H:i:s');
		$ra = $m->add($data);
		$rsdata["status"] = $ra;;
		return $rsdata;
	}
	
    /**
	 * 商家批量打包订单-只能处理[受理]的订单
	 */
	public function batchShopOrderProduce (){		
		$USER = session('WST_USER');
		$userId = (int)$USER["userId"];
		$orderIds = self::formatIn(",", I("orderIds"));
		$shopId = (int)$USER["shopId"];
		if($orderIds=='')return array('status'=>-2);
		$orderIds = explode(',',$orderIds);
		$orderNum = count($orderIds);
		$editOrderNum = 0;
		foreach ($orderIds as $orderId){
			if($orderId=='')continue;//订单号为空则跳过
			$sql = "SELECT orderId,orderNo,orderStatus FROM __PREFIX__orders WHERE orderId = $orderId AND orderFlag =1 and shopId=".$shopId;		
			$rsv = $this->queryRow($sql);
			if($rsv["orderStatus"]!=1)continue;//订单状态不符合则跳过
	
			$sql = "UPDATE __PREFIX__orders set orderStatus = 2 WHERE orderFlag=1 and orderId = $orderId and shopId=".$shopId;		
			$rs = $this->execute($sql);		
			$data = array();
			$m = M('log_orders');
			$data["orderId"] = $orderId;
			$data["logContent"] = "订单打包中";
			$data["logUserId"] = $userId;
			$data["logType"] = 0;
			$data["logTime"] = date('Y-m-d H:i:s');
			$ra = $m->add($data);
			$editOrderNum++;
		}
		if($editOrderNum==0)return array('status'=>-1);//没有符合条件的执行操作
		if($editOrderNum<$orderNum)return array('status'=>-2);//只有部分订单符合操作
		return array('status'=>1);
	}
	
	/**
	 * 商家发货配送订单
	 */
	public function shopOrderDelivery ($obj){		
		$userId = (int)$obj["userId"];
		$orderId = (int)$obj["orderId"];
		$shopId = (int)$obj["shopId"];
		$rsdata = array();
		$sql = "SELECT orderId,orderNo,orderStatus FROM __PREFIX__orders WHERE orderId = $orderId AND orderFlag =1 and shopId=".$shopId;		
		$rsv = $this->queryRow($sql);
		if($rsv["orderStatus"]!=2){
			$rsdata["status"] = -1;
			return $rsdata;
		}

		$sql = "UPDATE __PREFIX__orders set orderStatus = 3,deliveryTime='".date('Y-m-d H:i:s')."' WHERE orderFlag=1 and orderId = $orderId and shopId=".$shopId;		
		$rs = $this->execute($sql);		

		$data = array();
		$m = M('log_orders');
		$data["orderId"] = $orderId;
		$data["logContent"] = "商家已发货";
		$data["logUserId"] = $userId;
		$data["logType"] = 0;
		$data["logTime"] = date('Y-m-d H:i:s');
		$ra = $m->add($data);
		$rsdata["status"] = $ra;;
		return $rsdata;
	}
	
    /**
	 * 商家发货配送订单
	 */
	public function batchShopOrderDelivery ($obj){		
		$USER = session('WST_USER');
		$userId = (int)$USER["userId"];
		$orderIds = self::formatIn(",",I("orderIds"));
		$shopId = (int)$USER["shopId"];
		if($orderIds=='')return array('status'=>-2);
		$orderIds = explode(',',$orderIds);
		$orderNum = count($orderIds);
		$editOrderNum = 0;
		foreach ($orderIds as $orderId){
			if($orderId=='')continue;//订单号为空则跳过
			$sql = "SELECT orderId,orderNo,orderStatus FROM __PREFIX__orders WHERE orderId = $orderId AND orderFlag =1 and shopId=".$shopId;		
			$rsv = $this->queryRow($sql);
			if($rsv["orderStatus"]!=2)continue;//状态不符合则跳过
	
			$sql = "UPDATE __PREFIX__orders set orderStatus = 3,deliveryTime='".date('Y-m-d H:i:s')."' WHERE orderFlag=1 and orderId = $orderId and shopId=".$shopId;		
			$rs = $this->execute($sql);		
	
			$data = array();
			$m = M('log_orders');
			$data["orderId"] = $orderId;
			$data["logContent"] = "商家已发货";
			$data["logUserId"] = $userId;
			$data["logType"] = 0;
			$data["logTime"] = date('Y-m-d H:i:s');
			$ra = $m->add($data);
		    $editOrderNum++;
		}
		if($editOrderNum==0)return array('status'=>-1);//没有符合条件的执行操作
		if($editOrderNum<$orderNum)return array('status'=>-2);//只有部分订单符合操作
		return array('status'=>1);
	}
	
	/**
	 * 商家确认收货
	 */
	public function shopOrderReceipt ($obj){		
		$userId = (int)$obj["userId"];
		$shopId = (int)$obj["shopId"];
		$orderId = (int)$obj["orderId"];
		$rsdata = array();
		$sql = "SELECT orderId,orderNo,orderStatus FROM __PREFIX__orders WHERE orderId = $orderId AND orderFlag =1 and shopId=".$shopId;		
		$rsv = $this->queryRow($sql);
		if($rsv["orderStatus"]!=4){
			$rsdata["status"] = -1;
			return $rsdata;
		}

		$sql = "UPDATE __PREFIX__orders set orderStatus = 5 WHERE orderFlag=1 and orderId = $orderId and shopId=".$shopId;		
		$rs = $this->execute($sql);		

		$data = array();
		$m = M('log_orders');
		$data["orderId"] = $orderId;
		$data["logContent"] = "商家确认已收货，订单完成";
		$data["logUserId"] = $userId;
		$data["logType"] = 0;
		$data["logTime"] = date('Y-m-d H:i:s');
		$ra = $m->add($data);
		$rsdata["status"] = $ra;;
		return $rsdata;
	}
	/**
	 * 商家确认拒收/不同意拒收
	 */
	public function shopOrderRefund ($obj){		
		$userId = (int)$obj["userId"];
		$orderId = (int)$obj["orderId"];
		$shopId = (int)$obj["shopId"];
		$type = (int)I('type');
		$rsdata = array();
		$sql = "SELECT orderId,orderNo,orderStatus,useScore,userId FROM __PREFIX__orders WHERE orderId = $orderId AND orderFlag = 1 and shopId=".$shopId;		
		$rsv = $this->queryRow($sql);
		if($rsv["orderStatus"]!= -3){
			$rsdata["status"] = -1;
			return $rsdata;
		}
		//同意拒收
        if($type==1){
			$sql = "UPDATE __PREFIX__orders set orderStatus = -4 WHERE orderFlag=1 and orderId = $orderId and shopId=".$shopId;		
			$rs = $this->execute($sql);
			//加回库存
			if($rs>0){
				$sql = "SELECT goodsId,goodsNums,goodsAttrId from __PREFIX__order_goods WHERE orderId = $orderId";
				$oglist = $this->query($sql);
				foreach ($oglist as $key => $ogoods) {
					$goodsId = $ogoods["goodsId"];
					$goodsNums = $ogoods["goodsNums"];
					$goodsAttrId = $ogoods["goodsAttrId"];
					$sql = "UPDATE __PREFIX__goods set goodsStock = goodsStock+$goodsNums WHERE goodsId = $goodsId";
					$this->execute($sql);
					if($goodsAttrId>0){
						$sql = "UPDATE __PREFIX__goods_attributes set attrStock = attrStock+$goodsNums WHERE id = $goodsAttrId";
						$this->execute($sql);
					}
				}
				
				if($rsv["useScore"]>0){
					$sql = "UPDATE __PREFIX__users set userScore=userScore+".$rsv["useScore"]." WHERE userId=".$rsv["userId"];
					$this->execute($sql);
						
					$data = array();
					$m = M('user_score');
					$data["userId"] = $userId;
					$data["score"] = $rsv["useScore"];
					$data["dataSrc"] = 4;
					$data["dataId"] = $rsv["userId"];
					$data["dataRemarks"] = "拒收订单返还";
					$data["scoreType"] = 1;
					$data["createTime"] = date('Y-m-d H:i:s');
					$m->add($data);
				}
			}	
        }else{//不同意拒收
        	if(I('rejectionRemarks')=='')return $rsdata;//不同意拒收必须填写原因
        	$sql = "UPDATE __PREFIX__orders set orderStatus = -5 WHERE orderFlag=1 and orderId = $orderId and shopId=".$shopId;		
			$rs = $this->execute($sql);
        }
		$data = array();
		$m = M('log_orders');
		$data["orderId"] = $orderId;
		$data["logContent"] = ($type==1)?"商家同意拒收":"商家不同意拒收：".I('rejectionRemarks');
		$data["logUserId"] = $userId;
		$data["logType"] = 0;
		$data["logTime"] = date('Y-m-d H:i:s');
		$ra = $m->add($data);
		$rsdata["status"] = $ra;;
		return $rsdata;
	}
	
	/**
	 * 检查订单是否已支付
	 */
	public function checkOrderPay ($obj){
		$userId = (int)$obj["userId"];
		$orderId = (int)I("orderId");
		if($orderId>0){
			$sql = "SELECT orderId,orderNo FROM __PREFIX__orders WHERE userId = $userId AND orderId = $orderId AND orderFlag = 1 AND orderStatus = -2 AND isPay = 0 ";// AND payType = 1";
		}else{
			$orderunique = session("WST_ORDER_UNIQUE");
			$sql = "SELECT orderId,orderNo FROM __PREFIX__orders WHERE userId = $userId AND orderunique = '$orderunique' AND orderFlag = 1 AND orderStatus = -2 AND isPay = 0 "; // AND payType = 1";
		}
		$rsv = $this->query($sql);
		$oIds = array();
		for($i=0;$i<count($rsv);$i++){
			$oIds[] = $rsv[$i]["orderId"];
		}
		$orderIds = implode(",",$oIds);
		$data = array();
		if(count($rsv)>0){
			$sql = "SELECT og.goodsId,og.goodsName,og.goodsAttrName,g.goodsStock,og.goodsNums, og.goodsAttrId, ga.attrStock FROM  __PREFIX__goods g ,__PREFIX__order_goods og
					left join __PREFIX__goods_attributes ga on ga.goodsId=og.goodsId and og.goodsAttrId=ga.id
					WHERE og.goodsId = g.goodsId and og.orderId in($orderIds)";
			$glist = $this->query($sql);


			if(count($glist)>0){
				$rlist = array();
				foreach ($glist as $goods) {
					if($goods["goodsAttrId"]>0){
						if($goods["attrStock"]<$goods["goodsNums"]){
							$rlist[] = $goods;
						}
					}else{
						if($goods["goodsStock"]<$goods["goodsNums"]){
							$rlist[] = $goods;
						}
					}
				}

				if(count($rlist)>0){
					$data["status"] = -2;
					$data["rlist"] = $rlist;
				}else{
					$data["status"] = 1;
				}
			}else{
				$data["status"] = 1;
			}
		}else{
			$data["status"] = -1;
		}
		return $data;
	}

    /**
     * ADD BY YANG
     * @param $uid
     * @param $status
     * @param $limit
     * @return mixed
     */
    public function getUserCoup($uid, $status, $limit){
        $sql = "SELECT c.* FROM __PREFIX__coupon AS c LEFT JOIN  __PREFIX__user_coup AS uc ON c.id=uc.cid WHERE status=".$status." AND uc.uid=".$uid." ORDER BY date DESC LIMIT ".$limit ;
        return $this->query($sql);
    }

    /**
     * 余额支付
     * @param $umoney
     * @param $totalmoney
     */
    public function setUserMoney($uid, $umoney, $totalmoney){
        $data['userMoney'] = ($umoney-$totalmoney);
        $res = M('users')->where(array('userId'=>$uid))->save($data);
        return $res;
    }

    public function setUserGold($uid, $ugold, $goldpay){
        $data['userSignGold'] = ($ugold-$goldpay);
        $res = M('users')->where(array('userId'=>$uid))->save($data);
        return $res;
    }



    /**
     * 分页列表
     */
    public function kuaidiByPage(){
        $sql = "select * from __PREFIX__transport where isShow=1 order by id desc";
        $rs = $this->pageQuery($sql);
        return $rs;
    }
    /**
     * 获取列表
     */
    public function kuaidiByList(){
        $rs = $this->where('bankFlag=1')->select();
        return $rs;
    }

    public function toPostTransport(){
        $date = array();
        $data['orderid'] = I('orderid');
        $data['transid'] = I('kuaidi');
        $data['transno'] = I('kuaidino');
        $data['ctime'] = date('Y-m-d H:i:s',time());
        $ret = M('trans_orders')->add($data);
        if($ret){
            $res['status'] = 1;
            $res['code'] = 1;
            //$re = M('orders')->where(array('orderId'=>I('orderid')))->setField('orderStatus', 3); // 修改订单状态
            $this->changeOrderstatusAndLog($data['orderid'], $data['ctime'],  I('userId'));
            WLog('changeOrderStatus', 'orderid['.$data['ctime'].']', I('orderid'));
        }else{
            $res['status'] = 0;
            $res['code'] = 0;
        }
        return $res;
    }

    public function changeOrderstatusAndLog($oid, $date, $userId){
        $rsdata = array();
        $sql = "SELECT orderId,orderNo,orderStatus FROM __PREFIX__orders WHERE orderId = $oid AND orderFlag =1 ";
        $rsv = $this->queryRow($sql);
        if($rsv["orderStatus"]!=2){
            $rsdata["status"] = -1;
            return $rsdata;
        }
        $sql = "UPDATE __PREFIX__orders set orderStatus = 3,deliveryTime='".$date."' WHERE orderFlag=1 and orderId = $oid ";
        $rs = $this->execute($sql);
        $data = array();
        $m = M('log_orders');
        $data["orderId"] = $oid;
        $data["logContent"] = "商家已发货";
        $data["logUserId"] = $userId;
        $data["logType"] = 0;
        $data["logTime"] = date('Y-m-d H:i:s');
        $ra = $m->add($data);
        if($ra){
            $rsdata["status"] = 1;
        }
        return $rsdata;
    }

    /**
     * 获取订单的 物流信息，商家后台 查看订单详情 使用
     */
    public function getTransForOrderDetails($oid){
        $sql = "SELECT tr.name,tro.transno,tro.ctime,og.goodsThums FROM __PREFIX__trans_orders tro LEFT JOIN __PREFIX__transport tr ON tr.id=tro.transid LEFT JOIN fys_order_goods og ON og.orderId=tro.orderid WHERE tro.orderid=".$oid;
        return $this->query($sql);
    }

    /**
     * 前台手机端查看订单详情
     * @return [type] [description]
     */
    public function transport(){
    	$oid = I('orderid');
    	return $this->getTransForOrderDetails($oid);
    }



    /**
     * 获取商家订单列表
     */
    public function queryShopPtordersByPage_ssss($obj){
        $userId = (int)$obj["userId"];
        $shopId = (int)$obj["shopId"];
        $pcurr = (int)I("pcurr",0);
        $orderStatus = (int)I("statusMark");

        $orderNo = WSTAddslashes(I("orderNo"));
        $userName = WSTAddslashes(I("userName"));
        $userAddress = WSTAddslashes(I("userAddress"));
        $rsdata = array();
        $sql = "SELECT orderNo,orderId,userId,userName,userAddress,totalMoney,realTotalMoney,orderStatus,createTime FROM __PREFIX__orders WHERE  orderFlag=1 and shopId = $shopId ";
        if($orderStatus==5){
            $sql.=" AND orderStatus in (-3,-4,-5,-6,-7)";
        }else{
            $sql.=" AND orderStatus = $orderStatus ";
        }
        if($orderNo!=""){
            $sql .= " AND orderNo like '%$orderNo%'";
        }
        if($userName!=""){
            $sql .= " AND userName like '%$userName%'";
        }
        if($userAddress!=""){
            $sql .= " AND userAddress like '%$userAddress%'";
        }
        $sql.=" order by orderId desc ";
        $data = $this->pageQuery($sql,$pcurr);
        //获取取消/拒收原因
        $orderIds = array();
        $noReadrderIds = array();
        foreach ($data['root'] as $key => $v){
            if($v['orderStatus']==-6)$noReadrderIds[] = $v['orderId'];
            $sql = "select logContent from __PREFIX__log_orders where orderId =".$v['orderId']." and logType=0 and logUserId=".$v['userId']." order by logId desc limit 1";
            $ors = $this->query($sql);
            $data['root'][$key]['rejectionRemarks'] = $ors[0]['logContent'];
        }

        //要对用户取消【-6】的状态进行处理,表示这一条取消信息商家已经知道了
        if($orderStatus==5 && count($noReadrderIds)>0){
            $sql = "UPDATE __PREFIX__orders set orderStatus=-7 WHERE orderFlag=1 and shopId = $shopId AND orderId in (".implode(',',$noReadrderIds).")AND orderStatus = -6 ";
            $this->execute($sql);
        }
        return $data;
    }






} // END CLASS