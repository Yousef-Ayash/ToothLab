@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="mb-4">إضافة طلب جديد</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.orders.store') }}">
            @csrf

            <div class="mb-3">
                <label for="doctor_id" class="form-label">الطبيب</label>
                <select name="doctor_id" id="doctor_id" class="form-select" required>
                    <option value="">اختر الطبيب</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                            {{ $doctor->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="patient_name" class="form-label">اسم المريض</label>
                <input type="text" name="patient_name" id="patient_name" class="form-control"
                    value="{{ old('patient_name') }}" required>
            </div>

            <div class="mb-3">
                <label for="procedure_id" class="form-label">الإجراء</label>
                <select name="procedure_id" id="procedure_id" class="form-select" required>
                    <option value="">اختر الإجراء</option>
                    @foreach ($procedures as $procedure)
                        <option value="{{ $procedure->id }}" {{ old('procedure_id') == $procedure->id ? 'selected' : '' }}>
                            {{ $procedure->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">الأسنان</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($teeth as $tooth)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="teeth[]" id="tooth{{ $tooth->id }}"
                                value="{{ $tooth->id }}"
                                {{ is_array(old('teeth')) && in_array($tooth->id, old('teeth')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="tooth{{ $tooth->id }}">{{ $tooth->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-3">
                <label for="color_id" class="form-label">اللون</label>
                <select name="color_id" id="color_id" class="form-select">
                    <option value="">بدون لون</option>
                    @foreach ($colors as $color)
                        <option value="{{ $color->id }}" {{ old('color_id') == $color->id ? 'selected' : '' }}>
                            {{ $color->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="due_date" class="form-label">تاريخ الاستحقاق</label>
                <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date') }}">
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">ملاحظات</label>
                <textarea name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="special_instructions" class="form-label">تعليمات خاصة</label>
                <textarea name="special_instructions" id="special_instructions" class="form-control">{{ old('special_instructions') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">حفظ</button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>


@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection
@endsection
