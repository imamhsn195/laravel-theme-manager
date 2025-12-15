<?php

namespace ImamHasan\ThemeManager\Services;

use Illuminate\Contracts\Session\Session;
use ImamHasan\ThemeManager\Models\TmProduct;

class CartService
{
    protected string $sessionKey = 'theme_manager_cart';

    public function __construct(protected Session $session)
    {
    }

    public function all(): array
    {
        return $this->session->get($this->sessionKey, []);
    }

    public function add(int $productId, int $quantity = 1): void
    {
        $cart = $this->all();
        $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;
        $this->session->put($this->sessionKey, $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->all();
        unset($cart[$productId]);
        $this->session->put($this->sessionKey, $cart);
    }

    public function clear(): void
    {
        $this->session->forget($this->sessionKey);
    }

    public function items()
    {
        $ids = array_keys($this->all());
        return TmProduct::whereIn('id', $ids)->get();
    }
}
