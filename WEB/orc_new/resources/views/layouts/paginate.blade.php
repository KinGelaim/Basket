@if(isset($count_paginate))
	@if($count_paginate)
		@if(!isset($count_visible_page))
			<?php $count_visible_page = 7; ?>
		@endif
		<nav aria-label="page_navigation">
		  <ul class="pagination justify-content-center">
			@if($prev_page)
				<li class="page-item">
				  <a class="page-link" href="?page={{$prev_page}}{{$link}}" tabindex="-1">Предыдущая</a>
				</li>
			@else
				<li class="page-item disabled">
				  <a class="page-link" tabindex="-1">Предыдущая</a>
				</li>
			@endif
			@if($count_paginate <= $count_visible_page)
				@for($i = 1; $i < $count_paginate+1; $i++)
					@if($i == $page)
						<li class="page-item active"><a class="page-link" href="?page={{$i}}{{$link}}">{{$i}}</a></li>
					@else
						<li class="page-item"><a class="page-link" href="?page={{$i}}{{$link}}">{{$i}}</a></li>
					@endif
				@endfor
			@else
				@if($page - $count_visible_page > 1 && $page + $count_visible_page < $count_paginate)
					<li class="page-item"><a class="page-link" href="?page=1{{$link}}">1</a></li>
					<li class="page-item"><a class="page-link">...</a></li>
					@for($i = $page - $count_visible_page; $i < $page+$count_visible_page+1; $i++)
						@if($i == $page)
							<li class="page-item active"><a class="page-link" href="?page={{$i}}{{$link}}">{{$i}}</a></li>
						@else
							<li class="page-item"><a class="page-link" href="?page={{$i}}{{$link}}">{{$i}}</a></li>
						@endif
					@endfor
					@if($page < $count_paginate - $count_visible_page)
						<li class="page-item"><a class="page-link">...</a></li>
						<li class="page-item"><a class="page-link" href="?page={{$count_paginate}}{{$link}}">{{$count_paginate}}</a></li>
					@endif
				@elseif($page <= $count_visible_page+1)
					@for($i = 1; $i < $page+$count_visible_page; $i++)
						@if($i == $page)
							<li class="page-item active"><a class="page-link" href="?page={{$i}}{{$link}}">{{$i}}</a></li>
						@else
							<li class="page-item"><a class="page-link" href="?page={{$i}}{{$link}}">{{$i}}</a></li>
						@endif
					@endfor
					<li class="page-item"><a class="page-link">...</a></li>
					<li class="page-item"><a class="page-link" href="?page={{$count_paginate}}{{$link}}">{{$count_paginate}}</a></li>
				@elseif($page+$count_visible_page >= $count_paginate)
					<li class="page-item"><a class="page-link" href="?page=1{{$link}}">1</a></li>
					<li class="page-item"><a class="page-link">...</a></li>
					@for($i = $page - $count_visible_page; $i < $count_paginate+1; $i++)
						@if($i == $page)
							<li class="page-item active"><a class="page-link" href="?page={{$i}}{{$link}}">{{$i}}</a></li>
						@else
							<li class="page-item"><a class="page-link" href="?page={{$i}}{{$link}}">{{$i}}</a></li>
						@endif
					@endfor
				@endif
			@endif
			@if($next_page)
				<li class="page-item">
				  <a class="page-link" href="?page={{$next_page}}{{$link}}">Следующая</a>
				</li>
			@else
				<li class="page-item disabled">
				  <a class="page-link">Следующая</a>
				</li>
			@endif
		  </ul>
		</nav>
	@endif
@endif