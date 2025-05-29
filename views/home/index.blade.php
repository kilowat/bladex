<?php
// Подход 1: PHPDoc аннотации для шаблонов
// /local/views/home/index.blade.php

/**
 * Home page template
 * 
 * @param string $siteName - Название сайта
 * @param array $news - Массив новостей с полями: title, date, content
 * @param bool $showSidebar - Показывать ли боковую панель
 * @param string $metaTitle - Заголовок для meta
 * @param string $metaDescription - Описание для meta
 */
?>

@extends('layouts.app')

@section('title', $title)

@push('styles')
    <style>
        .news-item {
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('content')
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