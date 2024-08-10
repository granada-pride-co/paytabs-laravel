<?php

declare(strict_types=1);

namespace GranadaPride\Paytabs;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Paytabs
{
    protected string $cartId;

    protected float $cartAmount;

    protected string $cartDescription;

    protected string $customerName;

    protected string $customerPhone;

    protected string $customerEmail;

    protected string $customerStreet;

    protected string $customerCity;

    protected string $customerState;

    protected string $customerCountry;

    protected string $customerZipCode;

    protected string $shippingName;

    protected string $shippingPhone;

    protected string $shippingEmail;

    protected string $shippingStreet;

    protected string $shippingCity;

    protected string $shippingState;

    protected string $shippingCountry;

    protected string $shippingZipCode;

    protected string $callbackUrl;

    protected string $returnUrl;

    protected string $paypageLang;

    protected bool $hideShipping = false;

    public function __construct() {}

    public static function make(): static
    {
        return new static;
    }

    public function setCart($cartId, $cartAmount, $cartDescription): static
    {
        $this->cartId = $cartId;
        $this->cartAmount = $cartAmount;
        $this->cartDescription = $cartDescription;

        return $this;
    }

    public function getCart(): array
    {
        return [
            'cart_id' => $this->cartId,
            'cart_amount' => $this->cartAmount,
            'cart_description' => $this->cartDescription,
            'cart_currency' => config('paytabs.currency'),
        ];
    }

    public function setCustomer(
        $customerName,
        $customerPhone,
        $customerEmail,
        $customerStreet,
        $customerCity,
        $customerState,
        $customerCountry,
        $customerZipCode
    ): static {
        $this->customerName = $customerName;
        $this->customerPhone = $customerPhone;
        $this->customerEmail = $customerEmail;
        $this->customerStreet = $customerStreet;
        $this->customerCity = $customerCity;
        $this->customerState = $customerState;
        $this->customerCountry = $customerCountry;
        $this->customerZipCode = $customerZipCode;

        return $this;
    }

    public function getCustomer(): array
    {
        return [
            'name' => $this->customerName,
            'phone' => $this->customerPhone,
            'email' => $this->customerEmail,
            'street1' => $this->customerStreet,
            'city' => $this->customerCity,
            'state' => $this->customerState,
            'country' => $this->customerCountry,
            'zip' => $this->customerZipCode,
        ];
    }

    public function setShipping(
        $shippingName,
        $shippingPhone,
        $shippingEmail,
        $shippingStreet,
        $shippingCity,
        $shippingState,
        $shippingCountry,
        $shippingZipCode
    ): static {
        $this->shippingName = $shippingName;
        $this->shippingPhone = $shippingPhone;
        $this->shippingEmail = $shippingEmail;
        $this->shippingStreet = $shippingStreet;
        $this->shippingCity = $shippingCity;
        $this->shippingState = $shippingState;
        $this->shippingCountry = $shippingCountry;
        $this->shippingZipCode = $shippingZipCode;

        return $this;
    }

    public function getShipping(): array
    {
        return [
            'name' => $this->shippingName,
            'phone' => $this->shippingPhone,
            'email' => $this->shippingEmail,
            'street1' => $this->shippingStreet,
            'city' => $this->shippingCity,
            'state' => $this->shippingState,
            'country' => $this->shippingCountry,
            'zip' => $this->shippingZipCode,
        ];
    }

    public function setCallbackUrl(string $callbackUrl): static
    {
        $this->callbackUrl = $callbackUrl;

        return $this;
    }

    public function getCallbackUrl(): string
    {
        return $this->callbackUrl;
    }

    public function setReturnUrl(string $returnUrl): static
    {
        $this->returnUrl = $returnUrl;

        return $this;
    }

    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    public function setPaypageLang(string $paypageLang): static
    {
        $this->paypageLang = $paypageLang;

        return $this;
    }

    public function getPaypageLang(): string
    {
        return $this->paypageLang;
    }

    public function setHideShipping(bool $hide): static
    {
        $this->hideShipping = $hide;

        return $this;
    }

    public function getHideShipping(): bool
    {
        return $this->hideShipping;
    }

    protected function initialize(): PendingRequest
    {
        return Http::withHeaders([
            'authorization' => config('paytabs.server_key'),
        ])->baseUrl($this->getBaseUrl());
    }

    public function paypage()
    {
        return $this->initialize()
            ->post('payment/request', $this->paypagePayload())
            ->json();
    }

    protected function paypagePayload(): array
    {
        return [
            'profile_id' => intval(config('paytabs.profile_id')),
            'tran_type' => 'sale',
            'tran_class' => 'ecom',
            'paypage_lang' => $this->paypageLang,
            'callback' => $this->callbackUrl,
            'return' => $this->returnUrl,
            'user_defined' => [
                'udf3' => 'UDF3 Test3',
                'udf9' => 'UDF9 Test9',
            ],
            'hide_shipping' => $this->hideShipping,
            'customer_details' => [
                ...$this->getCustomer(),
            ],
            'shipping_details' => [
                ...$this->getShipping(),
            ],
            ...$this->getCart(),
        ];
    }

    protected function getBaseUrl(): string
    {
        $region = Str::upper(config('paytabs.region'));

        return match ($region) {
            'ARE' => 'https://secure.paytabs.com',
            'SAU' => 'https://secure.paytabs.sa',
            'OMN' => 'https://secure-oman.paytabs.com',
            'JOR' => 'https://secure-jordan.paytabs.com',
            'EGY' => 'https://secure-egypt.paytabs.com',
            'IRQ' => 'https://secure-iraq.paytabs.com',
            'PSE' => 'https://secure-palestine.paytabs.com',
            'GLOBAL' => 'https://secure-global.paytabs.com',
        };
    }
}
