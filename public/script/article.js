$(document).ready(function() {
	$('textarea').attr('rows', 3);
	$('textarea').focus(function() {
		$(this).attr('rows', 12);
	});
});