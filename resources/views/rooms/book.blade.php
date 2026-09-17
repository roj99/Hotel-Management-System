@extends('layouts.frontend')

@section('content')

<section class="page-header">
    <div>
        <p>RESERVE YOUR STAY</p>
        <h1>Book {{ $roomsType->name }}</h1>
        <span></span>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width:560px;">

        <div id="auth-check-msg" style="display:none; margin-bottom:15px;">
            You need to <a href="{{ route('login') }}">login</a> first to book a room.
        </div>

        <div id="booking-error" class="alert-error" style="display:none;"></div>
        <div id="booking-success" class="alert-success" style="display:none;"></div>

        <form id="booking-form">

            <label>Check-in date</label>
            <input type="date" id="check_in_date" class="field" required>

            <label>Check-out date</label>
            <input type="date" id="check_out_date" class="field" required>

            <label>Guests</label>
            <input type="number" id="guests_count" class="field" min="1" max="{{ $roomsType->capacity }}" value="1" required>

            <label>Room</label>
            <select id="room_id" class="field" required>
                @foreach($roomsType->rooms as $room)
                    <option value="{{ $room->id }}">
                        Room {{ $room->room_number ?? $room->id }}
                        @if($room->floor) (Floor {{ $room->floor }}) @endif
                    </option>
                @endforeach
            </select>

            <label>ID Document Type</label>
            <select id="id_document_type" class="field">
                <option value="">-- optional --</option>
                <option value="passport">Passport</option>
                <option value="national_id">National ID</option>
            </select>

            <label>ID Document Number</label>
            <input type="text" id="id_document_number" class="field" maxlength="50">

            <div id="price-summary" class="info-card" style="display:none;">
                <div>Price per night: <strong id="price-per-night">$0.00</strong></div>
                <div>Nights: <strong id="nights-count">0</strong></div>
                <div>Total price: <strong id="total-price">$0.00</strong></div>
                <div>Deposit due now: <strong id="deposit-amount">$0.00</strong></div>
            </div>

            <button type="submit" id="booking-submit" class="btn btn-block">
                Confirm Booking
            </button>

        </form>

    </div>
</section>

<script>
// عدّلي هالرقم لو المبلغ الثابت للمقدم مختلف
const DEPOSIT_AMOUNT = 50;

// السعر الأساسي لليلة الواحدة (مبسّط - أول سعر مسجّل لهالنوع من الغرف)
const PRICE_PER_NIGHT = {{ $roomsType->prices->first()->price_per_night ?? 0 }};

const form = document.getElementById('booking-form');
const errorBox = document.getElementById('booking-error');
const successBox = document.getElementById('booking-success');
const authMsg = document.getElementById('auth-check-msg');
const submitBtn = document.getElementById('booking-submit');
const checkInInput = document.getElementById('check_in_date');
const checkOutInput = document.getElementById('check_out_date');

let currentUserId = null;

function toISODate(date) {
    return date.toISOString().split('T')[0];
}

function addDays(dateStr, days) {
    const d = new Date(dateStr + 'T00:00:00');
    d.setDate(d.getDate() + days);
    return d;
}

// منع اختيار تاريخ ماضي لـ check-in، من الـ date picker نفسه
const today = toISODate(new Date());
checkInInput.min = today;

// كل ما يتغيّر check-in، نحدّث أقل تاريخ مسموح لـ check-out (يوم بعده عالأقل)
checkInInput.addEventListener('change', function () {
    if (!checkInInput.value) return;

    const minCheckOut = toISODate(addDays(checkInInput.value, 1));
    checkOutInput.min = minCheckOut;

    // لو check-out المختار صار قبل الحد الأدنى الجديد، امسحيه
    if (checkOutInput.value && checkOutInput.value < minCheckOut) {
        checkOutInput.value = '';
    }

    updatePriceSummary();
});

