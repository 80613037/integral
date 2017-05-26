<?php
 namespace Admin\Model;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 文章服务类
 */
class ArticlesModel extends BaseModel {
    /**
	  * 新增
	  */
	 public function insert(){
	 	$rd = array('status'=>-1);
	 	$id = (int)I("id",0);
		$data = array();
		$data["catId"] = (int)I("catId");
		$data["articleTitle"] = I("articleTitle");
		$data["isShow"] = (int)I("isShow",0);
		$data["articleContent"] = I("articleContent");
		$data["articleKey"] = I("articleKey");
		$data["staffId"] = (int)session('WST_STAFF.staffId');
		$data["createTime"] = date('Y-m-d H:i:s');
	    if($this->checkEmpty($data,true)){
			$rs = $this->add($data);
		    if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	 } 
     /**
	  * 修改
	  */
	 public function edit(){
	 	$rd = array('status'=>-1);
	 	$id = (int)I("id",0);
		$data = array();
		$data["catId"] = (int)I("catId");
		$data["articleTitle"] = I("articleTitle");
		$data["isShow"] = (int)I("isShow",0);
		$data["articleContent"] = I("articleContent");
		$data["articleKey"] = I("articleKey");
		$data["staffId"] = (int)session('WST_STAFF.staffId');
	    if($this->checkEmpty($data,true)){	
		    $rs = $this->where("articleId=".(int)I('id',0))->save($data);
			if(false !== $rs){
				$rd['status']= 1;
				
			}
		}
		return $rd;
	 } 
	 /**
	  * 获取指定对象
	  */
     public function get(){
		return $this->where("articleId=".(int)I('id'))->find();
	 }
	 /**
	  * 分页列表
	  */
     public function queryByPage(){
	 	$sql = "select a.articleTitle,a.articleId,a.isShow,a.createTime,c.catName,s.staffName
	 	    from __PREFIX__articles a,__PREFIX__article_cats c,__PREFIX__staffs s 
	 	    where a.catId=c.catId and a.staffId = s.staffId ";
	 	if(I('articleTitle')!='')$sql.=" and articleTitle like '%".WSTAddslashes(I('articleTitle'))."%'";
	 	$sql.=' order by articleId desc';
		return $this->pageQuery($sql);
	 }
	 /**
	  * 获取列表
	  */
	  public function queryByList(){
	     $sql = "select * from __PREFIX__articles where isShow =1 order by articleId desc";
		 $rs = $this->query($sql);
		 return $rs;
	  }
	  
	 /**
	  * 删除
	  */
	 public function del(){
	 	$rd = array('status'=>-1);
	    $rs = $this->delete((int)I('id'));
		if(false !== $rs){
		   $rd['status']= 1;
		}
		return $rd;
	 }
	 /**
	  * 显示分类是否显示/隐藏
	  */
	 public function editiIsShow(){
	 	$rd = array('status'=>-1);
	 	if(I('id',0)==0)return $rd;
	 	$this->isShow = ((int)I('isShow')==1)?1:0;
	 	$rs = $this->where("articleId=".(int)I('id',0))->save();
	    if(false !== $rs){
			$rd['status']= 1;
		}
	 	return $rd;
	 }
};
?>