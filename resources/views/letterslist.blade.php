@extends('layout')

@section('page', "Список входящих писем")

@section('content')
<vue-list-letters user-id="{{ Auth::id() }}"/>
@endsection