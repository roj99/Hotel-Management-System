<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Hotel Management System API',
    description: 'REST API for the Hotel Management System capstone project — rooms, bookings, payments, staff operations and reports.'
)]

#[OA\Server(
    url: 'http://myapp.test',
    description: 'Local Development Server'
)]

#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'JWT access token. Send as: Authorization: Bearer {token}'
)]

class ApiDocumentation
{
}

/*
|--------------------------------------------------------------------------
| Reusable component schemas
|--------------------------------------------------------------------------
*/

#[OA\Schema(
    schema: 'ValidationError',
    type: 'object',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
        new OA\Property(
            property: 'errors',
            type: 'object',
            example: ['field' => ['The field is required.']]
        ),
    ]
)]
class ValidationErrorSchema {}

#[OA\Schema(
    schema: 'Amenity',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'name', type: 'string', example: 'Free WiFi'),
    ]
)]
class AmenitySchema {}

#[OA\Schema(
    schema: 'Role',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'name', type: 'string', example: 'receptionist'),
        new OA\Property(property: 'permissions', type: 'array', items: new OA\Items(ref: '#/components/schemas/Permission')),
    ]
)]
class RoleSchema {}

#[OA\Schema(
    schema: 'Permission',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'name', type: 'string', example: 'manage-bookings'),
    ]
)]
class PermissionSchema {}

#[OA\Schema(
    schema: 'User',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'role_id', type: 'string', format: 'uuid', nullable: true),
        new OA\Property(property: 'full_name', type: 'string', example: 'Rojin Mohammad'),
        new OA\Property(property: 'email', type: 'string', format: 'email'),
        new OA\Property(property: 'phone', type: 'string', example: '0999999999'),
        new OA\Property(property: 'email_verified_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
class UserSchema {}

#[OA\Schema(
    schema: 'Invoice',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'booking_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'room_charge', type: 'number', format: 'float'),
        new OA\Property(property: 'services_charge', type: 'number', format: 'float'),
        new OA\Property(property: 'total_amount', type: 'number', format: 'float'),
        new OA\Property(property: 'issued_at', type: 'string', format: 'date-time'),
    ]
)]
class InvoiceSchema {}

#[OA\Schema(
    schema: 'Review',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'booking_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'user_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'rating', type: 'integer', example: 5),
        new OA\Property(property: 'comment', type: 'string', nullable: true),
    ]
)]
class ReviewSchema {}

#[OA\Schema(
    schema: 'RoomsType',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'name', type: 'string', example: 'Deluxe Double'),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'capacity', type: 'integer', example: 2),
    ]
)]
class RoomsTypeSchema {}

#[OA\Schema(
    schema: 'RoomImage',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'room_type_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'image_path', type: 'string', example: 'room-types/deluxe-1.jpg'),
    ]
)]
class RoomImageSchema {}

#[OA\Schema(
    schema: 'Room',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'room_type_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'room_number', type: 'string', example: '101'),
        new OA\Property(property: 'status', type: 'string', enum: ['available', 'occupied', 'cleaning', 'maintenance']),
        new OA\Property(property: 'floor', type: 'integer', example: 1),
    ]
)]
class RoomSchema {}

#[OA\Schema(
    schema: 'RoomPrice',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'room_type_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'start_date', type: 'string', format: 'date'),
        new OA\Property(property: 'end_date', type: 'string', format: 'date'),
        new OA\Property(property: 'price_per_night', type: 'number', format: 'float', example: 89.99),
    ]
)]
class RoomPriceSchema {}

#[OA\Schema(
    schema: 'RoomService',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'booking_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'handled_by', type: 'string', format: 'uuid', nullable: true),
        new OA\Property(property: 'item_description', type: 'string', example: 'Club sandwich + orange juice'),
        new OA\Property(property: 'quantity', type: 'integer', example: 1),
        new OA\Property(property: 'price', type: 'number', format: 'float'),
        new OA\Property(property: 'status', type: 'string', enum: ['pending', 'preparing', 'delivered', 'cancelled']),
        new OA\Property(property: 'ordered_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'delivered_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
class RoomServiceSchema {}

#[OA\Schema(
    schema: 'Booking',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'user_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'check_in_date', type: 'string', format: 'date'),
        new OA\Property(property: 'check_out_date', type: 'string', format: 'date'),
        new OA\Property(property: 'status', type: 'string', enum: ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled']),
        new OA\Property(property: 'total_price', type: 'number', format: 'float'),
        new OA\Property(property: 'deposit_amount', type: 'number', format: 'float'),
        new OA\Property(property: 'id_document_type', type: 'string', nullable: true),
        new OA\Property(property: 'id_document_number', type: 'string', nullable: true),
        new OA\Property(property: 'guests_count', type: 'integer', example: 2),
    ]
)]
class BookingSchema {}

#[OA\Schema(
    schema: 'Guest',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'booking_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'full_name', type: 'string'),
        new OA\Property(property: 'id_document_type', type: 'string', nullable: true),
        new OA\Property(property: 'id_document_number', type: 'string', nullable: true),
    ]
)]
class GuestSchema {}

#[OA\Schema(
    schema: 'BookingAction',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'booking_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'action_type', type: 'string', example: 'cancelled'),
        new OA\Property(property: 'performed_by', type: 'string', format: 'uuid', nullable: true),
        new OA\Property(property: 'reason', type: 'string', nullable: true),
        new OA\Property(property: 'performed_at', type: 'string', format: 'date-time'),
    ]
)]
class BookingActionSchema {}

#[OA\Schema(
    schema: 'Payment',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'booking_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'invoice_id', type: 'string', format: 'uuid', nullable: true),
        new OA\Property(property: 'amount', type: 'number', format: 'float'),
        new OA\Property(property: 'type', type: 'string', enum: ['deposit', 'refund', 'balance']),
        new OA\Property(property: 'method', type: 'string', example: 'card'),
        new OA\Property(property: 'stripe_payment_intent_id', type: 'string', nullable: true),
        new OA\Property(property: 'paid_at', type: 'string', format: 'date-time'),
    ]
)]
class PaymentSchema {}

#[OA\Schema(
    schema: 'Housekeeping',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'room_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'staff_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'started_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'finished_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'notes', type: 'string', nullable: true),
    ]
)]
class HousekeepingSchema {}

