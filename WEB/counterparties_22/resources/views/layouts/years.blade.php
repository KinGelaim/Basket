<div class="col-md-3">
	<div class="form-group">
		<label for="sel1">Выберите год</span></label>
		<select class="form-control" id="sel1">
			<option></option>
			@if($years)
				@foreach($years as $year)
					<option>{{ $year->year_contract }}</option>
				@endforeach
			@endif
		</select>
	</div>
</div>