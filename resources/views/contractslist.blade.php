@extends('layout')

@section('page', "Договоры")

@section('content')
<vue-list-contracts user-id="{{ Auth::id() }}"/>
@endsection