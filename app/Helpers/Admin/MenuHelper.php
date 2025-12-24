<?php

if (!function_exists('admin_menu')) {
    /**
     * Render admin sidebar menu item
     *
     * @param array $menu Menu configuration
     * @return string
     */
    function admin_menu(array $menu): string
    {
        return \App\Livewire\Admin\AdminComponent::renderMenu($menu);
    }
}