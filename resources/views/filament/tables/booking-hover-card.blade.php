@php
    $rooms = $record->rooms;

    $payment = $record->payments->first();

    $roomNumbers = $rooms
        ->map(fn ($room) =>
            $room->room_number
            ?? $room->number
            ?? $room->room_no
            ?? $room->id
        )
        ->filter()
        ->implode(', ');

    $roomTypes = $rooms
        ->map(function ($room) {
            $roomType = $room->roomType
                ?? $room->room_type
                ?? $room->type
                ?? null;

            return $roomType?->name
                ?? $roomType?->type_name
                ?? $roomType?->title
                ?? null;
        })
        ->filter()
        ->unique()
        ->implode(', ');

    $paymentMethod = $payment?->payment_method
        ?? $payment?->method
        ?? 'N/A';

    $recordedBy = $record->user?->full_name
        ?? $record->user?->name
        ?? 'N/A';
@endphp

<div class="booking-hover-wrapper">

    <span class="booking-hover-trigger">
        <span class="booking-hover-icon">ⓘ</span>

    </span>

    <div class="booking-hover-card">

        <div class="booking-detail">
            <div class="booking-icon">🛏️</div>
            <div>
                <span class="booking-label">Room Number:</span>
                <span class="booking-value">
                    {{ $roomNumbers ?: 'N/A' }}
                </span>
            </div>
        </div>

        <div class="booking-detail">
            <div class="booking-icon">🏢</div>
            <div>
                <span class="booking-label">Room Type:</span>
                <span class="booking-value">
                    {{ $roomTypes ?: 'N/A' }}
                </span>
            </div>
        </div>

        <div class="booking-detail">
            <div class="booking-icon">👥</div>
            <div>
                <span class="booking-label">Guests:</span>
                <span class="booking-value">
                    {{ $record->guests_count ?? 0 }}
                </span>
            </div>
        </div>

        <div class="booking-detail">
            <div class="booking-icon">📊</div>
            <div>
                <span class="booking-label">Status:</span>
                <span class="booking-value">
                    {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                </span>
            </div>
        </div>

        <div class="booking-detail">
            <div class="booking-icon">💰</div>
            <div>
                <span class="booking-label">Total Price is </span>
                <span class="booking-value">
                    ${{ number_format((float) $record->total_price, 2) }}
                </span>
            </div>
        </div>

        <div class="booking-detail">
            <div class="booking-icon">👤</div>
            <div>
                <span class="booking-label">Recorded By:</span>
                <span class="booking-value">
                    {{ $recordedBy }}
                </span>
            </div>
        </div>

    </div>
</div>
