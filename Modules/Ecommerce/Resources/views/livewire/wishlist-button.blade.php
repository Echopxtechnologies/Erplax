<button wire:click="toggle" wire:loading.attr="disabled" class="wl-btn {{ $isInWishlist ? 'active' : '' }}" type="button" title="{{ $isInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
    <span wire:loading.remove wire:target="toggle">
        <svg fill="{{ $isInWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18" height="18">
            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
        </svg>
    </span>
    <span wire:loading wire:target="toggle" class="wl-loading"></span>
</button>

<style>
.wl-btn{width:40px;height:40px;background:#fff;border:1px solid #e2e8f0;border-radius:50%;color:#94a3b8;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s}
.wl-btn:hover{border-color:#fca5a5;color:#ef4444}
.wl-btn.active{background:#fef2f2;border-color:#ef4444;color:#ef4444}
.wl-btn:disabled{opacity:.7;cursor:wait}
.wl-loading{width:14px;height:14px;border:2px solid #e2e8f0;border-top-color:#ef4444;border-radius:50%;animation:wlspin .6s linear infinite}
@keyframes wlspin{to{transform:rotate(360deg)}}
</style>
