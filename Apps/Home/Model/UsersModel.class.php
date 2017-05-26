<?php
namespace Home\Model;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 会员服务类
 */
class UsersModel extends BaseModel {
	
     /**
	  * 获取用户信息
	  */
     public function get($userId=0){

	 	$userId = intval($userId?$userId:I('id',0));
		$user = $this->where("userId=".$userId)->find();
		if(!empty($user) && $user['userType']==1){
			//加载商家信息
		 	$sp = M('shops');
		 	$shops = $sp->where('userId='.$user['userId']." and shopFlag=1")->find();
		 	if(!empty($shops))$user = array_merge($shops,$user);
		}
		return $user;
	 }
	 
	/**
	  * 获取用户信息
	  */
     public function getUserInfo($loginName,$loginPwd){
		$loginPwd = md5($loginPwd);
	 	$rs = $this->where(" loginName ='%s' AND loginPwd ='%s' ",array($loginName,$loginPwd))->find();
	    return $rs;
	 }
	 
	/**
	  * 获取用户信息
	  */
     public function getUserById($obj){
		$userId = (int)$obj["userId"];
	 	$rs = $this->where(" userId ='%s' ",array($userId))->find();
	    return $rs;
	 }
	 public function getUserPtByGoodsid($leaderid){
         // $rs = M('orders_pintuan')->field('buyerid')->where("goodsid =".I('goodsId'))->select();
         $sql = "SELECT u.userId,u.userName,u.userPhoto,op.startdate,op.enddate FROM __PREFIX__users u LEFT JOIN __PREFIX__orders_pintuan op ON u.userId=op.buyerid WHERE op.goodsid=".I('goodsId')." AND leaderid<>buyerid AND leaderid=".$leaderid." ORDER BY op.orderId DESC" ;
         $rs = $this->query($sql);
         return $rs;
     }

    /**
     * 获取信息
     * @param $from
     * @param $uid
     * @return
     */
     public function getLeaderOrUserinfo($from, $uid, $goodsid){
        $rs = $this->where(" userId ='%s' ",array($uid))->find();
        if($from == 'leader'){
            $rs['leaderdate'] = M('orders_pintuan')->field('startdate,enddate')->where('buyerid=leaderid AND leaderid='.$uid.' AND goodsid='.$goodsid)->find();
        }elseif($from == 'user'){
            $rs['leaderdate'] = M('orders_pintuan')->field('startdate,enddate')->where('buyerid<>leaderid AND leaderid='.$uid.' AND goodsid='.$goodsid)->find();
        }
        return $rs;
     }
	 
 	/**
	  * 查询登录名是否存在
	  */
	 public function checkLoginKey($loginName,$id = 0,$isCheckKeys = true){
         if(C('isDevelop')){
             WLog('check','L50','1');
         }
	 	$loginName = WSTAddslashes(($loginName!='')?$loginName:I("loginName"));
        if(C('isDevelop')){WLog('check','L53loginName',$loginName);}
	 	$rd = array('status'=>-1);
	 	if($loginName=='')return $rd;
	 	if($isCheckKeys){
		 	if(!WSTCheckFilterWords($loginName,$GLOBALS['CONFIG']['limitAccountKeys'])){
		 		$rd['status'] = -2;
		 		return $rd;
		 	}
	 	}
	 	$sql = " (loginName ='%s' or userPhone ='%s' or userEmail='%s') and userFlag=1 ";

	    if($id>0){
	 		$sql.=" and userId!=".$id;
	 	}
	 	$rs = $this->where($sql,array($loginName,$loginName,$loginName))->count();
	    if($rs==0){
	    	$rd['status'] = 1;
	    }
         if(C('isDevelop')){WLog('check','L71',json_encode($rd));}
	    return $rd;
	 }
	 
	 /**
	  * 查询并加载用户资料
	  */
	 public function checkAndGetLoginInfo($key){
	 	if($key=='')return array();
	 	$sql = " (loginName ='%s' or userPhone ='%s' or userEmail='%s') and userFlag=1 and userStatus=1 ";
	 	$keyArr = array($key,$key,$key);
	 	$rs = $this->where($sql,$keyArr)->find();
	    return $rs;
	 }
	 
