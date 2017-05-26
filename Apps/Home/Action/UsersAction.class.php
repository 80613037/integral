<?php
namespace Home\Action;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 会员控制器
 */
class UsersAction extends BaseAction {
    /**
     * 跳去登录界面
     */
	public function login(){
		//如果已经登录了则直接跳去后台
		$USER = session('WST_USER');
		if(!empty($USER) && $USER['userId']!=''){
			$this->redirect("Users/index");
		}
		if(isset($_COOKIE["loginName"])){
			$this->assign('loginName',$_COOKIE["loginName"]);
		}else{
			$this->assign('loginName','');
		}
		$this->assign('qqBackUrl',urlencode(WSTDomain()."/Wstapi/thridLogin/qqlogin.php"));
		$this->assign('wxBackUrl',urlencode(WSTDomain()."/Wstapi/thridLogin/wxlogin.php"));
		$this->display('mobile/login');
	}
	
	
	/**
	 * 用户退出
	 */
	public function logout(){
		session('WST_USER',null);
		setcookie("loginPwd", null);
		echo "1";
	}
	
	/**
     * 注册界面
     * 
     */
	public function regist(){
		if(isset($_COOKIE["loginName"])){
			$this->assign('loginName',$_COOKIE["loginName"]);
		}else{
			$this->assign('loginName','');
		}
		$this->display('mobile/regist');
	}

	/**
	 * 验证登陆
	 * 
	 */
	public function checkLogin(){
	    $rs = array();
	    $rs["status"]= 1;
        $m = D('Home/Users');
        $res = $m->checkLogin();

        if (!empty($res)){
            if($res['userFlag'] == 1){
                session('WST_USER',$res);



                // 众筹自动登录，保存cookie
                $loginZc = I('loginName');
		        $pwdZc = I('loginPwd');
                setCookie("email",$loginZc,time()+3600*24*30);
                setCookie("user_pwd",md5($pwdZc."_EASE_COOKIE"),time()+3600*24*30);
                if(C('isDevelop')){
                    WLog('log','L72_1', $_COOKIE);
                    WLog('log','L72_2', $_COOKIE['email']);
                    WLog('log','L72_3', $_COOKIE['user_pwd']);
                }
                unset($_SESSION['toref']);
                if(strripos($_SESSION['refer'],"regist")>0 || strripos($_SESSION['refer'],"logout")>0 || strripos($_SESSION['refer'],"login")>0){
                    $rs["refer"]= __ROOT__;
                }
            }else if($res['status'] == 1) {
                $rs["status"] = 1; // 登录成功
            }else if($res['status'] == -2) {
                $rs["status"] = -2; // 登陆失败，密码错误
            }else {
                $rs["status"] = -3; // 登陆失败，不存在账号
            }
        } else {
            $rs["status"]= -3; // 登陆失败，不存在账号
        }
        if(I('from',0) == 'user'){
            $rs["refer"] = "/index.php?m=Home&c=Users&a=index";
        }elseif(I('from',0) == 'mall'){
            $rs["refer"] = "/index.php?m=Home&c=Index&a=mall";
        }else{
            $rs["refer"] = __ROOT__;
        }
		echo json_encode($rs);
	}

	function analogToLogin(){

    }

	/**
	 * 新用户注册
	 */
	public function toRegist(){
	    WLog('regist', 'phone:'.I('phone').';code:'.I('code'));
        $phone = I('phone', 0);
        $pwd = I('pwd', 0);
        $code = I('code', 0);
//        cache('set', 'code', 'sss',10);
        $cacheCode = 8888; // cache('get', 'code');
        if($code == $cacheCode){
            $m = D('Home/Users');
            $res = $m->regist_new($phone, $pwd);
            if($res['userId']>0){//注册成功
                //加载用户信息
                $user = $m->get($res['userId']);
                if(!empty($user))session('WST_USER',$user);
                // 注册即获得优惠券
                $cid = M('coupon')->field('id')->where('status=1')->find();
                $m->getCoupon($res['userId'], $cid['id']);
            }
        }else{
            $res['status'] = -4;
            $res['msg'] = '验证码错误!';
        }
        echo json_encode($res);
	}
    
