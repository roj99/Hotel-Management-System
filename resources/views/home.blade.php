@extends('layouts.frontend')

@section('content')

{{-- ================= HERO ================= --}}

<section class="hero"><div class="hero-content">

    <p>
        WELCOME TO OUR HOTEL
    </p>

    <h1>
        Experience Comfort<br>
        & Luxury
    </h1>

    <p>
        Discover exceptional hospitality, elegant rooms
        and an unforgettable stay.
    </p>

    <a href="{{ route('rooms.index') }}" class="btn">
        Explore Our Rooms
    </a>

</div>

</section>{{-- ================= ROOMS ================= --}}

<section class="section"><div class="section-title">

    <h2>
        Discover Our Rooms
    </h2>

    <p>
        Choose the perfect accommodation for your stay.
    </p>

</div>


<div class="rooms-grid">

    @forelse($roomTypes as $roomType)

        <div class="room-card">

            @if($roomType->images->first())

                <img
                    class="room-image"
                    src="{{ asset('storage/' . $roomType->images->first()->image_path) }}"
                    alt="{{ $roomType->name }}"
                >

            @else

                <div class="room-image"
                     style="display:flex;align-items:center;justify-content:center;background:#eee;">
                    No Image Available
                </div>

            @endif


            <div class="room-content">

                <h3>
                    {{ $roomType->name }}
                </h3>

                <p>
                    {{ Str::limit($roomType->description, 110) }}
                </p>


                <div class="room-info">

                    <span>
                        👤 {{ $roomType->capacity }} Guests
                    </span>

                    <span>
                        {{ $roomType->rooms_count }} Rooms
                    </span>

                </div>


                <div class="room-price">

                    @if($roomType->prices->first())

                        ${{ number_format($roomType->prices->first()->price_per_night, 2) }}
                        / night

                    @else

                        Price unavailable

                    @endif

                </div>


                <br>

                <a
                    href="{{ route('rooms.show', $roomType) }}"
                    class="btn"
                >
                    View Details
                </a>

            </div>

        </div>

    @empty

        <p style="text-align:center;">
            No rooms available at the moment.
        </p>

    @endforelse

</div>


<div style="text-align:center;margin-top:40px;">

    <a href="{{ route('rooms.index') }}" class="btn">
        View All Rooms
    </a>

</div>

</section>{{-- ================= ABOUT ================= --}}

<section class="section about"><div class="container about-grid">

    <div>

        <img
            class="about-image"
            src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1200&q=80"
            alt="Our Hotel"
        >

    </div>


    <div>

        <h2>
            A Stay Designed Around You
        </h2>

        <p>
            Welcome to our hotel, where comfort, elegance and
            exceptional hospitality come together to create
            an unforgettable experience.
        </p>

        <br>

        <p>
            Whether you are travelling for business or leisure,
            our carefully designed rooms and hotel facilities
            provide everything you need for a relaxing stay.
        </p>

        <br>

        <a href="{{ route('rooms.index') }}" class="btn">
            Explore Our Rooms
        </a>

    </div>

</div>

</section>{{-- ================= AMENITIES ================= --}}

<section class="section"><div class="section-title">

    <h2>
        Hotel Amenities
    </h2>

    <p>
        Everything you need for a comfortable stay.
    </p>

</div>


@php

    $amenities = [

        [
            'icon' => '⌁',
            'name' => 'Free Wi-Fi',
            'text' => 'Stay connected throughout your stay.'
        ],

        [
            'icon' => '♨',
            'name' => 'Swimming Pool',
            'text' => 'Relax and enjoy our swimming facilities.'
        ],

        [
            'icon' => '✦',
            'name' => 'Restaurant',
            'text' => 'Enjoy delicious meals and beverages.'
        ],

        [
            'icon' => '◷',
            'name' => '24/7 Service',
            'text' => 'Our team is available whenever you need us.'
        ],

    ];

@endphp


<div class="container amenities-grid">

    @foreach($amenities as $amenity)

        <div class="amenity">

            <div style="font-size:30px;margin-bottom:15px;">
                {{ $amenity['icon'] }}
            </div>

            <h3>
                {{ $amenity['name'] }}
            </h3>

            <p>
                {{ $amenity['text'] }}
            </p>

        </div>

    @endforeach

</div>

</section>{{-- ================= CTA ================= --}}

<section class="cta"><h2>
    Ready for Your Next Stay?
</h2>

<p>
    Find the perfect room and make your reservation today.
</p>

<br>

<a href="{{ route('rooms.index') }}" class="btn">
    Book Your Stay
</a>

</section>@endsection
