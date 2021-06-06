$(document).ready(function(){
	window.addEventListener('pageshow', function(event){
		var historyTravel = event.persisted || (typeof window.performance != "undefined" && window.performance.navigation.type === 2 );
		if(historyTravel)
			$('#loader').css('display', 'none');
	});
});