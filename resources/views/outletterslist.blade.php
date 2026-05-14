@extends('layout')

@section('page', 'Исходящие письма')

@section('content')
<vue-list-outletters user-id="{{ Auth::id() }}"/>
@endsection