#[OA\Schema(
    schema: 'MaintenanceReport',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'room_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'reported_by', type: 'string', format: 'uuid'),
        new OA\Property(property: 'issue_description', type: 'string'),
        new OA\Property(property: 'severity', type: 'string', example: 'medium'),
        new OA\Property(property: 'status', type: 'string', enum: ['open', 'resolved']),
        new OA\Property(property: 'resolved_by', type: 'string', format: 'uuid', nullable: true),
        new OA\Property(property: 'reported_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'resolved_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
class MaintenanceReportSchema {}

#[OA\Schema(
    schema: 'LostFoundItem',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'room_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'found_by', type: 'string', format: 'uuid'),
        new OA\Property(property: 'item_description', type: 'string'),
        new OA\Property(property: 'storage_location', type: 'string', nullable: true),
        new OA\Property(property: 'status', type: 'string', example: 'stored'),
        new OA\Property(property: 'found_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'returned_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
class LostFoundItemSchema {}

#[OA\Schema(
    schema: 'StaffSchedule',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'user_id', type: 'string', format: 'uuid'),
        new OA\Property(property: 'shift_date', type: 'string', format: 'date'),
        new OA\Property(property: 'shift_start', type: 'string', format: 'date-time'),
        new OA\Property(property: 'shift_end', type: 'string', format: 'date-time'),
        new OA\Property(property: 'status', type: 'string', example: 'scheduled'),
    ]
)]
class StaffScheduleSchema {}

/*
|--------------------------------------------------------------------------
| Auth — AuthController (register / verify-otp / login / logout / me)
|--------------------------------------------------------------------------
*/
class AuthDocs
{
    #[OA\Post(
        path: '/api/register',
        tags: ['Auth'],
        summary: 'Register a new user (sends an OTP to email)',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Rojin Mohammad'),
                    new OA\Property(property: 'email', type: 'string', format: 'email'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', minLength: 8),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'User created, OTP sent', content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'message', type: 'string'),
                    new OA\Property(property: 'user_id', type: 'string', format: 'uuid'),
                ]
            )),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function register() {}

    #[OA\Post(
        path: '/api/verify-otp',
        tags: ['Auth'],
        summary: 'Verify the OTP code sent to the user\'s email',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['user_id', 'code'],
                properties: [
                    new OA\Property(property: 'user_id', type: 'string', format: 'uuid'),
                    new OA\Property(property: 'code', type: 'string', example: '123456'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'OTP verified successfully'),
            new OA\Response(response: 400, description: 'Invalid OTP'),
        ]
    )]
    public function verifyOtp() {}

    #[OA\Post(
        path: '/api/login',
        tags: ['Auth'],
        summary: 'Log in and receive a JWT access token',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email'),
                    new OA\Property(property: 'password', type: 'string', format: 'password'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Authenticated', content: new OA\JsonContent(
                properties: [new OA\Property(property: 'token', type: 'string')]
            )),
            new OA\Response(response: 401, description: 'Invalid credentials'),
        ]
    )]
    public function login() {}

    #[OA\Post(
        path: '/api/logout',
        tags: ['Auth'],
        summary: 'Log out (invalidate the current JWT)',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Logged out successfully'),
            new OA\Response(response: 500, description: 'Failed to logout'),
        ]
    )]
    public function logout() {}

    #[OA\Get(
        path: '/api/me',
        tags: ['Auth'],
        summary: 'Get the currently authenticated user',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Current user', content: new OA\JsonContent(ref: '#/components/schemas/User')),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function me() {}
}

/*
|--------------------------------------------------------------------------
| Amenities — Api\AmenityController
|--------------------------------------------------------------------------
*/
class AmenityDocs
{
    #[OA\Get(path: '/api/amenities', tags: ['Amenities'], summary: 'List all amenities',
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Amenity')))]
    )]
    public function index() {}

    #[OA\Post(path: '/api/amenities', tags: ['Amenities'], summary: 'Create an amenity',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(required: ['name'], properties: [new OA\Property(property: 'name', type: 'string')])),
        responses: [
            new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(ref: '#/components/schemas/Amenity')),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(path: '/api/amenities/{amenity}', tags: ['Amenities'], summary: 'Get a single amenity',
        parameters: [new OA\Parameter(name: 'amenity', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [
            new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/Amenity')),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function show() {}

    #[OA\Put(path: '/api/amenities/{amenity}', tags: ['Amenities'], summary: 'Update an amenity',
        parameters: [new OA\Parameter(name: 'amenity', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(properties: [new OA\Property(property: 'name', type: 'string')])),
        responses: [
            new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(ref: '#/components/schemas/Amenity')),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function update() {}

    #[OA\Delete(path: '/api/amenities/{amenity}', tags: ['Amenities'], summary: 'Delete an amenity',
        parameters: [new OA\Parameter(name: 'amenity', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 204, description: 'Deleted')]
    )]
    public function destroy() {}
}

/*
|--------------------------------------------------------------------------
| Roles — Api\RoleController
|--------------------------------------------------------------------------
*/
class RoleDocs
{
    #[OA\Get(path: '/api/roles', tags: ['Roles'], summary: 'List all roles',
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Role')))]
    )]
    public function index() {}

    #[OA\Post(path: '/api/roles', tags: ['Roles'], summary: 'Create a role',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(required: ['name'], properties: [new OA\Property(property: 'name', type: 'string')])),
        responses: [
            new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(ref: '#/components/schemas/Role')),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(path: '/api/roles/{role}', tags: ['Roles'], summary: 'Get a role (with its permissions loaded)',
        parameters: [new OA\Parameter(name: 'role', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [
            new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/Role')),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function show() {}

    #[OA\Put(path: '/api/roles/{role}', tags: ['Roles'], summary: 'Update a role',
        parameters: [new OA\Parameter(name: 'role', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(properties: [new OA\Property(property: 'name', type: 'string')])),
        responses: [new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(ref: '#/components/schemas/Role'))]
    )]
    public function update() {}

    #[OA\Delete(path: '/api/roles/{role}', tags: ['Roles'], summary: 'Delete a role',
        parameters: [new OA\Parameter(name: 'role', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 204, description: 'Deleted')]
    )]
    public function destroy() {}
}

