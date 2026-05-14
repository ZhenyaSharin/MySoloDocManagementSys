@extends('layout')

@section('page', "Заявки на согласование")

@section('content')
<vue-list-newagreements user-id="{{ Auth::id() }}"/>
@endsection