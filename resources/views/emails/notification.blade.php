<div>
	<h3>
		Оповещение СЭД
	</h3>
	<br/>
	<span>
		У Вас новое уведомление:
	</span>
	<br/>
	<span>
		Просмотреть <a href="{{ $link }}">{{ $type }}</a>.
	</span>
	@if($desc)
	<div style="background-color: #fff;padding: 5px;">
		"{{ $desc }}"
	</div>
	@endif
	<br/>
	<span>
		Ссылка на {{ $type }}:&nbsp;{{ $link }}
	</span>
</div>