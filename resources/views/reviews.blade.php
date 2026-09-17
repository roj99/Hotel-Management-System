@extends('layouts.frontend')

@section('content')

<section class="page-header">
    <div>
        <p>GUEST FEEDBACK</p>
        <h1>Reviews</h1>
        <span></span>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width:640px;">

        <div id="review-form-wrapper" class="info-card" style="display:none; margin-bottom:40px;">
            <h3 style="margin-bottom:15px;">Leave a Review</h3>

            <div id="review-auth-msg" style="display:none;">
                You need to <a href="{{ route('login') }}">login</a> first.
            </div>

            <div id="review-error" class="alert-error" style="display:none;"></div>
            <div id="review-success" class="alert-success" style="display:none;"></div>

            <form id="review-form" style="display:none;">
                <label>Rating</label>
                <select id="rating" class="field" required>
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Very Good</option>
                    <option value="3">3 - Average</option>
                    <option value="2">2 - Poor</option>
                    <option value="1">1 - Terrible</option>
                </select>

                <label>Comment</label>
                <textarea id="comment" class="field" rows="4"></textarea>

                <button type="submit" id="review-submit" class="btn btn-block">
                    Submit Review
                </button>
            </form>
        </div>

        <h2 style="margin-bottom:20px;">What Our Guests Say</h2>

        <div id="loading-msg">Loading reviews...</div>
        <div id="reviews-error" class="alert-error" style="display:none;"></div>
        <div id="reviews-list"></div>

    </div>
</section>

<script>
// booking_id اختياري - لو موجود بالرابط (جاية من My Bookings)، منربطها
// بالـ review. لو مش موجود، أي زبون مسجل دخول يقدر يكتب review عام.
const params = new URLSearchParams(window.location.search);
const bookingId = params.get('booking_id');

function renderReviews(reviews) {
    const list = document.getElementById('reviews-list');
    list.innerHTML = '';

    if (reviews.length === 0) {
        list.innerHTML = '<p>No reviews yet.</p>';
        return;
    }

    reviews.forEach(function (review) {
        const stars = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
        const name = review.user?.full_name ?? 'Guest';

        const card = document.createElement('div');
        card.style.cssText = 'padding:15px; border-bottom:1px solid var(--border);';
        card.innerHTML = `
            <div style="color:var(--gold);">${stars}</div>
            <div style="font-weight:600; margin-top:5px;">${name}</div>
            <p style="margin-top:5px;">${review.comment ?? ''}</p>
        `;
        list.appendChild(card);
    });
}

async function loadReviews() {
    try {
        const res = await fetch('/api/reviews', {
            headers: { 'Accept': 'application/json' },
        });

        if (!res.ok) throw new Error('failed');

        const reviews = await res.json();
        document.getElementById('loading-msg').style.display = 'none';
        renderReviews(reviews);
    } catch (err) {
        document.getElementById('loading-msg').style.display = 'none';
        document.getElementById('reviews-error').textContent = 'Could not load reviews.';
        document.getElementById('reviews-error').style.display = 'block';
    }
}

document.addEventListener('DOMContentLoaded', async function () {
    loadReviews();

    // الفورم بتظهر لأي زبون مسجل دخول - مش بس لما يجي بـ booking_id
    document.getElementById('review-form-wrapper').style.display = 'block';

    const token = localStorage.getItem('token');
    if (!token) {
        document.getElementById('review-auth-msg').style.display = 'block';
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
            document.getElementById('review-auth-msg').style.display = 'block';
            return;
        }

        const user = await res.json();
        document.getElementById('review-form').style.display = 'block';

        document.getElementById('review-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const errorBox = document.getElementById('review-error');
            const successBox = document.getElementById('review-success');
            const submitBtn = document.getElementById('review-submit');
            errorBox.style.display = 'none';
            successBox.style.display = 'none';
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';

            const payload = {
                user_id: user.id,
                rating: parseInt(document.getElementById('rating').value, 10),
                comment: document.getElementById('comment').value || null,
            };

            // بس منضيف booking_id للـ payload لو فعلاً موجود بالرابط
            if (bookingId) {
                payload.booking_id = bookingId;
            }

            try {
                const submitRes = await fetch('/api/reviews', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token,
                    },
                    body: JSON.stringify(payload),
                });

                const data = await submitRes.json();

                if (!submitRes.ok) {
                    errorBox.textContent = data.message || 'Could not submit review.';
                    errorBox.style.display = 'block';
                    return;
                }

                successBox.textContent = 'Thank you for your review!';
                successBox.style.display = 'block';
                document.getElementById('review-form').reset();
                loadReviews();
            } catch (err) {
                errorBox.textContent = 'Something went wrong. Please try again.';
                errorBox.style.display = 'block';
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Review';
            }
        });
    } catch (err) {
        document.getElementById('review-auth-msg').style.display = 'block';
    }
});
</script>

@endsection
