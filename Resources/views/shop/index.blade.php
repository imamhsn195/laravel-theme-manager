<div>
    <h1>Shop</h1>

    <ul>
        @foreach($products as $product)
            <li>
                <a href="{{ route('shop.show', $product->slug) }}">{{ $product->name }}</a>
                <span>${{ number_format($product->price, 2) }}</span>
                <form method="POST" action="{{ route('shop.cart.add') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit">Add to cart</button>
                </form>
            </li>
        @endforeach
    </ul>

    {{ $products->links() }}
</div>
