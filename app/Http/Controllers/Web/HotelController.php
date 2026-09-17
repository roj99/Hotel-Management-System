<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Hotel\Room;
use App\Models\Hotel\RoomsType;

class HotelController extends Controller
{
    public function home()
    {
        $roomTypes = RoomsType::with([
            'images',
            'amenities',
            'prices',
        ])
        ->withCount('rooms')
        ->take(3)
        ->get();

        return view('home', compact('roomTypes'));
    }

    public function rooms()
    {
        $roomTypes = RoomsType::with([
            'images',
            'amenities',
            'prices',
        ])
        ->withCount('rooms')
        ->get();

        return view('rooms.index', compact('roomTypes'));
    }

    public function show(RoomsType $roomsType)
    {
        $roomsType->load([
            'images',
            'amenities',
            'prices',
            'rooms',
        ]);

        return view('rooms.show', compact('roomsType'));
    }

    public function bookForm(RoomsType $roomsType)
    {
        $roomsType->load([
            'prices',
            'rooms',
        ]);

        return view('rooms.book', compact('roomsType'));
    }
}
