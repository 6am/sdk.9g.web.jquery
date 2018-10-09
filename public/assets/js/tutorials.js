	
$(window).scroll(function(e){ 
  var $el = $('.fixedSteps'); 
  var isPositionFixed = ($el.css('position') == 'fixed');
  var parentwidth = $(".parent").width();	
	
	console.log($(this).scrollTop())
  if ($(this).scrollTop() > 530 && !isPositionFixed){ 
	$el.width($el.parent().width());
	  
    $el.css({'position': 'fixed', 'top': '100px'}); 
  }
  if ($(this).scrollTop() < 530 && isPositionFixed)
  {
	$el.width($el.parent().width());
    $el.css({'position': 'static', 'top': '100px'}); 
  } 
});