 	/**
	 * 获取验证码
	 */
	public function getPhoneVerifyCode(){
		$userPhone = WSTAddslashes(I("userPhone"));
		$rs = array();
		if(!preg_match("#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#",$userPhone)){
			$rs["msg"] = '手机号格式不正确!';
			echo json_encode($rs);
			exit();
		}
		$m = D('Home/Users');
		$rs = $m->checkUserPhone($userPhone,(int)session('WST_USER.userId'));
		if($rs["status"]!=1){
			$rs["msg"] = '手机号已存在!';
			echo json_encode($rs);
			exit();
		}
		$phoneVerify = rand(100000,999999);
		$msg = "欢迎您注册成为".$GLOBALS['CONFIG']['mallName']."会员，您的注册验证码为:".$phoneVerify."，请在30分钟内输入。【".$GLOBALS['CONFIG']['mallName']."】";
		$rv = D('Home/LogSms')->sendSMS(0,$userPhone,$msg,'getPhoneVerifyByRegister',$phoneVerify);
		if($rv['status']==1){
			session('VerifyCode_userPhone',$phoneVerify);
			session('VerifyCode_userPhone_Time',time());
			//$rs["phoneVerifyCode"] = $phoneVerify;
		}
		echo json_encode($rv);
	}
   /**
    * 会员中心页面
    */
	public function index(){
//		$this->isUserLogin();
//		$this->redirect("Orders/queryByPage");
        $u = D('Home/Users');
        $uinfo = $u->getUserById(session('WST_USER'));
        $this->assign('uinfo', $uinfo);
        $this->assign('s','user');
        $this->display('mobile/users/index');
	}
	
   /**
    * 跳到修改用户密码
    */
	public function toEditPass(){
		$this->isLogin();
		$this->assign("umark","toEditPass");
		$this->display("default/users/edit_pass");
	}
	
	/**
	 * 修改用户密码
	 */
	public function editPass(){
		$this->isLogin();
		$USER = session('WST_USER');
		$m = D('Home/Users');
   		$rs = $m->editPass($USER['userId']);
    	$this->ajaxReturn($rs);
	}
	/**
	 * 跳去修改买家资料
	 */
	public function toEdit(){
		$this->isLogin();
		$m = D('Home/Users');
		$obj["userId"] = session('WST_USER.userId');
		$user = $m->getUserById($obj);
        if(C('isDevelop')){WLog('sql','L181',$m->getLastSql());}
		//判断会员等级
		$USER = session('WST_USER');
		$rm = D('Home/UserRanks');
		$USER["userRank"] = $rm->getUserRank();
		session('WST_USER',$USER);
		
		$this->assign("user",$user);
		$this->assign("umark","toEditUser");
		$this->display("mobile/users/edit_user");
	}
	
	/**
	 * 跳去修改买家资料
	 */
	public function editUser(){
        if(C('isDevelop')){WLog('editUser','L197',time());}
		$this->isLogin();
		$m = D('Home/Users');
		$obj["userId"] = session('WST_USER.userId');
		$data = $m->editUser($obj);
        if(C('isDevelop')){WLog('sql','L202',json_encode($data));}
		$this->ajaxReturn($data);
	}
	
	/**
	 * 判断手机或邮箱是否存在
	 */
	public function checkLoginKey(){
		$m = D('Home/Users');
		$key = I('clientid');
		$userId = (int)session('WST_USER.userId');
		$rs = $m->checkLoginKey(I($key),$userId);
		if($rs['status']==1){
			$rs['msg'] = "该账号可用";
		}else if($rs['status']==-2){
			$rs['msg'] = "不能使用该账号";
		}else{
			$rs['msg'] = "该账号已存在";
		}
		$this->ajaxReturn($rs);
	}
	/**
	 * 忘记密码
	 */
    public function forgetPass(){
        if((int)I('ss')==2){
            session('step',2);
            $this->display('mobile/forget_pass2');
        }else{
            session('step',1);
            $this->display('mobile/forget_pass');
        }
    }
    /**
     * 找回密码
     */
    public function findPass(){
    	//禁止缓存
    	header('Cache-Control:no-cache,must-revalidate');  
		header('Pragma:no-cache');
    	$step = (int)I('step');
    	switch ($step) {
    		case 1:#第二步，验证身份
                $code = I('resetcode', 0);
                $cacheresetCode = 8888; // cache('get', 'resetcode');
                if($code != $cacheresetCode){
                    $rs['msg'] = "验证码错误！";
    				$rs['status'] = -4;
                    $this->ajaxReturn($rs);
                    WLog('266', json_encode($rs));
    			}
    			$loginName = I('phone');
    			$m = D('Home/Users');
    			$info = $m->checkAndGetLoginInfo($loginName);
                WLog('info', json_encode($info));
    			if ($info != false) {
                    WLog('info1', json_encode($info));
    				session('findPass',array('userId'=>$info['userId'],'loginName'=>$loginName,'userPhone'=>$info['userPhone'],'userEmail'=>$info['userEmail'],'loginSecret'=>$info['loginSecret']) );
    				if($info['userPhone']!='')$info['userPhone'] = WSTStrReplace($info['userPhone'],'*',3);
    				if($info['userEmail']!='')$info['userEmail'] = WSTStrReplace($info['userEmail'],'*',2,'@');
                    $rs['msg'] = "下一步！";
                    $rs['status'] = 1;
                    $this->ajaxReturn($rs);
                    WLog('281', json_encode($rs));
//    				$this->display('default/forget_pass2');
    			}else{
                    $rs['msg'] = "该手机号未注册！";
                    $rs['status'] = -3;
                    $this->ajaxReturn($rs);
                    WLog('288', json_encode($rs));
                }
                $this->ajaxReturn($rs);
    			break;
    		case 2:#第三步,设置新密码
                $loginName = I('phone'); $newPwd = I('pwd');
                $m = D('Home/Users');
                $info = $m->checkAndGetLoginInfo($loginName);
                $data['loginPwd'] = md5($newPwd.$info['loginSecret']);
                $res = M('users')->where('userId='.$info['userId'])->save($data);
                if($res){
                    $rs['status'] = 1;
                }else{
                    $rs['status'] = -1;
                }
                $this->ajaxReturn($rs);
    			break;
    		case 3:#设置成功
    			$resetPass = session('REST_success');
    			if($resetPass!='1')$this->error("非法的操作!");
                $loginPwd = I('loginPwd');
                $repassword = I('repassword');
                if ($loginPwd == $repassword) {
	                $rs = D('Home/Users')->resetPass();
			    	if($rs['status']==1){
			    	    $this->display('default/forget_pass4');
			    	}else{
			    		$this->error($rs['msg']);
			    	}
                }else $this->error('两次密码不同！');
    			break;
    		default:
    			$this->error('页面过期！'); 
    			break;
    	}
        $this->ajaxReturn($rs);
    }

