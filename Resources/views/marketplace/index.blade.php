<div>
    <h1>Marketplace</h1>

    <section>
        <h2>Featured Themes</h2>
        <ul>
            @foreach($featured as $theme)
                <li><a href="{{ route('marketplace.theme.show', $theme->slug) }}">{{ $theme->name }}</a></li>
            @endforeach
        </ul>
    </section>

    <section>
        <h2>All Themes</h2>
        <ul>
            @foreach($themes as $theme)
                <li><a href="{{ route('marketplace.theme.show', $theme->slug) }}">{{ $theme->name }}</a></li>
            @endforeach
        </ul>

        {{ $themes->links() }}
    </section>
</div>
