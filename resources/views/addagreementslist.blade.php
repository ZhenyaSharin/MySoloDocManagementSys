@extends('layout')

@section('page', 'Дополнительные соглашения')

@section('content')
<vue-list-addagreements user-id="{{ Auth::id() }}"/>
@endsection