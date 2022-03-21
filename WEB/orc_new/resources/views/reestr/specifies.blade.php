@extends('layouts.header')

@section('title')
	Спецификации
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->surname == 'Филиппова' OR Auth::User()->surname == 'Бастрыкова' OR Auth::User()->surname == 'Едемская' OR Auth::User()->surname == 'Морозова' OR Auth::User()->surname == 'Логунова')
				<div class="container">
					<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
						<thead>
							<tr>
								<th>Номер спецификации</th>
								<th>Дата</th>
								<th>Сумма</th>
								<th>НДС</th>
								<th>НДС</th>
								<th>Примечание</th>
								@if(Auth::User()->hasRole()->role != 'Администрация')
									<th>Редактирование</th>
								@endif
							</tr>
						</thead>
						<tbody>
							<?php $all_amount = 0; ?>
							@foreach($specifies as $specify)
								<tr class='rowsContract'>
									<td>{{$specify->number}}</td>
									<td>{{$specify->date}}</td>
									<td>{{$specify->amount}}</td>
									<td>{{$specify->amount_vat}}</td>
									<td><?php if($specify->vat) echo "<input class='form-check-input' type='checkbox' disabled checked />"; else echo "<input class='form-check-input' type='checkbox' disabled />"; ?></td>
									<td>{{$specify->comment}}</td>
									@if(Auth::User()->hasRole()->role != 'Администрация')
										<td><button type='button' data-toggle="modal" data-target="#edit_amount_invoice"  class='btn btn-primary' onclick="$('#edit_amount_invoice form').attr('action', '{{route('department.reestr.update_specify', $specify->id)}}');
																																							$('#edit_amount_invoice input[name=number]').val('{{$specify->number}}');
																																							$('#edit_amount_invoice input[name=date]').val('{{$specify->date}}');
																																							$('#edit_amount_invoice input[name=amount]').val('{{$specify->amount}}');
																																							$('#edit_amount_invoice input[name=amount_vat]').val('{{$specify->amount_vat}}');
																																							if({{$specify->vat}}) $('#edit_amount_invoice input[name=vat]').prop('checked', true); else $('#edit_amount_invoice input[name=vat]').prop('checked', false);
																																							$('#edit_amount_invoice input[name=comment]').val('{{$specify->comment}}');">Редактировать спецификацию</button></td>
									@endif
								</tr>
								<?php
									$pr_amount = str_replace(' ', '', $specify->amount);
									if(is_numeric($pr_amount))
										$all_amount += $pr_amount;
								?>
							@endforeach
							<tr>
								<td></td>
								<td style='text-align: right;'>Итого:</td>
								<td>{{is_numeric($all_amount) ? number_format($all_amount, 2, '.', ' ') : $all_amount}}</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</tbody>
					</table>
					@if(Auth::User()->hasRole()->role != 'Администрация')
						<button type='button' data-toggle="modal" data-target="#new_amount_invoice"  class='btn btn-primary'>Добавить спецификацию</button>
					@endif
				</div>
				<!-- Модальное окно новой спецификации -->
				<div class="modal fade" id="new_amount_invoice" tabindex="-1" role="dialog" aria-labelledby="new_amount_invoiceModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form method='POST' action='{{route("department.reestr.store_specify",$id_contract)}}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="new_amount_invoiceModalLabel">Добавление спецификации</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
										<div class="col-md-4">
											<label>Номер спецификации:</label>
										</div>
										<div class="col-md-8">
											<input class='form-control' name='number' type='text' value='' required/>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-4">
											<label>Дата:</label>
										</div>
										<div class="col-md-8">
											<input class='datepicker form-control' name='date' type='text' value='' required/>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-4">
											<label>Сумма:</label>
										</div>
										<div class="col-md-8">
											<input class='form-control check-number' name='amount' type='text' value='' required/>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-4">
											<label>НДС</label>
										</div>
										<div class="col-md-8">
											<input class='form-control check-number' name='amount_vat' type='text' value=''/>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-4">
											<label>НДС</label>
										</div>
										<div class="col-md-8">
											<input class='form-check-input' name='vat' type="checkbox"/>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-4">
											<label>Примечание</label>
										</div>
										<div class="col-md-8">
											<input class='form-control' name='comment' type='text' value=''/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' type='button'>Добавить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Модальное окно редактирования спецификации -->
				<div class="modal fade" id="edit_amount_invoice" tabindex="-1" role="dialog" aria-labelledby="edit_amount_invoiceModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form id='formToUpdateProtocol' method='POST' action=''>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="edit_amount_invoiceModalLabel">Редактирование спецификации</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
										<div class="col-md-4">
											<label>Номер спецификации:</label>
										</div>
										<div class="col-md-8">
											<input class='form-control' name='number' type='text' value='' required/>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-4">
											<label>Дата:</label>
										</div>
										<div class="col-md-8">
											<input class='datepicker form-control' name='date' type='text' value='' required/>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-4">
											<label>Сумма:</label>
										</div>
										<div class="col-md-8">
											<input class='form-control check-number' name='amount' type='text' value='' required/>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-4">
											<label>НДС</label>
										</div>
										<div class="col-md-8">
											<input class='form-control check-number' name='amount_vat' type='text' value=''/>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-4">
											<label>НДС</label>
										</div>
										<div class="col-md-8">
											<input class='form-check-input' name='vat' type="checkbox"/>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-4">
											<label>Примечание</label>
										</div>
										<div class="col-md-8">
											<input class='form-control' name='comment' type='text' value=''/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' type='button'>Изменить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
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
				Для просмотра данной страницы необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
