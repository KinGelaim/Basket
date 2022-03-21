@extends('layouts.header')

@section('title')
	Оперативный учет договоров
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								Согласование
							</div>
							<div class="panel-body" style='max-height: 700px; overflow-y: auto;'>
								<ul class='nav nav-tabs'>
									<li class='active'>
										<a data-toggle='tab' href='#application'>Письма 									
											<?php
												//$count_message = App\ReconciliationUser::select('reconciliation_users.id')->join('applications','id_application', 'applications.id')->where('id_user',Auth::user()->id)->where('check_reconciliation', 0)->where('applications.is_protocol', 0)->where('applications.deleted_at', null)->get()->count();
												$count_message = count($new_my_applications);
												if($count_message)
													echo "<span class='badge badge-pill badge-danger'>" . $count_message . "</span>";
											?>
										</a>
									</li>
									<li>
										<a data-toggle='tab' href='#new_applications'>Заявки 									
											<?php
												//$count_message = App\ReconciliationUser::select('reconciliation_users.id')->join('applications','id_application', 'applications.id')->where('id_user',Auth::user()->id)->where('check_reconciliation', 0)->where('applications.is_protocol', 0)->where('applications.deleted_at', null)->get()->count();
												$count_message = count($new_applications);
												if($count_message)
													echo "<span class='badge badge-pill badge-danger'>" . $count_message . "</span>";
											?>
										</a>
									</li>
									<li>
										<a data-toggle='tab' href='#contract'>Договоры
											<?php
												$count_message = App\ReconciliationUser::select('reconciliation_users.id')->join('contracts','id_contract', 'contracts.id')->where('id_user',Auth::user()->id)->where('check_reconciliation', 0)->where('contracts.deleted_at', null)->get()->count();
												if($count_message)
													echo "<span class='badge badge-pill badge-danger'>" . $count_message . "</span>";
											?>
										</a>
									</li>
									<li>
										<a data-toggle='tab' href='#protocol'>Протоколы/ДС
											<?php
												//$count_message = App\ReconciliationUser::select('reconciliation_users.id')->join('applications','id_application', 'applications.id')->where('id_user',Auth::user()->id)->where('check_reconciliation', 0)->where('applications.is_protocol', 1)->where('applications.deleted_at', null)->get()->count();
												$count_message = App\ReconciliationProtocol::select('reconciliation_protocols.id')->where('id_user',Auth::user()->id)->where('check_reconciliation', null)->get()->count();
												if($count_message)
													echo "<span class='badge badge-pill badge-danger'>" . $count_message . "</span>";
											?>
										</a>
									</li>
									@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел')
										<!--<li>
											<a data-toggle='tab' href='#checkpoint'>Контрольные точки
												<?php
													//$count_message = count($red_checkpoints);
													//if($count_message > 0)
													//	echo "<span class='badge badge-pill badge-danger'>" . $count_message . "</span>";
												?>
											</a>
										</li>-->
									@endif
									@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Десятый отдел')
										<li>
											<a data-toggle='tab' href='#components'>Комплектующие
												<?php
													$count_message = count($new_my_components);
													if($count_message > 0)
														echo "<span class='badge badge-pill badge-danger'>" . $count_message . "</span>";
												?>
											</a>
										</li>
									@endif
									@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->surname == 'Бастрыкова')
										<li>
											<a data-toggle='tab' href='#new_additional_documents'>Новые договорные материалы</a>
										</li>
									@endif
									@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
										<li>
											<a data-toggle='tab' href='#new_scans_documents'>Новые сканы документов</a>
										</li>
									@endif
								</ul>
								<div class='tab-content'>
									<div id='application' class='tab-pane fade in active'>
										<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
											<thead>
												<tr>
													<th style='min-width: 60px;'></th>
													<th class='cursorPointer btn-href' style='min-width: 100px;' href='{{ route("home") }}?sorting=number_application&sort_p={{$re_sort}}'>№ записи<span>{{ $sort == 'number_application' ? $sort_span : ''}}</span></th>
													<th class='cursorPointer btn-href' style='min-width: 76px;' href='{{ route("home") }}?sorting=number_outgoing&sort_p={{$re_sort}}'>Исх №<span>{{$sort == 'number_outgoing' ? $sort_span : ''}}</span></th>
													<th class='cursorPointer btn-href' style='min-width: 90px;' href='{{ route("home") }}?sorting=date_outgoing&sort_p={{$re_sort}}'>Дата<span>{{$sort == 'date_outgoing' ? $sort_span : ''}}</span></th>
													<th class='cursorPointer btn-href' style='min-width: 68px;' href='{{ route("home") }}?sorting=number_incoming&sort_p={{$re_sort}}'>Вх №<span>{{$sort == 'number_incoming' ? $sort_span : ''}}</span></th>
													<th class='cursorPointer btn-href' style='min-width: 90px;' href='{{ route("home") }}?sorting=date_incoming&sort_p={{$re_sort}}'>Дата<span>{{$sort == 'date_incoming' ? $sort_span : ''}}</span></th>
													<th>Контрагент</th>
													<th>Тема</th>
												</tr>
											</thead>
											<tbody>
												@foreach($new_my_applications as $application)
													<tr class="rowsContract cursorPointer btn-href" href='{{route("reconciliation.application", $application->id)}}'>
														<td style='color: red;'>Новое</td>
														<td>
															{{ $application->number_application }}
														</td>
														<td>
															{{ $application->number_outgoing }}
														</td>
														<td>
															{{ $application->date_outgoing ? date('d.m.Y', strtotime($application->date_outgoing)) : '' }}
														</td>
														<td>
															{{ $application->number_incoming }}
														</td>
														<td>
															{{ $application->date_incoming ? date('d.m.Y', strtotime($application->date_incoming)) : '' }}
														</td>
														<td>
															{{ $application->counterpartie_name }}
														</td>
														<td>
															{{ $application->theme_application }}
														</td>
													</tr>
												@endforeach
												@foreach($my_applications as $application)
													<tr class="rowsContract cursorPointer btn-href" href='{{route("reconciliation.application", $application->id)}}'>
														<td></td>
														<td>
															{{ $application->number_application }}
														</td>
														<td>
															{{ $application->number_outgoing }}
														</td>
														<td>
															{{ $application->date_outgoing ? date('d.m.Y', strtotime($application->date_outgoing)) : '' }}
														</td>
														<td>
															{{ $application->number_incoming }}
														</td>
														<td>
															{{ $application->date_incoming ? date('d.m.Y', strtotime($application->date_incoming)) : '' }}
														</td>
														<td>
															{{ $application->counterpartie_name }}
														</td>
														<td>
															{{ $application->theme_application }}
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
									<div id='new_applications' class='tab-pane fade'>
										<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
											<thead>
												<tr>
													<th style='min-width: 60px;'></th>
													<th>№ записи</th>
													<th>Контрагент</th>
													<th>Предмет (содержание заявки)</th>
													<th>Цель (если указана)</th>
												</tr>
											</thead>
											<tbody>
												@foreach($new_applications as $new_application)
													<tr class="rowsContract cursorPointer btn-href" href='{{route("new_applications.reconciliation", $new_application->appID)}}'>
														<td style='color: red;'>Новое</td>
														<td>
															{{ $new_application->number_pp_new_application }}
														</td>
														<td>
															{{ $new_application->counterpartie_name }}
														</td>
														<td>
															{{ $new_application->item_new_application }}
														</td>
														<td>
															{{ $new_application->name_work_new_application }}
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
									<div id='contract' class='tab-pane fade'>
										<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
											<thead>
												<tr>
													<th></th>
													<th>№ договора</th>
													<th>Вид договора</th>
													<th>Предмет договора</th>
													<th>Сумма</th>
													<th>Срок</th>
													<th>Контрагент</th>
												</tr>
											</thead>
											<tbody>
												@foreach($new_my_contracts as $contract)
													<tr class="rowsContract cursorPointer btn-href" href='{{route("reconciliation.contract", $contract->id)}}'>
														<td style='color: red;'>Новый</td>
														<td>
															{{ $contract->number_contract }}
														</td>
														<td>
															{{ $contract->name_view_work }}
														</td>
														<td>
															{{ $contract->name_work_contract }}
														</td>
														<td>
															{{ $contract->amount_contract_reestr }}
														</td>
														<td>
															{{ $contract->date_maturity_date_reestr }}
														</td>
														<td>
															{{ $contract->counterpartie_name }}
														</td>
													</tr>
												@endforeach
												@foreach($my_contracts as $contract)
													<tr class="rowsContract cursorPointer btn-href" href='{{route("reconciliation.contract", $contract->id)}}'>
														<td></td>
														<td>
															{{ $contract->number_contract }}
														</td>
														<td>
															{{ $contract->name_view_work }}
														</td>
														<td>
															{{ $contract->name_work_contract }}
														</td>
														<td>
															{{ $contract->amount_contract_reestr }}
														</td>
														<td>
															{{ $contract->date_maturity_date_reestr }}
														</td>
														<td>
															{{ $contract->counterpartie_name }}
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
									<!--<div id='checkpoint' class='tab-pane fade'>
										<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
											<thead>
												<tr>
													<th>№ договора</th>
													<th>Дата</th>
													<th>Результат</th>
													<th>Исполнитель</th>
												</tr>
											</thead>
											<tbody>
												@foreach($red_checkpoints as $checkpoint)
													<tr class="rowsContract cursorPointer btn-href" href='{{route("department.reconciliation.show", $checkpoint->contractID)}}'>
														<td>
															{{ $checkpoint->number_contract }}
														</td>
														<td>
															{{ $checkpoint->date_checkpoint }}
														</td>
														<td>
															{{ $checkpoint->message_checkpoint }}
														</td>
														<td>
															{{ $checkpoint->curator_name }}
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>-->
									<div id='protocol' class='tab-pane fade'>
										<!--<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
											<thead>
												<tr>
													<th style='min-width: 60px;'></th>
													<th class='cursorPointer btn-href' style='min-width: 100px;' href='{{ route("home") }}?sorting=number_application&sort_p={{$re_sort}}'>№ записи<span>{{ $sort == 'number_application' ? $sort_span : ''}}</span></th>
													<th class='cursorPointer btn-href' style='min-width: 76px;' href='{{ route("home") }}?sorting=number_outgoing&sort_p={{$re_sort}}'>Исх №<span>{{$sort == 'number_outgoing' ? $sort_span : ''}}</span></th>
													<th class='cursorPointer btn-href' style='min-width: 90px;' href='{{ route("home") }}?sorting=date_outgoing&sort_p={{$re_sort}}'>Дата<span>{{$sort == 'date_outgoing' ? $sort_span : ''}}</span></th>
													<th class='cursorPointer btn-href' style='min-width: 68px;' href='{{ route("home") }}?sorting=number_incoming&sort_p={{$re_sort}}'>Вх №<span>{{$sort == 'number_incoming' ? $sort_span : ''}}</span></th>
													<th class='cursorPointer btn-href' style='min-width: 90px;' href='{{ route("home") }}?sorting=date_incoming&sort_p={{$re_sort}}'>Дата<span>{{$sort == 'date_incoming' ? $sort_span : ''}}</span></th>
													<th>Контрагент</th>
													<th>Тема</th>
												</tr>
											</thead>
											<tbody>
												@foreach($new_my_protocols as $protocol)
													<tr class="rowsContract cursorPointer btn-href" href='{{route("reconciliation.application", $protocol->id)}}'>
														<td style='color: red;'>Новое</td>
														<td>
															{{ $protocol->number_application }}
														</td>
														<td>
															{{ $protocol->number_outgoing }}
														</td>
														<td>
															{{ $protocol->date_outgoing ? date('d.m.Y', strtotime($protocol->date_outgoing)) : '' }}
														</td>
														<td>
															{{ $protocol->number_incoming }}
														</td>
														<td>
															{{ $protocol->date_incoming ? date('d.m.Y', strtotime($protocol->date_incoming)) : '' }}
														</td>
														<td>
															{{ $protocol->counterpartie_name }}
														</td>
														<td>
															{{ $protocol->theme_application }}
														</td>
													</tr>
												@endforeach
												@foreach($my_protocols as $protocol)
													<tr class="rowsContract cursorPointer btn-href" href='{{route("reconciliation.application", $protocol->id)}}'>
														<td></td>
														<td>
															{{ $protocol->number_application }}
														</td>
														<td>
															{{ $protocol->number_outgoing }}
														</td>
														<td>
															{{ $protocol->date_outgoing ? date('d.m.Y', strtotime($protocol->date_outgoing)) : '' }}
														</td>
														<td>
															{{ $protocol->number_incoming }}
														</td>
														<td>
															{{ $protocol->date_incoming ? date('d.m.Y', strtotime($protocol->date_incoming)) : '' }}
														</td>
														<td>
															{{ $protocol->counterpartie_name }}
														</td>
														<td>
															{{ $protocol->theme_application }}
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>-->
										<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
											<thead>
												<tr>
													<th style='min-width: 60px;'></th>
													<th>Заявка</th>
													<th>Наименование протокола</th>
													<th>Наименование работ</th>
													<th>Сроки проведения</th>
												</tr>
											</thead>
											<tbody>
												@foreach($new_my_additional_documents as $protocol)
													<tr class="rowsContract cursorPointer btn-href" href='{{route("reconciliation.additional_document.show", $protocol->protocolID)}}'>
														<td style='color: red;'>Новое</td>
														<td>
															{{ $protocol->application_protocol }}
														</td>
														<td>
															{{ $protocol->name_protocol }}
														</td>
														<td>
															{{ $protocol->name_work_protocol }}
														</td>
														<td>
															{{ $protocol->date_protocol }}
														</td>
													</tr>
												@endforeach
												@foreach($my_additional_documents as $protocol)
													<tr class="rowsContract cursorPointer btn-href" href='{{route("reconciliation.additional_document.show", $protocol->protocolID)}}'>
														<td></td>
														<td>
															{{ $protocol->application_protocol }}
														</td>
														<td>
															{{ $protocol->name_protocol }}
														</td>
														<td>
															{{ $protocol->name_work_protocol }}
														</td>
														<td>
															{{ $protocol->date_protocol }}
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
									<div id='components' class='tab-pane fade'>
										<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
											<thead>
												<tr>
													<th></th>
													<th>№ записи</th>
													<th>Контрагент</th>
													<th>Тема</th>
												</tr>
											</thead>
											<tbody>
												@foreach($new_my_components as $component)
													<tr class="rowsContract cursorPointer btn-href" href='{{route("ten.document_components", $component->id_document)}}'>
														<td style='color: red;'>Новое</td>
														<td>
															{{ $component->number_application }}
														</td>
														<td>
															{{ $component->counterpartie_name }}
														</td>
														<td>
															{{ $component->theme_application }}
														</td>
													</tr>
												@endforeach
												@foreach($my_components as $component)
													<tr class="rowsContract cursorPointer btn-href" href='{{route("ten.document_components", $component->id_document)}}'>
														<td></td>
														<td>
															{{ $component->number_application }}
														</td>
														<td>
															{{ $component->counterpartie_name }}
														</td>
														<td>
															{{ $component->theme_application }}
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
									<div id='new_additional_documents' class='tab-pane fade'>
										<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
											<thead>
												<tr>
													<th>Номер договора</th>
													<th>Наименование</th>
													<th>Дата на первом листе</th>
													<th>Дата регистрации</th>
													<th>Дата подписания ФКП "НТИИМ"</th>
													<th>Дата подписания контрагентом</th>
												</tr>
											</thead>
											<tbody>
												@foreach($new_additional_documents as $in_new_additional_document)
													<tr class="rowsContract cursorPointer btn-href" href="{{ route('department.ekonomic.contract_new_reestr',$in_new_additional_document->id_contract) }}">
														<td>
															{{ $in_new_additional_document->number_contract }}
														</td>
														<td>
															{{ $in_new_additional_document->name_protocol }}
														</td>
														<td>
															{{ $in_new_additional_document->date_on_first_protocol }}
														</td>
														<td>
															{{ $in_new_additional_document->date_registration_protocol }}
														</td>
														<td>
															{{ $in_new_additional_document->date_signing_protocol }}
														</td>
														<td>
															{{ $in_new_additional_document->date_signing_counterpartie_protocol }}
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
									<div id='new_scans_documents' class='tab-pane fade'>
										<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
											<thead>
												<tr>
													<th>Автор добавления</th>
													<th>Номер договора</th>
													<th>Наименование</th>
													<th>Дата добавления скана</th>
													<th>Дата удаления скана</th>
												</tr>
											</thead>
											<tbody>
												@foreach($new_scans_documents as $is_new_scans_documents)
													<tr class="rowsContract cursorPointer btn-href" href="{{ $is_new_scans_documents->id_contract ? route('department.ekonomic.contract_new_reestr',$is_new_scans_documents->id_contract) : route('department.ekonomic.contract_new_reestr',$is_new_scans_documents->id_contract_in_protocol) }}">
														<td>
															{{ $is_new_scans_documents->surname }} {{ $is_new_scans_documents->name }} {{ $is_new_scans_documents->patronymic }}
														</td>
														<td>
															{{ $is_new_scans_documents->is_protocol==null && $is_new_scans_documents->is_additional_agreement==null ? $is_new_scans_documents->number_contract : 'ПР/ДС' }}
														</td>
														<td>
															{{ $is_new_scans_documents->real_name_resolution }}
														</td>
														<td>
															{{ $is_new_scans_documents->created_at }}
														</td>
														<td>
															{{ $is_new_scans_documents->deleted_at }}
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		@else
			<div class="alert alert-danger">
				Для просмотра данной страницы необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection