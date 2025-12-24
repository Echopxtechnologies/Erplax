<?php

if (!function_exists('client_menu')) {
    /**
     * Render client navbar menu item
     *
     * @param array $menu Menu configuration
     * @return string
     */
    function client_menu(array $menu): string
    {
        return \App\Http\Controllers\Client\ClientController::renderMenu($menu);
    }
}

if (!function_exists('client_mobile_menu')) {
    /**
     * Render client mobile menu item
     *
     * @param array $menu Menu configuration
     * @return string
     */
    function client_mobile_menu(array $menu): string
    {
        return \App\Http\Controllers\Client\ClientController::renderMobileMenu($menu);
    }
}