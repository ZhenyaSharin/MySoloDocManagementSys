@extends('layout')

@section('page', 'Архив карточек документов')

@section('content')
<vue-list-archivedocs user-id="{{ Auth::id() }}"/>
@endsection