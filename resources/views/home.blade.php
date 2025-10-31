@extends('layout.main')

@section('content')
    <h1 class="text-2xl font-normal tracking-wide">
        BEST SELLERS
        Welcome back {{ auth()->user()->email }}!
    </h1>
@endsection
