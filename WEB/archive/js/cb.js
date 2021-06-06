$(document).ready(function(){
	$('.cbChose').click(function(){
		$($(this).attr('forID')).prop('checked', !$($(this).attr('forID')).prop('checked'));
	});
});