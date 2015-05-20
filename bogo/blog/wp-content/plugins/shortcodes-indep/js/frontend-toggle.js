/* Toggle Front-End */
jQuery(document).ready(function($){
	$('p, .trigger').click(function(e){
		e.preventDefault();
		$(this).toggleClass('active').next().slideToggle('fast');
	});
});