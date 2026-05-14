@extends('layout')

@section('page', "Карточка документа-$id")

@section('content')
<vue-page-doc :id="{{ $id }}" :user-id="{{ Auth::id() }}"/>
@endsection