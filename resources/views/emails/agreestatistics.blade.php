<div>
	<h3>
		Оповещение СЭД
	</h3>
	<br/>
	@if($refused)
	<span>
		Сообщение о ходе прерванной процедуры согласования документа:
	</span>
	@else
	<span>
		Сообщение о ходе процедуры согласования документа:
	</span>
	@endif
	<br/>
	<span>
		Посмотреть <a href="{{ $link }}">карточку документа</a>.
	</span>
	<br/>
	@if($list)
	<span>
		Процесс согласования:
		<br/>
	</span>
	<table class="table table-striped">
		<thead>
			<tr>
				<th scope="col" width="20">
					#
				</th>
				<th style="text-align: center;" scope="col">
					Согласовант
				</th>
				<th style="text-align: center;" scope="col">
					Комментарий
				</th>
				<th style="text-align: center;" scope="col">
					Статус
				</th>
				<th style="text-align: right;" scope="col">
					Дата и время(Мск) принятия решения
				</th>
			</tr>
		</thead>
		<tbody>
			@foreach($list as $item)
				<tr>
					@if($item['order'] != null)
					<th scope="row">
						{{ $item['order'] }}
					</th>
					@else
					<th scope="row">
						*
					</th>
					@endif
					<td style="text-align: center;">
						<?php echo($item['user']['surname'] . ' ' . mb_substr($item['user']['firstname'], 0, 1) . '. ' . mb_substr($item['user']['patronymic'], 0, 1));?>
					</td>
					@if($item['note'] != null)
					<td style="text-align: center;">
						{{ $item['note'] }}
					</td>
					@else
					<td style="text-align: center;">
						...
					</td>
					@endif
					@if($item['approved_at'] != null)
					<td style="color: green;text-align: center;">
						Согласовано
					</td>
					<td style="text-align: right;">
						<?php echo(date_format(date_modify(date_create($item['approved_at']),"+3 hours"),"Y-m-d H:i:s"));?>
					</td>
					@elseif($item['refused_at'] != null)
					<td style="color: red;text-align: center;">
						Отклонено
					</td>
					<td style="text-align: right;">
						<?php echo(date_format(date_modify(date_create($item['refused_at']),"+3 hours"),"Y-m-d H:i:s")); ?> 
					</td>
					@else
						@if($refused)
						<td style="text-align: center;">
							Отклонено (автоматически)
						</td>
						@else
						<td style="text-align: center;">
							На рассмотрении
						</td>
						@endif
						<td style="text-align: right;">
							...
						</td>
					@endif
				</tr>
			@endforeach
		</tbody>
	</table>
	@endif
	<br/>
	<span>
		Ссылка на карточку документа:&nbsp;{{ $link }}
	</span>
</div>