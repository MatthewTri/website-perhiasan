@extends('layout.main')

@section('content')
    <h1 class="text-2xl font-normal tracking-wide">
        BEST SELLERS
        @auth
            <br>Welcome back {{ auth()->user()->email }}!
        @endauth
    </h1>
@endsection
