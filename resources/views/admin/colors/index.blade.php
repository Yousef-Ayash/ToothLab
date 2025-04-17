@extends('layouts.app')

@section('content')
<div class="admin-colors">
    <div class="logo-container">
        <img src="{{ asset('images/tooth-logo.svg') }}" alt="Brown Dental Lab Logo">
        <div class="logo-text">Brown Dental Lab</div>
        <div class="logo-subtext">BY BROWN DENTAL GROUP</div>
    </div>
    
    <h3 class="page-title">إدارة الألوان</h3>
    
    <div class="action-buttons">
        <a href="{{ route('admin.dashboard') }}" class="btn-dental">
            <i class="fas fa-arrow-right"></i>
            العودة إلى لوحة التحكم
        </a>
        <a href="{{ route('admin.colors.create') }}" class="btn-dental btn-primary">
            <i class="fas fa-plus"></i>
            إضافة لون جديد
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
                    <th>اللون</th>
                    <th>الاسم</th>
                    <th>الوصف</th>
                    <th>مستخدم في</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($colors as $color)
                    <tr>
                        <td>{{ $color->id }}</td>
                        <td>
                            <div class="color-swatch" style="background-color: {{ $color->code }};"></div>
                        </td>
                        <td>{{ $color->name }}</td>
                        <td>{{ $color->description ?? 'غير متوفر' }}</td>
                        <td>{{ $color->procedures->count() }} إجراء</td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('admin.colors.show', $color) }}" class="btn-dental btn-sm">عرض</a>
                                <a href="{{ route('admin.colors.edit', $color) }}" class="btn-dental btn-sm btn-primary">تعديل</a>
                                <button type="button" class="btn-dental btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $color->id }}">
                                    حذف
                                </button>
                            </div>
                            
                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $color->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $color->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $color->id }}">تأكيد الحذف</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            هل أنت متأكد من رغبتك في حذف اللون <strong>{{ $color->name }}</strong>؟
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn-cancel" data-bs-dismiss="modal">إلغاء</button>
                                            <form action="{{ route('admin.colors.destroy', $color) }}" method="POST">
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
                        <td colspan="6" class="text-center">لم يتم العثور على ألوان.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@section('styles')
<style>
    .admin-colors {
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
    
    .color-swatch {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 1px solid #ddd;
        box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
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
