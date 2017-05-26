<?php
namespace Admin\Model;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/21
 * Time: 11:08
 */

 class CouponModel extends BaseModel {
     /**
      * 新增
      */
     public $isShow = null;
     public function insert(){
         $rd = array('status'=>-1);
         $id = (int)I("id",0);
         $data = array();
         $data["title"] = I("title");
         $data["status"] = (int)I("isShow",0);
         $data["man"] = I("man"); $data["jian"] = I("jian");
         $data["expire"] = I("expire");
//         $data["staffId"] = (int)session('WST_STAFF.staffId');
         $data["date"] = date('Y-m-d H:i:s');
         if($this->checkEmpty($data,true)){
             $rs = $this->add($data);
             if((int)I("isShow")==1){
                M("coupon")->where("id !=".$rs)->save(array("status"=>0));
             }
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
         $data["title"] = I("title");
         $data["status"] = (int)I("isShow",0);
         $data["man"] = I("man");
         $data["jian"] = I("jian");
         $data["expire"] = I("expire");
         $data["date"] = date('Y-m-d H:i:s');
//         $data["staffId"] = (int)session('WST_STAFF.staffId');
         if($this->checkEmpty($data,true)){
            
             $rs = $this->where("id=".(int)I('id',0))->save($data);
//             $rs = M('coupon')->where(array('id'=>(int)I('id',0)))->save($data);
             if((int)I("isShow")==1){
                M("coupon")->where("id !=".(int)I('id'))->save(array("status"=>0));
             }
             if(false !== $rs){
                 $rd['status']= 1;
             }
         }
         return $rd;
     }
     /**
      * 获取指定对象
      */
     public function get($id=0){
         if($id != 0){
             if(C('isDevelop')){WLog('jjj11','datas:', '123');}
             return $this->where("id=".$id)->find();
         }else
         return $this->where("id=".(int)I('id'))->find();
     }
     /**
      * 分页列表
      */
     public function queryByPage(){
         $sql = "SELECT * FROM __PREFIX__coupon";
         if(I('titkey')!='') {
             $sql .= " where title like '%" . WSTAddslashes(I('titkey')) . "%'";
         }
         $sql.=' order by id desc';
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
//         $rs = $this->where("id=".(int)I('id',0))->save();
         $rs = M('coupon')->where(array('id'=>(int)I('id',0)))->save(array('status'=>$this->isShow));
         if((int)I("isShow")==1){
                M("coupon")->where("id !=".(int)I('id'))->save(array("status"=>0));
          }
         if(false !== $rs){
             $rd['status']= 1;
         }
         return $rd;
     }
 };
 ?>