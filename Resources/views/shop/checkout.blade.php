<div>
    <h1>Checkout</h1>

    <form method="POST" action="{{ route('shop.checkout.process') }}">
        @csrf
        <label for="payment_method">Payment Method</label>
        <select id="payment_method" name="payment_method">
            <option value="stripe">Stripe</option>
            <option value="paypal">PayPal</option>
        </select>

        <button type="submit">Pay Now</button>
    </form>
</div>
