$(document).ready(function(){
	$('.btn-href').on('click', function(evt){
		if(evt.which == 1){
			isDelete = true;
			location.href=$(this).attr('href');
		}else if(evt.which == 2){
			window.open($(this).attr('href'));
		}
	});
	
	$(function(){
		$.each($('.datepicker'),function(){
			strDate = $(this).val();
			$(this).datepicker();
			$(this).datepicker("option", "dateFormat", "dd.mm.yy");
			$(this).val(strDate);
		});
	});
	
	$('.steps').on('click', function(evt){
		$($(this).attr('first_step')).css('display', 'none');
		$($(this).attr('second_step')).css('display', 'block');
	});
	
	$('.replace-attr').on('click', function(evt){
		$($(this).attr('replace_element')).attr($(this).attr('replace_attr'), $(this).attr('replace_value'));
	});
	
	$('#open_resolution').on('click', function(evt){
		if($('#resolution_list').val().length > 0)
			window.open($('#resolution_list').val(), '_blank');
	});
	
	$('#delete_resolution').on('click', function(evt){
		if($('#resolution_list').val().length > 0)
			location.href = $('#resolution_list option:selected').attr('delete_href');
	});
	
	$('#new_file_resolution').on('change', function(evt){
		$('#real_name_resolution').val($(this).val().replace('C:\\fakepath\\',''));
	});
	
	$('#id_executor').on('change', function(evt){
		$('#position').val($('#id_executor option:selected').attr('position'));
		$('#telephone').val($('#id_executor option:selected').attr('telephone'));
	});
	
	//Создание отчётов
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
	
	$('.print_area').on('click', '.createExcel', function(e){
		tableToExcel($(this).attr('href_table'),$(this).attr('real_name_table'),$(this).attr('real_name_table')+'.xls');
	});
	
	//Протокол отчётов C#
	$('.btnProtocolOpen').on('click', function(evt){
		window.open($(this).attr('href_protocol'), '_blank');
	});
});