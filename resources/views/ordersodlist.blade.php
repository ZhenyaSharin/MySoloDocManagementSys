@extends('layout')

@section('page', 'Приказы по О.Д.')

@section('content')
<vue-list-ordersod user-id="{{ Auth::id() }}"/>
@endsection