@extends('layouts.header')

@section('title')
	Авторизация
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
			<div class="row">
				<div class="col-md-12 alert alert-danger">
					Ведутся профилактические работы.  Расчётное время запуска реестра: <i>28.07.2021г. 17.00</i>
					<button type="button" class="close" onclick="$(this).parent().parent().empty();">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			</div>
			@if (isset($_GET['auth']))
            <div class="panel panel-default">
                <div class="panel-heading">Авторизация</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

						<?php
							$logins = App\User::getAllLogins();
						?>
						
                        <div class="form-group{{ $errors->has('login') ? ' has-error' : '' }}">
                            <label for="login" class="col-md-4 control-label">Логин</label>

                            <div class="col-md-6">
                                <input id="login" type="login" class="form-control autocomplete" name="login" value="{{ old('login') }}" required autofocus src_autocomplete="@foreach($logins as $login){{$login->login}};@endforeach">

                                @if ($errors->has('login'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('login') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Пароль</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!--<div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Запомнить меня
                                    </label>
                                </div>
                            </div>
                        </div>-->

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Вход
                                </button>

                                <!--<a class="btn btn-link" href="{{ route('password.request') }}">
                                    Забыли Ваш пароль?
                                </a>-->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
			@endif
        </div>
    </div>
</div>
<script>
	$(function(){
		$('.autocomplete').each(function(e){
			var k = $(this).attr('src_autocomplete').split(';');
			$(this).autocomplete({
				source: k
			});
		});
	});
</script>
@endsection
