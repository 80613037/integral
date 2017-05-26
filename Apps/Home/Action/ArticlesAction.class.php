<?php
 namespace Home\Action;
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 文章控制器
 */
class ArticlesAction extends BaseAction{
	
	/**
	 * 帮助中心
	 */
	public function index(){
		$m = D('Home/Articles');
    	$articleList = $m->getArticleList();
    	$obj["articleId"] = (int)I("articleId",0);
    	if(!$obj["articleId"]){
    		foreach($articleList as $key=> $articles){
    			$obj["articleId"] = $articles["articlecats"][0]["articleId"];
    			break;
    		}
    	}
    	
    	$article = $m->getArticle($obj);
    	$this->assign('articleList',$articleList);
    	$article['articleContent'] = htmlspecialchars_decode($article['articleContent']);
    	$this->assign('carticle',$article);
        $this->display("mobile/help_center");
	}

	public function lists(){
        $type = I('type',0); $con = I('con',0); $from = I('from',0);
	    $lists = M('articles')->where(array('catId'=>$type,'isShow'=>1))->order('createTime desc')->select();
        $show = M('articles')->where(array('articleId'=>$con,'isShow'=>1))->find();
        $this->assign('lists', $lists);
        $this->assign('show', $show);
        if($con > 0){
            $this->display("mobile/users/article/show");
        }elseif($from == 1){
            $this->display("mobile/users/article/listsQues");
        }else{
            $this->display("mobile/users/article/lists");
        }


    }


    /*
    * 众筹
    */
    public function zc(){
        $m = D('Home/Articles');
        $articleList = $m->getArticleList();
        $obj["articleId"] = (int)I("articleId",0);
        if(!$obj["articleId"]){
            foreach($articleList as $key=> $articles){
                $obj["articleId"] = $articles["articlecats"][0]["articleId"];
                break;
            }
        }
        
        $article = $m->getArticle($obj);
        $this->assign('articleList',$articleList);
        $article['articleContent'] = htmlspecialchars_decode($article['articleContent']);
        $this->assign('carticle',$article);
        $this->display("default/zcindex");
    }


	
};
?>