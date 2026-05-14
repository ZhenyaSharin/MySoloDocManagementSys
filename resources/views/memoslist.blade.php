@extends('layout')

@section('page', "Служебные записки")

@section('content')
<vue-list-memos user-id="{{ Auth::id() }}"/>
@endsection