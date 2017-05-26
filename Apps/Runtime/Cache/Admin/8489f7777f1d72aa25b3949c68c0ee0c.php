<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo ($CONF['mallTitle']); ?>后台管理中心</title>
    <link href="/Public/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Apps/Admin/View/css/AdminLTE.css" rel="stylesheet" type="text/css" />
    <!--[if lt IE 9]>
    <script src="/Public/js/html5shiv.min.js"></script>
    <script src="/Public/js/respond.min.js"></script>
    <![endif]-->
    <script src="/Public/js/jquery.min.js"></script>
    <script src="/Public/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/Public/js/common.js"></script>
    <script src="/Public/plugins/plugins/plugins.js"></script>
</head>
<script>
    function toggleflag(t,v){
        Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
        $.post("<?php echo U('Admin/Users/changeStatus');?>",{id:v,flag:t},function(data){
            if(data=='1'){
                Plugins.setWaitTipsMsg({content:'操作成功',timeout:1000,callback:function(){
                    location.reload();
                }});
            }else{
                Plugins.closeWindow();
                Plugins.Tips({title:'信息提示',icon:'error',content:'操作失败!',timeout:1000});
            }
        });
    }
    function del(id){
        Plugins.confirm({title:'信息提示',content:'您确定要删除该反馈吗?',okText:'确定',cancelText:'取消',okFun:function(){
            Plugins.closeWindow();
            Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
            $.post("<?php echo U('Admin/Users/feedbackDel');?>",{id:id},function(data){
                if(data=='1'){
                    Plugins.setWaitTipsMsg({content:'操作成功',timeout:1000,callback:function(){
                        location.reload();
                    }});
                }else{
                    Plugins.closeWindow();
                    Plugins.Tips({title:'信息提示',icon:'error',content:'操作失败!',timeout:1000});
                }
            });
        }});
    }
</script>
<body class='wst-page'>
<form method='post' action='<?php echo U("Admin/Users/feedback");?>'>
    <div class='wst-tbar' style='height:25px;'></div>
</form>
<div class="wst-body">
    <table class="table table-hover table-striped table-bordered wst-list">
        <thead>
        <tr>
            <th width='40'>序号</th>
            <th>反馈用户</th>
            <th>概述内容</th>
            <th width='80'>是否查看</th>
            <th width='150'>创建时间</th>
            <th width='150'>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td><?php echo ($i); ?></td>
                <td><?php echo ((isset($vo["userName"]) && ($vo["userName"] !== ""))?($vo["userName"]):'匿名'); ?></td>
                <td><?php echo ($vo["content"]); ?></td>
                <td>
                    <div class="dropdown">
                        <?php if($vo['flag']==0 ): ?><button class="btn btn-danger dropdown-toggle wst-btn-dropdown"  type="button" data-toggle="dropdown">
                                未查看
                                <span class="caret"></span>
                            </button>
                            <?php else: ?>
                            <button class="btn btn-success dropdown-toggle wst-btn-dropdown" type="button" data-toggle="dropdown">
                                已查看
                                <span class="caret"></span>
                            </button><?php endif; ?>
                        <ul class="dropdown-menu" role="menu">
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleflag(1,<?php echo ($vo['id']); ?>)">已查看</a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:toggleflag(0,<?php echo ($vo['id']); ?>)">未查看</a></li>
                        </ul>

                    </div>
                </td>
                <td><?php echo ($vo['createTime']); ?></td>
                <td>
                    <!--<?php if(in_array('wzlb_02',$WST_STAFF['grant'])){ ?>-->
                    <!--<a class="btn btn-default glyphicon glyphicon-pencil" href="<?php echo U('Admin/Articles/toEdit',array('id'=>$vo['articleId']));?>">修改</a>&nbsp;-->
                    <!--<?php } ?>-->
                    <?php if(in_array('wzlb_03',$WST_STAFF['grant'])){ ?>
                    <a class="btn btn-default glyphicon glyphicon-trash" href="javascript:del(<?php echo ($vo['id']); ?>)"">刪除</a>
                    <?php } ?>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        <tr>
            <td colspan='7' align='center'><?php echo ($Page['pager']); ?></td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>