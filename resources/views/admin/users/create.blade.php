@extends('layouts.app')

@section('content')
<div class="container main-content">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="mb-md-0 mb-2">{{ isset($user) ? 'Edit User' : 'Create New User' }}</h4>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">Back to Users</a>
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
                    
                    <form method="POST" action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}">
                        @csrf
                        @if(isset($user))
                            @method('PUT')
                        @endif
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ isset($user) ? $user->name : old('name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ isset($user) ? $user->email : old('email') }}" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">{{ isset($user) ? 'New Password (leave blank to keep current)' : 'Password' }}</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" {{ isset($user) ? '' : 'required' }}>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">{{ isset($user) ? 'Confirm New Password' : 'Confirm Password' }}</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" {{ isset($user) ? '' : 'required' }}>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="doctor" {{ (isset($user) && $user->role == 'doctor') || old('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                                    <option value="admin" {{ (isset($user) && $user->role == 'admin') || old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="employee" {{ (isset($user) && $user->role == 'employee') || old('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone (Optional)</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ isset($user) ? $user->phone : old('phone') }}">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="address" class="form-label">Address (Optional)</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ isset($user) ? $user->address : old('address') }}</textarea>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">{{ isset($user) ? 'Update User' : 'Create User' }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Navigation -->
<div class="mobile-nav">
    <div class="nav-items">
        <div class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i>
                <span>Orders</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.procedures.index') }}" class="nav-link {{ request()->routeIs('admin.procedures.*') ? 'active' : '' }}">
                <i class="fas fa-tooth"></i>
                <span>Procedures</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.colors.index') }}" class="nav-link {{ request()->routeIs('admin.colors.*') ? 'active' : '' }}">
                <i class="fas fa-palette"></i>
                <span>Colors</span>
            </a>
        </div>
    </div>
</div>

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection
@endsection
