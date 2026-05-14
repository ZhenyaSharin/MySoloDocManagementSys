<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="/img/favicon.png" type="image/png">
    <title>@yield('title', config('app.name'))</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- <script src="{{ asset('js/app.js') }}" defer></script>
	<script
	src="https://code.jquery.com/jquery-3.5.1.min.js"
	integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
	crossorigin="anonymous"></script>
	<script src="https://kit.fontawesome.com/42d566f993.js" crossorigin="anonymous"></script>
	<script src="{{ asset('js/jquery.malihu.PageScroll2id.min.js') }}" defer></script>
	<script src="{{ asset('js/script.js') }}" defer></script> -->
</head>
<body>
	<div class="d-flex flex-column pdfpreview">
		<div class="pdfpreview__title font-up font-bold ta-center tt-underline" style="text-transform: uppercase; text-align: center; font-weight: bold;">
			Лист согласования
		</div>
		<br/>
		<div>
			<span class="font-bold">К документу:</span>&nbsp;&nbsp;{{ $data['description'] }}
		</div>
		<div>
			<span class="font-bold">Тип документа:</span>&nbsp;&nbsp;{{ $data['type']['title'] }}
		</div>
		<div>
			<span class="font-bold">Внутренний номер документа:</span>&nbsp;&nbsp;{{ trim($data['orderNum']) }}
		</div>
		<div>
			<span class="font-bold">Дата и время отправки на согласование:</span>&nbsp;&nbsp;{{ explode('.', $data['created_at'])[0] }}
		</div>
		<div>
			<span class="font-bold">Автор карточки документа:</span>&nbsp;&nbsp;{{ $data['author']['surname'] }} {{ $data['author']['firstname'] }} {{ $data['author']['patronymic'] }}
		</div>
		<div>
			<span class="font-bold">Способ получения документа:</span>&nbsp;&nbsp;{{ $data['deliveryType']['title'] }}
		</div>
		<div>
			<span class="font-bold">Дата и время печати согласования:</span>&nbsp;&nbsp;{{ $date }}
		</div>
		<br/>
		<div class="pdfpreview__signs">
			<table class="pdf_agreements" width="100%">
				<thead>
					<tr>
						<th class="standart-border ta-center pdf-agreements_th">
							Ф.И.О.
						</th>
						<th class="standart-border ta-center pdf-agreements_th">
							Дата решения
						</th>
						<th class="standart-border ta-center pdf-agreements_th">
							Статус
						</th>
						<th class="standart-border ta-center pdf-agreements_th">
							Примечание
						</th>
					</tr>
				</thead>
				<tbody>
					@foreach($list['users'] as $item)
						<tr class="standart-border">
							<td class="standart-border pdf-agreements_td ta-center">
								{{ $item["surname"] }} {{ $item["firstname"] }} {{ $item["patronymic"] }}
							</td>
							@if($item['updated_at'])
								<td class="standart-border pdf-agreements_td ta-center">
									{{ explode('.', $item['updated_at'])[0] }}
								</td>
							@else
								<td class="ta-center">
									---
								</td>
							@endif
							@if(($list['removed']) && (!$item['updated_at']))
								<td class="standart-border ta-center">
									<span >Отклонено</span>&nbsp;<br/>(автоматически)
								</td>
							@else
								@if($item['approved_at'])
									<td  class="standart-border status_approved ta-center">
										@if(count($list['users'])==1)
											Подписано
										@else
											Согласовано
										@endif
									</td>
								@elseif($item['refused_at'])
									<td  class="standart-border status_refused ta-center">
										Отклонено
									</td>
								@else
									<td class="standart-border status_considering ta-center">
										На рассмотрении
									</td>
								@endif
							@endif
							@if($item['note'])
								<td class="standart-border standart-border pdf-agreements_td p-2">
									{{ $item['note'] }}
								</td>
							@else
								<td class="p-2">
									...
								</td>
							@endif
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<br/>
		<div class="">
			<div class="font-bold my-2 ml-4" style="text-indent: 1.5em; text-align: justify;">
				Для замечаний:
			</div>
			<div>
				<table class="pdf_agreements_notes" width="100%">
					<tbody>
						<tr class="underbody">
							<td colspan="100%">
								
							</td>
						</tr>
						<tr class="underbody">
							<td colspan="100%">
								
							</td>
						</tr>
						<tr class="underbody">
							<td colspan="100%">
								
							</td>
						</tr>
						<tr class="underbody">
							<td colspan="100%">
								
							</td>
						</tr>
						<tr class="underbody">
							<td colspan="100%">
								
							</td>
						</tr>
						<tr class="underbody">
							<td colspan="100%">
								
							</td>
						</tr>
						<tr class="underbody">
							<td colspan="100%">
								
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>