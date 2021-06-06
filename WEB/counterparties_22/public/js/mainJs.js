$(document).ready(function(){
	//Удаление
	$('.btn-danger').on('click', function(evt){
		if(!confirm('Вы подтверждаете действие?')){
			evt.cancel();
		}
	});

	$('form').submit(function(e){
		//$('#maincontainer').append("<div id='circularG'><div id='circularG_1' class='circularG'></div><div id='circularG_2' class='circularG'></div><div id='circularG_3' class='circularG'></div><div id='circularG_4' class='circularG'></div><div id='circularG_5' class='circularG'></div><div id='circularG_6' class='circularG'></div><div id='circularG_7' class='circularG'></div><div id='circularG_8' class='circularG'></div></div>");
		$('#loader').css('display', 'block');
	});
	
	$('#closeLoader').on('click', '.close', function(e){
		$('#loader').css('display', 'none');
	});
	
	//Переходы
		
	var isDelete = false;

	$('.btn-href').on('click', function(evt){
		if(evt.which == 1){
			isDelete = true;
			location.href=$(this).attr('href');
		}else if(evt.which == 2){
			window.open($(this).attr('href'));
		}
	});
	
	$('.steps').on('click', function(evt){
		$($(this).attr('first_step')).css('display', 'none');
		$($(this).attr('second_step')).css('display', 'block');
	});
	
	//Excel
	$('#createExcel').on('click', function(e){
		tableToExcel('resultTable',$(this).attr('real_name_table'),$(this).attr('real_name_table')+'.xls');
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
			}/*,
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
			}*/
			
			//Для firefox
			return function(table, name) {
				if (!table.nodeType) 
					table = document.getElementById(table)
				var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
				window.location.href = uri + base64(format(template, ctx))
				//downloadURI(resuri, fileName);
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
});