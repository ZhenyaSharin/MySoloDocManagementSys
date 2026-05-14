<div>
	<h3>
		Оповещение СЭД
	</h3>
	<br/>
	<span>
		У Вас уведомление о новом поручении:
	</span>
	<br/>
	<span>
		Просмотреть <a href="{{ $link }}">поручение</a>.
	</span>
	@if($desc)
	<span>
		Описание поручения:
	</span>
	<div style="background-color: #fff;padding: 5px;">
		"{{ $desc }}"
	</div>
	@endif
	<br/>
	<span>
		Ссылка на поручение:&nbsp;{{ $link }}
	</span>
</div>