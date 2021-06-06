@extends('layouts.header')

@section('title')
	Новый СИП реестр
@endsection

@section('content')
	<style>
		.flex-container{
			display: flex;
			flex-direction: row;
			justify-content: space-between;
			flex-wrap: wrap;
		}
		.box{
			
		}
	</style>
	<div class="flex-center position-ref full-height">
		@if(Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Отдел управления договорами' OR Auth::User()->hasRole()->role == 'Администрация')
				@if(Route::has('login'))
					<div class="top-right links">
						
					</div>
				@endif
				<div class="content">
					<form method='POST' action="{{route('department.ekonomic.create_sip_reestr')}}" file='true' enctype='multipart/form-data'>
						{{csrf_field()}}
						<div class="flex-container">
							<div class="box">
								<label>Контрагент</label>
								<div class="form-group">
									<select id="sel4" class='form-control select_counterpartie_reestr {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract'>
										<option></option>
										@if($counterparties)
											@foreach($counterparties as $counterpartie)
												@if(old('id_counterpartie_contract'))
													@if(old('id_counterpartie_contract') == $counterpartie->id)
														<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}' selected>{{ $counterpartie->name }}</option>
													@else
														<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>												
													@endif
												@else
													<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
												@endif
											@endforeach
										@endif
									</select>
									@if($errors->has('id_counterpartie_contract'))
										<label class='msgError'>{{$errors->first('id_counterpartie_contract')}}</label>
									@endif
								</div>
							</div>
							<div class="box">
								<div class="form-group">
									<button type='button' class="btn btn-primary" data-toggle="modal" data-target="#chose_counterpartie" style='margin-top: 27px; margin-left: 5px;'>Выбрать</button>
								</div>
							</div>
							<div class="box" style='margin-left: 20px;'>
								<div class="form-group">
									<label>Внимание!</label>
									<input class='form-control' style='color:red; text-align:center;' type='text' value='' readonly />
								</div>
							</div>
							<div class="box" style='margin-left: 20px;'>
								<div class="form-group">
									<label for='numberContract'>Номер договора</label>
									<input id='numberContract' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' name='number_contract' type='text' value='{{old("number_contract")}}' readonly />
									@if($errors->has('number_contract'))
										<label class='msgError'>{{$errors->first('number_contract')}}</label>
									@endif
								</div>
							</div>
							<div class="box" style='margin-left: 20px;'>
								<div class="form-group">
									<label for='amount_contract_reestr'>Сумма (окончательная)</label>
									<input id='amount_contract_reestr' class='form-control check-number' name='amount_contract_reestr' type='text' value='{{old("amount_contract_reestr")}}'/>
								</div>
							</div>
							<div class="box" style='margin-left: 20px;'>
								<div class="form-group">
									<label for='amount_invoice_reestr'>Сумма по счетам</label>
									<input id='amount_invoice_reestr' class='form-control check-number' name='amount_invoice_reestr' type='text' value='{{$reestr->amount_invoice_reestr}}' disabled />
								</div>
							</div>
							<div class="box" style='width: 100px;'>
								<div class="form-group">
									<label for='number_pp'>№ п/п</label>
									<input id='number_pp' class='change_contract_number form-control {{$errors->has("number_pp") ? print("inputError ") : print("")}}' name='number_pp' type='text' value='{{old("number_pp")}}' required/>
									@if($errors->has('number_pp'))
										<label class='msgError'>{{$errors->first('number_pp')}}</label>
									@endif
								</div>
							</div>
							<div class="box" style='margin-left: 20px; width: 200px;'>
								<div class="form-group">
									<label for='index_dep' style='font-size: 12px;'>Индекс подразд.</label>
									<select id='index_dep' class='change_contract_number form-control {{$errors->has("index_dep") ? print("inputError ") : print("")}}' name='index_dep' type='text' value='{{old("index_dep")}}' required>
										@if(old('index_dep'))
											<option>{{old('index_dep')}}</option>
										@endif
										<option></option>
										@foreach($departments as $department)
											<option value='{{$department->index_department}}'>{{$department->index_department}} {{$department->name_department}}</option>
										@endforeach
									</select>
									@if($errors->has('index_dep'))
										<label class='msgError'>{{$errors->first('index_dep')}}</label>
									@endif
								</div>
							</div>
							<div class="box" style='margin-left: 20px; width: 100px;'>
								<div class="form-group">
									<label for='year_contract'>Год</label>
									<input id='year_contract' class='change_contract_number form-control {{$errors->has("year_contract") ? print("inputError ") : print("")}}' name='year_contract' type='text' value='{{old("year_contract")}}' required />
									@if($errors->has('year_contract'))
										<label class='msgError'>{{$errors->first('year_contract')}}</label>
									@endif
								</div>
							</div>
							<div class="box" style='margin-left: 20px;'>
								<div class="form-group">
									<label for='executor_contract_reestr'>Исполнитель по Дог./Контр.</label>
									<select class='form-control' name='executor_contract_reestr'>
										<option></option>
										@if(old('executor_reestr'))
											@foreach($curators_sip as $in_curators)
												@if(old('executor_contract_reestr') == $in_curators->id)
													<option value='{{$in_curators->id}}' selected>{{$in_curators->FIO}}</option>
												@else
													<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
												@endif
											@endforeach
										@else
											@foreach($curators_sip as $in_curators)
												<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
											@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="box" style='margin-left: 20px;'>
								<div class="form-group">
									<label for='executor_reestr'>Исполнитель ОУД</label>
									<select class='form-control' name='executor_reestr'>
										<option></option>
										@if(old('executor_reestr'))
											@foreach($curators as $in_curators)
												@if(old('executor_reestr') == $in_curators->id)
													<option value='{{$in_curators->id}}' selected>{{$in_curators->FIO}}</option>
												@else
													<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
												@endif
											@endforeach
										@else
											@foreach($curators as $in_curators)
												<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
											@endforeach
										@endif
									</select>
									@if($errors->has('executor_reestr'))
										<label class='msgError'>{{$errors->first('executor_reestr')}}</label>
									@endif
								</div>
							</div>
							<div class="box" style='margin-left: 20px;'>
								<div class="form-group">
									<label for='date_save_contract_reestr' style='font-size: 12px;'>Дата сдачи Дог./Контр. на хранение</label>
									<input id='date_save_contract_reestr' class='datepicker form-control {{$errors->has("date_save_contract_reestr") ? print("inputError ") : print("")}}' name='date_save_contract_reestr' value='{{old("date_save_contract_reestr")}}'/>
									@if($errors->has('date_save_contract_reestr'))
										<label class='msgError'>{{$errors->first('date_save_contract_reestr')}}</label>
									@endif
								</div>
							</div>
							<div class="box" style='margin-left: 20px;'>
								<div class="form-group">
									<label for='place_save_contract_reestr'>Место хранения</label>
									<input id='place_save_contract_reestr' class='form-control {{$errors->has("place_save_contract_reestr") ? print("inputError ") : print("")}}' name='place_save_contract_reestr' value='{{old("place_save_contract_reestr")}}'/>
									@if($errors->has('place_save_contract'))
										<label class='msgError'>{{$errors->first('place_save_contract')}}</label>
									@endif
								</div>
							</div>
							<div class="box" style='margin-left: 20px;'>
								<div class="form-group">
									<label for='sel6' style='font-size: 12px;'>Тип документа</label>
									<select id="sel6" class='form-control {{$errors->has("type_document_reestr") ? print("inputError ") : print("")}}' name='type_document_reestr'>
										<option></option>
										@foreach($type_documents as $type_document)
											@if(old('type_document_reestr'))
												@if(old('type_document_reestr') == $type_document->id)
													<option value='{{$type_document->id}}' selected>{{$type_document->name_type_document}}</option>
												@else
													<option value='{{$type_document->id}}'>{{$type_document->name_type_document}}</option>
												@endif
											@else
												<option value='{{$type_document->id}}'>{{$type_document->name_type_document}}</option>
											@endif
										@endforeach
									</select>
								</div>
							</div>
							<div class="box" style='margin-left: 20px;'>
								<div class="form-group">
									<label for='date_contract_on_first_reestr'>Дата Дог./Контр. на 1 л.</label>
									<input id='date_contract_on_first_reestr' class='datepicker form-control {{$errors->has("date_contract_on_first_reestr") ? print("inputError ") : print("")}}' name='date_contract_on_first_reestr' value='{{old("date_contract_on_first_reestr")}}'/>
									@if($errors->has('date_contract_on_first_reestr'))
										<label class='msgError'>{{$errors->first('date_contract_on_first_reestr')}}</label>
									@endif
								</div>
							</div>
							<div class="box" style='margin-left: 20px;'>
								<div class="form-group">
									<label for='date_signing_contract_reestr'>Дата подписания ФКП "НТИИМ"</label>
									<input id='date_signing_contract_reestr' class='datepicker form-control {{$errors->has("date_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_reestr' value='{{old("date_signing_contract_reestr")}}'/>
									@if($errors->has('date_signing_contract_reestr'))
										<label class='msgError'>{{$errors->first('date_signing_contract_reestr')}}</label>
									@endif
								</div>
							</div>
							<div class="box" style='font-size: 12px; margin-left: 20px;'>
								<div class="form-group">
									<label for='date_control_signing_contract_reestr'>Контрольный срок подписания Дог./Контр.</label>
									<input id='date_control_signing_contract_reestr' class='datepicker form-control {{$errors->has("date_control_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_control_signing_contract_reestr' value='{{old("date_control_signing_contract_reestr")}}'/>
									@if($errors->has('date_control_signing_contract_reestr'))
										<label class='msgError'>{{$errors->first('date_control_signing_contract_reestr')}}</label>
									@endif
								</div>
							</div>
							<div class="box">
								<div class="form-group">
									<label id='label_date_registration_project_reestr' for='date_registration_project_reestr'>{{ $reestr->application_reestr ? 'Дата регистрации заявки' : 'Дата регистрации проекта'}}</label>
									@if(old('application_reestr'))
										<input id='application_reestr' class='form-check-input' style='float: right;' name='application_reestr' type="checkbox" checked />
									@else
										<input id='application_reestr' class='form-check-input' style='float: right;' name='application_reestr' type="checkbox" />
									@endif
									<label for='application_reestr' style='float: right; margin-right: 5px;'>Заявка</label>
									<input id='date_registration_project_reestr' class='datepicker form-control {{$errors->has("date_registration_project_reestr") ? print("inputError ") : print("")}}' name='date_registration_project_reestr' value='{{old("date_registration_project_reestr")}}'/>
									@if($errors->has('date_registration_project_reestr'))
										<label class='msgError'>{{$errors->first('date_registration_project_reestr')}}</label>
									@endif
								</div>
							</div>
							<div class="box">
								<div class="form-group">
									<label for='date_signing_contract_counterpartie_reestr'>Дата подписания Контрагентом</label>
									<input id='date_signing_contract_counterpartie_reestr' class='datepicker form-control {{$errors->has("date_signing_contract_counterpartie_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_counterpartie_reestr' value='{{old("date_signing_contract_counterpartie_reestr")}}'/>
									@if($errors->has('date_signing_contract_counterpartie_reestr'))
										<label class='msgError'>{{$errors->first('date_signing_contract_counterpartie_reestr')}}</label>
									@endif
								</div>
							</div>
							<div class="box">
								<div class="form-group">
									<label for='date_entry_into_force_reestr'>Дата вступления Дог./Контр. в силу</label>
									<input id='date_entry_into_force_reestr' class='datepicker form-control {{$errors->has("date_entry_into_force_reestr") ? print("inputError ") : print("")}}' name='date_entry_into_force_reestr' value='{{old("date_entry_into_force_reestr")}}'/>
									@if($errors->has('date_entry_into_force_reestr'))
										<label class='msgError'>{{$errors->first('date_entry_into_force_reestr')}}</label>
									@endif
								</div>
							</div>
						</div>
						<div class=''>
							<div class="">
								<div class=''>
									<div class="">
										<div class=''>
											
										</div>
										<div class=''>
											
										</div>
									</div>
									<div class="">
										<div class=''>
											<div class=" ">
												<div class="form-group">
													<label for='protocols_reestr'>Протоколы</label>
													<input id='protocols_reestr' class='form-control {{$errors->has("protocols_reestr") ? print("inputError ") : print("")}}' name='protocols_reestr' value='{{old("protocols_reestr")}}' readonly />
													@if($errors->has('protocols_reestr'))
														<label class='msgError'>{{$errors->first('protocols_reestr')}}</label>
													@endif
												</div>
											</div>
											<div class="  ">
												<div class="form-group">
													<label for='add_agreements_reestr'>ДС</label>
													<input id='add_agreements_reestr' class='form-control {{$errors->has("add_agreements_reestr") ? print("inputError ") : print("")}}' name='add_agreements_reestr' value='{{old("add_agreements_reestr")}}' readonly />
													@if($errors->has('add_agreements_reestr'))
														<label class='msgError'>{{$errors->first('add_agreements_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class=''>
											<div class=" ">
												<div class="form-group">
													<label for='sel9'>Согл./Не согл.</label>
													<select id="sel9" class='form-control {{$errors->has("reconciliation_protocol_reestr") ? print("inputError ") : print("")}}' name='reconciliation_protocol_reestr'>
														<option value='0'></option>
														<option value='1'>Согласовано</option>
														<option value='2'>Не согласовано</option>
													</select>
												</div>
											</div>
											<div class="  ">
												<div class="form-group">
													<label for='sel10'>Согл./Не согл.</label>
													<select id="sel10" class='form-control {{$errors->has("reconciliation_agreement_reestr") ? print("inputError ") : print("")}}' name='reconciliation_agreement_reestr'>
														<option value='0'></option>
														<option value='1'>Согласовано</option>
														<option value='2'>Не согласовано</option>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="">
								<div class=''>
									<div class="">
										<div class="form-group">
											<label class='form-check-label' for='break'>ОТКАЗ</label>
											@if(old('renouncement_contract'))
												<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked />
											@else
												<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox"/>
											@endif
										</div>
										<div class="form-group">
											<label class='form-check-label' for='archive_contract'>АРХИВ</label>
											@if(old('archive_contract'))
												<input id='archive_contract' class='form-check-input' name='archive_contract' type="checkbox" checked />
											@else
												<input id='archive_contract' class='form-check-input' name='archive_contract' type="checkbox"/>
											@endif
										</div>
									</div>
									<div class=" ">
										<div class="form-group">
											<label for='document_success_renouncement_reestr'>Документ, подтверждающий отказ</label>
											<input id='document_success_renouncement_reestr' class='form-control {{$errors->has("document_success_renouncement_reestr") ? print("inputError ") : print("")}}' name='document_success_renouncement_reestr' value='{{old("document_success_renouncement_reestr")}}'/>
											@if($errors->has('document_success_renouncement_reestr'))
												<label class='msgError'>{{$errors->first('document_success_renouncement_reestr')}}</label>
											@endif
										</div>
									</div>
								</div>
								<div class=''>
									<div class="2  ">
										<div class="form-group">
											<label for='number_aftair_renouncement_reestr'>№ дела</label>
											<input id='number_aftair_renouncement_reestr' class='form-control {{$errors->has("number_aftair_renouncement_reestr") ? print("inputError ") : print("")}}' name='number_aftair_renouncement_reestr' value='{{old("number_aftair_renouncement_reestr")}}'/>
											@if($errors->has('number_aftair_renouncement_reestr'))
												<label class='msgError'>{{$errors->first('number_aftair_renouncement_reestr')}}</label>
											@endif
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class=''>
							<div class="   ">
								<div class="form-group">
									<label for="sel3">Вид договора</span></label>
									<select id="sel3" class='form-control {{$errors->has("id_view_contract") ? print("inputError ") : print("")}}' name='id_view_contract'>
										<option></option>
										@if($viewContracts)
											@foreach($viewContracts as $viewContract)
												@if(old('id_view_contract'))
													@if(old('id_view_contract') == $viewContract->id)
														<option value='{{$viewContract->id}}' selected>{{ $viewContract->name_view_contract }}</option>
													@else
														<option value='{{$viewContract->id}}'>{{ $viewContract->name_view_contract }}</option>
													@endif
												@else
													<option value='{{$viewContract->id}}'>{{ $viewContract->name_view_contract }}</option>
												@endif
											@endforeach
										@endif
									</select>
									@if($errors->has('id_view_contract'))
										<label class='msgError'>{{$errors->first('id_view_contract')}}</label>
									@endif
								</div>
							</div>
							<div class="   ">
								<div class="form-group">
									<label for="sel5">Отбор поставщика</span></label>
									<select id="sel5" class='form-control {{$errors->has("selection_supplier_reestr") ? print("inputError ") : print("")}}' name='selection_supplier_reestr'>
										<option></option>
										@foreach($selection_suppliers as $selection_supplier)
											@if(old('selection_supplier_reestr'))
												@if(old('selection_supplier_reestr') == $selection_supplier->id)
													<option value='{{$selection_supplier->id}}' selected>{{$selection_supplier->name_selection_supplier}}</option>
												@else
													<option value='{{$selection_supplier->id}}'>{{$selection_supplier->name_selection_supplier}}</option>
												@endif
											@else
												<option value='{{$selection_supplier->id}}'>{{$selection_supplier->name_selection_supplier}}</option>
											@endif
										@endforeach
									</select>
									@if($errors->has('selection_supplier_reestr'))
										<label class='msgError'>{{$errors->first('selection_supplier_reestr')}}</label>
									@endif
								</div>
							</div>
							<div class="   ">
								<div class="form-group">
									<label for='app_outgoing_number_reestr'>Заявка исх. №</label>
									<input id='app_outgoing_number_reestr' class='form-control {{$errors->has("app_outgoing_number_reestr") ? print("inputError ") : print("")}}' name='app_outgoing_number_reestr' value='{{old("app_outgoing_number_reestr")}}'/>
									@if($errors->has('app_outgoing_number_reestr'))
										<label class='msgError'>{{$errors->first('app_outgoing_number_reestr')}}</label>
									@endif
								</div>
							</div>
							<div class="   ">
								<div class="form-group">
									<label for='app_incoming_number_reestr'>Вх. №</label>
									<input id='app_incoming_number_reestr' class='form-control {{$errors->has("app_incoming_number_reestr") ? print("inputError ") : print("")}}' name='app_incoming_number_reestr' value='{{old("app_incoming_number_reestr")}}'/>
									@if($errors->has('app_incoming_number_reestr'))
										<label class='msgError'>{{$errors->first('app_incoming_number_reestr')}}</label>
									@endif
								</div>
							</div>
							<div class="   ">
								<div class="form-group">
									<label for='result_second_department_date_reestr'>Заключение отдела №2 Дата</label>
									<input id='result_second_department_date_reestr' class='datepicker form-control {{$errors->has("result_second_department_date_reestr") ? print("inputError ") : print("")}}' name='result_second_department_date_reestr' value='{{old("result_second_department_date_reestr")}}'/>
									@if($errors->has('result_second_department_date_reestr'))
										<label class='msgError'>{{$errors->first('result_second_department_date_reestr')}}</label>
									@endif
								</div>
							</div>
							<div class="   ">
								<div class="form-group">
									<label for='result_second_department_number_reestr'>№</label>
									<input id='result_second_department_number_reestr' class='form-control {{$errors->has("result_second_department_number_reestr") ? print("inputError ") : print("")}}' name='result_second_department_number_reestr' value='{{old("result_second_department_number_reestr")}}'/>
									@if($errors->has('result_second_department_number_reestr'))
										<label class='msgError'>{{$errors->first('result_second_department_number_reestr')}}</label>
									@endif
								</div>
							</div>
						</div>
						<div class=''>
							<input id='is_new_reestr' class='form-check-input' name='is_new_reestr' type="checkbox" checked style='display: none;'/>
							<div class="">
								<div class=" ">
									<div class=''>
										<div class="2">
											@if(old('marketing_reestr'))
												<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" checked />
											@else
												<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" />
											@endif
											<label for='marketing_reestr'>Сбыт</label>
										</div>
									</div>
									<div class=''>
										<div class="2">
											@if(old('marketing_goz_reestr'))
												<input id='marketing_goz_reestr' class='form-check-input' name='marketing_goz_reestr' type="checkbox" checked />
											@else
												<input id='marketing_goz_reestr' class='form-check-input' name='marketing_goz_reestr' type="checkbox" />
											@endif
											<label for='marketing_goz_reestr'>ГОЗ</label>
										</div>
									</div>
									<div class=''>
										<div class="2">
											@if(old('participation_reestr'))
												<input id='participation_reestr' class='form-check-input' name='participation_reestr' type="checkbox" checked />
											@else
												<input id='participation_reestr' class='form-check-input' name='participation_reestr' type="checkbox" />
											@endif
											<label for='participation_reestr'>Участие</label>
										</div>
									</div>
									<div class=''>
										<div class="2">
											@if(old('marketing_fz_223_reestr'))
												<input id='marketing_fz_223_reestr' class='form-check-input' name='marketing_fz_223_reestr' type="checkbox" checked />
											@else
												<input id='marketing_fz_223_reestr' class='form-check-input' name='marketing_fz_223_reestr' type="checkbox" />
											@endif
											<label for='marketing_fz_223_reestr'>223-ФЗ</label>
										</div>
									</div>
									<div class=''>
										<div class="2">
											@if(old('marketing_fz_44_reestr'))
												<input id='marketing_fz_44_reestr' class='form-check-input' name='marketing_fz_44_reestr' type="checkbox" checked />
											@else
												<input id='marketing_fz_44_reestr' class='form-check-input' name='marketing_fz_44_reestr' type="checkbox" />
											@endif
											<label for='marketing_fz_44_reestr'>44-ФЗ</label>
										</div>
									</div>
								</div>
								<div class="  ">
									<div class=''>
										<div class="2">
											@if(old('procurement_reestr'))
												<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" checked />
											@else
												<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" />
											@endif
											<label for='procurement_reestr'>Закуп</label>
										</div>
									</div>
									<div class=''>
										<div class="2">
											@if(old('single_provider_reestr'))
												<input id='single_provider_reestr' class='form-check-input' name='single_provider_reestr' type="checkbox" checked />
											@else
												<input id='single_provider_reestr' class='form-check-input' name='single_provider_reestr' type="checkbox" />
											@endif
											<label for='single_provider_reestr'>Единственный поставщик</label>
										</div>
									</div>
									<div class=''>
										<div class="2">
											@if(old('own_funds_reestr'))
												<input id='own_funds_reestr' class='form-check-input' name='own_funds_reestr' type="checkbox" checked />
											@else
												<input id='own_funds_reestr' class='form-check-input' name='own_funds_reestr' type="checkbox" />
											@endif
											<label for='own_funds_reestr'>Собственные средства</label>
										</div>
									</div>
									<div class=''>
										<div class="2">
											@if(old('investments_reestr'))
												<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" checked />
											@else
												<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" />
											@endif
											<label for='investments_reestr'>Инвестиции</label>
										</div>
									</div>
									<div class=''>
										<div class="2">
											@if(old('purchase_reestr'))
												<input id='purchase_reestr' class='form-check-input' name='purchase_reestr' type="checkbox" checked />
											@else
												<input id='purchase_reestr' class='form-check-input' name='purchase_reestr' type="checkbox" />
											@endif
											<label for='purchase_reestr'>Закупка у СМСП</label>
										</div>
									</div>
								</div>
								<div class="   ">
									<div class=''>
										<div class="2">
											@if(old('procurement_fz_223_reestr'))
												<input id='procurement_fz_223_reestr' class='form-check-input' name='procurement_fz_223_reestr' type="checkbox" checked />
											@else
												<input id='procurement_fz_223_reestr' class='form-check-input' name='procurement_fz_223_reestr' type="checkbox" />
											@endif
											<label for='procurement_fz_223_reestr'>223-ФЗ</label>
										</div>
									</div>
									<div class=''>
										<div class="2">
											@if(old('procurement_fz_44_reestr'))
												<input id='procurement_fz_44_reestr' class='form-check-input' name='procurement_fz_44_reestr' type="checkbox" checked />
											@else
												<input id='procurement_fz_44_reestr' class='form-check-input' name='procurement_fz_44_reestr' type="checkbox" />
											@endif
											<label for='procurement_fz_44_reestr'>44-ФЗ</label>
										</div>
									</div>
									<div class=''>
										<div class="2">
											@if(old('procurement_goz_reestr'))
												<input id='procurement_goz_reestr' class='form-check-input' name='procurement_goz_reestr' type="checkbox" checked />
											@else
												<input id='procurement_goz_reestr' class='form-check-input' name='procurement_goz_reestr' type="checkbox" />
											@endif
											<label for='procurement_goz_reestr'>ГОЗ</label>
										</div>
									</div>
									<div class=''>
										<div class="2">
											@if(old('other_reestr'))
												<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" checked />
											@else
												<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" />
											@endif
											<label for='other_reestr'>Иное</label>
										</div>
									</div>
									<div class=''>
										<div class="2">
											@if(old('mob_reestr'))
												<input id='mob_reestr' class='form-check-input' name='mob_reestr' type="checkbox" checked />
											@else
												<input id='mob_reestr' class='form-check-input' name='mob_reestr' type="checkbox" />
											@endif
											<label for='mob_reestr'>МОБ</label>
										</div>
									</div>
								</div>
							</div>
							<div class=" ">
								<div class="" style='text-align: center;'>
									<div class="2">
										<label>Согласование крупной сделки</label>
									</div>
								</div>
								<div class="">
									<div class="">
										<label for='number_inquiry_reestr'>Запрос №</label>
									</div>
									<div class="">
										<input id='number_inquiry_reestr' class='form-control {{$errors->has("number_inquiry_reestr") ? print("inputError ") : print("")}}' name='number_inquiry_reestr' value='{{old("number_inquiry_reestr")}}'/>
										@if($errors->has('number_inquiry_reestr'))
											<label class='msgError'>{{$errors->first('number_inquiry_reestr')}}</label>
										@endif
									</div>
									<div class="">
										<label for='date_inquiry_reestr'>от</label>
									</div>
									<div class="">
										<input id='date_inquiry_reestr' class='datepicker form-control {{$errors->has("date_inquiry_reestr") ? print("inputError ") : print("")}}' name='date_inquiry_reestr' value='{{old("date_inquiry_reestr")}}'/>
										@if($errors->has('date_inquiry_reestr'))
											<label class='msgError'>{{$errors->first('date_inquiry_reestr')}}</label>
										@endif
									</div>
									<div class="">
										<label for='days_reconciliation_reestr'>Срок действия согласования крупной сделки</label>
									</div>
									<div class="">
										<input id='days_reconciliation_reestr' class='form-control {{$errors->has("days_reconciliation_reestr") ? print("inputError ") : print("")}}' name='days_reconciliation_reestr' value='{{old("days_reconciliation_reestr")}}'/>
										@if($errors->has('days_reconciliation_reestr'))
											<label class='msgError'>{{$errors->first('days_reconciliation_reestr')}}</label>
										@endif
									</div>
								</div>
								<div class="">
									<div class="">
										<label for='number_answer_reestr'>Ответ №</label>
									</div>
									<div class="">
										<input id='number_answer_reestr' class='form-control {{$errors->has("number_answer_reestr") ? print("inputError ") : print("")}}' name='number_answer_reestr' value='{{old("number_answer_reestr")}}'/>
										@if($errors->has('number_answer_reestr'))
											<label class='msgError'>{{$errors->first('number_answer_reestr')}}</label>
										@endif
									</div>
									<div class="">
										<label for='date_answer_reestr'>от</label>
									</div>
									<div class="">
										<input id='date_answer_reestr' class='datepicker form-control {{$errors->has("date_answer_reestr") ? print("inputError ") : print("")}}' name='date_answer_reestr' value='{{old("date_answer_reestr")}}'/>
										@if($errors->has('date_answer_reestr'))
											<label class='msgError'>{{$errors->first('date_answer_reestr')}}</label>
										@endif
									</div>
									<div class="">
										<label for='count_mounth_reestr'>Количество месяцев</label>
									</div>
									<div class="">
										<input id='count_mounth_reestr' class='form-control {{$errors->has("count_mounth_reestr") ? print("inputError ") : print("")}}' name='count_mounth_reestr' value='{{old("count_mounth_reestr")}}'/>
										@if($errors->has('count_mounth_reestr'))
											<label class='msgError'>{{$errors->first('count_mounth_reestr')}}</label>
										@endif
									</div>
								</div>
								<div class=" ">
									<div class="">
										<label>Сроки согласования проекта договора исполнителей</label>
									</div>
									<div class="">
										<label for='begin_date_reconciliation_reestr'>Начало согласования (дата)</label>
										<input id='begin_date_reconciliation_reestr' class='datepicker form-control {{$errors->has("begin_date_reconciliation_reestr") ? print("inputError ") : print("")}}' name='begin_date_reconciliation_reestr' value='{{old("begin_date_reconciliation_reestr")}}'/>
										@if($errors->has('begin_date_reconciliation_reestr'))
											<label class='msgError'>{{$errors->first('begin_date_reconciliation_reestr')}}</label>
										@endif
									</div>
									<div class="">
										<label for='end_date_reconciliation_reestr'>Окончание согласования (дата)</label>
										<input id='end_date_reconciliation_reestr' class='datepicker form-control {{$errors->has("end_date_reconciliation_reestr") ? print("inputError ") : print("")}}' name='end_date_reconciliation_reestr' value='{{old("end_date_reconciliation_reestr")}}'/>
										@if($errors->has('end_date_reconciliation_reestr'))
											<label class='msgError'>{{$errors->first('end_date_reconciliation_reestr')}}</label>
										@endif
									</div>
									<div class="">
										<label for='count_days_reconciliation_reestr'>Общее количество дней согласования</label>
										<input id='count_days_reconciliation_reestr' class='form-control {{$errors->has("count_days_reconciliation_reestr") ? print("inputError ") : print("")}}' name='count_days_reconciliation_reestr' value='{{old("count_days_reconciliation_reestr")}}'/>
										@if($errors->has('count_days_reconciliation_reestr'))
											<label class='msgError'>{{$errors->first('count_days_reconciliation_reestr')}}</label>
										@endif
									</div>
								</div>
							</div>
						</div>
						<div class="">
							<div class="">
								<div class="form-group">
									<label for='miniCounterpartie'>Полное наименование контрагента</label>
									<input id='miniCounterpartie' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' type='text' value='' readonly />
								</div>
							</div>
							<div class="">
								<label>ИНН</label>
								<input class='form-control' value='' readonly />
							</div>
						</div>
						<div class=" ">
							<div class="2">
								<div class=''>
									<div class="">
										<div class="form-group">
											<label for='number_counterpartie_contract_reestr'>№ дог. контрагента</label>
											<input id='number_counterpartie_contract_reestr' class='form-control {{$errors->has("number_counterpartie_contract_reestr") ? print("inputError ") : print("")}}' name='number_counterpartie_contract_reestr' value='{{old("number_counterpartie_contract_reestr")}}'/>
											@if($errors->has('number_counterpartie_contract_reestr'))
												<label class='msgError'>{{$errors->first('number_counterpartie_contract_reestr')}}</label>
											@endif
										</div>
									</div>
									<div class="">
										<div class="form-group">
											<label for='igk_reestr'>ИГК</label>
											<input id='igk_reestr' class='form-control {{$errors->has("igk_reestr") ? print("inputError ") : print("")}}' name='igk_reestr' value='{{ old("igk_reestr") }}'/>
										</div>
									</div>
									<div class="">
										<div class="form-group">
											<label for='ikz_reestr'>ИКЗ</label>
											<input id='ikz_reestr' class='form-control {{$errors->has("ikz_reestr") ? print("inputError ") : print("")}}' name='ikz_reestr' value='{{ old("ikz_reestr") }}'/>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="">
							<div class=" ">
								<div class="">
									<div class="2">
										<div class='form-group'>
											<label for='itemContract'>Предмет договора/контракта</label>
											<textarea id='itemContract' class='form-control {{$errors->has("item_contract") ? print("inputError ") : print("")}}' name='item_contract' type="text" style="width: 100%;" rows='4'>{{ old('item_contract') }}</textarea>
											@if($errors->has('item_contract'))
												<label class='msgError'>{{$errors->first('item_contract')}}</label>
											@endif
										</div>
									</div>
									<div class="2">
										<label for='okpd_2_reestr'>ОКПД 2</label>
										<input id='okpd_2_reestr' class='form-control {{$errors->has("okpd_2_reestr") ? print("inputError ") : print("")}}' name='okpd_2_reestr' value='{{ old("okpd_2_reestr") }}'/>
									</div>
									<div class="2">
										<div class='form-group'>
											<label for='nameWork'>Цель заключения Дог./Контр.</label>
											<textarea id='nameWork' class='form-control {{$errors->has("name_work_contract") ? print("inputError ") : print("")}}' name='name_work_contract' type="text" style="width: 100%;" rows='4'>{{ old('name_work_contract') }}</textarea>
											@if($errors->has('name_work_contract'))
												<label class='msgError'>{{$errors->first('name_work_contract')}}</label>
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class=" ">
								<div class="">
									<div class="2">
										<label>Гарантия банка</label>
									</div>
									<div class="">
										<div class=''>
											<div class="">
												<label for='date_bank_reestr'>До</label>
											</div>
											<div class="0">
												<input id='date_bank_reestr' class='datepicker form-control {{$errors->has("date_bank_reestr") ? print("inputError ") : print("")}}' name='date_bank_reestr' value='{{ old("date_bank_reestr") }}'/>
											</div>
										</div>
									</div>
									<div class="">
										<div class=''>
											<div class="">
												<label for='amount_bank_reestr'>Сумма</label>
											</div>
											<div class="">
												<input id='amount_bank_reestr' class='form-control check-number {{$errors->has("amount_bank_reestr") ? print("inputError ") : print("")}}' name='amount_bank_reestr' value='{{ old("amount_bank_reestr") }}'/>
											</div>
											<div class="">
												<label for='bank_reestr'>Банк</label>
											</div>
											<div class="">
												<input id='bank_reestr' class='form-control {{$errors->has("bank_reestr") ? print("inputError ") : print("")}}' name='bank_reestr' value='{{ old("bank_reestr") }}'/>
											</div>
										</div>
									</div>
									<div class=''>
										<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button' style='float: right; width: 184px;'>Скан</button>
									</div>
								</div>
								<div class=" ">
									<div class="">
										<div class=''>
											<div class="2">
												<label for='date_contract_reestr'>Срок действия договора</label>
											</div>
										</div>
									</div>
									<div class="">
										<div class=''>
											<div class="">
												<label for='date_b_contract_reestr'>с</label>
											</div>
											<div class="">
												<input id='date_b_contract_reestr' class='datepicker form-control {{$errors->has("date_b_contract_reestr") ? print("inputError ") : print("")}}' name='date_b_contract_reestr' value='{{ old("date_b_contract_reestr") }}'/>
											</div>
											<div class="">
												<label for='date_e_contract_reestr'>по</label>
											</div>
											<div class="">
												<input id='date_e_contract_reestr' class='datepicker form-control {{$errors->has("date_e_contract_reestr") ? print("inputError ") : print("")}}' name='date_e_contract_reestr' value='{{ old("date_e_contract_reestr") }}'/>
											</div>
										</div>
									</div>
								</div>
								<div class='row '>
									<div class="0">
										<div class="form-group">
											<input id='date_contract_reestr' class='form-control {{$errors->has("date_contract_reestr") ? print("inputError ") : print("")}}' name='date_contract_reestr' value='{{ old("date_contract_reestr") }}'/>
										</div>
									</div>
								</div>
								<div class=" ">
									<div class="0">
										<div class="form-group">
											<label for='date_maturity_reestr'>Срок исполнения обязательств</label>
											<input id='date_maturity_reestr' class='form-control {{$errors->has("date_maturity_reestr") ? print("inputError ") : print("")}}' name='date_maturity_reestr' value='{{ old("date_maturity_reestr") }}'/>
										</div>
									</div>
								</div>
								<div class="">
									<div class="0">
										<div class=''>
											<div class="">
												<label for='amount_reestr'>Сумма</label>
											</div>
											<div class="">
												<input id='amount_reestr' class='form-control check-number {{$errors->has("amount_reestr") ? print("inputError ") : print("")}}' name='amount_reestr' value='{{old("amount_reestr") }}'/>
											</div>
											<div class="">
												<select id="sel8" class='form-control {{$errors->has("unit_reestr") ? print("inputError ") : print("")}}' name='unit_reestr'>
													<option></option>
													@foreach($units as $unit)
														@if(old('unit_reestr'))
															<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
														@else
															<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
														@endif
													@endforeach
												</select>
											</div>
											<div class="">
												<label for='VAT'>НДС</label>
												@if(old('vat_reestr'))
													<input id='VAT' class='form-check-input' name='vat_reestr' type="checkbox" checked />
												@else
													<input id='VAT' class='form-check-input' name='vat_reestr' type="checkbox"/>
												@endif
											</div>
											<div class="">
												<label for='approximate_amount_reestr'>Ориентировочная</label>
												@if(old('approximate_amount_reestr'))
													<input id='approximate_amount_reestr' class='form-check-input' name='approximate_amount_reestr' type="checkbox" checked />
												@else
													<input id='approximate_amount_reestr' class='form-check-input' name='approximate_amount_reestr' type="checkbox"/>
												@endif
											</div>
										</div>
										<div class=''>
											<div class="2">
												<input class='form-control {{$errors->has("amount_comment_reestr") ? print("inputError ") : print("")}}' name='amount_comment_reestr' value='{{old("amount_comment_reestr") }}'/>
											</div>
										</div>
									</div>
								</div>
								<div class=''>
									<div class="">
										<div class=''>
											<div class="">
												<label for='nmcd_reestr'>НМЦД/НМЦК</label>
											</div>
											<div class="">
												<input id='nmcd_reestr' class='form-control check-number {{$errors->has("nmcd_reestr") ? print("inputError ") : print("")}}' name='nmcd_reestr' value='{{old("nmcd_reestr") }}'/>
											</div>
											<div class="">
												<select class='form-control {{$errors->has("nmcd_unit_reestr") ? print("inputError ") : print("")}}' name='nmcd_unit_reestr'>
													<option></option>
													@foreach($units as $unit)
														@if(old('nmcd_unit_reestr'))
															<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
														@else
															<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
														@endif
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="">
										<div class=''>
											<div class="">
												<label for='economy_reestr'>Экономия</label>
											</div>
											<div class="">
												<input id='economy_reestr' class='form-control check-number {{$errors->has("economy_reestr") ? print("inputError ") : print("")}}' name='economy_reestr' value='{{old("economy_reestr") }}'/>
											</div>
											<div class="">
												<select class='form-control {{$errors->has("economy_unit_reestr") ? print("inputError ") : print("")}}' name='economy_unit_reestr'>
													<option></option>
													@foreach($units as $unit)
														@if(old('economy_unit_reestr'))
															<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
														@else
															<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
														@endif
													@endforeach
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class=''>
									<div class="0">
										<label>Порядок оплаты</label>
									</div>
								</div>
								<div class="">
									<div class="0">
										<div class=''>
											<div class="">
												<label>Аванс</label>
												<textarea id='prepayment_order_reestr' class='form-control' type="text" style="width: 100%;" rows='2' name='prepayment_order_reestr'>{{old("prepayment_order_reestr") }}</textarea>
											</div>
											<div class="">
												<label>Окончат. расчет</label>
												<textarea id='score_order_reestr' class='form-control' type="text" style="width: 100%;" rows='2' name='score_order_reestr'>{{old("score_order_reestr") }}</textarea>
											</div>
										</div>
										<div class=''>
											<div class="2">
												<label>Иное</label>
												<textarea id='payment_order_reestr' class='form-control' type="text" style="width: 100%;" rows='2' name='payment_order_reestr'>{{old("payment_order_reestr") }}</textarea>
											</div>
										</div>
									</div>
								</div>
								<div class="">
									<div class="">
										<label for='prolongation_reestr'>Пролонгация</label>
										@if(old('prolongation_reestr'))
											<input id='prolongation_reestr' class='form-check-input' type="checkbox" name='prolongation_reestr' checked />
										@else
											@if($reestr->prolongation_reestr)
												<input id='prolongation_reestr' class='form-check-input' type="checkbox" name='prolongation_reestr' checked />
											@else
												<input id='prolongation_reestr' class='form-check-input' type="checkbox" name='prolongation_reestr'/>
											@endif
										@endif
									</div>
								</div>
							</div>
						</div>
						<div class="">
							<div class="">
								
							</div>
							<div class="">
								
							</div>
							<div class="">
								
							</div>
							<div class="">
								
							</div>
							<div class="">
								@if(Auth::User()->hasRole()->role != 'Администрация')
									<button type='submit' class="btn btn-primary" style="float: right;">Сохранить договор</button>
								@endif
							</div>
						</div>
						<div class="">
						</div>
						<!-- Модальное окно резолюции -->
						<div class="modal fade" id="scan" tabindex="-1" role="dialog" aria-labelledby="scanModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class='modal-body'>
										<div class=''>
											<div class='col-md-6' style='display: none;'>
												<input type='text' value='id_contract_resolution' name='real_name_document'/>
											</div>
											<div class='col-md-6'>
												<input id='new_file_resolution' type='file' name='new_file_resolution' value='D:\'/>
											</div>
											<div class='col-md-6'>
												<input id='date_resolution' name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
											</div>
										</div>
										<div class=''>
											<div class='col-md-12'>
												<label>Наименование резолюции</label>
												<input id='real_name_resolution' type='text' name='real_name_resolution' class='form-control'/>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-primary" data-dismiss="modal">Принять</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
				<!-- Модальное окно выбора контрагента -->
				<div class="modal fade" id="chose_counterpartie" tabindex="-1" role="dialog" aria-labelledby="choseCounterpartieModalLabel" aria-hidden="true" attr-open-modal="@if(\Session::has('search_counterparties')){{print('open')}}@endif">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<form method='POST' action="{{route('department.reestr.search_counterpartie')}}">
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="choseCounterpartieModalLabel">Выбор контрагента</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									@includeif('layouts.search', ['search_arr_value'=>['name'=>'Контрагент','name_full'=>'Полное наименование','inn'=>'ИНН']])
									@if(\Session::has('search_counterparties'))
										<div class="">
											<div class="2">
												<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
													<thead>
														<tr>
															<th>Контрагент</th>
															<th>Полное наименование</th>
															<th>Выбрать</th>
														</tr>
													</thead>
													<tbody>
														@foreach(\Session::get('search_counterparties') as $counterpartie)
															<tr class='rowsContract'>
																<td>{{$counterpartie->name}}</td>
																<td>{{$counterpartie->name_full}}</td>
																<td><button type='button' class='btn btn-primary chose-counterpartie' type='button' id_counterpartie='{{$counterpartie->id}}'>Выбрать</button></td>
															</tr>
														@endforeach
													</tbody>
												</table>
											</div>
										</div>
									@endif
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<script>
					if($('#chose_counterpartie').attr('attr-open-modal') == 'open1')
						$('#chose_counterpartie').modal('show');
				</script>
			@endif
		@else
			<div class="alert alert-danger">
				Необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection