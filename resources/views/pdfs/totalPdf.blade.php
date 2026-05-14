<?php 
	function frmtDate($str, $short = false) {
		$timeZone = new DateTimeZone(config('app.timezone'));
		$dtt = new DateTime("now", $timeZone);
		$hoursOff = $timeZone->getOffset($dtt)/3600;
		if ($str) {
			if ($short == true) {
				return explode('-' , explode(' ', explode('.', $str)[0])[0])[2].'.'.explode('-' , explode(' ', explode('.', $str)[0])[0])[1].'.'.explode('-' , explode(' ', explode('.', $str)[0])[0])[0];
			} else {
				return explode('-' , explode(' ', explode('.', $str)[0])[0])[2].'.'.explode('-' , explode(' ', explode('.', $str)[0])[0])[1].'.'.explode('-' , explode(' ', explode('.', $str)[0])[0])[0].' '.intval(explode(':', explode(' ', explode('.', $str)[0])[1])[0]) + $hoursOff.':'.explode(':', explode(' ', explode('.', $str)[0])[1])[1].':'.explode(':', explode(' ', explode('.', $str)[0])[1])[2];
			};
		} else {
			return null;
		}
	};
	// echo '<pre>';
	// print_r($data->document);
	// echo '</pre>';
	function shortName($surname, $firstname, $patronymic = null) {
		if ($surname) {
			if ($patronymic != null) {
				return $surname.' '.strtoupper(mb_substr($firstname, 0, 1)).'. '.strtoupper(mb_substr($patronymic, 0, 1)).'.';
			} else {
				return $surname.' '.strtoupper(mb_substr($firstname, 0, 1)).'. ';
			}
		} else {
			return null;
		}
	};

	// function checkHours($ts, $offset) {
	// 	$str;
	// 	intval(explode(':', explode(' ', explode('.', $str)[0])[1])[0]) + $hoursOff.':'.explode(':', explode(' ', explode('.', $str)[0])[1])[1].':'.explode(':', explode(' ', explode('.', $str)[0])[1])[2]
	// };
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="/img/favicon.png" type="image/png">
    <title>PDF</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link href="{{ $css }}" rel="stylesheet">
    <style>
/*    	@font-face {
		  font-family: 'Open Sans';
		  font-style: normal;
		  font-weight: normal;
		  src: url(http://themes.googleusercontent.com/static/fonts/opensans/v8/cJZKeOuBrn4kERxqtaUH3aCWcynf_cDxXwCLxiixG1c.ttf) format('truetype');
		}

		* {
			font-family: 'Open Sans';
		}*/
		.pdfpreview {
			min-height: 700px;
			font-size: 14px;
		}

		.pdfpreview__title {
			padding: 40px 0;
			font-size: 18px;
		}

		.pdf-agreements_th {
			padding: 10px;
		}

		.font-up {
			text-transform: uppercase;
		}

		.font-bold {
			font-weight: bold;
		}

		.ta-center {
			text-align: center;
		}

		.tt-underline {
			text-decoration: underline;
		}

		.underbody {
			border-bottom: 1px solid #000;
			padding: 20px;
		}

		.standart-border {
			border: 1px solid #000;
			border-collapse: collapse;
		}

		table {
			border-collapse: collapse;
		}

		.standart-border {
			border: 1px solid #000;
			border-collapse: collapse;
		}

		.pdf-agreements_td {
			padding: 10px 6px;
		}

		.status_considering {
			font-weight: bold;
		}

		.status_approved {
			font-weight: bold;
			color: green;
		}

		.status_refused {
			font-weight: bold;
			color: red;
		}
		.ws-nowrap {
			white-space: nowrap;
		}
    </style>	
</head>
<body>
	<div class="d-flex flex-column pdfpreview">
		<div class="pdfpreview__title font-up font-bold ta-center tt-underline">
			Лист согласования
		</div>
		<br/>
		<div>
			<span class="font-bold">К карточке документа № {{ $data->document->id }}:</span>&nbsp;&nbsp;{{ $data->document->description }}
		</div>
		<div>
			<span class="font-bold">Тип документа:</span>&nbsp;&nbsp;{{ $data->typeTitle ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Дата и время отправки на согласование:</span>&nbsp;&nbsp;{{ frmtDate($data->document->createdAt) ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Автор карточки документа:</span>&nbsp;&nbsp;{{ $data->authorSurname ?? '-Не указано-' }} {{ $data->authorFirstname ?? '-Не указано-' }} {{ $data->authorPatronymic ?? '-Не указано-' }}
		</div>
		@if($data->document->typeId == 2 || $data->document->typeId == 6 || $data->document->typeId == 7 || $data->document->typeId == 15)
			@if($data->diruser != false)
			<div>
				<span class="font-bold">Адресат/контрагент:</span>&nbsp;&nbsp;{{ $data->diruser->surname }} {{ $data->diruser->firstname ?? '' }} {{ $data->diruser->patronymic ?? '' }}
			</div>
			@else
			<div>
				<span class="font-bold">Адресат/контрагент:</span>-Не указано-
			</div>
			@endif
		@endif
		@if($data->document->typeId == 2)
		<div>
			<span class="font-bold">Номер договора:</span>&nbsp;&nbsp;{{ $data->document->orderNum ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Наименование:</span>&nbsp;&nbsp;{{ $data->document->name ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Дата заключения:</span>&nbsp;&nbsp;{{ frmtDate($data->document->creationDate, true) ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Дата закрытия:</span>&nbsp;&nbsp;{{ frmtDate($data->document->closeDate, true) ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Заказчик:</span>&nbsp;&nbsp;{{ $data->document->customer ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Соисполнитель:</span>&nbsp;&nbsp;{{ $data->document->coExecutor ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Разговорное название:</span>&nbsp;&nbsp;{{ $data->document->colName ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Сумма по договору:</span>&nbsp;&nbsp;{{ $data->document->sumContract ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Этапы:</span>
			<div>
				{{ $data->document->phases ?? '-Не указано-' }}
			</div>
		</div>
		@elseif($data->document->typeId == 6)
		<div>
			<span class="font-bold">Внутренний номер:</span>&nbsp;&nbsp;{{ $data->document->orderNum ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Примечание:</span>&nbsp;&nbsp;{{ $data->document->note ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Срок исполнения:</span>&nbsp;&nbsp;{{ frmtDate($data->document->closeDate, true) ?? '-Не указано-' }}
		</div>
		@elseif($data->document->typeId == 7)
		<div>
			<span class="font-bold">Номер исходящего письма:</span>&nbsp;&nbsp;{{ $data->document->orderNum ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Дата отправления:</span>&nbsp;&nbsp;{{ frmtDate($data->document->creationDate, true) ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Кому:</span>&nbsp;&nbsp;{{ $data->document->addresser ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Кому на исполнение:</span>&nbsp;&nbsp;{{ $data->document->letterExecutor ?? '-Не указано-' }}
		</div>
		@elseif($data->document->typeId == 9)
		<div>
			<span class="font-bold">Номер приказа по ОД:</span>&nbsp;&nbsp;{{ $data->document->orderNum ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Дата создания приказа:</span>&nbsp;&nbsp;{{ frmtDate($data->document->creationDate, true) ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Исполнитель:</span>&nbsp;&nbsp;{{ shortName($data->document->executor->surname, $data->document->executor->firstname, $data->document->executor->patronymic) ?? '-Не указано-' }}
		</div>
		@elseif($data->document->typeId == 12)
		<div>
			<span class="font-bold">Номер уведомления:</span>&nbsp;&nbsp;{{ $data->document->orderNum ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Дата создания:</span>&nbsp;&nbsp;{{ frmtDate($data->document->creationDate, true) ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Подписант:</span>&nbsp;&nbsp;{{ $data->document->signatory ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Автор:</span>&nbsp;&nbsp;{{ $data->document->author ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Дата ознакомления:</span>&nbsp;&nbsp;{{ frmtDate($data->document->acqDate, true) ?? '-Не указано-' }}
		</div>
		@else 
		<div>
			<span class="font-bold">Дата документа:</span>&nbsp;&nbsp;{{ frmtDate($data->document->creationDate, true) ?? '-Не указано-' }}
		</div>
		<div>
			<span class="font-bold">Номер документа:</span>&nbsp;&nbsp;{{ $data->document->orderNum ?? '-Не указано-' }}
		</div>
		@endif
		<br/>
		<div class="pdfpreview__signs">
			<table class="table pdf_agreements" width="100%">
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
					@foreach($data->agreements as $item)
						<tr class="standart-border">
							<td class="standart-border pdf-agreements_td ta-center ws-nowrap">
								{{ shortName($item->user->surname, $item->user->firstname, $item->user->patronymic) }}
							</td>
							@if($item->approve)
								<td class="standart-border pdf-agreements_td ta-center">
									{{ frmtDate($item->approve) }}
								</td>
							@elseif($item->refusedAt)
								<td class="standart-border pdf-agreements_td ta-center">
									{{ frmtDate($item->refusedAt) }}
								</td>
							@elseif($item->updatedAt)
								<td class="standart-border pdf-agreements_td ta-center">
									{{ frmtDate($item->updatedAt) }}
								</td>
							@else
								<td class="standart-border ta-center">
									---
								</td>
							@endif
							@if(($item->removed) && (!$item->updatedAt))
								<td class="standart-border ta-center pdf-agreements_td">
									<span >Отклонено</span>&nbsp;<br/>(автоматически)
								</td>
							@else
								@if($item->approve)
									<td  class="standart-border status_approved ta-center pdf-agreements_td">
										@if(count($data->agreements)==1)
											Подписано
										@else
											Согласовано
										@endif
									</td>
								@elseif($item->refusedAt)
									<td  class="standart-border status_refused ta-center pdf-agreements_td">
										Отклонено
									</td>
								@else
									<td class="standart-border status_considering ta-center pdf-agreements_td">
										На рассмотрении
									</td>
								@endif
							@endif
							@if($item->note)
								<td class="standart-border standart-border pdf-agreements_td ta-center p-2">
									{{ $item->note }}
								</td>
							@else
								<td class="standart-border ta-center p-2 pdf-agreements_td">
									...
								</td>
							@endif
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<br/>
		<div>
			<div class="font-bold my-2 ml-4">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Для замечаний:
			</div>
			<div>
				<table class="pdf_agreements_notes" width="100%">
					<thead>
						<tr class="underbody">
							<th class="pdf-agreements_td">
								
							</th>
						</tr>
					</thead>
					<tbody>
						<tr class="underbody">
							<td colspan="100%" class="pdf-agreements_td">
								
							</td>
						</tr>
						<tr class="underbody">
							<td colspan="100%" class="pdf-agreements_td">
								
							</td>
						</tr>
						<tr class="underbody">
							<td colspan="100%" class="pdf-agreements_td">
								
							</td>
						</tr>
						<tr class="underbody">
							<td colspan="100%" class="pdf-agreements_td">
								
							</td>
						</tr>
						<tr class="underbody">
							<td colspan="100%" class="pdf-agreements_td">
								
							</td>
						</tr>
						<tr class="underbody">
							<td colspan="100%" class="pdf-agreements_td">
								
							</td>
						</tr>
						<tr class="underbody">
							<td colspan="100%" class="pdf-agreements_td">
								
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<br/>
		<div style="display: inline-block;">
			<span class="font-bold">
				Дата и время печати листа согласования:
			</span>
			<span>
				{{ $date }}
			</span>
		</div>
	</div></body><html>