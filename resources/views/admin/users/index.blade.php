@extends('layouts.app')

@section('content')
<div class="admin-users">
    <div class="logo-container">
        <img src="{{ asset('images/tooth-logo.svg') }}" alt="Brown Dental Lab Logo">
        <div class="logo-text">Brown Dental Lab</div>
        <div class="logo-subtext">BY BROWN DENTAL GROUP</div>
    </div>
    
    <h3 class="page-title">إدارة المستخدمين</h3>
    
    <div class="action-buttons">
        <a href="{{ route('admin.dashboard') }}" class="btn-dental">
            <i class="fas fa-arrow-right"></i>
            العودة إلى لوحة التحكم
        </a>
        <a href="{{ route('admin.users.create') }}" class="btn-dental btn-primary">
            <i class="fas fa-plus"></i>
            إضافة مستخدم جديد
        </a>
    </div>
    
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
    
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>الرقم</th>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الدور</th>
                    <th>اسم المركز</th>
                    <th>تاريخ الإنشاء</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="role-badge {{ $user->role }}">
                                {{ $user->role == 'admin' ? 'مدير' : ($user->role == 'doctor' ? 'طبيب' : 'موظف') }}
                            </span>
                        </td>
                        <td>{{ $user->center_name ?? 'غير متوفر' }}</td>
                        <td>{{ $user->created_at->format('Y/m/d') }}</td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn-dental btn-sm">عرض</a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn-dental btn-sm btn-primary">تعديل</a>
                                <button type="button" class="btn-dental btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                    حذف
                                </button>
                            </div>
                            
                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">تأكيد الحذف</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            هل أنت متأكد من رغبتك في حذف المستخدم <strong>{{ $user->name }}</strong>؟
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn-cancel" data-bs-dismiss="modal">إلغاء</button>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-submit btn-danger">حذف</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">لم يتم العثور على مستخدمين.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@section('styles')
<style>
    .admin-users {
        padding: 20px 0;
    }
    
    .page-title {
        margin: 20px 0;
        text-align: center;
        font-weight: bold;
    }
    
    .action-buttons {
        display: flex;
        justify-content: space-between;
        margin: 20px 0;
    }
    
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        background-color: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .admin-table th, 
    .admin-table td {
        padding: 12px 15px;
        text-align: right;
    }
    
    .admin-table th {
        background-color: var(--primary-bg);
        font-weight: bold;
    }
    
    .admin-table tr:nth-child(even) {
        background-color: rgba(245, 242, 227, 0.3);
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
    
    .action-group {
        display: flex;
        gap: 5px;
    }
    
    .btn-dental.btn-sm {
        padding: 5px 10px;
        font-size: 0.8rem;
    }
    
    .btn-dental.btn-danger {
        background-color: var(--danger-btn);
        color: white;
    }
    
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
            gap: 10px;
        }
        
        .action-group {
            flex-direction: column;
            gap: 5px;
        }
    }
</style>
@endsection
@endsection
