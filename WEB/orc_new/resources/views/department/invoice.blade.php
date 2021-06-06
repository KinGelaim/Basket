@extends('layouts.header')

@section('title')
	Финансовый отдел
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Route::has('login'))
			<div class="top-right links">
				
			</div>
		@endif

		<div class="content">
			<div class="row">
				<div class="col-md-3">
					<p>Номер договора <input/></p>
				</div>
				<div class="col-md-1">
					ГОЗ <input type="checkbox"/>
				</div>
				<div class="col-md-3">
					Вид работ <input/>
				</div>
				<div class="col-md-1">
					от <input type="checkbox"/>
				</div>
				<div class="col-md-3">
					Дата <input/>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<p>Название предприятия</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<input style="width:100%;"/>
				</div>
			</div>
		</div>
	</div>
@endsection
