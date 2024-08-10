<?php

declare(strict_types=1);

namespace GranadaPride\Paytabs\Facades;

use Illuminate\Support\Facades\Facade;

class Paytabs extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'paytabs';
    }
}
