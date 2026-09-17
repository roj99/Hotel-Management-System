<nav class="navbar">
    <div class="container navbar-inner">

        <a href="{{ route('home') }}" class="logo">Hotel Management</a>

        <div class="nav-links">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('rooms.index') }}">Rooms</a>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('contact.show') }}">Contact</a>
            <a href="{{ route('reviews.index') }}">Reviews</a>

            <span id="guest-links">
                <a href="{{ route('login') }}" class="nav-login">Login</a>
                <a href="{{ route('register') }}" class="nav-register">Register</a>
            </span>

            <span id="auth-links" style="display:none;">
                <a href="{{ route('bookings.mine') }}">My Bookings</a>
                <a href="{{ route('profile') }}">Profile</a>
                <a href="{{ route('room-services') }}">Room Service</a>
                <button type="button" id="logout-btn" class="nav-button">Logout</button>
            </span>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const token = localStorage.getItem('token');
    if (token) {
        document.getElementById('guest-links').style.display = 'none';
        document.getElementById('auth-links').style.display = 'inline';
    }
});

document.getElementById('logout-btn')?.addEventListener('click', async function () {
    const token = localStorage.getItem('token');
    try {
        await fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json',
            },
        });
    } finally {
        localStorage.removeItem('token');
        window.location.href = '/';
    }
});
</script>
