@extends('layouts.app')

@section('content')
<div class="contact-page">
    <div class="logo-container">
        <img src="{{ asset('images/tooth-logo.svg') }}" alt="Brown Dental Lab Logo">
        <div class="logo-text">Brown Dental Lab</div>
        <div class="logo-subtext">BY BROWN DENTAL GROUP</div>
    </div>
    
    <a href="https://facebook.com/browndentallab" target="_blank" class="social-btn">
        <i class="fab fa-facebook"></i>
        <span>brown dental lab</span>
    </a>
    
    <a href="https://instagram.com/browndentallab" target="_blank" class="social-btn">
        <i class="fab fa-instagram"></i>
        <span>brown dental lab</span>
    </a>
    
    <a href="https://t.me/browndentallab" target="_blank" class="social-btn">
        <i class="fab fa-telegram"></i>
        <span>+963 982 072 746</span>
    </a>
    
    <a href="https://wa.me/963936153111" target="_blank" class="social-btn">
        <i class="fab fa-whatsapp"></i>
        <span>+963 936 153 111</span>
    </a>
</div>
@endsection