function showError(msg) {
    errorBox.textContent = msg;
    errorBox.style.display = 'block';
}

function updatePriceSummary() {
    const checkIn = document.getElementById('check_in_date').value;
    const checkOut = document.getElementById('check_out_date').value;
    const summary = document.getElementById('price-summary');

    if (!checkIn || !checkOut) {
        summary.style.display = 'none';
        return;
    }

    const nights = Math.round((new Date(checkOut) - new Date(checkIn)) / 86400000);

    if (nights <= 0) {
        summary.style.display = 'none';
        return;
    }

    const total = nights * PRICE_PER_NIGHT;

    document.getElementById('price-per-night').textContent = '$' + PRICE_PER_NIGHT.toFixed(2);
    document.getElementById('nights-count').textContent = nights;
    document.getElementById('total-price').textContent = '$' + total.toFixed(2);
    document.getElementById('deposit-amount').textContent = '$' + DEPOSIT_AMOUNT.toFixed(2);
    summary.style.display = 'block';
}

checkOutInput.addEventListener('change', updatePriceSummary);

// تحقق من تسجيل الدخول وجيب الـ user id عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', async function () {
    const token = localStorage.getItem('token');

    if (!token) {
        form.style.display = 'none';
        authMsg.style.display = 'block';
        return;
    }

    try {
        const res = await fetch('/api/me', {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json',
            },
        });

        if (!res.ok) {
            localStorage.removeItem('token');
            form.style.display = 'none';
            authMsg.style.display = 'block';
            return;
        }

        const data = await res.json();
        currentUserId = data.id ?? data.user?.id;
    } catch (err) {
        form.style.display = 'none';
        authMsg.style.display = 'block';
    }
});

form.addEventListener('submit', async function (e) {
    e.preventDefault();

    errorBox.style.display = 'none';
    successBox.style.display = 'none';

    const checkIn = document.getElementById('check_in_date').value;
    const checkOut = document.getElementById('check_out_date').value;

    // آخر خط دفاع - حتى لو حدا لعب بالـ min attribute من الكونسول
    if (checkIn < today) {
        showError('Check-in date cannot be in the past.');
        return;
    }

    const nights = Math.round((new Date(checkOut) - new Date(checkIn)) / 86400000);

    if (nights <= 0) {
        showError('Check-out date must be at least one day after check-in date.');
        return;
    }

    if (!currentUserId) {
        showError('Could not verify your account. Please login again.');
        return;
    }

    const payload = {
        user_id: currentUserId,
        check_in_date: checkIn,
        check_out_date: checkOut,
        guests_count: parseInt(document.getElementById('guests_count').value, 10),
        total_price: nights * PRICE_PER_NIGHT,
        deposit_amount: DEPOSIT_AMOUNT,
        id_document_type: document.getElementById('id_document_type').value || null,
        id_document_number: document.getElementById('id_document_number').value || null,
        room_ids: [document.getElementById('room_id').value],
    };

    submitBtn.disabled = true;
    submitBtn.textContent = 'Booking...';

    try {
        const res = await fetch('/api/bookings', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
            },
            body: JSON.stringify(payload),
        });

        const data = await res.json();

        if (res.status === 401) {
            localStorage.removeItem('token');
            showError('Your session expired. Please login again.');
            return;
        }

        if (res.status === 409) {
            showError(data.message || 'This room is not available for the selected dates. Please choose another room or dates.');
            return;
        }

        if (!res.ok) {
            const firstError = data.errors
                ? Object.values(data.errors)[0][0]
                : (data.message || 'Booking failed. Please check your details.');
            showError(firstError);
            return;
        }

        if (data.payment_url) {
            window.location.href = data.payment_url;
            return;
        }

        successBox.textContent = 'Booking created successfully!';
        successBox.style.display = 'block';
        form.reset();
    } catch (err) {
        showError('Something went wrong. Please try again.');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Confirm Booking';
    }
});
</script>

@endsection