	/**
	 * 手机验证码获取
	 */
	public function getPhoneVerify(){
		$rs = array('status'=>-1);
		if(session('findPass.userPhone')==''){
			$this->ajaxReturn($rs);
		}
		$phoneVerify = mt_rand(100000,999999);
		$USER = session('findPass');
		$USER['phoneVerify'] = $phoneVerify;
        session('findPass',$USER);
		$msg = "您正在重置登录密码，验证码为:".$phoneVerify."，请在30分钟内输入。【".$GLOBALS['CONFIG']['mallName']."】";
		$rv = D('Home/LogSms')->sendSMS(0,session('findPass.userPhone'),$msg,'getPhoneVerify',$phoneVerify);
		$rv['time']=30*60;
		$this->ajaxReturn($rv);
	}

	/**
	 * 手机验证码检测
	 * -1 错误，1正确
	 */
	public function checkPhoneVerify(){
		$phoneVerify = I('phoneVerify');
		$rs = array('status'=>-1);
		if (session('findPass.phoneVerify') == $phoneVerify ) {
			//获取用户信息
			$user = D('Home/Users')->checkAndGetLoginInfo(session('findPass.userPhone'));
			$rs['u'] = $user;
			if(!empty($user)){
				$rs['status'] = 1;
				$keyFactory = new \Think\Crypt();
			    $key = $keyFactory->encrypt("0_".$user['userId']."_".time(),C('SESSION_PREFIX'),30*60);
				$rs['url'] = "http://".$_SERVER['HTTP_HOST'].U('Home/Users/toResetPass',array('key'=>$key));
			}
		}
		$this->ajaxReturn($rs);
	}

	/**
	 * 发送验证邮件
	 */
	public function getEmailVerify(){
		$rs = array('status'=>-1);
		$keyFactory = new \Think\Crypt();
		$key = $keyFactory->encrypt("0_".session('findPass.userId')."_".time(),C('SESSION_PREFIX'),30*60);
		$url = "http://".$_SERVER['HTTP_HOST'].U('Home/Users/toResetPass',array('key'=>$key));
		$html="您好，会员 ".session('findPass.loginName')."：<br>
		您在".date('Y-m-d H:i:s')."发出了重置密码的请求,请点击以下链接进行密码重置:<br>
		<a href='".$url."'>".$url."</a><br>
		<br>如果您的邮箱不支持链接点击，请将以上链接地址拷贝到你的浏览器地址栏中。<br>
		该验证邮件有效期为30分钟，超时请重新发送邮件。<br>
		<br><br>*此邮件为系统自动发出的，请勿直接回复。";
		$sendRs = WSTSendMail(session('findPass.userEmail'),'密码重置',$html);
		if($sendRs['status']==1){
			$rs['status'] = 1;
		}else{
			$rs['msg'] = $sendRs['msg'];
		}
		$this->ajaxReturn($rs);
	}
	
    /**
     * 跳到重置密码
     */
    public function toResetPass(){
    	$key = I('key');
	    $keyFactory = new \Think\Crypt();
		$key = $keyFactory->decrypt($key,C('SESSION_PREFIX'));
		$key = explode('_',$key);
		if(time()>floatval($key[2])+30*60)$this->error('连接已失效！');
		if(intval($key[1])==0)$this->error('无效的用户！');
		session('REST_userId',$key[1]);
		session('REST_Time',$key[2]);
		session('REST_success','1');
		$this->display('default/forget_pass3');
    }
    
