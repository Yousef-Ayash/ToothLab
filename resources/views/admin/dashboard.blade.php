@extends('layouts.app')

@section('content')
<div class="admin-dashboard">
    <div class="logo-container">
        <img src="{{ asset('images/tooth-logo.svg') }}" alt="Brown Dental Lab Logo">
        <div class="logo-text">Brown Dental Lab</div>
        <div class="logo-subtext">BY BROWN DENTAL GROUP</div>
    </div>
    
    <h3 class="page-title">لوحة تحكم المدير</h3>
    
    <div class="admin-cards">
        <div class="admin-card">
            <div class="card-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-content">
                <h4>إدارة المستخدمين</h4>
                <p>إدارة الأطباء والمديرين والموظفين</p>
                <a href="{{ route('admin.users.index') }}" class="btn-dental">إدارة المستخدمين</a>
            </div>
        </div>
        
        <div class="admin-card">
            <div class="card-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="card-content">
                <h4>الإجراءات</h4>
                <p>إدارة إجراءات الأسنان والخطوات</p>
                <a href="{{ route('admin.procedures.index') }}" class="btn-dental">إدارة الإجراءات</a>
            </div>
        </div>
        
        <div class="admin-card">
            <div class="card-icon">
                <i class="fas fa-palette"></i>
            </div>
            <div class="card-content">
                <h4>الألوان</h4>
                <p>إدارة خيارات الألوان للإجراءات</p>
                <a href="{{ route('admin.colors.index') }}" class="btn-dental">إدارة الألوان</a>
            </div>
        </div>
        
        <div class="admin-card">
            <div class="card-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="card-content">
                <h4>الطلبات</h4>
                <p>عرض وإدارة جميع الطلبات</p>
                <a href="{{ route('admin.orders.index') }}" class="btn-dental">إدارة الطلبات</a>
            </div>
        </div>
    </div>
    
    <div class="admin-sections">
        <div class="admin-section">
            <h4 class="section-title">أحدث الطلبات</h4>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>رقم الطلب</th>
                            <th>الطبيب</th>
                            <th>المريض</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(App\Models\Order::with('doctor')->latest()->take(5)->get() as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->doctor->name }}</td>
                                <td>{{ $order->patient_name }}</td>
                                <td>
                                    <span class="status-badge {{ $order->status == 'completed' ? 'completed' : ($order->status == 'in_progress' ? 'in-progress' : 'pending') }}">
                                        {{ $order->status == 'completed' ? 'مكتمل' : ($order->status == 'in_progress' ? 'قيد التنفيذ' : 'معلق') }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn-dental btn-sm">عرض</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="view-all">
                <a href="{{ route('admin.orders.index') }}" class="btn-dental">عرض جميع الطلبات</a>
            </div>
        </div>
        
        <div class="admin-section">
            <h4 class="section-title">أحدث المستخدمين</h4>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الدور</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(App\Models\User::latest()->take(5)->get() as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="role-badge {{ $user->role }}">
                                        {{ $user->role == 'admin' ? 'مدير' : ($user->role == 'doctor' ? 'طبيب' : 'موظف') }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn-dental btn-sm">عرض</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="view-all">
                <a href="{{ route('admin.users.index') }}" class="btn-dental">عرض جميع المستخدمين</a>
            </div>
        </div>
    </div>
</div>

@section('styles')
<style>
    .admin-dashboard {
        padding: 20px 0;
    }
    
    .page-title {
        margin: 20px 0;
        text-align: center;
        font-weight: bold;
    }
    
    .admin-cards {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin: 30px 0;
    }
    
    .admin-card {
        flex: 1 0 calc(25% - 20px);
        min-width: 250px;
        background-color: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
    }
    
    .card-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: var(--primary-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 15px;
    }
    
    .card-icon i {
        font-size: 24px;
        color: var(--text-color);
    }
    
    .card-content {
        flex: 1;
    }
    
    .card-content h4 {
        margin-bottom: 5px;
        font-weight: bold;
    }
    
    .card-content p {
        margin-bottom: 15px;
        color: #666;
    }
    
    .admin-sections {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin: 30px 0;
    }
    
    .admin-section {
        flex: 1 0 calc(50% - 20px);
        min-width: 300px;
        background-color: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .section-title {
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
        font-weight: bold;
    }
    
    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .admin-table th, 
    .admin-table td {
        padding: 10px;
        text-align: right;
        border-bottom: 1px solid #eee;
    }
    
    .admin-table th {
        font-weight: bold;
        background-color: #f9f9f9;
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
    
    .role-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
    }
    
    .role-badge.admin {
        background-color: #ffebee;
        color: #c62828;
    }
    
    .role-badge.doctor {
        background-color: #e3f2fd;
        color: #0d47a1;
    }
    
    .role-badge.employee {
        background-color: #f5f5f5;
        color: #424242;
    }
    
    .btn-dental.btn-sm {
        padding: 5px 10px;
        font-size: 0.8rem;
    }
    
    .view-all {
        margin-top: 15px;
        text-align: center;
    }
    
    @media (max-width: 768px) {
        .admin-card {
            flex: 1 0 100%;
        }
        
        .admin-section {
            flex: 1 0 100%;
        }
    }
</style>
@endsection
@endsection
