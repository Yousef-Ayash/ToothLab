@extends('layouts.app')

@section('content')
<div class="order-details-page">
    <div class="logo-container">
        <img src="{{ asset('images/tooth-logo.svg') }}" alt="Brown Dental Lab Logo">
        <div class="logo-text">Brown Dental Lab</div>
        <div class="logo-subtext">BY BROWN DENTAL GROUP</div>
    </div>
    
    <h3 class="page-title">تفاصيل الطلب #{{ $order->id }}</h3>
    
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
    
    <div class="order-info-cards">
        <div class="info-card">
            <h4 class="card-title">معلومات الطلب</h4>
            <div class="info-table">
                <div class="info-row">
                    <div class="info-label">رقم الطلب</div>
                    <div class="info-value">{{ $order->id }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">الحالة</div>
                    <div class="info-value">
                        <span class="status-badge {{ $order->status == 'completed' ? 'completed' : ($order->status == 'in_progress' ? 'in-progress' : 'pending') }}">
                            {{ $order->status == 'completed' ? 'مكتمل' : ($order->status == 'in_progress' ? 'قيد التنفيذ' : 'معلق') }}
                        </span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">الطبيب</div>
                    <div class="info-value">{{ $order->doctor->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">المريض</div>
                    <div class="info-value">{{ $order->patient_name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">الإجراء</div>
                    <div class="info-value">{{ $order->procedure->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">تاريخ الإنشاء</div>
                    <div class="info-value">{{ $order->created_at->format('Y/m/d H:i') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">تاريخ الاستحقاق</div>
                    <div class="info-value">{{ $order->due_date ? $order->due_date->format('Y/m/d') : 'غير محدد' }}</div>
                </div>
            </div>
        </div>
        
        <div class="info-card">
            <h4 class="card-title">المواصفات</h4>
            <div class="info-table">
                <div class="info-row">
                    <div class="info-label">الأسنان</div>
                    <div class="info-value">
                        @if($order->teeth->count() > 0)
                            <div class="teeth-list">
                                @foreach($order->teeth as $tooth)
                                    <span class="tooth-badge">{{ $tooth->number }}</span>
                                @endforeach
                            </div>
                        @else
                            غير محدد
                        @endif
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">اللون</div>
                    <div class="info-value">
                        @if($order->color)
                            <div class="color-display">
                                <div class="color-swatch" style="background-color: {{ $order->color->code }};"></div>
                                <span>{{ $order->color->name }}</span>
                            </div>
                        @else
                            غير محدد
                        @endif
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">ملاحظات</div>
                    <div class="info-value">{{ $order->notes ?? 'لا توجد ملاحظات' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">تعليمات خاصة</div>
                    <div class="info-value">{{ $order->special_instructions ?? 'لا توجد' }}</div>
                </div>
            </div>
            
            <div class="progress-section">
                <h5>تقدم الطلب</h5>
                @php
                    $totalSteps = $order->orderSteps->count();
                    $completedSteps = $order->orderSteps->where('status', 'completed')->count();
                    $progressPercentage = $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;
                @endphp
                
                <div class="progress-info">
                    <span>{{ $completedSteps }} من {{ $totalSteps }} خطوات مكتملة</span>
                    <span>{{ $progressPercentage }}%</span>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar" style="width: {{ $progressPercentage }}%"></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="steps-section">
        <h4 class="section-title">خطوات الطلب</h4>
        
        <div class="steps-list">
            @foreach($order->orderSteps->sortBy('step.order') as $orderStep)
                @php
                    $isAssignedToMe = $orderStep->employees->contains('id', auth()->id());
                @endphp
                <div class="step-card {{ $isAssignedToMe ? 'assigned-to-me' : '' }}">
                    <div class="step-header">
                        <div class="step-name">{{ $orderStep->step->name }}</div>
                        <div class="step-status">
                            <span class="status-badge {{ $orderStep->status == 'completed' ? 'completed' : ($orderStep->status == 'in_progress' ? 'in-progress' : 'pending') }}">
                                {{ $orderStep->status == 'completed' ? 'مكتمل' : ($orderStep->status == 'in_progress' ? 'قيد التنفيذ' : 'معلق') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="step-details">
                        <div class="detail-row">
                            <div class="detail-label">الوصف:</div>
                            <div class="detail-value">{{ $orderStep->step->description }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">المدة:</div>
                            <div class="detail-value">{{ $orderStep->step->duration }} ساعات</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">مخصص لـ:</div>
                            <div class="detail-value">
                                @foreach($orderStep->employees as $employee)
                                    <span class="employee-badge">{{ $employee->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    @if($isAssignedToMe)
                        <div class="step-actions">
                            @if($orderStep->status == 'pending')
                                <button type="button" class="btn-dental btn-primary" data-bs-toggle="modal" data-bs-target="#startStepModal{{ $orderStep->id }}">
                                    بدء العمل
                                </button>
                                
                                <!-- Start Step Modal -->
                                <div class="modal fade" id="startStepModal{{ $orderStep->id }}" tabindex="-1" aria-labelledby="startStepModalLabel{{ $orderStep->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="startStepModalLabel{{ $orderStep->id }}">بدء العمل: {{ $orderStep->step->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('employee.step.update', $orderStep) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <p>أنت على وشك البدء في العمل على هذه الخطوة للطلب #{{ $order->id }}.</p>
                                                    <div class="mb-3">
                                                        <label for="notes" class="form-label">ملاحظات أولية (اختياري)</label>
                                                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ $orderStep->notes }}</textarea>
                                                    </div>
                                                    <input type="hidden" name="status" value="in_progress">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">إلغاء</button>
                                                    <button type="submit" class="btn-submit">بدء العمل</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @elseif($orderStep->status == 'in_progress')
                                <button type="button" class="btn-dental btn-primary" data-bs-toggle="modal" data-bs-target="#completeStepModal{{ $orderStep->id }}">
                                    إكمال العمل
                                </button>
                                
                                <!-- Complete Step Modal -->
                                <div class="modal fade" id="completeStepModal{{ $orderStep->id }}" tabindex="-1" aria-labelledby="completeStepModalLabel{{ $orderStep->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="completeStepModalLabel{{ $orderStep->id }}">إكمال العمل: {{ $orderStep->step->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('employee.step.update', $orderStep) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <p>أنت على وشك وضع علامة على هذه الخطوة كمكتملة للطلب #{{ $order->id }}.</p>
                                                    <div class="mb-3">
                                                        <label for="notes" class="form-label">ملاحظات الإكمال</label>
                                                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ $orderStep->notes }}</textarea>
                                                    </div>
                                                    <input type="hidden" name="status" value="completed">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">إلغاء</button>
                                                    <button type="submit" class="btn-submit">إكمال العمل</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    
    <div class="back-link">
        <a href="{{ route('employee.orders') }}" class="btn-dental">
            <i class="fas fa-arrow-right"></i>
            العودة إلى الطلبات
        </a>
    </div>
</div>

@section('styles')
<style>
    .order-details-page {
        padding: 20px 0;
    }
    
    .page-title {
        margin: 20px 0;
        text-align: center;
        font-weight: bold;
    }
    
    .order-info-cards {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin: 20px 0;
    }
    
    .info-card {
        flex: 1 0 45%;
        background-color: white;
        border-radius: 15px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .card-title {
        margin-bottom: 15px;
        padding-bottom: 5px;
        border-bottom: 1px solid #eee;
        font-weight: bold;
    }
    
    .info-table {
        margin-bottom: 15px;
    }
    
    .info-row {
        display: flex;
        margin-bottom: 8px;
    }
    
    .info-label {
        font-weight: bold;
        width: 120px;
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
    
    .teeth-list {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    
    .tooth-badge {
        padding: 3px 8px;
        background-color: #f1f1f1;
        border-radius: 15px;
        font-size: 0.8rem;
    }
    
    .color-display {
        display: flex;
        align-items: center;
    }
    
    .color-swatch {
        width: 20px;
        height: 20px;
        border-radius: 4px;
        border: 1px solid #ddd;
        margin-left: 10px;
    }
    
    .progress-section {
        margin-top: 20px;
    }
    
    .progress-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }
    
    .progress-bar-container {
        height: 10px;
        background-color: #f1f1f1;
        border-radius: 5px;
        overflow: hidden;
    }
    
    .progress-bar {
        height: 100%;
        background-color: var(--primary-btn);
        border-radius: 5px;
    }
    
    .steps-section {
        margin: 30px 0;
    }
    
    .section-title {
        margin-bottom: 15px;
        font-weight: bold;
    }
    
    .steps-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .step-card {
        background-color: white;
        border-radius: 15px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .step-card.assigned-to-me {
        border: 2px solid var(--primary-btn);
    }
    
    .step-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 1px solid #eee;
    }
    
    .step-name {
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    .step-details {
        margin-bottom: 15px;
    }
    
    .detail-row {
        display: flex;
        margin-bottom: 5px;
    }
    
    .detail-label {
        font-weight: bold;
        width: 80px;
    }
    
    .employee-badge {
        padding: 3px 8px;
        background-color: #f1f1f1;
        border-radius: 15px;
        font-size: 0.8rem;
        margin-left: 5px;
    }
    
    .step-actions {
        display: flex;
        justify-content: flex-end;
    }
    
    .back-link {
        margin-top: 20px;
        text-align: center;
    }
    
    @media (max-width: 768px) {
        .order-info-cards {
            flex-direction: column;
        }
        
        .info-card {
            flex: 1 0 100%;
        }
        
        .info-row, .detail-row {
            flex-direction: column;
        }
        
        .info-label, .detail-label {
            width: 100%;
            margin-bottom: 3px;
        }
        
        .step-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .step-status {
            margin-top: 5px;
        }
    }
</style>
@endsection
@endsection