    /**
     * 跳去用户登录的页面
     */
    public function toLoginBox(){
        if(isset($_COOKIE["loginName"])){
			$this->assign('loginName',$_COOKIE["loginName"]);
		}else{
			$this->assign('loginName','');
		}
		$this->assign('qqBackUrl',urlencode(WSTDomain()."/Wstapi/thridLogin/qqlogin.php"));
		$this->assign('wxBackUrl',urlencode(WSTDomain()."/Wstapi/thridLogin/wxlogin.php"));
    	$this->display('default/login_box');
    }
    
    /**
     * 查看积分记录
     */
    public function toScoreList(){
    	$this->isUserLogin();
    	$um = D('Home/Users');  //
    	$user = $um->getUserById(array("userId"=>session('WST_USER.userId')));
        $uScoreInfo = M('user_score')->where("userId=".session('WST_USER.userId')." and score<>0")->order("createTime desc")->select();
        $this->assign('uScoreInfo', $uScoreInfo);
    	$this->assign("userScore",$user['userScore']);
    	$this->assign("umark","toScoreList");
    	$this->display("mobile/users/score_list");
    }
    
    /**
     * 查看积分记录
     */
    public function getScoreList(){
    	$this->isUserLogin();
    	$m = D('Home/UserScore');
    	$rs = $m->getScoreList();
    	$this->ajaxReturn($rs);
    }
    
    /**
     * QQ登录回调方法
     */
	public function qqLoginCallback(){
    	header ( "Content-type: text/html; charset=utf-8" );
    	vendor ( 'ThirdLogin.QqLogin' );

    	$appId = $GLOBALS['CONFIG']["qqAppId"];
    	$appKey = $GLOBALS['CONFIG']["qqAppKey"];
    	//回调接口，接受QQ服务器返回的信息的脚本
    	$callbackUrl = WSTDomain()."/Wstapi/thridLogin/qqlogin.php";
    	//实例化qq登陆类，传入上面三个参数
    	$qq = new \QqLogin($appId,$appKey,$callbackUrl);
    	//得到access_token验证值
    	$accessToken = $qq->getToken();
    	if(!$accessToken){
    		$this->redirect("Home/Users/login");
    	}
    	//得到用户的openid(登陆用户的识别码)和Client_id
    	$arr = $qq->getClientId($accessToken);
    	if(isset($arr['client_id'])){
    		$clientId = $arr['client_id'];
    		$openId = $arr['openid'];
    		$um = D('Home/Users');
    		//已注册，则直接登录
    		if($um->checkThirdIsReg(1,$openId)){
    			$obj["openId"] = $openId;
    			$obj["userFrom"] = 1;
    			$rd = $um->thirdLogin($obj);
    			if($rd["status"]==1){
    				$this->redirect("Home/Index/index");
    			}else{
    				$this->redirect("Home/Users/login");
    			}
    		}else{
    			//未注册，则先注册
    			$arr = $qq->getUserInfo($clientId,$openId,$accessToken);
    			$obj["userName"] = $arr["nickname"];
    			$obj["openId"] = $openId;
    			$obj["userFrom"] = 1;
    			$obj["userPhoto"] = $arr["figureurl_2"];
    			$um->thirdRegist($obj);
    			$this->redirect("Home/Index/index");
    		}
    	}else{
    		$this->redirect("Home/Users/login");
    	}
    }
    
    /**
     * 微信登录回调方法
     */
	public function wxLoginCallback(){
    	header ( "Content-type: text/html; charset=utf-8" );
    	vendor ( 'ThirdLogin.WxLogin' );

    	$appId = $GLOBALS['CONFIG']["wxAppId"];
    	$appKey = $GLOBALS['CONFIG']["wxAppKey"];

    	$wx = new \WxLogin($appId,$appKey);
    	//得到access_token验证值
    	$accessToken = $wx->getToken();
    	
    	if(!$accessToken){
    		$this->redirect("Home/Users/login");
    	}
    	//得到用户的openid(登陆用户的识别码)和Client_id
    	$openId = $wx->getOpenId();
    	if($openId!=""){
    		$um = D('Home/Users');
    		//已注册，则直接登录
    		if($um->checkThirdIsReg(2,$openId)){
    			$obj["openId"] = $openId;
    			$obj["userFrom"] = 2;
    			$rd = $um->thirdLogin($obj);
    			if($rd["status"]==1){
    				$this->redirect("Home/Index/index");
    			}else{
    				$this->redirect("Home/Users/login");
    			}
    		}else{
    			//未注册，则先注册
    			$arr = $wx->getUserInfo($openId,$accessToken);
    			$obj["userName"] = $arr["nickname"];
    			$obj["openId"] = $openId;
    			$obj["userFrom"] = 2;
    			$obj["userPhoto"] = $arr["headimgurl"];
    			$um->thirdRegist($obj);
    			$this->redirect("Home/Index/index");
    		}
    	}else{
    		$this->redirect("Home/Users/login");
    	}
    }


