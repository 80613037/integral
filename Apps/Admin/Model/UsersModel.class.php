<?php
 namespace Admin\Model;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 会员服务类
 */
class UsersModel extends BaseModel {
    /**
	  * 新增
	  */
	 public function insert(){
	 	$rd = array('status'=>-1);
	 	//检测账号
	 	$hasLoginName = self::checkLoginKey(I("loginName"));
	    if($hasLoginName['status']<=0){
	 		$rd = array('status'=>-2);
	 		return $rd;
	 	}
	 	if(I("userPhone")!=''){
	 		$hasUserPhone = self::checkLoginKey(I("userPhone"));
		 	if($hasUserPhone['status']<=0){
		 		$rd = array('status'=>-2);
		 		return $rd;
		 	}
	 	}
	 	if(I("userEmail")!=''){
		 	$hasUserEmail = self::checkLoginKey(I("userEmail"));
		 	if($hasUserEmail['status']<=0){
		 		$rd = array('status'=>-2);
		 		return $rd;
		 	}
	 	}
	 	//创建数据
	 	$id = I("id",0);
		$data = array();
		$data["loginName"] = $phone = I("loginName");
		$data["loginSecret"] = rand(1000,9999);
		$data["loginPwd"] = md5(I('loginPwd').$data['loginSecret']);
		if($this->checkEmpty($data)){
			$data["userPhoto"] = I("userPhoto");
			$data["userName"] = I("userName");
			$data["userStatus"] = I("userStatus",0);
			$data["userType"] = (int)I("userType",0);
			$data["userSex"] = (int)I("userSex",0);
			$data["userEmail"] = I("userEmail");
			$data["userPhone"] = I("userPhone");
			$data["userQQ"] = I("userQQ");
            $data["userMoney"] = I("userMoney",0);
			$data["userScore"] = I("userScore",0);
		    $data["userTotalScore"] = I("userTotalScore",0);
		    $data["userFlag"] = 1;
		    
		    $data["createTime"] = date('Y-m-d H:i:s');
			$rs = $this->add($data);
			if(false !== $rs){
				$rd['status']= 1;
                // 同步注册 众筹用户
                $pwdzc = md5(I('loginPwd')); $ct = time();
                $sqlzc = "INSERT INTO fanwe_user(user_name,user_pwd,mobile,is_effect,create_time,update_time,user_level,user_type) VALUES('$phone', '$pwdzc', '$phone', 1, $ct, $ct, 8, 0)";
                $ret = M()->query($sqlzc);
			}
		}
		return $rd;
	 } 
     /**
	  * 修改
	  */
	 public function edit(){
	 	$rd = array('status'=>-1);
	 	$id = (int)I('id',0);
	 	//检测账号
	 	if(I("userPhone")!=''){
	        $hasUserPhone = self::checkLoginKey(I("userPhone"),$id);
	        if($hasUserPhone['status']<=0){
		        $rd = array('status'=>-2);
		 		return $rd;
	        }
	 	}
	 	if(I("userEmail")!=''){
		 	$hasUserEmail = self::checkLoginKey(I("userEmail"),$id);
		 	if($hasUserEmail['status']<=0){
		 		$rd = array('status'=>-2);
		 		return $rd;
		 	}
		 	
	 	}
	 	//修改数据
		$data = array();
        $data["userMoney"] = (int)I("userMoney",0);
		$data["userScore"] = (int)I("userScore",0);
		$data["userTotalScore"] = (int)I("userTotalScore",0);
		if($this->checkEmpty($data,true)){	
			$data["userName"] = I("userName");
			$data["userPhoto"] = I("userPhoto");
			$data["userSex"] = (int)I("userSex",0);
		    $data["userQQ"] = I("userQQ");
		    $data["userPhone"] = I("userPhone");
		    $data["userEmail"] = I("userEmail");
			$rs = $this->where("userId=".$id)->save($data);
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
		return $this->where("userId=".(int)I('id'))->find();
	 }
	 /**
	  * 分页列表
	  */
     public function queryByPage(){
        $map = array();
	 	$sql = "select * from __PREFIX__users where userFlag=1 ";
	 	if(I('loginName')!='')$sql.=" and loginName LIKE '%".WSTAddslashes(I('loginName'))."%'";
	 	if(I('userPhone')!='')$sql.=" and userPhone LIKE '%".WSTAddslashes(I('userPhone'))."%'";
	 	if(I('userEmail')!='')$sql.=" and userEmail LIKE '%".WSTAddslashes(I('userEmail'))."%'";
	 	if(I('userType',-1)!=-1)$sql.=" and userType=".I('userType',-1);
	 	$sql.="  order by userId desc";
		$rs = $this->pageQuery($sql);
		//计算等级
		if(count($rs)>0){
			$m = M('user_ranks');
			$urs = $m->select();
			foreach ($rs['root'] as $key=>$v){
				foreach ($urs as $rkey=>$rv){
					if($v['userTotalScore']>=$rv['startScore'] && $v['userTotalScore']<$rv['endScore']){
					   $rs['root'][$key]['userRank'] = $rv['rankName'];
					}
				}
			}
		}
		return $rs;
	 }
	 /**
	  * 获取列表
	  */
	  public function queryByList(){
	     $sql = "select * from __PREFIX__users order by userId desc";
		 $rs = $this->find($sql);
		 return $rs;
	  }
	  
	 /**
	  * 删除
	  */
	 public function del(){
	 	$rd = array('status'=>-1);
	 	$id = (int)I('id');
	 	$m = M('users');
	 	//获取用户类型
	 	$userType = $m->where('userId='.$id)->getField('userType',1);
	 	$m->userFlag = -1;
		$rs = $m->where(" userId=".$id)->save();
		if(false !== $rs){
		   $rd['status']= 1;
		   //如果是商家还要下架他的商品
		   if($userType==1){
		   	    $m = M('shops');
		   	    $m->shopFlag = -1;
		   	    $m->shopStatus=-2;
		   	    $rs = $m->where(" userId=".$id)->save();
		   	    $shopId = $m->where('userId='.$id)->getField('shopId',1);
				$sql = "update __PREFIX__goods set isSale=0,goodsStatus=-1 where shopId=".$shopId;
			 	$this->execute($sql);
		   }
		}
		
		return $rd;
	 }
	 /**
	  * 查询登录关键字
	  */
	 public function checkLoginKey($val,$id = 0){
	 	$rd = array('status'=>-1);
	 	if($val=='')return $rd;
	 	$sql = " (loginName ='%s' or userPhone ='%s' or userEmail='%s') and userFlag=1";
	 	$keyArr = array($val,$val,$val);
	 	if($id>0){
	 		$sql.=" and userId!=".$id;
	 	}
	 	$rs = $this->where($sql,$keyArr)->count();
	    if($rs==0)$rd['status'] = 1;
	    return $rd;
	 }
	 
	 
	 /**********************************************************************************************
	  *                                             账号管理                                                                                                                              *
	  **********************************************************************************************/
     /**
      * 获取账号分页列表
      */
	 public function queryAccountByPage(){
	 	$sql = "select * from __PREFIX__users where userFlag=1 ";
	 	if(I('loginName')!='')$sql.=" and loginName LIKE '%".WSTAddslashes(I('loginName'))."%'";
	 	if(I('userStatus',-1)!=-1)$sql.=" and userStatus=".(int)I('userStatus',-1);
	 	if(I('userType',-1)!=-1)$sql.=" and userType=".(int)I('userType',-1);
	 	$sql.="  order by userId desc";
		$rs = $this->pageQuery($sql);
		//计算等级
		if(count($rs)>0){
			$m = M('user_ranks');
			$urs = $m->select();
			foreach ($rs['root'] as $key=>$v){
				foreach ($urs as $rkey=>$rv){
					if($v['userTotalScore']>=$rv['startScore'] && $v['userTotalScore']<$rv['endScore']){
					   $rs['root'][$key]['userRank'] = $rv['rankName'];
					}
				}
			}
		}
		return $rs;
	 }
	 /**
	  * 编辑账号状态
	  */
	 public function editUserStatus(){
	 	$rd = array('status'=>-1);
	 	if(I('id',0)==0)return $rd;
	 	$this->userStatus = (I('userStatus')==1)?1:0;
	 	$rs = $this->where("userId=".(int)I('id',0))->save();
	    if(false !== $rs){
			$rd['status']= 1;
		}
	 	return $rd;
	 }
	 /**
	  * 获取账号信息
	  */
	 public function getAccountById(){
		 $rs = $this->where('userId='.(int)I('id',0))->getField('userId,loginName,userStatus,userType',1);
		 return current($rs);
	 }
	 /**
	  * 修改账号信息
	  */
	 public function editAccount(){
	 	$rd = array('status'=>-1);
	 	if(I('id')=='')return $rd;
	 	$loginSecret = $this->where("userId=".(int)I('id'))->getField('loginSecret');
	 	if(I('loginPwd')!='')$this->loginPwd = md5(I('loginPwd').$loginSecret);
	 	$this->userStatus = (int)I('userStatus',0);
	 	$rs = $this->where('userId='.(int)I('id'))->save();
	    if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	 }

    /**
     * 获取地址列表
     */
    public function queryByCityist($userId){
        $sql = "select ua.*,a1.areaName areaName1,a2.areaName areaName2,a3.areaName areaName3,c.communityName
	              from __PREFIX__user_address ua 
	              left join __PREFIX__areas a1 on a1.areaId=ua.areaId1 and a1.isShow=1 and a1.areaFlag=1
	              left join __PREFIX__areas a2 on a2.areaId=ua.areaId2 and a2.isShow=1 and a2.areaFlag=1
	              left join __PREFIX__areas a3 on a3.areaId=ua.areaId3 and a3.isShow=1 and a3.areaFlag=1
	              left join __PREFIX__communitys c on c.communityId=ua.communityId and c.isShow=1
	              where ua.userId=".(int)$userId;
        return $this->query($sql);
    }



};
?>