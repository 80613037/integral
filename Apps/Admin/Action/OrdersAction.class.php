<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 订单控制器
 */
class OrdersAction extends BaseAction{
	/**
	 * 分页查询
	 */
	public function index(){
		$this->isLogin();
		$this->checkPrivelege('ddlb_00');
		//获取地区信息
		$m = D('Admin/Areas');
		$this->assign('areaList',$m->queryShowByList(0));
		
		$m = D('Admin/Orders');
    	$page = $m->queryByPage();
    	$pager = new \Think\Page($page['total'],$page['pageSize'],I());
    	$page['pager'] = $pager->show();

        $mc = D('Admin/Coupon');
    	foreach ($page['root'] as $key=>$val){
            $coupon = $mc->get($val['coupId']);
            $page['root'][$key]['coupId'] = $coupon['id'];
            $page['root'][$key]['man'] = $coupon['man'];
            $page['root'][$key]['jian'] = $coupon['jian'];
        }
        //if(C('isDevelop')){WLog('page','data',json_encode($page['root']));WLog('page1','data',json_encode($page['root']));}
        if(I("f")=='api') {
            if(I('sort')=='sc'){
                $res['data']=$page['root'];
            }else{
                $res['data'] = $page['root'];
                $res['total'] = (int)$page['total'];    // 总条数
                $res['pageSize'] = $page['pageSize'];   // 每页条数
                $res['totalPage'] = $page['totalPage']; // 总页数
                $res['currPage'] = (int)$page['currPage'];   // 当前页码
                $res['code'] = 1;
            }
            if(isset($_GET['callback'])){
                exit($_GET['callback'].'(' . json_encode($res) .')');
            }else
            exit(json_encode($res));
        }
        $this->assign('Page',$page);
    	$this->assign('shopName',I('shopName'));
    	$this->assign('orderNo',I('orderNo'));
    	$this->assign('areaId1',I('areaId1',0));
    	$this->assign('areaId2',I('areaId2',0));
    	$this->assign('areaId3',I('areaId3',0));
    	$this->assign('orderStatus',I('orderStatus',-9999));
        $this->display("/orders/list");
	}
    /**
	 * 退款分页查询
	 */
	public function queryRefundByPage(){
		$this->isLogin();
		$this->checkPrivelege('tk_00');
		//获取地区信息
		$m = D('Admin/Areas');
		$this->assign('areaList',$m->queryShowByList(0));
		$m = D('Admin/Orders');
    	$page = $m->queryRefundByPage();
    	$pager = new \Think\Page($page['total'],$page['pageSize'],I());
    	$pager->setConfig('header','件商品');
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page);
    	$this->assign('shopName',I('shopName'));
    	$this->assign('orderNo',I('orderNo'));
    	$this->assign('isRefund',I('isRefund',-1));
    	$this->assign('areaId1',I('areaId1',0));
    	$this->assign('areaId2',I('areaId2',0));
    	$this->assign('areaId3',I('areaId3',0));
        $this->display("/orders/list_refund");
	}
	/**
	 * 查看订单详情
	 */
	public function toView(){
		$this->isLogin();
		$this->checkPrivelege('ddlb_00');
		$m = D('Admin/Orders');
		if(I('id')>0){
			$object = $m->getDetail();
            if(I("f")=='api') {
                $res['data'] = $object;
                $res['code'] = 1;
                if(isset($_GET['callback'])){
                    exit($_GET['callback'].'(' . json_encode($res) .')');
                }else
                exit(json_encode($res));
            }
			$this->assign('object',$object);
		}else{
            $res['code'] = 0;
            $res['msg'] = '没有该订单！';
            exit(json_encode($res));
        }
		$this->assign('referer',$_SERVER['HTTP_REFERER']);
		$this->display("/orders/view");
	}
    /**
	 * 查看订单详情
	 */
	public function toRefundView(){
		$this->isLogin();
		$this->checkPrivelege('tk_00');
		$m = D('Admin/Orders');
		if(I('id')>0){
			$object = $m->getDetail();
			$this->assign('object',$object);
		}
		$this->assign('referer',$_SERVER['HTTP_REFERER']);
		$this->display("/orders/view");
	}
	/**
	 * 跳到退款页面
	 */
	public function toRefund(){
		$this->isLogin();
		$this->checkPrivelege('tk_04');
		$m = D('Admin/Orders');
	    if(I('id')>0){
			$object = $m->get();

			$this->assign('object',$object);
		}
		$this->display("/orders/refund");
	}
	/**
	 * 退款
	 */
    public function refund(){
		$this->isLogin();
		$this->checkPrivelege('tk_04');
		$m = D('Admin/Orders');
		$rs = $m->refund();
		$this->ajaxReturn($rs);
	}
};
?>