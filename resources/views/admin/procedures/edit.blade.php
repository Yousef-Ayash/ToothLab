@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Edit Procedure: {{ $procedure->name }}</h4>
                    <a href="{{ route('admin.procedures.index') }}" class="btn btn-sm btn-secondary">Back to Procedures</a>
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
                    
                    <form method="POST" action="{{ route('admin.procedures.update', $procedure) }}" id="procedureForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Procedure Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $procedure->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                    <option value="">Select a category</option>
                                    <option value="crown" {{ old('category', $procedure->category) == 'crown' ? 'selected' : '' }}>Crown</option>
                                    <option value="bridge" {{ old('category', $procedure->category) == 'bridge' ? 'selected' : '' }}>Bridge</option>
                                    <option value="veneer" {{ old('category', $procedure->category) == 'veneer' ? 'selected' : '' }}>Veneer</option>
                                    <option value="implant" {{ old('category', $procedure->category) == 'implant' ? 'selected' : '' }}>Implant</option>
                                    <option value="denture" {{ old('category', $procedure->category) == 'denture' ? 'selected' : '' }}>Denture</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="price" class="form-label">Price ($)</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $procedure->price) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="colors" class="form-label">Available Colors</label>
                                <div class="border rounded p-3">
                                    @foreach($colors as $color)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="color_ids[]" value="{{ $color->id }}" id="color{{ $color->id }}" 
                                                {{ (old('color_ids') && in_array($color->id, old('color_ids'))) || 
                                                   (!old('color_ids') && $procedure->colors->contains($color->id)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="color{{ $color->id }}">
                                                {{ $color->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description', $procedure->description) }}</textarea>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h5>Procedure Steps</h5>
                        <p class="text-muted">Define the steps required to complete this procedure</p>
                        
                        <div id="steps-container">
                            <!-- Steps will be added here dynamically -->
                            @if(old('steps'))
                                @foreach(old('steps') as $index => $step)
                                    <div class="step-item card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="step-number">Step {{ $index + 1 }}</h6>
                                                <button type="button" class="btn btn-sm btn-danger remove-step">Remove</button>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Step Name</label>
                                                    <input type="text" class="form-control" name="steps[{{ $index }}][name]" value="{{ $step['name'] }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Duration (hours)</label>
                                                    <input type="number" min="1" class="form-control" name="steps[{{ $index }}][duration]" value="{{ $step['duration'] }}" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <label class="form-label">Description</label>
                                                    <textarea class="form-control" name="steps[{{ $index }}][description]" rows="2" required>{{ $step['description'] }}</textarea>
                                                </div>
                                            </div>
                                            <input type="hidden" name="steps[{{ $index }}][order]" value="{{ $index + 1 }}" class="step-order">
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                @foreach($procedure->steps->sortBy('order') as $index => $step)
                                    <div class="step-item card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="step-number">Step {{ $index + 1 }}</h6>
                                                <button type="button" class="btn btn-sm btn-danger remove-step">Remove</button>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Step Name</label>
                                                    <input type="text" class="form-control" name="steps[{{ $index }}][name]" value="{{ $step->name }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Duration (hours)</label>
                                                    <input type="number" min="1" class="form-control" name="steps[{{ $index }}][duration]" value="{{ $step->duration }}" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <label class="form-label">Description</label>
                                                    <textarea class="form-control" name="steps[{{ $index }}][description]" rows="2" required>{{ $step->description }}</textarea>
                                                </div>
                                            </div>
                                            <input type="hidden" name="steps[{{ $index }}][order]" value="{{ $index + 1 }}" class="step-order">
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="button" id="add-step" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Add Step
                                </button>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Update Procedure</button>
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
        const stepsContainer = document.getElementById('steps-container');
        const addStepButton = document.getElementById('add-step');
        
        // Add step button click handler
        addStepButton.addEventListener('click', function() {
            const stepCount = stepsContainer.querySelectorAll('.step-item').length;
            const newStepIndex = stepCount;
            
            const stepItem = document.createElement('div');
            stepItem.className = 'step-item card mb-3';
            stepItem.innerHTML = `
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="step-number">Step ${newStepIndex + 1}</h6>
                        <button type="button" class="btn btn-sm btn-danger remove-step">Remove</button>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Step Name</label>
                            <input type="text" class="form-control" name="steps[${newStepIndex}][name]" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Duration (hours)</label>
                            <input type="number" min="1" class="form-control" name="steps[${newStepIndex}][duration]" value="1" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="steps[${newStepIndex}][description]" rows="2" required></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="steps[${newStepIndex}][order]" value="${newStepIndex + 1}" class="step-order">
                </div>
            `;
            
            stepsContainer.appendChild(stepItem);
            updateStepNumbers();
            
            // Add event listener to the new remove button
            const removeButton = stepItem.querySelector('.remove-step');
            removeButton.addEventListener('click', function() {
                stepItem.remove();
                updateStepNumbers();
            });
        });
        
        // Add event listeners to existing remove buttons
        document.querySelectorAll('.remove-step').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.step-item').remove();
                updateStepNumbers();
            });
        });
        
        // If no steps exist yet, add one
        if (stepsContainer.querySelectorAll('.step-item').length === 0) {
            addStepButton.click();
        }
        
        // Function to update step numbers and order values
        function updateStepNumbers() {
            const steps = stepsContainer.querySelectorAll('.step-item');
            steps.forEach((step, index) => {
                step.querySelector('.step-number').textContent = `Step ${index + 1}`;
                
                // Update all input names and order values
                const inputs = step.querySelectorAll('input, textarea');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        const newName = name.replace(/steps\[\d+\]/, `steps[${index}]`);
                        input.setAttribute('name', newName);
                    }
                });
                
                const orderInput = step.querySelector('.step-order');
                if (orderInput) {
                    orderInput.value = index + 1;
                    orderInput.setAttribute('name', `steps[${index}][order]`);
                }
            });
            
            // Disable form submission if no steps
            const submitButton = document.querySelector('button[type="submit"]');
            submitButton.disabled = steps.length === 0;
        }
        
        // Form validation
        const form = document.getElementById('procedureForm');
        form.addEventListener('submit', function(event) {
            const steps = stepsContainer.querySelectorAll('.step-item');
            if (steps.length === 0) {
                event.preventDefault();
                alert('Please add at least one step to the procedure.');
            }
        });
    });
</script>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection
@endsection
