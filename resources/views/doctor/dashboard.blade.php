@extends('layouts.app')

@section('content')
<div class="doctor-dashboard">
    <div class="logo-container">
        <img src="{{ asset('images/tooth-logo.svg') }}" alt="Brown Dental Lab Logo">
        <div class="logo-text">Brown Dental Lab</div>
        <div class="logo-subtext">BY BROWN DENTAL GROUP</div>
    </div>
    
    <a href="{{ route('doctor.my-work') }}" class="btn-dental">
        <img src="{{ asset('images/work-icon.svg') }}" alt="My Work Icon" width="40">
        <span>اعمالي</span>
    </a>
    
    <a href="{{ route('doctor.new-order') }}" class="btn-dental">
        <img src="{{ asset('images/new-order-icon.svg') }}" alt="New Order Icon" width="40">
        <span>طلب جديد</span>
    </a>
    
    <a href="{{ route('doctor.finance') }}" class="btn-dental">
        <img src="{{ asset('images/finance-icon.svg') }}" alt="Finance Icon" width="40">
        <span>السجل المالي</span>
    </a>
    
    <a href="{{ route('doctor.prices') }}" class="btn-dental">
        <img src="{{ asset('images/price-icon.svg') }}" alt="Prices Icon" width="40">
        <span>لائحة الاسعار</span>
    </a>
    
    <a href="{{ route('doctor.contact') }}" class="btn-dental">
        <img src="{{ asset('images/contact-icon.svg') }}" alt="Contact Icon" width="40">
        <span>تواصل معنا</span>
    </a>
</div>
@endsection
