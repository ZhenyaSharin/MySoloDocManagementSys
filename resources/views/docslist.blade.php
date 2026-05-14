@extends('layout')

@section('page', "Карточки документов")

@section('content')
<vue-list-documents user-id="{{ Auth::id() }}"/>
@endsection
