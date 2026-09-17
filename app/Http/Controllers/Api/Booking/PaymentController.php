<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StorePaymentRequest;
use App\Http\Requests\Booking\UpdatePaymentRequest;
use App\Http\Resources\Booking\PaymentResource;
use App\Models\Booking\Payment;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function index(): JsonResponse
    {
        $payments = Payment::all();

        return response()->json(PaymentResource::collection($payments));
    }

    public function store(StorePaymentRequest $request): JsonResponse
    {
        $payment = Payment::create($request->validated());

        return response()->json(new PaymentResource($payment), 201);
    }

    public function show(Payment $payment): JsonResponse
    {
        return response()->json(new PaymentResource($payment));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment): JsonResponse
    {
        $payment->update($request->validated());

        return response()->json(new PaymentResource($payment));
    }

    public function destroy(Payment $payment): JsonResponse
    {
        $payment->delete();

        return response()->json(null, 204);
    }
}
