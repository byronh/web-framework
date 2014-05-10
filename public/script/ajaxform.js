$(document).ready(function() {
	$('#mainform input, #mainform textarea, #mainform select').bind('keyup blur change', function() {
		var field = this;
		if (this.value != this.lastvalue) {
			if (this.timer) clearTimeout(this.timer);
			this.timer = setTimeout(function() {
				var rules = field.id.split('|');
				var otherfield = '';
				for (var i=0; i < rules.length; i++) {
					if (rules[i].substr(0,7) == 'confirm') {
						otherfield = rules[i].substr(8,6);
						var otherdata = $('#mainform input[name=' + otherfield + ']').attr('value');
						break;
					}
				}
				var postdata = 'fieldname=' + field.name + '&input=' + field.value + '&rules=' + field.id;
				if (otherfield != '') { postdata += '&other=' + otherdata; }
				$.ajax({
					url: '/validate',
					data: postdata,
					type: 'post',
					success: function(result) {
						var output = $('#err' + field.name);
						if (result) {
							output.html(' ' + result);
							field.style.borderColor = '#FF6666';
						} else {
							output.html(' <img src="/icon/tick.png" alt="" />');	
							field.style.borderColor = '#33FF33';
						}
					}
				});
			}, 275);
		}
		this.lastvalue = this.value;
	});
});