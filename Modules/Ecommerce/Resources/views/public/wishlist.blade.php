@extends('ecommerce::public.shop-layout')

@section('title', 'My Wishlist - ' . ($settings->site_name ?? 'Store'))

@section('content')
    @livewire('ecommerce::wishlist')
@endsection
