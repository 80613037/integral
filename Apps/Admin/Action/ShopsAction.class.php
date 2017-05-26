<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 店铺控制器
 */
class ShopsAction extends BaseAction{
	/**
	 * 跳到新增/编辑页面
	 */
	public function toEdit(){
		$this->isLogin();
		//获取商品分类信息
		$m = D('Admin/GoodsCats');
		$this->assign('goodsCatsList',$m->queryByList());
		//获取地区信息
		$m = D('Admin/Areas');
		$this->assign('areaList',$m->queryShowByList(0));
		//获取银行列表
		$m = D('Admin/Banks');
		$this->assign('bankList',$m->queryByList(0));
		//获取商品信息
	    $m = D('Admin/Shops');
    	$object = array();
    	if(I('id',0)>0){
    		$this->checkPrivelege('dplb_02');
    		$object = $m->get();
    	}else{
    		$this->checkPrivelege('dplb_01');
    		$object = $m->getModel();
    	}
    	if(I('f')=='api'){
            $object = $m->get();
            if(isset($_GET['callback'])){
                exit($_GET['callback'].'(' . json_encode($object) .')');
            }else
            exit(json_encode($object));
        }
    	
    	$this->assign('object',$object);
    	$this->assign('src',I('src','index'));
		$this->view->display('/shops/edit');
	}
	
	/**
	 * 查询店铺名称是否存在
	 */
	public function checkShopName(){
		$m = D('Admin/Shops');
		$rs = $m->checkShopName(I('shopName'),(int)I('id'));
		echo json_encode($rs);
	}
	
	/**
	 * 新增/修改操作
	 */
	public function edit(){
		$this->isLogin();
		$m = D('Admin/Shops');
    	$rs = array();
    	if(I('id',0)>0){
    		$this->checkPrivelege('dplb_02');
    		if(I('f')=='api'){
                if(I('shopStatus',0)<=-1){
                    $rs = $m->reject();
                }else{
                    $rs = $m->edit();
                }
            }else{
                if(I('shopStatus',0)<=-1){
                    $rs = $m->reject();
                }else{
                    $rs = $m->edit();
                }
            }

    	}else{
    		$this->checkPrivelege('dplb_01');
            if(I("f")=='api'){
                $rs = $m->insertApi();
                if(isset($_GET['callback'])){
                    exit($_GET['callback'].'(' . json_encode($rs) .')');
                }else
                exit(json_encode($rs));
            }
            $rs = $m->insert();
    	}
        if(isset($_GET['callback'])){
            exit($_GET['callback'].'(' . json_encode($rs) .')');
        }else
    	$this->ajaxReturn($rs);
	}
	public function getGoodsCats(){
        $m = D('Admin/GoodsCats');
        $rs['data'] = $m->queryByList();
        if(isset($_GET['callback'])){
            exit($_GET['callback'].'(' . json_encode($rs) .')');
        }else
        exit(json_encode($rs));
    }

    /**
     * 为商铺添加管理员
     * 多管理员 或者 单管理员
     */
    public function addAdminToShops(){
        $m = D('Admin/Shops');
        $res = $m->addAdminToShops();

    }


	/**
	 * 删除操作
	 */
	public function del(){
		$this->isLogin();
		$this->checkPrivelege('dplb_03');
		$m = D('Admin/Shops');
    	$rs = $m->del();
    	$this->ajaxReturn($rs);
	}
   /**
	 * 查看
	 */
	public function toView(){
		$this->isLogin();
		$this->checkPrivelege('dplb_00');
		$m = D('Admin/Shops');
		if(I('id')>0){
			$object = $m->get();
			$this->assign('object',$object);
		}
		$this->view->display('/shops/view');
	}
	/**
	 * 分页查询
	 */
	public function index(){
		$this->isLogin();
		$this->checkPrivelege('dplb_00');
		//获取地区信息
		$m = D('Admin/Areas');
		$this->assign('areaList',$m->queryShowByList(0));
		$m = D('Admin/Shops');
    	$page = $m->queryByPage();
    	$pager = new \Think\Page($page['total'],$page['pageSize'],I());// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	if(I("f")=='api'){
    	    $res['data'] = $page['root'];
            $res['total'] = (int)$page['total'];         // 总条数
            $res['pageSize'] = $page['pageSize'];   // 每页条数
            $res['totalPage'] = $page['totalPage']; // 总页数
            $res['currPage'] = (int)$page['currPage'];   // 当前页码
    	    $res['code'] = 1;
            if(isset($_GET['callback'])){
                exit($_GET['callback'].'(' . json_encode($res) .')');
            }else{
                exit(json_encode($res));
            }
        }else{
            $this->assign('Page',$page);
            $this->assign('shopName',I('shopName'));
            $this->assign('shopSn',I('shopSn'));
            $this->assign('areaId1',I('areaId1',0));
            $this->assign('areaId2',I('areaId2',0));
            $this->display("/shops/list");
        }

	}
    /**
	 * 分页查询[待审核列表]
	 */
	public function queryPeddingByPage(){
		$this->isLogin();
		$this->checkPrivelege('dpsh_00');
		//获取地区信息
		$m = D('Admin/Areas');
		$this->assign('areaList',$m->queryShowByList(0));
		$m = D('Admin/Shops');
    	$page = $m->queryPeddingByPage();
    	$pager = new \Think\Page($page['total'],$page['pageSize'],I());
    	$pager->setConfig('header','');
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page);
    	$this->assign('shopName',I('shopName'));
    	$this->assign('shopSn',I('shopSn'));
    	$this->assign('shopStatus',I('shopStatus',-999));
    	$this->assign('areaId1',I('areaId1',0));
    	$this->assign('areaId2',I('areaId2',0));
        $this->display("/shops/list_pendding");
	}
	/**
	 * 列表查询
	 */
    public function queryByList(){
    	$this->isLogin();
		$m = D('Admin/Shops');
		$list = $m->queryList();
		$rs = array();
		$rs['status'] = 1;
		$rs['list'] = $list;
		$this->ajaxReturn($rs);
	}
    /**
	 * 获取待审核的店铺数量
	 */
	public function queryPenddingGoodsNum(){
		$this->isLogin();
    	$m = D('Admin/Shops');
    	$rs = $m->queryPenddingShopsNum();
    	$this->ajaxReturn($rs);
	}
};
?>