/*
|--------------------------------------------------------------------------
| Permissions — Api\PermissionController
|--------------------------------------------------------------------------
*/
class PermissionDocs
{
    #[OA\Get(path: '/api/permissions', tags: ['Permissions'], summary: 'List all permissions',
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Permission')))]
    )]
    public function index() {}

    #[OA\Post(path: '/api/permissions', tags: ['Permissions'], summary: 'Create a permission',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(required: ['name'], properties: [new OA\Property(property: 'name', type: 'string')])),
        responses: [
            new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(ref: '#/components/schemas/Permission')),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(path: '/api/permissions/{permission}', tags: ['Permissions'], summary: 'Get a single permission',
        parameters: [new OA\Parameter(name: 'permission', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/Permission'))]
    )]
    public function show() {}

    #[OA\Put(path: '/api/permissions/{permission}', tags: ['Permissions'], summary: 'Update a permission',
        parameters: [new OA\Parameter(name: 'permission', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(properties: [new OA\Property(property: 'name', type: 'string')])),
        responses: [new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(ref: '#/components/schemas/Permission'))]
    )]
    public function update() {}

    #[OA\Delete(path: '/api/permissions/{permission}', tags: ['Permissions'], summary: 'Delete a permission',
        parameters: [new OA\Parameter(name: 'permission', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 204, description: 'Deleted')]
    )]
    public function destroy() {}
}

/*
|--------------------------------------------------------------------------
| Users — Api\UserController
|--------------------------------------------------------------------------
*/
class UserDocs
{
    #[OA\Get(path: '/api/users', tags: ['Users'], summary: 'List all users (with role loaded)',
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/User')))]
    )]
    public function index() {}

    #[OA\Post(path: '/api/users', tags: ['Users'], summary: 'Create a user',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['full_name', 'email', 'password'],
            properties: [
                new OA\Property(property: 'role_id', type: 'string', format: 'uuid', nullable: true),
                new OA\Property(property: 'full_name', type: 'string'),
                new OA\Property(property: 'email', type: 'string', format: 'email'),
                new OA\Property(property: 'phone', type: 'string'),
                new OA\Property(property: 'password', type: 'string', format: 'password'),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(ref: '#/components/schemas/User')),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(path: '/api/users/{user}', tags: ['Users'], summary: 'Get a single user (with role loaded)',
        parameters: [new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/User'))]
    )]
    public function show() {}

    #[OA\Put(path: '/api/users/{user}', tags: ['Users'], summary: 'Update a user (password re-hashed if provided)',
        parameters: [new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'full_name', type: 'string'),
                new OA\Property(property: 'email', type: 'string', format: 'email'),
                new OA\Property(property: 'phone', type: 'string'),
                new OA\Property(property: 'password', type: 'string', format: 'password', nullable: true),
            ]
        )),
        responses: [new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(ref: '#/components/schemas/User'))]
    )]
    public function update() {}

    #[OA\Delete(path: '/api/users/{user}', tags: ['Users'], summary: 'Delete a user',
        parameters: [new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 204, description: 'Deleted')]
    )]
    public function destroy() {}
}

/*
|--------------------------------------------------------------------------
| Invoices — Api\InvoiceController
|--------------------------------------------------------------------------
*/
class InvoiceDocs
{
    #[OA\Get(path: '/api/invoices', tags: ['Invoices'], summary: 'List all invoices (with booking loaded)',
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Invoice')))]
    )]
    public function index() {}

    #[OA\Post(path: '/api/invoices', tags: ['Invoices'], summary: 'Create an invoice',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['booking_id'],
            properties: [
                new OA\Property(property: 'booking_id', type: 'string', format: 'uuid'),
                new OA\Property(property: 'room_charge', type: 'number', format: 'float'),
                new OA\Property(property: 'services_charge', type: 'number', format: 'float'),
                new OA\Property(property: 'total_amount', type: 'number', format: 'float'),
                new OA\Property(property: 'issued_at', type: 'string', format: 'date-time'),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(ref: '#/components/schemas/Invoice')),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(path: '/api/invoices/{invoice}', tags: ['Invoices'], summary: 'Get a single invoice (with booking + payments loaded)',
        parameters: [new OA\Parameter(name: 'invoice', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/Invoice'))]
    )]
    public function show() {}

    #[OA\Put(path: '/api/invoices/{invoice}', tags: ['Invoices'], summary: 'Update an invoice',
        parameters: [new OA\Parameter(name: 'invoice', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(properties: [
            new OA\Property(property: 'room_charge', type: 'number', format: 'float'),
            new OA\Property(property: 'services_charge', type: 'number', format: 'float'),
            new OA\Property(property: 'total_amount', type: 'number', format: 'float'),
        ])),
        responses: [new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(ref: '#/components/schemas/Invoice'))]
    )]
    public function update() {}

    #[OA\Delete(path: '/api/invoices/{invoice}', tags: ['Invoices'], summary: 'Delete an invoice',
        parameters: [new OA\Parameter(name: 'invoice', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 204, description: 'Deleted')]
    )]
    public function destroy() {}
}

/*
|--------------------------------------------------------------------------
| Reviews — Api\ReviewController
|--------------------------------------------------------------------------
*/
class ReviewDocs
{
    #[OA\Get(path: '/api/reviews', tags: ['Reviews'], summary: 'List all reviews (with booking + user loaded)',
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Review')))]
    )]
    public function index() {}

    #[OA\Post(path: '/api/reviews', tags: ['Reviews'], summary: 'Create a review (booking must already be checked_out)',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['booking_id', 'user_id', 'rating'],
            properties: [
                new OA\Property(property: 'booking_id', type: 'string', format: 'uuid'),
                new OA\Property(property: 'user_id', type: 'string', format: 'uuid'),
                new OA\Property(property: 'rating', type: 'integer', minimum: 1, maximum: 5),
                new OA\Property(property: 'comment', type: 'string', nullable: true),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(ref: '#/components/schemas/Review')),
            new OA\Response(response: 422, description: 'Stay not completed yet, or validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(path: '/api/reviews/{review}', tags: ['Reviews'], summary: 'Get a single review',
        parameters: [new OA\Parameter(name: 'review', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/Review'))]
    )]
    public function show() {}

    #[OA\Put(path: '/api/reviews/{review}', tags: ['Reviews'], summary: 'Update a review',
        parameters: [new OA\Parameter(name: 'review', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(properties: [
            new OA\Property(property: 'rating', type: 'integer', minimum: 1, maximum: 5),
            new OA\Property(property: 'comment', type: 'string', nullable: true),
        ])),
        responses: [new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(ref: '#/components/schemas/Review'))]
    )]
    public function update() {}

    #[OA\Delete(path: '/api/reviews/{review}', tags: ['Reviews'], summary: 'Delete a review',
        parameters: [new OA\Parameter(name: 'review', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 204, description: 'Deleted')]
    )]
    public function destroy() {}
}

