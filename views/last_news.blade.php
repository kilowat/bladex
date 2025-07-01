<div class="recent-news">
    <h3>Последние новости</h3>
    <ul>
        @foreach ($items as $item)
            <li><a href="{{ useRouter()->route('test') }}">{{ $item['title'] }}</a></li>

        @endforeach
    </ul>
</div>