    /**
	 * 用户登录验证
	 */
	public function checkLogin(){
		$loginName = WSTAddslashes(I('loginName'));
		$userPwd = WSTAddslashes(I('loginPwd'));
		$sql ="SELECT * FROM __PREFIX__users WHERE (loginName='".$loginName."' ) AND userFlag=1 and userStatus=1 ";
		$rss = $this->query($sql);
		if(!empty($rss)){
			$rs = $rss[0];
            $rv = array('status'=>-2);
			if($rs['loginPwd']!=md5($userPwd.$rs['loginSecret']))return $rv;
			if($rs['userFlag'] == 1 && $rs['userStatus']==1){
				$data = array();
				$data['lastTime'] = date('Y-m-d H:i:s');
				$data['lastIP'] = get_client_ip();
		    	$this->where(" userId=".$rs['userId'])->data($data)->save();
		    	//如果是店铺则加载店铺信息
		    	if($rs['userType']>=1){
		    		$s = M('shops');
			 		  $shops = $s->where('userId='.$rs['userId']." and shopFlag=1")->find();
			 		  if(!empty($shops))$rs = array_merge($shops,$rs);
		    	}
		    	//记录登录日志
				$data = array();
				$data["userId"] = $rs['userId'];
				$data["loginTime"] = date('Y-m-d H:i:s');
				$data["loginIp"] = get_client_ip();
				M('log_user_logins')->add($data);
                $rs['status'] == 1;
			    $rv = $rs;
			}
		}else{
            $rv = array('status'=>-3); // 账号不存在
        }
		return $rv;
	}
	
	/**
	 * 根据cookie自动登录
	 */
	public function autoLoginByCookie(){
		$loginName = WSTAddslashes($_COOKIE['loginName']);
		$loginKey = $_COOKIE['loginPwd'];
		if($loginKey!='' && $loginName!=''){
			$sql ="SELECT * FROM __PREFIX__users WHERE (loginName='".$loginName."' OR userEmail='".$loginName."' OR userPhone='".$loginName."') AND userFlag=1 and userStatus=1 ";
		    $rs = $this->queryRow($sql);
		    if(!empty($rs) && $rs['userFlag'] == 1 && $rs['userStatus']==1){
		    	//用数据库的记录记性加密核对
			    $datakey = md5($rs['loginName'])."_".md5($rs['loginPwd']);
				$key = C('COOKIE_PREFIX')."_".$rs['loginSecret'];
					
				$base64 = new \Think\Crypt\Driver\Base64();
				$compareKey = $base64->encrypt($datakey, $key);
				//验证成功的话则补上登录信息
				if($compareKey==$loginKey){
					$data = array();
				    $data['lastTime'] = date('Y-m-d H:i:s');
				    $data['lastIP'] = get_client_ip();
				    $m = M('users');
		    	    $m->where(" userId=".$rs['userId'])->data($data)->save();
		    	    //如果是店铺则加载店铺信息
		    	    if($rs['userType']>=1){
		    		     $s = M('shops');
			 		     $shops = $s->where('userId='.$rs['userId']." and shopFlag=1")->find();
			 		     $shops["serviceEndTime"] = str_replace('.5',':30',$shops["serviceEndTime"]);
		                 $shops["serviceEndTime"] = str_replace('.0',':00',$shops["serviceEndTime"]);
		                 $shops["serviceStartTime"] = str_replace('.5',':30',$shops["serviceStartTime"]);
		                 $shops["serviceStartTime"] = str_replace('.0',':00',$shops["serviceStartTime"]);
			 		     $rs = array_merge($shops,$rs);
		    	    }
		    	    //记录登录日志
				    $data = array();
				    $data["userId"] = $rs['userId'];
				    $data["loginTime"] = date('Y-m-d H:i:s');
				    $data["loginIp"] = get_client_ip();
				    M('log_user_logins')->add($data);
				    session('WST_USER',$rs);
				}
			}
		}
	}
	 
