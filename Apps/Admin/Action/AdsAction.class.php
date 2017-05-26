<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 广告控制器
 */
class AdsAction extends BaseAction{
	/**
	 * 跳到新增/编辑页面
	 */
	public function toEdit(){
		$this->isLogin();
		//获取地区信息
		$m = D('Admin/Areas');
		$this->assign('areaList',$m->queryShowByList(0));
		//获取商品分类
		$m = D('Admin/GoodsCats');
		$this->assign('goodsCatList',$m->queryByList(0));

	    $m = D('Admin/Ads');
        $this->assign('positionList',$m->getPositionAds());

    	$object = array();
    	if(I('id',0)>0){
    		$this->checkPrivelege('gggl_02');
    		$object = $m->get();
    	}else{
    		$this->checkPrivelege('gggl_01');
    		$object = $m->getModel();
    		$object['adStartDate'] = date('Y-m-d');
    		$object['adEndDate'] = date('Y-m-d');
    		$object['positionType'] = -1;
    	}
    	$this->assign('object',$object);
		$this->view->display('/ads/edit');
	}
	/**
	 * 新增/修改操作
	 */
	public function edit(){
		$this->isLogin();
		$m = D('Admin/Ads');
    	$rs = array();
    	if(I('id',0)>0){
    		$this->checkPrivelege('gggl_02');
    		$rs = $m->edit();
    	}else{
    		$this->checkPrivelege('gggl_01');
    		$rs = $m->insert();
    	}
    	$this->ajaxReturn($rs);
	}
	/**
	 * 删除操作
	 */
	public function del(){
		$this->isLogin();
		$this->checkPrivelege('gggl_03');
		$m = D('Admin/Ads');
    	$rs = $m->del();
    	$this->ajaxReturn($rs);
	}
	/**
	 * 分页查询
	 */
	public function index(){
		$this->isLogin();
		$this->checkPrivelege('gggl_00');
		self::WSTAssigns();
		//获取商品分类
		$m = D('Admin/GoodsCats');
		$this->assign('goodsCatList',$m->queryByList(0));
		$m = D('Admin/Ads');
    	$page = $m->queryByPage();
    	$pager = new \Think\Page($page['total'],$page['pageSize'],I());
    	$pager->setConfig('header','个会员');
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page);
    	$this->assign('positionType',(int)I('positionType',-1));
    	$this->assign('adPositionId',(int)I('adPositionId'));
        $this->display("/ads/list");
	}
	/**
	 * 列表查询
	 */
    public function queryByList(){
    	$this->isLogin();
		$m = D('Admin/Ads');
		$list = $m->queryByList();
		$rs = array();
		$rs['status'] = 1;
		$rs['list'] = $list;
		$this->ajaxReturn($rs);
	}
};
?>