# Hotel Management System

A full-featured hotel management system built with **Laravel**, covering the complete guest journey — from room browsing and booking to payment, check-in/check-out, and post-stay reviews — alongside an internal admin panel for staff operations (housekeeping, maintenance, room service, and reporting).

This project was built as a graduation capstone project, designed and implemented incrementally: database design (ERD) → migrations → models → factories/seeders → API layer → business logic.

---

## ✨ Key Features

### Guest-Facing
- **Room browsing & booking** with real-time availability checks across date ranges
- **Multi-room bookings** — a single reservation can include one or several rooms
- **Secure payments via Stripe**, including deposit collection and webhook-based confirmation
- **Cancellation policy** — automatic refund eligibility based on how close the cancellation is to check-in
- **Room service requests** with a full order lifecycle (pending → preparing → delivered)
- **Guest reviews** for completed stays
- **OTP-based authentication** — email verification codes for registration, login, and password reset (no password-only login)

### Staff / Admin (Filament Panel)
- **Booking management** — confirm, reject, check-in, check-out
- **Housekeeping workflow** — room cleaning status tracking (cleaning → available)
- **Maintenance reports** — issue tracking with severity levels and automatic room status updates
- **Lost & found** item logging
- **Staff scheduling**
- **Invoicing** — auto-generated from room charges and service charges
- **Reporting dashboards**: monthly revenue, cancellation rate, most-requested rooms, upcoming check-ins, staff performance, and more

### Access Control
- **Role-based permissions** (roles, permissions, role-permission mapping)
- **JWT-secured API** for all authenticated actions

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel |
| Authentication | JWT (`tymon/jwt-auth`) + Email OTP verification |
| Admin Panel | Filament |
| Payments | Stripe (Payment Intents + Webhooks) |
| API Documentation | L5-Swagger (OpenAPI) |
| Database | MySQL |
| Frontend (guest-facing) | Blade templates |

---

## 🗄️ Database Design

The system is modeled around 22 relational tables, including:

- **Core booking flow:** `bookings`, `booking_rooms` (many-to-many), `guests`, `booking_actions`
- **Hotel inventory:** `rooms`, `rooms_types`, `room_prices`, `room_images`, `amenities`
- **Payments & billing:** `payments`, `invoices`
- **Operations:** `housekeeping`, `maintenance_reports`, `lost_found_items`, `room_service`, `staff_schedule`
- **Access control:** `roles`, `permissions`, `role_has_permissions`, `users`
- **Feedback:** `reviews`

Key design decisions:
- Bookings support **multiple rooms per reservation** via a pivot table
- Room availability is derived from **date-range overlap checks** against active bookings, not a static room status flag
- Pricing is **date-sensitive** (`room_prices` supports different rates per period)
- UUIDs are used as primary keys across all tables

---

## 🔐 Authentication Flow

1. **Register** → account created → OTP sent to email
2. **Verify OTP** → email marked as verified
3. **Login** (email + password) → OTP sent again for 2-factor verification
4. **Verify Login OTP** → JWT token issued
5. **Forgot Password** → OTP sent → **Reset Password** with OTP verification

All authentication endpoints are rate-limited to prevent abuse.

---

## 💳 Booking & Payment Flow

1. Guest selects one or more rooms and a date range
2. System checks availability (no overlapping active bookings for the selected rooms/dates)
3. If available, guest proceeds to payment and pays a **deposit** via Stripe
4. Stripe webhook confirms payment → booking status becomes `confirmed`
5. **Cancellation:**
   - ≥ 2 days before check-in → booking cancelled **with deposit refund**
   - < 2 days before check-in → booking cancelled **without refund**
6. On check-in/check-out, room status updates accordingly, triggering housekeeping workflows

---

## 📡 API Overview

All API routes are prefixed and grouped under `api.*`, with JWT-protected resources for bookings, payments, staff operations, and administrative resources. Key resource groups:

- `POST /register`, `/verify-otp`, `/login`, `/verify-login-otp`, `/forgot-password`, `/reset-password`
- `GET|POST /bookings`, `POST /bookings/{booking}/cancel`
- `POST /stripe/webhook`
- `GET|PATCH /room-services`, `PATCH /room-services/{id}/status`
- `GET|PATCH /housekeeping`, `PATCH /housekeeping/{id}/finish`
- `GET|PATCH /maintenance-reports`, `PATCH /maintenance-reports/{id}/resolve`
- `GET /reports/rooms/{room}/bookings`
- `GET /reports/customers/{user}/invoices`
- `GET /reports/most-requested-rooms`

Full interactive API documentation is available via Swagger (L5-Swagger) once the project is running, at `/api/documentation`.

---

## 🚀 Installation

```bash
# Clone the repository
git clone https://github.com/roj99/Hotel-Management-System.git
cd Hotel-Management-System

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Environment setup
cp .env.example .env
php artisan key:generate
php artisan jwt:secret

# Configure your database and Stripe keys in .env, then run:
php artisan migrate --seed

# Build frontend assets
npm run build

# Serve the application
php artisan serve
```

### Required `.env` values
```
DB_CONNECTION=mysql
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

STRIPE_SECRET=...
STRIPE_WEBHOOK_SECRET=...

MAIL_MAILER=...   # required for sending OTP emails
```

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/Api/       # API controllers (Booking, Hotel, Staff, root-level)
│   ├── Requests/              # Form request validation, grouped by domain
│   └── Resources/             # API resource transformers
├── Models/                    # Eloquent models, grouped by domain (Booking, Hotel, Staff)
├── Filament/                  # Admin panel resources, pages, and widgets
└── Policies/                  # Authorization policies per model

database/
├── migrations/
├── factories/                 # Grouped by domain
└── seeders/                   # Grouped by domain, with realistic linked data
```

---

## 📄 License

This project was developed for educational purposes as a graduation capstone project.
