@extends('layouts.frontend')

@section('content')

<section class="page-header">
    <div>
        <p>DURING YOUR STAY</p>
        <h1>Room Service</h1>
        <span></span>
        <p>Order something for your room - it goes straight to our staff.</p>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width:640px;">

        <div id="auth-check-msg" style="display:none;">
            You need to <a href="{{ route('login') }}">login</a> first.
        </div>

        <div id="no-active-booking-msg" style="display:none;">
            Room service is only available during an active stay
            (a confirmed or checked-in booking).
        </div>

        <div id="order-error" class="alert-error" style="display:none;"></div>
        <div id="order-success" class="alert-success" style="display:none;"></div>

        <form id="order-form" style="display:none;">

            <label>Which stay is this for?</label>
            <select id="booking_id" class="field" required></select>

            <label>Item</label>
            <select id="item" class="field" required>
                <!-- عدّلي هالقائمة والأسعار حسب خدماتك الحقيقية -->
                <option value="Breakfast Tray|15">Breakfast Tray — $15</option>
                <option value="Extra Towels|5">Extra Towels — $5</option>
                <option value="Bottled Water (6-pack)|3">Bottled Water (6-pack) — $3</option>
                <option value="Laundry Service|20">Laundry Service — $20</option>
                <option value="Late Checkout (2h)|30">Late Checkout (2h) — $30</option>
                <option value="Room Cleaning|10">Room Cleaning — $10</option>
            </select>

            <label>Quantity</label>
            <input type="number" id="quantity" class="field" min="1" value="1" required>

            <button type="submit" id="order-submit" class="btn btn-block">
                Send Request
            </button>
        </form>

        <h2 style="margin:40px 0 15px;">Your Requests</h2>
        <div id="requests-loading">Loading...</div>
        <div id="requests-list"></div>

    </div>
</section>

<script>
let currentUserId = null;
let currentToken = null;

function statusColor(status) {
    return {
        pending: '#9B8055',
        preparing: '#263746',
        delivered: '#2d6a2d',
        cancelled: '#a33a3a',
    }[status] || '#666';
}

async function loadBookingsIntoSelect() {
    const select = document.getElementById('booking_id');

    const res = await fetch('/api/bookings', {
        headers: { 'Authorization': 'Bearer ' + currentToken, 'Accept': 'application/json' },
    });
    const allBookings = await res.json();

    const myActiveBookings = allBookings.filter(b =>
        b.user && b.user.id === currentUserId &&
        ['confirmed', 'checked_in'].includes(b.status)
    );

    if (myActiveBookings.length === 0) {
        document.getElementById('no-active-booking-msg').style.display = 'block';
        return false;
    }

    select.innerHTML = myActiveBookings.map(b => {
        const rooms = (b.rooms || []).map(r => r.room_number ?? r.id).join(', ');
        return `<option value="${b.id}">Room ${rooms} (${b.check_in_date} → ${b.check_out_date})</option>`;
    }).join('');

    return true;
}

async function loadMyRequests() {
    const loadingMsg = document.getElementById('requests-loading');
    const list = document.getElementById('requests-list');

    try {
        const res = await fetch('/api/room-services', {
            headers: { 'Accept': 'application/json' },
        });
        const all = await res.json();

        // فلترة بالمتصفح - نفس ملاحظة الأمان يلي بنهاية الرد
        const mine = all.filter(rs => rs.booking && rs.booking.user && rs.booking.user.id === currentUserId);

        loadingMsg.style.display = 'none';

        if (mine.length === 0) {
            list.innerHTML = '<p>No requests yet.</p>';
            return;
        }

        list.innerHTML = mine.map(rs => `
            <div style="padding:12px 0; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <strong>${rs.item_description}</strong> × ${rs.quantity}
                    <div style="font-size:13px; color:#999;">$${Number(rs.price).toFixed(2)}</div>
                </div>
                <span style="color:${statusColor(rs.status)}; font-weight:600; font-size:13px; text-transform:capitalize;">
                    ${rs.status}
                </span>
            </div>
        `).join('');
    } catch (err) {
        loadingMsg.textContent = 'Could not load your requests.';
    }
}

document.addEventListener('DOMContentLoaded', async function () {
    currentToken = localStorage.getItem('token');

    if (!currentToken) {
        document.getElementById('auth-check-msg').style.display = 'block';
        document.getElementById('requests-loading').style.display = 'none';
        return;
    }

    const meRes = await fetch('/api/me', {
        headers: { 'Authorization': 'Bearer ' + currentToken, 'Accept': 'application/json' },
    });

    if (!meRes.ok) {
        localStorage.removeItem('token');
        document.getElementById('auth-check-msg').style.display = 'block';
        document.getElementById('requests-loading').style.display = 'none';
        return;
    }

    const user = await meRes.json();
    currentUserId = user.id;

    const hasActiveBooking = await loadBookingsIntoSelect();
    if (hasActiveBooking) {
        document.getElementById('order-form').style.display = 'block';
    }

    loadMyRequests();
});

document.getElementById('order-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const errorBox = document.getElementById('order-error');
    const successBox = document.getElementById('order-success');
    const submitBtn = document.getElementById('order-submit');
    errorBox.style.display = 'none';
    successBox.style.display = 'none';

    const [itemDescription, price] = document.getElementById('item').value.split('|');

    submitBtn.disabled = true;
    submitBtn.textContent = 'Sending...';

    try {
        const res = await fetch('/api/room-services', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + currentToken,
            },
            body: JSON.stringify({
                booking_id: document.getElementById('booking_id').value,
                item_description: itemDescription,
                quantity: parseInt(document.getElementById('quantity').value, 10),
                price: parseFloat(price),
            }),
        });

        const data = await res.json();

        if (!res.ok) {
            const firstError = data.errors
                ? Object.values(data.errors)[0][0]
                : (data.message || 'Could not send request.');
            errorBox.textContent = firstError;
            errorBox.style.display = 'block';
            return;
        }

        successBox.textContent = 'Request sent! Our staff will take care of it shortly.';
        successBox.style.display = 'block';
        loadMyRequests();
    } catch (err) {
        errorBox.textContent = 'Something went wrong. Please try again.';
        errorBox.style.display = 'block';
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Send Request';
    }
});
</script>

@endsection
