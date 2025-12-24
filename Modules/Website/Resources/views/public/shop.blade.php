@extends('website::public.shop-layout')

@section('title', 'Shop - ' . ($settings->site_name ?? 'Store'))

@section('content')
    @livewire('website::product-grid')
@endsection
