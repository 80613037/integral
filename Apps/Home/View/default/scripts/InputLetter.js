
$(function() {		
	$.fn.manhuaInputLetter = function(options) {
		var defaults = {			
			len : 200,
			showId : "show"
		};
		var options = $.extend(defaults,options);	
		var $input = $(this);		
		var $show = $("#"+options.showId);
		$show.html(options.len);
		$input.live("keydown keyup blur",function(e){						
		  	var content =$(this).val();
			var length = content.length;
			var result = options.len - length;
			if (result >= 0){
				$show.html(result);
			}else{
				$(this).val(content.substring(0,options.len))
			}
		});	
	}	
});
$(function (){
	$("#content").manhuaInputLetter({					       
		len : 1000,//限制输入的字符个数				       
		showId : "sid"//显示剩余字符文本标签的ID
	});
});