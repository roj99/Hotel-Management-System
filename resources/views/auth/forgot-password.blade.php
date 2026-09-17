@extends('layouts.frontend')

@section('content')

<section class="section">
    <div class="container" style="max-width:420px;">

        <h2 style="margin-bottom:20px;">Did you forget your password?</h2>

        <div id="forgot-error" class="alert-error" style="display:none;"></div>
        <div id="forgot-success" class="alert-success" style="display:none;"></div>

        {{-- الخطوة 1: إدخال الإيميل --}}
        <form id="forgot-email-form">
            <p style="margin-bottom:10px;">
                أدخل إيميلك وبنبعتلك رمز تحقق (OTP) حتى تحط كلمة سر جديدة.
            </p>

            <input type="email" id="forgot-email" placeholder="Email" required
                   class="field">

            <button type="submit" id="forgot-email-submit" class="btn btn-block">
                إرسال الرمز
            </button>
        </form>

        {{-- الخطوة 2: إدخال OTP + كلمة السر الجديدة --}}
        <form id="reset-password-form" style="display:none; margin-top:20px;">
            <p style="margin-bottom:10px;">
                تم إرسال رمز التحقق إلى إيميلك.
            </p>

            <input type="text" id="reset-otp" placeholder="رمز OTP" required
                   class="field">

            <div class="password-wrapper" style="position:relative;">
                <input type="password" id="new-password" placeholder="كلمة السر الجديدة" required
                       minlength="8"
                       class="field" style="padding-left:12px; padding-right:40px; width:100%; box-sizing:border-box;">
                <span id="toggle-new-password" style="position:absolute; top:50%; right:12px; transform:translateY(-50%); cursor:pointer; user-select:none;">
                    👁️
                </span>
            </div>

            <button type="submit" id="reset-password-submit" class="btn btn-block">
                تعيين كلمة السر
            </button>
        </form>

        <p style="margin-top:15px;">
            <a href="{{ route('login') }}">رجوع لتسجيل الدخول</a>
        </p>

    </div>
</section>

<script>
let resetEmail = null;

document.getElementById('toggle-new-password').addEventListener('click', function () {
    const passwordInput = document.getElementById('new-password');
    const isHidden = passwordInput.type === 'password';
    passwordInput.type = isHidden ? 'text' : 'password';
    this.textContent = isHidden ? '🙈' : '👁️';
});

// الخطوة 1: طلب إرسال OTP
document.getElementById('forgot-email-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const errorBox = document.getElementById('forgot-error');
    const submitBtn = document.getElementById('forgot-email-submit');
    errorBox.style.display = 'none';
    submitBtn.disabled = true;
    submitBtn.textContent = 'جاري الإرسال...';

    try {
        resetEmail = document.getElementById('forgot-email').value;

        const res = await fetch('/api/forgot-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                email: resetEmail,
            }),
        });

        const data = await res.json();

        if (!res.ok) {
            errorBox.textContent = data.error || data.message || 'حدث خطأ، حاول مرة تانية';
            errorBox.style.display = 'block';
            return;
        }

        document.getElementById('forgot-email-form').style.display = 'none';
        document.getElementById('reset-password-form').style.display = 'block';
    } catch (err) {
        errorBox.textContent = 'حدث خطأ، حاول مرة تانية';
        errorBox.style.display = 'block';
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'إرسال الرمز';
    }
});

// الخطوة 2: تأكيد OTP + تعيين كلمة السر الجديدة
document.getElementById('reset-password-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const errorBox = document.getElementById('forgot-error');
    const submitBtn = document.getElementById('reset-password-submit');
    errorBox.style.display = 'none';
    submitBtn.disabled = true;
    submitBtn.textContent = 'جاري التحقق...';

    try {
        const res = await fetch('/api/reset-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                email: resetEmail,
                code: document.getElementById('reset-otp').value,
                password: document.getElementById('new-password').value,
            }),
        });

        const data = await res.json();

        if (!res.ok) {
            errorBox.textContent = data.error || data.message || 'رمز غير صحيح';
            errorBox.style.display = 'block';
            return;
        }

        document.getElementById('reset-password-form').style.display = 'none';
        const successBox = document.getElementById('forgot-success');
        successBox.textContent = 'تم تغيير كلمة السر بنجاح! جاري تحويلك لتسجيل الدخول...';
        successBox.style.display = 'block';

        setTimeout(() => window.location.href = '{{ route('login') }}', 1500);
    } catch (err) {
        errorBox.textContent = 'حدث خطأ، حاول مرة تانية';
        errorBox.style.display = 'block';
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'تعيين كلمة السر';
    }
});
</script>

@endsection
