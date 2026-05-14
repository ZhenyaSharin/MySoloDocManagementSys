@extends('layout')

@section('page', 'Поиск')

@section('content')
<vue-page-search user-id="{{ Auth::id() }}"/>
@endsection