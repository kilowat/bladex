<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <nav aria-label="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
                        @foreach($items as $index => $item)
                            <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                @if($item['url'] && $index !== count($items) - 1)
                                    <a href="{{ $item['url'] }}" itemprop="item"><span
                                            itemprop="name">{{ $item['title'] }}</span></a>
                                @else
                                    <span itemprop="name">{{ $item['title'] }}</span>
                                @endif
                                <meta itemprop="position" content="{{ $index + 1 }}" />
                            </span>
                            @if(!$loop->last) <span class="separator"> </span> @endif
                        @endforeach
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>