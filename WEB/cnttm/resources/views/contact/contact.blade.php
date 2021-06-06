@extends('layouts.app')

@section('title')
	Обратная связь
@endsection

@section('content')
	<div class="banner-1">
		
	</div>
	<div class="contact">
		<div class="container">
			<div class="contact-top">
				<h3>Обратная связь</h3>
				<p>Если у Вас остались какие-то вопросы, Вы можете связаться с нами по телефону, приехать лично или оставить информацию, чтобы мы смогли лично связаться с Вами.</p>
			</div>	
			<div class="col-md-6 contact-lft">
			<div class="map">
				<div style="position:relative;overflow:hidden;"><a href="https://yandex.ru/maps/11168/nizhniy-tagil/?utm_medium=mapframe&utm_source=maps" style="color:#eee;font-size:12px;position:absolute;top:0px;">Нижний Тагил</a><a href="https://yandex.ru/maps/11168/nizhniy-tagil/house/ulitsa_zdesenko_22/YkkYdgNhSUcFQFttfXR0cH9hbA==/?ll=60.042174%2C57.851066&utm_medium=mapframe&utm_source=maps&z=17.84" style="color:#eee;font-size:12px;position:absolute;top:14px;">Улица Здесенко, 22 — Яндекс.Карты</a><iframe src="https://yandex.ru/map-widget/v1/-/CWTgv8ic" width="560" height="400" frameborder="1" allowfullscreen="true" style="position:relative;"></iframe></div>
			</div>
			<h4>Адрес</h4>
				<address>
					Микрорайон Старатель<br/>
					ул. Здесенко, 22<br/>
					<abbr title="Телефон">Т: </abbr> +7 (3435) 29-15-17<br/>
					Проезд: Маршрут №17 до остановки "Школа"<br/>
				</address>
			</div>	
			<div class="col-md-6 contact-bottom">
				<form method='POST' action="{{route('contact.store_message')}}">
					{{csrf_field()}}
					<input name='first_name' type="text" value="" placeholder="Имя" required>	
					<input name='last_name' type="text" value="" placeholder="Фамилия" required>				
					<input name='email' type="text" value="" placeholder="Почтовый адрес" required>
					<textarea name='message' placeholder="Сообщение" required></textarea>	
					<input type="submit" value="Отправить">
				</form>	
			</div>
			<div class="clearfix"></div>		
		</div>
	</div>
@endsection
