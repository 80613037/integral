$(function () {
    $(".shopping_car_list").on("click", ".check", function () {
        var number = parseInt($(this).find("img").size());
        if (number) {
            $(this).find("img").remove();
            $(this).css("border", "1px solid #ddd");
        } else {
            $(this).append("<img src='../images/gwc_1xzdg.png' />");
            $(this).css("border", "none");
            $(".shopping_car_footer .check").find("img").remove();
            $(".shopping_car_footer .check").css("border", "1px solid #ddd");
        }
    });
    $(".shopping_car_footer").on("click", ".check", function () {
        var number = parseInt($(this).find("img").size());
        if (number) {
            $(this).find("img").remove();
            $(this).css("border", "1px solid #ddd");
            $(".shopping_car_list .check").find("img").remove();
            $(".shopping_car_list .check").css("border", "1px solid #ddd");
        } else {
            $(this).append("<img src='../images/gwc_1xzdg.png' />");
            $(this).css("border", "none");
            $(".shopping_car_list .check").children().remove();
            $(".shopping_car_list .check").append("<img src='../images/gwc_1xzdg.png' />");
            $(".shopping_car_list .check").css("border", "none");
        }
    });
    $(".shopping_car_list").on("click", ".add", function () {
        var number = parseInt($(this).siblings("small").html());
        $(this).siblings("small").html(number + 1);
        $(this).siblings(".cut").css("background", "#ff9420");
    });
    $(".shopping_car_list").on("click", ".cut", function () {
        var number = parseInt($(this).siblings("small").html());
        if (number > 1) {
            $(this).siblings("small").html(number - 1);
        } else if(number==1) {
            $(this).siblings("small").html(number - 1);
            $(this).css("background", "#dbdfe6");
        }
    })
})