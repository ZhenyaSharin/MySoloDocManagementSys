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
		<div class="d-flex flex-column align-items-end pdfpreview__header" style="text-align: right;">
			<div class="pdfpreview__header_txt">
				-{ Должность адресата }- 
				<br/> -{ Имя адресата }- 
			</div>
			<div class="pdfpreview__header_txt">
				От -{ Должность автора }- 
				<br/> -{ Имя автора }- 
			</div>
		</div>
		<br/>
		<br/>
		<br/>
		<br/>
		<div class="pdfpreview__title font-up font-bold ta-center" style="text-transform: uppercase; text-align: center; font-weight: bold;">
			Служебная записка111
		</div>
		<br/>
		<div class="pdfpreview__body">
			<p style="text-indent: 1.5em; text-align: justify;">
				Lorem ipsum dolor sit amet, consectetur adipisicing, elit. Mollitia eaque dolorum, modi alias fugit, corporis placeat commodi, voluptas suscipit inventore provident explicabo facilis dolorem? Modi beatae quod reiciendis quidem ipsa. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
				quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
				consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
				cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
				proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
			</p>
		</div>
		<br/>
		<div class="pdfpreview__date font-bold" style="font-weight: bold;">
			01.01.2000
		</div>
		<br/>
		<div class="pdfpreview__signs">
			<div class="d-flex justify-content-between pdfpreview__signs__author" style="display: flex; justify-content: space-between;">
				<div>
					-{ Фамилия Инициалы автора }-
				</div>
				<div style="text-align: right;">
					-{ Подпись автора }-
				</div>
			</div>
			<br/>
			<div class="pdfpreview__signs__agrements">
				<div class="pdfpreview__signs__agrements_title">
					Согласованты:
				</div>
				<br/>
				<div class="d-flex justify-content-between pdfpreview__signs__agrements_item" style="display: flex; justify-content: space-between;">
					<div>
						-{ Фамилия Инициалы согласованта }-
					</div>
					<div style="text-align: right;">
						-{ Подпись согласованта }-
					</div>
				</div>
				<div class="d-flex justify-content-between pdfpreview__signs__agrements_item" style="display: flex; justify-content: space-between;">
					<div>
						-{ Фамилия Инициалы согласованта }-
					</div>
					<div style="text-align: right;">
						-{ Подпись согласованта }-
					</div>
				</div>
				<div class="d-flex justify-content-between pdfpreview__signs__agrements_item" style="display: flex; justify-content: space-between;">
					<div>
						-{ Фамилия Инициалы согласованта }-
					</div>
					<div style="text-align: right;">
						-{ Подпись согласованта }-
					</div>
				</div>
			</div>
		</div>
	</div>
</body>