$(document).ready(function(){
	$('.ajax-submit').click(
		function(e){
			e.preventDefault();
			SendAjaxForm($(this).attr('id_form'), $(this).attr('action'));
			$($(this).attr('id_modal')).modal('hide');
		}
	);
	
	function SendAjaxForm(ajax_form, url){
		//alert($(ajax_form).serialize());
		$.ajax({
			url: url,
			type: 'POST',
			dataType: 'html',
			data: $(ajax_form).serialize(),
			success: function(response){
				//console.log(response);
				result = $.parseJSON(response);
				if(result.result == 'success')
					location.href ='';
				else if(result.result == 'href')
					location.href = result.href;
				else if(result.result == 'error')
					alert(result.message);
				else
					alert(result.result);
			},
			error: function(response){
				alert('Ошибка при доступе к серверу!');
			}
		});
	}
	
	$('.incomingBook').click(function(){
		if(!$(this).attr('date_incoming')){
			if(confirm('Вы подтверждаете получение данного дела?')){
				$.ajax({
					url: 'action_incoming_ajax_form.php',
					type: 'POST',
					dataType: 'html',
					data: 'id='+$(this).attr('id_book'),
					success: function(response){
						//console.log(response);
						result = $.parseJSON(response);
						if(result.result == 'success')
							location.href ='';
						else
							alert(result.result);
					},
					error: function(response){
						alert('Ошибка при доступе к серверу!');
					}
				});
			}
		}
	});
	
	$('#btnAjaxDeleting').click(function(){
		if(confirm('Вы подтверждаете удаление данного дела? Восстановление не подлежит!')){
			$.ajax({
				url: 'action_delete_book_ajax.php',
				type: 'POST',
				dataType: 'html',
				data: 'id='+$(this).attr('id_book'),
				success: function(response){
					//console.log(response);
					result = $.parseJSON(response);
					if(result.result == 'success')
						location.href ='';
					else
						alert(result.result);
				},
				error: function(response){
					alert('Ошибка при доступе к серверу!');
				}
			});
		}
	});
});