<?php
namespace Home\Action;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 店铺控制器
 */
class ShopsAction extends BaseAction {
	/**
     * 跳到商家首页面
     */
	public function toShopHome(){
		$mshops = D('Home/Shops');
		$shopId = (int)I('shopId');
		//如果沒有传店铺ID进来则取默认自营店铺
		if($shopId==0){
			$areaId2 = $this->getDefaultCity();
			$shopId = $mshops->checkSelfShopId($areaId2);
		}
		$shops = $mshops->getShopInfo($shopId);
		$shops["serviceEndTime"] = str_replace('.5',':30',$shops["serviceEndTime"]);
		$shops["serviceEndTime"] = str_replace('.0',':00',$shops["serviceEndTime"]);
		$shops["serviceStartTime"] = str_replace('.5',':30',$shops["serviceStartTime"]);
		$shops["serviceStartTime"] = str_replace('.0',':00',$shops["serviceStartTime"]);
		$this->assign('shops',$shops);
		if(!empty($shops)){
			$this->assign('shopId',$shopId);
			$this->assign('ct1',(int)I("ct1"));
			$this->assign('ct2',(int)I("ct2"));
			$this->assign('msort',(int)I("msort",1));
			$this->assign('mdesc',I("mdesc",0));
			$this->assign('sprice',I("sprice"));//上架开始时间
			$this->assign('eprice',I("eprice"));//上架结束时间
			$this->assign('goodsName',urldecode(I("goodsName")));//上架结束时间
					
			$mshopscates = D('Home/ShopsCats');
			$shopscates = $mshopscates->getShopCateList($shopId);
			$this->assign('shopscates',$shopscates);

			$mgoods = D('Home/Goods');
			$shopsgoods = $mgoods->getShopsGoods($shopId); // 商户所有商品

//            $shopsgoodsMS = $mgoods->getShopsGoods($shopId, 'miaosha'); // 秒杀商品
//            $shopsgoodsPT = $mgoods->getShopsGoods($shopId, 'pintuan'); // 拼团商品
//            $shopsgoodsZC = $mgoods->getShopsGoods($shopId, 'zhongchou'); // 众筹商品
//            dump($shopsgoods);
            $this->assign('type',I('type'));
            $this->assign('shopsgoods',$shopsgoods);
			//获取评分
			$obj = array();
			$obj["shopId"] = $shopId;
			$shopScores = $mshops->getShopScores($obj);
		
			$this->assign("shopScores",$shopScores);
			
			$m = D('Home/Favorites');
			$this->assign("favoriteShopId",$m->checkFavorite($shopId,1));
			$this->assign('actionName',ACTION_NAME);
			$this->assign('isSelf',$shops["isSelf"]);
		}
        $this->display("mobile/shop_home");
	}

	public function getShopDesc(){
        $desc = M('shops')->where("shopId=".(int)I('shopId'))->getField('shopDesc');
        $this->assign('desc', $desc);
        $this->display("mobile/shops/shopdesc");
    }


	/**
     * 跳到店铺街
     */
	public function toShopStreet(){
		$areas= D('Home/Areas');
		$areaId2 = $this->getDefaultCity();
   		$areaList = $areas->getDistricts($areaId2);
   		$mshops = D('Home/Shops');
   		$obj = array();
   		if((int)cookie("bstreesAreaId3")){
   			$obj["areaId3"] = (int)cookie("bstreesAreaId3");
   		}else{
   			$obj["areaId3"] = ((int)I('areaId3')>0)?(int)I('areaId3'):$areaList[0]['areaId'];
   			cookie("bstreesAreaId3",$obj["areaId3"]);
   		}

  		$this->assign('areaId3',$obj["areaId3"]);
   		$this->assign('keyWords',I("keyWords"));
   		$this->assign('areaList',$areaList);
        $this->display("default/shop_street");
	}
	