/*
|--------------------------------------------------------------------------
| Room Types — Api\Hotel\RoomsTypeController
|--------------------------------------------------------------------------
*/
class RoomsTypeDocs
{
    #[OA\Get(path: '/api/rooms-type', tags: ['Room Types'], summary: 'List room types (with images, amenities, prices loaded)',
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/RoomsType')))]
    )]
    public function index() {}

    #[OA\Post(path: '/api/rooms-type', tags: ['Room Types'], summary: 'Create a room type',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['name', 'capacity'],
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'description', type: 'string', nullable: true),
                new OA\Property(property: 'capacity', type: 'integer'),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(ref: '#/components/schemas/RoomsType')),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(path: '/api/rooms-type/{roomsType}', tags: ['Room Types'], summary: 'Get a single room type (with images, amenities, prices loaded)',
        parameters: [new OA\Parameter(name: 'roomsType', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/RoomsType'))]
    )]
    public function show() {}

    #[OA\Put(path: '/api/rooms-type/{roomsType}', tags: ['Room Types'], summary: 'Update a room type',
        parameters: [new OA\Parameter(name: 'roomsType', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(properties: [
            new OA\Property(property: 'name', type: 'string'),
            new OA\Property(property: 'description', type: 'string', nullable: true),
            new OA\Property(property: 'capacity', type: 'integer'),
        ])),
        responses: [new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(ref: '#/components/schemas/RoomsType'))]
    )]
    public function update() {}

    #[OA\Delete(path: '/api/rooms-type/{roomsType}', tags: ['Room Types'], summary: 'Delete a room type',
        parameters: [new OA\Parameter(name: 'roomsType', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 204, description: 'Deleted')]
    )]
    public function destroy() {}
}

/*
|--------------------------------------------------------------------------
| Room Images — Api\Hotel\RoomImageController
|--------------------------------------------------------------------------
*/
class RoomImageDocs
{
    #[OA\Get(path: '/api/room-images', tags: ['Room Images'], summary: 'List all room images',
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/RoomImage')))]
    )]
    public function index() {}

    #[OA\Post(path: '/api/room-images', tags: ['Room Images'], summary: 'Add an image to a room type',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['room_type_id', 'image_path'],
            properties: [
                new OA\Property(property: 'room_type_id', type: 'string', format: 'uuid'),
                new OA\Property(property: 'image_path', type: 'string'),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(ref: '#/components/schemas/RoomImage')),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(path: '/api/room-images/{roomImage}', tags: ['Room Images'], summary: 'Get a single room image',
        parameters: [new OA\Parameter(name: 'roomImage', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/RoomImage'))]
    )]
    public function show() {}

    #[OA\Put(path: '/api/room-images/{roomImage}', tags: ['Room Images'], summary: 'Update a room image',
        parameters: [new OA\Parameter(name: 'roomImage', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(properties: [new OA\Property(property: 'image_path', type: 'string')])),
        responses: [new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(ref: '#/components/schemas/RoomImage'))]
    )]
    public function update() {}

    #[OA\Delete(path: '/api/room-images/{roomImage}', tags: ['Room Images'], summary: 'Delete a room image',
        parameters: [new OA\Parameter(name: 'roomImage', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 204, description: 'Deleted')]
    )]
    public function destroy() {}
}

/*
|--------------------------------------------------------------------------
| Rooms — Api\Hotel\RoomController
|--------------------------------------------------------------------------
*/
class RoomDocs
{
    #[OA\Get(path: '/api/rooms', tags: ['Rooms'], summary: 'List all rooms (with room type loaded)',
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Room')))]
    )]
    public function index() {}

    #[OA\Post(path: '/api/rooms', tags: ['Rooms'], summary: 'Create a room',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['room_type_id', 'room_number'],
            properties: [
                new OA\Property(property: 'room_type_id', type: 'string', format: 'uuid'),
                new OA\Property(property: 'room_number', type: 'string'),
                new OA\Property(property: 'status', type: 'string', enum: ['available', 'occupied', 'cleaning', 'maintenance']),
                new OA\Property(property: 'floor', type: 'integer'),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(ref: '#/components/schemas/Room')),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(path: '/api/rooms/{room}', tags: ['Rooms'], summary: 'Get a single room (with room type loaded)',
        parameters: [new OA\Parameter(name: 'room', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/Room'))]
    )]
    public function show() {}

    #[OA\Put(path: '/api/rooms/{room}', tags: ['Rooms'], summary: 'Update a room',
        parameters: [new OA\Parameter(name: 'room', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(properties: [
            new OA\Property(property: 'room_number', type: 'string'),
            new OA\Property(property: 'status', type: 'string', enum: ['available', 'occupied', 'cleaning', 'maintenance']),
            new OA\Property(property: 'floor', type: 'integer'),
        ])),
        responses: [new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(ref: '#/components/schemas/Room'))]
    )]
    public function update() {}

    #[OA\Delete(path: '/api/rooms/{room}', tags: ['Rooms'], summary: 'Delete a room',
        parameters: [new OA\Parameter(name: 'room', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 204, description: 'Deleted')]
    )]
    public function destroy() {}
}

