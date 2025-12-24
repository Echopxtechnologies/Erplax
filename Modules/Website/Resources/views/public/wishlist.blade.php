@extends('website::public.shop-layout')

@section('title', 'My Wishlist - ' . ($settings->site_name ?? 'Store'))

@section('content')
    @livewire('website::wishlist')
@endsection
