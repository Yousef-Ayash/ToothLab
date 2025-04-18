@extends('layouts.app')


@extends('layouts.app')

@section('content')
    <div class="admin-orders">
        <!-- logo & page title -->
        <h3 class="page-title">إدارة الطلبات</h3>
        <div class="action-buttons">
            <a href="{{ route('admin.dashboard') }}" class="btn-dental">
                <i class="fas fa-arrow-right"></i> العودة إلى لوحة التحكم
            </a>
            <a href="{{ route('admin.orders.create') }}" class="btn-dental btn-primary">
                <i class="fas fa-plus"></i> إضافة طلب جديد
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>الرقم</th>
                        <th>المريض</th>
                        <th>الإجراء</th>
                        <th>اللون</th>
                        <th>الطبيب</th>
                        <th>الحالة</th>
                        <th>تاريخ الاستحقاق</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->patient_name }}</td>
                            <td>{{ $order->procedure->name }}</td>
                            <td>{{ optional($order->color)->name ?? '—' }}</td>
                            <td>{{ $order->doctor->name }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $order->status)) }}</td>
                            <td>{{ optional($order->due_date)->format('Y-m-d') }}</td>
                            <td class="action-group">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn-dental btn-sm">عرض</a>
                                <a href="{{ route('admin.orders.edit', $order) }}"
                                    class="btn-dental btn-sm btn-primary">تعديل</a>
                                <button class="btn-dental btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $order->id }}">
                                    حذف
                                </button>
                                <!-- Delete confirmation modal (same as Procedures) -->
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">لم يتم العثور على طلبات.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @section('styles')
        <style>
            .admin-procedures {
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

            .color-badges {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
            }

            .color-badge {
                padding: 3px 8px;
                background-color: #f1f1f1;
                border-radius: 15px;
                font-size: 0.8rem;
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
