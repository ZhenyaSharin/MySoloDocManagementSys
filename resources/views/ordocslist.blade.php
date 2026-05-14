@extends('layout')

@section('page', 'Организационно-распорядительные документы')

@section('content')
<vue-list-ordocs user-id="{{ Auth::id() }}"/>
@endsection