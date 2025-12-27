@extends('ecommerce::public.shop-layout')

@section('title', 'Shopping Cart - ' . ($settings->site_name ?? 'Store'))

@section('content')
    @livewire('ecommerce::cart')
@endsection
