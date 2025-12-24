{{-- Barcode Scanner --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

@php
    $color = $color ?? 'green';
    $colors = [
        'green' => ['bg' => '#d1fae5', 'border' => '#86efac', 'icon' => '#059669'],
        'red' => ['bg' => '#fee2e2', 'border' => '#fecaca', 'icon' => '#dc2626'],
        'blue' => ['bg' => '#dbeafe', 'border' => '#93c5fd', 'icon' => '#2563eb'],
        'orange' => ['bg' => '#ffedd5', 'border' => '#fed7aa', 'icon' => '#ea580c'],
        'purple' => ['bg' => '#ede9fe', 'border' => '#c4b5fd', 'icon' => '#7c3aed'],
    ];
    $c = $colors[$color] ?? $colors['green'];
@endphp

<style>
.scan-box{background:{{ $c['bg'] }};border:2px solid {{ $c['border'] }};border-radius:10px;padding:12px 14px;margin-bottom:16px;display:flex;align-items:center;gap:10px}
.scan-icon{width:36px;height:36px;border-radius:8px;background:{{ $c['icon'] }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:18px}
.scan-input{flex:1;padding:12px 14px;border:2px solid {{ $c['border'] }};border-radius:8px;font-size:16px;font-weight:600;font-family:'Courier New',monospace;background:#fff}
.scan-input:focus{outline:none;border-color:{{ $c['icon'] }}}
.scan-input::placeholder{font-size:12px;font-weight:400;color:#9ca3af;font-family:inherit}
.scan-cam-btn{padding:12px 14px;background:#374151;color:#fff;border:none;border-radius:8px;font-size:18px;cursor:pointer}
.scan-toast{position:fixed;top:16px;right:16px;background:{{ $c['icon'] }};color:#fff;padding:12px 18px;border-radius:8px;font-weight:600;display:none;z-index:10000;box-shadow:0 4px 20px rgba(0,0,0,0.3)}
.scan-toast.show{display:block}.scan-toast.error{background:#dc2626}
.scan-camera{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.95);z-index:9999;padding:16px;flex-direction:column}
.scan-camera.show{display:flex}
.scan-camera-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px}
.scan-camera-title{color:#fff;font-size:18px;font-weight:700}
.scan-camera-close{width:40px;height:40px;border-radius:50%;background:rgba(255,255,255,0.1);border:none;color:#fff;font-size:24px;cursor:pointer}
.scan-viewport{flex:1;display:flex;align-items:center;justify-content:center}
.scan-viewport-inner{width:100%;max-width:400px;aspect-ratio:4/3;background:#000;border-radius:12px;overflow:hidden;position:relative}
.scan-viewport-inner video{width:100%;height:100%;object-fit:cover}
.scan-area{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:80%;height:35%;border:3px solid {{ $c['icon'] }};border-radius:10px;box-shadow:0 0 0 9999px rgba(0,0,0,0.5)}
.scan-line{position:absolute;top:0;left:0;right:0;height:3px;background:{{ $c['icon'] }};animation:scanMove 1.5s ease-in-out infinite}
@keyframes scanMove{0%,100%{top:0}50%{top:calc(100% - 3px)}}
.scan-detected{position:absolute;bottom:16px;left:50%;transform:translateX(-50%);background:{{ $c['icon'] }};color:#fff;padding:10px 20px;border-radius:8px;font-weight:600;display:none}
.scan-detected.show{display:block}
.scan-camera-btns{display:flex;gap:10px;justify-content:center;margin-top:16px}
.scan-camera-btn{padding:10px 20px;background:rgba(255,255,255,0.1);color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer}
.scan-camera-btn.active{background:#fbbf24;color:#000}
</style>

<div class="scan-box">
    <div class="scan-icon">📦</div>
    <input type="text" class="scan-input" id="scanInput" placeholder="Scan barcode..." autocomplete="off">
    <button type="button" class="scan-cam-btn" onclick="openScanCam()">📷</button>
</div>
<div class="scan-toast" id="scanToast"></div>
<div class="scan-camera" id="scanCamera">
    <div class="scan-camera-header">
        <div class="scan-camera-title">📷 Scan</div>
        <button type="button" class="scan-camera-close" onclick="closeScanCam()">✕</button>
    </div>
    <div class="scan-viewport">
        <div class="scan-viewport-inner" id="scanViewport">
            <div class="scan-area"><div class="scan-line"></div></div>
            <div class="scan-detected" id="scanDetected"></div>
        </div>
    </div>
    <div class="scan-camera-btns">
        <button type="button" class="scan-camera-btn" id="scanTorchBtn" onclick="toggleScanTorch()">🔦</button>
        <button type="button" class="scan-camera-btn" onclick="flipScanCam()">🔄</button>
    </div>
</div>

<script>
(function(){
    // State
    var scannedData = null;
    var scannedQty = 0;
    var scannedVarId = null;
    var lastScanTime = 0;
    
    // Initialize after page is ready
    window.addEventListener('load', function(){
        setTimeout(setupScanner, 500); // Wait for TomSelect to initialize
    });
    
    function setupScanner(){
        var input = document.getElementById('scanInput');
        if(!input) return;
        
        input.addEventListener('keydown', function(e){
            if(e.key === 'Enter'){
                e.preventDefault();
                processBarcode(this.value.trim());
                this.value = '';
            }
        });
        
        var timer;
        input.addEventListener('input', function(){
            clearTimeout(timer);
            var code = this.value.trim();
            var self = this;
            if(code.length >= 4){
                timer = setTimeout(function(){
                    processBarcode(code);
                    self.value = '';
                }, 400);
            }
        });
    }
    
    window.processBarcode = function(code){
        if(!code) return;
        
        var now = Date.now();
        if(now - lastScanTime < 400) return;
        lastScanTime = now;
        
        // Same product - increment qty
        if(scannedData){
            var p = scannedData.product;
            var v = scannedData.variation;
            if(p.barcode === code || p.sku === code || (v && (v.barcode === code || v.sku === code))){
                scannedQty++;
                setQtyField(scannedQty);
                toast('Qty: ' + scannedQty);
                if(navigator.vibrate) navigator.vibrate(50);
                return;
            }
        }
        
        // Lookup
        fetch('{{ route("inventory.barcode.lookup") }}?code=' + encodeURIComponent(code))
            .then(function(r){ return r.json(); })
            .then(function(data){
                if(data.success){
                    if(scannedData && scannedQty > 0){
                        if(!confirm('Switch to ' + data.product.name + '?')) return;
                    }
                    
                    scannedData = data;
                    scannedQty = 1;
                    scannedVarId = data.variation ? String(data.variation.id) : null;
                    
                    selectScannedProduct(data.product.id);
                    setQtyField(1);
                    toast('✓ ' + data.product.name + (data.variation ? ' - ' + data.variation.name : ''));
                    if(navigator.vibrate) navigator.vibrate(100);
                    
                    // Set variation after delay to ensure options are loaded
                    if(scannedVarId){
                        setVariationAfterLoad(scannedVarId);
                    }
                } else {
                    toast('Not found: ' + code, true);
                }
            })
            .catch(function(e){
                console.error(e);
                toast('Error', true);
            });
    };
    
    function selectScannedProduct(pid){
        // Get the TomSelect instance for product
        var productTomSelect = null;
        if(typeof selProduct !== 'undefined' && selProduct){
            productTomSelect = selProduct;
        } else {
            var sel = document.getElementById('product_id');
            if(sel && sel.tomselect){
                productTomSelect = sel.tomselect;
            }
        }
        
        // Set product value silently
        if(productTomSelect){
            productTomSelect.setValue(String(pid), true); // true = silent, no onChange
        } else {
            var sel = document.getElementById('product_id');
            if(sel) sel.value = pid;
        }
        
        // IMPORTANT: Call onProduct directly to load units, lots, variations
        setTimeout(function(){
            if(typeof onProduct === 'function'){
                onProduct(String(pid));
            }
        }, 50);
    }
    
    function setVariationAfterLoad(varId){
        var attempts = 0;
        var maxAttempts = 50; // 5 seconds max
        
        function trySet(){
            attempts++;
            if(attempts > maxAttempts){
                console.log('Barcode scanner: Gave up setting variation after ' + maxAttempts + ' attempts');
                return;
            }
            
            setTimeout(function(){
                // Get the TomSelect instance for variation
                var varTomSelect = null;
                if(typeof selVariation !== 'undefined' && selVariation){
                    varTomSelect = selVariation;
                } else {
                    var vSel = document.getElementById('variation_id');
                    if(vSel && vSel.tomselect){
                        varTomSelect = vSel.tomselect;
                    }
                }
                
                if(!varTomSelect){
                    trySet(); // No TomSelect yet, retry
                    return;
                }
                
                // Check if options are loaded (more than just the empty option)
                var optCount = 0;
                if(varTomSelect.options){
                    optCount = Object.keys(varTomSelect.options).length;
                }
                
                // Check if the specific variation option exists
                var hasVariation = varTomSelect.options && varTomSelect.options[varId];
                
                if(optCount > 1 && hasVariation){
                    // Set variation silently then trigger onVariation
                    varTomSelect.setValue(varId, true);
                    
                    // Call onVariation if exists
                    setTimeout(function(){
                        if(typeof onVariation === 'function'){
                            onVariation(varId);
                        }
                    }, 50);
                    
                    console.log('Barcode scanner: Variation set successfully on attempt ' + attempts);
                } else {
                    trySet(); // Options not loaded yet, retry
                }
            }, 100);
        }
        
        trySet();
    }
    
    function setQtyField(qty){
        var inp = document.getElementById('qty');
        if(!inp) inp = document.querySelector('input[name="qty"]');
        if(inp){
            inp.value = qty;
            inp.dispatchEvent(new Event('input', {bubbles:true}));
        }
    }
    
    function toast(msg, isErr){
        var t = document.getElementById('scanToast');
        if(!t) return;
        t.textContent = msg;
        t.className = 'scan-toast show' + (isErr ? ' error' : '');
        setTimeout(function(){ t.classList.remove('show'); }, 1500);
    }
    
    // Camera
    var camOn = false, camFace = 'environment', camStream = null;
    
    window.openScanCam = function(){
        document.getElementById('scanCamera').classList.add('show');
        document.body.style.overflow = 'hidden';
        startQuagga();
    };
    
    window.closeScanCam = function(){
        stopQuagga();
        document.getElementById('scanCamera').classList.remove('show');
        document.body.style.overflow = '';
    };
    
    function startQuagga(){
        if(camOn || typeof Quagga === 'undefined') return;
        
        Quagga.init({
            inputStream: {
                type: "LiveStream",
                target: document.getElementById('scanViewport'),
                constraints: { width: 1280, height: 720, facingMode: camFace }
            },
            decoder: { readers: ["ean_reader","ean_8_reader","code_128_reader","code_39_reader","upc_reader"] },
            locate: true
        }, function(err){
            if(err){ closeScanCam(); return; }
            Quagga.start();
            camOn = true;
            var vid = document.querySelector('#scanViewport video');
            if(vid && vid.srcObject) camStream = vid.srcObject;
        });
        
        Quagga.onDetected(function(r){
            if(r && r.codeResult && r.codeResult.code && r.codeResult.code.length >= 4){
                var code = r.codeResult.code;
                var det = document.getElementById('scanDetected');
                det.textContent = '✓ ' + code;
                det.classList.add('show');
                if(navigator.vibrate) navigator.vibrate(100);
                
                processBarcode(code);
                
                Quagga.pause();
                setTimeout(function(){
                    det.classList.remove('show');
                    if(camOn) Quagga.start();
                }, 800);
            }
        });
    }
    
    function stopQuagga(){
        if(camOn){ Quagga.stop(); camOn = false; }
        camStream = null;
    }
    
    window.toggleScanTorch = function(){
        if(!camStream) return;
        var track = camStream.getVideoTracks()[0];
        if(!track) return;
        var caps = track.getCapabilities ? track.getCapabilities() : {};
        if(!caps.torch) return;
        var btn = document.getElementById('scanTorchBtn');
        var on = btn.classList.contains('active');
        track.applyConstraints({ advanced: [{ torch: !on }] }).then(function(){
            btn.classList.toggle('active');
        });
    };
    
    window.flipScanCam = function(){
        camFace = camFace === 'environment' ? 'user' : 'environment';
        stopQuagga();
        setTimeout(startQuagga, 300);
    };
    
    document.addEventListener('keydown', function(e){
        if(e.key === 'Escape' && document.getElementById('scanCamera').classList.contains('show')){
            closeScanCam();
        }
    });
})();
</script>
