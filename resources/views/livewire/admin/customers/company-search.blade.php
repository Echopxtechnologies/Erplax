<div class="company-search-wrapper">
    <input 
        type="text" 
        name="company"
        wire:model.live.debounce.300ms="search"
        wire:keydown.escape="hideDropdown"
        class="form-control"
        placeholder="Type to search company..."
        autocomplete="off"
    >
    
    @if($showDropdown && count($results) > 0)
        <div class="company-dropdown">
            @foreach($results as $index => $company)
                <div class="company-item" wire:click="selectCompany({{ $index }})">
                    <div class="company-name">{{ $company['company'] }}</div>
                    @if($company['gst_number'])
                        <div class="company-details">GST: {{ $company['gst_number'] }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <style>
        .company-search-wrapper { position: relative; }
        .company-dropdown { position: absolute; top: 100%; left: 0; right: 0; background: var(--card-bg, #fff); border: 1px solid var(--card-border, #ddd); border-top: none; border-radius: 0 0 6px 6px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 1000; max-height: 200px; overflow-y: auto; }
        .company-item { padding: 10px 12px; cursor: pointer; border-bottom: 1px solid var(--card-border, #f0f0f0); }
        .company-item:hover { background: var(--primary-light, #f0f9ff); }
        .company-item:last-child { border-bottom: none; }
        .company-name { font-weight: 600; color: var(--text-primary, #333); }
        .company-details { font-size: 12px; color: var(--text-muted, #666); margin-top: 2px; }

        /* Dark mode overrides */
        .dark .company-dropdown, [data-theme="dark"] .company-dropdown { background: #1e293b; border-color: #334155; }
        .dark .company-item, [data-theme="dark"] .company-item { border-color: #334155; }
        .dark .company-item:hover, [data-theme="dark"] .company-item:hover { background: #1e3a5f; }
        .dark .company-name, [data-theme="dark"] .company-name { color: #f1f5f9; }
        .dark .company-details, [data-theme="dark"] .company-details { color: #94a3b8; }
    </style>
</div>