/*
|--------------------------------------------------------------------------
| Room Prices — Api\Hotel\RoomPriceController
|--------------------------------------------------------------------------
*/
class RoomPriceDocs
{
    #[OA\Get(path: '/api/room-prices', tags: ['Room Prices'], summary: 'List all room prices',
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/RoomPrice')))]
    )]
    public function index() {}

    #[OA\Post(path: '/api/room-prices', tags: ['Room Prices'], summary: 'Create a price period for a room type',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['room_type_id', 'start_date', 'end_date', 'price_per_night'],
            properties: [
                new OA\Property(property: 'room_type_id', type: 'string', format: 'uuid'),
                new OA\Property(property: 'start_date', type: 'string', format: 'date'),
                new OA\Property(property: 'end_date', type: 'string', format: 'date'),
                new OA\Property(property: 'price_per_night', type: 'number', format: 'float'),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(ref: '#/components/schemas/RoomPrice')),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(path: '/api/room-prices/{roomPrice}', tags: ['Room Prices'], summary: 'Get a single room price entry',
        parameters: [new OA\Parameter(name: 'roomPrice', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/RoomPrice'))]
    )]
    public function show() {}

    #[OA\Put(path: '/api/room-prices/{roomPrice}', tags: ['Room Prices'], summary: 'Update a room price entry',
        parameters: [new OA\Parameter(name: 'roomPrice', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(properties: [
            new OA\Property(property: 'start_date', type: 'string', format: 'date'),
            new OA\Property(property: 'end_date', type: 'string', format: 'date'),
            new OA\Property(property: 'price_per_night', type: 'number', format: 'float'),
        ])),
        responses: [new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(ref: '#/components/schemas/RoomPrice'))]
    )]
    public function update() {}

    #[OA\Delete(path: '/api/room-prices/{roomPrice}', tags: ['Room Prices'], summary: 'Delete a room price entry',
        parameters: [new OA\Parameter(name: 'roomPrice', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 204, description: 'Deleted')]
    )]
    public function destroy() {}
}

/*
|--------------------------------------------------------------------------
| Room Services — Api\Hotel\RoomServiceController
|--------------------------------------------------------------------------
*/
class RoomServiceDocs
{
    #[OA\Get(path: '/api/room-services', tags: ['Room Services'], summary: 'List all room service orders (with booking + handledBy loaded)',
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/RoomService')))]
    )]
    public function index() {}

    #[OA\Post(path: '/api/room-services', tags: ['Room Services'], summary: 'Order a room service item (starts as "pending")',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['booking_id', 'item_description', 'quantity', 'price'],
            properties: [
                new OA\Property(property: 'booking_id', type: 'string', format: 'uuid'),
                new OA\Property(property: 'handled_by', type: 'string', format: 'uuid', nullable: true),
                new OA\Property(property: 'item_description', type: 'string'),
                new OA\Property(property: 'quantity', type: 'integer'),
                new OA\Property(property: 'price', type: 'number', format: 'float'),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(ref: '#/components/schemas/RoomService')),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(path: '/api/room-services/{roomService}', tags: ['Room Services'], summary: 'Get a single room service order',
        parameters: [new OA\Parameter(name: 'roomService', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/RoomService'))]
    )]
    public function show() {}

    #[OA\Put(path: '/api/room-services/{roomService}', tags: ['Room Services'], summary: 'Update a room service order',
        parameters: [new OA\Parameter(name: 'roomService', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(properties: [
            new OA\Property(property: 'item_description', type: 'string'),
            new OA\Property(property: 'quantity', type: 'integer'),
            new OA\Property(property: 'price', type: 'number', format: 'float'),
        ])),
        responses: [new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(ref: '#/components/schemas/RoomService'))]
    )]
    public function update() {}

    #[OA\Delete(path: '/api/room-services/{roomService}', tags: ['Room Services'], summary: 'Delete a room service order',
        parameters: [new OA\Parameter(name: 'roomService', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 204, description: 'Deleted')]
    )]
    public function destroy() {}

    #[OA\Patch(
        path: '/api/room-services/{roomService}/status',
        tags: ['Room Services'],
        summary: 'Advance the order status: pending -> preparing -> delivered (or cancelled)',
        parameters: [new OA\Parameter(name: 'roomService', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['status'],
            properties: [new OA\Property(property: 'status', type: 'string', enum: ['pending', 'preparing', 'delivered', 'cancelled'])]
        )),
        responses: [
            new OA\Response(response: 200, description: 'Status updated', content: new OA\JsonContent(ref: '#/components/schemas/RoomService')),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function updateStatus() {}
}

/*
|--------------------------------------------------------------------------
| Bookings — Api\Booking\BookingController (auth:api)
|--------------------------------------------------------------------------
*/
class BookingDocs
{
    #[OA\Get(path: '/api/bookings', tags: ['Bookings'], summary: 'List all bookings, newest first (with user + rooms loaded)',
        security: [['bearerAuth' => []]],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Booking')))]
    )]
    public function index() {}

    #[OA\Post(
        path: '/api/bookings',
        tags: ['Bookings'],
        summary: 'Create a booking: checks room availability, then opens a Stripe Checkout Session for the deposit',
        description: 'Rejects with 409 if any requested room overlaps an existing non-cancelled booking for the given dates. On success the booking is created with status "pending" and a Stripe payment_url is returned; the booking is only confirmed once the Stripe webhook receives the completed checkout session.',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['room_ids', 'check_in_date', 'check_out_date', 'deposit_amount'],
            properties: [
                new OA\Property(property: 'room_ids', type: 'array', items: new OA\Items(type: 'string', format: 'uuid')),
                new OA\Property(property: 'check_in_date', type: 'string', format: 'date'),
                new OA\Property(property: 'check_out_date', type: 'string', format: 'date'),
                new OA\Property(property: 'deposit_amount', type: 'number', format: 'float'),
                new OA\Property(property: 'total_price', type: 'number', format: 'float'),
                new OA\Property(property: 'guests_count', type: 'integer'),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Booking created, Stripe payment URL returned', content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'booking', ref: '#/components/schemas/Booking'),
                    new OA\Property(property: 'payment_url', type: 'string', format: 'uri'),
                ]
            )),
            new OA\Response(response: 409, description: 'One or more rooms are not available for the requested dates', content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'message', type: 'string'),
                    new OA\Property(property: 'unavailable_room_ids', type: 'array', items: new OA\Items(type: 'string', format: 'uuid')),
                ]
            )),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(path: '/api/bookings/{booking}', tags: ['Bookings'], summary: 'Get a single booking (with user, rooms, guests, actions, payments loaded)',
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'booking', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/Booking'))]
    )]
    public function show() {}

    #[OA\Put(path: '/api/bookings/{booking}', tags: ['Bookings'], summary: 'Update a booking',
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'booking', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(properties: [
            new OA\Property(property: 'check_in_date', type: 'string', format: 'date'),
            new OA\Property(property: 'check_out_date', type: 'string', format: 'date'),
            new OA\Property(property: 'status', type: 'string', enum: ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled']),
        ])),
        responses: [new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(ref: '#/components/schemas/Booking'))]
    )]
    public function update() {}

    #[OA\Delete(path: '/api/bookings/{booking}', tags: ['Bookings'], summary: 'Delete a booking',
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'booking', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 204, description: 'Deleted')]
    )]
    public function destroy() {}

    #[OA\Post(
        path: '/api/bookings/{booking}/cancel',
        tags: ['Bookings'],
        summary: 'Cancel a pending/confirmed booking (refunds via Stripe if 2+ days before check-in)',
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'booking', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: false, content: new OA\JsonContent(properties: [
            new OA\Property(property: 'reason', type: 'string', nullable: true),
        ])),
        responses: [
            new OA\Response(response: 200, description: 'Cancelled', content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'booking', ref: '#/components/schemas/Booking'),
                    new OA\Property(property: 'refunded', type: 'boolean'),
                ]
            )),
            new OA\Response(response: 422, description: 'Booking can no longer be cancelled'),
        ]
    )]
    public function cancel() {}
}

