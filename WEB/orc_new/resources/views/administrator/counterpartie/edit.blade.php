@extends('layouts.header')

@section('title')
	Редактирование контрагента
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Отдел управления договорами' OR Auth::User()->hasRole()->role == 'Десятый отдел' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Контрагент</h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<form class="form-horizontal" method="POST" action="{{ route('counterpartie.update', $counterpartie->id) }}">
								{{ csrf_field() }}
								<div class="form-group{{ $errors->has('') ? ' has-error' : '' }}">
									<div class="col-md-8">
										
									</div>
									<div class="col-md-4">
										<label for="dishonesty" class="control-label">Недобросовестность</label>
										@if($counterpartie->dishonesty == 1 || old('dishonesty'))
											<input id='dishonesty' class='form-check-input' name='dishonesty' type="checkbox" checked />
										@else
											<input id='dishonesty' class='form-check-input' name='dishonesty' type="checkbox" />
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
									<label for="name" class="col-md-4 control-label">Контрагент</label>
									<div class="col-md-6">
										<input id="name" type="text" class="form-control" name="name" value="{{ old('name') ? old('name') : $counterpartie->name }}" required>
										@if ($errors->has('name'))
											<span class="help-block">
												<strong>{{ $errors->first('name') }}</strong>
											</span>
										@endif
									</div>
									<div class="col-md-2">
										<button type="submit" class="btn btn-primary">
											Сохранить контрагента
										</button>
									</div>
								</div>
								<div class="form-group{{ $errors->has('name_full') ? ' has-error' : '' }}">
									<label for="name_full" class="col-md-4 control-label">Полное наименование</label>
									<div class="col-md-6">
										<input id="name_full" type="text" class="form-control" name="name_full" value="{{ old('name_full') ? old('name_full') : $counterpartie->name_full }}" required>
										@if ($errors->has('name(full)'))
											<span class="help-block">
												<strong>{{ $errors->first('name_full') }}</strong>
											</span>
										@endif
									</div>
									<div class="col-md-2">
										<button type="button" class="btn btn-primary btn-href" href="{{route('counterpartie.show_reestr', $counterpartie->id)}}">Договоры</button>
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
															<option value='{{$curator->id}}' selected>{{$curator->FIO}}</option>
														@else
															<option value='{{$curator->id}}'>{{$curator->FIO}}</option>
														@endif
													@endforeach
												@else
													@foreach($curators as $curator)
														@if($counterpartie->curator == $curator->id)
															<option value='{{$curator->id}}' selected>{{$curator->FIO}}</option>
														@else
															<option value='{{$curator->id}}'>{{$curator->FIO}}</option>
														@endif
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
											@if($counterpartie->is_sip_counterpartie == 1 || old('is_sip_counterpartie'))
												<input id='is_sip_counterpartie' class='form-check-input' name='is_sip_counterpartie' type="checkbox" checked />
											@else
												<input id='is_sip_counterpartie' class='form-check-input' name='is_sip_counterpartie' type="checkbox" />
											@endif
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
										<input id="lider" type="text" class="form-control" name="lider" value="{{ old('lider') ? old('lider') : $counterpartie->lider }}">
										@if ($errors->has('name(full)'))
											<span class="help-block">
												<strong>{{ $errors->first('lider') }}</strong>
											</span>
										@endif
									</div>
									<div class="col-md-2">
										<button type="button" class="btn btn-primary btn-href" href="{{route('counterpartie.new_edit', $counterpartie->id)}}">Новый вид</button>
									</div>
								</div>
								<div class="form-group{{ $errors->has('legal_address') ? ' has-error' : '' }}">
									<label for="legal_address" class="col-md-4 control-label">Адр. юридический</label>
									<div class="col-md-6">
										<input id="legal_address" type="text" class="form-control" name="legal_address" value="{{ old('legal_address') ? old('legal_address') : $counterpartie->legal_address }}">
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
										<input id="mailing_address" type="text" class="form-control" name="mailing_address" value="{{ old('mailing_address') ? old('mailing_address') : $counterpartie->mailing_address }}">
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
										<input id="actual_address" type="text" class="form-control" name="actual_address" value="{{ old('actual_address') ? old('actual_address') : $counterpartie->actual_address }}">
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
										<input id="telephone" type="text" class="form-control" name="telephone" value="{{ old('telephone') ? old('telephone') : $counterpartie->telephone }}">
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
										<input id="email" type="text" class="form-control" name="email" value="{{ old('email') ? old('email') : $counterpartie->email }}">
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
										<input id="inn" type="text" class="form-control" name="inn" value="{{ old('inn') ? old('inn') : $counterpartie->inn }}">
										@if ($errors->has('name(full)'))
											<span class="help-block">
												<strong>{{ $errors->first('inn') }}</strong>
											</span>
										@endif
									</div>
									<label for="ogrn" class="col-md-1 control-label">ОГРН</label>
									<div class="col-md-3">
										<input id="ogrn" type="text" class="form-control" name="ogrn" value="{{ old('ogrn') ? old('ogrn') : $counterpartie->ogrn }}">
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
										<input id="kpp" type="text" class="form-control" name="kpp" value="{{ old('kpp') ? old('kpp') : $counterpartie->kpp }}">
										@if ($errors->has('name(full)'))
											<span class="help-block">
												<strong>{{ $errors->first('kpp') }}</strong>
											</span>
										@endif
									</div>
									<label for="okpo" class="col-md-1 control-label">ОКПО</label>
									<div class="col-md-3">
										<input id="okpo" type="text" class="form-control" name="okpo" value="{{ old('okpo') ? old('okpo') : $counterpartie->okpo }}">
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
										<input id="okved" type="text" class="form-control" name="okved" value="{{ old('okved') ? old('okved') : $counterpartie->okved }}">
										@if ($errors->has('name(full)'))
											<span class="help-block">
												<strong>{{ $errors->first('okved') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
									</div>
									<div class="col-md-2">
										<label for="statement_egryl" class="control-label">Выписка из ЕГРЮЛ</label>
										@if($counterpartie->statement_egryl == 1 || old('statement_egryl'))
											<input id='statement_egryl' class='form-check-input' name='statement_egryl' type="checkbox" checked />
										@else
											<input id='statement_egryl' class='form-check-input' name='statement_egryl' type="checkbox" />
										@endif
									</div>
									<div class="col-md-2">
										<label for="order_counterpartie" class="control-label">Приказ</label>
										@if($counterpartie->order_counterpartie == 1 || old('order_counterpartie'))
											<input id='order_counterpartie' class='form-check-input' name='order_counterpartie' type="checkbox" checked />
										@else
											<input id='order_counterpartie' class='form-check-input' name='order_counterpartie' type="checkbox" />
										@endif
									</div>
									<div class="col-md-2">
										<label for="protocol_meeting" class="control-label">Протокол собрания</label>
										@if($counterpartie->protocol_meeting == 1 || old('protocol_meeting'))
											<input id='protocol_meeting' class='form-check-input' name='protocol_meeting' type="checkbox" checked />
										@else
											<input id='protocol_meeting' class='form-check-input' name='protocol_meeting' type="checkbox" />
										@endif
									</div>
									<div class="col-md-2">
										<label for="statement_egrip" class="control-label">Выписка из ЕГРИП</label>
										@if($counterpartie->statement_egrip == 1 || old('statement_egrip'))
											<input id='statement_egrip' class='form-check-input' name='statement_egrip' type="checkbox" checked />
										@else
											<input id='statement_egrip' class='form-check-input' name='statement_egrip' type="checkbox" />
										@endif
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
									</div>
									<div class="col-md-2">
										<label for="statement_charter" class="control-label">Выписка из устава</label>
										@if($counterpartie->statement_charter == 1 || old('statement_charter'))
											<input id='statement_charter' class='form-check-input' name='statement_charter' type="checkbox" checked />
										@else
											<input id='statement_charter' class='form-check-input' name='statement_charter' type="checkbox" />
										@endif
									</div>
									<div class="col-md-2">
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
									<div class="col-md-2">
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
									<div class="col-md-2">
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
									<div class="col-md-3">
									</div>
									<div class="col-md-2">
										<label for="proxys" class="control-label">Доверенность</label>
										@if($counterpartie->proxys == 1 || old('proxys'))
											<input id='proxys' class='form-check-input' name='proxys' type="checkbox" checked />
										@else
											<input id='proxys' class='form-check-input' name='proxys' type="checkbox" />
										@endif
									</div>
									<div class="col-md-2">
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
									<div class="col-md-2">
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
									<div class="col-md-2">
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
								<div class="form-group">
									<div class="col-md-3">
										
									</div>
									<div class="col-md-1">
										<label class="control-label">Карты контрагента</label>
									</div>
									<div class="col-md-6">
										<table class="table" style='margin: 0 auto;'>
											<thead>
													<tr>
														<th>Дата</th>
														<th>Текст</th>
														<th>Актуальность</th>
													</tr>
											</thead>
											<tbody>
												@foreach($maps as $map)
													<tr class="rowsContract cursorPointer titleDocumentClick" type_title_document="map" 
																												my_action="{{route('counterpartie.update_title_document',$map->id)}}" 
																												my_title="Редактирование карты контрагента" my_id="{{$map->id}}" 
																												my_date="{{$map->date_title_document}}" 
																												my_text="{{$map->text_title_document}}" 
																												my_relevance="{{$map->relevance_document}}" 
																												my_btn_text="Изменить"
																												my_action_resol="{{route('resolution_counterpartie_store',$map->id)}}"
																												resolutions_list="{{$map->resolutions}}"
																												display_resol="block">
														<td>{{$map->date_title_document}}</td>
														<td>{{$map->text_title_document}}</td>
														<td>{{$map->relevance_document == 1 ? 'Актуальная' : 'Не актуальная'}}</td>
													</tr>
												@endforeach
											</tbody>
										</table>
										<button class="btn btn-secondary titleDocumentClick" type="button" style="float: right; margin-top: 10px;" type_title_document="map" 
																																					my_action="{{route('counterpartie.save_title_document', $counterpartie->id)}}" 
																																					my_title="Добавление карты контрагента" 
																																					my_btn_text="Добавить" 
																																					display_resol="none">Добавить карту контрагента</button>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										
									</div>
									<div class="col-md-1">
										<label class="control-label">Приказы</label>
									</div>
									<div class="col-md-6">
										<table class="table" style='margin: 0 auto;'>
											<thead>
													<tr>
														<th>Дата</th>
														<th>Текст</th>
														<th>Актуальность</th>
													</tr>
											</thead>
											<tbody>
												@foreach($orders as $order)
													<tr class="rowsContract cursorPointer titleDocumentClick" type_title_document="order" 
																												my_action="{{route('counterpartie.update_title_document',$order->id)}}" 
																												my_title="Редактирование приказа" my_id="{{$order->id}}" 
																												my_date="{{$order->date_title_document}}" 
																												my_text="{{$order->text_title_document}}" 
																												my_relevance="{{$order->relevance_document}}" 
																												my_btn_text="Изменить"
																												my_action_resol="{{route('resolution_counterpartie_store',$order->id)}}"
																												resolutions_list="{{$order->resolutions}}"
																												display_resol="block">
														<td>{{$order->date_title_document}}</td>
														<td>{{$order->text_title_document}}</td>
														<td>{{$order->relevance_document == 1 ? 'Актуальная' : 'Не актуальная'}}</td>
													</tr>
												@endforeach
											</tbody>
										</table>
										<button class="btn btn-secondary titleDocumentClick" type="button" style="float: right; margin-top: 10px;" type_title_document="order" 
																																					my_action="{{route('counterpartie.save_title_document', $counterpartie->id)}}" 
																																					my_title="Добавление приказа" 
																																					my_btn_text="Добавить" 
																																					display_resol="none">Добавить приказ</button>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										
									</div>
									<div class="col-md-1">
										<label class="control-label">Протоколы собрания</label>
									</div>
									<div class="col-md-6">
										<table class="table" style='margin: 0 auto;'>
											<thead>
													<tr>
														<th>Дата</th>
														<th>Текст</th>
														<th>Актуальность</th>
													</tr>
											</thead>
											<tbody>
												@foreach($protocol_meetings as $protocol_meeting)
													<tr class="rowsContract cursorPointer titleDocumentClick" type_title_document="protocol_meeting" 
																												my_action="{{route('counterpartie.update_title_document',$protocol_meeting->id)}}" 
																												my_title="Редактирование протокола собрания" my_id="{{$protocol_meeting->id}}" 
																												my_date="{{$protocol_meeting->date_title_document}}" 
																												my_text="{{$protocol_meeting->text_title_document}}" 
																												my_relevance="{{$protocol_meeting->relevance_document}}" 
																												my_btn_text="Изменить"
																												my_action_resol="{{route('resolution_counterpartie_store',$protocol_meeting->id)}}"
																												resolutions_list="{{$protocol_meeting->resolutions}}"
																												display_resol="block">
														<td>{{$protocol_meeting->date_title_document}}</td>
														<td>{{$protocol_meeting->text_title_document}}</td>
														<td>{{$protocol_meeting->relevance_document == 1 ? 'Актуальная' : 'Не актуальная'}}</td>
													</tr>
												@endforeach
											</tbody>
										</table>
										<button class="btn btn-secondary titleDocumentClick" type="button" style="float: right; margin-top: 10px;" type_title_document="protocol_meeting" 
																																					my_action="{{route('counterpartie.save_title_document', $counterpartie->id)}}" 
																																					my_title="Добавление протокола собрания" 
																																					my_btn_text="Добавить" 
																																					display_resol="none">Добавить протокол собрания</button>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										
									</div>
									<div class="col-md-1">
										<label class="control-label">Выписки из ЕГРЮЛ(ЕГРИП)</label>
									</div>
									<div class="col-md-6">
										<table class="table" style='margin: 0 auto;'>
											<thead>
													<tr>
														<th>Дата</th>
														<th>Текст</th>
														<th>Актуальность</th>
													</tr>
											</thead>
											<tbody>
												@foreach($statement_egrips as $statement_egrip)
													<tr class="rowsContract cursorPointer titleDocumentClick" type_title_document="statement_egrip" 
																												my_action="{{route('counterpartie.update_title_document',$statement_egrip->id)}}" 
																												my_title="Редактирование выписки из ЕГРЮЛ(ЕГРИП)" my_id="{{$statement_egrip->id}}" 
																												my_date="{{$statement_egrip->date_title_document}}" 
																												my_text="{{$statement_egrip->text_title_document}}" 
																												my_relevance="{{$statement_egrip->relevance_document}}" 
																												my_btn_text="Изменить"
																												my_action_resol="{{route('resolution_counterpartie_store',$statement_egrip->id)}}"
																												resolutions_list="{{$statement_egrip->resolutions}}"
																												display_resol="block">
														<td>{{$statement_egrip->date_title_document}}</td>
														<td>{{$statement_egrip->text_title_document}}</td>
														<td>{{$statement_egrip->relevance_document == 1 ? 'Актуальная' : 'Не актуальная'}}</td>
													</tr>
												@endforeach
											</tbody>
										</table>
										<button class="btn btn-secondary titleDocumentClick" type="button" style="float: right; margin-top: 10px;" type_title_document="statement_egrip" 
																																					my_action="{{route('counterpartie.save_title_document', $counterpartie->id)}}" 
																																					my_title="Добавление выписки из ЕГРЮЛ(ЕГРИП)" 
																																					my_btn_text="Добавить" 
																																					display_resol="none">Добавить выписку</button>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										
									</div>
									<div class="col-md-1">
										<label class="control-label">Выписки из устава</label>
									</div>
									<div class="col-md-6">
										<table class="table" style='margin: 0 auto;'>
											<thead>
													<tr>
														<th>Дата</th>
														<th>Текст</th>
														<th>Актуальность</th>
													</tr>
											</thead>
											<tbody>
												@foreach($statement_charters as $statement_charter)
													<tr class="rowsContract cursorPointer titleDocumentClick" type_title_document="statement_charter" 
																												my_action="{{route('counterpartie.update_title_document',$statement_charter->id)}}" 
																												my_title="Редактирование выписки из устава" my_id="{{$statement_charter->id}}" 
																												my_date="{{$statement_charter->date_title_document}}" 
																												my_text="{{$statement_charter->text_title_document}}" 
																												my_relevance="{{$statement_charter->relevance_document}}" 
																												my_btn_text="Изменить"
																												my_action_resol="{{route('resolution_counterpartie_store',$statement_charter->id)}}"
																												resolutions_list="{{$statement_charter->resolutions}}"
																												display_resol="block">
														<td>{{$statement_charter->date_title_document}}</td>
														<td>{{$statement_charter->text_title_document}}</td>
														<td>{{$statement_charter->relevance_document == 1 ? 'Актуальная' : 'Не актуальная'}}</td>
													</tr>
												@endforeach
											</tbody>
										</table>
										<button class="btn btn-secondary titleDocumentClick" type="button" style="float: right; margin-top: 10px;" type_title_document="statement_charter" 
																																					my_action="{{route('counterpartie.save_title_document', $counterpartie->id)}}" 
																																					my_title="Добавление выписки из устава" 
																																					my_btn_text="Добавить" 
																																					display_resol="none">Добавить выписку</button>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										
									</div>
									<div class="col-md-1">
										<label class="control-label">Копии паспорта</label>
									</div>
									<div class="col-md-6">
										<table class="table" style='margin: 0 auto;'>
											<thead>
													<tr>
														<th>Дата</th>
														<th>Текст</th>
														<th>Актуальность</th>
													</tr>
											</thead>
											<tbody>
												@foreach($copy_passports as $copy_passport)
													<tr class="rowsContract cursorPointer titleDocumentClick" type_title_document="copy_passport" 
																												my_action="{{route('counterpartie.update_title_document',$copy_passport->id)}}" 
																												my_title="Редактирование копии паспорта" my_id="{{$copy_passport->id}}" 
																												my_date="{{$copy_passport->date_title_document}}" 
																												my_text="{{$copy_passport->text_title_document}}" 
																												my_relevance="{{$copy_passport->relevance_document}}" 
																												my_btn_text="Изменить"
																												my_action_resol="{{route('resolution_counterpartie_store',$copy_passport->id)}}"
																												resolutions_list="{{$copy_passport->resolutions}}"
																												display_resol="block">
														<td>{{$copy_passport->date_title_document}}</td>
														<td>{{$copy_passport->text_title_document}}</td>
														<td>{{$copy_passport->relevance_document == 1 ? 'Актуальная' : 'Не актуальная'}}</td>
													</tr>
												@endforeach
											</tbody>
										</table>
										<button class="btn btn-secondary titleDocumentClick" type="button" style="float: right; margin-top: 10px;" type_title_document="copy_passport" 
																																					my_action="{{route('counterpartie.save_title_document', $counterpartie->id)}}" 
																																					my_title="Добавление копии паспорта" 
																																					my_btn_text="Добавить" 
																																					display_resol="none">Добавить копию паспорта</button>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										
									</div>
									<div class="col-md-1">
										<label class="control-label">Выписки из Росстата</label>
									</div>
									<div class="col-md-6">
										<table class="table" style='margin: 0 auto;'>
											<thead>
													<tr>
														<th>Дата</th>
														<th>Текст</th>
														<th>Актуальность</th>
													</tr>
											</thead>
											<tbody>
												@foreach($statements_rosstata as $statement_rosstata)
													<tr class="rowsContract cursorPointer titleDocumentClick" type_title_document="statement_rosstata" 
																												my_action="{{route('counterpartie.update_title_document',$statement_rosstata->id)}}" 
																												my_title="Редактирование выписки из Росстата" my_id="{{$statement_rosstata->id}}" 
																												my_date="{{$statement_rosstata->date_title_document}}" 
																												my_text="{{$statement_rosstata->text_title_document}}" 
																												my_relevance="{{$statement_rosstata->relevance_document}}" 
																												my_btn_text="Изменить"
																												my_action_resol="{{route('resolution_counterpartie_store',$statement_rosstata->id)}}"
																												resolutions_list="{{$statement_rosstata->resolutions}}"
																												display_resol="block">
														<td>{{$statement_rosstata->date_title_document}}</td>
														<td>{{$statement_rosstata->text_title_document}}</td>
														<td>{{$statement_rosstata->relevance_document == 1 ? 'Актуальная' : 'Не актуальная'}}</td>
													</tr>
												@endforeach
											</tbody>
										</table>
										<button class="btn btn-secondary titleDocumentClick" type="button" style="float: right; margin-top: 10px;" type_title_document="statement_rosstata" 
																																					my_action="{{route('counterpartie.save_title_document', $counterpartie->id)}}" 
																																					my_title="Добавление выписки из Росстата" 
																																					my_btn_text="Добавить" 
																																					display_resol="none">Добавить выписку</button>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										
									</div>
									<div class="col-md-1">
										<label class="control-label">Сертификаты</label>
									</div>
									<div class="col-md-6">
										<table class="table" style='margin: 0 auto;'>
											<thead>
													<tr>
														<th>Дата</th>
														<th>Текст</th>
														<th>Актуальность</th>
													</tr>
											</thead>
											<tbody>
												@foreach($certificates as $certificate)
													<tr class="rowsContract cursorPointer titleDocumentClick" type_title_document="certificate" 
																												my_action="{{route('counterpartie.update_title_document',$certificate->id)}}" 
																												my_title="Редактирование сертификата" my_id="{{$certificate->id}}" 
																												my_date="{{$certificate->date_title_document}}" 
																												my_text="{{$certificate->text_title_document}}" 
																												my_relevance="{{$certificate->relevance_document}}" 
																												my_btn_text="Изменить"
																												my_action_resol="{{route('resolution_counterpartie_store',$certificate->id)}}"
																												resolutions_list="{{$certificate->resolutions}}"
																												display_resol="block">
														<td>{{$certificate->date_title_document}}</td>
														<td>{{$certificate->text_title_document}}</td>
														<td>{{$certificate->relevance_document == 1 ? 'Актуальная' : 'Не актуальная'}}</td>
													</tr>
												@endforeach
											</tbody>
										</table>
										<button class="btn btn-secondary titleDocumentClick" type="button" style="float: right; margin-top: 10px;" type_title_document="certificate" 
																																					my_action="{{route('counterpartie.save_title_document', $counterpartie->id)}}" 
																																					my_title="Добавление сертификата" 
																																					my_btn_text="Добавить" 
																																					display_resol="none">Добавить сертификат</button>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										
									</div>
									<div class="col-md-1">
										<label class="control-label">Лицензии</label>
									</div>
									<div class="col-md-6">
										<table class="table" style='margin: 0 auto;'>
											<thead>
													<tr>
														<th>Дата</th>
														<th>Текст</th>
														<th>Актуальность</th>
													</tr>
											</thead>
											<tbody>
												@foreach($licences as $licence)
													<tr class="rowsContract cursorPointer titleDocumentClick" type_title_document="licence" 
																												my_action="{{route('counterpartie.update_title_document',$licence->id)}}" 
																												my_title="Редактирование лицензии" my_id="{{$licence->id}}" 
																												my_date="{{$licence->date_title_document}}" 
																												my_text="{{$licence->text_title_document}}" 
																												my_relevance="{{$licence->relevance_document}}" 
																												my_btn_text="Изменить"
																												my_action_resol="{{route('resolution_counterpartie_store',$licence->id)}}"
																												resolutions_list="{{$licence->resolutions}}"
																												display_resol="block">
														<td>{{$licence->date_title_document}}</td>
														<td>{{$licence->text_title_document}}</td>
														<td>{{$licence->relevance_document == 1 ? 'Актуальная' : 'Не актуальная'}}</td>
													</tr>
												@endforeach
											</tbody>
										</table>
										<button class="btn btn-secondary titleDocumentClick" type="button" style="float: right; margin-top: 10px;" type_title_document="licence" 
																																					my_action="{{route('counterpartie.save_title_document', $counterpartie->id)}}" 
																																					my_title="Добавление лицензии" 
																																					my_btn_text="Добавить" 
																																					display_resol="none">Добавить лицензию</button>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										
									</div>
									<div class="col-md-1">
										<label class="control-label">Доверенности</label>
									</div>
									<div class="col-md-6">
										<table class="table" style='margin: 0 auto;'>
											<thead>
													<tr>
														<th>Дата</th>
														<th>Текст</th>
														<th>Актуальность</th>
													</tr>
											</thead>
											<tbody>
												@foreach($proxys as $proxy)
													<tr class="rowsContract cursorPointer titleDocumentClick" type_title_document="proxy" 
																												my_action="{{route('counterpartie.update_title_document',$proxy->id)}}" 
																												my_title="Редактирование доверенности" my_id="{{$proxy->id}}" 
																												my_date="{{$proxy->date_title_document}}" 
																												my_text="{{$proxy->text_title_document}}" 
																												my_relevance="{{$proxy->relevance_document}}" 
																												my_btn_text="Изменить"
																												my_action_resol="{{route('resolution_counterpartie_store',$proxy->id)}}"
																												resolutions_list="{{$proxy->resolutions}}"
																												display_resol="block">
														<td>{{$proxy->date_title_document}}</td>
														<td>{{$proxy->text_title_document}}</td>
														<td>{{$proxy->relevance_document == 1 ? 'Актуальная' : 'Не актуальная'}}</td>
													</tr>
												@endforeach
											</tbody>
										</table>
										<button class="btn btn-secondary titleDocumentClick" type="button" style="float: right; margin-top: 10px;" type_title_document="proxy" 
																																					my_action="{{route('counterpartie.save_title_document', $counterpartie->id)}}" 
																																					my_title="Добавление доверенности" 
																																					my_btn_text="Добавить" 
																																					display_resol="none">Добавить доверенность</button>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										
									</div>
									<div class="col-md-1">
										<label class="control-label">Банковские реквизиты</label>
									</div>
									<div class="col-md-6">
										<div class="panel-body" style='max-height: 700px; overflow-y: auto;'>
											<ul class='nav nav-tabs'>
												<?php $k = 1; ?>
												@foreach($bank_details as $bank_detail)
													<li <?php if ($k==1) echo 'class="active"'; ?>>
														<a data-toggle='tab' href='#bank_detail_{{$k}}'>{{$k}}</a>
													</li>
													<?php $k++; ?>
												@endforeach
											</ul>
											<div class='tab-content'>
												<?php $k = 1; ?>
												@foreach($bank_details as $bank_detail)
													<div id='bank_detail_{{$k}}' class='tab-pane fade  <?php if ($k==1) echo 'in active'; ?>'>
														<table class="table">
															<thead>
																<tr>
																	<th>Расчетный счет</th>
																	<th>{{$bank_detail->checking_account}}</th>
																</tr>
																<tr>
																	<th>Банк р/с</th>
																	<th>{{$bank_detail->bank_account}}</th>
																</tr>
																<tr>
																	<th>Корреспонд. счет</th>
																	<th>{{$bank_detail->correspondent_account}}</th>
																</tr>
																<tr>
																	<th>Лицевой счет</th>
																	<th>{{$bank_detail->personal_account}}</th>
																</tr>
																<tr>
																	<th>БИК</th>
																	<th>{{$bank_detail->bik}}</th>
																</tr>
																<tr>
																	<th>Банк к/с (л/с)</th>
																	<th>{{$bank_detail->bank_ca_pa}}</th>
																</tr>
															</thead>
														</table>
														<button class="btn btn-secondary" type="button" style="margin-top: 10px;" data-toggle="modal" data-target="#bank_detail_modal" 
																	onclick="$('#bankDetailModalLabel').text('Редактирование расчетного счета');
																	$('#bank_detail_modal form').attr('action','{{route('counterpartie.update_bank_detail', $bank_detail->id)}}');
																	$('#bank_detail_modal input[name=checking_account]').val('{{$bank_detail->checking_account}}');
																	$('#bank_detail_modal input[name=bank_account]').val('{{$bank_detail->bank_account}}');
																	$('#bank_detail_modal input[name=correspondent_account]').val('{{$bank_detail->correspondent_account}}');
																	$('#bank_detail_modal input[name=personal_account]').val('{{$bank_detail->personal_account}}');
																	$('#bank_detail_modal input[name=bik]').val('{{$bank_detail->bik}}');
																	$('#bank_detail_modal input[name=bank_ca_pa]').val('{{$bank_detail->bank_ca_pa}}');
																	$('#bank_detail_modal button[type=submit]').text('Изменить')">Редактировать</button>
													</div>
													<?php $k++; ?>
												@endforeach
											</div>
										</div>
										<button class="btn btn-secondary" type="button" data-toggle="modal" data-target="#bank_detail_modal" style="float: right; margin-top: 10px;"
																	onclick="$('#bankDetailModalLabel').text('Новый расчетный счет');
																	$('#bank_detail_modal form').attr('action','{{route('counterpartie.save_bank_detail', $counterpartie->id)}}');
																	$('#bank_detail_modal input[name=checking_account]').val('');
																	$('#bank_detail_modal input[name=bank_account]').val('');
																	$('#bank_detail_modal input[name=correspondent_account]').val('');
																	$('#bank_detail_modal input[name=personal_account]').val('');
																	$('#bank_detail_modal input[name=bik]').val('');
																	$('#bank_detail_modal input[name=bank_ca_pa]').val('');
																	$('#bank_detail_modal button[type=submit]').text('Добавить')">Добавить банковские реквизиты</button>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-2">
										
									</div>
									<div class="col-md-2">
										<label class="control-label">Другие правоустанавливающие документы</label>
									</div>
									<div class="col-md-6">
										<table class="table" style='margin: 0 auto;'>
											<thead>
													<tr>
														<th>Дата</th>
														<th>Текст</th>
														<th>Актуальность</th>
													</tr>
											</thead>
											<tbody>
												@foreach($other_title_documents as $other_title_document)
													<tr class="rowsContract cursorPointer titleDocumentClick" type_title_document="other_title_document" 
																												my_action="{{route('counterpartie.update_title_document',$other_title_document->id)}}" 
																												my_title="Редактирование других правоустанавливающих документов" my_id="{{$other_title_document->id}}" 
																												my_date="{{$other_title_document->date_title_document}}" 
																												my_text="{{$other_title_document->text_title_document}}" 
																												my_relevance="{{$other_title_document->relevance_document}}" 
																												my_btn_text="Изменить"
																												my_action_resol="{{route('resolution_counterpartie_store',$other_title_document->id)}}"
																												resolutions_list="{{$other_title_document->resolutions}}"
																												display_resol="block">
														<td>{{$other_title_document->date_title_document}}</td>
														<td>{{$other_title_document->text_title_document}}</td>
														<td>{{$other_title_document->relevance_document == 1 ? 'Актуальная' : 'Не актуальная'}}</td>
													</tr>
												@endforeach
											</tbody>
										</table>
										<button class="btn btn-secondary titleDocumentClick" type="button" style="float: right; margin-top: 10px;" type_title_document="other_title_document" 
																																					my_action="{{route('counterpartie.save_title_document', $counterpartie->id)}}" 
																																					my_title="Добавление других правоустанавливающих документов" 
																																					my_btn_text="Добавить" 
																																					display_resol="none">Добавить другие правоустанавливающие документы</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Модальное окно новой карты контрагента -->
				<div class="modal fade" id="map_modal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form class='form_main' method='POST' action='{{route("counterpartie.save_title_document", $counterpartie->id)}}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="mapModalLabel">Новая карта контрагента</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Дата:</label>
										</div>
										<div class="col-md-7">
											<input class='form-control datepicker' type='text' name='date_title_document' value=""/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Карта контрагента:</label>
										</div>
										<div class="col-md-7">
											<input name='text_title_document' class='form-control' type='text' value=''/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											
										</div>
										<div class="col-md-7">
											<label for='map_actual'>Актуальна:</label>
											<input id='map_actual' class='form-check-input' name='relevance_document' type="checkbox"/>
										</div>
									</div>
									<div class='resolution_div'>
										<div class='form-group row'>
											<div class="col-md-3">
												<label>Резолюция:</label>
											</div>
											<div class="col-md-4">
												<button first_step='#map_modal .form_main' second_step='#map_modal .form_second' type='button' class='btn btn-secondary steps'>Добавить скан</button>
											</div>
											<div class="col-md-4">
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-7">
												<select name='resolution_list' class='form-control resolution_list {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
													@if(Session::has("new_scan"))
														@foreach(Session("all_scan") as $res)
															@if(Session("new_scan")->id == $res->id)
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' selected>{{$res->real_name_resolution}}</option>
															@else
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}'>{{$res->real_name_resolution}}</option>
															@endif
														@endforeach
													@else
														<option></option>
													@endif
												</select>
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-3">
												<button type='button' class='btn btn-secondary open_resolution' style='width: 122px;' resolution_block='map_modal .resolution_list'>Открыть скан</button>
											</div>
										</div>
									</div>
									<div class='form-group row' style='display: none;'>
										<div class="col-md-3">
											<label>Тип:</label>
										</div>
										<div class="col-md-7">
											<input name='type_title_document' class='form-control' type='text' value='map'/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' style='width: 122px;'>Добавить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
							<form class='form_second' method='POST' file='true' enctype='multipart/form-data' action='' style='display: none;'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
										<div class='col-md-6' style='display: none;'>
											<input type='text' value='id_title_document' name='real_name_document'/>
										</div>
										<div class='col-md-6'>
											<input type='file' name='new_file_resolution'/>
										</div>
										<div class='col-md-6'>
											<input name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
									<button type="button" class="btn btn-secondary steps" first_step='#map_modal .form_second' second_step='#map_modal .form_main'>Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Модальное окно нового приказа -->
				<div class="modal fade" id="order_modal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form class='form_main' method='POST' action='{{route("counterpartie.save_title_document", $counterpartie->id)}}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="orderModalLabel">Новый приказ</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Дата:</label>
										</div>
										<div class="col-md-7">
											<input class='form-control datepicker' type='text' name='date_title_document' value=""/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Приказ:</label>
										</div>
										<div class="col-md-7">
											<input name='text_title_document' class='form-control' type='text' value=''/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											
										</div>
										<div class="col-md-7">
											<label for='order_actual'>Актуальна:</label>
											<input id='order_actual' class='form-check-input' name='relevance_document' type="checkbox"/>
										</div>
									</div>
									<div class='resolution_div'>
										<div class='form-group row'>
											<div class="col-md-3">
												<label>Резолюция:</label>
											</div>
											<div class="col-md-4">
												<button first_step='#order_modal .form_main' second_step='#order_modal .form_second' type='button' class='btn btn-secondary steps'>Добавить скан</button>
											</div>
											<div class="col-md-4">
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-7">
												<select name='resolution_list' class='form-control resolution_list {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
													@if(Session::has("new_scan"))
														@foreach(Session("all_scan") as $res)
															@if(Session("new_scan")->id == $res->id)
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' selected>{{$res->real_name_resolution}}</option>
															@else
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}'>{{$res->real_name_resolution}}</option>
															@endif
														@endforeach
													@else
														<option></option>
													@endif
												</select>
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-3">
												<button type='button' class='btn btn-secondary open_resolution' style='width: 122px;' resolution_block='order_modal .resolution_list'>Открыть скан</button>
											</div>
										</div>
									</div>
									<div class='form-group row' style='display: none;'>
										<div class="col-md-3">
											<label>Тип:</label>
										</div>
										<div class="col-md-7">
											<input name='type_title_document' class='form-control' type='text' value='order'/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' style='width: 122px;'>Добавить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
							<form class='form_second' method='POST' file='true' enctype='multipart/form-data' action='' style='display: none;'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
										<div class='col-md-6' style='display: none;'>
											<input type='text' value='id_title_document' name='real_name_document'/>
										</div>
										<div class='col-md-6'>
											<input type='file' name='new_file_resolution'/>
										</div>
										<div class='col-md-6'>
											<input name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
									<button type="button" class="btn btn-secondary steps" first_step='#order_modal .form_second' second_step='#order_modal .form_main'>Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Модальное окно нового протокола собрания -->
				<div class="modal fade" id="protocol_meeting_modal" tabindex="-1" role="dialog" aria-labelledby="protocolMeetingModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form class='form_main' method='POST' action='{{route("counterpartie.save_title_document", $counterpartie->id)}}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="protocolMeetingModalLabel">Новый протокол собрания</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Дата:</label>
										</div>
										<div class="col-md-7">
											<input class='form-control datepicker' type='text' name='date_title_document' value=""/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Протокол собрания:</label>
										</div>
										<div class="col-md-7">
											<input name='text_title_document' class='form-control' type='text' value=''/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											
										</div>
										<div class="col-md-7">
											<label for='protocol_meeting_actual'>Актуальна:</label>
											<input id='protocol_meeting_actual' class='form-check-input' name='relevance_document' type="checkbox"/>
										</div>
									</div>
									<div class='resolution_div'>
										<div class='form-group row'>
											<div class="col-md-3">
												<label>Резолюция:</label>
											</div>
											<div class="col-md-4">
												<button first_step='#protocol_meeting_modal .form_main' second_step='#protocol_meeting_modal .form_second' type='button' class='btn btn-secondary steps'>Добавить скан</button>
											</div>
											<div class="col-md-4">
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-7">
												<select name='resolution_list' class='form-control resolution_list {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
													@if(Session::has("new_scan"))
														@foreach(Session("all_scan") as $res)
															@if(Session("new_scan")->id == $res->id)
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' selected>{{$res->real_name_resolution}}</option>
															@else
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}'>{{$res->real_name_resolution}}</option>
															@endif
														@endforeach
													@else
														<option></option>
													@endif
												</select>
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-3">
												<button type='button' class='btn btn-secondary open_resolution' style='width: 122px;' resolution_block='protocol_meeting_modal .resolution_list'>Открыть скан</button>
											</div>
										</div>
									</div>
									<div class='form-group row' style='display: none;'>
										<div class="col-md-3">
											<label>Тип:</label>
										</div>
										<div class="col-md-7">
											<input name='type_title_document' class='form-control' type='text' value='protocol_meeting'/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' style='width: 122px;'>Добавить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
							<form class='form_second' method='POST' file='true' enctype='multipart/form-data' action='' style='display: none;'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
										<div class='col-md-6' style='display: none;'>
											<input type='text' value='id_title_document' name='real_name_document'/>
										</div>
										<div class='col-md-6'>
											<input type='file' name='new_file_resolution'/>
										</div>
										<div class='col-md-6'>
											<input name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
									<button type="button" class="btn btn-secondary steps" first_step='#protocol_meeting_modal .form_second' second_step='#protocol_meeting_modal .form_main'>Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Модальное окно новой выписки из ЕГРЮЛ(ЕГРИП) -->
				<div class="modal fade" id="statement_egrip_modal" tabindex="-1" role="dialog" aria-labelledby="statementEgripModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form class='form_main' method='POST' action='{{route("counterpartie.save_title_document", $counterpartie->id)}}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="statementEgripModalLabel">Новая выписка из ЕГРЮЛ(ЕГРИП)</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Дата:</label>
										</div>
										<div class="col-md-7">
											<input class='form-control datepicker' type='text' name='date_title_document' value=""/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Выписка:</label>
										</div>
										<div class="col-md-7">
											<input name='text_title_document' class='form-control' type='text' value=''/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											
										</div>
										<div class="col-md-7">
											<label for='statement_egrip_actual'>Актуальна:</label>
											<input id='statement_egrip_actual' class='form-check-input' name='relevance_document' type="checkbox"/>
										</div>
									</div>
									<div class='resolution_div'>
										<div class='form-group row'>
											<div class="col-md-3">
												<label>Резолюция:</label>
											</div>
											<div class="col-md-4">
												<button first_step='#statement_egrip_modal .form_main' second_step='#statement_egrip_modal .form_second' type='button' class='btn btn-secondary steps'>Добавить скан</button>
											</div>
											<div class="col-md-4">
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-7">
												<select name='resolution_list' class='form-control resolution_list {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
													@if(Session::has("new_scan"))
														@foreach(Session("all_scan") as $res)
															@if(Session("new_scan")->id == $res->id)
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' selected>{{$res->real_name_resolution}}</option>
															@else
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}'>{{$res->real_name_resolution}}</option>
															@endif
														@endforeach
													@else
														<option></option>
													@endif
												</select>
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-3">
												<button type='button' class='btn btn-secondary open_resolution' style='width: 122px;' resolution_block='statement_egrip_modal .resolution_list'>Открыть скан</button>
											</div>
										</div>
									</div>
									<div class='form-group row' style='display: none;'>
										<div class="col-md-3">
											<label>Тип:</label>
										</div>
										<div class="col-md-7">
											<input name='type_title_document' class='form-control' type='text' value='statement_egrip'/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' style='width: 122px;'>Добавить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
							<form class='form_second' method='POST' file='true' enctype='multipart/form-data' action='' style='display: none;'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
										<div class='col-md-6' style='display: none;'>
											<input type='text' value='id_title_document' name='real_name_document'/>
										</div>
										<div class='col-md-6'>
											<input type='file' name='new_file_resolution'/>
										</div>
										<div class='col-md-6'>
											<input name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
									<button type="button" class="btn btn-secondary steps" first_step='#statement_egrip_modal .form_second' second_step='#statement_egrip_modal .form_main'>Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Модальное окно новой выписки из устава -->
				<div class="modal fade" id="statement_charter_modal" tabindex="-1" role="dialog" aria-labelledby="statementCharterModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form class='form_main' method='POST' action='{{route("counterpartie.save_title_document", $counterpartie->id)}}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="statementCharterModalLabel">Новая выписка из устава</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Дата:</label>
										</div>
										<div class="col-md-7">
											<input class='form-control datepicker' type='text' name='date_title_document' value=""/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Выписка:</label>
										</div>
										<div class="col-md-7">
											<input name='text_title_document' class='form-control' type='text' value=''/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											
										</div>
										<div class="col-md-7">
											<label for='statement_charter_actual'>Актуальна:</label>
											<input id='statement_charter_actual' class='form-check-input' name='relevance_document' type="checkbox"/>
										</div>
									</div>
									<div class='resolution_div'>
										<div class='form-group row'>
											<div class="col-md-3">
												<label>Резолюция:</label>
											</div>
											<div class="col-md-4">
												<button first_step='#statement_charter_modal .form_main' second_step='#statement_charter_modal .form_second' type='button' class='btn btn-secondary steps'>Добавить скан</button>
											</div>
											<div class="col-md-4">
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-7">
												<select name='resolution_list' class='form-control resolution_list {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
													@if(Session::has("new_scan"))
														@foreach(Session("all_scan") as $res)
															@if(Session("new_scan")->id == $res->id)
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' selected>{{$res->real_name_resolution}}</option>
															@else
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}'>{{$res->real_name_resolution}}</option>
															@endif
														@endforeach
													@else
														<option></option>
													@endif
												</select>
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-3">
												<button type='button' class='btn btn-secondary open_resolution' style='width: 122px;' resolution_block='statement_charter_modal .resolution_list'>Открыть скан</button>
											</div>
										</div>
									</div>
									<div class='form-group row' style='display: none;'>
										<div class="col-md-3">
											<label>Тип:</label>
										</div>
										<div class="col-md-7">
											<input name='type_title_document' class='form-control' type='text' value='statement_charter'/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' style='width: 122px;'>Добавить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
							<form class='form_second' method='POST' file='true' enctype='multipart/form-data' action='' style='display: none;'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
										<div class='col-md-6' style='display: none;'>
											<input type='text' value='id_title_document' name='real_name_document'/>
										</div>
										<div class='col-md-6'>
											<input type='file' name='new_file_resolution'/>
										</div>
										<div class='col-md-6'>
											<input name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
									<button type="button" class="btn btn-secondary steps" first_step='#statement_charter_modal .form_second' second_step='#statement_charter_modal .form_main'>Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Модальное окно новой копии паспорта -->
				<div class="modal fade" id="copy_passport_modal" tabindex="-1" role="dialog" aria-labelledby="copyPassportModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form class='form_main' method='POST' action='{{route("counterpartie.save_title_document", $counterpartie->id)}}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="copyPassportModalLabel">Новая копия паспорта</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Дата:</label>
										</div>
										<div class="col-md-7">
											<input class='form-control datepicker' type='text' name='date_title_document' value=""/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Копия паспорта:</label>
										</div>
										<div class="col-md-7">
											<input name='text_title_document' class='form-control' type='text' value=''/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											
										</div>
										<div class="col-md-7">
											<label for='copy_passport_actual'>Актуальна:</label>
											<input id='copy_passport_actual' class='form-check-input' name='relevance_document' type="checkbox"/>
										</div>
									</div>
									<div class='resolution_div'>
										<div class='form-group row'>
											<div class="col-md-3">
												<label>Резолюция:</label>
											</div>
											<div class="col-md-4">
												<button first_step='#copy_passport_modal .form_main' second_step='#copy_passport_modal .form_second' type='button' class='btn btn-secondary steps'>Добавить скан</button>
											</div>
											<div class="col-md-4">
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-7">
												<select name='resolution_list' class='form-control resolution_list {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
													@if(Session::has("new_scan"))
														@foreach(Session("all_scan") as $res)
															@if(Session("new_scan")->id == $res->id)
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' selected>{{$res->real_name_resolution}}</option>
															@else
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}'>{{$res->real_name_resolution}}</option>
															@endif
														@endforeach
													@else
														<option></option>
													@endif
												</select>
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-3">
												<button type='button' class='btn btn-secondary open_resolution' style='width: 122px;' resolution_block='copy_passport_modal .resolution_list'>Открыть скан</button>
											</div>
										</div>
									</div>
									<div class='form-group row' style='display: none;'>
										<div class="col-md-3">
											<label>Тип:</label>
										</div>
										<div class="col-md-7">
											<input name='type_title_document' class='form-control' type='text' value='copy_passport'/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' style='width: 122px;'>Добавить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
							<form class='form_second' method='POST' file='true' enctype='multipart/form-data' action='' style='display: none;'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
										<div class='col-md-6' style='display: none;'>
											<input type='text' value='id_title_document' name='real_name_document'/>
										</div>
										<div class='col-md-6'>
											<input type='file' name='new_file_resolution'/>
										</div>
										<div class='col-md-6'>
											<input name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
									<button type="button" class="btn btn-secondary steps" first_step='#copy_passport_modal .form_second' second_step='#copy_passport_modal .form_main'>Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Модальное окно новой выписки из Росстата -->
				<div class="modal fade" id="statement_rosstata_modal" tabindex="-1" role="dialog" aria-labelledby="statementRosstataModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form class='form_main' method='POST' action='{{route("counterpartie.save_title_document", $counterpartie->id)}}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="statementRosstataModalLabel">Новая выписка из Росстата</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Дата:</label>
										</div>
										<div class="col-md-7">
											<input class='form-control datepicker' type='text' name='date_title_document' value=""/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Выписка:</label>
										</div>
										<div class="col-md-7">
											<input name='text_title_document' class='form-control' type='text' value=''/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											
										</div>
										<div class="col-md-7">
											<label for='statement_rosstata_actual'>Актуальна:</label>
											<input id='statement_rosstata_actual' class='form-check-input' name='relevance_document' type="checkbox"/>
										</div>
									</div>
									<div class='resolution_div'>
										<div class='form-group row'>
											<div class="col-md-3">
												<label>Резолюция:</label>
											</div>
											<div class="col-md-4">
												<button first_step='#statement_rosstata_modal .form_main' second_step='#statement_rosstata_modal .form_second' type='button' class='btn btn-secondary steps'>Добавить скан</button>
											</div>
											<div class="col-md-4">
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-7">
												<select name='resolution_list' class='form-control resolution_list {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
													@if(Session::has("new_scan"))
														@foreach(Session("all_scan") as $res)
															@if(Session("new_scan")->id == $res->id)
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' selected>{{$res->real_name_resolution}}</option>
															@else
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}'>{{$res->real_name_resolution}}</option>
															@endif
														@endforeach
													@else
														<option></option>
													@endif
												</select>
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-3">
												<button type='button' class='btn btn-secondary open_resolution' style='width: 122px;' resolution_block='statement_rosstata_modal .resolution_list'>Открыть скан</button>
											</div>
										</div>
									</div>
									<div class='form-group row' style='display: none;'>
										<div class="col-md-3">
											<label>Тип:</label>
										</div>
										<div class="col-md-7">
											<input name='type_title_document' class='form-control' type='text' value='statement_rosstata'/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' style='width: 122px;'>Добавить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
							<form class='form_second' method='POST' file='true' enctype='multipart/form-data' action='' style='display: none;'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
										<div class='col-md-6' style='display: none;'>
											<input type='text' value='id_title_document' name='real_name_document'/>
										</div>
										<div class='col-md-6'>
											<input type='file' name='new_file_resolution'/>
										</div>
										<div class='col-md-6'>
											<input name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
									<button type="button" class="btn btn-secondary steps" first_step='#statement_rosstata_modal .form_second' second_step='#statement_rosstata_modal .form_main'>Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Модальное окно нового сертификата -->
				<div class="modal fade" id="certificate_modal" tabindex="-1" role="dialog" aria-labelledby="certificateModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form class='form_main' method='POST' action='{{route("counterpartie.save_title_document", $counterpartie->id)}}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="certificateModalLabel">Новый сертификат</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Дата:</label>
										</div>
										<div class="col-md-7">
											<input class='form-control datepicker' type='text' name='date_title_document' value=""/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Сертификат:</label>
										</div>
										<div class="col-md-7">
											<input name='text_title_document' class='form-control' type='text' value=''/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											
										</div>
										<div class="col-md-7">
											<label for='certificate_actual'>Актуальна:</label>
											<input id='certificate_actual' class='form-check-input' name='relevance_document' type="checkbox"/>
										</div>
									</div>
									<div class='resolution_div'>
										<div class='form-group row'>
											<div class="col-md-3">
												<label>Резолюция:</label>
											</div>
											<div class="col-md-4">
												<button first_step='#certificate_modal .form_main' second_step='#certificate_modal .form_second' type='button' class='btn btn-secondary steps'>Добавить скан</button>
											</div>
											<div class="col-md-4">
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-7">
												<select name='resolution_list' class='form-control resolution_list {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
													@if(Session::has("new_scan"))
														@foreach(Session("all_scan") as $res)
															@if(Session("new_scan")->id == $res->id)
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' selected>{{$res->real_name_resolution}}</option>
															@else
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}'>{{$res->real_name_resolution}}</option>
															@endif
														@endforeach
													@else
														<option></option>
													@endif
												</select>
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-3">
												<button type='button' class='btn btn-secondary open_resolution' style='width: 122px;' resolution_block='certificate_modal .resolution_list'>Открыть скан</button>
											</div>
										</div>
									</div>
									<div class='form-group row' style='display: none;'>
										<div class="col-md-3">
											<label>Тип:</label>
										</div>
										<div class="col-md-7">
											<input name='type_title_document' class='form-control' type='text' value='certificate'/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' style='width: 122px;'>Добавить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
							<form class='form_second' method='POST' file='true' enctype='multipart/form-data' action='' style='display: none;'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
										<div class='col-md-6' style='display: none;'>
											<input type='text' value='id_title_document' name='real_name_document'/>
										</div>
										<div class='col-md-6'>
											<input type='file' name='new_file_resolution'/>
										</div>
										<div class='col-md-6'>
											<input name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
									<button type="button" class="btn btn-secondary steps" first_step='#certificate_modal .form_second' second_step='#certificate_modal .form_main'>Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Модальное окно новой лицензии -->
				<div class="modal fade" id="licence_modal" tabindex="-1" role="dialog" aria-labelledby="licenceModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form class='form_main' method='POST' action='{{route("counterpartie.save_title_document", $counterpartie->id)}}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="licenceModalLabel">Новая лицензия</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Дата:</label>
										</div>
										<div class="col-md-7">
											<input class='form-control datepicker' type='text' name='date_title_document' value=""/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Лицензия:</label>
										</div>
										<div class="col-md-7">
											<input name='text_title_document' class='form-control' type='text' value=''/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											
										</div>
										<div class="col-md-7">
											<label for='licence_actual'>Актуальна:</label>
											<input id='licence_actual' class='form-check-input' name='relevance_document' type="checkbox"/>
										</div>
									</div>
									<div class='resolution_div'>
										<div class='form-group row'>
											<div class="col-md-3">
												<label>Резолюция:</label>
											</div>
											<div class="col-md-4">
												<button first_step='#licence_modal .form_main' second_step='#licence_modal .form_second' type='button' class='btn btn-secondary steps'>Добавить скан</button>
											</div>
											<div class="col-md-4">
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-7">
												<select name='resolution_list' class='form-control resolution_list {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
													@if(Session::has("new_scan"))
														@foreach(Session("all_scan") as $res)
															@if(Session("new_scan")->id == $res->id)
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' selected>{{$res->real_name_resolution}}</option>
															@else
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}'>{{$res->real_name_resolution}}</option>
															@endif
														@endforeach
													@else
														<option></option>
													@endif
												</select>
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-3">
												<button type='button' class='btn btn-secondary open_resolution' style='width: 122px;' resolution_block='licence_modal .resolution_list'>Открыть скан</button>
											</div>
										</div>
									</div>
									<div class='form-group row' style='display: none;'>
										<div class="col-md-3">
											<label>Тип:</label>
										</div>
										<div class="col-md-7">
											<input name='type_title_document' class='form-control' type='text' value='licence'/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' style='width: 122px;'>Добавить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
							<form class='form_second' method='POST' file='true' enctype='multipart/form-data' action='' style='display: none;'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
										<div class='col-md-6' style='display: none;'>
											<input type='text' value='id_title_document' name='real_name_document'/>
										</div>
										<div class='col-md-6'>
											<input type='file' name='new_file_resolution'/>
										</div>
										<div class='col-md-6'>
											<input name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
									<button type="button" class="btn btn-secondary steps" first_step='#licence_modal .form_second' second_step='#licence_modal .form_main'>Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Модальное окно новой доверенности -->
				<div class="modal fade" id="proxy_modal" tabindex="-1" role="dialog" aria-labelledby="proxyModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form class='form_main' method='POST' action='{{route("counterpartie.save_title_document", $counterpartie->id)}}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="proxyModalLabel">Новая доверенность</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Дата:</label>
										</div>
										<div class="col-md-7">
											<input class='form-control datepicker' type='text' name='date_title_document' value=""/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Доверенность:</label>
										</div>
										<div class="col-md-7">
											<input name='text_title_document' class='form-control' type='text' value=''/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											
										</div>
										<div class="col-md-7">
											<label for='proxy_actual'>Актуальна:</label>
											<input id='proxy_actual' class='form-check-input' name='relevance_document' type="checkbox"/>
										</div>
									</div>
									<div class='resolution_div'>
										<div class='form-group row'>
											<div class="col-md-3">
												<label>Резолюция:</label>
											</div>
											<div class="col-md-4">
												<button first_step='#proxy_modal .form_main' second_step='#proxy_modal .form_second' type='button' class='btn btn-secondary steps'>Добавить скан</button>
											</div>
											<div class="col-md-4">
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-7">
												<select name='resolution_list' class='form-control resolution_list {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
													@if(Session::has("new_scan"))
														@foreach(Session("all_scan") as $res)
															@if(Session("new_scan")->id == $res->id)
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' selected>{{$res->real_name_resolution}}</option>
															@else
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}'>{{$res->real_name_resolution}}</option>
															@endif
														@endforeach
													@else
														<option></option>
													@endif
												</select>
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-3">
												<button type='button' class='btn btn-secondary open_resolution' style='width: 122px;' resolution_block='proxy_modal .resolution_list'>Открыть скан</button>
											</div>
										</div>
									</div>
									<div class='form-group row' style='display: none;'>
										<div class="col-md-3">
											<label>Тип:</label>
										</div>
										<div class="col-md-7">
											<input name='type_title_document' class='form-control' type='text' value='proxy'/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' style='width: 122px;'>Добавить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
							<form class='form_second' method='POST' file='true' enctype='multipart/form-data' action='' style='display: none;'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
										<div class='col-md-6' style='display: none;'>
											<input type='text' value='id_title_document' name='real_name_document'/>
										</div>
										<div class='col-md-6'>
											<input type='file' name='new_file_resolution'/>
										</div>
										<div class='col-md-6'>
											<input name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
									<button type="button" class="btn btn-secondary steps" first_step='#proxy_modal .form_second' second_step='#proxy_modal .form_main'>Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Модальное окно новых банковских реквизитов -->
				<div class="modal fade" id="bank_detail_modal" tabindex="-1" role="dialog" aria-labelledby="bankDetailModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form method='POST' action='{{route("counterpartie.save_bank_detail", $counterpartie->id)}}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="bankDetailModalLabel">Новый расчетный счет</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Расчетный счет:</label>
										</div>
										<div class="col-md-7">
											<input class='form-control' type='text' name='checking_account' value=""/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Банк р/с:</label>
										</div>
										<div class="col-md-7">
											<input name='bank_account' class='form-control' type='text' value=''/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Корреспонд. счет:</label>
										</div>
										<div class="col-md-7">
											<input name='correspondent_account' class='form-control' type='text' value=''/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Лицевой счет:</label>
										</div>
										<div class="col-md-7">
											<input name='personal_account' class='form-control' type='text' value=''/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>БИК:</label>
										</div>
										<div class="col-md-7">
											<input name='bik' class='form-control' type='text' value=''/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Банк к/с (л/с):</label>
										</div>
										<div class="col-md-7">
											<input name='bank_ca_pa' class='form-control' type='text' value=''/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' style='width: 122px;'>Добавить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Модальное окно новых других правоустанавливающих документов -->
				<div class="modal fade" id="other_title_document_modal" tabindex="-1" role="dialog" aria-labelledby="otherTitleDocumentModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form class='form_main' method='POST' action='{{route("counterpartie.save_title_document", $counterpartie->id)}}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="otherTitleDocumentModalLabel">Новый другой правоустанавливающий документ</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Дата:</label>
										</div>
										<div class="col-md-7">
											<input class='form-control datepicker' type='text' name='date_title_document' value=""/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Документ:</label>
										</div>
										<div class="col-md-7">
											<!--<textarea name='text_title_document' class='form-control' type='text' value='' rows='5'></textarea>-->
											<input name='text_title_document' class='form-control' type='text' value=''/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											
										</div>
										<div class="col-md-7">
											<label for='other_title_document_actual'>Актуальна:</label>
											<input id='other_title_document_actual' class='form-check-input' name='relevance_document' type="checkbox"/>
										</div>
									</div>
									<div class='resolution_div'>
										<div class='form-group row'>
											<div class="col-md-3">
												<label>Резолюция:</label>
											</div>
											<div class="col-md-4">
												<button first_step='#other_title_document_modal .form_main' second_step='#other_title_document_modal .form_second' type='button' class='btn btn-secondary steps'>Добавить скан</button>
											</div>
											<div class="col-md-4">
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-7">
												<select name='resolution_list' class='form-control resolution_list {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
													@if(Session::has("new_scan"))
														@foreach(Session("all_scan") as $res)
															@if(Session("new_scan")->id == $res->id)
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' selected>{{$res->real_name_resolution}}</option>
															@else
																<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}'>{{$res->real_name_resolution}}</option>
															@endif
														@endforeach
													@else
														<option></option>
													@endif
												</select>
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
											</div>
											<div class="col-md-3">
												<button type='button' class='btn btn-secondary open_resolution' style='width: 122px;' resolution_block='other_title_document_modal .resolution_list'>Открыть скан</button>
											</div>
										</div>
									</div>
									<div class='form-group row' style='display: none;'>
										<div class="col-md-3">
											<label>Тип:</label>
										</div>
										<div class="col-md-7">
											<input name='type_title_document' class='form-control' type='text' value='other_title_document'/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' style='width: 122px;'>Добавить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
							<form class='form_second' method='POST' file='true' enctype='multipart/form-data' action='' style='display: none;'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
										<div class='col-md-6' style='display: none;'>
											<input type='text' value='id_title_document' name='real_name_document'/>
										</div>
										<div class='col-md-6'>
											<input type='file' name='new_file_resolution'/>
										</div>
										<div class='col-md-6'>
											<input name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
									<button type="button" class="btn btn-secondary steps" first_step='#other_title_document_modal .form_second' second_step='#other_title_document_modal .form_main'>Закрыть</button>
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
