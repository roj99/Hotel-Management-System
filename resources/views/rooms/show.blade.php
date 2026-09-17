@extends('layouts.frontend')

@section('content')

<section class="room-details-page">

    <div class="room-details-container">


        {{-- IMAGES --}}

        <div class="room-gallery">

            @if($roomsType->images->count() > 0)

                <img
                    id="gallery-main"
                    class="gallery-main"
                    src="{{ asset('storage/' . $roomsType->images->first()->image_path) }}"
                    alt="{{ $roomsType->name }}"
                >

                @if($roomsType->images->count() > 1)

                    <div class="gallery-thumbs">

                        @foreach($roomsType->images as $index => $image)

                            <img
                                src="{{ asset('storage/' . $image->image_path) }}"
                                alt="{{ $roomsType->name }}"
                                class="gallery-thumb {{ $index === 0 ? 'active' : '' }}"
                                data-src="{{ asset('storage/' . $image->image_path) }}"
                            >

                        @endforeach

                    </div>

                @endif

            @else

                <div class="room-placeholder large">
                    No Images Available
                </div>

            @endif

        </div>


        {{-- DETAILS --}}

        <div class="room-details-content">

            <p class="room-type">
                ROOM TYPE
            </p>

            <h1>
                {{ $roomsType->name }}
            </h1>

            <span class="gold-line"></span>


            <p class="room-description">
                {{ $roomsType->description }}
            </p>


            <div class="room-features">

                <div>
                    <strong>Capacity</strong>
                    <span>
                        {{ $roomsType->capacity }} Guests
                    </span>
                </div>

                <div>
                    <strong>Available Rooms</strong>
                    <span>
                        {{ $roomsType->rooms->count() }}
                    </span>
                </div>

            </div>


            {{-- AMENITIES --}}

            <div class="details-section">

                <h2>
                    Amenities
                </h2>

                <div class="amenities-list">

                    @forelse($roomsType->amenities as $amenity)

                        <span>
                            ✓ {{ $amenity->name }}
                        </span>

                    @empty

                        <span>
                            No amenities listed
                        </span>

                    @endforelse

                </div>

            </div>


            {{-- PRICE --}}

            <div class="details-price">

                @if($roomsType->prices->first())

                    <small>
                        Starting from
                    </small>

                    <strong>
                        ${{ number_format($roomsType->prices->first()->price_per_night, 2) }}
                    </strong>

                    <span>
                        / night
                    </span>

                @endif

            </div>


            {{-- BOOK --}}

            @if($roomsType->rooms->count() > 0)

                <a
                    href="{{ route('rooms.book', $roomsType) }}"
                    class="btn btn-block"
                >
                    Book This Room
                </a>

            @else

                <button
                    class="disabled-btn"
                    disabled
                >
                    Currently Unavailable
                </button>

            @endif

        </div>

    </div>

</section>

<script>
// تبديل الصورة الرئيسية عند الضغط على أي صورة مصغّرة (thumbnail)
document.querySelectorAll('.gallery-thumb').forEach(function (thumb) {
    thumb.addEventListener('click', function () {
        document.getElementById('gallery-main').src = this.dataset.src;

        document.querySelectorAll('.gallery-thumb').forEach(function (t) {
            t.classList.remove('active');
        });

        this.classList.add('active');
    });
});
</script>

@endsection
