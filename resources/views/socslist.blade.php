@extends('layout')

@section('page', 'Соглашения')

@section('content')
<vue-list-socs user-id="{{ Auth::id() }}"/>
@endsection