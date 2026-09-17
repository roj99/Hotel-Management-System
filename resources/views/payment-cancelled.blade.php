@extends('layouts.frontend')

@section('content')

<section class="page-header">
    <div>
        <p>PAYMENT</p>
        <h1>Payment Cancelled</h1>
        <span></span>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width:560px;">

        <p>
            Your payment was not completed, so your deposit was not charged.
            Your booking is still marked as <strong>pending</strong> and holds the
            room for now.
        </p>

        <div id="cancel-error" class="alert-error" style="display:none;"></div>
        <div id="cancel-success" class="alert-success" style="display:none;"></div>

        <button type="button" id="cancel-booking-btn" class="btn" style="margin-top:20px; border:none; cursor:pointer;">
            Cancel this booking
        </button>

        <p style="margin-top:20px;">
            <a href="{{ route('rooms.index') }}">Or browse rooms and try again</a>
        </p>

    </div>
</section>

<script>
document.getElementById('cancel-booking-btn').addEventListener('click', async function () {
    const params = new URLSearchParams(window.location.search);
    const bookingId = params.get('booking_id');
    const token = localStorage.getItem('token');
    const btn = this;
    const errorBox = document.getElementById('cancel-error');
    const successBox = document.getElementById('cancel-success');

    errorBox.style.display = 'none';
    successBox.style.display = 'none';

    if (!bookingId || !token) {
        errorBox.textContent = 'Could not identify this booking. Please check "My Bookings" instead.';
        errorBox.style.display = 'block';
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Cancelling...';

    try {
        const res = await fetch('/api/bookings/' + bookingId + '/cancel', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        });

        const data = await res.json();

        if (!res.ok) {
            errorBox.textContent = data.message || 'Could not cancel this booking.';
            errorBox.style.display = 'block';
            return;
        }

        successBox.textContent = 'Booking cancelled. The room is now free for other guests.';
        successBox.style.display = 'block';
        btn.style.display = 'none';
    } catch (err) {
        errorBox.textContent = 'Something went wrong. Please try again.';
        errorBox.style.display = 'block';
    } finally {
        btn.disabled = false;
        btn.textContent = 'Cancel this booking';
    }
});
</script>

@endsection
