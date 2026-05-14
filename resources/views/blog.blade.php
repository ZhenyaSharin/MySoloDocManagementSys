@extends('layout')

@section('page', "Информационная лента")

@section('content')
<vue-blog user-id="{{ Auth::id() }}"/>
@endsection