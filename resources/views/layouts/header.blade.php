<header>
	<div class="container-fluid d-flex justify-content-between align-items-center hdr p-0 pl-md-0 pr-md-3" id="top">
		<div class="d-flex justify-content-between align-items-center">
            @guest

            @else
                @if(!isset(Auth::user()->removed))
                <div class="hdr__sidebar hdr__sidebar_big shadow__lit">
                    <i class="fas fa-bars fa-3x"></i>
                </div>
                <div class="hdr__sidebar hdr__sidebar_small shadow__lit">
                    <i class="fas fa-bars fa-2x"></i>
                </div>
                @else
                <div class="hdr__sidebar_disabled">
                    <i class="fas fa-bars fa-3x"></i>
                </div>
                @endif
            @endguest
			<a class="font-bold logo ml-3 ml-md-4 shadow__lit" href="{{ url('/') }}">
	            {{ config('app.name', 'Laravel') }}
	        </a>
            <a class="font-bold logo_small ml-2 ml-md-4 shadow__lit" href="{{ url('/') }}">
                {{ mb_substr(config('app.name', 'Laravel'), 0, 1) }}
            </a>
        </div>
    	<ul class="d-flex flex-row align-items-center navbar-nav ml-auto">
            @guest
                @if (Route::has('login'))
                    <li class="nav-item">
                        <a class="link_login" href="{{ route('login') }}" title="Войти"><i class="fas fa-users fa-2x"></i>
                        </a>
                    </li>
                @endif

<!--                 @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif -->
            @else
                <li class="font-bold font-up hdr__search" class="mr-4">
                    <a href="{{ route('search') }}" class="mr-2 mr-sm-4">
                        <i class="fas fa-search fa-lg"></i>&nbsp;Поиск
                    </a>
                </li>
                <li class="d-flex nav-item dropdown">
<!--                     <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->login }}
                    </a> -->
<!--                     <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre> -->
                    <div class="hdr__fio mr-1 mr-md-4">
                        @if(isset(Auth::user()->surname) && isset(Auth::user()->firstname) && isset(Auth::user()->patronymic))
                        <a href="{{ route('account', ['login' => Auth::user()->login]) }}" class="hdr__name">
                            {{ Auth::user()->surname.' '.Auth::user()->firstname.' '.Auth::user()->patronymic }}
                        </a>
                        <a href="{{ route('account', ['login' => Auth::user()->login]) }}" class="hdr__name_short">
                            {{ Auth::user()->surname.' '.mb_substr(Auth::user()->firstname, 0, 1).'. '.mb_substr(Auth::user()->patronymic, 0, 1).'.' }}
                        </a>
                        @else 
                        {{ Auth::user()->login }}
                        @endif
                    </div>

                    <!-- </a> -->
                     <!-- type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong" -->

                    <!-- <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown"> -->
                    <a href="#" class="link_logout" data-toggle="modal" data-target="#exit__acc_modal">
                        <i class="fas fa-sign-out-alt fa-2x"></i>
                    </a>
                    <a href="#" class="link_logout_small" data-toggle="modal" data-target="#exit__acc_modal">
                        <i class="fas fa-sign-out-alt fa-lg"></i>
                    </a>
<!-- link_logout -->
                    <!-- </div> -->
                </li>
            @endguest
        </ul>
	</div>
</header>
@guest

@else
@if(!isset(Auth::user()->removed))
<div class="side-menu">
    <div>
        <a href="{{ url('/') }}">
            главная страница    
        </a>
    </div>
    <div>
<!--         <a href="#">
            о приложении
        </a>
        <a href="{{ route('info') }}">
            Руководство по эксплуатации
        </a> -->
        <ul class="submenu"> 
            <li>
                <a href="{{ route('info') }}">
                    Руководство по эксплуатации
                </a>
            </li>
<!--             <li>
                <a href="#coop">
                    сотрудничество
                </a>
            </li> -->
            <li>
                <a href="{{ route('blog') }}">
                    Информационная лента
                </a>
            </li>
