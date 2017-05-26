<?php
 namespace Home\Action;;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 会员地址控制器
 */
class UserAddressAction extends BaseAction{
	/**
	 * 跳到新增/编辑页面
	 */
	public function toEdit(){
		$this->isUserLogin();
	    $m = D('Home/UserAddress');
    	$object = array();
    	if((int)I('id',0)>0){
    		$object = $m->get();
    	}else{
    		$object = $m->getModel();
    	}
    	//获取地区信息
		$m = D('Home/Areas');
		$this->assign('areaList',$m->getProvinceList());
    	$this->assign('object',$object);
    	$this->assign("umark","addressQueryByPage");
		$this->view->display('mobile/users/useraddress/edit');
	}
	/**
	 * 新增/修改操作
	 */
	public function edit(){
		$this->isUserLogin();
		$m = D('Home/UserAddress');
    	$rs = array();
    	if((int)I('id',0)>0){
    		$rs = $m->edit();
    	}else{
    		$rs = $m->insert();
    	}
    	$this->ajaxReturn($rs);
	}

    /**
     * 新增收货地址
     */
	public function edit_new(){
        $this->isLogin();
        $USER = session('WST_USER');
        $data['userId'] = $USER['userId'];
        $data['userName'] = I('uname');
        $data['userPhone'] = I('uphone');
        $data['areaId3'] = I('harea');
        $data['areaId2'] = I('hproper');
        $data['areaId1'] = I('hcity');
        $data['address'] = I('address');
        $data['createTime'] = date("y-m-d H:i:s");
        if(I('addressId')>0){
            $res = M('user_address')->where(array('addressId'=>I('addressId')))->save($data);
        }else{
            $res = M('user_address')->add($data);
        }
        if($res){ echo 1; }else{ echo 0; }
    }
    /**
     * 编辑收货地址
     */
    public function edit_edit(){
        $this->isUserLogin();
        if((int)I('addressId',0)>0){
            $m = D('Home/UserAddress');
            $address = $m->getUserAddressInfo_new();
            $this->assign('object', $address);
            $this->display('/mobile/Users/UserAddress/edit');
        }
    }

	/**
	 * 删除操作
	 */
	public function del(){
		$this->isUserLogin();
		$m = D('Home/UserAddress');
    	$rs = $m->del();
    	$this->ajaxReturn($rs);
	}
	/**
	 * 分页查询
	 */
	public function queryByPage(){
		$this->isLogin();
		$USER = session('WST_USER');
		$m = D('Home/UserAddress');
    	$list = $m->queryByList($USER['userId']);
    	$this->assign('List',$list);
    	$this->assign("umark","addressQueryByPage");
        $this->display("mobile/users/useraddress/list");
	}

    /**
     * 设置默认收货地址
     * @param $id
     */
	public function setDefaultAddr(){
        $this->isLogin();
        $id = I('get.id');
        $USER = session('WST_USER');
        $res = M('user_address')->where(array('userId'=>$USER['userId'], 'addressId'=>$id))->save(array('isDefault'=>1));
        $res1 = M('user_address')->where('userId='.$USER['userId']. ' and addressId!='.$id)->save(array('isDefault'=>0));
        if($res){echo 1;}else{echo 0;}
    }


	/**
	 * 获取用户地址
	 */
	public function getUserAddress(){
		$this->isUserLogin();
		$m = D('Home/UserAddress');
		$address = $m->getUserAddressInfo();	
		$addressInfo = array();
		$addressInfo["status"] = 1;
		$addressInfo["address"] = $address;
		$this->ajaxReturn($addressInfo);
	}
	
	/**
	 * 获取区县
	 */
	public function getDistricts(){
		$m = D('Home/UserAddress');
		$areaId2 = (int)I("areaId2");
		$communitys = $m->getDistricts($areaId2);
		$this->ajaxReturn($communitys);
			
	}
	
	/**
	 * 获取社区
	 */
	public function getCommunitys(){
		
		$m = D('Home/UserAddress');
		$districtId = (int)I("districtId");
		$communitys = $m->getCommunitys($districtId);	
		$this->ajaxReturn($communitys);
			
	}
	
	/**
	 * 获取区县
	 */
	public function getDistrictsOption(){
		
		$m = D('Home/UserAddress');
		$areaId2 = (int)I("areaId2");
		$communitys = $m->getDistrictsOption($areaId2);	
		$this->ajaxReturn($communitys);
			
	}
	
	/**
	 * 获取社区
	 */
	public function getCommunitysOption(){
		
		$m = D('Home/UserAddress');
		$districtId = (int)I("districtId");
		$communitys = $m->getCommunitysOption($districtId);	
		$this->ajaxReturn($communitys);
			
	}
	
	/**
	 * 获取店铺配送区县
	 */
	public function getShopDistricts(){
	
		$m = D('Home/UserAddress');
		$areaId2 = (int)I("areaId2");
		$shopId = (int)I("shopId");
		$communitys = $m->getShopDistricts($areaId2,$shopId);
		$this->ajaxReturn($communitys);
			
	}
	
	/**
	 * 获取店铺配送社区
	 */
	public function getShopCommunitys(){
	
		$m = D('Home/UserAddress');
		$districtId = (int)I("districtId");
		$shopId = (int)I("shopId");
		$communitys = $m->getShopCommunitys($districtId,$shopId);
		$this->ajaxReturn($communitys);
			
	}
	
};
?>