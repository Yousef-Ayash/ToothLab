@extends('layouts.app')

@section('content')
<div class="login-form">
    <div class="logo-container">
        <img src="{{ asset('images/tooth-logo.svg') }}" alt="Brown Dental Lab Logo">
        <div class="logo-text">Brown Dental Lab</div>
        <div class="logo-subtext">BY BROWN DENTAL GROUP</div>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <input type="hidden" name="role" value="{{ request('role', '') }}">

        <div class="mb-4">
            <label for="email" class="form-label">اسم المستخدم</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">كلمة السر</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn-submit">
                موافق
            </button>
        </div>
    </form>
    
    <div class="footer">
        <div class="social-icons">
            <a href="#" target="_blank"><i class="fab fa-whatsapp"></i></a>
            <a href="#" target="_blank"><i class="fab fa-facebook"></i></a>
            <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
            <a href="#" target="_blank"><i class="fab fa-telegram"></i></a>
            <a href="#" target="_blank">@browndentallab</a>
        </div>
    </div>
</div>
@endsection
