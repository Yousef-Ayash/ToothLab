@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>تفاصيل الطلب #{{ $order->id }}</h4>
                <div>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-secondary">العودة إلى الطلبات</a>
                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-warning">تعديل</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>المعرف</th>
                        <td>{{ $order->id }}</td>
                    </tr>
                    <tr>
                        <th>المريض</th>
                        <td>{{ $order->patient_name }}</td>
                    </tr>
                    <tr>
                        <th>الإجراء</th>
                        <td>{{ $order->procedure->name }}</td>
                    </tr>
                    <tr>
                        <th>اللون</th>
                        <td>{{ optional($order->color)->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>الطبيب</th>
                        <td>{{ $order->doctor->name }}</td>
                    </tr>
                    <tr>
                        <th>تاريخ الاستحقاق</th>
                        <td>{{ optional($order->due_date)->format('Y-m-d') }}</td>
                    </tr>
                    <tr>
                        <th>الحالة</th>
                        <td>{{ ucfirst(str_replace('_', ' ', $order->status)) }}</td>
                    </tr>
                    <tr>
                        <th>ملاحظات</th>
                        <td>{{ $order->notes }}</td>
                    </tr>
                    <tr>
                        <th>تعليمات خاصة</th>
                        <td>{{ $order->special_instructions }}</td>
                    </tr>
                </table>

                <h5 class="mt-4">خطوات الطلب</h5>
                @if ($order->orderSteps->count())
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>الخطوة</th>
                                <th>الوصف</th>
                                <th>المسؤولون</th>
                                <th>الحالة</th>
                                <th>تاريخ الاستحقاق</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderSteps as $step)
                                <tr>
                                    <td>{{ $step->step->name }}</td>
                                    <td>{{ $step->step->description }}</td>
                                    <td>
                                        {{ $step->employees->pluck('name')->join(', ') }}
                                    </td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $step->status)) }}</td>
                                    <td>{{ optional($step->due_date)->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">لا توجد خطوات لهذا الطلب.</p>
                @endif
            </div>
        </div>
    </div>

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection
@endsection
