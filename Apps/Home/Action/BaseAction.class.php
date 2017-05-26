<?php
namespace Home\Action;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 基础控制器
 */
use Think\Controller;
class BaseAction extends Controller {
	public function __construct(){
		parent::__construct();
		//初始化系统信息
		$m = D('Home/System');
		$GLOBALS['CONFIG'] = $m->loadConfigs();
		WSTAutoByCookie();
		$this->assign("WST_USER",session('WST_USER'));
		$this->assign("WST_IS_LOGIN",(session('WST_USER.userId')>0)?1:0);
		$areas= D('Home/Areas');
		$areaId2 = $this->getDefaultCity();
		$currArea = $areas->getArea($areaId2);
		$this->assign('currArea',$currArea);
   		$this->assign('searchType',(int)I("searchType",1));
   		$this->assign('CONF',$GLOBALS['CONFIG']);
   		$this->assign('WST_REFERE',$_SERVER['HTTP_REFERER']);
		$this->footer(); //加入底部
	}
	
	/**
	 * 空操作处理
	 */
    public function _empty($name){
        $this->assign('msg',"你的思想太飘忽，系统完全跟不上....");
        $this->display('default/sys_msg');
    }
	/**
     * ajax程序验证,只要不是会员都返回-999
     */
    public function isUserLogin() {
    	$USER = session('WST_USER');
		if (empty($USER) || ($USER['userId']=='')){
			if(IS_AJAX){
				$this->ajaxReturn(array('status'=>-999,'url'=>'Users/login'));
			}else{
				$this->redirect("Users/login");
			}
		}
	}
	/**
	 * 商家ajax登录验证
	 */
	public function isShopLogin(){
		$USER = session('WST_USER');
		if (empty($USER) || $USER['userType']==0){
			if(IS_AJAX){
				$this->ajaxReturn(array('status'=>-999,'url'=>'Shops/login'));
			}else{
				$this->redirect("Shops/login");
			}
		}
	}
	/**
	 * 用户登录验证-主要用来判断会员和商家共同功能的部分
	 */
	public function isLogin($userType = 'Users'){
		$USER = session('WST_USER');
		if (empty($USER)){
			if(IS_AJAX){
			    $this->ajaxReturn(array('status'=>-999,'url'=>$userType.'/login'));
			}else{
				$this->redirect($userType."/login");
			}
		}
   }
   /**
    * 检查登录状态
    */
   public function checkLoginStatus(){
   	   $USER = session('WST_USER');
	   if (empty($USER)){
	   	    die("{status:-999}");
	   }else{
	   		die("{status:1}");
	   }
   }
   /**
	 * 验证模块的码校验
	 */
	public function checkVerify($type){
		if(stripos($GLOBALS['CONFIG']['captcha_model'],$type) !==false) {
			$verify = new \Think\Verify();
			return $verify->check(I('verify'));
		}else{
			return true;
		}
		return false;
	}
	
    /**
     * 核对单独的验证码
	 * $re = false 的时候不是ajax返回
	 * @param  boolean $re [description]
	 * @return [type]      [description]
	 */
	public function checkCodeVerify($re = true){
		$code = I('code');
		$verify = new \Think\Verify(array('reset'=>false));    
		$rs =  $verify->check($code);		
		if ($re == false) return $rs;
		else $this->ajaxReturn(array('status'=>(int)$rs));
	}
    /**
	 * 单个上传图片
	 */
    public function uploadPic(){
	   $config = array(
		        'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
		        'exts'          =>  array('jpg','png','gif','jpeg'), //允许上传的文件后缀
		        'rootPath'      =>  './Upload/', //保存根路径
		        'driver'        =>  'LOCAL', // 文件上传驱动
		        'subName'       =>  array('date', 'Y-m'),
		        'savePath'      =>  I('dir','uploads')."/"
		);
	   	$dirs = explode(",",C("WST_UPLOAD_DIR"));
	   	if(!in_array(I('dir','uploads'), $dirs)){
	   		echo '非法文件目录！';
	   		return false;
	   	}

		$upload = new \Think\Upload($config);
		$rs = $upload->upload($_FILES);
		$Filedata = key($_FILES);
		if(!$rs){
			$this->error($upload->getError());
		}else{
			$images = new \Think\Image();
			$images->open('./Upload/'.$rs[$Filedata]['savepath'].$rs[$Filedata]['savename']);
			$newsavename = str_replace('.','_thumb.',$rs[$Filedata]['savename']);
			$vv = $images->thumb(I('width',300), I('height',300))->save('./Upload/'.$rs[$Filedata]['savepath'].$newsavename);
		    if(C('WST_M_IMG_SUFFIX')!=''){
		        $msuffix = C('WST_M_IMG_SUFFIX');
		        $mnewsavename = str_replace('.',$msuffix.'.',$rs[$Filedata]['savename']);
		        $mnewsavename_thmb = str_replace('.',"_thumb".$msuffix.'.',$rs[$Filedata]['savename']);
			    $images->open('./Upload/'.$rs[$Filedata]['savepath'].$rs[$Filedata]['savename']);
			    $images->thumb(I('width',700), I('height',700))->save('./Upload/'.$rs[$Filedata]['savepath'].$mnewsavename);
			    $images->thumb(I('width',250), I('height',250))->save('./Upload/'.$rs[$Filedata]['savepath'].$mnewsavename_thmb);
			}
			$rs[$Filedata]['savepath'] = "Upload/".$rs[$Filedata]['savepath'];
			$rs[$Filedata]['savethumbname'] = $newsavename;
			$rs['status'] = 1;
			
			echo json_encode($rs);

		}	
    }
	
	/**
	 * 产生验证码图片
	 * 
	 */
	public function getVerify(){
		// 导入Image类库
    	$Verify = new \Think\Verify();
    	$Verify->length   = 4;
    	$Verify->entry();
    }
   /**
	 * 页尾参数初始化
	 */
	public function footer(){
		$m = D('Home/Friendlinks');
		$friendLikds = $m->getFriendLinks();
		$this->assign('friendLikds',$friendLikds);
		$m = D('Home/Articles');
		$helps = $m->getHelps();
		$this->view->assign("helps",$helps);
	}
	/**
	 * 设置所在城市
	 */
	public function setDefaultCity($cityId){
		setcookie("areaId2", $cityId, time()+3600*24*90);
	}
	/**
	 * 定位所在城市
	 */
	public function getDefaultCity(){
		$areas= D('Home/Areas');
		return $areas->getDefaultCity();
	}
	

	/**
	 * 返回所有参数
	 */
	function WSTAssigns(){
		$params = I();
		$this->assign("params",$params);
	}
	
}