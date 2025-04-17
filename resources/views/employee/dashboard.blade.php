@extends('layouts.app')

@section('content')
<div class="employee-dashboard">
    <div class="logo-container">
        <img src="{{ asset('images/tooth-logo.svg') }}" alt="Brown Dental Lab Logo">
        <div class="logo-text">Brown Dental Lab</div>
        <div class="logo-subtext">BY BROWN DENTAL GROUP</div>
    </div>
    
    <div class="status-cards">
        <div class="status-card pending">
            <h3>الطلبات المعلقة</h3>
            <div class="count">{{ $pendingSteps->count() }}</div>
            <a href="#pending-section" class="btn-dental btn-primary">عرض المعلقة</a>
        </div>
        
        <div class="status-card in-progress">
            <h3>قيد التنفيذ</h3>
            <div class="count">{{ $inProgressSteps->count() }}</div>
            <a href="#in-progress-section" class="btn-dental btn-primary">عرض الجارية</a>
        </div>
        
        <div class="status-card completed">
            <h3>المكتملة</h3>
            <div class="count">{{ $completedSteps->count() }}</div>
            <a href="#completed-section" class="btn-dental btn-primary">عرض المكتملة</a>
        </div>
    </div>
    
    <div class="orders-section" id="pending-section">
        <h3>الطلبات المعلقة</h3>
        @if($pendingSteps->count() > 0)
            <div class="orders-list">
                @foreach($pendingSteps as $orderStep)
                    <div class="order-card">
                        <div class="order-info">
                            <div class="order-number">طلب #{{ $orderStep->order->id }}</div>
                            <div class="step-name">{{ $orderStep->step->name }}</div>
                            <div class="due-date">{{ $orderStep->due_date ? $orderStep->due_date->format('Y/m/d') : 'غير محدد' }}</div>
                        </div>
                        <div class="order-actions">
                            <a href="{{ route('employee.order.show', $orderStep->order) }}" class="btn-dental">عرض التفاصيل</a>
                            <button type="button" class="btn-dental btn-primary" data-bs-toggle="modal" data-bs-target="#startStepModal{{ $orderStep->id }}">
                                بدء العمل
                            </button>
                        </div>
                    </div>
                    
                    <!-- Start Step Modal -->
                    <div class="modal fade" id="startStepModal{{ $orderStep->id }}" tabindex="-1" aria-labelledby="startStepModalLabel{{ $orderStep->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="startStepModalLabel{{ $orderStep->id }}">بدء العمل: {{ $orderStep->step->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('employee.step.update', $orderStep) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <p>أنت على وشك البدء في العمل على هذه الخطوة للطلب #{{ $orderStep->order->id }}.</p>
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
                @endforeach
            </div>
        @else
            <div class="alert alert-info">
                ليس لديك أي خطوات معلقة مخصصة لك.
            </div>
        @endif
    </div>
    
    <div class="orders-section" id="in-progress-section">
        <h3>الطلبات قيد التنفيذ</h3>
        @if($inProgressSteps->count() > 0)
            <div class="orders-list">
                @foreach($inProgressSteps as $orderStep)
                    <div class="order-card">
                        <div class="order-info">
                            <div class="order-number">طلب #{{ $orderStep->order->id }}</div>
                            <div class="step-name">{{ $orderStep->step->name }}</div>
                            <div class="due-date">{{ $orderStep->due_date ? $orderStep->due_date->format('Y/m/d') : 'غير محدد' }}</div>
                        </div>
                        <div class="order-actions">
                            <a href="{{ route('employee.order.show', $orderStep->order) }}" class="btn-dental">عرض التفاصيل</a>
                            <button type="button" class="btn-dental btn-primary" data-bs-toggle="modal" data-bs-target="#completeStepModal{{ $orderStep->id }}">
                                إكمال العمل
                            </button>
                        </div>
                    </div>
                    
                    <!-- Complete Step Modal -->
                    <div class="modal fade" id="completeStepModal{{ $orderStep->id }}" tabindex="-1" aria-labelledby="completeStepModalLabel{{ $orderStep->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="completeStepModalLabel{{ $orderStep->id }}">إكمال العمل: {{ $orderStep->step->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('employee.step.update', $orderStep) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <p>أنت على وشك وضع علامة على هذه الخطوة كمكتملة للطلب #{{ $orderStep->order->id }}.</p>
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
                @endforeach
            </div>
        @else
            <div class="alert alert-info">
                ليس لديك أي خطوات قيد التنفيذ.
            </div>
        @endif
    </div>
    
    <div class="orders-section" id="completed-section">
        <h3>الطلبات المكتملة مؤخراً</h3>
        @if($completedSteps->count() > 0)
            <div class="orders-list">
                @foreach($completedSteps as $orderStep)
                    <div class="order-card completed">
                        <div class="order-info">
                            <div class="order-number">طلب #{{ $orderStep->order->id }}</div>
                            <div class="step-name">{{ $orderStep->step->name }}</div>
                            <div class="completed-date">{{ $orderStep->completed_at ? $orderStep->completed_at->format('Y/m/d') : 'غير محدد' }}</div>
                        </div>
                        <div class="order-actions">
                            <a href="{{ route('employee.order.show', $orderStep->order) }}" class="btn-dental">عرض التفاصيل</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">
                ليس لديك أي خطوات مكتملة مؤخراً.
            </div>
        @endif
    </div>
</div>

@section('styles')
<style>
    .employee-dashboard {
        padding: 20px 0;
    }
    
    .status-cards {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin: 20px 0;
    }
    
    .status-card {
        flex: 1 0 30%;
        margin: 10px;
        padding: 15px;
        border-radius: 15px;
        text-align: center;
        background-color: white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .status-card h3 {
        font-size: 1.2rem;
        margin-bottom: 10px;
    }
    
    .status-card .count {
        font-size: 2rem;
        font-weight: bold;
        margin: 15px 0;
    }
    
    .orders-section {
        margin: 30px 0;
    }
    
    .orders-section h3 {
        margin-bottom: 15px;
        padding-bottom: 5px;
        border-bottom: 2px solid var(--border-color);
    }
    
    .orders-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .order-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .order-card.completed {
        background-color: rgba(76, 175, 80, 0.1);
    }
    
    .order-info {
        flex: 1;
    }
    
    .order-number {
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    .order-actions {
        display: flex;
        gap: 10px;
    }
    
    @media (max-width: 768px) {
        .status-cards {
            flex-direction: column;
        }
        
        .status-card {
            margin: 5px 0;
        }
        
        .order-card {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .order-actions {
            margin-top: 10px;
            width: 100%;
            justify-content: space-between;
        }
    }
</style>
@endsection
@endsection
