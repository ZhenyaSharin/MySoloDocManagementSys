@extends('layout')

@section('page', "История Ваших согласований")

@section('content')
<vue-list-history user-id="{{ Auth::id() }}"/>
@endsection