	/**
	 * 会员注册
	 */
    public function regist(){
    	$rd = array('status'=>-1);	   
    	
    	$data = array();
    	$data['loginName'] = I('loginName','');
    	$data['loginPwd'] = I("loginPwd");
    	$data['reUserPwd'] = I("reUserPwd");
    	$data['protocol'] = (int)I("protocol");
    	$loginName = $data['loginName'];
        //检测账号是否存在
        $crs = $this->checkLoginKey($loginName);
        if($crs['status']!=1){
	    	$rd['status'] = -2;
	    	$rd['msg'] = ($crs['status']==-2)?"不能使用该账号":"该账号已存在";
	    	return $rd;
	    }
    	if($data['loginPwd']!=$data['reUserPwd']){
    		$rd['status'] = -3;
    		$rd['msg'] = '两次输入密码不一致!';
    		return $rd;
    	}
    	if($data['protocol']!=1){
    		$rd['status'] = -6;
    		$rd['msg'] = '必须同意使用协议才允许注册!';
    		return $rd;
    	}
    	foreach ($data as $v){
    		if($v ==''){
    			$rd['status'] = -7;
    			$rd['msg'] = '注册信息不完整!';
    			return $rd;
    		}
    	}
	    $nameType = (int)I("nameType");
	    $mobileCode = I("mobileCode");
		if($nameType==3 && $GLOBALS['CONFIG']['phoneVerfy']==1){//手机号码
			$verify = session('VerifyCode_userPhone');
			$startTime = (int)session('VerifyCode_userPhone_Time');
			if((time()-$startTime)>120){
				$rd['status'] = -5;
				$rd['msg'] = '验证码已超过有效期!';
				return $rd;
			}
			if($mobileCode=="" || $verify != $mobileCode){
				$rd['status'] = -4;
				$rd['msg'] = '验证码错误!';
				return $rd;
			}
			$loginName = $this->randomLoginName($loginName);
		}else if($nameType==1){//邮箱注册
			$unames = explode("@",$loginName);
			$loginName = $this->randomLoginName($unames[0]);
		}
		if($loginName=='')return $rd;//分派不了登录名
		$data['loginName'] = $loginName;
	    unset($data['reUserPwd']);
	    unset($data['protocol']);
	    //检测账号，邮箱，手机是否存在
	    $data["loginSecret"] = rand(1000,9999);
	    $data['loginPwd'] = md5($data['loginPwd'].$data['loginSecret']);
	    $data['userType'] = 0;
	    $data['userName'] = I('userName');
	    $data['userQQ'] = I('userQQ');
	    $data['userPhone'] = I('loginName');//I('userPhone');
	    $data['userScore'] = I('userScore');
		$data['userEmail'] = I("userEmail");
	    $data['createTime'] = date('Y-m-d H:i:s');
	    $data['userFlag'] = 1;
	    
	   
		$rs = $this->add($data);
		if(false !== $rs){
			$rd['status']= 1;
			$rd['userId']= $rs;
	    	$data = array();
	    	$data['lastTime'] = date('Y-m-d H:i:s');
	    	$data['lastIP'] = get_client_ip();
	    	$this->where(" userId=".$rd['userId'])->data($data)->save();	 		
	    	//记录登录日志
		 	$data = array();
			$data["userId"] = $rd['userId'];
			$data["loginTime"] = date('Y-m-d H:i:s');
			$data["loginIp"] = get_client_ip();
			$m = M('log_user_logins');
			$m->add($data);
	    	
	    } 
		return $rd;
	}

