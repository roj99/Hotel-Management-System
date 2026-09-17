@extends('layouts.frontend')

@section('content')

<section class="page-header">
    <div>
        <p>GET IN TOUCH</p>
        <h1>Contact Us</h1>
        <span></span>
        <p>Have a question? Send us a message and we'll get back to you.</p>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width:560px;">

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('contact.send') }}">
            @csrf

            <label>Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="field">

            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="field">

            <label>Message</label>
            <textarea name="message" rows="6" required
                      class="field">{{ old('message') }}</textarea>

            <button type="submit" class="btn btn-block">
                Send Message
            </button>
        </form>

    </div>
</section>

@endsection
