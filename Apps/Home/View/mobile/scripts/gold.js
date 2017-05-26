$(function () {
   
    $(".gold_button").click(function () {
        $(".zhezhaoceng").show();
        $(".message_warning").show().animate({
            "opacity": "1",
        }, "fast");
    })
    $(".message_warning .queding").click(function () {
        
            $(".message_warning").hide();
            $(".zhezhaoceng").hide();
            $(".message_warning").css("opacity","0")
    })
})