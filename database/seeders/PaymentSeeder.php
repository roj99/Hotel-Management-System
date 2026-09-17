<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Booking\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    // Every payment must reference the SAME booking as its invoice - a payment
    // can never settle a different booking's invoice, so we always derive
    // booking_id from the invoice rather than picking one independently.
    public function run(): void
    {
        Invoice::all()->each(function ($invoice) {
            Payment::factory()->create([
                'booking_id' => $invoice->booking_id,
                'invoice_id' => $invoice->id,
                'amount' => $invoice->total_amount,
                'type' => 'full_payment',
            ]);
        });
    }
}
