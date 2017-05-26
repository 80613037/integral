<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 商品分类控制器
 */
class GoodsCatsAction extends BaseAction{
	/**
	 * 跳到新增/编辑页面
	 */
	public function toEdit(){
		$this->isLogin();
	    $m = D('Admin/GoodsCats');
    	$object = array();
    	if(I('id',0)>0){
    		$this->checkPrivelege('spfl_02');
    		$object = $m->get(I('id',0));
    		if(I('f')=='api'){
                if(isset($_GET['callback'])){
                    exit($_GET['callback'].'(' . json_encode($object) .')');
                }else
    		    exit(json_encode($object));
            }
    	}else{
    		$this->checkPrivelege('spfl_01');
    		if(I('parentId',0)>0){
    		   $object = $m->get(I('parentId',0));
    		   $object['parentId'] = $object['catId'];
    		   $object['catName'] = '';
    		   $object['catSort'] = 0;
    		   $object['catId'] = 0;
    		}else{
    		   $object = $m->getModel();
    		}
    	}
    	$this->assign('object',$object);
		$this->view->display('/goodscats/edit');
	}
	/**
	 * 新增/修改操作
	 */
	public function edit(){
		$this->isLogin();
		$m = D('Admin/GoodsCats');
    	$rs = array();
    	if(I('id',0)>0){
    		$this->checkPrivelege('spfl_02');
    		$rs = $m->edit();
    	}else{
    		$this->checkPrivelege('spfl_01');
    		$rs = $m->insert();
    	}
        if(isset($_GET['callback'])){
            exit($_GET['callback'].'(' . json_encode($rs) .')');
        }else
    	$this->ajaxReturn($rs);
	}
	/**
	 * 修改名称
	 */
	public function editName(){
		$this->isLogin();
		$m = D('Admin/GoodsCats');
    	$rs = array('status'=>-1);
    	if(I('id',0)>0){
    		$this->checkPrivelege('spfl_02');
    		$rs = $m->editName();
    	}
    	$this->ajaxReturn($rs);
	}
	/**
	 * 删除操作
	 */
	public function del(){
		$this->isLogin();
		$this->checkPrivelege('spfl_03');
		$m = D('Admin/GoodsCats');
    	$rs = $m->del();
    	$this->ajaxReturn($rs);
	}
	/**
	 * 分页查询
	 */
	public function index(){
		$this->isLogin();
		$this->checkPrivelege('spfl_00');
		$m = D('Admin/GoodsCats');
    	$list = $m->getCatAndChild();
    	$this->assign('List',$list);
        $this->display("/goodscats/list");
	}
	/**
	 * 列表查询
	 */
    public function queryByList(){
    	$this->isLogin();
		$m = D('Admin/GoodsCats');
		$list = $m->queryByList(I('id'));
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
	 	$this->checkPrivelege('spfl_02');
	 	$m = D('Admin/GoodsCats');
		$rs = $m->editiIsShow();
		$this->ajaxReturn($rs);
	 }
	 /**
	 * 是否推荐
	 */
	 public function editIsFloor()
	 {
	 	$this->isLogin();
	 	$m = D('Admin/GoodsCats');
		$rs = $m->editIsFloor();
		$this->ajaxReturn($rs);
	 }
};
?>