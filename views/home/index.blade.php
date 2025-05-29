@php /** @var \Views\Home\IndexViewModel $data */ @endphp

@extends('layouts.app')


@push('styles')
    <style>
        .news-item {
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('content')
    {{ $data->name }}
    <h2>{{ $title }}</h2>
    <p>{{ $description }}</p>

    @if(!empty($news))
        <div class="news-list">
            @foreach($news as $item)
                <div class="news-item">
                    <h3><a href="{{ $item['DETAIL_PAGE_URL'] }}">{{ $item['NAME'] }}</a></h3>
                    <p>{{ $item['PREVIEW_TEXT'] }}</p>
                </div>
            @endforeach
        </div>
    @else
        <p>Новости не найдены</p>
    @endif

@endsection