    /**
     * 获取用户信息
     * @return mixed
     */
    public function userInfo(){
        $USER = session('WST_USER');
        $u = D('Home/Users');
        $uinfo = $u->get($USER['userId']);
        return $uinfo;
    }

    /**
     * 签到得金币
     */
    public function signGold(){
        $s = $this->isUserLogin();
        $uid = session('WST_USER.userId');
        $uGold=M('users')->where("userId=$uid")->getField('userSignGold');
        $uGoldinfo = M('sign_info')->where("userId=$uid")->order('sc_time desc')->limit(30)->select();
        $signDate = M('sign_gold')->where("userId=$uid")->getField('si_time');
        if(substr($signDate,0,10) == date('Y-m-d', time())){
            $status = 1;
        }else{
            $status = -1;
        }
        $this->assign('sign', $status);
        $this->assign('uGoldinfo', $uGoldinfo); // 账户明细
        $this->assign('uGold', $uGold); // 当前金币
        $this->display("mobile/users/signGold");
    }

    public function sign(){
        $s = $this->isUserLogin();
        $USER = session('WST_USER');
        $userId = (int)$USER['userId'];
        if($_GET['tag']=='in'){
            /**
            $data=array(
                'userId'=>$userId,
                'sc_score'=>5,
                'sc_detail'=>'签到+5分-L516',
                'sc_type'=>1
            );
            */
        }
        $current=date('Y-m-d H:i:s',time());
        $hasSign=M('sign_gold')->where("userId={$userId}")->find();
        $count=$hasSign['si_count'];
        //WLog('count',count,$count);
        if($hasSign){
            $lastSignDay=strtotime("{$hasSign['si_time']}");
            $lastSign=date('Y-m-d',$lastSignDay);
            $today=date('Y-m-d',time());
            if($lastSign==$today){
                $json = array('code'=>3, 'signGold'=>0, 'msg'=>'今天已签到,您已连续签到'. $count .'天');
                $this->ajaxReturn($json);
                die();
            }
            $residueHour=24+24-date('H',$lastSignDay); //有效的签到时间  (签到当天剩余的小时+1天的时间)
            $formatHour=strtotime(date('Y-m-d H',$lastSignDay).':00:00');//签到当天 2014-12-07 18:00:00
            $lastSignDate=strtotime("+{$residueHour}hour",$formatHour);//在2014-12-07 18:00:00 基础上+ 有效的签到时间
            if(time()>$lastSignDate){ //当前时间 >  上一次签到时间
                $count=1;
            }else{
                $count=$count+1;
            }
            $sign=M('sign_gold')->where("userId={$userId}")->save(array('si_time'=>$current,'si_count'=>$count)); //签到表
        }else{
            $sign=M('sign_gold')->add(array('userId'=>$userId,'si_count'=>1)); //签到表
        }
        //WLog('count',count1,$count);
        if($sign){
            if($count <=1 ){$score = 5;}
            if($count ==2 ){$score = 10;}
            if($count == 3){$score = 15;}
            if($count == 4){$score = 20;}
            if($count >= 5){$score = 30;}
            $data=array(
                'userId'=>$userId,
                'sc_score'=>$score,
                'sc_detail'=>'签到+'.$score.'金币',
                'sc_type'=>1,
                'sc_status'=>1,
                'bak'=>'签到金币',
                'sc_time'=>date('Y-m-d H:i:s',time()),
                'plus_minus'=>1
            );
            M('sign_info')->add($data); //积分表
            /**
            if($count !=0 && !empty($count)){
                if($count%7==0){
                    $data2=array(
                        'userId'=>$userId,
                        'sc_score'=>30,
                        'sc_detail'=>'连续签到7天，增送50积分',
                        'sc_type'=>1
                    );
                    M('sign_info')->add($data2); //积分表
                }
            }*/
            // 更新用户表 用户金币
            $signGold = M('sign_info')->where(array('userId'=>$userId,'sc_type'=>1))->sum('sc_score');
            M('users')->where(array("userId"=>$userId))->save(array('userSignGold'=>$signGold));

            if($count>0){
                $json = array('code'=>2, 'signGold'=>$score, 'msg'=>'签到成功,您已连续签到'. $count .'天');
            }else{
                $json = array('code'=>1, 'signGold'=>$score, 'msg'=>'签到成功');
            }
            $this->ajaxReturn($json);
            die();
        }else{
            $json = array('code'=>0, 'signGold'=>0, 'msg'=>'签到失败,请稍后重试！');
            $this->ajaxReturn($json);
            die();
        }
    }

