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
      <script src="/Public/plugins/layer/layer.min.js"></script>
      <script src="/Public/plugins/bootstrap/js/bootstrap.min.js"></script>
      <script src="/Public/js/common.js"></script>
      <script src="/Public/plugins/plugins/plugins.js"></script>
      <script src="/Public/plugins/formValidator/formValidator-4.1.3.js"></script>
   </head>
   <body class="wst-page">
       <form name="myform" method="post" id="myform">
        <input type='hidden' id='id' value='<?php echo ($object["order"]["bankId"]); ?>'/>
        <table class="table table-hover table-striped table-bordered wst-form">
           <tr>
             <td style='font-weight:bold;'>订单详情
             <a id="showBtn" href='javascript:WST.showHide(1,"#hideBtn,#orderDiv");WST.showHide(0,"#showBtn");' style='float:right'>显示订单详情&nbsp;</a>
             <a id="hideBtn" href='javascript:WST.showHide(1,"#showBtn");WST.showHide(0,"#hideBtn,#orderDiv");' style='float:right;display:none'>隐藏订单详情</a>&nbsp;</td>
           </tr>
           <tbody id='orderDiv' style='display:none'>
           <tr>
             <td>
             <span style='font-weight:bold;'>订单号：<?php echo ($object["order"]['orderNo']); ?></span>
             <span style='margin-left:100px;'>
                                       状态：<?php if($object["order"]["orderStatus"] == -3): ?>用户拒收
               <?php elseif($object["order"]["orderStatus"] == -5): ?>商户不同意拒收
               <?php elseif($object["order"]["orderStatus"] == -4): ?>商户同意拒收
			   <?php elseif($object["order"]["orderStatus"] == -2): ?>未付款
			   <?php elseif(($object["order"]["orderStatus"] == -6) OR ($vo["orderStatus"] == -7)): ?>用户取消
			   <?php elseif($object["order"]["orderStatus"] == 0): ?>未受理
			   <?php elseif($object["order"]["orderStatus"] == 1): ?>已受理
			   <?php elseif($object["order"]["orderStatus"] == 2): ?>打包中
			   <?php elseif($object["order"]["orderStatus"] == 3): ?>配送中
			   <?php elseif($object["order"]["orderStatus"] == 4): ?>已到货<?php endif; ?>
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
                <?php if(is_array($object['order']['log'])): $i = 0; $__LIST__ = $object['order']['log'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$log): $mod = ($i % 2 );++$i;?><tr>
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
                  <?php if($object["order"]["payType"] == 1): ?>在线支付<?php else: ?>货到付款<?php endif; ?>
                  </td>
                </tr>
                <tr>
                  <td style='text-align:right'>配送方式：</td>
                  <td>
                  <?php if($object["order"]["isSelf"] == 1): ?>自提
                  <?php else: ?>
                  	<?php if($object["order"]["deliverType"] == 1): ?>商户配送<?php else: ?>商城配送<?php endif; endif; ?>                                                
                  </td>
                </tr>
                <tr>
                  <td style='text-align:right'>送货时间：</td>
                  <td><?php echo ($object["order"]['requireTime']); ?></td>
                </tr>
                <tr>
                  <td style='text-align:right'>买家留言：</td>
                  <td><?php echo ($object["order"]['orderRemarks']); ?></td>
                </tr>
                </table>
             </td>
           </tr>
           <?php if($object["order"]["isRefund"] == 1): ?><tr>
             <td style='font-weight:bold;'>退款说明</td>
           </tr>
           <tr>
             <td>
             <table width='700'>
                <tr>
                  <td width='120' style='text-align:right'>说明：</td>
                  <td>
                  <?php echo ($object["order"]['refundRemark']); ?>
                  </td>
                </tr>
                </table>
             </td>
           </tr><?php endif; ?>
           <?php if($object["order"]["isInvoice"] == 1): ?><tr>
             <td style='font-weight:bold;'>发票信息</td>
           </tr>
           <tr>
             <td>
             <table width='700'>
                <tr>
                  <td width='120' style='text-align:right'>发票抬头：</td>
                  <td>
                  <?php echo ($object["order"]['invoiceClient']); ?>
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
                  <td><?php echo ($object["order"]['userName']); ?></td>
                </tr>
                <tr>
                  <td style='text-align:right'>地址：</td>
                  <td><?php echo ($object["order"]['userAddress']); ?></td>
                </tr>
                <tr>
                  <td style='text-align:right'>联系方式：</td>
                  <td>
                  <notmpty name='object['userPhone']'>
                  <?php echo ($object["order"]['userPhone']); ?>
                  </notmpty>
                  <notmpty name='object['userTel']'>
                  <?php echo ($object["order"]['userTel']); ?>
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
                <?php if(is_array($object['order']['goodslist'])): $i = 0; $__LIST__ = $object['order']['goodslist'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$goods): $mod = ($i % 2 );++$i;?><tr>
                  <td width='50'><img src='/<?php echo ($goods["goodsThums"]); ?>' style='margin:2px;' width='50' height='50'/></td>
                  <td width='400'><?php echo ($goods["goodsName"]); if($goods['goodsAttrName'] != ''): ?>【<?php echo ($goods['goodsAttrName']); ?>】<?php endif; ?></td>
                  <td width='350'>￥<?php echo ($goods["goodsPrice"]); ?></td>
                  <td width='130'><?php echo ($goods["goodsNums"]); ?></td>
                  <td width='130'>￥<?php echo ($goods["goodsPrice"]*$goods["goodsNums"]); ?></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </table>
              </td>
           </tr>
           <tr>
              <td style='text-align:right;padding-right:10px;'>
              	商品总金额：￥<?php echo ($object["order"]['totalMoney']); ?><br/>+ 运费：￥<?php echo ($object["order"]['deliverMoney']); ?><br/>
              	<?php if($object["order"]["useScore"] > 0): ?>使用积分：<?php echo ($object["order"]["useScore"]); ?> 点<br/><?php endif; ?>
              	<span>订单金额：</span><span style='font-weight:bold;font-size:20px;color:red;'>￥<?php echo ($object["order"]['totalMoney']+$object["order"]['deliverMoney']); ?></span><br/>
              	<span style='font-weight:bold;font-size:20px'>实付金额：</span><span style='font-weight:bold;font-size:20px;color:red;'>￥<?php echo ($object["order"]['realTotalMoney']); ?></span>
              </td>
           </tr>
           </tbody>
           <tr>
             <td>
             <span style='font-weight:bold;'>投诉信息</span>
             </td>
           </tr>
           <tr>
             <td>
             <table>
               <tr>
                  <td width='120' align='right'>订单号：</td>
                  <td><?php echo ($object['order']['orderNo']); ?></td>
               </tr>
               <tr>
                  <td width='120' align='right'>投诉人：</td>
                  <td><?php echo ($object['userName']?$object['userName']:$object['loginName']); ?></td>
               </tr>
               <tr>
                  <td align='right'>投诉类型：</td>
                  <td>
                  <?php if($object['complainType'] == 1): ?>承诺的没有做到
	              <?php elseif($object['complainType'] == 2): ?>
	                                          未按约定时间发货
	              <?php elseif($object['complainType'] == 3): ?>
	                                          未按成交价格进行交易
	              <?php elseif($object['complainType'] == 4): ?>
	                                          恶意骚扰<?php endif; ?>
                  </td>
               </tr>
               <tr>
                  <td align='right'>投诉内容：</td>
                  <td><?php echo ($object['complainContent']); ?></td>
               </tr>
               <tr>
                  <td align='right' valign='top'>附件：</td>
                  <td id="layer-photos-complain">

                  <?php if(is_array($object['complainAnnex'])): $i = 0; $__LIST__ = $object['complainAnnex'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$annex): $mod = ($i % 2 );++$i;?><a href="#" onclick="javascript:void(0)">
                  <img  layer-src="/<?php echo ($annex); ?>" width="100" height="100" src="/<?php echo str_replace('.','_thumb.',$annex)?>" alt="<?php echo ($vo['content']); ?>">
                  </a><?php endforeach; endif; else: echo "" ;endif; ?>
                


                  </td>
               </tr>
               <tr>
                  <td align='right'>投诉时间：</td>
                  <td><?php echo ($object['complainTime']); ?></td>
               </tr>
             </table>
             </td>
           </tr>
           <?php if($object["needRespond"] == 1): ?><tr>
             <td>
             <span style='font-weight:bold;'>应诉信息</span>
             </td>
           </tr>
           <tr>
             <td>
             <table>
               <tr>
                  <td width='120' align='right'>移交应付时间：</td>
                  <td><?php echo ($object['deliverRespondTime']); ?></td>
               </tr>
               <tr>
                  <td width='120' align='right'>应诉人：</td>
                  <td><?php echo ($object['order']['shopName']); ?></td>
               </tr>
               <tr>
                  <td align='right'>应诉内容：</td>
                  <td><?php echo ($object['respondContent']); ?></td>
               </tr>
               <tr>
                  <td align='right' valign='top'>附件：</td>
                  <td>
                  <?php if(is_array($object['respondAnnex'])): $i = 0; $__LIST__ = $object['respondAnnex'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$annex): $mod = ($i % 2 );++$i;?><a target='_blank' href="/<?php echo ($annex); ?>">
					<img src="/<?php echo ($annex); ?>" height="100" width="100"/>
				  </a><?php endforeach; endif; else: echo "" ;endif; ?>
                  </td>
               </tr>
               <tr>
                  <td align='right'>应诉时间：</td>
                  <td><?php echo ($object['respondTime']); ?></td>
               </tr>
             </table>
             </td>
             </tr><?php endif; ?>
             <tr>
                <td><span style='font-weight:bold;'>仲裁结果</span></td>
             </tr>
             <tr>
                <td>
                   <table>
                       <tr>
		                  <td width='120' align='right'>处理状态：</td>
		                  <td>
		                   <?php if($object['complainStatus'] == 0): ?>等待处理
			              <?php elseif($object['complainStatus'] == 1): ?>
			                                          等待应诉人回应
			              <?php elseif($object['complainStatus'] == 2): ?>
			                                          应诉人回应
			              <?php elseif($object['complainStatus'] == 3): ?>
			                                          等待仲裁
			              <?php elseif($object['complainStatus'] == 4): ?>
			                                           已仲裁<?php endif; ?>
		                  </td>
		               </tr>
		               <tr>
		                  <td width='120' align='right'>仲裁结果：</td>
		                  <td><?php echo ($object['finalResult']); ?></td>
		               </tr>
		               <tr>
		                  <td width='120' align='right'>仲裁时间：</td>
		                  <td><?php echo ($object['finalResultTime']); ?></td>
		               </tr>
		           </table>
                </td>
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

<script>
  layer.photos({
    photos: '#layer-photos-complain'
  });
</script>