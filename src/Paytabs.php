<?php

declare(strict_types=1);

namespace GranadaPride\Paytabs;

use Exception;
use GranadaPride\Paytabs\DTO\CustomerDetails;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;

class Paytabs
{

    protected string $cartId;

    protected float $cartAmount;

    protected string $cartDescription;

    protected CustomerDetails $customerDetails;

    protected CustomerDetails $shippingDetails;

    protected string $callbackUrl;

    protected string $returnUrl;

    protected string $paypageLang;

    protected bool $hideShipping = false;

    protected bool $framed = false;

    protected bool $framedReturnTop = false;

    protected bool $framedReturnParent = false;

    protected string $framedMessageTarget;


    public function __construct(
        private ?string $currency = null,
        private ?string $serverKey = null,
        private ?string $profileId = null,
        private ?string $region = null)
    {
        $this->currency = $currency ?? config('paytabs.currency');
        $this->serverKey = $serverKey ?? config('paytabs.server_key');
        $this->profileId = $profileId ?? config('paytabs.profile_id');
        $this->region = $region ?? config('paytabs.region');
    }

    public static function make(): static
    {
        return new static;
    }

    public function setCart(string $cartId, float $cartAmount, string $cartDescription): static
    {
        if ($cartAmount <= 0) {
            throw new InvalidArgumentException('Cart amount must be greater than zero.');
        }

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
            'cart_currency' => $this->currency,
        ];
    }

    public function setCustomer(CustomerDetails $details): static
    {
        $this->customerDetails = $details;

        return $this;
    }

    public function setShipping(CustomerDetails $details): static
    {
        $this->shippingDetails = $details;

        return $this;
    }

    public function useCustomerForShipping(): static
    {
        $this->shippingDetails = $this->customerDetails;

        return $this;
    }

    public function getCallbackUrl(): string
    {
        return $this->callbackUrl;
    }

    public function setCallbackUrl(string $callbackUrl): static
    {
        $this->callbackUrl = $callbackUrl;

        return $this;
    }

    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    public function setReturnUrl(string $returnUrl): static
    {
        $this->returnUrl = $returnUrl;

        return $this;
    }

    public function getPaypageLang(): string
    {
        return $this->paypageLang;
    }

    public function setPaypageLang(string $paypageLang): static
    {
        $this->paypageLang = $paypageLang;

        return $this;
    }

    public function getHideShipping(): bool
    {
        return $this->hideShipping;
    }

    public function setHideShipping(bool $hide): static
    {
        $this->hideShipping = $hide;

        return $this;
    }

    public function paypage()
    {
        try {
            return $this->initialize()
                ->post('payment/request', $this->paypagePayload())
                ->json();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to create PayTabs payment page: ' . $e->getMessage());
        }
    }

    protected function initialize(): PendingRequest
    {
        return Http::withHeaders([
            'authorization' => $this->serverKey,
        ])->baseUrl($this->getBaseUrl());
    }

    protected function getBaseUrl(): string
    {
        $region = Str::upper($this->region);

        return match ($region) {
            'ARE' => 'https://secure.paytabs.com',
            'SAU' => 'https://secure.paytabs.sa',
            'OMN' => 'https://secure-oman.paytabs.com',
            'JOR' => 'https://secure-jordan.paytabs.com',
            'EGY' => 'https://secure-egypt.paytabs.com',
            'IRQ' => 'https://secure-iraq.paytabs.com',
            'PSE' => 'https://secure-palestine.paytabs.com',
            'GLOBAL' => 'https://secure-global.paytabs.com',
            default => throw new InvalidArgumentException("Unsupported region: $region"),
        };
    }

    protected function paypagePayload(): array
    {
        return [
            'profile_id' => intval($this->profileId),
            'tran_type' => 'sale',
            'tran_class' => 'ecom',
            'paypage_lang' => $this->paypageLang,
            'callback' => $this->callbackUrl,
            'return' => $this->returnUrl,
            'user_defined' => [
                'udf3' => 'UDF3 Test3',
                'udf9' => 'UDF9 Test9',
            ],
            'customer_details' => $this->getCustomer(),
            'shipping_details' => $this->getShipping(),
            'cart_id' => $this->cartId,
            'cart_amount' => $this->cartAmount,
            'cart_description' => $this->cartDescription,
            'cart_currency' => $this->currency,
            'framed' => $this->framed,
            'framed_return_top' => $this->framedReturnTop,
            'framed_return_parent' => $this->framedReturnParent,
            'framed_message_target' => $this->framedMessageTarget,
        ];
    }

    public function getCustomer(): array
    {
        return $this->customerDetails->toArray();
    }

    public function getShipping(): array
    {
        return $this->shippingDetails->toArray();
    }

    public function queryTransaction(string $transactionReference)
    {
        try {
            return $this->initialize()
                ->post('payment/query', [
                    'profile_id' => intval($this->profileId),
                    'tran_ref' => $transactionReference,
                ])->json();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to fetch PayTabs query transaction: ' . $e->getMessage());
        }
    }

    public function displayIFrame(): static
    {
        $this->framed = true;
        $this->framedReturnTop = true;
        $this->framedReturnParent = true;
        $this->framedMessageTarget = config('paytabs.iframe_message_target');;

        return $this;
    }
}