/*
|--------------------------------------------------------------------------
| Stripe Webhook — Api\Booking\StripeWebhookController (no auth, called by Stripe)
|--------------------------------------------------------------------------
*/
class StripeWebhookDocs
{
    #[OA\Post(
        path: '/api/stripe/webhook',
        tags: ['Stripe Webhook'],
        summary: 'Stripe calls this when a checkout session completes; confirms the booking and records the deposit payment',
        description: 'Verified using the Stripe-Signature header and the configured webhook secret. Not intended to be called manually.',
        responses: [
            new OA\Response(response: 200, description: 'Event processed', content: new OA\JsonContent(properties: [new OA\Property(property: 'received', type: 'boolean')])),
            new OA\Response(response: 400, description: 'Invalid webhook payload/signature'),
        ]
    )]
    public function handle() {}
}

/*
|--------------------------------------------------------------------------
| Guests — Api\Booking\GuestController (auth:api)
|--------------------------------------------------------------------------
*/
class GuestDocs
{
    #[OA\Get(path: '/api/guests', tags: ['Guests'], summary: 'List all guests',
        security: [['bearerAuth' => []]],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Guest')))]
    )]
    public function index() {}

    #[OA\Post(path: '/api/guests', tags: ['Guests'], summary: 'Add a guest to a booking',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['booking_id', 'full_name'],
            properties: [
                new OA\Property(property: 'booking_id', type: 'string', format: 'uuid'),
                new OA\Property(property: 'full_name', type: 'string'),
                new OA\Property(property: 'id_document_type', type: 'string', nullable: true),
                new OA\Property(property: 'id_document_number', type: 'string', nullable: true),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(ref: '#/components/schemas/Guest')),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(path: '/api/guests/{guest}', tags: ['Guests'], summary: 'Get a single guest',
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'guest', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/Guest'))]
    )]
    public function show() {}

    #[OA\Put(path: '/api/guests/{guest}', tags: ['Guests'], summary: 'Update a guest',
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'guest', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(properties: [
            new OA\Property(property: 'full_name', type: 'string'),
            new OA\Property(property: 'id_document_type', type: 'string', nullable: true),
            new OA\Property(property: 'id_document_number', type: 'string', nullable: true),
        ])),
        responses: [new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(ref: '#/components/schemas/Guest'))]
    )]
    public function update() {}

    #[OA\Delete(path: '/api/guests/{guest}', tags: ['Guests'], summary: 'Delete a guest',
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'guest', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 204, description: 'Deleted')]
    )]
    public function destroy() {}
}

/*
|--------------------------------------------------------------------------
| Booking Actions — Api\Booking\BookingActionController (auth:api)
|--------------------------------------------------------------------------
*/
class BookingActionDocs
{
    #[OA\Get(path: '/api/booking-actions', tags: ['Booking Actions'], summary: 'List all booking actions (with booking + performedBy loaded)',
        security: [['bearerAuth' => []]],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/BookingAction')))]
    )]
    public function index() {}

    #[OA\Post(path: '/api/booking-actions', tags: ['Booking Actions'], summary: 'Log a booking action',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['booking_id', 'action_type'],
            properties: [
                new OA\Property(property: 'booking_id', type: 'string', format: 'uuid'),
                new OA\Property(property: 'action_type', type: 'string'),
                new OA\Property(property: 'performed_by', type: 'string', format: 'uuid', nullable: true),
                new OA\Property(property: 'reason', type: 'string', nullable: true),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(ref: '#/components/schemas/BookingAction')),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(path: '/api/booking-actions/{bookingAction}', tags: ['Booking Actions'], summary: 'Get a single booking action',
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'bookingAction', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/BookingAction'))]
    )]
    public function show() {}

    #[OA\Put(path: '/api/booking-actions/{bookingAction}', tags: ['Booking Actions'], summary: 'Update a booking action',
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'bookingAction', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(properties: [
            new OA\Property(property: 'action_type', type: 'string'),
            new OA\Property(property: 'reason', type: 'string', nullable: true),
        ])),
        responses: [new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(ref: '#/components/schemas/BookingAction'))]
    )]
    public function update() {}

    #[OA\Delete(path: '/api/booking-actions/{bookingAction}', tags: ['Booking Actions'], summary: 'Delete a booking action',
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'bookingAction', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 204, description: 'Deleted')]
    )]
    public function destroy() {}
}

