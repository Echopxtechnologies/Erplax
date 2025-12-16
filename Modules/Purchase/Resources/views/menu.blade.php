{!! \App\Livewire\Admin\AdminComponent::renderMenu([
    'title' => 'Purchase',
    'icon' => 'shopping-cart',
    'route' => 'admin.purchase.*',
    'children' => [
        ['title' => 'Vendors', 'route' => 'admin.purchase.vendors.index', 'icon' => 'building'],
        ['title' => 'Purchase Requests', 'route' => 'admin.purchase.requests.index', 'icon' => 'clipboard-list'],
        ['title' => 'Purchase Orders', 'route' => 'admin.purchase.orders.index', 'icon' => 'clipboard-check'],
        ['title' => 'Goods Receipt (GRN)', 'route' => 'admin.purchase.grn.index', 'icon' => 'package'],
        ['title' => 'Vendor Bills', 'route' => 'admin.purchase.bills.index', 'icon' => 'file-text'],
        ['title' => 'Settings', 'route' => 'admin.purchase.settings', 'icon' => 'settings'],
    ]
]) !!}