	/**
     * 获取县区内的商铺
     */
	public function getDistrictsShops(){
   		$mshops = D('Home/Shops');
   		$obj["areaId3"] = (int)I("areaId3");
   		$obj["shopName"] = WSTAddslashes(I("shopName"));
   		$obj["deliveryStartMoney"] = (float)I("deliveryStartMoney");
   		$obj["deliveryMoney"] = (float)I("deliveryMoney");
   		$obj["shopAtive"] = (int)I("shopAtive");
   		cookie("bstreesAreaId3",$obj["areaId3"]);
   		
   		$dsplist = $mshops->getDistrictsShops($obj);
   		$this->ajaxReturn($dsplist);
	}
	
	/**
     * 获取社区内的商铺
     */
	public function getShopByCommunitys(){
		
   		$mshops = D('Home/Shops');
   		$obj["communityId"] = (int)I("communityId");
   		$obj["areaId3"] = (int)I("areaId3");
   		$obj["shopName"] = WSTAddslashes(I("shopName"));
   		$obj["deliveryStartMoney"] = (float)I("deliveryStartMoney");
   		$obj["deliveryMoney"] = (float)I("deliveryMoney");
   		$obj["shopAtive"] = (int)I("shopAtive",-1);
   		$ctplist = $mshops->getShopByCommunitys($obj);
   		$pages = $rslist["pages"];

   		$this->assign('ctplist',$pages);
       	$this->ajaxReturn($ctplist);
       	
	}
	
    /**
     * 跳到商家登录页面
     */
	public function login(){
		$USER = session('WST_USER');
//        dump($USER); die();
		if(!empty($USER) && $USER['userType']==1){
			$this->redirect("Shops/index");
		}else{
            $this->display("mobile/shop_login");
		}
	}
	
	/**
	 * 商家登录验证
	 */
	public function checkLogin(){
		$rs = array('status'=>-2);
	    $rs["status"]= 1;
        if(I("f")=='api'){
            $m = D('Home/Shops');
            $rs = $m->login();
            if($rs['status']==1){
                session('WST_USER',$rs['shop']);
                unset($rs['shop']);
            }
        }else{
            if(!$this->checkVerify("4") && ($GLOBALS['CONFIG']["captcha_model"]["valueRange"]!="" && strpos($GLOBALS['CONFIG']["captcha_model"]["valueRange"],"3")>=0)){
                $rs["status"]= -2;//验证码错误
            }else{
                $m = D('Home/Shops');
                $rs = $m->login();
                if($rs['status']==1){
                    session('WST_USER',$rs['shop']);
                    unset($rs['shop']);
                }
            }
        }

        if(isset($_GET['callback'])){
            exit($_GET['callback'].'(' . json_encode($rs) .')');
        }else
    	$this->ajaxReturn($rs);
	}
	/**
	 * 退出
	 */
	public function logout(){
		session('WST_USER',null);
		echo "1";
	}
	/**
	 * 跳到商家中心页面
	 */
	public function index(){
		$this->isShopLogin();
		$spm = D('Home/Shops');
		$data['shop'] = $spm->loadShopInfo(session('WST_USER.userId'));
		$obj["shopId"] = $data['shop']['shopId'];
		$details = $spm->getShopDetails($obj);
		$data['details'] = $details;
        $data['mallPhone'] = $spm->getMallInfo('phoneNo', 'fieldValue');
        $data['QQ'] = $spm->getMallInfo('qqNo', 'fieldValue');
		$this->assign('shopInfo',$data);
		$this->assign('isSelf',$data['shop']['isSelf']);
		$this->display("mobile/shops/index");
	}
	/**
	 * 编辑商家资料
	 */
	public function toEdit(){
		$m = D('Home/Shops');
		$USER = session('WST_USER');
		$shop = $m->get((int)$USER['shopId']);
		if($shop["shopStatus"]!=-1){
			$this->isShopLogin();
		}
		//获取银行列表
		$m = D('Admin/Banks');
		$this->assign('bankList',$m->queryByList(0));
		//获取商品信息
        $isSelf = D('Home/Goods')->isSelf();
        $this->assign('isSelf',$isSelf['isSelf']);
		$this->assign('object',$shop);
		$this->assign("umark","toEdit");
		$this->display("mobile/shops/edit_shop");
	}
	
