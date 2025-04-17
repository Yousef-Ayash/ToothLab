@extends('layouts.app')

@section('content')
<div class="order-form">
    <form method="POST" action="{{ route('doctor.orders.store') }}">
        @csrf
        
        <div class="form-row">
            <div class="form-group">
                <label for="center_name" class="form-label">اسم المركز</label>
                <input type="text" class="form-control @error('center_name') is-invalid @enderror" id="center_name" name="center_name" value="{{ old('center_name') }}" required>
            </div>
            <div class="form-group">
                <label for="order_number" class="form-label">رقم الطلب</label>
                <input type="text" class="form-control @error('order_number') is-invalid @enderror" id="order_number" name="order_number" value="{{ old('order_number') }}" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="patient_name" class="form-label">اسم المريض</label>
                <input type="text" class="form-control @error('patient_name') is-invalid @enderror" id="patient_name" name="patient_name" value="{{ old('patient_name') }}" required>
            </div>
            <div class="form-group">
                <label for="doctor_name" class="form-label">اسم الطبيب</label>
                <input type="text" class="form-control @error('doctor_name') is-invalid @enderror" id="doctor_name" name="doctor_name" value="{{ old('doctor_name', Auth::user()->name) }}" required>
            </div>
        </div>
        
        <div class="tooth-diagram">
            <img src="{{ asset('images/tooth-diagram.svg') }}" alt="Tooth Diagram">
            
            <div class="upper-text">علوي</div>
            <div class="lower-text">سفلي</div>
            <div class="left-text">يمين</div>
            <div class="right-text">يسار</div>
            
            @foreach($teeth as $tooth)
                <div class="tooth" style="top: {{ $tooth->position_y }}%; left: {{ $tooth->position_x }}%;" 
                     data-tooth-id="{{ $tooth->id }}" data-tooth-number="{{ $tooth->number }}">
                    {{ $tooth->number }}
                    <input type="checkbox" name="teeth[]" value="{{ $tooth->id }}" class="d-none">
                </div>
            @endforeach
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="notes" class="form-label">ملاحظات</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
            </div>
            <div class="form-group">
                <label for="color_id" class="form-label">اللون</label>
                <select class="form-control @error('color_id') is-invalid @enderror" id="color_id" name="color_id">
                    <option value="">اختر اللون</option>
                    @foreach($colors as $color)
                        <option value="{{ $color->id }}" {{ old('color_id') == $color->id ? 'selected' : '' }}
                            data-color="{{ $color->code }}">
                            {{ $color->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="btn-row">
            <button type="button" class="btn-cancel">الغاء الطلب</button>
            <button type="submit" class="btn-submit">ارسال الطلب</button>
        </div>
    </form>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tooth selection functionality
        const teeth = document.querySelectorAll('.tooth');
        teeth.forEach(tooth => {
            tooth.addEventListener('click', function() {
                const checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
                this.classList.toggle('selected', checkbox.checked);
            });
        });
    });
</script>
@endsection
@endsection
