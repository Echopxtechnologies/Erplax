<div class="atc-wrap" wire:ignore.self>
    @if($style === 'full')
        <div class="atc-row">
            <div class="atc-qty">
                <button wire:click="decrement" type="button">−</button>
                <span>{{ $qty }}</span>
                <button wire:click="increment" type="button">+</button>
            </div>
            <button wire:click="addToCart" wire:loading.attr="disabled" class="atc-btn-full" type="button">
                <span wire:loading.remove wire:target="addToCart">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="20" height="20">
                        <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Add to Cart
                </span>
                <span wire:loading wire:target="addToCart">Adding...</span>
            </button>
        </div>
    @else
        <button wire:click="addToCart" wire:loading.attr="disabled" class="atc-btn" type="button">
            <span wire:loading.remove wire:target="addToCart">Add to Cart</span>
            <span wire:loading wire:target="addToCart">Adding...</span>
        </button>
    @endif
</div>

<style>
.atc-wrap{display:contents}
.atc-row{display:flex;gap:12px;align-items:center;flex-wrap:wrap}
.atc-qty{display:flex;align-items:center;background:#f1f5f9;border-radius:8px;overflow:hidden}
.atc-qty button{width:40px;height:44px;border:none;background:transparent;font-size:18px;color:#64748b;cursor:pointer}
.atc-qty button:hover{background:#e2e8f0;color:#1e293b}
.atc-qty span{width:40px;text-align:center;font-weight:600;color:#1e293b}
.atc-btn-full{flex:1;min-width:160px;padding:14px 24px;background:#3b82f6;color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px}
.atc-btn-full:hover{background:#2563eb}
.atc-btn-full:disabled{opacity:.7;cursor:wait}
.atc-btn{padding:10px 16px;background:#3b82f6;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;width:100%}
.atc-btn:hover{background:#2563eb}
.atc-btn:disabled{opacity:.7;cursor:wait}
</style>
