<div>
    <h1>Cart</h1>

    <ul>
        @foreach($items as $item)
            <li>
                {{ $item->name }}
                <form method="POST" action="{{ route('marketplace.cart.remove', $item->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Remove</button>
                </form>
            </li>
        @endforeach
    </ul>

    <a href="{{ route('marketplace.checkout.index') }}">Proceed to checkout</a>
</div>
