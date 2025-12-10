<div>
    <h1>{{ $theme->name }}</h1>
    <p>{{ $theme->description }}</p>

    <form method="POST" action="{{ route('marketplace.cart.add') }}">
        @csrf
        <input type="hidden" name="theme_id" value="{{ $theme->id }}">
        <button type="submit">Add to cart</button>
    </form>
</div>
