@extends('layouts.header')

@section('title')
	Авторизация
@endsection

@section('content')
<div class="container" style='margin-top: 70px; margin-bottom: 270px;'>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
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
