<?php

namespace App\Filament\Resources\Booking\Payments\Pages;

use App\Filament\Resources\Booking\Payments\PaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;
}
