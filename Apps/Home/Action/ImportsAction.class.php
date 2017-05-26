<?php
 namespace Home\Action;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 数据导入控制器
 */
class ImportsAction extends BaseAction{
	/**
	 * 数据导入首页
	 */
    public function index(){
    	$this->isShopLogin();
    	$this->assign("umark","Imports");
    	$this->display('default/shops/import');
	}
};
?>