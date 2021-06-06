@extends('layouts.header')

@section('title')
	Подпись
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="container">
				<div class='row'>
					<div class='col-md-12'>
						<button class='btn btn-primary' data-toggle="modal" data-target="#create_signature" style='width: 90px;'>Подпись</button>
					</div>
				</div>
				<div class='row'>
					<div class='col-md-12'>
						<button class='btn btn-primary' style='width: 90px;' onclick='location.href="";'>Отчеты</button>
					</div>
				</div>
				<div class='row'>
					<div class='col-md-12'>
						@if($signatures)
							<table class="table table-bordered">
								<thead style='text-align: center;'>
									<tr>
										<th>Название файла</th>
										<th>Подпись</th>
									</tr>
								</thead>
								<tbody>
									@foreach($signatures as $signature)
										<tr>
											<td>{{$signature}}</td>
											<td><img src='signatures/{{$signature}}'/></td>
										</tr>
									@endforeach
								</tbody>
							</table>
						@endif
					</div>
				</div>
			</div>
			<!-- Модальное окно создания подписи -->
			<div class="modal fade" id="create_signature" tabindex="-1" role="dialog" aria-labelledby="signatureModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="signatureModalLabel">Создать подпись</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form id='post_send' method='POST' action='{{route("signature.store_signature")}}'>
								{{csrf_field()}}
								<div class='form-group row'>
									<div class='col-md-12'>
										<canvas id='canvas' style='border: 1px black solid;' name='signature'>
											Обновите браузер
										</canvas>
									</div>
									<div class='col-md-12'>
										<button id='sendCanvas' type='button' class='btn btn-primary' href='{{route("signature.store_signature")}}'>Подтвердить</button>
									</div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
						</div>
					</div>
				</div>
			</div>
			<script>
				var canvas = document.getElementById('canvas');
				var context = canvas.getContext('2d');
				var isDrawing = false;
				
				context.fillStyle = '#344CFF';
				
				$('#canvas').on('mousedown', function(e){
					isDrawing = true;
				});
				
				$('#canvas').on('mousemove', function(e){
					if(isDrawing)
					{
						context.fillRect(e.offsetX - 2, e.offsetY - 2, 4, 4);
						//context.beginPath();
						//context.closePath();
					}
				});
				
				$('#canvas').on('mouseup', function(e){
					isDrawing = false;
				});
				
				$('#sendCanvas').on('click', function(e){
					var data = canvas.toDataURL('image/png').replace(/data:image\/png;base64,/, '');
					$.post($(this).attr('href'), {_token: $('#post_send input[name=_token]').val(), data: data}, function(data){
						alert(data);
					});
				});
			</script>
		@else
			<div class="alert alert-danger">
				Для просмотра данной страницы необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection