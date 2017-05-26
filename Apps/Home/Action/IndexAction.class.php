<?php
namespace Home\Action;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 首页控制器
 */
class IndexAction extends BaseAction {
	/**
	 * 获取首页信息
	 */
    public function index(){
        $this->mall(); exit;
   		$ads = D('Home/Ads');
   		$areaId2 = $this->getDefaultCity();
   		//获取分类
		$gcm = D('Home/GoodsCats');
		$catList = $gcm->getGoodsCatsAndGoodsForIndex($areaId2);
		$this->assign('catList',$catList);
   		//分类广告
   		$catAds = $ads->getAdsByCat($areaId2);
        $g = D('Home/Goods');
        $goodsMs = $g->getMsGoodsIndex();
        $goodsPt = $g->getGoodsList_index(1, 'pintuan', 4);
        $goodsZc = $g->getGoodsList_zc(2);
    

        $this->assign('goodsMs', $goodsMs);
        $this->assign('goodsPt', $goodsPt);
        $this->assign('goodsZc', $goodsZc);
   		$this->assign('catAds',$catAds);
   		$this->assign('ishome',1);
        $this->assign('s','index');

        $dateH = date("H");
        if(($dateH<12) && ($dateH>0) ){
            $date = date("Y-m-d 12:00:00");
        }elseif(($dateH>=12) && ($dateH<16) ){
            $date = date("Y-m-d 16:00:00");
        }elseif(($dateH>=16) && ($dateH<20) ){
            $date = date("Y-m-d 20:00:00");
        }else{
            $date = date("Y-m-d 23:59:59");
        }
        if(C('isDevelop')){WLog('log','L43', $date.'--'.$dateH);};
        $this->assign('date',$date);
   		if(I("changeCity")){
   			echo $_SERVER['HTTP_REFERER'];
   		}else{
   			$this->display("mobile/index");
   		}
    }

    public function mall(){
        // session('WST_USER','');
        // $USER = session('WST_USER');
        $u = D('Home/Users');
        $uinfo = $u->getUserById(session('WST_USER'));
        $g = D('Home/Goods');
        $goodsListTj = $g->getGoodsList_mall(1,8);
        $goodsMs = $g->getMsGoodsIndex();
        $this->assign('goodsListTj', $goodsListTj);
        $this->assign('goodsMs', $goodsMs);
        $this->assign('userinfo', $uinfo);
        $this->assign('s','mall');
        $this->display("mobile/mall");
    }

    public function getCats(){
        $gcm = D('Home/GoodsCats');
        $catList = $gcm->getGoodsCatsAndGoodsForIndex(1);
//        var_dump($catList['0']['catChildren']);die();
        $this->assign('catList',$catList['0']['catChildren']);
        $this->display("mobile/goodsCats");
    }
    /**
     * 广告记数
     */
    public function access(){
    	$ads = D('Home/Ads');
    	$ads->statistics((int)I('id'));
    }
    /**
     * 切换城市
     */
    public function changeCity(){
    	$m = D('Home/Areas');
    	$areaId2 = $this->getDefaultCity();
    	$provinceList = $m->getProvinceList();
    	$cityList = $m->getCityGroupByKey();
    	$area = $m->getArea($areaId2);
    	$this->assign('provinceList',$provinceList);
    	$this->assign('cityList',$cityList);
    	$this->assign('area',$area);
    	$this->assign('areaId2',$areaId2);
    	$this->display("default/change_city");
    }
    /**
     * 跳到用户注册协议
     */
    public function toUserProtocol(){
        $a = D('Home/Articles');
        $obj = array('articleId'=>16);
        $article = $a->getArticle($obj);
        $article['articleContent'] = htmlspecialchars_decode($article['articleContent']);
        $this->assign('article', $article);
    	$this->display("mobile/user_protocol");
    }
    
    /**
     * 修改切换城市ID
     */
    public function reChangeCity(){
    	$this->getDefaultCity();
    }

    /**
     * 秒杀页
     */
    public function msIndex(){
        $g = D('Home/Goods');
        $goodsMs12 = $g->getGoodsList_index(-1, 'miaosha', 50, 12);
        $goodsMs16 = $g->getGoodsList_index(-1, 'miaosha', 50, 16);
        $goodsMs20 = $g->getGoodsList_index(-1, 'miaosha', 50, 20);
        $this->assign("h", date("H",time()));
        $this->assign("goodsMs12", $goodsMs12);
        $this->assign("goodsMs16", $goodsMs16);
        $this->assign("goodsMs20", $goodsMs20);
        $this->display("mobile/miaosha");
    }

    /**
     * 拼团页
     */
    public function ptIndex(){
        $g = D('Home/Goods');
        $o = D('Home/Orders');
        $goodsPt = $g->getGoodsList_index(-1, 'pintuan', 50);
        // 删除失效拼团订单
        if(I('o') == 'del'){
            $o->delOrders();
        }

        $this->assign("goodsPt", $goodsPt);
        $this->display("mobile/pintuan");
    }

    /**
     * 积分兑换商城
     */
    public function jfMall(){
        $g = D('Home/Goods');
        $goodsScore = $g->getGoodsList_index(-1, 'score', 50);
        $um = D('Home/Users');
        $user = $um->getUserById(array("userId"=>session('WST_USER.userId')));
        $this->assign("userScore",$user['userScore']);
        $this->assign("goodsjf", $goodsScore);
        $this->display("mobile/jfmall");
    }




    
} // END