	/**
	 * 设置商家资料
	 */
	public function toShopCfg(){
		$this->isShopLogin();
        $USER = session('WST_USER');
		//获取商品信息
		$m = D('Home/Shops');
        $isSelf = D('Home/Goods')->isSelf();
        $this->assign('isSelf',$isSelf['isSelf']);
		$this->assign('object',$m->getShopCfg((int)$USER['shopId']));
		$this->assign("umark","setShop");
		$this->display("mobile/shops/cfg_shop");
	}
	/**
	 * 查询店铺名称是否存在
	 */
	public function checkShopName(){
		$m = D('Home/Shops');
		$rs = $m->checkShopName(I('shopName'),(int)I('id'));
		echo json_encode($rs);
	}
	/**
	 * 新增/修改操作
	 */
	public function editShopCfg(){
		$this->isShopLogin();
		$USER = session('WST_USER');
		$m = D('Home/Shops');
    	$rs = array('status'=>-1);
    	if($USER['shopId']>0){
    		$rs = $m->editShopCfg((int)$USER['shopId']);
    	}
    	$this->ajaxReturn($rs);
	}
	
   /**
	* 新增/修改操作
	*/
	public function edit(){
		$this->isShopLogin();
		$USER = session('WST_USER');
		$m = D('Home/Shops');
    	$rs = array('status'=>-1);
    	if($USER['shopId']>0){
    		$rs = $m->edit((int)$USER['shopId']);
    	}
    	$this->ajaxReturn($rs);
	}
	
   /**
    * 跳到修改用户密码
    */
	public function toEditPass(){
		$this->isShopLogin();
        $isSelf = D('Home/Goods')->isSelf();
        $this->assign('isSelf',$isSelf['isSelf']);
		$this->assign("umark","toEditPass");
        $this->display("default/shops/edit_pass");
	}
	
	/**
	 * 申请开店
	 */
	public function toOpenShopByUser(){
		$this->isUserLogin();
		$USER = session('WST_USER');
		if(!empty($USER) && $USER['userType']==0){
			//获取用户申请状态
			$m = D('Home/Shops');
			$shop = $m->checkOpenShopStatus((int)$USER['userId']);
			
			if(empty($shop)){
				//获取商品分类信息
				$m = D('Home/GoodsCats');
				$this->assign('goodsCatsList',$m->queryByList());
				//获取地区信息
				$m = D('Home/Areas');
				$this->assign('areaList',$m->getProvinceList());
				//获取所在城市信息
		        $cityId = $this->getDefaultCity();
		        $area = $m->getArea($cityId);
		        $this->assign('area',$area);
				//获取银行列表
				$m = D('Home/Banks');
				$this->assign('bankList',$m->queryByList(0));
				$object = $m->getModel();
				$object['areaId1'] = $area['parentId'];
				$object['areaId2'] = $area['areaId'];
				$this->assign('object',$object);
				$this->display("default/users/open_shop");
			}else{
				if($shop["shopStatus"]==1){
					$shops = $m->loadShopInfo((int)$USER['userId']);
					$USER = array_merge($USER,$shops);
					session('WST_USER',$USER);
					$this->assign('msg','您的申请已通过，请刷新页面后点击右上角的"卖家中心"进入店铺界面.');
					$this->display("default/users/user_msg");
				}else{
					if($shop["shopStatus"]==-1){
						$this->assign('msg','您的申请审核不通过【原因：'.$shop["statusRemarks"].'】,请<a style="color:blue;" href="'.U('Home/Shops/toEditShopByUser').'"> 点击这里 </a>进行修改！');
					}else{
						$this->assign('msg','您的申请正在审核中...');
					}
					$this->display("default/users/user_msg");
				}
			}
		}else{
			$this->redirect("Shops/index");
		}
	}
	
