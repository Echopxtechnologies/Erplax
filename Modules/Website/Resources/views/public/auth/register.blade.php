@extends('website::public.auth.auth-layout')

@section('title', 'Register - ' . ($settings->site_name ?? 'Store'))

@section('content')
<div class="auth-page">
    <div class="auth-container">
        <a href="{{ route('website.shop') }}" class="back-link">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18" height="18"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Shop
        </a>
        
        <div class="auth-card">
            @if($settings->getLogoUrl())
                <img src="{{ $settings->getLogoUrl() }}" alt="{{ $settings->site_name }}" class="logo">
            @endif
            <h1>Create Account</h1>
            <p class="subtitle">Join us and start shopping</p>
            
            @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif
            
            <form method="POST" action="{{ route('website.register.post') }}">
                @csrf
                
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your name" required>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required>
                </div>
                
                <div class="form-group">
                    <label>Phone <span class="optional">(Optional)</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Enter phone number">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Min 6 characters" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label>Confirm</label>
                        <input type="password" name="password_confirmation" placeholder="Confirm" required>
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">Create Account</button>
            </form>
            
            <p class="auth-footer">Already have an account? <a href="{{ route('website.login') }}">Login</a></p>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.auth-page{
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:40px 20px;
}
.auth-container{
    width:100%;
    max-width:480px;
}
.back-link{
    display:inline-flex;
    align-items:center;
    gap:6px;
    color:#64748b;
    font-size:14px;
    text-decoration:none;
    margin-bottom:24px;
    transition:color 0.2s;
}
.back-link:hover{
    color:#3b82f6;
}
.auth-card{
    background:#fff;
    border-radius:16px;
    padding:40px;
    box-shadow:0 1px 3px rgba(0,0,0,0.1);
    border:1px solid #e2e8f0;
    text-align:center;
}
.logo{
    max-height:50px;
    margin-bottom:24px;
}
.auth-card h1{
    font-size:26px;
    font-weight:700;
    color:#1e293b;
    margin-bottom:8px;
}
.subtitle{
    color:#64748b;
    font-size:14px;
    margin-bottom:32px;
}
.alert{
    padding:12px 16px;
    border-radius:10px;
    margin-bottom:20px;
    font-size:13px;
    text-align:left;
}
.alert-error{
    background:#fef2f2;
    color:#dc2626;
    border:1px solid #fecaca;
}
.form-group{
    margin-bottom:18px;
    text-align:left;
}
.form-row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
}
.form-group label{
    display:block;
    font-size:13px;
    font-weight:600;
    color:#374151;
    margin-bottom:8px;
}
.form-group label .optional{
    font-weight:400;
    color:#9ca3af;
}
.form-group input{
    width:100%;
    padding:14px 16px;
    border:1px solid #e2e8f0;
    border-radius:10px;
    font-size:15px;
    transition:all 0.2s;
}
.form-group input:focus{
    outline:none;
    border-color:#3b82f6;
    box-shadow:0 0 0 3px rgba(59,130,246,0.1);
}
.btn-submit{
    width:100%;
    padding:14px;
    background:#3b82f6;
    color:#fff;
    border:none;
    border-radius:10px;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
    transition:background 0.2s;
}
.btn-submit:hover{
    background:#2563eb;
}
.auth-footer{
    margin-top:24px;
    font-size:14px;
    color:#64748b;
}
.auth-footer a{
    color:#3b82f6;
    font-weight:600;
    text-decoration:none;
}
</style>
@endsection
