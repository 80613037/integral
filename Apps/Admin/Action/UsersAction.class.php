<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 会员控制器
 */
class UsersAction extends BaseAction{
	/**
	 * 跳到新增/编辑页面
	 */
	public function toEdit(){
		$this->isLogin();
	    $m = D('Admin/Users');
    	$object = array();
    	if(I('id',0)>0){
    		$this->checkPrivelege('hylb_02');
            // 获取个人地址列表
            $cityList = $m->queryByCityist(I('id'));
//            echo '<pre>';
//            print_r($cityList);
    		$object = $m->get();
            $this->assign('cityList',$cityList);
    		$this->assign('object',$object);
		    $this->view->display('users/edit');
    	}else{
    		$this->checkPrivelege('hylb_01');
    		$object = $m->getModel();
    		$object['userStatus'] = 1;
    		$this->assign('object',$object);
		    $this->view->display('/users/add');
    	}
	}
	/**
	 * 新增/修改操作
	 */
	public function edit(){
		$this->isLogin();
		$m = D('Admin/Users');
    	$rs = array();
    	if(I('id',0)>0){
    		$this->checkPrivelege('hylb_02');
    		$rs = $m->edit();
    	}else{
    		$this->checkPrivelege('hylb_01');
    		$rs = $m->insert();
    	}
    	$this->ajaxReturn($rs);
	}
	/**
	 * 删除操作
	 */
	public function del(){
		$this->isLogin();
		$this->checkPrivelege('hylb_03');
		$m = D('Admin/Users');
    	$rs = $m->del();
    	$this->ajaxReturn($rs);
	}
    /**
	 * 查看
	 */
	public function toView(){
		$this->isLogin();
		$this->checkPrivelege('hylb_00');
		$m = D('Admin/Users');
		if(I('id')>0){
			$object = $m->get();
			$this->assign('object',$object);
		}
		$this->view->display('/users/view');
	}
	/**
	 * 分页查询
	 */
	public function index(){
		$this->isLogin();
		$this->checkPrivelege('hylb_00');
		$m = D('Admin/Users');
    	$page = $m->queryByPage();
//        var_dump($page['root']);
    	$pager = new \Think\Page($page['total'],$page['pageSize'],I());
    	$page['pager'] = $pager->show();
    	$this->assign('loginName',I('loginName'));
    	$this->assign('userPhone',I('userPhone'));
    	$this->assign('userEmail',I('userEmail'));
    	$this->assign('userType',I('userType',-1));
    	$this->assign('Page',$page);
        $this->display("/users/list");
	}
	/**
	 * 列表查询
	 */
    public function queryByList(){
    	$this->isLogin();
		$m = D('Admin/Users');
		$list = $m->queryByList();
		$rs = array();
		$rs['status'] = 1;
		$rs['list'] = $list;
		$this->ajaxReturn($rs);
	}
	/**
	 * 查询用户账号
	 */
	public function checkLoginKey(){
		$this->isLogin();
		$m = D('Admin/Users');
		$key = I('clientid');
	 	$id = I('id',0);
		$rs = $m->checkLoginKey(I($key),$id);
		$this->ajaxReturn($rs);
	}
	
	/**********************************************************************************************
	  *                                             账号管理                                                                                                                              *
	  **********************************************************************************************/
    /**
     * 获取账号分页列表
     */
    public function queryAccountByPage(){
    	$this->isLogin();
    	$this->checkPrivelege('hyzh_00');
		$m = D('Admin/Users');
    	$page = $m->queryAccountByPage();
    	$pager = new \Think\Page($page['total'],$page['pageSize'],I());
    	$page['pager'] = $pager->show();
    	$this->assign('loginName',I('loginName'));
    	$this->assign('userStatus',I('userStatus',-1));
    	$this->assign('userType',I('userType',-1));
    	$this->assign('Page',$page);
        $this->display("/users/account_list");
	}
	/**
	 * 编辑账号状态
	 */
	public function editUserStatus(){
		$this->isLogin();
		$this->checkPrivelege('hyzh_04');
		$m = D('Admin/Users');
		$rs = $m->editUserStatus();
		$this->ajaxReturn($rs);
	}
	/**
	 * 跳到账号编辑状态
	 */
	public function toEditAccount(){
		$this->isLogin();
		$this->checkPrivelege('hyzh_04');
		$m = D('Admin/Users');
		$object = $m->getAccountById();
		$this->assign('object',$object);
		$this->display("/users/edit_account");
	}
	/**
	 * 编辑账号信息
	 */
	public function editAccount(){
		$this->isLogin();
		$this->checkPrivelege('hyzh_04');
		$m = D('Admin/Users');
		$rs = $m->editAccount();
		$this->ajaxReturn($rs);
	}

    /**
     * 意见反馈
     */
	public function feedback(){
        $this->isLogin();
        $list = M('feedbacks')->order('createTime desc')->select();
        $this->assign('list', $list);
	    $this->display("users/feedback/list");
    }

    public function feedbackDel(){
        $this->isLogin();
        $res = M('feedbacks')->where(array('id'=>I('id',0)))->delete();
        if($res){
            echo 1;
        }else{
            echo 0;
        }
    }
    /**
     * 是否显示/隐藏
     */
    public function changeStatus(){
        $flag = ((int)I('flag')==1)?1:0;
        $rs = M('feedbacks')->where(array('id'=>(int)I('id',0)))->save(array('flag'=>$flag));
        if($rs){
            echo 1;
        }else{
            echo 0;
        }
    }

};
?>