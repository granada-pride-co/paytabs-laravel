<?php

declare(strict_types=1);

namespace GranadaPride\Paytabs\DTO;

class CustomerDetails
{
    public function __construct(
        public string $name,
        public string $phone,
        public string $email,
        public string $street,
        public string $city,
        public string $state,
        public string $country,
        public string $zipCode
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'street1' => $this->street,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'zip' => $this->zipCode,
        ];
    }
}
