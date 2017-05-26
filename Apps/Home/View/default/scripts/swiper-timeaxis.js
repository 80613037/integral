//限时抢购时间轴
var mySwiper = new Swiper('.swiper-container',{
      slidesPerView : "auto",/*设置slider容器能够同时显示的slides数量(carousel模式)。可以设置为number或者 'auto'则自动根据slides的宽度来设定数量。*/
      freeMode : true, /*自动贴合*/
      freeModeSticky : true,/*自动贴合。*/
      centeredSlides : true,/*设定为true时，活动块会居中，而不是默认状态下的居左。*/
slideToClickedSlide:true,/*设置为true则swiping时点击slide会过渡到这个slide。*/
      centeredSlides : true,/*设定为true时，活动块会居中，而不是默认状态下的居左。*/
      onInit: function(swiper){ /*回调函数，初始化后执行。*/
      $(".swiper-slide-active").css({
         "color": '#dd2727',
         "font-weight": 'bold'
     });
    },

onTransitionEnd: function(){
	var i=$(".swiper-slide-active").index();
	$(".ani").eq(i).addClass("on").siblings(".ani").removeClass("on");
     $(".swiper-slide-active").css({
         "color": '#dd2727',
         "font-weight": 'bold'
     });
     $(".swiper-slide").not('.swiper-slide-active').css({
         "color": '#333',
         "font-weight": 'bold'
     });
    },
    onTouchMove: function(){
      $(".swiper-slide").not('.swiper-slide-active').css({
         "color": '#333',
         "font-weight": 'bold'
     });
    }

  })

