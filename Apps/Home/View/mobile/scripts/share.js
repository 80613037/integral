$(function(){
	//************************点击弹出半透明框 分享
	$(".detail-footer.join-group-footer .buynow").click(function(){
		$(".prompt-wrap").css("display","block");
		$('html,body').animate({scrollTop: '0px'}, 100);//因为页面很长，有纵向滚动条，先让页面滚动到最顶端，然后禁止滑动事件，这样可以使遮罩层锁住整个屏幕
        $('.prompt-wrap').bind("touchmove",function(e){
                e.preventDefault();
        });
	});
	 $('.prompt-wrap').click(function(){
		 $(this).hide();
	});
});




