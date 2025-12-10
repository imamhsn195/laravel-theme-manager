<?php

namespace ImamHasan\ThemeManager\Services\Payments;

use Illuminate\Contracts\Container\Container;
use ImamHasan\ThemeManager\Contracts\PaymentGateway;
use InvalidArgumentException;

class PaymentGatewayManager
{
    protected array $resolved = [];

    public function __construct(
        protected Container $app,
        protected array $config
    ) {
    }

    public function gateway(?string $name = null): PaymentGateway
    {
        $name ??= $this->config['default'] ?? 'stripe';

        if (! isset($this->resolved[$name])) {
            $this->resolved[$name] = $this->resolve($name);
        }

        return $this->resolved[$name];
    }

    protected function resolve(string $name): PaymentGateway
    {
        $gateways = $this->config['gateways'] ?? [];

        if (! isset($gateways[$name])) {
            throw new InvalidArgumentException("Payment gateway [{$name}] is not configured.");
        }

        return match ($name) {
            'stripe' => new StripeGateway($gateways[$name]),
            'paypal' => new PayPalGateway($gateways[$name]),
            'ngenius' => new NgeniusGateway($gateways[$name]),
            default => throw new InvalidArgumentException("Payment gateway [{$name}] is not supported."),
        };
    }
}
