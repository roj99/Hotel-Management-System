@extends('layouts.frontend')

@section('content')

<section class="section">
    <div class="container" style="max-width:420px;">

        <h2 style="margin-bottom:20px;">Register</h2>

        <div id="register-error" class="alert-error" style="display:none;"></div>
        <div id="register-success" class="alert-success" style="display:none;"></div>

        <form id="register-form">
            <input type="text" id="name" placeholder="Full Name" required
                   class="field">

            <input type="email" id="email" placeholder="Email" required
                   class="field">

            <div class="password-wrapper" style="position:relative;">
                <input type="password" id="password" placeholder="Password" required
                       class="field" style="padding-left:12px; padding-right:40px; width:100%; box-sizing:border-box;">
                <span id="toggle-password" style="position:absolute; top:50%; right:12px; transform:translateY(-50%); cursor:pointer; user-select:none;">
                    👁️
                </span>
            </div>

            <button type="submit" id="register-submit" class="btn btn-block">
                Register
            </button>
        </form>

        <form id="otp-form" style="display:none; margin-top:20px;">
            <p style="margin-bottom:10px;">We sent a verification code to your email.</p>

            <input type="text" id="otp" placeholder="Enter OTP code" required
                   class="field">

            <button type="submit" id="otp-submit" class="btn btn-block">
                Verify
            </button>
        </form>

        <p style="margin-top:15px;">
            Already have an account? <a href="{{ route('login') }}">Login</a>
        </p>

    </div>
</section>

<script>
document.getElementById('toggle-password').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const isHidden = passwordInput.type === 'password';
    passwordInput.type = isHidden ? 'text' : 'password';
    this.textContent = isHidden ? '🙈' : '👁️';
});

let registeredUserId = null;

document.getElementById('register-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const errorBox = document.getElementById('register-error');
    const submitBtn = document.getElementById('register-submit');
    errorBox.style.display = 'none';
    submitBtn.disabled = true;
    submitBtn.textContent = 'Registering...';

    try {
        const res = await fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
            }),
        });

        const data = await res.json();

        if (!res.ok) {
            const firstError = data.errors
                ? Object.values(data.errors)[0][0]
                : (data.message || 'Registration failed');
            errorBox.textContent = firstError;
            errorBox.style.display = 'block';
            return;
        }

        // عدّل هون إذا اسم الحقل يلي رجعه verify-otp مختلف (مثلاً data.id بدل data.user_id)
        registeredUserId = data.user_id ?? data.id;

        document.getElementById('register-form').style.display = 'none';
        document.getElementById('otp-form').style.display = 'block';
    } catch (err) {
        errorBox.textContent = 'Something went wrong. Please try again.';
        errorBox.style.display = 'block';
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Register';
    }
});

document.getElementById('otp-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const errorBox = document.getElementById('register-error');
    const submitBtn = document.getElementById('otp-submit');
    errorBox.style.display = 'none';
    submitBtn.disabled = true;
    submitBtn.textContent = 'Verifying...';

    try {
        const res = await fetch('/api/verify-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                user_id: registeredUserId,
                code: document.getElementById('otp').value,
            }),
        });

        const data = await res.json();

        if (!res.ok) {
            errorBox.textContent = data.error || data.message || 'Invalid code';
            errorBox.style.display = 'block';
            return;
        }

        document.getElementById('otp-form').style.display = 'none';
        const successBox = document.getElementById('register-success');
        successBox.textContent = 'Account verified! Redirecting to login...';
        successBox.style.display = 'block';

        setTimeout(() => window.location.href = '{{ route('login') }}', 1500);
    } catch (err) {
        errorBox.textContent = 'Something went wrong. Please try again.';
        errorBox.style.display = 'block';
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Verify';
    }
});
</script>

@endsection
