@extends('layout')

@section('page', "Страница $login")

@section('content')
<vue-page-user login="{{ $login }}" :user-id="{{ Auth::id() }}"/>
@endsection