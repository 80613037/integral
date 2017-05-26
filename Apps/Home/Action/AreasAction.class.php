<?php
 namespace Home\Action;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 区域控制器
 */
class AreasAction extends BaseAction{
	/**
	 * 列表查询
	 */
    public function queryByList(){
		$m = D('Home/Areas');
		$list = $m->queryByList((int)I('parentId'));
		$rs = array();
		$rs['status'] = 1;
		$rs['list'] = $list;
		$this->ajaxReturn($rs);
	}
	
    /**
	 * 列表查询[带社区]
	 */
    public function getAreaAndCommunitysByList(){
		$m = D('Home/Areas');
		$areaId = (int)I('areaId');
		$list = $m->queryAreaAndCommunitysByList($areaId);
		$rs = array();
		$rs['status'] = 1;
		$rs['list'] = $list;
		$this->ajaxReturn($rs);
	}
	
	
	/**
	 * 通过省份获取城市列表
	 */
	public function getCityListByProvince(){
		$provinceId = (int)I('provinceId');
		$m = D('Home/Areas');
		$cityList = $m->getCityListByProvince($provinceId);
		$this->ajaxReturn($cityList);
	}
	
	public function getAreasByParentId(){
		$parentId = (int)I('parentId');
		$m = D('Home/Areas');
		$rs = $m->getAreasByParentId($parentId);
		$this->ajaxReturn($rs);
	}
};
?>