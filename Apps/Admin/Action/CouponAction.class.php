<?php
namespace Admin\Action;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/20
 * Time: 16:47
 */

 class CouponAction extends BaseAction{
     /**
      * 跳到新增/编辑页面
      */
     public function toEdit(){
         $this->isLogin();
         $m = D('Admin/Coupon');
         $object = array();
         if(I('id',0)>0){
             $this->checkPrivelege('coupon_02');
             $object = $m->get();
         }else{
             $this->checkPrivelege('coupon_01');
             $object = $m->getModel();
         }
         $this->assign('object',$object);
         $this->display('/coupon/edit');
     }
     /**
      * 新增/修改操作
      */
     public function edit(){
         $this->isLogin();
         $m = D('Admin/Coupon');
         $rs = array();
         if(I('id',0)>0){
             $this->checkPrivelege('coupon_02');
             $rs = $m->edit();
         }else{
             $this->checkPrivelege('coupon_01');
             $rs = $m->insert();
         }
         $this->ajaxReturn($rs);
     }
     /**
      * 删除操作
      */
     public function del(){
         $this->isLogin();
         $this->checkPrivelege('coupon_03');
         $m = D('Admin/Coupon');
         $rs = $m->del();
         $this->ajaxReturn($rs);
     }
     /**
      * 查看
      */
     public function toView(){
         $this->isLogin();
         $this->checkPrivelege('coupon_00');
         $m = D('Admin/Coupon');
         if(I('id')>0){
             $object = $m->get();
             $this->assign('object',$object);
         }
         $this->view->display('/coupon/view');
     }
     /**
      * 分页查询
      */
     public function index(){
         $this->isLogin();
         $this->checkPrivelege('coupon_00');
         $m = D('Admin/Coupon');
         $page = $m->queryByPage();
         $pager = new \Think\Page($page['total'],$page['pageSize'],I());// 实例化分页类 传入总记录数和每页显示的记录数
         $page['pager'] = $pager->show();
//         var_dump($page);
         $this->assign('Page',$page);
         $this->assign('titkey',I('titkey'));
         $this->display("/coupon/list");
     }
     /**
      * 列表查询
      */
     public function queryByList(){
         $this->isLogin();
         $m = D('Admin/Coupon');
         $list = $m->queryByList();
         $rs = array();
         $rs['status'] = 1;
         $rs['list'] = $list;
         $this->ajaxReturn($rs);
     }
     /**
      * 显示商品是否显示/隐藏
      */
     public function editiIsShow(){
         $this->isLogin();
         $this->checkPrivelege('coupon_02');
         $m = D('Admin/Coupon');
         $rs = $m->editiIsShow();
         $this->ajaxReturn($rs);
     }
 };
 ?>