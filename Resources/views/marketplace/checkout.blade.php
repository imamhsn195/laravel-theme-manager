<div>
    <h1>Checkout</h1>

    <form method="POST" action="{{ route('marketplace.checkout.process') }}">
        @csrf
        <label for="theme_id">Select Theme</label>
        <select name="theme_id" id="theme_id">
            @foreach($themes as $theme)
                <option value="{{ $theme->id }}">{{ $theme->name }}</option>
            @endforeach
        </select>

        <button type="submit">Complete Purchase</button>
    </form>
</div>
