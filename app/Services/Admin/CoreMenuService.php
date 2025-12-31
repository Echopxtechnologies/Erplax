<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class CoreMenuService
{
    /**
     * Cache duration in seconds (1 hour)
     */
    const CACHE_DURATION = 3600;

    /**
     * SVG Icons Library
     */
    protected static array $icons = [
        'dashboard' => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>',
        'users' => '<path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>',
        'user' => '<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>',
        'cube' => '<path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>',
        'folder' => '<path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>',
        'settings' => '<path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>',
        'chart-bar' => '<path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>',
        'document' => '<path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>',
        'mail' => '<path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>',
        'clock' => '<path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
        'globe' => '<path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
        'currency' => '<path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
        'shield' => '<path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>',
        'server' => '<path d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>',
        'key' => '<path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>',
        'flag' => '<path d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>',
        'receipt' => '<path d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>',
        'credit-card' => '<path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>',
        'building' => '<path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>',
        'archive' => '<path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>',
        'arrow-down' => '<path d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>',
        'arrow-up' => '<path d="M5 10l7-7m0 0l7 7m-7-7v18"></path>',
        'arrows-right-left' => '<path d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-9L21 12m0 0l-4.5 4.5M21 12H7.5"></path>',
        'adjustments' => '<path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>',
        'building-warehouse' => '<path d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>',
        'ruler' => '<path d="M14 5l7 7m0 0l-7 7m7-7H3"></path>',
        'document-chart-bar' => '<path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>',
        'arrows-pointing-out' => '<path d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>',
        'exclamation-triangle' => '<path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>',
        'shopping-cart' => '<path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>',
        'truck' => '<path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>',
        'clipboard-list' => '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>',
        'calculator' => '<path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>',
        'banknotes' => '<path d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"></path>',
        'presentation-chart-line' => '<path d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605"></path>',
        'cog' => '<path d="M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.41-.513m14.095-5.13l1.41-.513M5.106 17.785l1.15-.964m11.49-9.642l1.149-.964M7.501 19.795l.75-1.3m7.5-12.99l.75-1.3m-6.063 16.658l.26-1.477m2.605-14.772l.26-1.477m0 17.726l-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205L12 12m6.894 5.785l-1.149-.964M6.256 7.178l-1.15-.964m15.352 8.864l-1.41-.513M4.954 9.435l-1.41-.514M12.002 12l-3.75 6.495"></path>',
        'puzzle' => '<path d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path>',
        'activity' => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>',
        'chevron-right' => '<path d="M9 5l7 7-7 7"></path>',
        'chevron-down' => '<path d="M19 9l-7 7-7-7"></path>',
        'plus' => '<path d="M12 4v16m8-8H4"></path>',
        'search' => '<path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>',
        'bell' => '<path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>',
        'home' => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>',
        'tag' => '<path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>',
        'briefcase' => '<path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>',
        'calendar' => '<path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>',
        'check-circle' => '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
        'x-circle' => '<path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
        'information-circle' => '<path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
        'question-mark-circle' => '<path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
        'eye' => '<path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>',
        'eye-off' => '<path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>',
        'pencil' => '<path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>',
        'trash' => '<path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>',
        'download' => '<path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>',
        'upload' => '<path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>',
        'refresh' => '<path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>',
        'filter' => '<path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>',
        'sort-asc' => '<path d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>',
        'sort-desc' => '<path d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>',
        'dots-vertical' => '<path d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>',
        'menu' => '<path d="M4 6h16M4 12h16M4 18h16"></path>',
        'x' => '<path d="M6 18L18 6M6 6l12 12"></path>',
        'link' => '<path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>',
        'photograph' => '<path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>',
        'paper-clip' => '<path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>',
        'printer' => '<path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>',
        'qrcode' => '<path d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h2M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>',
        'share' => '<path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>',
        'database' => '<path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>',
        'code' => '<path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>',
        'terminal' => '<path d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>',
        'chip' => '<path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>',
        'sparkles' => '<path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>',
        'lightning-bolt' => '<path d="M13 10V3L4 14h7v7l9-11h-7z"></path>',
        'fire' => '<path d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>',
        'heart' => '<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>',
        'star' => '<path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>',
        'bookmark' => '<path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>',
        'chat' => '<path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>',
        'annotation' => '<path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>',
        'support' => '<path d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>',
        'phone' => '<path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>',
        'location-marker' => '<path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>',
        'office-building' => '<path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>',
        'collection' => '<path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>',
        'template' => '<path d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>',
        'view-grid' => '<path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>',
        'view-list' => '<path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>',
        'table' => '<path d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>',
        'clipboard' => '<path d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>',
        'duplicate' => '<path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>',
        'document-text' => '<path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>',
        'document-report' => '<path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>',
        'folder-open' => '<path d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path>',
        'archive-box' => '<path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>',
        'inbox' => '<path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>',
        'cash' => '<path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>',
        'trending-up' => '<path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>',
        'trending-down' => '<path d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>',
        'chart-pie' => '<path d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>',
        'scale' => '<path d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>',
        'library' => '<path d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>',
        'academic-cap' => '<path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>',
        'beaker' => '<path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>',
        'globe-alt' => '<path d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>',
        'light-bulb' => '<path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>',
        'hand' => '<path d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"></path>',
    ];

    /*
    |--------------------------------------------------------------------------
    | Get All Module Services
    |--------------------------------------------------------------------------
    */

    /**
     * Discover and load all module service files
     */
    protected static function getModuleServices(): array
    {
        return Cache::remember('module_services', self::CACHE_DURATION, function () {
            $services = [];
            $servicesPath = app_path('Services/Admin');

            if (!File::isDirectory($servicesPath)) {
                return $services;
            }

            foreach (File::files($servicesPath) as $file) {
                $fileName = $file->getFilenameWithoutExtension();

                // Skip CoreMenuService itself
                if ($fileName === 'CoreMenuService') {
                    continue;
                }

                // Only load *Service files
                if (!str_ends_with($fileName, 'Service')) {
                    continue;
                }

                $className = "App\\Services\\Admin\\{$fileName}";

                if (!class_exists($className)) {
                    continue;
                }

                // Must have config() and menus() methods
                if (!method_exists($className, 'config') || !method_exists($className, 'menus')) {
                    continue;
                }

                $services[] = $className;
            }
            // 2. nWidart Module Services (Modules/*/Services/*Service.php)
        $modulesPath = base_path('Modules');
        if (File::isDirectory($modulesPath)) {
            foreach (File::directories($modulesPath) as $moduleDir) {
                $servicesPath = $moduleDir . '/Services';
                if (!File::isDirectory($servicesPath)) continue;

                foreach (File::files($servicesPath) as $file) {
                    $fileName = $file->getFilenameWithoutExtension();
                    if (!str_ends_with($fileName, 'Service')) continue;

                    $moduleName = basename($moduleDir);
                    $className = "Modules\\{$moduleName}\\Services\\{$fileName}";

                    if (class_exists($className) && method_exists($className, 'config') && method_exists($className, 'menus')) {
                        $services[] = $className;
                    }
                }
            }
        }

            return $services;
        });
    }

    /**
     * Get all menus grouped by category
     */
    public static function getAllMenus(): array
    {
        return Cache::remember('core_menus_all', self::CACHE_DURATION, function () {
            $menusByCategory = [
                'core' => [],
                'system' => [],
                'settings' => [],
            ];

            foreach (self::getModuleServices() as $serviceClass) {
                $config = $serviceClass::config();
                $menus = $serviceClass::menus();
                $moduleAlias = $config['alias'];

                foreach ($menus as $menu) {
                    $category = $menu['category'] ?? 'core';

                    // Ensure category exists
                    if (!isset($menusByCategory[$category])) {
                        $menusByCategory[$category] = [];
                    }

                    // Add module info to menu
                    $menu['module_alias'] = $moduleAlias;
                    $menu['module_name'] = $config['name'];

                    $menusByCategory[$category][] = $menu;
                }
            }

            // Sort each category by sort_order
            foreach ($menusByCategory as &$menus) {
                usort($menus, fn($a, $b) => ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0));
            }

            return $menusByCategory;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Permission Checking
    |--------------------------------------------------------------------------
    */

    /**
     * Check if current user can access a menu
     */
    protected static function canAccess(?string $permission): bool
    {
        if (empty($permission)) {
            return true;
        }

        $user = Auth::guard('admin')->user();

        if (!$user) {
            return false;
        }

        // Super admin can access everything
        if ($user->hasRole('super-admin')) {
            return true;
        }

        return $user->hasPermissionTo($permission, 'admin');
    }

    /**
     * Filter menus by permission
     */
    protected static function filterAccessibleMenus(array $menus): array
    {
        $accessible = [];

        foreach ($menus as $menu) {
            // Build permission name: module.slug.read
            $permission = $menu['permission'] ?? "{$menu['module_alias']}.{$menu['slug']}.read";

            if (!self::canAccess($permission)) {
                continue;
            }

            // Filter children too
            if (!empty($menu['children'])) {
                $menu['children'] = self::filterAccessibleChildren($menu['children'], $menu['module_alias']);

                // Skip parent if no accessible children and no route
                if (empty($menu['children']) && empty($menu['route'])) {
                    continue;
                }
            }

            $accessible[] = $menu;
        }

        return $accessible;
    }

    /**
     * Filter child menus by permission
     */
    protected static function filterAccessibleChildren(array $children, string $moduleAlias): array
    {
        $accessible = [];

        foreach ($children as $child) {
            $permission = $child['permission'] ?? "{$moduleAlias}.{$child['slug']}.read";

            if (!self::canAccess($permission)) {
                continue;
            }

            // Recursive for nested children
            if (!empty($child['children'])) {
                $child['children'] = self::filterAccessibleChildren($child['children'], $moduleAlias);
            }

            $accessible[] = $child;
        }

        return $accessible;
    }

    /*
    |--------------------------------------------------------------------------
    | Render Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Render Core Menu Section (category: core)
     */
    public static function renderCoreMenu(): string
    {
        $allMenus = self::getAllMenus();
        $menus = self::filterAccessibleMenus($allMenus['core'] ?? []);

        if (empty($menus)) {
            return '';
        }

        $html = '';
        foreach ($menus as $menu) {
            $html .= self::renderMenuItem($menu);
        }

        return $html;
    }

    /**
     * Render System Menu Section (category: system)
     */
    public static function renderSystemMenu(): string
    {
        $allMenus = self::getAllMenus();
        $menus = self::filterAccessibleMenus($allMenus['system'] ?? []);

        $html = '';

        // Add Modules link
        // $html .= self::renderSimpleMenuItem('Modules', 'puzzle', 'admin.modules.index');

        // Add Activity Logs
        $html .= self::renderSimpleMenuItem('Activity Logs', 'activity', 'admin.activity-logs.index');

        // Render system menus from services
        foreach ($menus as $menu) {
            $html .= self::renderMenuItem($menu);
        }

        // Settings Panel Trigger
        $html .= '<a href="javascript:void(0)" class="nav-item" onclick="toggleSettingsPanel()">';
        $html .= self::getIconSvg('settings');
        $html .= '<span>Settings</span>';
        $html .= '</a>';

        return $html;
    }

    /**
     * Render Settings Panel (slide-out panel)
     */
    public static function renderSettingsPanel(): string
    {
        $allMenus = self::getAllMenus();
        $menus = self::filterAccessibleMenus($allMenus['settings'] ?? []);

        if (empty($menus)) {
            return '<p class="text-muted p-3">No settings available</p>';
        }

        $html = '';
        foreach ($menus as $menu) {
            $html .= self::renderSettingsPanelItem($menu);
        }

        return $html;
    }

    /**
     * Render a single menu item (with children support)
     */
    protected static function renderMenuItem(array $menu): string
    {
        $hasChildren = !empty($menu['children']);
        $icon = $menu['icon'] ?? 'folder';
        $title = $menu['menu_name'] ?? $menu['title'] ?? 'Menu';
        $route = $menu['route'] ?? null;
        $isActive = $route ? request()->routeIs($route) : false;

        // Check if any child is active
        if ($hasChildren) {
            foreach ($menu['children'] as $child) {
                if (!empty($child['route']) && request()->routeIs($child['route'])) {
                    $isActive = true;
                    break;
                }
            }
        }

        $html = '';

        if ($hasChildren) {
            // Parent with dropdown
            $openClass = $isActive ? 'open' : '';
            $html .= "<a href=\"javascript:void(0)\" class=\"nav-item {$openClass}\" onclick=\"toggleSubmenu(this)\">";
            $html .= self::getIconSvg($icon);
            $html .= "<span>{$title}</span>";
            $html .= '<svg class="chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>';
            $html .= '</a>';

            // Submenu
            $submenuClass = $isActive ? 'open' : '';
            $html .= "<div class=\"nav-submenu {$submenuClass}\">";

            foreach ($menu['children'] as $child) {
                $html .= self::renderChildMenuItem($child, $menu['module_alias'] ?? '');
            }

            $html .= '</div>';
        } else {
            // Simple menu item
            $url = $route && \Route::has($route) ? route($route) : '#';
            $activeClass = $isActive ? 'active' : '';

            $html .= "<a href=\"{$url}\" class=\"nav-item {$activeClass}\">";
            $html .= self::getIconSvg($icon);
            $html .= "<span>{$title}</span>";
            $html .= '</a>';
        }

        return $html;
    }

    /**
     * Render child menu item (supports nested children)
     */
    protected static function renderChildMenuItem(array $child, string $moduleAlias): string
    {
        $hasNestedChildren = !empty($child['children']);
        $icon = $child['icon'] ?? 'folder';
        $title = $child['menu_name'] ?? $child['title'] ?? 'Menu';
        $route = $child['route'] ?? null;
        $isActive = $route ? request()->routeIs($route) : false;

        // Check nested children for active state
        if ($hasNestedChildren) {
            foreach ($child['children'] as $nested) {
                if (!empty($nested['route']) && request()->routeIs($nested['route'])) {
                    $isActive = true;
                    break;
                }
            }
        }

        $html = '';

        if ($hasNestedChildren) {
            // Has nested submenu
            $expandedClass = $isActive ? 'expanded' : '';
            $html .= "<a href=\"javascript:void(0)\" class=\"nav-item has-nested {$expandedClass}\" onclick=\"toggleNestedSubmenu(event, this)\">";
            $html .= self::getIconSvg($icon);
            $html .= "<span>{$title}</span>";
            $html .= '<svg class="chevron-nested" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>';
            $html .= '</a>';

            // Nested submenu
            $nestedOpenClass = $isActive ? 'open' : '';
            $html .= "<div class=\"nav-nested-submenu {$nestedOpenClass}\">";

            foreach ($child['children'] as $nested) {
                $nestedRoute = $nested['route'] ?? null;
                $nestedUrl = $nestedRoute && \Route::has($nestedRoute) ? route($nestedRoute) : '#';
                $nestedActive = $nestedRoute && request()->routeIs($nestedRoute) ? 'active' : '';
                $nestedTitle = $nested['menu_name'] ?? $nested['title'] ?? 'Item';
                $nestedIcon = $nested['icon'] ?? 'folder';

                $html .= "<a href=\"{$nestedUrl}\" class=\"nav-item {$nestedActive}\">";
                $html .= self::getIconSvg($nestedIcon);
                $html .= "<span>{$nestedTitle}</span>";
                $html .= '</a>';
            }

            $html .= '</div>';
        } else {
            // Simple child item
            $url = $route && \Route::has($route) ? route($route) : '#';
            $activeClass = $isActive ? 'active' : '';

            $html .= "<a href=\"{$url}\" class=\"nav-item {$activeClass}\">";
            $html .= self::getIconSvg($icon);
            $html .= "<span>{$title}</span>";
            $html .= '</a>';
        }

        return $html;
    }

    /**
     * Render settings panel item
     */
    protected static function renderSettingsPanelItem(array $menu): string
    {
        $hasChildren = !empty($menu['children']);
        $icon = $menu['icon'] ?? 'settings';
        $title = $menu['menu_name'] ?? $menu['title'] ?? 'Setting';
        $route = $menu['route'] ?? null;
        $isActive = $route ? request()->routeIs($route . '*') : false;

        $html = '';

        if ($hasChildren) {
            $openClass = $isActive ? 'open' : '';
            $html .= "<div class=\"setup-nav-item {$openClass}\" onclick=\"toggleSetupSubmenu(this)\">";
            $html .= self::getIconSvg($icon);
            $html .= "<span>{$title}</span>";
            $html .= '<svg class="arrow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>';
            $html .= '</div>';

            $submenuClass = $isActive ? 'open' : '';
            $html .= "<div class=\"setup-submenu {$submenuClass}\">";

            foreach ($menu['children'] as $child) {
                $childRoute = $child['route'] ?? null;
                $childUrl = $childRoute && \Route::has($childRoute) ? route($childRoute) : '#';
                $childActive = $childRoute && request()->routeIs($childRoute) ? 'active' : '';
                $childTitle = $child['menu_name'] ?? $child['title'] ?? 'Item';

                $html .= "<a href=\"{$childUrl}\" class=\"setup-submenu-item {$childActive}\">{$childTitle}</a>";
            }

            $html .= '</div>';
        } else {
            $url = $route && \Route::has($route) ? route($route) : '#';
            $activeClass = $isActive ? 'active' : '';

            $html .= "<a href=\"{$url}\" class=\"setup-nav-item {$activeClass}\">";
            $html .= self::getIconSvg($icon);
            $html .= "<span>{$title}</span>";
            $html .= '</a>';
        }

        return $html;
    }

    /**
     * Render a simple menu item
     */
    protected static function renderSimpleMenuItem(string $title, string $icon, string $routeName): string
    {
        if (!\Route::has($routeName)) {
            return '';
        }

        $url = route($routeName);
        $isActive = request()->routeIs($routeName) ? 'active' : '';

        $html = "<a href=\"{$url}\" class=\"nav-item {$isActive}\">";
        $html .= self::getIconSvg($icon);
        $html .= "<span>{$title}</span>";
        $html .= '</a>';

        return $html;
    }

    /*
    |--------------------------------------------------------------------------
    | Icon Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Get SVG icon by name
     */
    public static function getIcon(string $name): string
    {
        return self::$icons[$name] ?? self::$icons['folder'];
    }

    /**
     * Get full SVG element
     */
    public static function getIconSvg(string $name): string
    {
        $path = self::getIcon($name);
        return '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">' . $path . '</svg>';
    }

    /**
     * Get all available icon names
     */
    public static function getAvailableIcons(): array
    {
        return array_keys(self::$icons);
    }

    /*
    |--------------------------------------------------------------------------
    | Cache Management
    |--------------------------------------------------------------------------
    */

    /**
     * Clear all menu caches
     */
    public static function clearCache(): void
    {
        Cache::forget('module_services');
        Cache::forget('core_menus_all');
    }
}