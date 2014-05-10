$(document).ready(function() {
	$("tr:even").addClass("even");
	$("tr:odd").addClass("odd");
	$('.popup').click(function() {
		var width = screen.availWidth*0.75;
		var height = screen.availHeight*0.79;
		var left = screen.availWidth*0.125;
		var top = screen.availHeight*0.115;
		popup = window.open($(this).attr('href'), '', 'height=' + height + ',width=' + width + ',top=' + top + ',left=' + left);
		if (window.focus) {
			popup.focus();
		}
		return false;
	});
});