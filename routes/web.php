<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HotelController;
use App\Http\Controllers\Web\ContactController;

Route::get('/', [HotelController::class, 'home'])->name('home');

Route::get('/rooms', [HotelController::class, 'rooms'])->name('rooms.index');

Route::get('/rooms/{roomsType}', [HotelController::class, 'show'])->name('rooms.show');

Route::get('/rooms/{roomsType}/book', [HotelController::class, 'bookForm'])->name('rooms.book');

Route::view('/about', 'about')->name('about');

Route::view('/login', 'auth.login')->name('login');

Route::view('/register', 'auth.register')->name('register');

Route::view('/forgot-password', 'auth.forgot-password')->name('password.forgot');

Route::view('/payment-success', 'payment-success')->name('payment.success');

Route::view('/payment-cancelled', 'payment-cancelled')->name('payment.cancelled');

Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');

Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::view('/my-bookings', 'my-bookings')->name('bookings.mine');

Route::view('/profile', 'profile')->name('profile');

Route::view('/room-services', 'room-services')->name('room-services');

Route::view('/reviews', 'reviews')->name('reviews.index');
