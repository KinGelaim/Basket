<div class="form-group">
	<label for="filter_date">Выберите дату</label>
	@if(isset($_GET['filter_date']))
		<input id='filter_date' class='datepicker form-control' name='filter_date' value="{{$_GET['filter_date']}}" onchange='form.submit();'/>
	@else
		<input id='filter_date' class='datepicker form-control' name='filter_date' value='{{old("filter_date")}}' onchange='form.submit();'/>
	@endif
</div>