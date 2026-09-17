@extends('layouts.frontend')

@section('content')

<section class="page-header">

    <div>

        <p>OUR ACCOMMODATION</p>

        <h1>
            Explore Our Rooms
        </h1>

        <span></span>

        <p>
            Find the perfect room for your stay.
        </p>

    </div>

</section>


<section class="rooms-page">

    <div class="rooms-grid">

        @forelse($roomTypes as $roomType)

            <div class="room-card">

                <div class="room-image">

                    @if($roomType->images->first())

                        <img
                            src="{{ asset('storage/' . $roomType->images->first()->image_path) }}"
                            alt="{{ $roomType->name }}"
                        >

                    @else

                        <div class="room-placeholder">
                            No Image
                        </div>

                    @endif

                </div>


                <div class="room-content">

                    <p class="room-type">
                        ROOM
                    </p>

                    <h3>
                        {{ $roomType->name }}
                    </h3>

                    <p>
                        {{ Str::limit($roomType->description, 120) }}
                    </p>


                    <div class="room-info">

                        <span>
                            👤 {{ $roomType->capacity }} Guests
                        </span>

                        <span>
                            {{ $roomType->rooms_count }} Available Units
                        </span>

                    </div>


                    <div class="room-bottom">

                        <span class="room-price">

                            @if($roomType->prices->first())

                                From
                                <strong>
                                    ${{ number_format($roomType->prices->first()->price_per_night, 2) }}
                                </strong>

                            @endif

                        </span>


                        <a href="{{ route('rooms.show', $roomType) }}">
                            View Details →
                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="empty-rooms">
                <h3>No rooms available.</h3>
            </div>

        @endforelse

    </div>

</section>

@endsection
