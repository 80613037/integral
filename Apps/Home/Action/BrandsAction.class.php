<?php
 namespace Home\Action;;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 品牌控制器
 */
class BrandsAction extends BaseAction{
	
	/**
	 * 列表查询
	 */
    public function index(){
    	$areas= D('Home/Areas');
    	$areaId2 = $this->getDefaultCity();
   		$areaList = $areas->getDistricts($areaId2);
   		$this->assign('areaList',$areaList);
   		
   		if((int)cookie("bstreesAreaId3")){
   			$obj["areaId3"] = (int)cookie("bstreesAreaId3");
   		}else{
   			$obj["areaId3"] = ((int)I('areaId3')>0)?(int)I('areaId3'):$areaList[0]['areaId'];
   			cookie("bstreesAreaId3",$obj["areaId3"]);
   		}
   		$this->assign('areaId3',$obj["areaId3"]);
		$this->display("default/brands_list");
	}
	
	/**
	 * 列表查询
	 */
    public function getBrands(){
		$m = D('Home/Brands');
		$brandslist = $m->queryBrandsByDistrict();
		cookie("bstreesAreaId3",(int)I("areaId3"));
		$this->ajaxReturn($brandslist);
	}
	
	/**
	 * 获取品牌列表
	 */
    public function queryBrandsByCat(){
		$m = D('Home/Brands');
		$brandslist = $m->queryBrandsByCat((int)I('catId'));
		$this->ajaxReturn($brandslist);
	}
	
};
?>