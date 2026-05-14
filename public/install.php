<?php

$phpVersion = intval(explode('.', phpversion())[0]);
exec('psql --version', $pgOut);
$pgArr = explode(' ', $pgOut[0]);
$pgVersion = $pgArr[count($pgArr) - 1];
$systemEnable = false;
$phpMin = 7;
$pgMin = 10;

if (($phpVersion >= $phpMin) && (function_exists('mail') === true) && (intval($pgVersion) >= $pgMin)) {
    $systemEnable = true;
};

?>
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <title>Первый запуск Системы</title>
    <link rel="shortcut icon" href="/icon.PNG" type="image/png">

    <!-- Scripts -->
<!--     <script src="/js/moment.min.js"></script>
    <script src="/js/app.js" defer></script> -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <style type="text/css">
        .lds-facebook,
        .lds-facebook div {
            box-sizing: border-box;
        }
        .lds-facebook {
            display: inline-block;
            position: relative;
            width: 80px;
            height: 80px;
        }
        .lds-facebook div {
            display: inline-block;
            position: absolute;
            left: 8px;
            width: 16px;
            background: currentColor;
            animation: lds-facebook 1.2s cubic-bezier(0, 0.5, 0.5, 1) infinite;
        }
        .lds-facebook div:nth-child(1) {
            left: 8px;
            animation-delay: -0.24s;
        }
        .lds-facebook div:nth-child(2) {
            left: 32px;
            animation-delay: -0.12s;
        }
        .lds-facebook div:nth-child(3) {
            left: 56px;
            animation-delay: 0s;
        }
        @keyframes lds-facebook {
            0% {
                top: 8px;
                height: 64px;
            }
            50%, 100% {
                top: 24px;
                height: 32px;
            }
        }
        #loading,
        #error {
            display: none;
        }
    </style>
</head>
<body>
	<div class="container">
		<br/>
		<br/>
		<h1>
            Установка системы документооборота
        </h1>
		<div class="row justify-content-center align-items-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <span class="font-bold font-up">
                            Проверка системы
                        </span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            Конфигурация   
                                        </th>
                                        <th scope="col" class="ta-right">
                                            Статус
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            Версия PHP
                                        </td>
                                        <?php if ($phpVersion < $phpMin):?>
                                        <td class="ta-right status_refused">
                                        <?php else:?>
                                        <td class="ta-right status_approved">
                                        <?php endif;?>
                                            <?php echo(phpversion());?>
                                        </td>
                                    </tr>
<!--                                     <tr>
                                        <td>
                                            Наличие Curl
                                        </td>
                                        <?php if (function_exists('curl_version') == false) :?>
                                        <td class="ta-right status_refused">
                                            <?php function_exists('curl_version');?>
                                        </td>
                                        <?php else :?>
                                        <td v-else class="ta-right status_approved">
                                            Да
                                        </td>
                                        <?php endif;?>
                                    </tr> -->
                                    <tr>
                                        <td>
                                            Наличие Mail
                                        </td>
                                        <?php if (function_exists('mail') == false) :?>
                                        <td class="ta-right status_refused">
                                            Нет
                                        </td>
                                        <?php else :?>
                                        <td v-else class="ta-right status_approved">
                                            Да
                                        </td>
                                        <?php endif;?>
                                    </tr>
                                    <tr>
                                        <td>
                                            Версия PostgreSQL
                                        </td>
                                        <?php if (intval($pgVersion) < $pgMin):?>
                                        <td class="ta-right status_refused">
                                        <?php else :?>
                                        <td v-else class="ta-right status_approved">
                                        <?php endif;?>
                                            <?php echo($pgVersion);?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php if ($systemEnable === true) :?>
<!--                         <div class="d-flex justify-content-center align-items-center">
                            <button type="button" class="btn btn-primary font-bold font-up no-radius" @click="adInstallToggle(1)">
                                Далее
                            </button>
                        </div> -->
                        <?php else:?>
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <small class="form-text text-muted greytxt my-2">
                                Необходимо завершить все необходимые настройки веб-сервера
                            </small>
                            <button type="button" class="btn btn-danger font-bold font-up no-radius" disabled title="Проверьте наличие условий, необходимых для установки системы">
                                Система не может быть установлена
                            </button>
                        </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($systemEnable === true) :?>
        <!-- <form action="action.php" method="post"> -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <span class="font-bold font-up">
                                    Хост системы
                                </span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <span class="font-bold my-1">
                                Введите имя хоста для системы
                            </span>
