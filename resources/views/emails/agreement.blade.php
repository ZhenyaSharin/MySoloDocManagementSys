<div>
	<h3>
		Оповещение СЭД
	</h3>
	<br/>
	<span>
		Документ прислан Вам на согласование:
	</span>
	<br/>
	<span>
		Просмотреть <a href="{{ $link }}">карточку документа</a>.
	</span>
	@if($desc)
	<span>
		Описание документа:
	</span>
	<div style="background-color: #fff;padding: 5px;">
		"{{ $desc }}"
	</div>
	@endif
	<br/>
	<span>
		Ссылка на карточку документа:&nbsp;{{ $link }}
	</span>
</div>