@extends('layouts.frontend')

@section('content')

<section class="page-header">
    <div>
        <p>PAYMENT</p>
        <h1>Payment Successful</h1>
        <span></span>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width:560px;">

        <div id="loading-msg">Loading your booking details...</div>

        <div id="load-error" class="alert-error" style="display:none;"></div>

        <div id="booking-summary" class="info-card" style="display:none;">
            <p>Your deposit was received. Here are your booking details:</p>

            <div style="margin-top:15px;">
                <div>Booking ID: <strong id="b-id"></strong></div>
                <div>Status: <strong id="b-status"></strong></div>
                <div>Room(s): <strong id="b-rooms"></strong></div>
                <div>Check-in: <strong id="b-checkin"></strong></div>
                <div>Check-out: <strong id="b-checkout"></strong></div>
                <div>Total price: <strong id="b-total"></strong></div>
                <div>Deposit paid: <strong id="b-deposit"></strong></div>
            </div>

            <p style="margin-top:15px;">
                If the status still shows "pending", it will update to "confirmed" automatically
                once our system finishes processing your payment.
            </p>
        </div>

        <p style="margin-top:20px;">
            <a href="{{ route('rooms.index') }}">Browse more rooms</a>
        </p>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', async function () {
    const params = new URLSearchParams(window.location.search);
    const bookingId = params.get('booking_id');
    const token = localStorage.getItem('token');

    const loadingMsg = document.getElementById('loading-msg');
    const errorBox = document.getElementById('load-error');

    if (!bookingId || !token) {
        loadingMsg.style.display = 'none';
        errorBox.textContent = 'Could not load booking details automatically. Please check "My Bookings" instead.';
        errorBox.style.display = 'block';
        return;
    }

    try {
        const res = await fetch('/api/bookings/' + bookingId, {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json',
            },
        });

        if (!res.ok) {
            throw new Error('Failed to load booking');
        }

        const booking = await res.json();

        const roomNumbers = (booking.rooms || [])
            .map(r => r.room_number ?? r.id)
            .join(', ');

        document.getElementById('b-id').textContent = booking.id;
        document.getElementById('b-status').textContent = booking.status;
        document.getElementById('b-rooms').textContent = roomNumbers || 'N/A';
        document.getElementById('b-checkin').textContent = booking.check_in_date;
        document.getElementById('b-checkout').textContent = booking.check_out_date;
        document.getElementById('b-total').textContent = '$' + Number(booking.total_price).toFixed(2);
        document.getElementById('b-deposit').textContent = '$' + Number(booking.deposit_amount).toFixed(2);

        loadingMsg.style.display = 'none';
        document.getElementById('booking-summary').style.display = 'block';
    } catch (err) {
        loadingMsg.style.display = 'none';
        errorBox.textContent = 'Could not load booking details automatically. Please check "My Bookings" instead.';
        errorBox.style.display = 'block';
    }
});
</script>

@endsection