<!--                             <small class="form-text text-muted greytxt my-2 pr-2">
                                * по умолчанию система будет использовать защищённый протокол https
                            </small> -->
                            <div class="form-group row">
                                <label for="sysProtocol" class="col-sm-5 col-form-label">
                                    Тип протокола
                                </label>
                                <div class="col-sm-7 cursor-point">
                                    <select id="sysProtocol" class="form-control" name="sysProtocol" required>
                                        <option value="null" disabled selected>
                                            Выберите тип протокола
                                        </option>
                                        <option value="http">
                                            http
                                        </option>
                                        <option value="https">
                                            https
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="sysHost" class="col-sm-5 col-form-label">
                                    Хост системы
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="sysHost" placeholder="Имя хоста системы" name="sysHost" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="sysMode" class="col-sm-5 col-form-label">
                                    Режим
                                </label>
                                <div class="col-sm-7 cursor-point">
                                    <select id="sysMode" class="form-control" name="sysMode" required>
                                        <option value="null" disabled selected>
                                            Выберите режим проекта
                                        </option>
                                        <option value="local">
                                            Пробный
                                        </option>
                                        <option value="production">
                                            Рабочий
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <span class="font-bold font-up">
                                    Создание и настройка базы данных
                                </span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <span class="font-bold my-1">
                                Подключение к ранее созданной пустой базе
                            </span>
                            <small class="form-text text-muted greytxt my-2 pr-2">
                                * Корректная работа возможна только при наличии СУБД PostgreSQL
                            </small>
                            <div class="form-group row">
                                <label for="dbHost" class="col-sm-5 col-form-label">
                                    Хост базы данных
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="dbHost" placeholder="Введите адрес хоста" name="dbHost" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="dbPort" class="col-sm-5 col-form-label">
                                    Порт
                                </label>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" id="dbPort" placeholder="Введите номер порта (по умолчанию 5432)" name="dbPort" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="dbUser" class="col-sm-5 col-form-label">
                                    Пользователь
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="dbUser" placeholder="Введите имя пользователя" name="dbUser" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="dbPassword" class="col-sm-5 col-form-label">
                                    Пароль
                                </label>
                                <div class="col-sm-7">
                                    <input type="password" class="form-control" id="dbPassword" placeholder="Введите пароль базы данных" name="dbPassword" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="dbTitle" class="col-sm-5 col-form-label">
                                    Наименование базы данных
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="dbTitle" placeholder="Введите наименование базы данных" name="dbTitle" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <span class="font-bold font-up">
                                    Настройка почтового сервера (SMTP)
                                </span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="form-group row">
                                <label for="mailHost" class="col-sm-5 col-form-label">
                                    Хост почтового сервера
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="mailHost" placeholder="Имя хоста почтового сервера" name="mailHost" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="mailPort" class="col-sm-5 col-form-label">
                                    Номер порта сервера
                                </label>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" id="mailPort" placeholder="Введите номер порта сервера" name="mailPort" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="mailUser" class="col-sm-5 col-form-label">
                                    Имя пользователя
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="mailUser" placeholder="Введите имя пользователя" name="mailUser" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="mailPassword" class="col-sm-5 col-form-label">
                                    Пароль почтового сервера
                                </label>
                                <div class="col-sm-7">
                                    <input type="password" class="form-control" id="mailPassword" placeholder="Введите пароль почтового сервера" name="mailPassword" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="mailEncType" class="col-sm-5 col-form-label">
                                    Тип шифрования
                                </label>
                                <div class="col-sm-7 cursor-point">
                                    <select id="mailEncType" class="form-control" name="mailEncType" required>
                                        <option value="null" disabled selected>
                                            Выберите тип шифрования
                                        </option>
                                        <option value="tls">
                                            TLS
                                        </option>
                                        <option value="ssl">
                                            SSL
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="mailEmail" class="col-sm-5 col-form-label">
                                    Адрес электронной почты
                                </label>
                                <div class="col-sm-7">
                                    <input type="email" class="form-control" id="mailEmail" placeholder="Введите адрес рабочей почты" name="mailEmail" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4" id="loading">
                <div class="col-12 py-1 px-4 alert alert-warning font-bold font-up d-flex align-items-center" role="alert">
                    <div class="lds-facebook"><div></div><div></div><div></div></div>
                    <div>
                        &nbsp;&nbsp;&nbsp;&nbsp;Загрузка...
                    </div>
                </div>
            </div>
            <div class="row mt-4" id="error">
                <div class="col-12 alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle fa-lg"></i>&nbsp;&nbsp;&nbsp;&nbsp;Возникла ошибка...
                </div>
            </div>
            <div class="row my-4">
                <div class="col-12 d-flex justify-content-center" id="submit">
                    <button class="btn btn-success btn_larger btn-shad no-round font-up font-bold mt-2">
                        Сохранить
                    </button>
                </div>
            </div>
            <br>
            <br>
            <br>
        <!-- </form> -->
        <?php endif;?>
	</div>
</body>
</html>
<script src="js/jquery-3.7.1.min.js"></script>
<script src="https://kit.fontawesome.com/42d566f993.js" crossorigin="anonymous"></script>
<script>
    $('#submit').on('click', function() {
        console.log('submit');
        $('#loading').css('display', 'block');
        $('#error').css('display', 'none');
        let settings = {
            'sysHost': $('#sysHost').val(),
            'sysProtocol': $('#sysProtocol').val(),
            'sysMode': $('#sysMode').val(),
            'dbHost': $('#dbHost').val(),
            'dbPort': $('#dbPort').val(),
            'dbUser': $('#dbUser').val(),
            'dbPassword': $('#dbPassword').val(),
            'dbTitle': $('#dbTitle').val(),
            'dbWithout': 1,
            'mailHost': $('#mailHost').val(),
            'mailPort': $('#mailPort').val(),
            'mailUser': $('#mailUser').val(),
            'mailPassword': $('#mailPassword').val(),
            'mailEncType': $('#mailEncType').val(),
            'mailEmail': $('#mailEmail').val(),
        };
        if ((settings.sysMode != "") && (settings.sysProtocol != "") && (settings.sysHost != "") && (settings.dbHost != "") && (settings.dbPort != "") && (settings.dbUser != "") && (settings.dbPassword != "") && (settings.dbTitle != "") && (settings.mailHost != "") && (settings.mailPort != "") && (settings.mailUser != "") && (settings.mailPassword != "") && (settings.mailEncType != "") && (settings.mailEmail != "")) {
            $.ajax({
                url: 'action.php',
                type: 'POST',
                data: settings,
                // contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    // $('#loading').css('display', 'none');
                    console.log(response);
                    window.location.reload();
                },        
                error: function(error) {
                    // alert('ENV creation error');
                    $('#loading').css('display', 'none');
                    $('#error').css('display', 'block');
                }
            });
            // console.log(settings);
        } else {
            $('#loading').css('display', 'none');
            $('#error').css('display', 'block');
        }
    });
</script>