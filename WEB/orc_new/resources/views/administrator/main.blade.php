@extends('layouts.header')

@section('title')
	Панель администрирования
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор')
				<div class="content">
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
											<h3>Изделия</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{ route('element.main') }}" style='width: 150px;'>Страница изделий</button>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('element.create')}}" style='width: 150px;'>Добавить изделие</button>
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
											<h3>Виды испытаний</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{ route('view_work.element.main') }}" style='width: 210px;'>Страница видов испытаний</button>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('view_work.element.create')}}" style='width: 210px;'>Добавить вид испытаний</button>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="row">
										<div class="col-md-12">
											<h3>Виды работ</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{ route('view_work.main') }}" style='width: 177px;'>Страница видов работ</button>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' type='button' href="{{route('view_work.create')}}" style='width: 177px;'>Добавить вид работ</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-3">
							<div class="row">
								<div class="col-md-12">
									<h3>Контрагенты</h3>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class='btn btn-primary btn-href' type='button' href="{{route('counterpartie.main')}}" style='width: 185px;'>Страница контрагентов</button>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12" style='margin-top: 10px;'>
									<button class='btn btn-primary btn-href' type='button' href="{{route('counterpartie.create')}}" style='width: 185px;'>Добавить контрагента</button>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12" style='margin-top: 10px;'>
									<button class='btn btn-primary btn-href' type='button' href="{{route('curator.main')}}" style='width: 185px;'>Страница кураторов</button>
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="row">
								<div class="col-md-12">
									<h3>Комплектующие</h3>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class='btn btn-primary btn-href' type='button' href="{{ route('ten.element_main') }}" style='width: 150px;'>Страница изделий</button>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class='btn btn-primary btn-href' type='button' href="{{route('ten.element_create')}}" style='width: 150px;'>Добавить изделие</button>
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="row">
								<div class="col-md-12">
									<h3>Склад комплектующих</h3>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class='btn btn-primary btn-href' type='button' href="{{ route('ten.party_element_main') }}" style='width: 150px;'>Страница изделий</button>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class='btn btn-primary btn-href' type='button' href="{{route('ten.party_element_create')}}" style='width: 150px;'>Добавить изделие</button>
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="row">
								<div class="col-md-12">
									<h3>Виды договоров</h3>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class='btn btn-primary btn-href' type='button' href="{{ route('view_contract.main') }}" style='width: 177px;'>Страница видов</button>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class='btn btn-primary btn-href' type='button' href="{{route('view_contract.create')}}" style='width: 177px;'>Добавить вид контракта</button>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="row">
								<div class="col-md-12">
									<h3>Единицы измерения второго отдела</h3>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class='btn btn-primary btn-href' type='button' href="{{ route('second_department_unit.main') }}">Страница единиц измерений</button>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class='btn btn-primary btn-href' type='button' href="{{ route('second_department_unit.create') }}">Добавить новую единицу измерения</button>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="row">
								<div class="col-md-12">
									<h3>Подразделения</h3>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class='btn btn-primary btn-href' type='button' href="{{ route('departments.main') }}">Страница подразделений</button>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="row">
								<div class="col-md-12">
									<h3>Типы для второго отдела</h3>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class='btn btn-primary btn-href' type='button' href="{{ route('second_department_caliber.main') }}">Страница типов</button>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="row">
								<div class="col-md-12">
									<h3>Наименование для второго отдела</h3>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class='btn btn-primary btn-href' type='button' href="{{ route('second_department_name_element.main') }}">Страница наименований</button>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h3>Канцелярия</h3>
						</div>
						<div class="col-md-12">
							<button class='btn btn-primary btn-href' type='button' href="{{ route('department.chancery') }}">Страница канцелярии</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h3>Планово-экономический отдел</h3>
						</div>
						<div class="col-md-12">
							<button class='btn btn-primary btn-href' type='button' href="{{ route('department.ekonomic') }}">Страница планово-экономического отдела</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h3>Финансовый отдел</h3>
						</div>
						<div class="col-md-12">
							<button class='btn btn-primary btn-href' type='button' href="{{ route('department.invoice') }}">Страница финансового отдела</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h3>Второй отдел</h3>
						</div>
						<div class="col-md-12">
							<button class='btn btn-primary btn-href' type='button' href="{{ route('department.second') }}">Страница второго отдела</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h3>Руководство (отчеты)</h3>
						</div>
						<div class="col-md-12">
							<button class='btn btn-primary btn-href' type='button' href="{{ route('department.leadership') }}">Страница для печати отчетов</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h3>Архив</h3>
						</div>
						<div class="col-md-12">
							<button class='btn btn-primary btn-href' type='button' href="{{ route('archive.main') }}">Страница архива</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h3>Журнал</h3>
						</div>
						<div class="col-md-12">
							<button class='btn btn-primary btn-href' type='button' href="{{ route('journal.main') }}">Страница журнала</button>
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
