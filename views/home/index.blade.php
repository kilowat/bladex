@extends('layouts.app')

{{ $siteName }}

@section('content')
    Контент главная страница
    @widget('lastNews')
@endsection