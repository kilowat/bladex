<div class="recent-news">
    <h3>Последние новости</h3>
    <ul>
        @foreach ($items as $item)
            <li>{{ $item['title'] }}</li>
        @endforeach
    </ul>
</div>