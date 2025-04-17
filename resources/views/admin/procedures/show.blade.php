@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Procedure Details: {{ $procedure->name }}</h4>
                    <div>
                        <a href="{{ route('admin.procedures.index') }}" class="btn btn-sm btn-secondary me-2">Back to Procedures</a>
                        <a href="{{ route('admin.procedures.edit', $procedure) }}" class="btn btn-sm btn-warning">Edit Procedure</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Procedure Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 30%">ID</th>
                                            <td>{{ $procedure->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $procedure->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Category</th>
                                            <td>{{ ucfirst($procedure->category) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Price</th>
                                            <td>${{ number_format($procedure->price, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td>{{ $procedure->description }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{ $procedure->created_at->format('M d, Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated At</th>
                                            <td>{{ $procedure->updated_at->format('M d, Y H:i:s') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Available Colors</h5>
                                </div>
                                <div class="card-body">
                                    @if($procedure->colors->count() > 0)
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($procedure->colors as $color)
                                                <span class="badge bg-secondary p-2">{{ $color->name }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted">No colors assigned to this procedure.</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Procedure Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.procedures.edit', $procedure) }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Edit Procedure
                                        </a>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash"></i> Delete Procedure
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Procedure Steps</h5>
                                </div>
                                <div class="card-body">
                                    @if($procedure->steps->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Order</th>
                                                        <th>Name</th>
                                                        <th>Description</th>
                                                        <th>Duration (hours)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($procedure->steps->sortBy('order') as $step)
                                                        <tr>
                                                            <td>{{ $step->order }}</td>
                                                            <td>{{ $step->name }}</td>
                                                            <td>{{ $step->description }}</td>
                                                            <td>{{ $step->duration }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">No steps defined for this procedure.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete procedure <strong>{{ $procedure->name }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.procedures.destroy', $procedure) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection
@endsection
