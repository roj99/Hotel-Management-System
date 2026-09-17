<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Otp;
use App\Mail\SendOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // =========================
    // REGISTER
    // =========================
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        // البحث عن المستخدم
        $user = User::where('email', $request->email)->first();

        // إذا المستخدم موجود
        if ($user) {

            // إذا الإيميل موثق مسبقاً
            if ($user->email_verified_at) {
                return response()->json([
                    'error' => 'Email already registered. Please login.'
                ], 409);
            }

            // المستخدم موجود لكن غير موثق
            // لا ننشئ User جديد
            // فقط نرسل OTP جديد
        } else {

            // إنشاء مستخدم جديد
            $user = User::create([
                'full_name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);
        }

        // تعطيل OTPs القديمة غير المستخدمة
        Otp::where('user_id', $user->id)
            ->where('is_used', false)
            ->update([
                'is_used' => true
            ]);

        // إنشاء OTP جديد
        $otpCode = random_int(100000, 999999);

        Otp::create([
            'user_id' => $user->id,
            'code'    => (string) $otpCode,
            'is_used' => false,
        ]);

        // إرسال OTP
        Mail::to($user->email)->send(
            new SendOtpMail((string) $otpCode)
        );

        return response()->json([
            'message' => 'OTP sent to your email.',
            'user_id' => $user->id,
        ]);
    }


    // =========================
    // VERIFY REGISTER OTP
    // =========================
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'code'    => 'required|digits:6',
        ]);

        $otp = Otp::where('user_id', $request->user_id)
            ->where('code', $request->code)
            ->where('is_used', false)
            ->latest()
            ->first();

        if (! $otp) {
            return response()->json([
                'error' => 'Invalid OTP'
            ], 400);
        }

        // التحقق من صلاحية الرمز (10 دقائق)
        if ($otp->created_at->diffInMinutes(now()) > 10) {
            return response()->json([
                'error' => 'OTP expired. Please request a new one.'
            ], 400);
        }

        // استخدام OTP
        $otp->update([
            'is_used' => true
        ]);

        // توثيق الإيميل
        $user = User::find($request->user_id);

        if (! $user) {
            return response()->json([
                'error' => 'User not found'
            ], 404);
        }

        $user->update([
            'email_verified_at' => now()
        ]);

        return response()->json([
            'message' => 'OTP verified successfully.'
        ]);
    }


    // =========================
    // LOGIN
    // =========================
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // البحث عن المستخدم
        $user = User::where('email', $request->email)->first();

        // التحقق من البيانات
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'Invalid email or password'
            ], 401);
        }

        // لا يوجد منع بسبب email_verified_at
        // Login يرسل OTP مباشرة

        // تعطيل OTPs القديمة غير المستخدمة
        Otp::where('user_id', $user->id)
            ->where('is_used', false)
            ->update([
                'is_used' => true
            ]);

        // إنشاء OTP جديد
        $otpCode = random_int(100000, 999999);

        Otp::create([
            'user_id' => $user->id,
            'code'    => (string) $otpCode,
            'is_used' => false,
        ]);

        // إرسال OTP
        Mail::to($user->email)->send(
            new SendOtpMail((string) $otpCode)
        );

        return response()->json([
            'message' => 'OTP sent to your email.',
            'user_id' => $user->id,
        ]);
    }


    // =========================
    // VERIFY LOGIN OTP
    // =========================
    public function verifyLoginOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'code'    => 'required|digits:6',
        ]);

        $otp = Otp::where('user_id', $request->user_id)
            ->where('code', $request->code)
            ->where('is_used', false)
            ->latest()
            ->first();

        if (! $otp) {
            return response()->json([
                'error' => 'Invalid OTP'
            ], 400);
        }

        // التحقق من صلاحية الرمز (10 دقائق)
        if ($otp->created_at->diffInMinutes(now()) > 10) {
            return response()->json([
                'error' => 'OTP expired. Please login again.'
            ], 400);
        }

        // استخدام OTP
        $otp->update([
            'is_used' => true
        ]);

        // جلب المستخدم
        $user = User::find($request->user_id);

        if (! $user) {
            return response()->json([
                'error' => 'User not found'
            ], 404);
        }

        // إنشاء JWT فقط بعد نجاح OTP
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Login successful.',
            'token'   => $token,
            // إخفاء الـ password hash عن الاستجابة احترازياً
            'user'    => $user->makeHidden(['password']),
        ]);
    }


    // =========================
    // FORGOT PASSWORD
    // =========================
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // البحث عن المستخدم
        $user = User::where('email', $request->email)->first();

        // لا نكشف إذا الإيميل موجود أو لا (لأسباب أمنية)
        if (! $user) {
            return response()->json([
                'message' => 'If this email exists, an OTP has been sent.'
            ]);
        }

        // تعطيل OTPs القديمة غير المستخدمة
        Otp::where('user_id', $user->id)
            ->where('is_used', false)
            ->update([
                'is_used' => true
            ]);

        // إنشاء OTP جديد
        $otpCode = random_int(100000, 999999);

        Otp::create([
            'user_id' => $user->id,
            'code'    => (string) $otpCode,
            'is_used' => false,
        ]);

        // إرسال OTP
        Mail::to($user->email)->send(
            new SendOtpMail((string) $otpCode)
        );

        return response()->json([
            'message' => 'If this email exists, an OTP has been sent.'
        ]);
    }


    // =========================
    // RESET PASSWORD
    // =========================
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'code'     => 'required|digits:6',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'error' => 'Invalid email or code'
            ], 400);
        }

        $otp = Otp::where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('is_used', false)
            ->latest()
            ->first();

        if (! $otp) {
            return response()->json([
                'error' => 'Invalid or expired OTP'
            ], 400);
        }

        // التحقق من صلاحية الرمز (10 دقائق)
        if ($otp->created_at->diffInMinutes(now()) > 10) {
            return response()->json([
                'error' => 'OTP expired. Please request a new one.'
            ], 400);
        }

        // استخدام OTP
        $otp->update([
            'is_used' => true
        ]);

        // تعيين كلمة السر الجديدة
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Password reset successfully.'
        ]);
    }


    // =========================
    // LOGOUT
    // =========================
    public function logout()
    {
        try {

            JWTAuth::invalidate(
                JWTAuth::getToken()
            );

            return response()->json([
                'message' => 'Logged out successfully'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'error' => 'Failed to logout'
            ], 500);
        }
    }


    // =========================
    // ME
    // =========================
    public function me()
    {
        return response()->json(
            JWTAuth::user()
        );
    }
}