    /**
     * 用户余额使用记录
     */
    public function getMoneyList(){
        $this->isUserLogin();
        $um = D('Home/Users');
        $user = $um->getUserById(array("userId"=>session('WST_USER.userId')));
        $uMoneyInfo = M('log_moneys')->where(array("targetId"=>session('WST_USER.userId')))->order("createTime desc")->select();
        $this->assign('uMoneyInfo', $uMoneyInfo);
        $this->assign("userMoney",$user['userMoney']);
        $this->display("mobile/users/money_list");
    }

    /**
     * 充值
     */
    public function toCharge(){
        $this->isUserLogin();
        $um = D('Home/Users');
        $user = $um->getUserById(array("userId"=>session('WST_USER.userId')));
        $this->assign("userMoney",$user['userMoney']);
        $this->display("mobile/users/toCharge");

    }

    /**
     * 更多
     */
    public function more(){
        $this->display("mobile/users/more");
    }

    /**
     * 意见反馈
     */
    public function feedback(){
        $USER = session('WST_USER');
        if($_POST){
            $data['userId'] = $USER['userId'];
            $data['userName'] = $USER['loginName'];
            $data['userPhone'] = $USER['userPhone'];
            $data['createTime'] = date('Y-m-d H:i:s');
            $data['content'] = I('content');
            $res = M('feedbacks')->add($data);
            if($res){
                die('1');
            }else{
                die('0');
            }
        }
        $this->display("mobile/users/feedback");
    }

    /**
     * 用户优惠券
     */
    public function userCoup(){
        $this->isUserLogin();
        $uid = session('WST_USER.userId');
        $u = D('Home/Users');
        $list = $u->userCoup($uid);
        $d = $list['0']['getdate'];
        $days = $list['0']['expire'];
        $endDate = date("Y-m-d",strtotime("$d   +$days   day"));   //日期天数相加函数 // 失效日期
        $list['0']['enddate'] = $endDate;
        $minus = strtotime($endDate) - time();
        if(($minus > 0) && ($list['0']['ucstatus'] == 1)){
            $this->assign('listWsy',$list);
        }else{
            $this->assign('listYsy',$list);
        }
        $this->display("mobile/users/userCoup");
    }

    /**
     * 我的收益
     */
    public function profit(){
        $this->isUserLogin();
        $uid = session('WST_USER.userId');
        $uinfo = D('Users')->profit($uid);
        $this->assign('user', $uinfo[1]);
        if(I('to') == 'info'){
            $this->display("mobile/users/profit_info");
        }else{
            $this->display("mobile/users/profit");
        }
    }

    /**
     * 用户个人中心--我的收益--结算
     */
    public function jiesuan(){
        $this->isUserLogin();
        $uid = session('WST_USER.userId');
        $um = D("Home/Users");
        $user = $um->getUserById(array("userId"=>$uid));
        $umoney = $user['userMoney']+$user['tjshouyi1']+$user['tjshouyi2']+$user['tjshouyi3'];
        $res = $um -> jiesuan($uid, $umoney);
        $this->ajaxReturn($res);
    }