/*
|--------------------------------------------------------------------------
| Payments — Api\Booking\PaymentController
|--------------------------------------------------------------------------
*/
class PaymentDocs
{
    #[OA\Get(path: '/api/payments', tags: ['Payments'], summary: 'List all payments',
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Payment')))]
    )]
    public function index() {}

    #[OA\Post(path: '/api/payments', tags: ['Payments'], summary: 'Record a payment',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['booking_id', 'amount', 'type', 'method'],
            properties: [
                new OA\Property(property: 'booking_id', type: 'string', format: 'uuid'),
                new OA\Property(property: 'invoice_id', type: 'string', format: 'uuid', nullable: true),
                new OA\Property(property: 'amount', type: 'number', format: 'float'),
                new OA\Property(property: 'type', type: 'string', enum: ['deposit', 'refund', 'balance']),
                new OA\Property(property: 'method', type: 'string'),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(ref: '#/components/schemas/Payment')),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(path: '/api/payments/{payment}', tags: ['Payments'], summary: 'Get a single payment',
        parameters: [new OA\Parameter(name: 'payment', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/Payment'))]
    )]
    public function show() {}

    #[OA\Put(path: '/api/payments/{payment}', tags: ['Payments'], summary: 'Update a payment',
        parameters: [new OA\Parameter(name: 'payment', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(properties: [
            new OA\Property(property: 'amount', type: 'number', format: 'float'),
            new OA\Property(property: 'type', type: 'string', enum: ['deposit', 'refund', 'balance']),
        ])),
        responses: [new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(ref: '#/components/schemas/Payment'))]
    )]
    public function update() {}

    #[OA\Delete(path: '/api/payments/{payment}', tags: ['Payments'], summary: 'Delete a payment',
        parameters: [new OA\Parameter(name: 'payment', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 204, description: 'Deleted')]
    )]
    public function destroy() {}
}

/*
|--------------------------------------------------------------------------
| Housekeeping — Api\Staff\HousekeepingController
|--------------------------------------------------------------------------
*/
class HousekeepingDocs
{
    #[OA\Get(path: '/api/housekeeping', tags: ['Housekeeping'], summary: 'List housekeeping tasks, ordered by start time (with room + staff loaded)',
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Housekeeping')))]
    )]
    public function index() {}

    #[OA\Post(
        path: '/api/housekeeping',
        tags: ['Housekeeping'],
        summary: 'Start a housekeeping task (room status becomes "cleaning")',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['room_id', 'staff_id'],
            properties: [
                new OA\Property(property: 'room_id', type: 'string', format: 'uuid'),
                new OA\Property(property: 'staff_id', type: 'string', format: 'uuid'),
                new OA\Property(property: 'started_at', type: 'string', format: 'date-time', nullable: true),
                new OA\Property(property: 'notes', type: 'string', nullable: true),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(ref: '#/components/schemas/Housekeeping')),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(path: '/api/housekeeping/{housekeeping}', tags: ['Housekeeping'], summary: 'Get a single housekeeping task',
        parameters: [new OA\Parameter(name: 'housekeeping', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/Housekeeping'))]
    )]
    public function show() {}

    #[OA\Put(path: '/api/housekeeping/{housekeeping}', tags: ['Housekeeping'], summary: 'Update a housekeeping task',
        parameters: [new OA\Parameter(name: 'housekeeping', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(properties: [new OA\Property(property: 'notes', type: 'string', nullable: true)])),
        responses: [new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(ref: '#/components/schemas/Housekeeping'))]
    )]
    public function update() {}

    #[OA\Delete(path: '/api/housekeeping/{housekeeping}', tags: ['Housekeeping'], summary: 'Delete a housekeeping task',
        parameters: [new OA\Parameter(name: 'housekeeping', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 204, description: 'Deleted')]
    )]
    public function destroy() {}

    #[OA\Patch(
        path: '/api/housekeeping/{housekeeping}/finish',
        tags: ['Housekeeping'],
        summary: 'Finish a housekeeping task (room status becomes "available")',
        parameters: [new OA\Parameter(name: 'housekeeping', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 200, description: 'Finished', content: new OA\JsonContent(ref: '#/components/schemas/Housekeeping'))]
    )]
    public function finish() {}
}

/*
|--------------------------------------------------------------------------
| Maintenance Reports — Api\Staff\MaintenanceReportController
|--------------------------------------------------------------------------
*/
class MaintenanceReportDocs
{
    #[OA\Get(path: '/api/maintenance-reports', tags: ['Maintenance Reports'], summary: 'List maintenance reports (with room, reportedBy, resolvedBy loaded)',
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/MaintenanceReport')))]
    )]
    public function index() {}

    #[OA\Post(
        path: '/api/maintenance-reports',
        tags: ['Maintenance Reports'],
        summary: 'Report an issue (room status becomes "maintenance")',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['room_id', 'reported_by', 'issue_description', 'severity'],
            properties: [
                new OA\Property(property: 'room_id', type: 'string', format: 'uuid'),
                new OA\Property(property: 'reported_by', type: 'string', format: 'uuid'),
                new OA\Property(property: 'issue_description', type: 'string'),
                new OA\Property(property: 'severity', type: 'string'),
                new OA\Property(property: 'status', type: 'string', enum: ['open', 'resolved'], nullable: true),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(ref: '#/components/schemas/MaintenanceReport')),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(path: '/api/maintenance-reports/{maintenanceReport}', tags: ['Maintenance Reports'], summary: 'Get a single maintenance report',
        parameters: [new OA\Parameter(name: 'maintenanceReport', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/MaintenanceReport'))]
    )]
    public function show() {}

    #[OA\Put(path: '/api/maintenance-reports/{maintenanceReport}', tags: ['Maintenance Reports'], summary: 'Update a maintenance report',
        parameters: [new OA\Parameter(name: 'maintenanceReport', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(properties: [
            new OA\Property(property: 'issue_description', type: 'string'),
            new OA\Property(property: 'severity', type: 'string'),
        ])),
        responses: [new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(ref: '#/components/schemas/MaintenanceReport'))]
    )]
    public function update() {}

    #[OA\Delete(path: '/api/maintenance-reports/{maintenanceReport}', tags: ['Maintenance Reports'], summary: 'Delete a maintenance report',
        parameters: [new OA\Parameter(name: 'maintenanceReport', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 204, description: 'Deleted')]
    )]
    public function destroy() {}

    #[OA\Patch(
        path: '/api/maintenance-reports/{maintenanceReport}/resolve',
        tags: ['Maintenance Reports'],
        summary: 'Resolve a maintenance report (room status becomes "available")',
        parameters: [new OA\Parameter(name: 'maintenanceReport', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: false, content: new OA\JsonContent(properties: [
            new OA\Property(property: 'resolved_by', type: 'string', format: 'uuid', nullable: true),
        ])),
        responses: [new OA\Response(response: 200, description: 'Resolved', content: new OA\JsonContent(ref: '#/components/schemas/MaintenanceReport'))]
    )]
    public function resolve() {}
}

