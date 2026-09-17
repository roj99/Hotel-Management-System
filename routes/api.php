<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Root-level controllers
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\AmenityController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ReportController;

// Hotel controllers
use App\Http\Controllers\Api\Hotel\RoomsTypeController;
use App\Http\Controllers\Api\Hotel\RoomImageController;
use App\Http\Controllers\Api\Hotel\RoomController;
use App\Http\Controllers\Api\Hotel\RoomPriceController;
use App\Http\Controllers\Api\Hotel\RoomServiceController;

// Booking controllers
use App\Http\Controllers\Api\Booking\BookingController;
use App\Http\Controllers\Api\Booking\StripeWebhookController;
use App\Http\Controllers\Api\Booking\GuestController;
use App\Http\Controllers\Api\Booking\BookingActionController;
use App\Http\Controllers\Api\Booking\PaymentController;

// Staff controllers
use App\Http\Controllers\Api\Staff\StaffScheduleController;
use App\Http\Controllers\Api\Staff\HousekeepingController;
use App\Http\Controllers\Api\Staff\MaintenanceReportController;
use App\Http\Controllers\Api\Staff\LostFoundItemController;

/*
|--------------------------------------------------------------------------
| كل الـ API routes ملفوفة بـ name('api.') عشان ما تتصادم أسماؤها
| مع أسماء routes/web.php (متل rooms.index)
|--------------------------------------------------------------------------
*/
Route::name('api.')->group(function () {

    /*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

// =========================
// Register
// =========================

// إنشاء حساب جديد
// بعد إنشاء الحساب يتم إرسال OTP إلى إيميل المستخدم
Route::post('register', [AuthController::class, 'register'])->middleware('throttle:5,1');


// =========================
// Verify Register OTP
// =========================

// التحقق من رمز OTP الذي تم إرساله بعد التسجيل
// إذا كان الرمز صحيحاً يتم توثيق إيميل المستخدم
Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->middleware('throttle:10,1');


// =========================
// Login
// =========================

// تسجيل الدخول باستخدام الإيميل وكلمة المرور
// إذا كانت البيانات صحيحة يتم إرسال OTP إلى إيميل المستخدم
// لا يتم إنشاء JWT Token هنا
Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1');


// =========================
// Verify Login OTP
// =========================

// التحقق من OTP الخاص بتسجيل الدخول
// بعد نجاح التحقق يتم إنشاء JWT Token
Route::post('verify-login-otp', [AuthController::class, 'verifyLoginOtp'])->middleware('throttle:10,1');


// =========================
// Forgot / Reset Password
// =========================

// إرسال OTP لإعادة تعيين كلمة السر
Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:5,1');

// التحقق من OTP وتعيين كلمة سر جديدة
Route::post('reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:10,1');


// =========================
// Protected Routes
// =========================

// هذه الـ Routes تحتاج JWT Token صالح
Route::middleware('auth:api')->group(function () {

    // تسجيل خروج المستخدم وإلغاء الـ JWT Token
    Route::post('logout', [AuthController::class, 'logout']);

    // جلب بيانات المستخدم الذي قام بتسجيل الدخول
    Route::get('me', [AuthController::class, 'me']);
});

    /*
    |----------------------------------------------------------------
    | Booking resources
    |----------------------------------------------------------------
    */
    Route::middleware('auth:api')->group(function () {
        Route::apiResource('bookings', BookingController::class);
        Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel']);
        Route::apiResource('guests', GuestController::class);
        Route::apiResource('booking-actions', BookingActionController::class);
    });

    Route::post('stripe/webhook', [StripeWebhookController::class, 'handle']);
    Route::apiResource('payments', PaymentController::class);

    /*
    |----------------------------------------------------------------
    | Hotel resources
    |----------------------------------------------------------------
    */
    Route::apiResource('room-services', RoomServiceController::class);
    Route::patch('room-services/{roomService}/status', [RoomServiceController::class, 'updateStatus']);
    Route::apiResource('rooms-type', RoomsTypeController::class);
    Route::apiResource('room-images', RoomImageController::class);
    Route::apiResource('rooms', RoomController::class);
    Route::apiResource('room-prices', RoomPriceController::class);

    /*
    |----------------------------------------------------------------
    | Staff resources
    |----------------------------------------------------------------
    */
    Route::apiResource('housekeeping', HousekeepingController::class);
    Route::patch('housekeeping/{housekeeping}/finish', [HousekeepingController::class, 'finish']);
    Route::apiResource('maintenance-reports', MaintenanceReportController::class);
    Route::patch('maintenance-reports/{maintenanceReport}/resolve', [MaintenanceReportController::class, 'resolve']);
    Route::apiResource('lost-found-items', LostFoundItemController::class);
    Route::apiResource('staff-schedules', StaffScheduleController::class);

    /*
    |----------------------------------------------------------------
    | Root-level resources
    |----------------------------------------------------------------
    */
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);
    Route::apiResource('amenities', AmenityController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('invoices', InvoiceController::class);
    Route::apiResource('reviews', ReviewController::class);

    /*
    |----------------------------------------------------------------
    | Report resources
    |----------------------------------------------------------------
    */
    Route::prefix('reports')->group(function () {

        // 1. تقرير حجوزات غرفة ضمن فترة
        Route::get('/rooms/{room}/bookings', [ReportController::class, 'roomBookingReport']);

        // 2. جميع فواتير زبون + عدد حجوزاته
        Route::get('/customers/{user}/invoices', [ReportController::class, 'customerInvoices']);

        // 3. أكثر الغرف طلباً
        Route::get('/most-requested-rooms', [ReportController::class, 'mostRequestedRooms']);
    });
});

