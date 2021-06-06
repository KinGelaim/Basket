@extends('layouts.header')

@section('title')
	Редактирование контрагента отдела №22
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		<div class="content">
			<div class="row">
				<div class="col-md-12">
					<h3><a href='{{route("counterpartie.main")}}'>Контрагенты для отдела №22</a></h3>
				</div>
			</div>
			<div class="row">
				<form class="form-horizontal" method="POST" action="{{ route('counterpartie.update', $counterpartie->id) }}">
					<div class="col-md-7">
						{{ csrf_field() }}
						<div class="form-group{{ $errors->has('') ? ' has-error' : '' }}">
							<div class="col-md-7">
								
							</div>
							<div class="col-md-5">
								<label for="dishonesty" class="control-label">Недобросовестность</label>
								@if($counterpartie->dishonesty == 1 || old('dishonesty'))
									<input id='dishonesty' class='form-check-input' name='dishonesty' type="checkbox" checked  />
								@else
									<input id='dishonesty' class='form-check-input' name='dishonesty' type="checkbox"  />
								@endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
							<label for="code" class="col-md-3 control-label">Код контрагента</label>
							<div class="col-md-9">
								<input id="code" type="text" class="form-control" name="code" value="{{ old('code') ? old('code') : $counterpartie->code }}">
								@if ($errors->has('code'))
									<span class="help-block">
										<strong>{{ $errors->first('code') }}</strong>
									</span>
								@endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
							<label for="name" class="col-md-3 control-label">Контрагент</label>
							<div class="col-md-9">
								<input id="name" type="text" class="form-control" name="name" value="{{ old('name') ? old('name') : $counterpartie->name }}"  required>
								@if ($errors->has('name'))
									<span class="help-block">
										<strong>{{ $errors->first('name') }}</strong>
									</span>
								@endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('name_full') ? ' has-error' : '' }}">
							<label for="name_full" class="col-md-3 control-label">Полное наименование</label>
							<div class="col-md-9">
								<input id="name_full" type="text" class="form-control" name="name_full" value="{{ old('name_full') ? old('name_full') : $counterpartie->name_full }}"  required>
								@if ($errors->has('name(full)'))
									<span class="help-block">
										<strong>{{ $errors->first('name_full') }}</strong>
									</span>
								@endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('lider') ? ' has-error' : '' }}">
							<label for="lider" class="col-md-3 control-label">Руководитель</label>
							<div class="col-md-9">
								<input id="lider" type="text" class="form-control" name="lider" value="{{ old('lider') ? old('lider') : $counterpartie->lider }}" >
								@if ($errors->has('name(full)'))
									<span class="help-block">
										<strong>{{ $errors->first('lider') }}</strong>
									</span>
								@endif
							</div>
							<div class="col-md-2">
								<!--<button type="button" class="btn btn-primary btn-href" href="{{route('counterpartie.edit', $counterpartie->id)}}">Старый вид</button>-->
							</div>
						</div>
						<div class="form-group{{ $errors->has('legal_address') ? ' has-error' : '' }}">
							<label for="legal_address" class="col-md-3 control-label">Адр. юридический</label>
							<div class="col-md-9">
								<input id="legal_address" type="text" class="form-control" name="legal_address" value="{{ old('legal_address') ? old('legal_address') : $counterpartie->legal_address }}" >
								@if ($errors->has('name(full)'))
									<span class="help-block">
										<strong>{{ $errors->first('legal_address') }}</strong>
									</span>
								@endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('mailing_address') ? ' has-error' : '' }}">
							<label for="mailing_address" class="col-md-3 control-label">Адр. почтовый</label>
							<div class="col-md-9">
								<input id="mailing_address" type="text" class="form-control" name="mailing_address" value="{{ old('mailing_address') ? old('mailing_address') : $counterpartie->mailing_address }}" >
								@if ($errors->has('name(full)'))
									<span class="help-block">
										<strong>{{ $errors->first('mailing_address') }}</strong>
									</span>
								@endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('actual_address') ? ' has-error' : '' }}">
							<label for="actual_address" class="col-md-3 control-label">Адр. фактический</label>
							<div class="col-md-9">
								<input id="actual_address" type="text" class="form-control" name="actual_address" value="{{ old('actual_address') ? old('actual_address') : $counterpartie->actual_address }}" >
								@if ($errors->has('name(full)'))
									<span class="help-block">
										<strong>{{ $errors->first('actual_address') }}</strong>
									</span>
								@endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('telephone') ? ' has-error' : '' }}">
							<label for="telephone" class="col-md-3 control-label">Телефон / факс</label>
							<div class="col-md-9">
								<input id="telephone" type="text" class="form-control" name="telephone" value="{{ old('telephone') ? old('telephone') : $counterpartie->telephone }}" >
								@if ($errors->has('name(full)'))
									<span class="help-block">
										<strong>{{ $errors->first('telephone') }}</strong>
									</span>
								@endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
							<label for="email" class="col-md-3 control-label">E-mail</label>
							<div class="col-md-9">
								<input id="email" type="text" class="form-control" name="email" value="{{ old('email') ? old('email') : $counterpartie->email }}" >
								@if ($errors->has('name(full)'))
									<span class="help-block">
										<strong>{{ $errors->first('email') }}</strong>
									</span>
								@endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('inn') ? ' has-error' : '' }}">
							<label for="inn" class="col-md-3 control-label">ИНН</label>
							<div class="col-md-4">
								<input id="inn" type="text" class="form-control" name="inn" value="{{ old('inn') ? old('inn') : $counterpartie->inn }}" >
								@if ($errors->has('name(full)'))
									<span class="help-block">
										<strong>{{ $errors->first('inn') }}</strong>
									</span>
								@endif
							</div>
							<label for="ogrn" class="col-md-1 control-label">ОГРН</label>
							<div class="col-md-4">
								<input id="ogrn" type="text" class="form-control" name="ogrn" value="{{ old('ogrn') ? old('ogrn') : $counterpartie->ogrn }}" >
								@if ($errors->has('name(full)'))
									<span class="help-block">
										<strong>{{ $errors->first('ogrn') }}</strong>
									</span>
								@endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('kpp') ? ' has-error' : '' }}">
							<label for="kpp" class="col-md-3 control-label">КПП</label>
							<div class="col-md-4">
								<input id="kpp" type="text" class="form-control" name="kpp" value="{{ old('kpp') ? old('kpp') : $counterpartie->kpp }}" >
								@if ($errors->has('name(full)'))
									<span class="help-block">
										<strong>{{ $errors->first('kpp') }}</strong>
									</span>
								@endif
							</div>
							<label for="okpo" class="col-md-1 control-label">ОКПО</label>
							<div class="col-md-4">
								<input id="okpo" type="text" class="form-control" name="okpo" value="{{ old('okpo') ? old('okpo') : $counterpartie->okpo }}" >
								@if ($errors->has('name(full)'))
									<span class="help-block">
										<strong>{{ $errors->first('okpo') }}</strong>
									</span>
								@endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('okved') ? ' has-error' : '' }}">
							<label for="okved" class="col-md-3 control-label">ОКВЭД</label>
							<div class="col-md-9">
								<input id="okved" type="text" class="form-control" name="okved" value="{{ old('okved') ? old('okved') : $counterpartie->okved }}" >
								@if ($errors->has('name(full)'))
									<span class="help-block">
										<strong>{{ $errors->first('okved') }}</strong>
									</span>
								@endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('number_file') ? ' has-error' : '' }}">
							<label for="number_file" class="col-md-3 control-label">Дело №</label>
							<div class="col-md-9">
								<input id="number_file" type="text" class="form-control" name="number_file" value="{{ old('number_file') ? old('number_file') : $counterpartie->number_file }}" >
								@if ($errors->has('name(full)'))
									<span class="help-block">
										<strong>{{ $errors->first('number_file') }}</strong>
									</span>
								@endif
							</div>
						</div>
					</div>
					<div class='col-md-2'>
						<div class="form-group">
							<div class="col-md-12">
									<button type="submit" class="btn btn-primary">
										Сохранить контрагента
									</button>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#employees">Сотрудники</button>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label for="statement_egryl" class="control-label">Выписка из ЕГРЮЛ</label>
								@if($counterpartie->statement_egryl == 1 || old('statement_egryl'))
									<input id='statement_egryl' class='form-check-input' name='statement_egryl' type="checkbox" checked />
								@else
									<input id='statement_egryl' class='form-check-input' name='statement_egryl' type="checkbox" />
								@endif
							</div>
							<div class="col-md-12">
								<label for="order_counterpartie" class="control-label">Приказ</label>
								@if($counterpartie->order_counterpartie == 1 || old('order_counterpartie'))
									<input id='order_counterpartie' class='form-check-input' name='order_counterpartie' type="checkbox" checked />
								@else
									<input id='order_counterpartie' class='form-check-input' name='order_counterpartie' type="checkbox" />
								@endif
							</div>
							<div class="col-md-12">
								<label for="protocol_meeting" class="control-label">Протокол собрания</label>
								@if($counterpartie->protocol_meeting == 1 || old('protocol_meeting'))
									<input id='protocol_meeting' class='form-check-input' name='protocol_meeting' type="checkbox" checked />
								@else
									<input id='protocol_meeting' class='form-check-input' name='protocol_meeting' type="checkbox" />
								@endif
							</div>
							<div class="col-md-12">
								<label for="statement_egrip" class="control-label">Выписка из ЕГРИП</label>
								@if($counterpartie->statement_egrip == 1 || old('statement_egrip'))
									<input id='statement_egrip' class='form-check-input' name='statement_egrip' type="checkbox" checked />
								@else
									<input id='statement_egrip' class='form-check-input' name='statement_egrip' type="checkbox" />
								@endif
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label for="statement_charter" class="control-label">Выписка из устава</label>
								@if($counterpartie->statement_charter == 1 || old('statement_charter'))
									<input id='statement_charter' class='form-check-input' name='statement_charter' type="checkbox" checked />
								@else
									<input id='statement_charter' class='form-check-input' name='statement_charter' type="checkbox" />
								@endif
							</div>
							<div class="col-md-12">
								<label for="map" class="control-label">Карта</label>
								@if(old('map'))
									<input id='map' class='form-check-input' name='map' type="checkbox" checked />
								@else
									@if($counterpartie->map == 1)
										<input id='map' class='form-check-input' name='map' type="checkbox" checked />
									@else
										<input id='map' class='form-check-input' name='map' type="checkbox" />
									@endif
								@endif
							</div>
							<div class="col-md-12">
								<label for="statement_rosstata" class="control-label">Выписка из Росстата</label>
								@if(old('statement_rosstata'))
									<input id='statement_rosstata' class='form-check-input' name='statement_rosstata' type="checkbox" checked />
								@else
									@if($counterpartie->statement_rosstata == 1)
										<input id='statement_rosstata' class='form-check-input' name='statement_rosstata' type="checkbox" checked />
									@else
										<input id='statement_rosstata' class='form-check-input' name='statement_rosstata' type="checkbox" />
									@endif
								@endif
							</div>
							<div class="col-md-12">
								<label for="copy_passport" class="control-label">Копия паспорта</label>
								@if(old('copy_passport'))
									<input id='copy_passport' class='form-check-input' name='copy_passport' type="checkbox" checked />
								@else
									@if($counterpartie->copy_passport == 1)
										<input id='copy_passport' class='form-check-input' name='copy_passport' type="checkbox" checked />
									@else
										<input id='copy_passport' class='form-check-input' name='copy_passport' type="checkbox" />
									@endif
								@endif
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label for="proxys" class="control-label">Доверенность</label>
								@if($counterpartie->proxys == 1 || old('proxys'))
									<input id='proxys' class='form-check-input' name='proxys' type="checkbox" checked />
								@else
									<input id='proxys' class='form-check-input' name='proxys' type="checkbox" />
								@endif
							</div>
							<div class="col-md-12">
								<label for="licences" class="control-label">Лицензии</label>
								@if(old('licences'))
									<input id='licences' class='form-check-input' name='licences' type="checkbox" checked />
								@else
									@if($counterpartie->licences == 1)
										<input id='licences' class='form-check-input' name='licences' type="checkbox" checked />
									@else
										<input id='licences' class='form-check-input' name='licences' type="checkbox" />
									@endif
								@endif
							</div>
							<div class="col-md-12">
								<label for="certificates" class="control-label">Сертификаты</label>
								@if(old('certificates'))
									<input id='certificates' class='form-check-input' name='certificates' type="checkbox" checked />
								@else
									@if($counterpartie->certificates == 1)
										<input id='certificates' class='form-check-input' name='certificates' type="checkbox" checked />
									@else
										<input id='certificates' class='form-check-input' name='certificates' type="checkbox" />
									@endif
								@endif
							</div>
							<div class="col-md-12">
								<label for="other_documents" class="control-label">Иные документы</label>
								@if(old('other_documents'))
									<input id='other_documents' class='form-check-input' name='other_documents' type="checkbox" checked />
								@else
									@if($counterpartie->other_documents == 1)
										<input id='other_documents' class='form-check-input' name='other_documents' type="checkbox" checked />
									@else
										<input id='other_documents' class='form-check-input' name='other_documents' type="checkbox" />
									@endif
								@endif
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<!-- Модальное окно сотрудников -->
		<div class="modal fade" id="employees" tabindex="-1" role="dialog" aria-labelledby="employeesModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div id='first_step_employee'>
						<div class="modal-header">
							<h5 class="modal-title" id="employeesModalLabel">Сотрудники</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div id='allEmployees' class='row'>
								<div class="col-md-12">
									<button type='button' class='btn btn-primary steps' first_step='#allEmployees' second_step='#orderEmployees'>Изменить порядок</button>
								</div>
								<div class='col-md-12'>
									
									<table class="table" style='margin: 0 auto;'>
										<thead>
											<tr>
												<th>ФИО</th>
												<th>Должность</th>
												<th>Телефон</th>
												<th>Почта</th>
												<th>Удалить</th>
											</tr>
										</thead>
										<tbody>
											@foreach($employees as $employee)
												<tr class='rowsContract cursorPointer steps' first_step='#first_step_employee' second_step='#second_step_employee' onclick="$('#second_step_employee form').attr('action', '{{route('counterpartie.update_employee',$employee->id)}}');
																																											$('#secondEmployeesModalLabel').text('Редактировать сотрудника');
																																											$('#second_step_employee input[name=FIO]').val('{{$employee->FIO}}');
																																											$('#second_step_employee input[name=post]').val('{{$employee->post}}');
																																											$('#second_step_employee input[name=telephone]').val('{{$employee->telephone}}');
																																											$('#second_step_employee input[name=email]').val('{{$employee->email}}');
																																											$('#second_step_employee button[type=submit]').text('Обновить сотрудника');">
													<td>{{$employee->FIO}}</td>
													<td>{{$employee->post}}</td>
													<td>{{$employee->telephone}}</td>
													<td>{{$employee->email}}</td>
													<td class='table_coll_btn'><button type='button' class='btn btn-danger btn-href' type='button' href="{{route('counterpartie.delete_employee', $employee->id)}}">Удалить</button></td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
								<div class="col-md-12">
										<button type='button' class='btn btn-primary steps' first_step='#first_step_employee' second_step='#second_step_employee' onclick="$('#second_step_employee form').attr('action', '{{route('counterpartie.store_employee',$counterpartie->id)}}');
																																												$('#secondEmployeesModalLabel').text('Новый сотрудник');
																																												$('#second_step_employee input[name=FIO]').val('');
																																												$('#second_step_employee input[name=post]').val('');
																																												$('#second_step_employee input[name=telephone]').val('');
																																												$('#second_step_employee input[name=email]').val('');
																																												$('#second_step_employee button[type=submit]').text('Сохранить сотрудника');">Добавить сотрудника</button>
								</div>
							</div>
							<div id='orderEmployees' class='row' style='display: none;'>
								<form method='POST' action='{{route("counterpartie.swap_employee")}}' >
									{{csrf_field()}}
									<div class='col-md-12'>
										<table class="table" style='margin: 0 auto;'>
											<thead>
												<tr>
													<th>Позиция</th>
													<th style='display:none;'>id</th>
													<th>ФИО</th>
													<th>Должность</th>
													<th>Телефон</th>
													<th>Почта</th>
												</tr>
											</thead>
											<tbody>
												<?php $k = 0; ?>
												@foreach($employees as $employee)
													<tr class='rowsContract'>
														<td><input class='form-control' type='numeric' value='{{++$k}}' name='position[{{$k}}]'/></td>
														<td style='display:none;'><input class='form-control' type='numeric' value='{{$employee->id}}' name='id[{{$k}}]' readonly /></td>
														<td>{{$employee->FIO}}</td>
														<td>{{$employee->post}}</td>
														<td>{{$employee->telephone}}</td>
														<td>{{$employee->email}}</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
									<div class='col-md-2'>
										<button type='submit' class='btn btn-primary'>Сохранить</button>
									</div>
									<div class='col-md-2'>
										<button type='button' class='btn btn-secondary steps' first_step='#orderEmployees' second_step='#allEmployees'>Отмена</button>
									</div>
								</form>
							</div>
						</div>
						<div class="modal-footer">
							<button id='btn_close_new_history' type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
						</div>
					</div>
					<div id='second_step_employee' style='display:none;'>
						<form method='POST' action="{{ route('counterpartie.store_employee',$counterpartie->id)}}">
							{{csrf_field()}}
							<div class="modal-header">
								<h5 class="modal-title" id="secondEmployeesModalLabel">Новый сотрудник</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class='row'>
									<div class='col-md-8 col-md-offset-2'>
										<div class='form-group'>
											<label for='FIO' class='col-form-label'>ФИО сотрудника</label>
											<input id='FIO' class='form-control {{$errors->has("FIO") ? print("inputError ") : print("")}}' type='text' name='FIO' required />
											@if($errors->has('FIO'))
												<label class='msgError'>{{$errors->first('FIO')}}</label>
											@endif
										</div>
										<div class='form-group'>
											<label for='post' class='col-form-label'>Должность</label>
											<input id='post' class='form-control {{$errors->has("post") ? print("inputError ") : print("")}}' type='text' name='post'/>
											@if($errors->has('post'))
												<label class='msgError'>{{$errors->first('post')}}</label>
											@endif
										</div>
										<div class='form-group'>
											<label for='telephone' class='col-form-label'>Телефон</label>
											<input id='telephone' class='form-control {{$errors->has("telephone") ? print("inputError ") : print("")}}' type='text' name='telephone'/>
											@if($errors->has('telephone'))
												<label class='msgError'>{{$errors->first('telephone')}}</label>
											@endif
										</div>
										<div class='form-group'>
											<label for='email' class='col-form-label'>Почта</label>
											<input id='email' class='form-control {{$errors->has("email") ? print("inputError ") : print("")}}' type='text' name='email'/>
											@if($errors->has('email'))
												<label class='msgError'>{{$errors->first('email')}}</label>
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
									<button type='submit' class='btn btn-primary'>Сохранить сотрудника</button>
								<button type="button" class="btn btn-secondary steps" first_step='#second_step_employee' second_step='#first_step_employee'>Закрыть</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
