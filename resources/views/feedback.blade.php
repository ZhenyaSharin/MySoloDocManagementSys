@extends('layout')

@section('page', "Обращения пользователей")

@section('content')
<vue-feedback user-id="{{ Auth::id() }}"/>
@endsection