<!--             <li>
                <a href="{{ route('feedback') }}">
                    поддержка для пользователей
                </a>
            </li> -->
        </ul>
    </div>
    <div>
        <a href="{{ route('search') }}">
            Поиск по системе
        </a>
    </div>
    <div>
        <a href="{{ route('docslist', ['type' => 2, 'page' => 1]) }}">
            Документы
        </a>
        <ul class="submenu"> 
            <li>
                <a href="{{ route('docslist', ['type' => 1, 'page' => 1]) }}">
                    Создать
                </a>
            </li>
            <li>
                <a href="{{ route('docslist', ['type' => 1, 'page' => 1]) }}">
                    Документы, созданные Вами
                </a>
            </li>
            <li>
                <a href="{{ route('memos', ['type' => 2, 'page' => 1]) }}">
                    Служебные записки
                </a>
            </li>
            <li>
                <a href="{{ route('contracts', ['type' => 2, 'page' => 1]) }}" class="font-up">
                    Договоры
                </a>
            </li>
            <li>
                <a href="{{ route('socs', ['type' => 2, 'page' => 1]) }}" class="ml-2">
                    Соглашения
                </a>
            </li>
            <li>
                <a href="{{ route('addagreements', ['type' => 2, 'page' => 1]) }}" class="ml-2">
                    Дополнительные соглашения
                </a>
            </li>
            <li>
                <span>
                    ПИСЬМА:
                </span>
            <li>
                <a href="{{ route('letters', ['type' => 2, 'page' => 1]) }}" class="ml-2">
                    Входящие письма
                </a>
            </li>
            <li>
                <a href="{{ route('outletters', ['type' => 2, 'page' => 1]) }}" class="ml-2">
                    Исходящие письма
                </a>
            </li>
            <li>
                <a href="{{ route('ordersod', ['type' => 2, 'page' => 1]) }}">
                    Приказы по организационной деятельности
                </a>
            </li>
            <li>
                <a href="{{ route('notifications', ['type' => 2, 'page' => 1]) }}">
                    Уведомления
                </a>
            </li>
            <li>
                <a href="{{ route('ordocs', ['type' => 2, 'page' => 1]) }}">
                    Организационно-распорядительные документы
                </a>
            </li>
            <li>
                <a href="{{ route('otherDocs', ['type' => 2, 'page' => 1]) }}">
                    Прочие документы
                </a>
            </li>
            <li>
                <a href="{{ route('archivedocs', ['page' => 1]) }}">
                    Архив документов
                </a>
            </li>
        </ul>
    </div>
    <div>
        <a href="{{ route('assignslist', ['type' => 3, 'page' => 1]) }}">
            Поручения
        </a>
        <ul class="submenu"> 
            <li>
                <a href="{{ route('assignslist', ['type' => 1, 'page' => 1]) }}">
                    Создать
                </a>
            </li>
            <li>
                <a href="{{ route('assignslist', ['type' => 2, 'page' => 1]) }}">
                    Поручения, присланные Вам
                </a>
            </li>
             <li>
                <a href="{{ route('assignslist', ['type' => 1, 'page' => 1]) }}">
                    Поручения, созданные Вами
                </a>
            </li>
        </ul>
    </div>
    <div>
        <a href="{{ route('newagreementslist') }}">
            Актуальные заявки на согласование  
        </a>
    </div>
    <div>
        <a href="{{ route('history') }}">
            ИСТОРИЯ ВАШИХ СОГЛАСОВАНИЙ  
        </a>
    </div>
    <div>
        <a href="{{ route('agreementslist') }}">
            Ваши заявки на согласование
        </a>
    </div>
    <div>
        <a href="{{ route('acquaintances') }}">
            Заявки на ознакомление
        </a>
    </div>
    <div>
        <a href="{{ route('analytics') }}">
            Общая аналитика
        </a>
    </div>
<!--     <div>
        <a href="#conts">
            контакты
        </a>
        <ul class="submenu"> 
            <li>
                <a href="#">
                    контакты разработчика
                </a>
            </li>
        </ul>
    </div> -->
</div>
@endif
@endguest
@include('modals.exit')
