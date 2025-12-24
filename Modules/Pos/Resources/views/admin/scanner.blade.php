<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>📷 POS Scanner</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;background:#000;color:#fff;min-height:100vh;display:flex;flex-direction:column}
.header{padding:16px;background:rgba(0,0,0,0.8);display:flex;align-items:center;justify-content:space-between;position:fixed;top:0;left:0;right:0;z-index:100}
.header h1{font-size:18px;display:flex;align-items:center;gap:8px}
.session-badge{background:#22c55e;color:#fff;padding:6px 12px;border-radius:20px;font-size:12px;font-weight:600}
.scanner-area{flex:1;display:flex;align-items:center;justify-content:center;padding:80px 20px 200px}
.viewport{width:100%;max-width:400px;aspect-ratio:4/3;background:#111;border-radius:16px;overflow:hidden;position:relative}
.viewport video{width:100%;height:100%;object-fit:cover}
.scan-frame{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:80%;height:40%;border:3px solid #22c55e;border-radius:12px;box-shadow:0 0 0 9999px rgba(0,0,0,0.6)}
.scan-frame::before{content:'';position:absolute;left:0;right:0;height:3px;background:#22c55e;animation:scan 1.5s ease-in-out infinite}
@keyframes scan{0%,100%{top:0}50%{top:calc(100% - 3px)}}
.result{position:absolute;bottom:20px;left:50%;transform:translateX(-50%);background:#22c55e;color:#fff;padding:12px 24px;border-radius:10px;font-weight:600;font-size:16px;display:none;white-space:nowrap}
.result.show{display:block}
.result.error{background:#ef4444}
.controls{position:fixed;bottom:0;left:0;right:0;padding:20px;background:linear-gradient(to top,rgba(0,0,0,0.95),transparent);display:flex;flex-direction:column;gap:12px}
.manual-input{display:flex;gap:10px}
.manual-input input{flex:1;padding:14px 16px;border:2px solid #333;border-radius:12px;font-size:18px;font-family:monospace;background:#111;color:#fff}
.manual-input input:focus{outline:none;border-color:#22c55e}
.manual-input button{padding:14px 20px;background:#22c55e;border:none;border-radius:12px;color:#fff;font-weight:600;font-size:16px}
.btn-row{display:flex;gap:10px}
.btn{flex:1;padding:14px;background:rgba(255,255,255,0.1);border:none;border-radius:12px;color:#fff;font-weight:600;font-size:14px;display:flex;align-items:center;justify-content:center;gap:8px}
.btn.active{background:#22c55e}
.count{text-align:center;font-size:14px;color:#888;padding:8px}
.count span{color:#22c55e;font-weight:700;font-size:18px}
.status{position:fixed;top:70px;left:50%;transform:translateX(-50%);background:#22c55e;color:#fff;padding:8px 16px;border-radius:20px;font-size:12px;font-weight:600;display:none}
.status.show{display:block}
.status.offline{background:#ef4444}
</style>
</head>
<body>

<div class="header">
    <h1>📷 POS Scanner</h1>
    <div class="session-badge">{{ $code }}</div>
</div>

<div class="status" id="status">Connected</div>

<div class="scanner-area">
    <div class="viewport" id="viewport">
        <div class="scan-frame"></div>
        <div class="result" id="result"></div>
    </div>
</div>

<div class="controls">
    <div class="count">Scanned: <span id="scanCount">0</span></div>
    <div class="manual-input">
        <input type="text" id="manualInput" placeholder="Type barcode..." autocomplete="off">
        <button onclick="sendManual()">Send</button>
    </div>
    <div class="btn-row">
        <button class="btn" id="torchBtn" onclick="toggleTorch()">🔦 Light</button>
        <button class="btn" onclick="flipCam()">🔄 Flip</button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script>
const SESSION_CODE = '{{ $code }}';
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let scanCount = 0;
let camOn = false;
let facing = 'environment';
let lastSent = '';
let lastTime = 0;

// Audio context for beep sound
var audioCtx = null;
function beep(success){
    try {
        if(!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        var osc = audioCtx.createOscillator();
        var gain = audioCtx.createGain();
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        osc.frequency.value = success ? 1200 : 400;
        osc.type = 'sine';
        gain.gain.value = 0.3;
        osc.start();
        osc.stop(audioCtx.currentTime + (success ? 0.15 : 0.3));
    } catch(e){}
}

// Start camera on load
window.onload = function(){
    setTimeout(startCam, 500);
};

function startCam(){
    if(camOn || typeof Quagga === 'undefined') return;
    
    Quagga.init({
        inputStream: {
            type: "LiveStream",
            target: document.getElementById('viewport'),
            constraints: { width: 1280, height: 720, facingMode: facing }
        },
        decoder: { readers: ["ean_reader","ean_8_reader","code_128_reader","code_39_reader","upc_reader","upc_e_reader"] },
        locate: true
    }, function(err){
        if(err){ console.error(err); return; }
        Quagga.start();
        camOn = true;
    });
    
    Quagga.onDetected(onDetected);
}

function stopCam(){
    if(camOn && typeof Quagga !== 'undefined'){
        Quagga.offDetected(onDetected);
        Quagga.stop();
        camOn = false;
    }
    var vp = document.getElementById('viewport');
    var v = vp.querySelector('video');
    var c = vp.querySelector('canvas');
    if(v) v.remove();
    if(c) c.remove();
}

function onDetected(r){
    if(r && r.codeResult && r.codeResult.code && r.codeResult.code.length >= 4){
        var code = r.codeResult.code;
        sendBarcode(code);
    }
}

function sendBarcode(code){
    // Debounce - same code within 500ms
    var now = Date.now();
    if(code === lastSent && (now - lastTime) < 500) return;
    lastSent = code;
    lastTime = now;
    
    // Show result
    var res = document.getElementById('result');
    res.textContent = '✓ ' + code;
    res.className = 'result show';
    
    // Play beep sound
    beep(true);
    if(navigator.vibrate) navigator.vibrate(100);
    
    // Pause camera briefly
    if(camOn) Quagga.pause();
    
    // Send to server
    fetch('{{ route("pos.scanner.send") }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF},
        body: JSON.stringify({ session_code: SESSION_CODE, barcode: code })
    })
    .then(r => r.json())
    .then(d => {
        if(d.success){
            scanCount++;
            document.getElementById('scanCount').textContent = scanCount;
            res.textContent = '✓ Sent: ' + code;
            showStatus('Sent to POS', false);
        } else {
            res.textContent = '✕ Failed';
            res.className = 'result show error';
            beep(false);
            showStatus('Failed to send', true);
        }
    })
    .catch(() => {
        res.textContent = '✕ Offline';
        res.className = 'result show error';
        beep(false);
        showStatus('Connection error', true);
    });
    
    // Resume camera
    setTimeout(function(){
        res.classList.remove('show');
        if(camOn) Quagga.start();
    }, 1000);
}

function sendManual(){
    var input = document.getElementById('manualInput');
    var code = input.value.trim();
    if(code.length >= 3){
        beep(true);
        sendBarcode(code);
        input.value = '';
    }
}

document.getElementById('manualInput').addEventListener('keydown', function(e){
    if(e.key === 'Enter'){
        e.preventDefault();
        sendManual();
    }
});

function flipCam(){
    facing = facing === 'environment' ? 'user' : 'environment';
    stopCam();
    setTimeout(startCam, 300);
}

function toggleTorch(){
    var vp = document.getElementById('viewport');
    var video = vp.querySelector('video');
    if(!video || !video.srcObject) return;
    
    var track = video.srcObject.getVideoTracks()[0];
    if(!track) return;
    
    var caps = track.getCapabilities ? track.getCapabilities() : {};
    if(!caps.torch) return;
    
    var btn = document.getElementById('torchBtn');
    var on = btn.classList.contains('active');
    track.applyConstraints({ advanced: [{ torch: !on }] }).then(function(){
        btn.classList.toggle('active');
    });
}

function showStatus(msg, isError){
    var s = document.getElementById('status');
    s.textContent = msg;
    s.className = 'status show' + (isError ? ' offline' : '');
    setTimeout(function(){ s.classList.remove('show'); }, 2000);
}

// Keep screen awake
if('wakeLock' in navigator){
    navigator.wakeLock.request('screen').catch(()=>{});
}
</script>
</body>
</html>
