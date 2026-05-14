@extends('layout')

@section('page', 'Уведомления')

@section('content')
<vue-list-notifications user-id="{{ Auth::id() }}"/>
@endsection