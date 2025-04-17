@extends('layouts.app')

@section('content')
<div class="employee-orders">
    <div class="logo-container">
        <img src="{{ asset('images/tooth-logo.svg') }}" alt="Brown Dental Lab Logo">
        <div class="logo-text">Brown Dental Lab</div>
        <div class="logo-subtext">BY BROWN DENTAL GROUP</div>
    </div>
    
    <h3 class="page-title">طلباتي</h3>
    
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="orders-container">
        @forelse ($orders as $order)
            <div class="order-card">
                <div class="order-header">
                    <div class="order-number">طلب #{{ $order->id }}</div>
                    <div class="order-status">
                        <span class="status-badge {{ $order->status == 'completed' ? 'completed' : ($order->status == 'in_progress' ? 'in-progress' : 'pending') }}">
                            {{ $order->status == 'completed' ? 'مكتمل' : ($order->status == 'in_progress' ? 'قيد التنفيذ' : 'معلق') }}
                        </span>
                    </div>
                </div>
                
                <div class="order-details">
                    <div class="detail-row">
                        <div class="detail-label">الطبيب:</div>
                        <div class="detail-value">{{ $order->doctor->name }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">المريض:</div>
                        <div class="detail-value">{{ $order->patient_name }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">الإجراء:</div>
                        <div class="detail-value">{{ $order->procedure->name }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">تاريخ الإنشاء:</div>
                        <div class="detail-value">{{ $order->created_at->format('Y/m/d') }}</div>
                    </div>
                </div>
                
                <div class="order-steps">
                    @php
                        $mySteps = $order->orderSteps->filter(function($orderStep) {
                            return $orderStep->employees->contains('id', auth()->id());
                        });
                        
                        $pendingCount = $mySteps->where('status', 'pending')->count();
                        $inProgressCount = $mySteps->where('status', 'in_progress')->count();
                        $completedCount = $mySteps->where('status', 'completed')->count();
                    @endphp
                    
                    <div class="steps-summary">
                        @if($pendingCount > 0)
                            <span class="step-badge pending">{{ $pendingCount }} معلق</span>
                        @endif
                        
                        @if($inProgressCount > 0)
                            <span class="step-badge in-progress">{{ $inProgressCount }} قيد التنفيذ</span>
                        @endif
                        
                        @if($completedCount > 0)
                            <span class="step-badge completed">{{ $completedCount }} مكتمل</span>
                        @endif
                    </div>
                </div>
                
                <div class="order-actions">
                    <a href="{{ route('employee.order.show', $order) }}" class="btn-dental">عرض التفاصيل</a>
                </div>
            </div>
        @empty
            <div class="no-orders">
                <p>لا توجد طلبات مخصصة لك.</p>
            </div>
        @endforelse
    </div>
    
    <div class="back-link">
        <a href="{{ route('employee.dashboard') }}" class="btn-dental">
            <i class="fas fa-arrow-right"></i>
            العودة إلى لوحة التحكم
        </a>
    </div>
</div>

@section('styles')
<style>
    .employee-orders {
        padding: 20px 0;
    }
    
    .page-title {
        margin: 20px 0;
        text-align: center;
        font-weight: bold;
    }
    
    .orders-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin: 20px 0;
    }
    
    .order-card {
        background-color: white;
        border-radius: 15px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    
    .order-number {
        font-weight: bold;
        font-size: 1.2rem;
    }
    
    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
    }
    
    .status-badge.pending {
        background-color: #e3f2fd;
        color: #0d47a1;
    }
    
    .status-badge.in-progress {
        background-color: #fff8e1;
        color: #ff8f00;
    }
    
    .status-badge.completed {
        background-color: #e8f5e9;
        color: #2e7d32;
    }
    
    .order-details {
        margin-bottom: 15px;
    }
    
    .detail-row {
        display: flex;
        margin-bottom: 5px;
    }
    
    .detail-label {
        font-weight: bold;
        width: 100px;
    }
    
    .steps-summary {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .step-badge {
        padding: 3px 8px;
        border-radius: 15px;
        font-size: 0.8rem;
    }
    
    .step-badge.pending {
        background-color: #e3f2fd;
        color: #0d47a1;
    }
    
    .step-badge.in-progress {
        background-color: #fff8e1;
        color: #ff8f00;
    }
    
    .step-badge.completed {
        background-color: #e8f5e9;
        color: #2e7d32;
    }
    
    .order-actions {
        margin-top: 15px;
        display: flex;
        justify-content: flex-end;
    }
    
    .no-orders {
        text-align: center;
        padding: 30px;
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .back-link {
        margin-top: 20px;
        text-align: center;
    }
    
    @media (max-width: 768px) {
        .order-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .order-status {
            margin-top: 5px;
        }
        
        .detail-row {
            flex-direction: column;
        }
        
        .detail-label {
            width: 100%;
        }
    }
</style>
@endsection
@endsection
