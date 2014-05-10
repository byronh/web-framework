$(document).ready(function() {
	var speed = 100;
	$('.mainFeature').mouseleave(function() {
		$(this).animate({
			opacity: 1.0
		}, speed);
	});
	$('.feature').mouseenter(function() {
		$(this).animate({
			opacity: 1.0
		}, speed);
		$(this).find('img').animate({
			top: -6,
			left: -10,
			height: 189,
			width: 253
		}, speed);
	});
	$('.feature').mouseleave(function() {
		$(this).animate({
			opacity: 0.85
		}, speed);
		$(this).find('img').animate({
			top: 0,
			left: 0,
			height: 172,
			width: 230
		}, speed);
	});
});