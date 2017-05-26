<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 银行控制器
 */
class BanksAction extends BaseAction{
	/**
	 * 跳到新增/编辑页面
	 */
	public function toEdit(){
		$this->isLogin();
	    $m = D('Admin/Banks');
    	$object = array();
    	if(I('id',0)>0){
    		$this->checkPrivelege('yhgl_02');
    		$object = $m->get();
    	}else{
    		$this->checkPrivelege('yhgl_01');
    		$object = $m->getModel();
    	}
    	$this->assign('object',$object);
		$this->view->display('/banks/edit');
	}
	/**
	 * 新增/修改操作
	 */
	public function edit(){
		$this->isLogin();
		$m = D('Admin/Banks');
    	$rs = array();
    	if(I('id',0)>0){
    		$this->checkPrivelege('yhgl_02');
    		$rs = $m->edit();
    	}else{
    		$this->checkPrivelege('yhgl_01');
    		$rs = $m->insert();
    	}
    	$this->ajaxReturn($rs);
	}
	/**
	 * 删除操作
	 */
	public function del(){
		$this->isLogin();
		$this->checkPrivelege('yhgl_03');
		$m = D('Admin/Banks');
    	$rs = $m->del();
    	$this->ajaxReturn($rs);
	}
   
	/**
	 * 分页查询
	 */
	public function index(){
		$this->isLogin();
		$this->checkPrivelege('yhgl_00');
		$m = D('Admin/Banks');
    	$page = $m->queryByPage();
    	$pager = new \Think\Page($page['total'],$page['pageSize']);
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page);
        $this->display("/banks/list");
	}
	/**
	 * 列表查询
	 */
    public function queryByList(){
    	$this->isLogin();
		$m = D('Admin/Banks');
		$list = $m->queryByList();
		$rs = array();
		$rs['status'] = 1;
		$rs['list'] = $list;
		$this->ajaxReturn($rs);
	}
};
?>