    /**
     * 积分商品兑换
     */
    public function topayForjf(){
        $this->isUserLogin();
        $uid = session('WST_USER.userId');
        $um = D("Home/Users");
        $maddress = D('Home/UserAddress');
        $mg = D("Home/Goods");
        $user = $um->getUserById(array("userId"=>$uid));
        //获取地址列表
        $areaId2 = $this->getDefaultCity();
        $addressList = $maddress->queryByUserAndCity($uid,$areaId2);
        $obj["goodsId"] = I('goodsId');
        $goods = $mg->getGoodsDetails($obj);
        $this->assign("goods", $goods);
        $this->assign("userjf", $user['userScore']);
        $this->assign("addressList",$addressList);
        $this->assign("areaId2",$areaId2);
        $this->display("mobile/topayForjf");
    }
    public function payForjf(){
        $ret = $this->paypayjf();
        $this->ajaxReturn($ret);
    }
    public function paypayjf(){
        $userId = session('WST_USER.userId');
        $m = M('orderids');
        $sql = "SELECT s.shopId,s.deliveryType,g.goodsName,g.goodsThums,g.exchangeScore FROM __PREFIX__goods g LEFT JOIN __PREFIX__shops s ON s.shopId = g.shopId WHERE g.goodsId=".I('goodsId');
        $shop = M()->query($sql);
        $shops = $shop['0'];
        if((int)I('userjf') >= (int)$shops["exchangeScore"]){ // 用户积分 需要大于 兑换积分
            //生成订单ID
            $orderSrcNo = $m->add(array('rnd'=>time()));
            $orderNo = $orderSrcNo."".(fmod($orderSrcNo,7));
            //创建订单信息
            $data = array();

            $shopId = (int)$shops["shopId"];
            $deliverType = intval($shops["deliveryType"]);
            $data["orderNo"] = $orderNo;
            $data["shopId"] = $shopId;
            $data["userId"] = $userId;
            $data["orderFlag"] = 1;
            $data["totalMoney"] = 0; // 积分交易 金额设置为0
            $data["useScore"] = $shops["exchangeScore"]; // 购买使用的积分
            $deliverMoney = 0; // 快递费设置为0
            $addressInfo = D('Home/UserAddress')->getAddressDetails(I('consigneeId'));
            $data["deliverMoney"] = $deliverMoney;
            $data["payType"] = 9; // 4 为积分支付
            $data["deliverType"] = $deliverType;
            $data["userName"] = $addressInfo["userName"];
            $data["areaId1"] = $addressInfo["areaId1"];
            $data["areaId2"] = $addressInfo["areaId2"];
            $data["areaId3"] = $addressInfo["areaId3"];
            $data["communityId"] = $addressInfo["communityId"];
            $data["userAddress"] = $addressInfo["paddress"]." ".$addressInfo["address"];
            $data["userTel"] = $addressInfo["userTel"];
            $data["userPhone"] = $addressInfo["userPhone"];
            $data['orderScore'] = 0; // 交易所得积分 设置为0
            $data["isInvoice"] = 0;  // 发票
            $data["orderRemarks"] = '';
            $data["requireTime"] = I("requireTime");
            $data["invoiceClient"] = I("invoiceClient");
            $data["isAppraises"] = 0;
            $data["isSelf"] = 0;     // 是否自提
            $isScorePay = 1;
            $scoreMoney = 0;
            $useScore = $shops["exchangeScore"]; // 需用积分
            $data["poundageRate"] = 0;
            $data["poundageMoney"] = 0;

            if($GLOBALS['CONFIG']['isOpenScorePay']==1 && $isScorePay==1){ // 积分支付
                $data["useScore"] = $shops["exchangeScore"];
                $data["scoreMoney"] = $scoreMoney;
            }
            $data["realTotalMoney"] = 0;
            $data["needPay"] = 0;
            $data["createTime"] = date("Y-m-d H:i:s");
            $data["orderStatus"] = 1; // 1 已受理 订单
            $data["orderunique"] = WSTGetMillisecond().$userId;;
            $data["isPay"] = 0;
            if($data["needPay"]==0){
                $data["isPay"] = 1;
            }
            $morders = M('orders');
            $orderId = $morders->add($data);

            //订单创建成功则建立相关记录
            if($orderId>0) {
                if ($GLOBALS['CONFIG']['isOpenScorePay'] == 1 && $isScorePay == 1 && $useScore > 0) { // 积分支付
                    $sql = "UPDATE __PREFIX__users set userScore=userScore-" . $useScore . " WHERE userId=" . $userId;
                    $rs = M()->execute($sql);
                    $data = array();
                    $mus = M('user_score');
                    $data["userId"] = $userId;
                    $data["score"] = $useScore;
                    $data["dataSrc"] = 1;
                    $data["dataId"] = $orderId;
                    $data["dataRemarks"] = "订单支付-扣积分";
                    $data["scoreType"] = 2;
                    $data["createTime"] = date('Y-m-d H:i:s');
                    $mus->add($data);
                }

                //$orderIds[] = $orderId;
                //建立订单商品记录表
                $mog = M('order_goods');
                $data = array();
                $data["orderId"] = $orderId;
                $data["goodsId"] = I('goodsId');
                $data["goodsAttrId"] = 0;
                $data["goodsNums"] = 1;
                $data["goodsPrice"] = 0;
                $data["goodsName"] = $shops["goodsName"];
                $data["goodsThums"] = $shops["goodsThums"];
                $data["exchangeScore"] = $shops["exchangeScore"];
                $mog->add($data);

                if (1) { // 货到付款或者余额支付
                    //建立订单记录
                    $data = array();
                    $data["orderId"] = $orderId;

                    $data["logContent"] = "积分支付成功";
                    $data["logUserId"] = $userId;
                    $data["logType"] = 0;
                    $data["logTime"] = date('Y-m-d H:i:s');
                    $mlogo = M('log_orders');
                    $mlogo->add($data);
                    //建立订单提醒
                    $sql1 = "SELECT userId,shopId,shopName FROM __PREFIX__shops WHERE shopId=$shopId AND shopFlag=1  ";
                    $users = M()->query($sql1);

                    $morm = M('order_reminds');
                    for ($i = 0; $i < count($users); $i++) {
                        $data = array();
                        $data["orderId"] = $orderId;
                        $data["shopId"] = $shopId;
                        $data["userId"] = $users[$i]["userId"];
                        $data["userType"] = 0;
                        $data["remindType"] = 0;
                        $data["createTime"] = date("Y-m-d H:i:s");
                        $morm->add($data);
                    }
                    //修改库存
                    $sql2 = "update __PREFIX__goods set goodsStock=goodsStock-1 where goodsId=".I("goodsId");
                    M()->query($sql2);
                    if (0 > 0) {
                        $sql3 = "update __PREFIX__goods_attributes set attrStock=attrStock-1 where id=0";
                        M()->query($sql3);
                    }
                }
                if($rs){
                    $rt['status'] = 1;
                    $rt['msg'] = "成功生成订单并扣除用户积分！";
                }else{
                    $rt['status'] = 2;
                    $rt['msg'] = "失败！";
                }
                return $rt;
            }else{
                $rt['status'] = 0;
                $rt['msg'] = "生成订单失败！";
                return $rt;
            }
        }else{
            $rt['status'] = -1;
            $rt['msg'] = "用户积分不足！";
            return $rt;
        }
    }

