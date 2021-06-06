@if(isset($roles))
	<div class="form-group">
		<label for="filter_role">Выберите роль</label>
		<select class="form-control" id='filter_role' name='filter_role' onchange='form.submit();'>
			<option></option>
				@foreach($roles as $role)
					@if(isset($_GET['filter_role']))
						@if($_GET['filter_role'] == $role->id)
							<option value='{{$role->id}}' selected>{{$role->role}}</option>
						@else
							<option value='{{$role->id}}'>{{$role->role}}</option>
						@endif
					@else
						<option value='{{$role->id}}'>{{$role->role}}</option>
					@endif
				@endforeach
		</select>
	</div>
@endif