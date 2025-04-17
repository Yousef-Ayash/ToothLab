@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>User Details: {{ $user->name }}</h4>
                    <div>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary me-2">Back to Users</a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">Edit User</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">User Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 30%">ID</th>
                                            <td>{{ $user->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Role</th>
                                            <td>
                                                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'doctor' ? 'primary' : 'success') }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @if($user->role == 'doctor')
                                        <tr>
                                            <th>Center Name</th>
                                            <td>{{ $user->center_name }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{ $user->created_at->format('M d, Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated At</th>
                                            <td>{{ $user->updated_at->format('M d, Y H:i:s') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            @if($user->role == 'doctor')
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Doctor Statistics</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-4 mb-3">
                                            <div class="border rounded p-3">
                                                <h6>Total Orders</h6>
                                                <h3 class="text-primary">0</h3>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="border rounded p-3">
                                                <h6>In Progress</h6>
                                                <h3 class="text-warning">0</h3>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="border rounded p-3">
                                                <h6>Completed</h6>
                                                <h3 class="text-success">0</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @elseif($user->role == 'employee')
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Employee Statistics</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-6 mb-3">
                                            <div class="border rounded p-3">
                                                <h6>Assigned Steps</h6>
                                                <h3 class="text-primary">0</h3>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="border rounded p-3">
                                                <h6>Completed Steps</h6>
                                                <h3 class="text-success">0</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Account Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Edit User
                                        </a>
                                        @if($user->id !== auth()->id())
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash"></i> Delete User
                                        </button>
                                        @else
                                        <button type="button" class="btn btn-danger" disabled>
                                            <i class="fas fa-trash"></i> Cannot Delete Own Account
                                        </button>
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
</div>

<!-- Delete Modal -->
@if($user->id !== auth()->id())
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete user <strong>{{ $user->name }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection
@endsection
