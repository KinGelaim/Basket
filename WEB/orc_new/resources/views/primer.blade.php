@extends('layouts.header')

@section('title')
	Страница примеров
@endsection

@section('content')	
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class='row'>
				<div class='col-md-3'>
					<div class='form-group row'>
						<div class="col-md-12">
							<select id='resolution_list' class='form-control' onchange="if($('#resolution_list option:selected').attr('attr_del') == 'true') $(this).css('text-decoration', 'line-through'); else $(this).css('text-decoration', 'none');">
								<option></option>
								<option>ДС</option>
								<option>ПР</option>
								<option>Контракт 2</option>
								<option style='background-color: black; color: red;'>Контракт 1</option>
								<option attr_del='true' style='color: red;'>Проект договора</option>
								<option attr_del='true' style='background-color: red;'>Заявка на договор</option>
							</select>
						</div>
					</div>
					<div class='form-group row'>
						<div class="col-md-3">
							<button type='button' class='btn btn-secondary' style='width: 122px;'>Открыть скан</button>
						</div>
						<div class="col-md-3">
							<!--<button id='download_resolution' type='button' class='btn btn-secondary' style='width: 122px;'>Скачать скан</button>-->
						</div>
						<div class="col-md-3">
							<button type='button' class='btn btn-danger' style=''>Отметить на удаление</button>
						</div>
						<div class="col-md-3">
						</div>
					</div>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-5'>
					<label>Аванс</label>
					<textarea class='text-area1 form-control' rows='4'></textarea>
				</div>
				<div id='msgs' class='col-md-5'>
					
				</div>
			</div>
			<script>
				var phraseList = ["акцепт оферты осуществляется путем оплаты счета",
									"100 % в течение 7 раб. дней с момента заключения договора",
									"100 % предоплата",
									"50 % договора и получения счёта",
									"50 % в течение 5 банк. дней после подписания после Акта приёма-передачи оказанных услуг",
									"На основании счёта № 18 от 07.02.2019 г. путём перечисления ден. средств на р/с",
									"по счету",
									"предоплата 100%",
									"100% в течение 30 дней с даты получения счета",
									"аванс 50% в течение 5  банк.дней с даты направления заявки  конч.расчет по факту исполнения"];
				phraseList.sort();
				/*$('#text-area1').autocomplete({
					source: phraseList
				});*/
				monkeyPathAutocomplete();
				$('.text-area1').autocomplete({
					source: function(req, responseFn){
						addMessage("search on: '" + req.term + "'<br/>");
						var re = $.ui.autocomplete.escapeRegex(req.term);
						var matcher = new RegExp(re, "i");
						var a = $.grep(phraseList, function (item, index){
							//addMessage("&nbsp;&nbsp;sniffing: '" + item + "'<br/>");
							return matcher.test(item);
						});
						addMessage("Result :" + a.length + " items<br/>");
						responseFn(a);
					},
					select: function(val, data){
						var s = "";
						if(typeof data == "undefined"){
							s = value;
						}else{
							s = data.item.value;
						}
						if(s.length > 30){
							s = s.substring(0,30) + "...";								
						}
						addMessage('You selected: ' + s + "<br/>");
					}
				});
				
				function monkeyPathAutocomplete(){
					var oldFn = $.ui.autocomplete.prototype._renderItem;
					$.ui.autocomplete.prototype._renderItem = function (ul, item){
						var re = new RegExp(this.term, "i");
						var t = item.label.replace(re, "<span style='font-weight: bold; color: blue;'>" + this.term + "</span>");
						return $("<li></li>").data("item.autocomplete", item).append("<a>" + t + "</a>").appendTo(ul);
					};
				}
				
				function addMessage(msg){
					$('#msgs').append(msg + "<br/>");
				}
			</script>
			<div>
				<b><i>Notify</i></b>
			</div>
			<script>
				if(Notification.permission === 'granted')
				{
					var notification = new Notification('Внимание!',
					{
						body: 'У вас 2 новых согласования',
						img: 'css/images/favicon.png',	//Лучше 40 на 40
						dir: 'auto',
						tag: 'quote',
						buttons: 'asd',
						data: 'urlka'
					});
					notification.addEventListener('click', function(e){
						console.log(e.target.data);
					});
				}
				else{
					// Если прав нет, то пытаемся получить
					Notification.requestPermission(function(permission){
						switch(permission){
							case 'granted':
								alert('Разрешено');
								break;
							case 'default':
								alert('Не сейчас');
								break;
							case 'denied':
								alert('Запрещено');
								break;
						}
					});
				}
			</script>
		@else
			<div class="alert alert-danger">
				Для просмотра данной страницы необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection