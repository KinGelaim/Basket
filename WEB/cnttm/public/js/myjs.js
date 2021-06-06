$(document).ready(function(){
	$('.btn-href').on('click', function(evt){
		if(evt.which == 1){
			isDelete = true;
			location.href=$(this).attr('href');
		}else if(evt.which == 2){
			window.open($(this).attr('href'));
		}
	});
	
	$('.btn-new-href').on('click', function(evt){
		window.open($(this).attr('href'));
	});
	
	$('.referseSpisok').on('click', function(){
		if($(this).children()[0].textContent == "▼")
			$(this).children()[0].textContent = "▲";
		else
			$(this).children()[0].textContent = "▼";
	});
	
	$('.answerYesSelect').on('change', function(){
		var ds = $('#'+$(this).attr('for_question')).attr('value');
		ds = parseInt(ds) + 1;
		$('#'+$(this).attr('for_question')).attr('value', ds);
		ChangeSpanOnValue($('#'+$(this).attr('for_question')));
	});
	
	$('.answerNoneSelect').on('change', function(){
		var ds = $('#'+$(this).attr('for_question')).attr('value');
		ds = parseInt(ds) - 1;
		$('#'+$(this).attr('for_question')).attr('value', ds);
		ChangeSpanOnValue($('#'+$(this).attr('for_question')));
	});
	
	function ChangeSpanOnValue(k){
		k.text(k.attr('value'));
	}
	
	$('.myModal').on('click', function(){
		var json = $.parseJSON($(this).attr('prepod'));
		var fullName = json['surname'] + " " + json['name'] + " " + json['patronymic'];
		$($(this).attr('modal')).find('.modal-title').text(fullName);
		var fullImage = "";
		$.each(json['photo'], function(key,value){
			fullImage += '<img src="images/' + value + '" style="width: 40%;"/> ';
		});
		$($(this).attr('modal')).find('.modal-body').empty()
					.append('<p style="text-align: center;">'+fullImage+'</p><pre>' + json['full_information'] + '</pre>');
		$($(this).attr('modal')).modal('show');
	});
	
	var tableToExcel = (function() {
		if(navigator.userAgent.search('Firefox') >= 0){
			var uri = 'data:application/vnd.ms-excel;base64,', 
			template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridLines/></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table border="2px solid black">{table}</table></body></html>',
			base64 = function(s) {
				return window.btoa(unescape(encodeURIComponent(s)))
			},
			format = function(s,c) {
				return s.replace(/{(\w+)}/g, function(m,p) {
					return c[p];
				})
			}
			
			//Для firefox
			return function(table, name) {
				if (!table.nodeType) 
					table = document.getElementById(table)
				var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
				window.location.href = uri + base64(format(template, ctx))
			}
		}else{
			var uri = 'data:application/vnd.ms-excel;base64,', 
			template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridLines/></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table border="2px solid black">{table}</table></body></html>',
			base64 = function(s) {
				return window.btoa(unescape(encodeURIComponent(s)))
			},
			format = function(s,c) {
				return s.replace(/{(\w+)}/g, function(m,p) {
					return c[p];
				})
			},
			downloadURI = function (uri, name) {
				var link = document.createElement("a");
				link.download = name;
				link.href = uri;
				link.click();
			}
			return function(table, name, fileName) {
				if (!table.nodeType) 
					table = document.getElementById(table)
				var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
				var resuri = uri + base64(format(template, ctx))
				downloadURI(resuri, fileName);
			}
		}
	})();
	
	$('#createExcel').on('click', function(){
		tableToExcel('resultTable',$(this).attr('real_name_table'),$(this).attr('real_name_table')+'.xls');
	});
});