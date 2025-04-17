@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Edit Color: {{ $color->name }}</h4>
                    <a href="{{ route('admin.colors.index') }}" class="btn btn-sm btn-secondary">Back to Colors</a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('admin.colors.update', $color) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Color Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $color->name) }}" required>
                                <small class="form-text text-muted">Enter a unique name for this color</small>
                            </div>
                            <div class="col-md-6">
                                <label for="code" class="form-label">Color Code</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color" id="color-picker" value="{{ old('code', $color->code) }}">
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $color->code) }}" required>
                                </div>
                                <small class="form-text text-muted">Select a color or enter a hex color code (e.g., #FF5733)</small>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="description" class="form-label">Description (Optional)</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $color->description) }}</textarea>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Color Preview</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div id="color-preview" style="width: 100%; height: 100px; border-radius: 4px; border: 1px solid #ddd; background-color: {{ old('code', $color->code) }};"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1">This color is used in:</p>
                                                @if($color->procedures->count() > 0)
                                                    <ul>
                                                        @foreach($color->procedures as $procedure)
                                                            <li>{{ $procedure->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p class="text-muted">This color is not currently used in any procedures.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Update Color</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorPicker = document.getElementById('color-picker');
        const codeInput = document.getElementById('code');
        const colorPreview = document.getElementById('color-preview');
        
        // Update code input when color picker changes
        colorPicker.addEventListener('input', function() {
            codeInput.value = this.value;
            colorPreview.style.backgroundColor = this.value;
        });
        
        // Update color picker when code input changes
        codeInput.addEventListener('input', function() {
            // Validate hex color format
            if (/^#[0-9A-F]{6}$/i.test(this.value)) {
                colorPicker.value = this.value;
                colorPreview.style.backgroundColor = this.value;
            }
        });
    });
</script>
@endsection
@endsection
