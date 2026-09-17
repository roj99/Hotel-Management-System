@extends('layouts.frontend')

@section('content')

<section class="page-header">
    <div>
        <p>YOUR ACCOUNT</p>
        <h1>Profile</h1>
        <span></span>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width:480px;">

        <div id="auth-check-msg" style="display:none;">
            You need to <a href="{{ route('login') }}">login</a> first.
        </div>

        <div id="profile-error" class="alert-error" style="display:none;"></div>
        <div id="profile-success" class="alert-success" style="display:none;"></div>

        <form id="profile-form" style="display:none;">

            <label>Full Name</label>
            <input type="text" id="full_name" required class="field">

            <label>Email</label>
            <input type="email" id="email" required class="field">

            <label>Phone</label>
            <input type="text" id="phone" class="field">

            <label>New Password (leave blank to keep current password)</label>
            <input type="password" id="password" minlength="8" class="field">

            <button type="submit" id="profile-submit" class="btn btn-block">
                Save Changes
            </button>
        </form>

    </div>
</section>

<script>
/*
 * ملاحظة أمان مهمة: PUT /api/users/{id} حالياً بدون أي حماية auth:api
 * إطلاقاً بملفك api.php - يعني بالوضع الحالي أي حدا (حتى بدون تسجيل
 * دخول) يقدر يغيّر بيانات أي مستخدم لو عرف الـ id تبعه. لازم تصلّح
 * هاد من الباك اند (شوف recommended-backend-fixes/ بالزيب) قبل ما
 * تنشر الموقع فعلياً - هاي الصفحة بترسل الـ token بس هلق ما في شي
 * بالسيرفر عم يتحقق منه لهالـ endpoint بالذات.
 */

let currentUserId = null;
let currentToken = null;

document.addEventListener('DOMContentLoaded', async function () {
    currentToken = localStorage.getItem('token');

    if (!currentToken) {
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
            document.getElementById('auth-check-msg').style.display = 'block';
            return;
        }

        const user = await res.json();
        currentUserId = user.id;

        document.getElementById('full_name').value = user.full_name ?? '';
        document.getElementById('email').value = user.email ?? '';
        document.getElementById('phone').value = user.phone ?? '';

        document.getElementById('profile-form').style.display = 'block';
    } catch (err) {
        document.getElementById('auth-check-msg').style.display = 'block';
    }
});

document.getElementById('profile-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const errorBox = document.getElementById('profile-error');
    const successBox = document.getElementById('profile-success');
    const submitBtn = document.getElementById('profile-submit');
    errorBox.style.display = 'none';
    successBox.style.display = 'none';

    const payload = {
        full_name: document.getElementById('full_name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
    };

    const password = document.getElementById('password').value;
    if (password) {
        payload.password = password;
    }

    submitBtn.disabled = true;
    submitBtn.textContent = 'Saving...';

    try {
        const res = await fetch('/api/users/' + currentUserId, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + currentToken,
            },
            body: JSON.stringify(payload),
        });

        const data = await res.json();

        if (!res.ok) {
            const firstError = data.errors
                ? Object.values(data.errors)[0][0]
                : (data.message || 'Could not update profile.');
            errorBox.textContent = firstError;
            errorBox.style.display = 'block';
            return;
        }

        successBox.textContent = 'Profile updated successfully.';
        successBox.style.display = 'block';
        document.getElementById('password').value = '';
    } catch (err) {
        errorBox.textContent = 'Something went wrong. Please try again.';
        errorBox.style.display = 'block';
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Save Changes';
    }
});
</script>

@endsection
