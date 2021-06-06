@extends('layouts.header')

@section('title')
	Граф элемента
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел')
				<div id='content' class="content">
					
				</div>
				<script>
					function createTree(){
						var xBegin = $('#main_application').offset().left + $('#main_application').width() + 4;
						var yBegin = $('#main_application').offset().top - 27;
						var xEnd = $('#applications').offset().left - 4;
						var yEnd = $('#applications').offset().top - 7;
						var length = Math.sqrt(((xEnd - xBegin) * (xEnd - xBegin)) + ((yEnd - yBegin) * (yEnd - yBegin)));
						var cx = ((xBegin + xEnd) / 2) - (length / 2); 
						var cy = ((yBegin + yEnd) / 2) - 2;
						var angle = Math.atan2((yBegin - yEnd), (xBegin - xEnd))*(180/Math.PI);
						var htmlLine = "<hr class='hrLine' style='height: 3px; position: absolute; left:" + cx + "px; top:" + cy + "px; width:" + length + "px; -webkit-transform:rotate(" + angle + "deg);border-top: 1px solid black;' />";
						$('#content').append(htmlLine);
						xBegin = $('#main_application').offset().left + $('#main_application').width() + 4;
						yBegin = $('#main_application').offset().top - 9;
						xEnd = $('#contracts').offset().left - 4;
						yEnd = $('#contracts').offset().top - 7;
						length = Math.sqrt(((xEnd - xBegin) * (xEnd - xBegin)) + ((yEnd - yBegin) * (yEnd - yBegin)));
						cx = ((xBegin + xEnd) / 2) - (length / 2); 
						cy = ((yBegin + yEnd) / 2) - 2;
						angle = Math.atan2((yBegin - yEnd), (xBegin - xEnd))*(180/Math.PI);
						htmlLine = "<hr class='hrLine' style='height: 3px; position: absolute; left:" + cx + "px; top:" + cy + "px; width:" + length + "px; -webkit-transform:rotate(" + angle + "deg);border-top: 1px solid black;' />";
						$('#content').append(htmlLine);
						//alert(xBegin);
					};
					createTree();
					$(window).resize(function(){
						$('.hrLine').each(function(){
							$(this).remove();
						});
						createTree();
					});
				</script>
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
