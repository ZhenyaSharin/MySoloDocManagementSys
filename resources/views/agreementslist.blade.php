@extends('layout')

@section('page', 'Рассмотрение Ваших заявок на согласование')

@section('content')
<vue-list-agreements user-id="{{ Auth::id() }}"/>
@endsection
