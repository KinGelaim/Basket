@if(isset($form_action))
	<form action='{{route($form_action)}}'>
@else
	<form>
@endif
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="selSearch">Выберите поле для поиска</label>
				<select class="form-control" id="selSearch" name='search_name'>
					<option></option>
					@if(isset($search_arr_value))
						@foreach($search_arr_value as $key=>$value)
							@if(isset($search_name))
								<option value='{{$key}}' <?php if($search_name == $key) echo 'selected'; ?>>{{$value}}</option>
							@else
								<option value='{{$key}}'>{{$value}}</option>
							@endif
						@endforeach
					@endif
				</select>
			</div>
		</div>
		<div class="col-md-4">
			<label>Поиск</label>
			<input class='form-control' type='text' value='@if(isset($search_value)){{$search_value}}@endif' name='search_value'/>
		</div>
		<div class="col-md-2">
			<button class="btn btn-primary" type="submit" href="" style="margin-top: 26px;">Поиск</button>
		</div>
	</div>
</form>