@extends('layout')

@section('page', 'Прочие документы')

@section('content')
<vue-list-otherdocs user-id="{{ Auth::id() }}"/>
@endsection