    public function regist_new($phone, $pwd){
        $data = array();
        $data['loginName'] = $data['userPhone'] = $phone;
        //检测账号是否存在
        $crs = $this->checkLoginKey($data['loginName']);
        if($crs['status']!=1){
            $rd['status'] = -2;
            $rd['msg'] = ($crs['status']==-2)?"不能使用该账号":"该账号已存在";
            return $rd;
        }
        //检测账号，邮箱，手机是否存在
        $data["loginSecret"] = rand(1000,9999);
        $data['loginPwd'] = md5($pwd.$data['loginSecret']);
        $data['userType'] = 0;
        $data['userName'] = '昵称';
        $data['userQQ'] = I('userQQ');
        $data['userScore'] = I('userScore');
        $data['userEmail'] = I("userEmail");
        $data['createTime'] = date('Y-m-d H:i:s');
        $data['userFlag'] = 1;

        $rs = $this->add($data);
        if(false !== $rs){
            $rd['status']= 1;
            $rd['userId']= $rs;
            $data = array();
            $data['lastTime'] = date('Y-m-d H:i:s');
            $data['lastIP'] = get_client_ip();
            $this->where(" userId=".$rd['userId'])->data($data)->save();
            //记录登录日志
            $data = array();
            $data["userId"] = $rd['userId'];
            $data["loginTime"] = date('Y-m-d H:i:s');
            $data["loginIp"] = get_client_ip();
            $m = M('log_user_logins');
            $m->add($data);
            // 同步注册 众筹用户
            $pwdzc = md5($pwd); $ct = time();
            $sqlzc = "INSERT INTO fanwe_user(user_name,user_pwd,mobile,is_effect,create_time,update_time,user_level,user_type) VALUES('$phone', '$pwdzc', '$phone', 1, $ct, $ct, 8, 0)";
            $ret = M()->query($sqlzc);
        }
        return $rd;
    }

	
	/**
	 * 随机生成一个账号
	 */
	public function randomLoginName($loginName){
		$chars = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
		//简单的派字母
		foreach ($chars as $key =>$c){
			$crs = $this->checkLoginKey($loginName."_".$c);
			if($crs['status']==1)return $loginName."_".$c;
		}
		//随机派三位数值
		for($i=0;$i<1000;$i++){
			$crs = $this->checkLoginKey($loginName."_".$i);
			if($crs['status']==1)return $loginName."_".$i;
		}
		return '';
	}
	
	/**
	 * 查询用户手机是否存在
	 */
    public function checkUserPhone($userPhone,$userId = 0){
    	$userId = $userId>0?$userId:(int)I("userId");
    	$rd = array('status'=>-3);
		$sql =" userFlag=1 and userPhone='".$userPhone."'";
		if($userId>0){
			$sql .= " AND userId <> $userId";
		}
		$rs = $this->where($sql)->count();
	
	    if($rs==0)$rd['status'] = 1;
	    return $rd;
	}
	
	/**
	 * 修改用户密码
	 */
	public function editPass($id){
		$rd = array('status'=>-1);
		$data = array();
		$data["loginPwd"] = I("newPass");
		if($this->checkEmpty($data,true)){
			$rs = $this->where('userId='.$id)->find();
			//核对密码
			if($rs['loginPwd']==md5(I("oldPass").$rs['loginSecret'])){
				$data["loginPwd"] = md5(I("newPass").$rs['loginSecret']);
				$rs = $this->where("userId=".$id)->save($data);
				if(false !== $rs){
					$rd['status']= 1;
				}
			}else{
				$rd['status']= -2;
			}
		}
		return $rd;
	}
	
	/**
	 * 修改用户资料
	 */
	public function editUser($obj){
        if(C('isDevelop')){ WLog('editUserModel','L323',json_encode($obj).'--'.I("userPhone"));}
		$rd = array('status'=>-1);
		$userPhone = I("userPhone");
		$userId = (int)$obj["userId"];
	    //检测账号是否存在
        $crs = $this->checkLoginKey($userPhone,$userId,false);
        if(C('isDevelop')){ WLog('editUserModel','L329',$userPhone.'--'.$userId);}
        if($crs['status']!=1){
	    	$rd['status'] = -2;
	    	return $rd;
	    }
		$data = array();
		$data["userName"] = I("userName");
		$data["userPhone"] = $userPhone;
        if(C('isDevelop')){ WLog('editUserModel','L337',json_encode($data));}
        $imgFileUrl = $this->base64_upload(I("userPhoto")); // 存放路径
        if(C('isDevelop')){ WLog('editUserModel','L339', $imgFileUrl);}
        if(!$imgFileUrl){
            $rd['status']= 0;
        }else{
            $data["userPhoto"] = $imgFileUrl;
        }
		$rs = $this->where(" userId=".$userId)->data($data)->save();
	    if(false !== $rs){
			$rd['status']= 1;
			$WST_USER = session('WST_USER');
			$WST_USER['userName'] = $data["userName"];
			$WST_USER['userPhone'] = $data["userPhone"];
			$WST_USER['userPhoto'] = $data["userPhoto"];
			session('WST_USER',$WST_USER);
		}
        if(C('isDevelop')){
            WLog('headimg','351',json_encode($rd));
        }
		return $rd;
	}

