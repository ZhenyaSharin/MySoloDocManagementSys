<footer>
	<div class="container-fluid d-flex flex-column flex-lg-row justify-content-between align-items-center">
		<div class="d-flex flex-column flex-sm-row foot mb-2 mb-md-1">
			<div>
				@if(date("Y") != 2021)
					©&nbsp;<a class="foot__selflink font-bold" href="{{ url('/') }}">
						DocManagementSystem
					</a>&nbsp;{{__('2021')}} - {{ date("Y") }}
				@else
					©&nbsp;<a class="foot__selflink font-bold" href="{{ url('/') }}">
						DocManagementSystem
					</a>&nbsp;{{ date("Y") }}
				@endif
			</div>
			<a href="{{ route('blog') }}" class="foot__selflink ml-md-4 ml-1">
				Информационная лента
			</a>
		</div>
		<div class="mb-2 mb-md-1">
			Разработка: <span>Jerry Sharin</span>
		</div>
	</div>
</footer>