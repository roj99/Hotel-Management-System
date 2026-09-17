@extends('layouts.frontend')

@section('content')

<section class="section">
    <div class="container" style="max-width:420px;">

        <h2 style="margin-bottom:20px;">Login</h2>

        <div id="login-error" class="alert-error" style="display:none;"></div>

        <form id="login-form">
            <input type="email" id="email" placeholder="Email" required
                   class="field">

            <div class="password-wrapper" style="position:relative;">
                <input type="password" id="password" placeholder="Password" required
                       class="field" style="padding-left:12px; padding-right:40px; width:100%; box-sizing:border-box;">
                <span id="toggle-password" style="position:absolute; top:50%; right:12px; transform:translateY(-50%); cursor:pointer; user-select:none;">
                    👁️
                </span>
            </div>

            <button type="submit" id="login-submit" class="btn btn-block">
                Login
            </button>
        </form>

        <form id="login-otp-form" style="display:none; margin-top:20px;">
            <p style="margin-bottom:10px;">تم إرسال رمز التحقق إلى إيميلك.</p>

            <input type="text" id="login-otp" placeholder="Enter OTP code" required
                   class="field">

            <button type="submit" id="login-otp-submit" class="btn btn-block">
                Verify
            </button>
        </form>

        <p style="margin-top:15px;">
            Don't have an account? <a href="{{ route('register') }}">Register</a>
        </p>

        <p style="margin-top:8px;">
            <a href="{{ route('password.forgot') }}">نسيت كلمة السر؟</a>
        </p>

    </div>
</section>

<script>
let loginUserId = null;

document.getElementById('toggle-password').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const isHidden = passwordInput.type === 'password';
    passwordInput.type = isHidden ? 'text' : 'password';
    this.textContent = isHidden ? '🙈' : '👁️';
});

document.getElementById('login-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const errorBox = document.getElementById('login-error');
    const submitBtn = document.getElementById('login-submit');
    errorBox.style.display = 'none';
    submitBtn.disabled = true;
    submitBtn.textContent = 'Logging in...';

    try {
        const res = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
            }),
        });

        const data = await res.json();

        if (!res.ok) {
            errorBox.textContent = data.error || data.message || 'Login failed';
            errorBox.style.display = 'block';
            return;
        }

        // تسجيل الدخول ينجح فقط بعد التحقق من OTP
        loginUserId = data.user_id;

        document.getElementById('login-form').style.display = 'none';
        document.getElementById('login-otp-form').style.display = 'block';
    } catch (err) {
        errorBox.textContent = 'Something went wrong. Please try again.';
        errorBox.style.display = 'block';
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Login';
    }
});

document.getElementById('login-otp-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const errorBox = document.getElementById('login-error');
    const submitBtn = document.getElementById('login-otp-submit');
    errorBox.style.display = 'none';
    submitBtn.disabled = true;
    submitBtn.textContent = 'Verifying...';

    try {
        const res = await fetch('/api/verify-login-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                user_id: loginUserId,
                code: document.getElementById('login-otp').value,
            }),
        });

        const data = await res.json();

        if (!res.ok) {
            errorBox.textContent = data.error || data.message || 'Invalid code';
            errorBox.style.display = 'block';
            return;
        }

        localStorage.setItem('token', data.token);
        window.location.href = '/';
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
