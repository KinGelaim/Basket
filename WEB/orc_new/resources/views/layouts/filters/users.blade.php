@if(isset($users))
	<div class="form-group">
		<label for="filter_user">Выберите пользователя</label>
		<select class="form-control" id='filter_user' name='filter_user' onchange='form.submit();'>
			<option></option>
				@foreach($users as $user)
					@if(isset($_GET['filter_user']))
						@if($_GET['filter_user'] == $user->id)
							<option value='{{$user->id}}' selected>{{$user->surname . ' ' . $user->name . ' ' . $user->patronymic}}</option>
						@else
							<option value='{{$user->id}}'>{{$user->surname . ' ' . $user->name . ' ' . $user->patronymic}}</option>
						@endif
					@else
						<option value='{{$user->id}}'>{{$user->surname . ' ' . $user->name . ' ' . $user->patronymic}}</option>
					@endif
				@endforeach
		</select>
	</div>
@endif