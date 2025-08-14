<div class="col-lg-12 text-center">
    <div class="pagination__option">
        {{-- В начало --}}
        @if($navData['links']['first'])
            <a href="{{ $navData['links']['first'] }}"><i class="fa fa-angle-double-left"></i></a>
        @else
            <span class="disabled"><i class="fa fa-angle-double-left"></i></span>
        @endif

        {{-- Назад --}}
        @if($navData['links']['prev'])
            <a href="{{ $navData['links']['prev'] }}"><i class="fa fa-angle-left"></i></a>
        @else
            <span class="disabled"><i class="fa fa-angle-left"></i></span>
        @endif

        {{-- Страницы --}}
        @foreach($navData['pages'] as $page)
            @if($page['is_current'])
                <span class="active">{{ $page['number'] }}</span>
            @else
                <a href="{{ $page['url'] }}">{{ $page['number'] }}</a>
            @endif
        @endforeach

        {{-- Вперёд --}}
        @if($navData['links']['next'])
            <a href="{{ $navData['links']['next'] }}"><i class="fa fa-angle-right"></i></a>
        @else
            <span class="disabled"><i class="fa fa-angle-right"></i></span>
        @endif

        {{-- В конец --}}
        @if($navData['links']['last'])
            <a href="{{ $navData['links']['last'] }}"><i class="fa fa-angle-double-right"></i></a>
        @else
            <span class="disabled"><i class="fa fa-angle-double-right"></i></span>
        @endif
    </div>
</div>