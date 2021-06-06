$(document).ready(function(){
	$('.bookRow').click(function(){
		$k = $.parseJSON($(this).attr('book'));
		$('#updateId').val($k['id']);
		
		$('#btnOutgoing').attr('href', 'out.php?id=' + $k['id']);
		$('#btnAjaxDeleting').attr('id_book', $k['id']);
		
		$typeId = $k['typeID'];
		$('#updateType option').each(function(){
			if($(this).val() == $typeId)
				$(this).prop('selected',true);
			else
				$(this).prop('selected',false);
		});
		
		$('#updateInventoryNumber').val($k['inventory_number']);
		$('#updateIndex').val($k['index_book']);
		$('#updateTitle').val($k['title_book']);
		$('#updateDateBeginEnd').val($k['date_begin_end']);
		$('#updateCount').val($k['count']);
		$('#updateComment').val($k['comment']);
		$('#updatePlaceS').val($k['place_s']);
		$('#updatePlaceP').val($k['place_p']);
		$('#updateDepartment').val($k['department']);
		$('#updateDateIncomingOutgoing').val($k['date_incoming_outgoing']);
		$('#updateCounterpartie').val($k['counterpartie']);
		$('#updateDocumentIncoming').val($k['document_incoming']);
		$('#updateFond').val($k['fond']);		
		
		$('#update_new_book').modal("show");
	});
	
	$('#btnOutgoing').click(function(){
		location.href=$(this).attr('href');
	});
	
	$(function(){
		$.each($('.datepicker'),function(){
			strDate = $(this).val();
			$(this).datepicker();
			$(this).datepicker("option", "dateFormat", "dd.mm.yy");
			$(this).val(strDate);
		});
	});
});