	/**
	 * 申请开店
	 */
	public function toEditShopByUser(){
		$this->isUserLogin();
		$USER = session('WST_USER');
		if(!empty($USER) && $USER['userType']==0){
			//获取用户申请状态
			$sm = D('Home/Shops');
			$shop = $sm->checkOpenShopStatus((int)$USER['userId']);
				
			if($shop["shopStatus"]==-1){
				//获取商品分类信息
				$m = D('Home/GoodsCats');
				$this->assign('goodsCatsList',$m->queryByList());
				//获取地区信息
				$m = D('Home/Areas');
				$this->assign('areaList',$m->getProvinceList());
				//获取所在城市信息
				$cityId = $this->getDefaultCity();
				//$area = $m->getArea($cityId);
				//$this->assign('area',$area);
				//获取银行列表
				$m = D('Home/Banks');
				$this->assign('bankList',$m->queryByList(0));
				//$object = $m->getModel();
				$object = $sm->getShopByUser((int)$USER['userId']);

				$this->assign('object',$object);
				$this->display("default/users/open_shop");
			}
		}else{
			$this->redirect("Shops/index");
		}
	}
	
	/**
	 * 会员提交开店申请
	 */
	public function openShopByUser(){
		$this->isUserLogin();
		$rs = array('status'=>-1);
		if($GLOBALS['CONFIG']['phoneVerfy']==1){
			$verify = session('VerifyCode_userPhone');
			$startTime = (int)session('VerifyCode_userPhone_Time');
			$mobileCode = I("mobileCode");
			if((time()-$startTime)>120){
				 $rs['msg'] = '验证码已失效!';
			}
			if($mobileCode=="" || $verify != $mobileCode){
				$rs['msg'] = '验证码错误!';
			}
    	}else{
	    	if(!$this->checkVerify("1")){			
				$rs['msg'] = '验证码错误!';
			}
    	}
    	if($rs['msg']==''){
			$USER = session('WST_USER');
			$m = D('Home/Shops');
	    	$userId = (int)$USER['userId'];
	    	$shop = $m->getShopByUser($userId);
	    	if($shop['shopId']>0){
	    		
	    		$rs = $m->edit((int)$shop['shopId'],true);
	    	}else{
			 	//如果用户没注册则先建立账号
				if($userId>0){
			   	    $rs = $m->addByUser($userId);
			    	if($rs['status']>0)$USER['shopStatus'] = 0;
				}
	    	}
    	}
    	$this->ajaxReturn($rs);
	}
	
	
	/**
	 * 游客跳到开店申请
	 */
    public function toOpenShop(){
    	//获取商品分类信息
		$m = D('Home/GoodsCats');
		$this->assign('goodsCatsList',$m->queryByList());
		//获取省份信息
		$m = D('Home/Areas');
		$this->assign('areaList',$m->getProvinceList());
		//获取所在城市信息
		$cityId = $this->getDefaultCity();
		$area = $m->getArea($cityId);
		$this->assign('area',$area);
		//获取银行列表
		$m = D('Home/Banks');
		$this->assign('bankList',$m->queryByList(0));
		$object = $m->getModel();
		$this->assign('object',$object);
		$this->display("default/open_shop");

	}
	
    /**
	 * 游客提交开店申请
	 */
	public function openShop(){
		$m = D('Home/Shops');
    	$rs = array('status'=>-1);
    	if($GLOBALS['CONFIG']['phoneVerfy']==1){
	    	$verify = session('VerifyCode_userPhone');
			$startTime = (int)session('VerifyCode_userPhone_Time');
			$mobileCode = I("mobileCode");
			if((time()-$startTime)>120){
			    $rs['msg'] = '验证码已失效!';
		    }
			if($mobileCode=="" || $verify != $mobileCode){
				$rs['msg'] = '验证码错误!';
			}
    	}else{
	    	if(!$this->checkVerify("1")){			
				$rs['msg'] = '验证码错误!';
			}
    	}
    	if($rs['msg']==''){
			$rs = $m->addByVisitor();
			$m = D('Home/Users');
			$user = $m->get($rs['userId']);
			if(!empty($user))session('WST_USER',$user);
    	}
    	$this->ajaxReturn($rs);
	}

	/**
	 * 获取店铺搜索提示列表
	 */
	public function getKeyList(){
		$m = D('Home/Shops');
		$areaId2 = $this->getDefaultCity();
		$rs = $m->getKeyList($areaId2);
		$this->ajaxReturn($rs);
	}
	
	
}