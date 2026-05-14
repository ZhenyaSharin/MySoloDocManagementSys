@extends('layout')

@section('page', 'Список ознакомлений')

@section('content')
<vue-list-acquaintances user-id="{{ Auth::id() }}" surname="{{ Auth::user()->surname }}" firstname="{{ Auth::user()->firstname }}" patronymic="{{ Auth::user()->patronymic }}"/>
@endsection