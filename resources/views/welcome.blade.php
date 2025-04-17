@extends('layouts.app')

@section('content')
<div class="role-selection">
    <div class="logo-container">
        <img src="{{ asset('images/tooth-logo.svg') }}" alt="Brown Dental Lab Logo">
        <div class="logo-text">Brown Dental Lab</div>
        <div class="logo-subtext">BY BROWN DENTAL GROUP</div>
    </div>
    
    <a href="{{ route('login', ['role' => 'doctor']) }}" class="btn-dental">
        <img src="{{ asset('images/doctor-icon.svg') }}" alt="Doctor Icon" width="40">
        <span>طبيب</span>
    </a>
    
    <a href="{{ route('login', ['role' => 'employee']) }}" class="btn-dental">
        <img src="{{ asset('images/employee-icon.svg') }}" alt="Employee Icon" width="40">
        <span>موظف</span>
    </a>
    
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

@section('styles')
<style>
    body {
        background-color: var(--secondary-bg);
    }
    
    .role-selection {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    
    .logo-container {
        margin-bottom: 50px;
    }
    
    .logo-container img {
        width: 120px;
        height: auto;
        margin-bottom: 10px;
    }
    
    .logo-text, .logo-subtext {
        color: white;
    }
    
    .btn-dental {
        justify-content: space-between;
        padding: 15px 30px;
        margin: 15px 0;
        font-size: 1.2rem;
    }
    
    .footer {
        margin-top: 50px;
    }
    
    .social-icons a {
        color: white;
        margin: 0 5px;
        font-size: 1rem;
    }
</style>
@endsection
