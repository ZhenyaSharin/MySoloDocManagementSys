@extends('layout')

@section('page', "Поручение-$id")

@section('content')
<vue-page-assign :id="{{ $id }}" user-id="{{ Auth::id() }}"/>
@endsection