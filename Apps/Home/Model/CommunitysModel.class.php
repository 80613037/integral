<?php
 namespace Home\Model;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 社区服务类
 */
class CommunitysModel extends BaseModel {
     /**
	  * 根据县区获取社区列表
	  */
	  public function getByDistrict($areaId3 = 0){
	  	 $w = " communityFlag=1 AND isShow = 1 AND areaId3=$areaId3 ";
		 $rs=  $this->cache('WST_CACHE_CITY_004_'.$areaId3,31536000)->where($w)->field('communityId,communityName')->order('communitySort asc')->select();
		 return $rs;
	  }
	  
	/**
	  * 根据城市获取社区列表
	  */
	public function getCommunityList($areaId2){
	    $sql = "SELECT * FROM __PREFIX__communitys WHERE areaId2=$areaId2 AND communityFlag=1 AND isShow = 1 ORDER BY areaId3, communitySort";
	    $rs = $this->query($sql);
		return $rs;
	}
	
    /**
	 * 获取列表
	 */
	public function queryByListByArea($areaId3){
		return $this->where('areaId3='.$areaId3)->select();
	}
};
?>