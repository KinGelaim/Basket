@extends('layouts.app')

@section('title')
	Панель андминистрирования
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Преподаватель')
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
											<h3>Лаборатория</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('laboratory.main')}}" style='width: 200px;'>Страница лабораторий</button>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('laboratory.create')}}" style='width: 200px;'>Добавить лабораторию</button>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="row">
										<div class="col-md-12">
											<h3>Группы</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('group.main')}}" style='width: 210px;'>Страница групп</button>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('group.create')}}" style='width: 210px;'>Добавить группу</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="col-sm-6">
									<div class="row">
										<div class="col-md-12">
											<h3>Ученики</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('schoolchildren.main')}}" style='width: 197px;'>Страница учеников</button>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="row">
										<div class="col-md-12">
											<h3>Тесты</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('test.main')}}" style='width: 177px;'>Страница тестов</button>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('test.create')}}" style='width: 177px;'>Создать тест</button>
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
											<h3>Проверка</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('administration.answer.main_answers')}}" style='width: 197px;'>Страница учеников</button>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="row">
										<div class="col-md-12">
											<h3>Методические материалы</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('teaching_materials')}}" style='width: 197px;'>Материалы</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="col-sm-6">
									<div class="row">
										<div class="col-md-12">
											<h3>Педагоги</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('educator.main')}}" style='width: 197px;'>Страница педагогов</button>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="row">
										<div class="col-md-12">
											<h3>Обратная связь</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('contact.show_message')}}" style='width: 197px;'>Сообщения</button>
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
											<h3>Результат посещения</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('journal_state.main')}}" style='width: 197px;'>Страница оценок</button>
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
