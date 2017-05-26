<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 控制器
 */
class TransportAction extends BaseAction{
	/**
	 * 跳到新增/编辑页面
	 */
	public function toEdit(){
		$this->isLogin();
	    $m = D('Admin/transport');
    	if(I('id',0)>0){
    		$this->checkPrivelege('kdgl_02');
    		$object = $m->get();
    	}else{
    		$this->checkPrivelege('kdgl_01');
    		$object = $m->getModel();
    	}
    	$this->assign('object',$object);
		$this->view->display('/transport/edit');
	}
	/**
	 * 新增/修改操作
	 */
	public function edit(){
		$this->isLogin();
		$m = D('Admin/transport');
    	$rs = array();
    	if(I('id',0)>0){
    		$this->checkPrivelege('kdgl_02');
    		$rs = $m->edit();
    	}else{
    		$this->checkPrivelege('kdgl_01');
    		$rs = $m->insert();
    	}
    	$this->ajaxReturn($rs);
	}
	/**
	 * 删除操作
	 */
	public function del(){
		$this->isLogin();
		$this->checkPrivelege('kdgl_03');
		$m = D('Admin/transport');
    	$rs = $m->del();
    	$this->ajaxReturn($rs);
	}
   /**
	 * 查看
	 */
	public function toView(){
		$this->isLogin();
		$this->checkPrivelege('kdgl_00');
		$m = D('Admin/transport');
		if(I('id')>0){
			$object = $m->get();
			$this->assign('object',$object);
		}
		$this->view->display('/transport/view');
	}
	/**
	 * 分页查询
	 */
	public function index(){
		$this->isLogin();
		$this->checkPrivelege('kdgl_00');
		$m = D('Admin/transport');
    	$page = $m->queryByPage();
    	$pager = new \Think\Page($page['total'],$page['pageSize'],I());// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page);
        $this->display("/transport/list");
	}
	/**
	 * 列表查询
	 */
    public function queryByList(){
    	$this->isLogin();
		$m = D('Admin/transport');
		$list = $m->queryByList();
		$rs = array();
		$rs['status'] = 1;
		$rs['list'] = $list;
		$this->ajaxReturn($rs);
	}
    /**
	 * 显示商品是否显示/隐藏
	 */
	 public function editiIsShow(){
	 	$this->isLogin();
	 	$this->checkPrivelege('kdgl_02');
	 	$m = D('Admin/transport');
		$rs = $m->editiIsShow();
		$this->ajaxReturn($rs);
	 }
};
?>