/*
|--------------------------------------------------------------------------
| Lost & Found — Api\Staff\LostFoundItemController
|--------------------------------------------------------------------------
*/
class LostFoundItemDocs
{
    #[OA\Get(path: '/api/lost-found-items', tags: ['Lost & Found'], summary: 'List lost & found items (with room + foundBy loaded)',
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/LostFoundItem')))]
    )]
    public function index() {}

    #[OA\Post(path: '/api/lost-found-items', tags: ['Lost & Found'], summary: 'Log a found item',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['room_id', 'found_by', 'item_description'],
            properties: [
                new OA\Property(property: 'room_id', type: 'string', format: 'uuid'),
                new OA\Property(property: 'found_by', type: 'string', format: 'uuid'),
                new OA\Property(property: 'item_description', type: 'string'),
                new OA\Property(property: 'storage_location', type: 'string', nullable: true),
                new OA\Property(property: 'status', type: 'string', nullable: true),
                new OA\Property(property: 'found_at', type: 'string', format: 'date-time', nullable: true),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(ref: '#/components/schemas/LostFoundItem')),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(path: '/api/lost-found-items/{lostFoundItem}', tags: ['Lost & Found'], summary: 'Get a single lost & found item',
        parameters: [new OA\Parameter(name: 'lostFoundItem', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/LostFoundItem'))]
    )]
    public function show() {}

    #[OA\Put(path: '/api/lost-found-items/{lostFoundItem}', tags: ['Lost & Found'], summary: 'Update a lost & found item (e.g. mark returned)',
        parameters: [new OA\Parameter(name: 'lostFoundItem', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(properties: [
            new OA\Property(property: 'status', type: 'string'),
            new OA\Property(property: 'returned_at', type: 'string', format: 'date-time', nullable: true),
        ])),
        responses: [new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(ref: '#/components/schemas/LostFoundItem'))]
    )]
    public function update() {}

    #[OA\Delete(path: '/api/lost-found-items/{lostFoundItem}', tags: ['Lost & Found'], summary: 'Delete a lost & found item',
        parameters: [new OA\Parameter(name: 'lostFoundItem', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 204, description: 'Deleted')]
    )]
    public function destroy() {}
}

/*
|--------------------------------------------------------------------------
| Staff Schedules — Api\Staff\StaffScheduleController
|--------------------------------------------------------------------------
*/
class StaffScheduleDocs
{
    #[OA\Get(path: '/api/staff-schedules', tags: ['Staff Schedules'], summary: 'List all staff schedules (with user loaded)',
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/StaffSchedule')))]
    )]
    public function index() {}

    #[OA\Post(path: '/api/staff-schedules', tags: ['Staff Schedules'], summary: 'Create a staff shift',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['user_id', 'shift_date', 'shift_start', 'shift_end'],
            properties: [
                new OA\Property(property: 'user_id', type: 'string', format: 'uuid'),
                new OA\Property(property: 'shift_date', type: 'string', format: 'date'),
                new OA\Property(property: 'shift_start', type: 'string', format: 'date-time'),
                new OA\Property(property: 'shift_end', type: 'string', format: 'date-time'),
                new OA\Property(property: 'status', type: 'string', nullable: true),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(ref: '#/components/schemas/StaffSchedule')),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(path: '/api/staff-schedules/{staffSchedule}', tags: ['Staff Schedules'], summary: 'Get a single staff schedule',
        parameters: [new OA\Parameter(name: 'staffSchedule', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/StaffSchedule'))]
    )]
    public function show() {}

    #[OA\Put(path: '/api/staff-schedules/{staffSchedule}', tags: ['Staff Schedules'], summary: 'Update a staff schedule',
        parameters: [new OA\Parameter(name: 'staffSchedule', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(properties: [
            new OA\Property(property: 'shift_start', type: 'string', format: 'date-time'),
            new OA\Property(property: 'shift_end', type: 'string', format: 'date-time'),
            new OA\Property(property: 'status', type: 'string'),
        ])),
        responses: [new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(ref: '#/components/schemas/StaffSchedule'))]
    )]
    public function update() {}

    #[OA\Delete(path: '/api/staff-schedules/{staffSchedule}', tags: ['Staff Schedules'], summary: 'Delete a staff schedule',
        parameters: [new OA\Parameter(name: 'staffSchedule', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 204, description: 'Deleted')]
    )]
    public function destroy() {}
}

/*
|--------------------------------------------------------------------------
| Reports — Api\ReportController
|--------------------------------------------------------------------------
*/
class ReportDocs
{
    #[OA\Get(
        path: '/api/reports/rooms/{room}/bookings',
        tags: ['Reports'],
        summary: 'Report 1: bookings of a room within a date period',
        parameters: [
            new OA\Parameter(name: 'room', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
            new OA\Parameter(name: 'from', in: 'query', required: true, schema: new OA\Schema(type: 'string', format: 'date')),
            new OA\Parameter(name: 'to', in: 'query', required: true, schema: new OA\Schema(type: 'string', format: 'date')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'room', type: 'object'),
                    new OA\Property(property: 'period', type: 'object'),
                    new OA\Property(property: 'bookings_count', type: 'integer'),
                    new OA\Property(property: 'bookings', type: 'array', items: new OA\Items(type: 'object')),
                ]
            )),
            new OA\Response(response: 422, description: 'Validation error (missing/invalid from-to)', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function roomBookingReport() {}

    #[OA\Get(
        path: '/api/reports/customers/{user}/invoices',
        tags: ['Reports'],
        summary: 'Report 2: all invoices of a customer, plus their booking count',
        parameters: [new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))],
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'customer', type: 'object'),
                new OA\Property(property: 'bookings_count', type: 'integer'),
                new OA\Property(property: 'invoices_count', type: 'integer'),
                new OA\Property(property: 'invoices', type: 'array', items: new OA\Items(type: 'object')),
            ]
        ))]
    )]
    public function customerInvoices() {}

    #[OA\Get(
        path: '/api/reports/most-requested-rooms',
        tags: ['Reports'],
        summary: 'Report 3: rooms ordered by number of bookings, most requested first',
        responses: [new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
            properties: [new OA\Property(property: 'rooms', type: 'array', items: new OA\Items(type: 'object'))]
        ))]
    )]
    public function mostRequestedRooms() {}
}
