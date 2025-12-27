<div class="search-container">
    <form wire:submit.prevent="submitSearch" class="search-box">
        <input type="text" wire:model.live.debounce.300ms="query" placeholder="Search for products..." autocomplete="off">
        <button type="submit">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </button>
    </form>
    
    @if($showSuggestions && count($suggestions) > 0)
    <div class="search-results">
        @foreach($suggestions as $item)
        <a href="{{ route('ecommerce.product', $item['id']) }}" class="result-item">
            <div class="result-img">
                @if($item['image'])<img src="{{ $item['image'] }}" alt="">@endif
            </div>
            <div class="result-info">
                <span class="result-name">{{ $item['name'] }}</span>
                @if($item['category'])<span class="result-cat">in {{ $item['category'] }}</span>@endif
            </div>
        </a>
        @endforeach
        <button type="button" wire:click="submitSearch" class="see-all-btn">See all results for "{{ $query }}"</button>
    </div>
    @endif
</div>

<style>
.search-container { position: relative; flex: 1; max-width: 520px; }
.search-results { position: absolute; top: calc(100% + 6px); left: 0; right: 0; background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,.12); z-index: 100; overflow: hidden; }
.result-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-bottom: 1px solid #f1f5f9; transition: background .15s; }
.result-item:hover { background: #f8fafc; }
.result-img { width: 44px; height: 44px; background: #f8fafc; border-radius: 8px; overflow: hidden; flex-shrink: 0; }
.result-img img { width: 100%; height: 100%; object-fit: contain; }
.result-info { flex: 1; min-width: 0; }
.result-name { display: block; font-size: 14px; color: #1e293b; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.result-cat { font-size: 12px; color: #64748b; }
.see-all-btn { display: block; width: 100%; padding: 14px; background: #f8fafc; border: none; font-size: 14px; color: #0891b2; font-weight: 500; cursor: pointer; text-align: left; }
.see-all-btn:hover { background: #f1f5f9; }
</style>
