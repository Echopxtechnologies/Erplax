{{-- Ecommerce Module - Admin Sidebar Menu --}}
{!! admin_menu([
    'title' => 'Ecommerce',
    'icon' => 'shopping-cart',
    'route' => 'admin.ecommerce.*',
    'children' => [
        ['title' => 'Dashboard', 'route' => 'admin.ecommerce.index', 'icon' => 'layout-dashboard'],
        ['title' => 'Orders', 'route' => 'admin.ecommerce.orders', 'icon' => 'package'],
        ['title' => 'Reviews', 'route' => 'admin.ecommerce.reviews', 'icon' => 'star'],
        ['title' => 'Settings', 'route' => 'admin.ecommerce.settings', 'icon' => 'settings'],
    ]
]) !!}
