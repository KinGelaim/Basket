$(document).ready(function(){

	$('#datepicker').datepicker();
	$('#datepicker').datepicker("option", "dateFormat", "dd.mm.yy");
	
	$('#myForm').validate({
		rules:{
			FIO:{
				minlength: 2
			},
			telephone:{
				required: true,
				number: true
			}
		},
		messages:{
			FIO:{
				minlength: 'Введите минимум 2 символа'
			},
			telephone:{
				required: 'Поле обязательно для заполнения',
				number: 'Можно вводить только цифры'
			}
		}
	});
});