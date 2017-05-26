<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?php echo ($CONF['shopTitle']['fieldValue']); ?>后台管理中心</title>
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
      <script src="/Public/plugins/formValidator/formValidator-4.1.3.js"></script>
   </head>
   <body class="wst-page">
       <form name="myform" method="post" id="myform">
        <input type='hidden' id='id' value='<?php echo ($object["bankId"]); ?>'/>
        <table class="table table-hover table-striped table-bordered wst-form">
           <tr>
             <td>
             <span style='font-weight:bold;'>订单号：<?php echo ($object['orderNo']); ?></span>
             <span style='margin-left:100px;'>
                                       状态：<?php if($object["orderStatus"] == -3): ?>用户拒收
               <?php elseif($object["orderStatus"] == -5): ?>商户不同意拒收
               <?php elseif($object["orderStatus"] == -4): ?>商户同意拒收
			   <?php elseif($object["orderStatus"] == -2): ?>未付款
			   <?php elseif(($object["orderStatus"] == -6) OR ($vo["orderStatus"] == -7)): ?>用户取消
			   <?php elseif($object["orderStatus"] == 0): ?>未受理
			   <?php elseif($object["orderStatus"] == 1): ?>已受理
			   <?php elseif($object["orderStatus"] == 2): ?>打包中
			   <?php elseif($object["orderStatus"] == 3): ?>配送中
			   <?php elseif($object["orderStatus"] == 4): ?>已到货<?php endif; ?>
             </span></td>
           </tr>
           <tr>
              <td style='font-weight:bold;'>订单日志</td>
           </tr>
           <tr>
              <td>
                <table width='700'>
                <tr>
                  <td width='220'>操作时间</td>
                  <td width='350'>操作信息</td>
                  <td width='230'>操作人</td>
                </tr>
                <?php if(is_array($object['log'])): $i = 0; $__LIST__ = $object['log'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$log): $mod = ($i % 2 );++$i;?><tr>
                  <td><?php echo ($log['logTime']); ?></td>
                  <td><?php echo ($log['logContent']); ?></td>
                  <td><?php echo ($log['loginName']); if(!empty($log['shopName'])): ?>(<?php echo ($log['shopName']); ?>)<?php endif; ?></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </table>
              </td>
           </tr>
           <tr>
             <td style='font-weight:bold;'>订单信息</td>
           </tr>
           <tr>
             <td>
             <table width='700'>
                <tr>
                  <td width='120' style='text-align:right'>支付方式：</td>
                  <td>
                  <?php if($object["payType"] == 1): ?>在线支付<?php elseif($object["payType"] == 3): ?>余额付款<?php elseif($object["payType"] == 9): ?>积分支付<?php endif; ?>
                  </td>
                </tr>
                <tr>
                  <td style='text-align:right'>配送方式：</td>
                  <td>
                  <?php if($object["isSelf"] == 1): ?>自提
                  <?php else: ?>
                  	<?php if($object["deliverType"] == 1): ?>商户配送<?php else: ?>商城配送<?php endif; endif; ?>                                                
                  </td>
                </tr>
                <!-- <tr>
                  <td style='text-align:right'>送货时间：</td>
                  <td><?php echo ($object['requireTime']); ?></td>
                </tr> -->
                <!-- <tr>
                  <td style='text-align:right'>买家留言：</td>
                  <td><?php echo ($object['orderRemarks']); ?></td>
                </tr> -->
                </table>
             </td>
           </tr>
           <?php if($object["isRefund"] == 1): ?><tr>
             <td style='font-weight:bold;'>退款说明</td>
           </tr>
           <tr>
             <td>
             <table width='700'>
                <tr>
                  <td width='120' style='text-align:right'>说明：</td>
                  <td>
                  <?php echo ($object['refundRemark']); ?>
                  </td>
                </tr>
                </table>
             </td>
           </tr><?php endif; ?>
           <?php if($object["isInvoice"] == 1): ?><tr>
             <td style='font-weight:bold;'>发票信息</td>
           </tr>
           <tr>
             <td>
             <table width='700'>
                <tr>
                  <td width='120' style='text-align:right'>发票抬头：</td>
                  <td>
                  <?php echo ($object['invoiceClient']); ?>
                  </td>
                </tr>
                </table>
             </td>
           </tr><?php endif; ?>
           <tr>
             <td style='font-weight:bold;'>收货人信息</td>
           </tr>
           <tr>
             <td>
                <table width='700'>
                <tr>
                  <td width='120' style='text-align:right'>收货人：</td>
                  <td><?php echo ($object['userName']); ?></td>
                </tr>
                <tr>
                  <td style='text-align:right'>地址：</td>
                  <td><?php echo ($object['userAddress']); ?></td>
                </tr>
                <tr>
                  <td style='text-align:right'>联系方式：</td>
                  <td>
                  <notmpty name='object['userPhone']'>
                  <?php echo ($object['userPhone']); ?>
                  </notmpty>
                  <notmpty name='object['userTel']'>
                  <?php echo ($object['userTel']); ?>
                  </notmpty>
                  </td>
                </tr>
                </table>
             </td>
           </tr>
           <tr>
              <td style='font-weight:bold;'>商品信息</td>
           </tr>
           <tr>
              <td>
                <table>
                <tr>
                  <td width='450' colspan='2'>商品</td>
                  <td width='350'>价格</td>
                  <td width='130'>数量</td>
                  <td width='130'>总金额</td>
                </tr>
                <?php if(is_array($object['goodslist'])): $i = 0; $__LIST__ = $object['goodslist'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$goods): $mod = ($i % 2 );++$i;?><tr>
                  <td width='50'><img src='/<?php echo ($goods["goodsThums"]); ?>' style='margin:2px;' width='50' height='50'/></td>
                  <td width='400'><?php echo ($goods["goodsName"]); if($goods['goodsAttrName'] != ''): ?>【<?php echo ($goods['goodsAttrName']); ?>】<?php endif; ?></td>
                  <td width='350'>
                      <?php if($object["useScore"] > 0): echo ($object["useScore"]); ?> 积分
                      <?php else: ?>
                          ￥<?php echo ($goods["goodsPrice"]); endif; ?>
                  </td>
                  <td width='130'><?php echo ($goods["goodsNums"]); ?></td>
                  <td width='130'>￥<?php echo ($goods["goodsPrice"]*$goods["goodsNums"]); ?></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </table>
              </td>
           </tr>
           <tr>
              <td style='text-align:right;padding-right:10px;'>商品总金额：￥<?php echo ($object['totalMoney']); ?><br/>+ 运费：￥<?php echo ($object['deliverMoney']); ?><br/>
              <?php if($object["useScore"] > 0): ?>使用积分：<?php echo ($object["useScore"]); ?> 积分<br/><?php endif; ?>
              <span>订单金额：</span><span style='font-weight:bold;font-size:20px;color:red;'>￥<?php echo ($object['totalMoney']+$object['deliverMoney']); ?></span><br/>
           	  <span style='font-weight:bold;font-size:20px'>实付金额：</span><span style='font-weight:bold;font-size:20px;color:red;'>￥<?php echo ($object['realTotalMoney']); ?></span></td>
           </tr>
           <tr>
             <td colspan='2' align='center'>
                 <button type="button" class="btn btn-primary" onclick='javascript:location.href="<?php echo ($referer); ?>"'>返&nbsp;回</button>
             </td>
           </tr>
        </table>
       </form>
   </body>
</html>