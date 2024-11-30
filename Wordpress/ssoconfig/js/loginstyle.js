
jQuery(document).ready(function(){   
	jQuery(document).on('click', '.sshow', function() {
		jQuery(this).toggleClass("fa-eye fa-eye-slash");
         var input = $("#lpassword");
		input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
	});
})