    /**
     * 接收图片流
     * @param $base64
     * @return bool|string
     */
    public function base64_upload($base64) {
        $base64_image = str_replace(' ', '+', $base64);
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image, $result)){
            //匹配成功
            if($result[2] == 'jpeg'){
                $image_name = uniqid().'.jpg';
            }else{
                $image_name = uniqid().'.'.$result[2];
            }
            $path = WSTRootPath()."/Upload/users/";
            $date = date("Ym",time());
            //如果目录不存在则创建
            !is_dir($path .$date) && mkdir($path."/".$date,0777);
            $image_file = $path .$date."/".$image_name;
            if(C('isDevelop')){
                WLog('headimg','imgsrc372',$image_file);
                WLog('headimg','result',json_encode($result));
            }
            //服务器文件存储路径
            $img = base64_decode(str_replace($result[1], '', $base64_image));
            if (file_put_contents($image_file, $img)){
                return "Upload/users/".$date."/".$image_name;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

	/**
	 * 重置用户密码
	 */
	public function resetPass(){
		$rs = array('status'=>-1);
    	$reset_userId = (int)session('REST_userId');
    	if($reset_userId==0){
    		$rs['msg'] = '无效的用户！';
    		return $rs;
    	}
    	$user = $this->where("userId=".$reset_userId." and userFlag=1 and userStatus=1")->find();
    	if(empty($user)){
    		$rs['msg'] = '无效的用户！';
    		return $rs;
    	}
    	$loginPwd = I('loginPwd');
    	if(trim($loginPwd)==''){
    		$rs['msg'] = '无效的密码！';
    		return $rs;
    	}
    	$data['loginPwd'] = md5($loginPwd.$user["loginSecret"]);
    	$rc = $this->where("userId=".$reset_userId)->save($data);
    	if(false !== $rc){
    	    $rs['status'] =1;
    	}
    	session('REST_userId',null);
    	session('REST_Time',null);
    	session('REST_success',null);
    	session('findPass',null);
    	return $rs;
	}
	
	/**
	 * 检测第三方帐号是否已注册
	 */
	public function checkThirdIsReg($userFrom,$openId){
		$openId = WSTAddslashes($openId);
		$sql = "select userId, userName from __PREFIX__users where userFrom=$userFrom and openId='$openId'";
		$row = $this->queryRow($sql);
		if($row["userId"]>0){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 第三方注册
	 */
    public function thirdRegist($obj){
    	$rd = array('status'=>-1);	   
    	
    	$data = array();
    	$data['loginName'] = $this->randomLoginName(time());
	    $data["loginSecret"] = rand(1000,9999);
	    $data['loginPwd'] = "";
	    $data['userType'] = 0;
	    $data['userName'] = WSTAddslashes($obj["userName"]);
	    $data['userQQ'] = "";
	    $data['userPhoto'] = WSTDownFile($obj["userPhoto"],'./Upload/users/'.date("Y-m/"));
	    $data['createTime'] = date('Y-m-d H:i:s');
	    $data['userFlag'] = 1;
	    $data['userFrom'] = $obj["userFrom"];
	    $data['openId'] = WSTAddslashes($obj["openId"]);
	   
		$rs = $this->add($data);
		if(false !== $rs){
			$rd['status']= 1;
			$rd['userId']= $rs;
	    	$data = array();
	    	$data['lastTime'] = date('Y-m-d H:i:s');
	    	$data['lastIP'] = get_client_ip();
	    	$this->where(" userId=".$rd['userId'])->data($data)->save();	 		
	    	//记录登录日志
		 	$data = array();
			$data["userId"] = $rd['userId'];
			$data["loginTime"] = date('Y-m-d H:i:s');
			$data["loginIp"] = get_client_ip();
			$m = M('log_user_logins');
			$m->add($data);
			
			$user = self::get($rd['userId']);
			if(!empty($user))session('WST_USER',$user);
	    } 
		return $rd;
	}
	/**
	 * 第三方登录
	 */
	public function thirdLogin($obj){
		$rd = array('status'=>-1);
		$openId = WSTAddslashes($obj['openId']);
		$sql = "select * from __PREFIX__users where userStatus=1 and userFlag=1 and userFrom=".$obj['userFrom']." and openId='".$openId."'";
		$row = $this->queryRow($sql);
		if($row["userId"]>0){
			if($row['userType']==1){
				$s = M('shops');
			 	$shops = $s->where('userId='.$row['userId']." and shopFlag=1")->find();
			    if(!empty($shops))$row = array_merge($shops,$row);			
			}
			session('WST_USER',$row);
			$rd["status"] = 1;
			//修改最后登录时间
		    $ldata = array();
		    $ldata['lastTime'] = date('Y-m-d H:i:s');
		    $ldata['lastIP'] = get_client_ip();
		    $this->where('userId='.$row['userId'])->save($ldata);
			//记录登录日志
			$data = array();
			$data["userId"] = $row['userId'];
			$data["loginTime"] = date('Y-m-d H:i:s');
			$data["loginIp"] = get_client_ip();
			M('log_user_logins')->add($data);
		}
		return $rd;
	}

    /**
     * 用户优惠券
     */
	public function userCoup($uid, $status=0){
	    $sql = "select c.id,c.title,c.man,c.jian,c.expire,c.date,c.status as cstatus,uc.getdate,uc.status as ucstatus from __PREFIX__user_coup uc left join __PREFIX__coupon c on c.id=uc.cid where c.status=1 and uc.uid=".$uid;
        if($status != 0){
            $sql .=  " and uc.status=".$status;
        }
        $sql .= " order by c.date desc limit 0,1";
        return $this->query($sql);
    }

    /**
     * 我的收益
     */
    public function profit($uid){
        $uinfo = M('users')->where(array('userId'=>$uid))->getField('1,tjrs1, tjrs2, tjshouyi1, tjshouyi2, tjshouyi3');
        return $uinfo;
    }

    /**
     * 用户交易流水log
     * @param $uid
     * @param $payway 支付方式：1在线支付  3余额支付
     * @param $dataSrc 流水来源：	1:商家结算 2:订单 3:提现
     * @param $moneyRemark 流水备注
     * @param $moneyType 流水标志：1:收入 0:支出
     * @param $money
     * @param $transactionId  交易ID
     */
    public function log_money($uid, $payway, $dataSrc, $moneyRemark ,$moneyType, $money, $transactionId, $useGold, $useCoup){
        $data = array();
        $data['targetId'] = $uid;
        $data['dataSrc'] = $dataSrc;
        $data['moneyRemark'] = $moneyRemark;
        $data['moneyType'] = $moneyType;
        $data['money'] = $money;
        $data['useGold'] = $useGold;
        $data['useCoup'] = $useCoup;
        $data['createTime'] = date("Y-m-d H:i:s", time());
        $data['transactionId'] = $transactionId;
        $data['payType'] = $payway;
        $data['dataFlag'] = 1;
        M('log_moneys')->add($data);
//        WLog('log_money', 'Mark',  M('log_moneys')->getLastSql());
    }

    /**
     * 用户使用签到金币消费log
     * @param $uid
     * @param $gold
     * @param $goldtype  1增加, -1 减少
     */
    public function log_gold($uid, $gold, $detail, $goldtype, $bak){
        $data=array(
            'userId'=>$uid,
            'sc_score'=>$gold,
            'sc_detail'=>$detail,
            'sc_type'=>$goldtype,
            'sc_status'=>1,
            'bak'=>$bak,
            'sc_time'=>date('Y-m-d H:i:s',time()),
            'plus_minus'=>1
        );
        if($gold != 0){
            M("sign_info")->add($data);
        }
    }

    /**
     * 用户个人中心-我的收益-结算
     * @param $uid
     * @param $umoney
     * @return mixed
     */
    public function jiesuan($uid, $umoney){
        $data = array(
            "userMoney" => $umoney,
            "tjshouyi1" => 0,
            "tjshouyi2" => 0,
            "tjshouyi3" => 0
        );
        $res = M("users")->where("userId=".$uid)->save($data);
        WLog('jiesuan','Mark', M("users")->getLastSql());
        if($res){
            $rs['status'] =1;
        }else{
            $rs['status'] =0;
        }
        return $rs;
    }

    /**
     * 注册既得优惠券
     */
    public function getCoupon($uid, $cid){
        $data=array(
            'uid'=>$uid,
            'cid'=>$cid,
            'getdate'=>date('Y-m-d H:i:s'),
            'status'=>1
        );
        $res = M('user_coup')->add($data);
        return $res;
    }

    /**
     * 积分兑换记录
     * $stat 0 未发货；1已发货
     */
    public function exchangeRecord($uid, $stat, $limit){
        if($stat == 1){
            $where = " AND o.orderStatus in (1,2) ";
            $sql = "SELECT o.orderId,og.goodsName,og.exchangeScore,og.goodsThums,og.goodsNums FROM __PREFIX__orders o LEFT JOIN __PREFIX__order_goods og ON o.orderId = og.orderId WHERE o.payType=9 ". $where." AND userId=".$uid." ORDER BY o.orderId DESC limit ".$limit;
        }else{
            $sql = "SELECT o.orderId,og.goodsName,og.exchangeScore,og.goodsThums,og.goodsNums FROM __PREFIX__orders o LEFT JOIN __PREFIX__order_goods og ON o.orderId = og.orderId WHERE o.payType=9 AND o.orderStatus=".$stat." AND userId=".$uid." ORDER BY o.orderId DESC limit ".$limit;
        }
        $data = $this->query($sql);
        return $data;
    }
    public function exDetail(){
        $orderId = I('orderId');
        $sql = "SELECT o.orderNo,o.userName,o.userAddress,o.userPhone,o.createTime,og.goodsName,og.exchangeScore,og.goodsThums,tso.transno,ts.name FROM fys_orders o LEFT JOIN fys_order_goods og ON o.orderId=og.orderId LEFT JOIN fys_trans_orders tso ON o.orderId=tso.orderid LEFT JOIN fys_transport ts ON tso.transid=ts.id WHERE o.orderId=".$orderId;
        return $this->query($sql);
    }

    /**
     * @param $uid
     * @param $status
     * 拼团订单
     */
    public function getOrderPintuan($uid, $limit, $status){
        $where = " ( op.buyerid = ".$uid.") AND op.pintuanStatus =".$status." AND g.isSale=1 ORDER by orderId DESC LIMIT ".$limit;
//        $orders = M('orders_pintuan')->where($where)->order("orderId desc")->limit($limit)->select();
        $sql = "SELECT op.*,g.goodsId,g.goodsName,g.goodsThums,g.shopPrice,g.ptrs,g.ptlastdate FROM __PREFIX__orders_pintuan op LEFT JOIN __PREFIX__goods g ON op.goodsid=g.goodsId WHERE ".$where;
        return $this->query($sql);
    }

    public function pintuanDetails($oid){
        $sql = "SELECT op.buyerid,op.leaderid,op.userName,op.userAddress,op.userPhone,op.orderNo,op.payType,op.pintuanStatus,op.createTime,op.realTotalMoney,op.enddate,op.succpintuan,op.deliverMoney,op.needPay,g.goodsId,g.goodsName,g.goodsThums,g.shopPrice,g.ptrs,g.ptlastdate,g.shopPrice,g.ptrs,a.areaName,op.areaId1 FROM fys_orders_pintuan op LEFT JOIN fys_goods g ON op.goodsid=g.goodsId LEFT JOIN fys_areas a ON a.areaId=op.areaId1 WHERE op.orderId=".$oid;
        return M()->query($sql);
    }

    /**
     * 修改拼团订单状态
     */
    public function setPintuanStatus($oid, $state){
        $data = array('pintuanStatus'=>$state);
        M('orders_pintuan')->where(array('orderId'=>$oid))->save($data);
        if(C('isDevelop')){ WLog('tuan','cc955', M('orders_pintuan')->getLastSql() );}
    }


	
}