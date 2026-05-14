@extends('layout')

@section('content')

@section('page', 'Главная страница')

@if(isset(Auth::user()->removed))
<vue-panel-blocked user-id="{{ Auth::id() }}"/>
@else
<vue-page-home user-id="{{ Auth::id() }}" :role-id="{{ Auth::user()->roleid }}"/>
@endif

@endsection
