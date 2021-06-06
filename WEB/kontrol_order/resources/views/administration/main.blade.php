@extends('layouts.app')

@section('title')
	Панель андминистрирования
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор')
				<div class="content" style='margin-bottom: 270px;'>
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="col-sm-6">
									<div class="row">
										<div class="col-md-12">
											<h3>Пользователи</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('user.main')}}" style='width: 197px;'>Страница пользователей</button>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('user.create')}}" style='width: 197px;'>Добавить пользователя</button>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="row">
										<div class="col-md-12">
											<h3>Роли</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('role.main')}}" style='width: 177px;'>Страница ролей</button>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('role.create')}}" style='width: 177px;'>Добавить роль</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="row">
								<div class="col-sm-6">
									<div class="row">
										<div class="col-md-12">
											<h3>Организации</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('counterpartie.main')}}" style='width: 177px;'>Страница организаций</button>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('counterpartie.create')}}" style='width: 177px;'>Добавить организацию</button>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="row">
										<div class="col-md-12">
											<h3>Типы документов</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('type_document.main')}}" style='width: 177px;'>Страница типов</button>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('type_document.create')}}" style='width: 177px;'>Добавить тип</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<div class="row">
								<div class="col-sm-6">
									<div class="row">
										<div class="col-md-12">
											<h3>Периоды контроля</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('kontrol_period.main')}}" style='width: 197px;'>Страница периодов</button>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('kontrol_period.create')}}" style='width: 197px;'>Добавить период</button>
										</div>
									</div>
								</div>
							</div>
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
