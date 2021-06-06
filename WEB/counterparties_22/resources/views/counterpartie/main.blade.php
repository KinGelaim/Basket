@extends('layouts.header')

@section('title')
	Контрагенты для отдела №22
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		<div class="content">
			<div class="row">
				<div class="col-md-12">
					<h3><a href='{{route("counterpartie.main")}}'>Контрагенты для отдела №22</a></h3>
				</div>
				<div class="col-md-6">
					@includeif('layouts.search', ['search_arr_value'=>['code'=>'Код контрагента','name'=>'Контрагент','name_full'=>'Полное наименование','inn'=>'ИНН']])
				</div>
				<div class="col-md-4" style='margin-top: 10px; text-align: right;'>
					<button class='btn btn-primary btn-href' type='button' href="{{route('counterpartie.report')}}">Отчёт</button>
				</div>
				<div class="col-md-2" style='margin-top: 10px; text-align: right;'>
					<button class='btn btn-primary btn-href' type='button' href="{{route('counterpartie.create')}}">Добавить контрагента</button>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12" style="text-align: center;">
					<nav aria-label="counterpartie_navigation">
					  <ul class="pagination justify-content-center">
						@foreach(range(0,9) as $letter)
							@if($letter_value == iconv('CP1251','UTF-8',$letter))
								<li class="page-item active"><a class="page-link" href="{{'?page=1'  . $link . '&letter=' . $letter}}">{{$letter}}</a></li>
							@else
								<li class="page-item"><a class="page-link" href="{{'?page=1'  . $link . '&letter=' . $letter}}">{{$letter}}</a></li>
							@endif
						@endforeach
						@foreach(range(chr(0xC0),chr(0xDF)) as $letter)
							@if($letter_value == iconv('CP1251','UTF-8',$letter))
								<li class="page-item active"><a class="page-link" href="{{'?page=1'  . $link . '&letter=' . iconv('CP1251','UTF-8',$letter)}}">{{iconv('CP1251','UTF-8',$letter)}}</a></li>
							@else
								<li class="page-item"><a class="page-link" href="{{'?page=1'  . $link . '&letter=' . iconv('CP1251','UTF-8',$letter)}}">{{iconv('CP1251','UTF-8',$letter)}}</a></li>
							@endif
						@endforeach
						</ul>
					</nav>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12" style="text-align: center;">
					@include('layouts.paginate')
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
						<thead>
							<tr>
								<th>Контрагент</th>
								<th>Полное наименование</th>
								<th>Редактировать</th>
								<th>Удалить</th>
							</tr>
						</thead>
						<tbody>
							@foreach($counterparties as $counterpartie)
									<tr class='rowsContract'>
										<td>{{$counterpartie->name}}</td>
										<td>{{$counterpartie->name_full}}</td>
										<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("counterpartie.edit", $counterpartie->id)}}'>Редактировать</button></td>
										<td><button type='button' class='btn btn-danger btn-href' type='button' href='{{route("counterpartie.delete", $counterpartie->id)}}'>Удалить</button></td>
									</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12" style="text-align: center;">
					@include('layouts.paginate')
				</div>
			</div>
		</div>
	</div>
@endsection
