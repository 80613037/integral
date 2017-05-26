<?php
namespace Admin\Action;
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
		$this->assign('CONF',$GLOBALS['CONFIG']);
		$s = session('WST_STAFF');
		$this->assign('WST_STAFF',$s);
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
     * 上传文件
     * Enter description here ...
     */
    public function uploadFile(){
    	
    }
    /**
     * 登录操作验证
     */
    public function isLogin(){
    	$s = session('WST_STAFF');
        if(IS_AJAX){
    	    if(empty($s))die("{status:-999,url:'toLogin'}");
    	}else{
    		if(empty($s))$this->redirect("Index/toLogin");
    	}
    }
    /**
     * 跳转权限操作
     */
    public function checkPrivelege($grant){
    	$s = session('WST_STAFF.grant');
    	if(IS_AJAX){
    		if(empty($s) || !in_array($grant,$s))die("{status:-998}");
    	}else{
    	    if(empty($s) || !in_array($grant,$s)){
	    		$this->display("/noprivelege");exit();
	    	}
    	}
    }
    
    /**
     * 返回所有参数
     */
    function WSTAssigns(){
    	$params = I();
    	$this->assign("params",$params);
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
    
    public function checkVerify(){
	    $verify = new \Think\Verify();
	    return $verify->check(I('verify'));
    }
}