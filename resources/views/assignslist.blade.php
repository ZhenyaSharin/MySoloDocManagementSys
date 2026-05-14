@extends('layout')

@section('page', "Поручения")

@section('content')
<vue-list-assignments user-id="{{ Auth::id() }}"/>
@endsection