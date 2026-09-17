@extends('layouts.frontend')

@section('content')

<section class="page-header">
    <div>
        <p>YOUR STAYS</p>
        <h1>My Bookings</h1>
        <span></span>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width:720px;">

        <div id="auth-check-msg" style="display:none;">
            You need to <a href="{{ route('login') }}">login</a> first.
        </div>

        <div id="load-error" class="alert-error" style="display:none;"></div>
        <div id="loading-msg">Loading your bookings...</div>
        <div id="no-bookings-msg" style="display:none;">You don't have any bookings yet.</div>

        <div id="bookings-list"></div>

    </div>
</section>

<script>
/*
 * ملاحظة أمان مهمة: GET /api/bookings حالياً بيرجع حجوزات كل الزباين
 * (مو بس حجوزاتك)، وهاد الكود عم يفلتر بالمتصفح (client-side) بس.
 * يعني بيانات كل الزباين (بما فيها رقم جواز السفر) وصلت أصلاً لمتصفحك
 * حتى قبل الفلترة. لازم تصلّح هاد من الباك اند (شوف
 * recommended-backend-fixes/ بالزيب) قبل ما تنشر الموقع فعلياً.
 */

let currentUserId = null;
let currentToken = null;

async function cancelBooking(bookingId, btn) {
    btn.disabled = true;
    btn.textContent = 'Cancelling...';

    try {
        const res = await fetch('/api/bookings/' + bookingId + '/cancel', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + currentToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        });

        const data = await res.json();

        if (!res.ok) {
            alert(data.message || 'Could not cancel this booking.');
            btn.disabled = false;
            btn.textContent = 'Cancel Booking';
            return;
        }

        loadBookings();
    } catch (err) {
        alert('Something went wrong.');
        btn.disabled = false;
        btn.textContent = 'Cancel Booking';
    }
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';

    const date = new Date(dateString);

    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
}


function renderBookings(bookings) {
    const list = document.getElementById('bookings-list');
    list.innerHTML = '';

    if (bookings.length === 0) {
        document.getElementById('no-bookings-msg').style.display = 'block';
        return;
    }

    bookings.forEach(function (booking) {
        const roomNumbers = (booking.rooms || [])
            .map(r => r.room_number ?? r.id)
            .join(', ');

        const card = document.createElement('div');
        card.className = 'info-card';
        card.style.marginBottom = '15px';

        let actionsHtml = '';

        if (['pending', 'confirmed'].includes(booking.status)) {
            actionsHtml += `<button type="button" class="btn cancel-btn" data-id="${booking.id}" style="border:none; cursor:pointer; margin-top:10px; margin-right:10px;">Cancel Booking</button>`;
        }

        if (booking.status === 'checked_out') {
            actionsHtml += `<a href="/reviews?booking_id=${booking.id}" class="btn" style="display:inline-block; margin-top:10px;">Leave a Review</a>`;
        }

        card.innerHTML = `
            <div>Booking #${booking.id}</div>
            <div>Room(s): <strong>${roomNumbers || 'N/A'}</strong></div>
            <div>Check-in: <strong>${formatDate(booking.check_in_date)}</strong></div>
            <div>Check-out: <strong>${formatDate(booking.check_out_date)}</strong></div>
            <div>Status: <strong>${booking.status}</strong></div>
            <div>Total price: <strong>$${Number(booking.total_price).toFixed(2)}</strong></div>
            ${actionsHtml}
        `;

        list.appendChild(card);
    });

    document.querySelectorAll('.cancel-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (confirm('Cancel this booking?')) {
                cancelBooking(this.dataset.id, this);
            }
        });
    });
}

async function loadBookings() {
    const loadingMsg = document.getElementById('loading-msg');
    const errorBox = document.getElementById('load-error');
    loadingMsg.style.display = 'block';
    errorBox.style.display = 'none';
    document.getElementById('no-bookings-msg').style.display = 'none';

    try {
        const res = await fetch('/api/bookings', {
            headers: {
                'Authorization': 'Bearer ' + currentToken,
                'Accept': 'application/json',
            },
        });

        if (!res.ok) {
            throw new Error('Failed to load bookings');
        }

        const allBookings = await res.json();

        // فلترة بالمتصفح فقط - شوف الملاحظة فوق بخصوص الأمان
        const myBookings = allBookings.filter(b => b.user && b.user.id === currentUserId);

        loadingMsg.style.display = 'none';
        renderBookings(myBookings);
    } catch (err) {
        loadingMsg.style.display = 'none';
        errorBox.textContent = 'Could not load your bookings. Please try again later.';
        errorBox.style.display = 'block';
    }
}

document.addEventListener('DOMContentLoaded', async function () {
    currentToken = localStorage.getItem('token');

    if (!currentToken) {
        document.getElementById('loading-msg').style.display = 'none';
        document.getElementById('auth-check-msg').style.display = 'block';
        return;
    }

    try {
        const res = await fetch('/api/me', {
            headers: {
                'Authorization': 'Bearer ' + currentToken,
                'Accept': 'application/json',
            },
        });

        if (!res.ok) {
            localStorage.removeItem('token');
            document.getElementById('loading-msg').style.display = 'none';
            document.getElementById('auth-check-msg').style.display = 'block';
            return;
        }

        const user = await res.json();
        currentUserId = user.id;
        loadBookings();
    } catch (err) {
        document.getElementById('loading-msg').style.display = 'none';
        document.getElementById('auth-check-msg').style.display = 'block';
    }
});
</script>

@endsection
