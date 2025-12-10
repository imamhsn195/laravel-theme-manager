<div>
    <h1>Your Cart</h1>

    <ul>
        @forelse($items as $item)
            <li>
                {{ $item->name }} x {{ $quantities[$item->id] ?? 1 }}
                <form method="POST" action="{{ route('shop.cart.remove', $item->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Remove</button>
                </form>
            </li>
        @empty
            <li>No items yet.</li>
        @endforelse
    </ul>

    <a href="{{ route('shop.checkout.index') }}">Checkout</a>
</div>
