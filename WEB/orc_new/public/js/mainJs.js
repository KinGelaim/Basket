$(document).ready(function(){
	//Удаление
	$('.btn-danger').on('click', function(evt){
		if(!confirm('Вы подтверждаете действие?')){
			evt.cancel();
		}
	});
	
	//Сессия
	$('.panel-body li a').on('click', function(evt){
		$.ajax({
			url: $(this).attr('ajax-href'),
			data: 'setCookie=' + $(this).attr('href').substring(1, $(this).attr('href').length),
			type: 'GET',
			dataType: 'json',
			success: function (data) {
				console.log(data);
			},
			error: function(data){
				console.log(data);
			}
		});
	});
	
	//Отправка данных
	$('.btn-ajax-delete-directed').on('click', function(evt){
		if(confirm('Вы подтверждаете удаление данного направления?')){
			var prNameLabel = $(this);
			$.ajax({
				url: $(this).attr('ajax-href'),
				data: $('#post_send input[name=_token]').serialize() + '&' + $(this).attr('ajax-data'),
				type: 'post',
				dataType: 'json',
				success: function (data) {
					prNameLabel.parents().first().remove();
				},
				error: function(data){
					alert('Увы, не удалось удалить! :(\nОбратитесь к системному администратору!');
					console.log(data);
				}
			});
		}
	});
	
	$('#implementation_contract').on('change', function(evt){
		var isImplementationContract = false;
		if($(this).prop('checked'))
			isImplementationContract = true;
		$.ajax({
			url: $(this).attr('ajax-href'),
			data: 'isImplementation=' + isImplementationContract,
			type: 'GET',
			dataType: 'json',
			success: function (data) {
				if(data == true)
				{
					/*var txtMessage = "";
					if(isImplementationContract)
					{
						txtMessage = "Контракт заключен";
					}
					$('#divMessage').append('<div id="msgComplete" class="col-md-12 alert alert-success" role="alert">' + txtMessage + '</div>');
						setTimeout(function(){
							$('#msgComplete').alert('close');
						}, 3000);*/
				}else
					alert('Увы, не удалось изменить! :(\nОбратитесь к системному администратору!');
			},
			error: function(data){
				alert('Увы, не удалось изменить! :(\nОбратитесь к системному администратору!');
				console.log(data);
			}
		});
	});
	
	$('#check_complete').on('change', function(evt){
		var isStartProcess = false;
		if($(this).prop('checked'))
			isStartProcess = true;
		$.ajax({
			url: $(this).attr('ajax-href'),
			data: 'isProcess=' + isStartProcess,
			type: 'GET',
			dataType: 'json',
			success: function (data) {
				if(data == true)
				{
					var txtMessage = "";
					if(isStartProcess)
					{
						txtMessage = "Процесс завершен";
					}else
					{
						txtMessage = "Процесс запущен";
					}
					$('#divMessage').append('<div id="msgComplete" class="col-md-12 alert alert-success" role="alert">' + txtMessage + '</div>');
						setTimeout(function(){
							$('#msgComplete').alert('close');
						}, 3000);
				}else
					alert('Увы, не удалось удалить! :(\nОбратитесь к системному администратору!');
			},
			error: function(data){
				alert('Увы, не удалось удалить! :(\nОбратитесь к системному администратору!');
				console.log(data);
			}
		});
	});
	
	$('#formChoseContractForTen').submit(function(e){
		var need_summa = $(this).attr('check-count'); 
		var summa = 0; 
		$('.count_element').each(function(){ 
			summa = summa + Number($(this).val());
		}); 
		if($(this).attr('check-count') < summa){ 
			e.preventDefault();
			if(window.confirm('Неправильно указано количество элементов!\nМаксимальное количествоs: ' + need_summa + '\nПерейти на страницу редактирования общего количество комплектующих элементов?'))
				window.location.href = $(this).attr('href-to-change-components');
		}
	});
	
	$('.formChoseComponentForContract').submit(function(e){
		var need_summa = $(this).attr('check-count'); 
		var summa = 0; 
		$('.count_element').each(function(){ 
			summa = summa + Number($(this).val());
		}); 
		if(summa <= 0 || $(this).attr('check-count') < summa){
			e.preventDefault();
			if(window.confirm('Неправильно указано количество элементов!\nМаксимальное количествоs: ' + need_summa + '\nПерейти на страницу редактирования общего количество комплектующих элементов?'))
				window.location.href = $(this).attr('href-to-change-components');
		}
	});
	
	$('#formStoreOldComponent').submit(function(e){
		if(parseInt($('#need_count').val()) > parseInt($('#count_party').val()))
			e.preventDefault();
	});
	
	$('form').submit(function(e){
		//$('#maincontainer').append("<div id='circularG'><div id='circularG_1' class='circularG'></div><div id='circularG_2' class='circularG'></div><div id='circularG_3' class='circularG'></div><div id='circularG_4' class='circularG'></div><div id='circularG_5' class='circularG'></div><div id='circularG_6' class='circularG'></div><div id='circularG_7' class='circularG'></div><div id='circularG_8' class='circularG'></div></div>");
		$('#loader').css('display', 'block');
	});
	
	$('#closeLoader').on('click', '.close', function(e){
		$('#loader').css('display', 'none');
	});
	
	$('#resolution_table_tbody').on('click', '.ajax-send-on-delete-href', function(e){
		var prObject = $(this);
		DeleteResolutionAjax(prObject);
	});
	
	$('.ajax-send-on-delete-href').on('click', function(e){
		var prObject = $(this);
		DeleteResolutionAjax(prObject);
	});
	
	function DeleteResolutionAjax(prObject){
		$.ajax({
			url: $(prObject).attr('ajax-href'),
			type: $(prObject).attr('ajax-method'),
			dataType: 'json',
			success: function (data) {
				var txtMessage = "";
				if(data == true)
					txtMessage = "Резолюция успешно удалена!";
				else
					txtMessage = 'Увы, не удалось удалить! :(\nОбратитесь к системному администратору!';
				$($(prObject).attr('div-message')).append('<div id="msgComplete" class="col-md-12 alert alert-success" role="alert">' + txtMessage + '</div>');
				setTimeout(function(){
					$('#msgComplete').alert('close');
				}, 3000);
				if(data == true)
					$(prObject).parent().parent().remove();
			},
			error: function(data){
				txtMessage = 'Увы, не удалось удалить! :(\nОбратитесь к системному администратору!';
				console.log(data);
			}
		});
	};
	
	//Переходы
	$("#addNewContract").on("click", function(evt)
	{
		location.href=$(this).attr('href');
	});
	
	$('.rowsContractClick td').on('click', function(evt)
	{
		if(!$(this).hasClass('table_coll_btn'))
			location.href=$(this).parents().first().attr('href');
	});
	
	$('.rowsContractClickPEO').on('click', function(evt)
	{
		//location.href=$(this).attr('href');
		$('#selectContractModalLabel').text('Договор ' + $(this).attr('number_contract'));
		$('#result').attr('href', $(this).attr('contract_peo'));
		$('#new_reestr').attr('href', $(this).attr('contract_new_reestr'));
		$('#reestr').attr('href', $(this).attr('contract_reestr'));
		$('#sogl').attr('href', $(this).attr('contract_reconciliation'));
		$('#modal_btn_peo').attr('href', $(this).attr('contract_new_peo'));
		$('#selectContract').modal('show');
	});
	
	$('.rowsManagementClickContracts').on('click', function(evt)
	{
		$('#send_new_number').attr('action', $(this).attr('contract_renumber'));
		$('#selectContract').modal('show');
	});
	
	var isDelete = false;
	
	$('.rowsInvoiceClick').on('click', function(evt)
	{
		if(!isDelete)
			location.href=$(this).attr('href');
	});
	
	$('.rowsInvoiceClick').on('click', '.btn-href', function(evt)
	{
		if(!isDelete)
			location.href=$(this).attr('href');
	});
	
	$('.btn-href').on('click', function(evt){
		if(evt.which == 1){
			isDelete = true;
			location.href=$(this).attr('href');
		}else if(evt.which == 2){
			window.open($(this).attr('href'));
		}
	});
	
	$('#refreshContract').on('click', function(evt)
	{
		strLocation = $(this).attr('href');
		if($('#sel1').val().length > 0)
			strLocation += '?year=' + $('#sel1').val();
		if($('#sel2').val().length > 0)
			if($('#sel1').val().length > 0)
				strLocation += '&view=' + $('#sel2').val();
			else
				strLocation += '?view=' + $('#sel2').val();
		if($('#sel3').val().length > 0)
			if($('#sel1').val().length > 0 || $('#sel2').val().length > 0)
				strLocation += '&counterpartie=' + $('#sel3').val();
			else
				strLocation += '?counterpartie=' + $('#sel3').val();
		if($('#sel5').length > 0)
			if($('#sel5').val().length > 0)
				if($('#sel1').val().length > 0 || $('#sel2').val().length > 0 || $('#sel3').val().length > 0)
					strLocation += '&goz_work=' + $('#sel5').val();
				else
					strLocation += '?goz_work=' + $('#sel5').val();
		location.href = strLocation;
	});
	
	$('#refreshContractReestr').on('click', function(evt)
	{
		strLocation = $(this).attr('href');
		if($('#sel1').val().length > 0)
			strLocation += '?year=' + $('#sel1').val();
		if($('#sel2').val().length > 0)
			if($('#sel1').val().length > 0)
				strLocation += '&view=' + $('#sel2').val();
			else
				strLocation += '?view=' + $('#sel2').val();
		if($('#sel3').val().length > 0)
			if($('#sel1').val().length > 0 || $('#sel2').val().length > 0)
				strLocation += '&department=' + $('#sel3').val();
			else
				strLocation += '?department=' + $('#sel3').val();
		if($('#sel4').val().length > 0)
			if($('#sel1').val().length > 0 || $('#sel2').val().length > 0 || $('#sel3').val().length > 0)
				strLocation += '&counterpartie=' + $('#sel4').val();
			else
				strLocation += '?counterpartie=' + $('#sel4').val();
		location.href = strLocation;
	});
	
	$('#selSearch').on('change', function(evt)
	{
		if($('#selSearch').val() == 'name_view_contract'){
			$('#searchValueInput').val('');
			$('#searchValueInput').css('display', 'none');
			$('#selectNameViewWorkSearch').css('display', 'block');
		}else{
			$('#searchValueInput').val('');
			$('#searchValueInput').css('display', 'block');
			$('#selectNameViewWorkSearch').css('display', 'none');
		}
	});
	
	$('#selectNameViewWorkSearch').on('change', function(evt)
	{
		$('#searchValueInput').val($('#selectNameViewWorkSearch').val());
	});
	
	$('.changeCounterpartie').on('change', function(evt)
	{
		location.href = '?counterpartie=' + $(this).val();
	});
	
	$('.steps').on('click', function(evt){
		$($(this).attr('first_step')).css('display', 'none');
		$($(this).attr('second_step')).css('display', 'block');
	});
	
	//Отдел управления договорами
	$('.change_contract_number').on('change', function(evt){
		$('#numberContract').val($('#number_pp').val() + '‐' + $('#index_dep').val() + '‐' + $('#year_contract').val());
	});
	
	$('.btn-edit-protocol').on('click', function(evt){
		$('#formToUpdateProtocol').attr('action', $(this).attr('href_edit_protocol'));
		$('#update_name_protocol').val($(this).attr('name_protocol'));
		$('#update_date_on_first_protocol').val($(this).attr('date_on_first_protocol'));
		$('#update_date_registration_protocol').val($(this).attr('date_registration_protocol'));
		$('#update_date_signing_protocol').val($(this).attr('date_signing_protocol'));
		$('#update_date_signing_counterpartie_protocol').val($(this).attr('date_signing_counterpartie_protocol'));
		$('#update_date_entry_ento_force_additional_agreement').val($(this).attr('date_entry_ento_force_additional_agreement'));
		if($(this).attr('is_oud') == 1)
			$('#update_is_oud').prop('checked', true);
		else
			$('#update_is_oud').prop('checked', false);
		if($(this).attr('is_oud_el') == 1)
			$('#update_is_oud_el').prop('checked', true);
		else
			$('#update_is_oud_el').prop('checked', false);
		if($(this).attr('is_dep') == 1)
			$('#update_is_dep').prop('checked', true);
		else
			$('#update_is_dep').prop('checked', false);
		if($(this).attr('is_dep_el') == 1)
			$('#update_is_dep_el').prop('checked', true);
		else
			$('#update_is_dep_el').prop('checked', false);
		$('#update_date_oud_protocol').val($(this).attr('date_oud_protocol'));
		$('#update_date_oud_el_protocol').val($(this).attr('date_oud_el_protocol'));
		$('#update_date_dep_protocol').val($(this).attr('date_dep_protocol'));
		$('#update_date_dep_el_protocol').val($(this).attr('date_dep_el_protocol'));
	});
	
	$('.titleDocumentClick').on('click', function(evt){
		$('#' + $(this).attr('type_title_document') + '_modal .form_main').attr('action',$(this).attr('my_action'));
		$('#' + $(this).attr('type_title_document') + '_modal .form_second').attr('action',$(this).attr('my_action_resol'));
		$('#' + $(this).attr('type_title_document') + '_modal h5').text($(this).attr('my_title'));
		$('#' + $(this).attr('type_title_document') + '_modal input[name=date_title_document]').val($(this).attr('my_date'));
		$('#' + $(this).attr('type_title_document') + '_modal input[name=text_title_document]').val($(this).attr('my_text'));
		if($(this).attr('my_relevance') == 1)
			$('#' + $(this).attr('type_title_document') + '_modal input[name=relevance_document]').prop('checked', true);
		else
			$('#' + $(this).attr('type_title_document') + '_modal input[name=relevance_document]').prop('checked', false);
		$('#' + $(this).attr('type_title_document') + '_modal .form_main button[type=submit]').text($(this).attr('my_btn_text'));
		$('#' + $(this).attr('type_title_document') + '_modal').modal('show');
		
		$('#' + $(this).attr('type_title_document') + '_modal .new_resolution_div').css('display', $(this).attr('new_display_resol'));
		$('#' + $(this).attr('type_title_document') + '_modal .resolution_div').css('display',$(this).attr('display_resol'));
		if($(this).attr('display_resol') == 'block'){
			$('#' + $(this).attr('type_title_document') + '_modal .resolution_list').empty();
			if($(this).attr('resolutions_list').length == 0)
				$('#' + $(this).attr('type_title_document') + '_modal .resolution_list').append('<option></option>');
			if($(this).attr('resolutions_list')){
				var prCall = '#' + $(this).attr('type_title_document') + '_modal .resolution_list';
				$.each($.parseJSON($(this).attr('resolutions_list')), function(key, value)
				{
					$(prCall).append('<option value="http://' + $(this)[0]['path_resolution'] + '" download_href="resolution_download/' + $(this)[0]['id'] + '">' + $(this)[0]['real_name_resolution'] + '</option>');
				});
			}
		}
	});
	
	$('.select_counterpartie_reestr').on('change', function(evt){
		$('#miniCounterpartie').val($('.select_counterpartie_reestr option:selected').attr('full_name'));
		$('#inn_counterpartie').val($('.select_counterpartie_reestr option:selected').attr('inn'));
	});	
	
	$('.chose-counterpartie').on('click', function(evt){
		var id_counterpartie_pr = $(this).attr('id_counterpartie');
		$('#chose_counterpartie').modal('hide');
		$('#miniCounterpartie').val('');
		$('#inn_counterpartie').val('');
		var id_select = 'sel4';
		if($(this).attr('id_select'))
			id_select = $(this).attr('id_select');
		$('#' + id_select + ' option').each(function(){
			if($(this).val() == id_counterpartie_pr){
				$(this).prop('selected',true);
				$('#miniCounterpartie').val($('.select_counterpartie_reestr option:selected').attr('full_name'));
				$('#inn_counterpartie').val($('.select_counterpartie_reestr option:selected').attr('inn'));
			}
			else
				$(this).prop('selected',false);
		});
	});
	
	$('.chose-counterpartie-independent').on('click', function(evt){
		var id_counterpartie_pr = $(this).attr('id_counterpartie');
		$('#chose_counterpartie').modal('hide');
		$('#miniCounterpartie').val('');
		$('#inn_counterpartie').val('');
		var id_select = 'sel4';
		if($(this).attr('id_select'))
			id_select = $(this).attr('id_select');
		$('#' + id_select).empty()
							.append('<option></option>')
							.append("<option value='"+$(this).attr('id_counterpartie')+"' full_name='"+$(this).attr('full_name_counterpartie')+"' inn='"+$(this).attr('inn_counterpartie')+"'>"+$(this).attr('name_counterpartie')+"</option>");
		
		$('#' + id_select + ' option').each(function(){
			if($(this).val() == id_counterpartie_pr){
				$(this).prop('selected',true);
				$('#miniCounterpartie').val($('.select_counterpartie_reestr option:selected').attr('full_name'));
				$('#inn_counterpartie').val($('.select_counterpartie_reestr option:selected').attr('inn'));
			}
			else
				$(this).prop('selected',false);
		});
	});
	
	$('#application_reestr').on('change', function(evt)
	{
		if($('#application_reestr').prop('checked'))
			$('#label_date_registration_project_reestr').text('Дата регистрации заявки');
		else
			$('#label_date_registration_project_reestr').text('Дата регистрации проекта');
	});
	
	$('.btn-update-date-contract').on('click', function(evt)
	{
		$('#form_update_date_contract_reestr').attr('action', $(this).attr('action_update'));
		
		$json = $.parseJSON($(this).attr('reestr_date_contract'));
		
		$('#update_name_date_contract').val($json['name_date_contract']);
		$('#update_term_date_contract').val($json['term_date_contract']);
		$('#update_end_date_contract').val($json['end_date_contract']);
	});
	
	$('.btn-update-date-maturity').on('click', function(evt)
	{
		$('#form_update_date_maturity_reestr').attr('action', $(this).attr('action_update'));
		
		$json = $.parseJSON($(this).attr('reestr_date_maturity'));
		
		$('#update_name_date_maturity').val($json['name_date_maturity']);
		$('#update_term_date_maturity').val($json['term_date_maturity']);
		$('#update_end_date_maturity').val($json['end_date_maturity']);
	});
	
	$('.btn-update-amount').on('click', function(evt)
	{
		$('#form_update_amount_reestr').attr('action', $(this).attr('action_update'));
		
		$json = $.parseJSON($(this).attr('reestr_amount'));
		
		$('#update_name_amount').val($json['name_amount']);
		$('#update_value_amount').val($json['value_amount']);
		$('#update_unit_amount option').each(function(e){
			if($(this).val() == $json['unit_amount']) {
				$(this).prop('selected', true);
			}
			else
				$(this).prop('selected', false);
		});
		if ($json['vat_amount'])
			$('#update_vat_amount').prop('checked', true);
		else
			$('#update_vat_amount').prop('checked', false);
		if ($json['approximate_amount'])
			$('#update_approximate_amount').prop('checked', true);
		else
			$('#update_approximate_amount').prop('checked', false);
		if ($json['fixed_amount'])
			$('#update_fixed_amount').prop('checked', true);
		else
			$('#update_fixed_amount').prop('checked', false);
	});
	
	//Планово-экономический отдел
	var count_elements = 0;
	$(function(){
		$('.count-old-element').each(function(){
			count_elements++;
		});
	});
	var count_checkpoints = 0;
	$(function(){
		$('.count-old-checkpoint').each(function(){
			count_checkpoints++;
		});
	});
	
	$('#btn_add_element').on('click', function(evt){
		$('#detailing_list').append("<div class='col-md-4' style='margin-top: 5px;'><select name='name_elements[" + count_elements + "]' class='form-control'><option></option>" + $(this).attr('select_elemet') + "</select></div><div class='col-md-5' style='margin-top: 5px;'><select name='name_view_work[" + count_elements + "]' class='form-control'><option></option>" + $(this).attr('select_view_work_elements') + "</select></div><div class='col-md-3' style='margin-top: 5px;'><input name='count_elements[" + count_elements + "]' class='form-control' type='text' value=''/></div>");
		count_elements++;
		$('#detailing_list').scrollTop($('#detailing_list').prop('scrollHeight'));
	});
	
	$('#btn_add_checkpoint').on('click', function(evt){
		$('#checkpoint_list').append("<div class='col-md-6' style='margin-top: 5px;'><input name='checkpoint_date[" + count_checkpoints + "]' class='form-control datepicker' type='text' value=''/></div><div class='col-md-6' style='margin-top: 5px;'><input name='checkpoint_comment[" + count_checkpoints + "]' class='form-control' type='text' value=''/></div>");
		$('.datepicker').datepicker();
		$('.datepicker').datepicker("option", "dateFormat", "dd.mm.yy");
		count_checkpoints++;
		$('#checkpoint_list').scrollTop($('#checkpoint_list').prop('scrollHeight'));
	});
	
	$('#date_test').on('click', function(evt){
		if($('#date_test').prop('checked'))
			$('#date_textarea').attr('readonly', false);
		else
			$('#date_textarea').attr('readonly', true);
	});
	
	$('#btn_add_state').on('click', function(evt){
		$('#table_history_states').css('display', 'none');
		$('#add_history_states').css('display', 'block');
		$('#btn_add_new_history').css('display', 'inline-block')
									.text('Добавить');
		$('#btn_close_new_history_states').css('display', 'inline-block');
		$('#btn_add_state').css('display', 'none');
		$('#btn_close_new_history').css('display', 'none');
		$('#new_name_state').val('');
		$('#date_state').val($(this).attr('clear_date'));
		$('#history_states form').attr('action', $(this).attr('action_state'));
	});
	
	$('#btn_close_new_history_states').on('click', function(evt){
		$('#table_history_states').css('display', 'block');
		$('#add_history_states').css('display', 'none');
		$('#btn_add_new_history').css('display', 'none');
		$('#btn_close_new_history_states').css('display', 'none');
		$('#btn_add_state').css('display', 'block');
		$('#btn_close_new_history').css('display', 'inline-block');
		$('#btn_destroy_state').css('display', 'none');
	});
	
	$('.updateState').on('click', function(evt){
		$('#table_history_states').css('display', 'none');
		$('#add_history_states').css('display', 'block');
		$('#btn_add_new_history').css('display', 'inline-block')
									.text('Редактировать');
		$('#btn_close_new_history_states').css('display', 'inline-block');
		$('#btn_add_state').css('display', 'none');
		$('#btn_close_new_history').css('display', 'none');
		$('#new_name_state').val($(this).attr('name_state'));
		$('#comment_state').val($(this).attr('comment_state'));
		$('#date_state').val($(this).attr('date_state'));
		$('#history_states form').attr('action', $(this).attr('action_state'));
		$('#id_state').val($(this).attr('id_state'));
		$('#btn_destroy_state').css('display', 'inline-block');
		$('#btn_destroy_state').attr('destroy_state',$(this).attr('destroy_state'));
		var pr_name_state = $(this).attr('name_state');
		var is_not_equal = true;
		$('#type_state option').each(function(e){
			if($(this).val() == pr_name_state) {
				$(this).prop('selected', true);
				is_not_equal = false;
				$('#new_name_state').css('display','none');
			}
		});
		if(is_not_equal){
			$('#type_state option').each(function(e){
				if($(this).val() == 'Другое') {
					$(this).prop('selected', true);
				}
			});
			$('#new_name_state').css('display','block');
		}
	});
	
	$('#btn_destroy_state').on('click', function(evt){
		$('#history_states form').attr('action',$(this).attr('destroy_state'));
	});
	
	$('#type_state').on('change', function(evt){
		$('#type_state option').each(function(e){
			if($(this).prop('selected')){
				if($(this).val() != 'Другое') {
					$('#new_name_state').val($(this).val());
					$('#new_name_state').css('display','none');
				}
				else {
					$('#new_name_state').val('');
					$('#new_name_state').css('display','block');
				}
			}
		});
	});
	
	$('#type_work').on('change', function(evt){
		$('#type_work option').each(function(e){
			if($(this).prop('selected')){
				if($(this).val() != 'Другое') {
					$('#new_name_work').val($(this).val());
					$('#new_name_work').css('display','none');
				}
				else {
					$('#new_name_work').val('');
					$('#new_name_work').css('display','block');
				}
			}
		});
	});
	
	$('.btn_all_history_protocol_states').on('click', function(evt){
		$('#btn_add_state_protocol').attr('action_state',$(this).attr('href_add_states'));
		$('#tbody_history_protocol_states').empty();
		$.each($.parseJSON($(this).attr('states')), function(key, value) {
			$('#tbody_history_protocol_states').append("<tr class='rowsContract' id_state='{{$state->id}}' ><td>" + value['name_state'] + "<br/>" + (value['comment_state'] != null ? value['comment_state'] : '') + "</td><td>" + value['date_state'] + "</td><td>" + value['surname'] + " " + value['name'][0] + "." + value['patronymic'][0] + ".</td></tr>");
		});
	});
	
	$('#type_state_protocol').on('change', function(evt){
		$('#type_state_protocol option').each(function(e){
			if($(this).prop('selected')){
				if($(this).val() != 'Другое') {
					$('#new_name_protocol_state').val($(this).val());
					$('#new_name_protocol_state').css('display','none');
				}
				else {
					$('#new_name_protocol_state').val('');
					$('#new_name_protocol_state').css('display','block');
				}
			}
		});
	});
	
	var select_message = 1;
	
	$('#add_message').on('click', function(evt){
		$('#message_div').append("<div class='col-md-8' style='margin-top: 5px;'><select id='select_message" + select_message + "' name='select_message[" + select_message + "]' class='form-control'><option></option>" + $(this).attr('new_message') + "</select></div><div class='col-md-4' style='margin-top: 5px;'><button type='button' class='btn btn-primary btn_select_message' for_message='select_message" + select_message + "'>Просмотреть</button></div>");
		select_message++;
	});
	
	$('#message_div').on('click', '.btn_select_message', function(evt){
		if($('#'+$(this).attr('for_message')).val().length > 0)
		{
			$('#number_application').val($('#'+$(this).attr('for_message') + ' option:selected').attr('number_application'));
			$('#number_outgoing').val($('#'+$(this).attr('for_message') + ' option:selected').attr('number_outgoing'));
			$('#date_outgoing').val($('#'+$(this).attr('for_message') + ' option:selected').attr('date_outgoing'));
			$('#number_incoming').val($('#'+$(this).attr('for_message') + ' option:selected').attr('number_incoming'));
			$('#date_incoming').val($('#'+$(this).attr('for_message') + ' option:selected').attr('date_incoming'));
			$('#theme_application').val($('#'+$(this).attr('for_message') + ' option:selected').attr('theme_application'));
			if($('#'+$(this).attr('for_message') + ' option:selected').attr('is_protocol') == '1')
				$('#create_protocol').prop('disabled', true);
			else
				$('#create_protocol').prop('disabled', false);
			$('#create_protocol').attr('href', $('#'+$(this).attr('for_message') + ' option:selected').attr('href_create_protocol'));
			$('#form_create_protocol').attr('action', $('#'+$(this).attr('for_message') + ' option:selected').attr('href_create_protocol'));
			$('#form_message').css('display', 'none');
			$('#this_message').css('display', 'block');
		}
	});
	
	$('#message_div').on('click', '.table_select_message td', function(evt){
		//console.log($(this).parents().first());
		if(!$(this).hasClass('table_coll_btn')){
			$('#number_application').val($(this).parents().first().attr('number_application'));
			$('#number_outgoing').val($(this).parents().first().attr('number_outgoing'));
			$('#date_outgoing').val($(this).parents().first().attr('date_outgoing'));
			$('#number_incoming').val($(this).parents().first().attr('number_incoming'));
			$('#date_incoming').val($(this).parents().first().attr('date_incoming'));
			$('#theme_application').val($(this).parents().first().attr('theme_application'));
			if($(this).parents().first().attr('is_protocol') == '1'){
				$('#create_protocol').prop('disabled', true)
									.attr('href', $(this).parents().first().attr('href_create_protocol'));
				$('#name_protocol').val($(this).parents().first().attr('name_protocol'))
									.prop('readonly', true);
				$('#form_create_protocol').attr('action', $(this).parents().first().attr('href_create_protocol'));
				$('#additional_agreement').prop('disabled', true);
				if($(this).parents().first().attr('is_additional_agreement') == '1'){
					$('#additional_agreement').prop('checked', true);
					$('#label_text_protocol').text('Название доп. соглашения:');
				}
				else{
					$('#additional_agreement').prop('checked', false);
					$('#label_text_protocol').text('Название протокола:');
				}
			}
			else{
				$('#create_protocol').prop('disabled', false)
									.attr('href', $(this).parents().first().attr('href_create_protocol'));
				$('#name_protocol').val('').prop('readonly', false);
				$('#form_create_protocol').attr('action', $(this).parents().first().attr('href_create_protocol'));
				$('#additional_agreement').prop('disabled', false);
				if($(this).parents().first().attr('is_additional_agreement') == '1'){
					$('#additional_agreement').prop('checked', true);
					$('#label_text_protocol').text('Название доп. соглашения:');
				}
				else{
					$('#additional_agreement').prop('checked', false);
					$('#label_text_protocol').text('Название протокола:');
				}
			}			
			$('#form_message').css('display', 'none');
			$('#this_message').css('display', 'block');
		}
	});
	
	$('#close_message').on('click', function(evt){
		$('#form_message').css('display', 'block');
		$('#this_message').css('display', 'none');
	});
	
	$('.tableRowsMessage').on('click', function(){
		if($(this).hasClass('marked')){
			$(this).removeClass('marked');
			$('#' + $(this).attr('for_check')).prop('checked', false);
		}else{
			$(this).addClass('marked');
			$('#' + $(this).attr('for_check')).prop('checked', true);
		}
	});
	
	$('.rowsAdditionalDocumentResolution').on('click', function(evt){
		var additional_document = $.parseJSON($(this).attr('additional_document'));
		var mainForm = '#edit_resolutions';
		//Резолюции
		$(mainForm + ' #resolution_list').empty();
		$(mainForm + ' #resolution_table_tbody').empty();
		$.each(additional_document['resolutions'], function(key, value)
		{
			if(value['type_resolution'] == 1)
				$(mainForm + ' #resolution_list').append('<option value="http://' + value['path_resolution'] + '" download_href="resolution_download/' + value['id'] + '" style="color: rgb(239,19,198);">' + value['real_name_resolution'] + '</option>');
			else
				$(mainForm + ' #resolution_list').append('<option value="http://' + value['path_resolution'] + '" download_href="resolution_download/' + value['id'] + '">' + value['real_name_resolution'] + '</option>');
			$(mainForm + ' #resolution_table_tbody').append("<tr class='rowsContract'><td>" + value['real_name_resolution'] + "</td><td><button class='btn btn-danger ajax-send-on-delete-href' ajax-href='" + value['href_delete_ajax'] + "' ajax-method='GET' div-message='#divMessagePrDs'>Удалить</button></td></tr>");
		});
		$(mainForm + ' #formInShowNewResolution').attr('action', $(this).attr('href_add_resolution'));
		$(mainForm).modal('show');
	});
	
	$('.rowsAdditionalDocument').on('click', function(evt){
		var additional_document = $.parseJSON($(this).attr('additional_document'));
		var mainForm = '#edit_protocol';
		if(additional_document['is_protocol'] == 1)
			var mainForm = '#edit_protocol';
		else
			var mainForm = '#edit_additional_agreement';
		$(mainForm + ' #formToUpdateProtocol').attr('action', $(this).attr('href_edit_additional_document'));
		$(mainForm + ' #update_application_protocol').val(additional_document['application_protocol']);
		$(mainForm + ' #update_name_protocol').val(additional_document['name_protocol']);
		$(mainForm + ' #update_name_work_protocol').val(additional_document['name_work_protocol']);
		$(mainForm + ' #update_date_protocol').val(additional_document['date_protocol']);
		$(mainForm + ' #update_date_on_first_protocol').val(additional_document['date_on_first_protocol']);
		$(mainForm + ' #update_date_registration_protocol').val(additional_document['date_registration_protocol']);
		$(mainForm + ' #update_date_signing_protocol').val(additional_document['date_signing_protocol']);
		$(mainForm + ' #update_date_signing_counterpartie_protocol').val(additional_document['date_signing_counterpartie_protocol']);
		$(mainForm + ' #update_date_entry_ento_force_additional_agreement').val(additional_document['date_entry_ento_force_additional_agreement']);
		if(additional_document['is_oud'] == 1)
			$(mainForm + ' #update_is_oud').prop('checked', true);
		else
			$(mainForm + ' #update_is_oud').prop('checked', false);
		if(additional_document['is_oud_el'] == 1)
			$(mainForm + ' #update_is_oud_el').prop('checked', true);
		else
			$(mainForm + ' #update_is_oud_el').prop('checked', false);
		if(additional_document['is_dep'] == 1)
			$(mainForm + ' #update_is_dep').prop('checked', true);
		else
			$(mainForm + ' #update_is_dep').prop('checked', false);
		if(additional_document['is_dep_el'] == 1)
			$(mainForm + ' #update_is_dep_el').prop('checked', true);
		else
			$(mainForm + ' #update_is_dep_el').prop('checked', false);
		$(mainForm + ' #update_date_oud_protocol').val(additional_document['date_oud_protocol']);
		$(mainForm + ' #update_date_dep_protocol').val(additional_document['date_dep_protocol']);
		$(mainForm + ' #update_date_oud_el_protocol').val(additional_document['date_oud_el_protocol']);
		$(mainForm + ' #update_date_dep_el_protocol').val(additional_document['date_dep_el_protocol']);
		$(mainForm + ' #update_amount_protocol').val(additional_document['amount_protocol']);
		$(mainForm + ' #update_amount_year_protocol').val(additional_document['amount_year_protocol']);
		//Резолюции
		$(mainForm + ' #resolution_list').empty();
		$.each(additional_document['resolutions'], function(key, value)
		{
			if(value['type_resolution'] == 1)
				$(mainForm + ' #resolution_list').append('<option value="http://' + value['path_resolution'] + '" download_href="resolution_download/' + value['id'] + '" style="color: rgb(239,19,198);">' + value['real_name_resolution'] + '</option>');
			else
				$(mainForm + ' #resolution_list').append('<option value="http://' + value['path_resolution'] + '" download_href="resolution_download/' + value['id'] + '">' + value['real_name_resolution'] + '</option>');
		});
		$(mainForm + ' #formNewResolution').attr('action', $(this).attr('href_add_resolution'));
		$(mainForm).modal('show');
	});
	
	$('.rowsAdditionalDocument2').on('click', function(evt){
		var additional_document = $.parseJSON($(this).attr('additional_document'));
		var mainForm = '#edit_protocol';
		if(additional_document['is_protocol'] == 1)
			var mainForm = '#edit_protocol';
		else
			var mainForm = '#edit_additional_agreement';
		$(mainForm + ' #formToUpdateProtocol').attr('action', $(this).attr('href_edit_additional_document'));
		$(mainForm + ' #update_application_protocol').val(additional_document['application_protocol']);
		$(mainForm + ' #update_name_protocol').val(additional_document['name_protocol']);
		$(mainForm + ' #update_name_work_protocol').val(additional_document['name_work_protocol']);
		$(mainForm + ' #update_date_protocol2').val(additional_document['date_protocol']);
		$(mainForm + ' #update_date_on_first_protocol2').val(additional_document['date_on_first_protocol']);
		$(mainForm + ' #update_date_registration_protocol2').val(additional_document['date_registration_protocol']);
		$(mainForm + ' #update_date_signing_protocol2').val(additional_document['date_signing_protocol']);
		$(mainForm + ' #update_date_signing_counterpartie_protocol2').val(additional_document['date_signing_counterpartie_protocol']);
		$(mainForm + ' #update_date_entry_ento_force_additional_agreement2').val(additional_document['date_entry_ento_force_additional_agreement']);
		if(additional_document['is_oud'] == 1)
			$(mainForm + ' #update_is_oud').prop('checked', true);
		else
			$(mainForm + ' #update_is_oud').prop('checked', false);
		if(additional_document['is_oud_el'] == 1)
			$(mainForm + ' #update_is_oud_el').prop('checked', true);
		else
			$(mainForm + ' #update_is_oud_el').prop('checked', false);
		if(additional_document['is_dep'] == 1)
			$(mainForm + ' #update_is_dep').prop('checked', true);
		else
			$(mainForm + ' #update_is_dep').prop('checked', false);
		if(additional_document['is_dep_el'] == 1)
			$(mainForm + ' #update_is_dep_el').prop('checked', true);
		else
			$(mainForm + ' #update_is_dep_el').prop('checked', false);
		$(mainForm + ' #update_date_oud_protocol2').val(additional_document['date_oud_protocol']);
		$(mainForm + ' #update_date_dep_protocol2').val(additional_document['date_dep_protocol']);
		$(mainForm + ' #update_date_oud_el_protocol2').val(additional_document['date_oud_el_protocol']);
		$(mainForm + ' #update_date_dep_el_protocol2').val(additional_document['date_dep_el_protocol']);
		$(mainForm + ' #update_amount_protocol').val(additional_document['amount_protocol']);
		$(mainForm + ' #update_amount_year_protocol').val(additional_document['amount_year_protocol']);
		//Резолюции
		$(mainForm + ' #resolution_list').empty();
		$.each(additional_document['resolutions'], function(key, value)
		{
			if(value['type_resolution'] == 1)
				$(mainForm + ' #resolution_list').append('<option value="http://' + value['path_resolution'] + '" download_href="resolution_download/' + value['id'] + '" style="color: rgb(239,19,198);">' + value['real_name_resolution'] + '</option>');
			else
				$(mainForm + ' #resolution_list').append('<option value="http://' + value['path_resolution'] + '" download_href="resolution_download/' + value['id'] + '">' + value['real_name_resolution'] + '</option>');
		});
		$(mainForm + ' #formNewResolution').attr('action', $(this).attr('href_add_resolution'));
		$(mainForm).modal('show');
	});
	
	$('.select_for_department').on('change', function(evt)
	{
		//Считываем ID выбранного подразделения
		var pr_id_department = null;
		$('.select_for_department option').each(function(k,v){
			if($(this).prop('selected'))
				pr_id_department = $(this).attr('id_department');
		});
		//Отображаем только тех исполнителей, которые относятся к данному подразделению
		if(pr_id_department != null)
			$('.select_curator option').each(function(k,v){
				if($(this).val() != '')
					if($(this).attr('id_department') == pr_id_department)
						$(this).css('display','block');
					else
						$(this).css('display', 'none');
			});
		//Выбираем первое значение в списке исполнителей
		
	});
	
	//Второй отдел
	$('.rowsUpdateIsp').on('click', function(evt)
	{
		$('#form_update_isp').attr('action', $(this).attr('href'));
		$('#action_update_isp').val($(this).attr('href'));
		$('#new_comment_isp').attr('action', $(this).attr('comment_isp'));
		$('#action_new_comment').val($(this).attr('comment_isp'));
		//Параметры
		var id_element = $(this).data('isp')['id_element'];
		$('#selElement_update option').each(function(){
			if($(this).val() == id_element)
				$(this).prop('selected',true);
			else
				$(this).prop('selected',false);
		});
		var id_view_work = $(this).data('isp')['id_view_work_elements'];
		$('#selView_update option').each(function(){
			if($(this).val() == id_view_work)
				$(this).prop('selected',true);
			else
				$(this).prop('selected',false);
		});
		$('#updateIsp input[name=year_update]').val($(this).data('isp')['year']);
		$('#updateIsp input[name=id_element_update]').val($(this).data('isp')['id_element']);
		//Количество испытаний
		$('#updateIsp input[name=january_update]').val($(this).data('isp')['january']);
		$('#updateIsp input[name=february_update]').val($(this).data('isp')['february']);
		$('#updateIsp input[name=march_update]').val($(this).data('isp')['march']);
		$('#updateIsp input[name=april_update]').val($(this).data('isp')['april']);
		$('#updateIsp input[name=may_update]').val($(this).data('isp')['may']);
		$('#updateIsp input[name=june_update]').val($(this).data('isp')['june']);
		$('#updateIsp input[name=july_update]').val($(this).data('isp')['july']);
		$('#updateIsp input[name=august_update]').val($(this).data('isp')['august']);
		$('#updateIsp input[name=september_update]').val($(this).data('isp')['september']);
		$('#updateIsp input[name=october_update]').val($(this).data('isp')['october']);
		$('#updateIsp input[name=november_update]').val($(this).data('isp')['november']);
		$('#updateIsp input[name=december_update]').val($(this).data('isp')['december']);
		//Проверка испытаний
		if($(this).data('isp')['january_check'] == 1)
			$('#updateIsp input[name=january_check_update]').prop('checked',true);
		else
			$('#updateIsp input[name=january_check_update]').prop('checked',false);
		if($(this).data('isp')['february_check'] == 1)
			$('#updateIsp input[name=february_check_update]').prop('checked',true);
		else
			$('#updateIsp input[name=february_check_update]').prop('checked',false);
		if($(this).data('isp')['march_check'] == 1)
			$('#updateIsp input[name=march_check_update]').prop('checked',true);
		else
			$('#updateIsp input[name=march_check_update]').prop('checked',false);
		if($(this).data('isp')['april_check'] == 1)
			$('#updateIsp input[name=april_check_update]').prop('checked',true);
		else
			$('#updateIsp input[name=april_check_update]').prop('checked',false);
		if($(this).data('isp')['may_check'] == 1)
			$('#updateIsp input[name=may_check_update]').prop('checked',true);
		else
			$('#updateIsp input[name=may_check_update]').prop('checked',false);
		if($(this).data('isp')['june_check'] == 1)
			$('#updateIsp input[name=june_check_update]').prop('checked',true);
		else
			$('#updateIsp input[name=june_check_update]').prop('checked',false);
		if($(this).data('isp')['july_check'] == 1)
			$('#updateIsp input[name=july_check_update]').prop('checked',true);
		else
			$('#updateIsp input[name=july_check_update]').prop('checked',false);
		if($(this).data('isp')['august_check'] == 1)
			$('#updateIsp input[name=august_check_update]').prop('checked',true);
		else
			$('#updateIsp input[name=august_check_update]').prop('checked',false);
		if($(this).data('isp')['september_check'] == 1)
			$('#updateIsp input[name=september_check_update]').prop('checked',true);
		else
			$('#updateIsp input[name=september_check_update]').prop('checked',false);
		if($(this).data('isp')['october_check'] == 1)
			$('#updateIsp input[name=october_check_update]').prop('checked',true);
		else
			$('#updateIsp input[name=october_check_update]').prop('checked',false);
		if($(this).data('isp')['november_check'] == 1)
			$('#updateIsp input[name=november_check_update]').prop('checked',true);
		else
			$('#updateIsp input[name=november_check_update]').prop('checked',false);
		if($(this).data('isp')['december_check'] == 1)
			$('#updateIsp input[name=december_check_update]').prop('checked',true);
		else
			$('#updateIsp input[name=december_check_update]').prop('checked',false);
		$('#updateIsp').modal('show');
		//Комментарии
		$('#updateIsp input[name=january_update]').attr('message', '');
		$('#updateIsp input[name=february_update]').attr('message', '');
		$('#updateIsp input[name=march_update]').attr('message', '');
		$('#updateIsp input[name=april_update]').attr('message', '');
		$('#updateIsp input[name=may_update]').attr('message', '');
		$('#updateIsp input[name=june_update]').attr('message', '');
		$('#updateIsp input[name=july_update]').attr('message', '');
		$('#updateIsp input[name=august_update]').attr('message', '');
		$('#updateIsp input[name=september_update]').attr('message', '');
		$('#updateIsp input[name=october_update]').attr('message', '');
		$('#updateIsp input[name=november_update]').attr('message', '');
		$('#updateIsp input[name=december_update]').attr('message', '');
		$.each($(this).data('isp')['month'], function(key, value){
			var prMessage = '';
			if($('#updateIsp input[name='+value['month']+']').attr('message'))
				prMessage += $('#updateIsp input[name='+value['month']+']').attr('message');
			prMessage += value['message'] + ';';
			$('#updateIsp input[name='+value['month']+']').attr('message', prMessage);
		});
	});
	
	$('.comment_for_naryad').on('dblclick', function(e)
	{
		$('#form_update_isp').css('display', 'none');
		$('#comment_isp').css('display', 'block');
		$('#month_new_comment').val($(this).attr('name'));
		$('#list_message').empty()
							.append("<div class='col-md-12'><label>Комментарии:</label></div>");
		if($(this).attr('message')){
			var prArr = $(this).attr('message').split(';');
			for(var i = 0; i < prArr.length; i++)
				if(prArr[i].length > 0)
					$('#list_message').append("<div class='col-md-11'><input class='form-control' type='text' value='"+prArr[i]+"' style='margin-top: 5px;' readonly /></div><div class='col-md-1'><input class='form-check-input' type='checkbox' style='margin-top: 15px;' disabled /></div>");
		}
	});
	
	$('#close_comment').on('click', function(evt){
		$('#comment_isp').css('display', 'none');
		$('#form_update_isp').css('display', 'block');
	});
	
	$('#add_new_comment').on('click', function(evt){
		$('#comment_isp').css('display', 'none');
		$('#new_comment_isp').css('display', 'block');
	});
	
	$('#close_new_comment').on('click', function(evt){
		$('#new_comment_isp').css('display', 'none');
		$('#comment_isp').css('display', 'block');
	});
	
	$('.openDivSelect').on('change', function(evt){
		var prSelectId = $(this).val();
		$('.closeDivSelect').each(function(e){
			$(this).css('display', 'none');
			if($(this).attr('id') == prSelectId)
				$(this).css('display', 'block');
		});
	});
	
	$('.modalActsBTN').on('click', function(evt){
		$('#allActs').css('display','block');
		$('#newAct').css('display','none');
		$('#editAct').css('display','none');
		$('#tbodyForModalActs').empty();
		$.each($.parseJSON($(this).attr('acts')), function(key, value) {
			$('#tbodyForModalActs').append("<tr class='rowsContract'><td>" + 
				(value['number_act']!=null?value['number_act']:'') + "</td><td>"+
				(value['date_act']!=null?value['date_act']:'')+"</td><td>"+
				(value['number_outgoing_act']!=null?value['number_outgoing_act']:'')+"</td><td>"+
				(value['date_outgoing_act']!=null?value['date_outgoing_act']:'')+"</td><td>"+
				(value['number_incoming_act']!=null?value['number_incoming_act']:'')+"</td><td>"+
				(value['date_incoming_act']!=null?value['date_incoming_act']:'')+"</td><td>"+
				value['amount_act']+"</td><td><button type='button' class='btn btn-primary editActBTN' type='button' edit_act_href='"+value['edit_href']+"' number_act='"+(value['number_act']!=null?value['number_act']:'')+"' date_act='"+(value['date_act']!=null?value['date_act']:'')+"' number_outgoing_act='"+(value['number_outgoing_act']!=null?value['number_outgoing_act']:'')+"' date_outgoing_act='"+(value['date_outgoing_act']!=null?value['date_outgoing_act']:'')+"' number_incoming_act='"+(value['number_incoming_act']!=null?value['number_incoming_act']:'')+"' date_incoming_act='"+(value['date_incoming_act']!=null?value['date_incoming_act']:'')+"' amount_act='"+value['amount_act']+"'>Редактировать</button></td></tr>");
		});
		$('#tbodyForModalActs').append("<tr class='rowsContract'><td></td><td></td><td></td><td></td><td></td><td><b>Сумма:</b></td><td><b>"+$(this).attr('amount_acts')+"</b></td><td></td></tr>");
		$('#formNewAct').attr('action', $(this).attr('new_act_href'));
		$('#modalActs').modal('show');
	});
	
	$('#allActs').on('click','.editActBTN', function(evt){
		$('#allActs').css('display','none');
		$('#editAct').css('display', 'block');
		$('#formEditAct').attr('action',$(this).attr('edit_act_href'));
		$('#edit_number_act').val($(this).attr('number_act'));
		$('#edit_date_act').val($(this).attr('date_act'));
		$('#edit_number_outgoing_act').val($(this).attr('number_outgoing_act'));
		$('#edit_date_outgoing_act').val($(this).attr('date_outgoing_act'));
		$('#edit_number_incoming_act').val($(this).attr('number_incoming_act'));
		$('#edit_date_incoming_act').val($(this).attr('date_incoming_act'));
		$('#edit_amount_act').val($(this).attr('amount_act'));
	});
	
	$('#allContractActs').on('click','.editContractActBTN', function(evt){
		$('#allContractActs').css('display','none');
		$('#editContractAct').css('display', 'block');
		$('#formEditContractAct').attr('action',$(this).attr('edit_act_href'));
		$('#edit_contract_number_act').val($(this).attr('number_act'));
		$('#edit_contract_date_act').val($(this).attr('date_act'));
		$('#edit_contract_number_outgoing_act').val($(this).attr('number_outgoing_act'));
		$('#edit_contract_date_outgoing_act').val($(this).attr('date_outgoing_act'));
		$('#edit_contract_number_incoming_act').val($(this).attr('number_incoming_act'));
		$('#edit_contract_date_incoming_act').val($(this).attr('date_incoming_act'));
		$('#edit_contract_amount_act').val($(this).attr('amount_act'));
	});
	
	//Канцелярия
	$('#newApplication').on('shown.bs.modal', function(e)
	{
		if($('#selectCounerChancery').val() == ''){
			$(this).modal('hide');
			$('#divError').append('<div id="msgError" class="col-md-12 alert alert-danger" role="alert">Выберите контрагента!</div>');
			setTimeout(function(){
				$('#msgError').alert('close');
			}, 3000);
		}else{
			$('#valIdCounterpartie').val($('#selectCounerChancery').val());
			$.each($('#selectCounerChancery option'), function(e){
				if($(this).val() == $('#selectCounerChancery').val()){
					$('#valNameCounterpartie').val($(this).attr('realName'));
					$('#valTelephoneCounterpartie').val($(this).attr('telephone'));
					$('#valCuratorCounterpartie').val($(this).attr('curator'));
				}
			});
			$('#date_begin').val($('#beginDate').val());
			$('#date_end').val($('#endDate').val());
		}
	});
	
	$('.rowsApplications td').on('click', function(evt)
	{
		if(!$(this).hasClass('table_coll_btn')){
			/*$('#valIdCounterpartie_update').val($('#selectCounerChancery').val());
			$.each($('#selectCounerChancery option'), function(e){
				if($(this).val() == $('#selectCounerChancery').val()){
					$('#valNameCounterpartie_update').val($(this).attr('realName'));
					$('#valTelephoneCounterpartie_update').val($(this).attr('telephone'));
					$('#valCuratorCounterpartie_update').val($(this).attr('curator'));
				}
			});
			$('#date_begin').val($('#beginDate_update').val());
			$('#date_end').val($('#endDate_update').val());*/
			$('#updateApplication form').attr('action', $(this).parents().first().attr('href'));
			$('#action').val($(this).parents().first().attr('href'));
			$('#updateApplication').modal('show');
			$('#number_application').val($(this).parents().first().attr('number_application'));
			$('#number_outgoing').val($(this).parents().first().attr('number_outgoing'));
			$('#date_outgoing').val($(this).parents().first().attr('date_outgoing'));
			$('#number_incoming').val($(this).parents().first().attr('number_incoming'));
			$('#date_incoming').val($(this).parents().first().attr('date_incoming'));
			$('#theme_application').val($(this).parents().first().attr('theme_application'));
			/*var k = $(this).attr('directed_application');
			$.each($('#directed_application option'), function(e){
				if($(this).val() == k)
					$(this).attr('selected','true');
			});*/
			var k = $(this).parents().first().attr('real_directed_application');
			var isDeletedDirection = true;
			$('#directed_application').empty()
										.append('<option></option>')
										.attr('disabled', false);
			$.each($.parseJSON($('#all_users').attr('all_users')), function(key, value) {
				$('#directed_application').append('<option value="' + value['id'] + '" real_name="' + value['surname'] + ' ' + value['name'] + ' ' + value['patronymic'] + '">' + value['surname'] + ' ' + value['name'][0] + '.' + value['patronymic'][0] + '.' + '</option>');
			});
			$('#directed_application option').each(function(e){
				$(this).attr('selected',false);
				if(k.length > 0){
					if($(this).attr('real_name') == k){
						$(this).prop('selected','true');
						isDeletedDirection = false;
					}
				}else{
					isDeletedDirection = false;
				}
			});
			if(isDeletedDirection){
				$('#directed_application').prepend('<option>' + $(this).parents().first().attr('directed_application') + '</option>')
											.attr('disabled', true);
				
			}
			//alert($('#directed_application :contains("'+$(this).attr('directed_application')+'")').val().length);
			//$('#directed_application :contains("'+$(this).attr('directed_application')+'")').attr('selected','selected');
			$('#date_directed').val($(this).parents().first().attr('date_directed'));
			//$('#resolution_application').val($(this).attr('resolution_application'));
			//$('#date_resolution').val($(this).attr('date_resolution'));
			if($(this).parents().first().attr('isp_dir') == 1)
				$('#isp_dir').prop('checked',true);
			else
				$('#isp_dir').prop('checked',false);
			if($(this).parents().first().attr('zam_isp_dir_niokr') == 1)
				$('#zam_isp_dir_niokr').prop('checked',true);
			else
				$('#zam_isp_dir_niokr').prop('checked',false);
			if($(this).parents().first().attr('main_in') == 1)
				$('#main_in').prop('checked',true);
			else
				$('#main_in').prop('checked',false);
			if($(this).parents().first().attr('dir_sip') == 1)
				$('#dir_sip').prop('checked',true);
			else
				$('#dir_sip').prop('checked',false);
			if($(this).parents().first().attr('dir_peo') == 1)
				$('#dir_peo').prop('checked',true);
			else
				$('#dir_peo').prop('checked',false);
			if($(this).parents().first().attr('isp_dir_check') == 1)
				$('#isp_dir_check').prop('checked',true);
			else
				$('#isp_dir_check').prop('checked',false);
			if($(this).parents().first().attr('zam_isp_dir_niokr_check') == 1)
				$('#zam_isp_dir_niokr_check').prop('checked',true);
			else
				$('#zam_isp_dir_niokr_check').prop('checked',false);
			if($(this).parents().first().attr('main_in_check') == 1)
				$('#main_in_check').prop('checked',true);
			else
				$('#main_in_check').prop('checked',false);
			if($(this).parents().first().attr('dir_sip_check') == 1)
				$('#dir_sip_check').prop('checked',true);
			else
				$('#dir_sip_check').prop('checked',false);
			if($(this).parents().first().attr('dir_peo_check') == 1)
				$('#dir_peo_check').prop('checked',true);
			else
				$('#dir_peo_check').prop('checked',false);
			$('#add_new_resolution').attr('action_new_resolution',$(this).parents().first().attr('action_resolution'));
			$('#form_new_application').attr('action', $(this).parents().first().attr('action_resolution'));
			$('#resolution_list').empty()
			if($(this).parents().first().attr('resolutions_list').length == 0)
				$('#resolution_list').append('<option></option>');
			if($(this).parents().first().attr('resolutions_list'))
				$.each($.parseJSON($(this).parents().first().attr('resolutions_list')), function(key, value)
				{
					$('#resolution_list').append('<option value="http://' + $(this)[0]['path_resolution'] + '" download_href="resolution_download/' + $(this)[0]['id'] + '">' + $(this)[0]['real_name_resolution'] + '</option>');
				});
			$('#directed_list').empty();
			var kDirection = [];
			if($(this).parents().first().attr('directed_list'))
				$.each($.parseJSON($(this).parents().first().attr('directed_list')), function(key, value)
				{
					var prNameDirection = $(this)[0]['surname'] + ' ' + $(this)[0]['name'] + ' ' + $(this)[0]['patronymic'];
					var proverkaNewDirection = true;
					$.each(kDirection, function(k,v){
						if(v == prNameDirection)
							proverkaNewDirection = false;
					});
					if(proverkaNewDirection){
						$('#directed_list').append('<label>' + prNameDirection + '</label><br/>');
						kDirection.push(prNameDirection);
					}
					//console.log(kDirection);
				});
		}
	});
	
	$('#refreshApplication').on('click', function(evt)
	{
		strLocation = $(this).attr('href');
		if($('#selectCounerChancery').val().length > 0)
			strLocation += '?counterpartie=' + $('#selectCounerChancery option:selected').attr('realName');
		if($('#beginDate').val().length > 0)
			if($('#selectCounerChancery').val().length > 0)
				strLocation += '&begindate=' + $('#beginDate').val();
			else
				strLocation += '?begindate=' + $('#beginDate').val();
		if($('#endDate').val().length > 0)
			if($('#selectCounerChancery').val().length > 0 || $('#beginDate').val().length > 0)
				strLocation += '&enddate=' + $('#endDate').val();
			else
				strLocation += '?enddate=' + $('#endDate').val();
		location.href = strLocation;
	});
	
	//Согласование
	$('#fix_amount_contract_reestr').on('click', function(e){
		if($('#fix_amount_contract_reestr').prop('checked') == true)
			if($('#amount_reestr').val().length > 0 && $('#amount_contract_reestr').val().length > 0 && $('#amount_reestr').val() != $('#amount_contract_reestr').val()) {
				alert('Внимание! Какую из сумм фиксировать?');
				e.preventDefault();
			} else {
				if($('#amount_reestr').val().length > 0)
					$('#amount_contract_reestr').val($('#amount_reestr').val());
				else
					$('#amount_reestr').val($('#amount_contract_reestr').val());
			}
	});
	
	$('#comment_label').on('click', function(e){
		if($('#comment_label').prop('checked'))
			$('#textarea_for_comment').attr('readonly', false);
		else
			$('#textarea_for_comment').attr('readonly', true);
	});
	
	$('#add_direction').on('click', function(e){
		if($('#directed_select').val().length > 0){
			var count_new_direction = 0;
			$('.new_directed_input_list').each(function(e){
				count_new_direction++;
			});
			
			$('#directed_list').append('<div style="position: relative;"><input class="form-control" type="text" value="' + $('#directed_select option:selected').attr('real_name') + '" style="margin-top: 2px;" readonly /><input class="form-control new_directed_input_list" type="text" name="name_new_direction[' + count_new_direction + ']" value="' + $('#directed_select option:selected').val() + '" style="display: none;" readonly /><span class="closebtn" onclick="$(this).parents().first().remove()" style="position: absolute; top: 3px; right: 15px;">&times;</span></div>');
			count_new_direction++;
			$('#directed_list').scrollTop($('#directed_list').prop('scrollHeight'));
			$('#directed_select option:eq(0)').prop('selected', true);
		}
	});
	
	$('#directed_select').on('change', function(e){
		//$('#add_direction').click();
		if($('#directed_select').val().length > 0){
			var count_new_direction = 0;
			$('.new_directed_input_list').each(function(e){
				count_new_direction++;
			});
			
			$('#directed_list').append('<div style="position: relative;"><input class="form-control" type="text" value="' + $('#directed_select option:selected').attr('real_name') + '" style="margin-top: 2px;" readonly /><input class="form-control new_directed_input_list" type="text" name="name_new_direction[' + count_new_direction + ']" value="' + $('#directed_select option:selected').val() + '" style="display: none;" readonly /><span class="closebtn" onclick="$(this).parents().first().remove()" style="position: absolute; top: 3px; right: 15px;">&times;</span></div>');
			count_new_direction++;
			$('#directed_list').scrollTop($('#directed_list').prop('scrollHeight'));
			$('#directed_select option:eq(0)').prop('selected', true);
		}
	});
	
	$('#add_modal_direction').on('click', function(e){
		var count_new_direction = 0;
		$('.new_directed_input_list').each(function(e){
			count_new_direction++;
		});
		
		$('.check_directed_list').each(function(e){
			if($(this).prop('checked')){
				$(this).prop('checked', false);
				$(this).parent().parent().removeClass('marked');
				$('#directed_list').append('<div style="position: relative;"><input class="form-control" type="text" value="' + $(this).attr('real_name') + '" style="margin-top: 2px;" readonly /><input class="form-control new_directed_input_list" type="text" name="name_new_direction[' + count_new_direction + ']" value="' + $(this).val() + '" style="display: none;" readonly /><span class="closebtn" onclick="$(this).parents().first().remove()" style="position: absolute; top: 3px; right: 15px;">&times;</span></div>');
				count_new_direction++;
			}
		});
		$('#directed_modal_list').modal('hide');
	});
	
	$('.change_select').on('change', function(e){
		strLocation = $(this).attr('href');
		if($('#select_counterpartie').val().length > 0)
			strLocation += '?counterpartie=' + $('#select_counterpartie').val();
		if($('#select_view').val())
			if($('#select_view').val().length > 0)
				if($('#select_counterpartie').val().length > 0)
					strLocation += '&view=' + $('#select_view').val();
				else
					strLocation += '?view=' + $('#select_view').val();
		location.href = strLocation;
	});
	
	$('.btn-href-select').on('click', function(e){
		strLocation = $(this).attr('href');
		if($('#select_counterpartie').val().length > 0)
			strLocation += '?counterpartie=' + $('#select_counterpartie').val();
		if($('#select_view').val())
			if($('#select_view').val().length > 0)
				if($('#select_counterpartie').val().length > 0)
					strLocation += '&view=' + $('#select_view').val();
				else
					strLocation += '?view=' + $('#select_view').val();
		location.href = strLocation;
	});
	
	var countSelectedMessage = 0;
	
	$('.rowsMessage').on('click', function(){
		if($(this).hasClass('marked')){
			$(this).removeClass('marked');
			$('#' + $(this).attr('for_check')).prop('checked', false);
			$('#' + $(this).attr('for_input')).remove();
		}else{
			$(this).addClass('marked');
			$('#' + $(this).attr('for_check')).prop('checked', true);
			$(this).append("<input id='select_message" + countSelectedMessage + "' name='select_message[" + countSelectedMessage + "]' value='" + $(this).attr('id_application') + "' style='display:none;'></input>")
					.attr('for_input', 'select_message' + countSelectedMessage + '');
			countSelectedMessage++;
		}
	});
	
	//Резолюция
	$('#add_new_resolution').on('click', function(evt){
		$('#form_all_application').css('display', 'none');
		$('#form_new_application').css('display', 'block')
									.attr('action', $(this).attr('action_new_resolution'));
		//Информация о записи после сохранения
		$('#number_application_scan').val($('#number_application').val());
		$('#number_outgoing_scan').val($('#number_outgoing').val());
		$('#date_outgoing_scan').val($('#date_outgoing').val());
		$('#number_incoming_scan').val($('#number_incoming').val());
		$('#date_incoming_scan').val($('#date_incoming').val());
		$('#theme_application_scan').val($('#theme_application').val());
		$('#directed_application_scan option').each(function(e){
			if($(this).val() == $('#directed_application').val())
				$(this).attr('selected',true);
			else
				$(this).attr('selected',false);
		});
		//alert($('#directed_application').val());
		//$('#directed_application_scan :contains("'+$('#directed_application').val()+'")').attr('selected',true);
		$('#directed_block_scan').empty();
		$('.new_directed_input_list').each(function(e){
			$('#directed_block_scan').append($(this).clone());
		});
		
		$('#date_directed_scan').val($(this).attr('#date_directed'));
		if($('#isp_dir').prop('checked'))
			$('#isp_dir_scan').prop('checked',true);
		else
			$('#isp_dir_scan').prop('checked',false);
		if($('#zam_isp_dir_niokr').prop('checked'))
			$('#zam_isp_dir_niokr_scan').prop('checked',true);
		else
			$('#zam_isp_dir_niokr_scan').prop('checked',false);
		if($('#main_in').prop('checked'))
			$('#main_in_scan').prop('checked',true);
		else
			$('#main_in_scan').prop('checked',false);
		if($('#dir_sip').prop('checked'))
			$('#dir_sip_scan').prop('checked',true);
		else
			$('#dir_sip_scan').prop('checked',false);
		if($('#dir_peo').prop('checked'))
			$('#dir_peo_scan').prop('checked',true);
		else
			$('#dir_peo_scan').prop('checked',false);
		if($('#isp_dir_check').prop('checked'))
			$('#isp_dir_check_scan').prop('checked',true);
		else
			$('#isp_dir_check_scan').prop('checked',false);
		if($('#zam_isp_dir_niokr_check').prop('checked'))
			$('#zam_isp_dir_niokr_check_scan').prop('checked',true);
		else
			$('#zam_isp_dir_niokr_check_scan').prop('checked',false);
		if($('#main_in_check').prop('checked'))
			$('#main_in_check_scan').prop('checked',true);
		else
			$('#main_in_check_scan').prop('checked',false);
		if($('#dir_sip_check').prop('checked'))
			$('#dir_sip_check_scan').prop('checked',true);
		else
			$('#dir_sip_check_scan').prop('checked',false);
		if($('#dir_peo_check').prop('checked'))
			$('#dir_peo_check_scan').prop('checked',true);
		else
			$('#dir_peo_check_scan').prop('checked',false);
		$('#valIdCounterpartie_update_scan').val($('#valIdCounterpartie_update').val());
		$('#valNameCounterpartie_update_scan').val($('#valNameCounterpartie_update').val());
		$('#valTelephoneCounterpartie_update_scan').val($('#valTelephoneCounterpartie_update').val());
		$('#valCuratorCounterpartie_update_scan').val($('#valCuratorCounterpartie_update').val());
		$('#date_begin_update_scan').val($('#date_begin_update').val());
		$('#date_end_update_scan').val($('#date_end_update').val());
		$('#action_scan').val($('#action').val());
	});
	
	$('#btn_close_new_application').on('click', function(evt){
		$('#form_all_application').css('display', 'block');
		$('#form_new_application').css('display', 'none');
	});
	
	$('#open_resolution').on('click', function(evt){
		if($('#resolution_list').val().length > 0)
			window.open($('#resolution_list').val(), '_blank');
	});
	
	$('.open_resolution').on('click', function(evt){
		if($('#' + $(this).attr('resolution_block')).val().length > 0)
			window.open($('#' + $(this).attr('resolution_block')).val(), '_blank');
	});
	
	$('#download_resolution').on('click', function(evt){
		if($('#resolution_list').val().length > 0)
			window.open($('#resolution_list option:selected').attr('download_href'), '_blank');
	});
	
	$('#delete_resolution').on('click', function(evt){
		if($('#resolution_list').val().length > 0)
			location.href = $('#resolution_list option:selected').attr('delete_href');
	});
	
	$('.delete_resolution').on('click', function(evt){
		if($($(this).attr('for_select')).val().length > 0)
			location.href = $($(this).attr('for_select')+' option:selected').attr('delete_href');
	});
	
	$('#new_file_resolution').on('change', function(evt){
		$('#real_name_resolution').val($(this).val().replace('C:\\fakepath\\',''));
	});
	
	$('.new_file_resolution').on('change', function(evt){
		$($(this).attr('for')).val($(this).val().replace('C:\\fakepath\\',''));
	});
	
	//Десятый отдел
	$('#selectOldComponent').on('change', function(evt){
		$('#name_party').val($('#selectOldComponent option:selected').attr('name_party'));
		$('#date_party').val($('#selectOldComponent option:selected').attr('date_party'));
		$('#count_party').val($('#selectOldComponent option:selected').attr('count_party'));
	});
	
	//Настройки
	$('.dateCheck').on('click', function(evt)
	{
		if($(this).is(':checked'))
			$('.dateInput').css('display','block');
		else
			$('.dateInput').css('display','none');
	});
	
	$('.completeCheck').on('click', function(evt)
	{
		/*if($(this).is(':checked'))
			$('.lastCompleteInput').css('display','none');
		else
			$('.lastCompleteInput').css('display','block');*/
	});
	
	$('#prepayment_reestr').on('change', function(evt)
	{
		if($('#prepayment_reestr').prop('checked')) {
			$('#input_prepayment_contract').css('display', 'block');
			$('.input_prepayment_contract').css('display', 'block');
		}
		else {
			$('#input_prepayment_contract').css('display', 'none');
			$('.input_prepayment_contract').css('display', 'none');
		}
	});
	
	$('.implementationCheck').on('click', function(evt)
	{
		if($(this).is(':checked'))
			$('.implementationInput').css('display','none');
		else
			$('.implementationInput').css('display','block');
	});
	
	$('#selectCounerChancery').on('change', function(evt)
	{
		var k = $(this).val();
		$.each($('#selectCounerChancery option'), function(e){
			if($(this).val() == k){
				$('#curator').val($(this).attr('curator'));
				$('#telephone').val($(this).attr('telephone'));
			}
		});
	});
	
	$(function(){
		$.each($('.datepicker'),function(){
			strDate = $(this).val();
			$(this).datepicker();
			$(this).datepicker("option", "dateFormat", "dd.mm.yy");
			$(this).val(strDate);
		});
	});

	$(function(){
		if($('#selectCounerChancery').val())
			if($('#selectCounerChancery').val().length > 0)
			{
				var k = $('#selectCounerChancery').val();
				$.each($('#selectCounerChancery option'), function(e){
					if($(this).val() == k){
						$('#curator').val($(this).attr('curator'));
						$('#telephone').val($(this).attr('telephone'));
					}
				});
			}
	});
	
	$('.check-number').on('change', function(){
		$(this).val($(this).val().replace(/,/, '.'));
		$(this).val($(this).val().replace(/[^\d.\ \-]/g, ''));
	});
	
	$('.check-year').on('change', function(){
		if($(this).val().length != 4)
			alert('Неверно заполнено поле даты! Пример: 2019');
	});
	
	/*$('ul.nav > li').hover(function(){
		$(this).find('.dropdown-menu').stop(true,true).delay(200).fadeIn();
	},function(){
		$(this).find('.dropdown-menu').stop(true,true).delay(200).fadeOut();
	});*/
	
	//Руководство
	var nameCount = 1;
	
	var tableToExcel = (function() {
		if(navigator.userAgent.search('Firefox') >= 0){
			var uri = 'data:application/vnd.ms-excel;base64,', 
			template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridLines/></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table border="2px solid black">{table}</table></body></html>',
			templateNoneTable = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridLines/></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>',
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
			return function(table, name, fileName, noneTable) {
				if (!table.nodeType) 
					table = document.getElementById(table)
				var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
				if (noneTable == true)
					window.location.href = uri + base64(format(templateNoneTable, ctx))
				else
					window.location.href = uri + base64(format(template, ctx))
				//downloadURI(resuri, fileName);
			}
		}else{
			var uri = 'data:application/vnd.ms-excel;base64,', 
			template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridLines/></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table border="2px solid black">{table}</table></body></html>',
			templateNoneTable = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridLines/></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>',
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
			return function(table, name, fileName, noneTable) {
				if (!table.nodeType) 
					table = document.getElementById(table)
				var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
				if (noneTable == true)
					var resuri = uri + base64(format(templateNoneTable, ctx))
				else
					var resuri = uri + base64(format(template, ctx))
				downloadURI(resuri, fileName);
			}
		}
	})();
	
	$('#createExcel').on('click', function(e){
		tableToExcel('resultTable',$(this).attr('real_name_table'),$(this).attr('real_name_table')+'.xls');
	});
	
	$('#createExcelNoneTable').on('click', function(e){
		tableToExcel('resultTable',$(this).attr('real_name_table'),$(this).attr('real_name_table')+'.xls', true);
	});
	
	$('#createWord').on('click', function(e){
		var MSWord=null;
		 
		try
		{
			if(MSWord=new ActiveXObject("Word.Document"))
				MSWord.Visible=true;
			else
				alert("!Word.Application");
		}
		catch(Exception)
		{
			alert(Exception.name+": "+Exception.message);
		}
	});
	
	$('#copyInBuffer').on('click', function(e){
		if(document.selection){
			var range = document.body.createTextRange();
			range.moveToElementText(document.getElementById($(this).attr('copy_target')));
			range.select().createTextRange();
			document.execCommand('copy');
		} else if(window.getSelection){
			var range = document.createRange();
			range.selectNode(document.getElementById($(this).attr('copy_target')));
			window.getSelection().addRange(range);
			document.execCommand('copy');
		}
		if(navigator.userAgent.search('Firefox') >= 0)
			console.log('Огонь лиса!');
		else
			console.log('Энавер браузер!');
	});
	
	$('#btnAddColumn').on('click', function(e){
		$('#divForColumn').append('<div class="col-md-5"><div class="form-group"><label>Выберите поле</span></label><select class="form-control" name="selectColumn[' + nameCount + ']"><option></option><option value="contracts.number_contract">Номер договора</option><option value="contracts.id_counterpartie_contract">Контрагент</option><option value="view_contracts.name_view_contract">Вид договора</option><option value="contracts.name_work_contract">Наименование работ</option><option value="type_documents.name_type_document">Тип документа (реестр)</option><option value="contracts.comment_implementation_contract">Стадия выполнение (ПЭО)</option><option value="contracts.year_contract">Год</option></select></div></div>');
		nameCount++;
	});
});