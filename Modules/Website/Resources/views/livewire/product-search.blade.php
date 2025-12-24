<div class="search-wrap">
    <form wire:submit.prevent="submitSearch" class="search-form">
        <input 
            type="text" 
            wire:model.live.debounce.300ms="query"
            class="search-input" 
            placeholder="Search products..."
            autocomplete="off"
        >
        <button type="submit" class="search-btn">
            <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" width="20" height="20">
                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </button>
    </form>
    
    @if($showSuggestions && (count($suggestions) > 0 || count($searchTerms) > 0))
    <div class="search-dropdown">
        @if(count($suggestions) > 0)
            <div class="dropdown-section">
                <div class="section-title">Products</div>
                @foreach($suggestions as $item)
                    <a href="{{ route('website.product', $item['id']) }}" class="suggestion-item">
                        <div class="sug-img">
                            @if($item['image'])
                                <img src="{{ $item['image'] }}" alt="">
                            @endif
                        </div>
                        <span class="sug-name">{!! $this->highlightMatch($item['name'], $query) !!}</span>
                    </a>
                @endforeach
            </div>
        @endif
        
        @if(count($searchTerms) > 0)
            <div class="dropdown-section">
                <div class="section-title">Suggestions</div>
                @foreach($searchTerms as $term)
                    <button type="button" wire:click="searchTerm('{{ $term }}')" class="term-item">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="16" height="16"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        {{ $term }}
                    </button>
                @endforeach
            </div>
        @endif
    </div>
    @endif
</div>

<style>
.search-wrap{flex:1;max-width:500px;position:relative}
.search-form{display:flex;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;transition:all .15s}
.search-form:focus-within{border-color:#3b82f6;background:#fff;box-shadow:0 0 0 3px rgba(59,130,246,.1)}
.search-input{flex:1;padding:12px 16px;border:none;background:transparent;font-size:14px;outline:none}
.search-input::placeholder{color:#94a3b8}
.search-btn{padding:0 16px;background:#3b82f6;border:none;color:#fff;cursor:pointer}
.search-btn:hover{background:#2563eb}
.search-dropdown{position:absolute;top:100%;left:0;right:0;margin-top:8px;background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,.12);z-index:50;overflow:hidden}
.dropdown-section{padding:8px 0}
.dropdown-section:not(:last-child){border-bottom:1px solid #f1f5f9}
.section-title{padding:8px 16px;font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px}
.suggestion-item{display:flex;align-items:center;gap:12px;padding:10px 16px;transition:background .1s}
.suggestion-item:hover{background:#f8fafc}
.sug-img{width:36px;height:36px;background:#f1f5f9;border-radius:6px;overflow:hidden;flex-shrink:0}
.sug-img img{width:100%;height:100%;object-fit:contain}
.sug-name{font-size:13px;color:#334155;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.sug-name strong{color:#1e293b}
.term-item{display:flex;align-items:center;gap:10px;width:100%;padding:10px 16px;background:none;border:none;font-size:13px;color:#64748b;cursor:pointer;text-align:left}
.term-item:hover{background:#f8fafc;color:#3b82f6}
</style>
