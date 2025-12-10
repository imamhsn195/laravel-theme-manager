<div>
    <h1>{{ $product->name }}</h1>
    <p>{{ $product->description }}</p>
    <strong>${{ number_format($product->price, 2) }}</strong>

    <form method="POST" action="{{ route('shop.cart.add') }}">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <button type="submit">Add to cart</button>
    </form>
</div>
