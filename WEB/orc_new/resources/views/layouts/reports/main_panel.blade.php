<div class="panel panel-default">
	<div class="panel-body" style='max-height: 700px; overflow-y: auto;'>
		<ul class='nav nav-tabs'>
			<li class='<?php if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Финансовый отдел' && Auth::User()->hasRole()->role != 'Второй отдел' && Auth::User()->hasRole()->role != 'Десятый отдел') echo 'active'; ?>'>
				<a data-toggle='tab' href='#peo'>ПЭО</a>
			</li>
			<li class='<?php if(Auth::User()->hasRole()->role == 'Отдел управления договорами') echo 'active'; ?>'>
				<a data-toggle='tab' href='#oud'>ОУД</a>
			</li>
			<li class='<?php if(Auth::User()->hasRole()->role == 'Финансовый отдел') echo 'active'; ?>'>
				<a data-toggle='tab' href='#fin'>Финансовый отдел</a>
			</li>
			<li class='<?php if(Auth::User()->hasRole()->role == 'Второй отдел') echo 'active'; ?>'>
				<a data-toggle='tab' href='#second'>Второй отдел</a>
			</li>
			<li class='<?php if(Auth::User()->hasRole()->role == 'Десятый отдел') echo 'active'; ?>'>
				<a data-toggle='tab' href='#ten'>Десятый отдел</a>
			</li>
		</ul>
		<div class='tab-content'>
			<!-- Планово-экономический отдел -->
			<div id='peo' class='tab-pane fade <?php if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Финансовый отдел' && Auth::User()->hasRole()->role != 'Второй отдел' && Auth::User()->hasRole()->role != 'Десятый отдел') echo 'in active'; ?>'>
				@include('layouts.reports.peo')
			</div>
			<!-- Отдел управления договорами -->
			<div id='oud' class='tab-pane fade <?php if(Auth::User()->hasRole()->role == 'Отдел управления договорами') echo 'in active'; ?>'>
				@include('layouts.reports.oud')
			</div>
			<!-- Финансовый отдел -->
			<div id='fin' class='tab-pane fade <?php if(Auth::User()->hasRole()->role == 'Финансовый отдел') echo 'in active'; ?>'>
				@include('layouts.reports.invoice')
			</div>
			<!-- Второй отдел -->
			<div id='second' class='tab-pane fade <?php if(Auth::User()->hasRole()->role == 'Второй отдел') echo 'in active'; ?>'>
				@include('layouts.reports.second')
			</div>
			<!-- Десятый отдел -->
			<div id='ten' class='tab-pane fade <?php if(Auth::User()->hasRole()->role == 'Десятый отдел') echo 'in active'; ?>'>
				@include('layouts.reports.ten')
			</div>
		</div>
	</div>
</div>