    /**
     * 积分兑换记录
     *
     */
    public function exchangeRecord(){
        $um = D("Home/Users");
        $uid = session('WST_USER.userId');
        $glistAlreadyshipped = $um->exchangeRecord($uid, 1, 20); // 未发货
        $glistNotShipped = $um->exchangeRecord($uid, 3, 20);     // 已发货
        $this->assign('alreadyShipped', $glistAlreadyshipped);
        $this->assign('notShipped', $glistNotShipped);
        $this->display('mobile/exchangeRecord');
    }

    /**
     * 兑换详情
     */
    public function exDetail(){
        $um = D("Home/Users");
        $detail = $um->exDetail();
        $this->assign('detail', $detail['0']);
        $this->display('mobile/exDetail');
    }

    /**
     * 个人中心--我的团
     */
    public function myPintuan(){
        $this->isUserLogin();
        $uid = session('WST_USER.userId');
        $orderIng = D('Users')->getOrderPintuan($uid, 30, 1);
        $orderSucc = D('Users')->getOrderPintuan($uid, 30, 2);
        $orderFail = D('Users')->getOrderPintuan($uid, 30, -1);


        foreach($orderIng as $key=>$val){
            $details = D('Users')->pintuanDetails($val['orderId']);
            if((strtotime($val['ptlastdate'])-time())<0){ // 过期
                $count = count($details[0]['info']['users']);
                if(C('isDevelop')){ WLog('tuan','cc955', $details[0]['ptrs'].'--'.$count);}
                if($details[0]['ptrs']-$count>0){ // 未成团
                    if(C('isDevelop')){ WLog('tuan','cc957', $details[0]['ptrs'].'--'.$count);}
                    $s = D('Users')->setPintuanStatus($val['orderId'], -1); // 修改状态为未成团
                    if(C('isDevelop')){ WLog('tuan','cc959', $details[0]['ptrs'].'--'.$s);}
                }
            }
        }

//dump($orderIng);
        $this->assign('orderIng', $orderIng);
        $this->assign('orderSucc', $orderSucc);
        $this->assign('orderFail', $orderFail);
        $this->display("mobile/users/myPintuan");
    }
    public function pintuanDetails(){
        $this->isUserLogin();
        $details = D('Users')->pintuanDetails(I('orderid'));
        $this->assign('details', $details['0']);
        $this->display("mobile/users/pintuanDetails");
    }

    public function toSharept(){
        $this->isUserLogin();
        $um = D('Users');
        $details = $um->pintuanDetails(I('orderid'));
        //$details[0]['info']['leader'] = $um->getUserById(array('userId'=>$details[0]['leaderid']));
        $leaderinfo = $details[0]['info']['leader'] = $um->getLeaderOrUserinfo('leader', $details[0]['leaderid'], I('goodsId')); // 开团人
        $details[0]['info']['users'] = $um->getUserPtByGoodsid($details[0]['leaderid']);  // 参团人
        $count = count($details[0]['info']['users']);
        for($i=0; $i<($details[0]['ptrs']-$count-1); $i++){
            $defaultUsers[] = $i;
        }
        if(C('isDevelop')){ WLog('count','cc',$details[0]['ptrs'].'--'.$count);}
        $this->assign('defaultUsers', $defaultUsers);

        $this->assign('count', $count);
        $this->assign('details', $details['0']); // 开团人
        $this->assign('users', $details['0']['info']['users']); // 参团人
        $this->display("mobile/users/toSharept");
    }




} // END CLASS