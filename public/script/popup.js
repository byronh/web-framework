$(document).ready(function() {
	$('#loading').hide();
	$('.close').click(function() {
		window.close();
		return false;
	});
	$('#editcode').tabby();
	$('#mainform').submit(function() {
		var code = $('#editcode').val();
		var postdata = $.base64Encode(code);
		$('#editcode').val(postdata);
		$('#editcode').hide();
		$('#loading').show();
	});
});

function refreshparent() {
	window.opener.location.href = window.opener.location.href;
}