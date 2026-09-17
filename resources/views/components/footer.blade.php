<footer class="footer">
    <div class="container footer-grid">

        <div>
            <h3>Hotel Management</h3>
            <p>
                Experience comfort, elegance and exceptional hospitality.
            </p>
        </div>

        <div>
            <h4>Quick Links</h4>
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('rooms.index') }}">Rooms</a>
        </div>

        <div>
            <h4>Contact</h4>
            <p>Email: info@hotel.com</p>
            <p>Phone: +44 000 000 0000</p>
        </div>

    </div>

    <div class="footer-bottom">
        © {{ date('Y') }} Hotel Management. All rights reserved.
    </div>
</footer>
