@extends('layouts.header')

@section('title')
	Новый контрагент
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Отдел управления договорами' OR Auth::User()->hasRole()->role == 'Десятый отдел' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Новый контрагент</h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<form class="form-horizontal" method="POST" action="{{ route('counterpartie.save') }}">
								{{ csrf_field() }}
								<div class="form-group{{ $errors->has('') ? ' has-error' : '' }}">
									<div class="col-md-8">
										
									</div>
									<div class="col-md-4">
										<label for="dishonesty" class="control-label">Недобросовестность</label>
										<input id='dishonesty' class='form-check-input' name='dishonesty' type="checkbox" />
									</div>
								</div>
								<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
									<label for="name" class="col-md-4 control-label">Контрагент</label>
									<div class="col-md-6">
										<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>
										@if ($errors->has('name'))
											<span class="help-block">
												<strong>{{ $errors->first('name') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('name_full') ? ' has-error' : '' }}">
									<label for="name_full" class="col-md-4 control-label">Полное наименование</label>
									<div class="col-md-6">
										<input id="name_full" type="text" class="form-control" name="name_full" value="{{ old('name_full') }}" required>
										@if ($errors->has('name(full)'))
											<span class="help-block">
												<strong>{{ $errors->first('name_full') }}</strong>
											</span>
										@endif
									</div>
								</div>
								@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел')
									<div class="form-group{{ $errors->has('curator') ? ' has-error' : '' }}">
										<label for="curator" class="col-md-4 control-label">Куратор</label>
										<div class="col-md-6">
											<select id="curator" type="text" class="form-control" name="curator">
												<option></option>
												@if(old('curator'))
													@foreach($curators as $curator)
														@if(old('curator') == $curator->id)
															<option value='{{$curator->id}}'>{{$curator->FIO}}</option>
														@else
															<option value='{{$curator->id}}' selected>{{$curator->FIO}}</option>
														@endif
													@endforeach
												@else
													@foreach($curators as $curator)
														<option value='{{$curator->id}}'>{{$curator->FIO}}</option>
													@endforeach
												@endif
											</select>
											@if ($errors->has('curator'))
												<span class="help-block">
													<strong>{{ $errors->first('curator') }}</strong>
												</span>
											@endif
										</div>
									</div>
									<div class="form-group{{ $errors->has('is_sip_counterpartie') ? ' has-error' : '' }}">
										<div class="col-md-4">
											
										</div>
										<div class="col-md-6">
											<input id='is_sip_counterpartie' class='form-check-input' name='is_sip_counterpartie' type="checkbox" />
											<label for='is_sip_counterpartie'>Контрагент СИП</label>
											@if ($errors->has('is_sip_counterpartie'))
												<span class="help-block">
													<strong>{{ $errors->first('is_sip_counterpartie') }}</strong>
												</span>
											@endif
										</div>
									</div>
								@endif
								<div class="form-group{{ $errors->has('lider') ? ' has-error' : '' }}">
									<label for="lider" class="col-md-4 control-label">Руководитель</label>
									<div class="col-md-6">
										<input id="lider" type="text" class="form-control" name="lider" value="{{ old('lider') }}">
										@if ($errors->has('name(full)'))
											<span class="help-block">
												<strong>{{ $errors->first('lider') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('legal_address') ? ' has-error' : '' }}">
									<label for="legal_address" class="col-md-4 control-label">Адр. юридический</label>
									<div class="col-md-6">
										<input id="legal_address" type="text" class="form-control" name="legal_address" value="{{ old('legal_address') }}">
										@if ($errors->has('name(full)'))
											<span class="help-block">
												<strong>{{ $errors->first('legal_address') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('mailing_address') ? ' has-error' : '' }}">
									<label for="mailing_address" class="col-md-4 control-label">Адр. почтовый</label>
									<div class="col-md-6">
										<input id="mailing_address" type="text" class="form-control" name="mailing_address" value="{{ old('mailing_address') }}">
										@if ($errors->has('name(full)'))
											<span class="help-block">
												<strong>{{ $errors->first('mailing_address') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('actual_address') ? ' has-error' : '' }}">
									<label for="actual_address" class="col-md-4 control-label">Адр. фактический</label>
									<div class="col-md-6">
										<input id="actual_address" type="text" class="form-control" name="actual_address" value="{{ old('actual_address') }}">
										@if ($errors->has('name(full)'))
											<span class="help-block">
												<strong>{{ $errors->first('actual_address') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('telephone') ? ' has-error' : '' }}">
									<label for="telephone" class="col-md-4 control-label">Телефон / факс</label>
									<div class="col-md-6">
										<input id="telephone" type="text" class="form-control" name="telephone" value="{{ old('telephone') }}">
										@if ($errors->has('name(full)'))
											<span class="help-block">
												<strong>{{ $errors->first('telephone') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
									<label for="email" class="col-md-4 control-label">E-mail</label>
									<div class="col-md-6">
										<input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}">
										@if ($errors->has('name(full)'))
											<span class="help-block">
												<strong>{{ $errors->first('email') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('inn') ? ' has-error' : '' }}">
									<label for="inn" class="col-md-4 control-label">ИНН</label>
									<div class="col-md-2">
										<input id="inn" type="text" class="form-control" name="inn" value="{{ old('inn') }}">
										@if ($errors->has('name(full)'))
											<span class="help-block">
												<strong>{{ $errors->first('inn') }}</strong>
											</span>
										@endif
									</div>
									<label for="ogrn" class="col-md-1 control-label">ОГРН</label>
									<div class="col-md-3">
										<input id="ogrn" type="text" class="form-control" name="ogrn" value="{{ old('ogrn') }}">
										@if ($errors->has('name(full)'))
											<span class="help-block">
												<strong>{{ $errors->first('ogrn') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('kpp') ? ' has-error' : '' }}">
									<label for="kpp" class="col-md-4 control-label">КПП</label>
									<div class="col-md-2">
										<input id="kpp" type="text" class="form-control" name="kpp" value="{{ old('kpp') }}">
										@if ($errors->has('name(full)'))
											<span class="help-block">
												<strong>{{ $errors->first('kpp') }}</strong>
											</span>
										@endif
									</div>
									<label for="okpo" class="col-md-1 control-label">ОКПО</label>
									<div class="col-md-3">
										<input id="okpo" type="text" class="form-control" name="okpo" value="{{ old('okpo') }}">
										@if ($errors->has('name(full)'))
											<span class="help-block">
												<strong>{{ $errors->first('okpo') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('okved') ? ' has-error' : '' }}">
									<label for="okved" class="col-md-4 control-label">ОКВЭД</label>
									<div class="col-md-6">
										<input id="okved" type="text" class="form-control" name="okved" value="{{ old('okved') }}">
										@if ($errors->has('name(full)'))
											<span class="help-block">
												<strong>{{ $errors->first('okved') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('number_file') ? ' has-error' : '' }}">
									<label for="number_file" class="col-md-4 control-label">Дело №</label>
									<div class="col-md-6">
										<input id="number_file" type="text" class="form-control" name="number_file" value="{{ old('number_file') }}">
										@if ($errors->has('name(full)'))
											<span class="help-block">
												<strong>{{ $errors->first('number_file') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('') ? ' has-error' : '' }}">
									<div class="col-md-3">
									</div>
									<div class="col-md-2">
										<label for="statement_egryl" class="control-label">Выписка из ЕГРЮЛ</label>
										<input id='statement_egryl' class='form-check-input' name='statement_egryl' type="checkbox" />
									</div>
									<div class="col-md-2">
										<label for="order_counterpartie" class="control-label">Приказ</label>
										<input id='order_counterpartie' class='form-check-input' name='order_counterpartie' type="checkbox" />
									</div>
									<div class="col-md-2">
										<label for="protocol_meeting" class="control-label">Протокол собрания</label>
										<input id='protocol_meeting' class='form-check-input' name='protocol_meeting' type="checkbox" />
									</div>
									<div class="col-md-2">
										<label for="statement_egrip" class="control-label">Выписка из ЕГРИП</label>
										<input id='statement_egrip' class='form-check-input' name='statement_egrip' type="checkbox" />
									</div>
								</div>
								<div class="form-group{{ $errors->has('') ? ' has-error' : '' }}">
									<div class="col-md-3">
									</div>
									<div class="col-md-2">
										<label for="statement_charter" class="control-label">Выписка из устава</label>
										<input id='statement_charter' class='form-check-input' name='statement_charter' type="checkbox" />
									</div>
									<div class="col-md-2">
										<label for="map" class="control-label">Карта</label>
										<input id='map' class='form-check-input' name='map' type="checkbox" />
									</div>
									<div class="col-md-2">
										<label for="statement_rosstata" class="control-label">Выписка из Росстата</label>
										<input id='statement_rosstata' class='form-check-input' name='statement_rosstata' type="checkbox" />
									</div>
									<div class="col-md-2">
										<label for="copy_passport" class="control-label">Копия паспорта</label>
										<input id='copy_passport' class='form-check-input' name='copy_passport' type="checkbox" />
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
									</div>
									<div class="col-md-2">
										<label for="proxys" class="control-label">Доверенность</label>
										<input id='proxys' class='form-check-input' name='proxys' type="checkbox" />
									</div>
									<div class="col-md-2">
										<label for="licences" class="control-label">Лицензии</label>
										<input id='licences' class='form-check-input' name='licences' type="checkbox" />
									</div>
									<div class="col-md-2">
										<label for="certificates" class="control-label">Сертификаты</label>
										<input id='certificates' class='form-check-input' name='certificates' type="checkbox" />
									</div>
									<div class="col-md-2">
										<label for="other_documents" class="control-label">Иные документы</label>
										<input id='other_documents' class='form-check-input' name='other_documents' type="checkbox" />
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											Добавить контрагента
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			@else
				<div class="alert alert-danger">
					Недостаточно прав для просмотра данной страницы!
				</div>
			@endif
		@else
			<div class="alert alert-danger">
				Необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
