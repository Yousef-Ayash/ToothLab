@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Color Details: {{ $color->name }}</h4>
                    <div>
                        <a href="{{ route('admin.colors.index') }}" class="btn btn-sm btn-secondary me-2">Back to Colors</a>
                        <a href="{{ route('admin.colors.edit', $color) }}" class="btn btn-sm btn-warning">Edit Color</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Color Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 30%">ID</th>
                                            <td>{{ $color->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $color->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Color Code</th>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div style="width: 30px; height: 30px; background-color: {{ $color->code }}; border-radius: 4px; border: 1px solid #ddd; margin-right: 10px;"></div>
                                                    <span>{{ $color->code }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td>{{ $color->description ?? 'No description provided' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{ $color->created_at->format('M d, Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated At</th>
                                            <td>{{ $color->updated_at->format('M d, Y H:i:s') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Color Preview</h5>
                                </div>
                                <div class="card-body">
                                    <div style="width: 100%; height: 150px; background-color: {{ $color->code }}; border-radius: 4px; border: 1px solid #ddd;"></div>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Color Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.colors.edit', $color) }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Edit Color
                                        </a>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash"></i> Delete Color
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
                                    <h5 class="mb-0">Used In Procedures</h5>
                                </div>
                                <div class="card-body">
                                    @if($color->procedures->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Procedure Name</th>
                                                        <th>Category</th>
                                                        <th>Price</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($color->procedures as $procedure)
                                                        <tr>
                                                            <td>{{ $procedure->id }}</td>
                                                            <td>{{ $procedure->name }}</td>
                                                            <td>{{ ucfirst($procedure->category) }}</td>
                                                            <td>${{ number_format($procedure->price, 2) }}</td>
                                                            <td>
                                                                <a href="{{ route('admin.procedures.show', $procedure) }}" class="btn btn-sm btn-info text-white">View</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">This color is not currently used in any procedures.</p>
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
                Are you sure you want to delete color <strong>{{ $color->name }}</strong>?
                @if($color->procedures->count() > 0)
                    <div class="alert alert-warning mt-3">
                        <strong>Warning:</strong> This color is used in {{ $color->procedures->count() }} procedures. Deleting it may affect those procedures.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.colors.destroy', $color) }}" method="POST">
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
