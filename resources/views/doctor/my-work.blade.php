@extends('layouts.app')

@section('content')
<div class="container main-content">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="mb-md-0 mb-2">My Work</h4>
                    <div>
                        <a href="{{ route('doctor.new-order') }}" class="btn btn-sm btn-primary">New Order</a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <ul class="nav nav-tabs mb-3" id="orderTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">
                                Pending
                                <span class="badge bg-primary">{{ $orders->where('status', 'pending')->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="in-progress-tab" data-bs-toggle="tab" data-bs-target="#in-progress" type="button" role="tab" aria-controls="in-progress" aria-selected="false">
                                In Progress
                                <span class="badge bg-warning text-dark">{{ $orders->where('status', 'in_progress')->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">
                                Completed
                                <span class="badge bg-success">{{ $orders->where('status', 'completed')->count() }}</span>
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="orderTabsContent">
                        <!-- Pending Orders Tab -->
                        <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                            @if($orders->where('status', 'pending')->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Patient</th>
                                                <th>Procedure</th>
                                                <th>Created</th>
                                                <th>Due Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orders->where('status', 'pending') as $order)
                                                <tr>
                                                    <td>{{ $order->id }}</td>
                                                    <td>{{ $order->patient_name }}</td>
                                                    <td>{{ $order->procedure->name }}</td>
                                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                    <td>{{ $order->due_date ? $order->due_date->format('M d, Y') : 'Not set' }}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="{{ route('doctor.order.show', $order) }}" class="btn btn-sm btn-info text-white">View</a>
                                                            <a href="{{ route('doctor.orders.edit', $order) }}" class="btn btn-sm btn-warning">Edit</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    You don't have any pending orders.
                                </div>
                            @endif
                        </div>
                        
                        <!-- In Progress Orders Tab -->
                        <div class="tab-pane fade" id="in-progress" role="tabpanel" aria-labelledby="in-progress-tab">
                            @if($orders->where('status', 'in_progress')->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Patient</th>
                                                <th>Procedure</th>
                                                <th>Created</th>
                                                <th>Due Date</th>
                                                <th>Progress</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orders->where('status', 'in_progress') as $order)
                                                @php
                                                    $totalSteps = $order->orderSteps->count();
                                                    $completedSteps = $order->orderSteps->where('status', 'completed')->count();
                                                    $progressPercentage = $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;
                                                @endphp
                                                <tr>
                                                    <td>{{ $order->id }}</td>
                                                    <td>{{ $order->patient_name }}</td>
                                                    <td>{{ $order->procedure->name }}</td>
                                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                    <td>{{ $order->due_date ? $order->due_date->format('M d, Y') : 'Not set' }}</td>
                                                    <td>
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progressPercentage }}%" aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100">{{ $progressPercentage }}%</div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('doctor.order.show', $order) }}" class="btn btn-sm btn-info text-white">View</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    You don't have any orders in progress.
                                </div>
                            @endif
                        </div>
                        
                        <!-- Completed Orders Tab -->
                        <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                            @if($orders->where('status', 'completed')->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Patient</th>
                                                <th>Procedure</th>
                                                <th>Created</th>
                                                <th>Completed</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orders->where('status', 'completed') as $order)
                                                <tr>
                                                    <td>{{ $order->id }}</td>
                                                    <td>{{ $order->patient_name }}</td>
                                                    <td>{{ $order->procedure->name }}</td>
                                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                    <td>{{ $order->updated_at->format('M d, Y') }}</td>
                                                    <td>
                                                        <a href="{{ route('doctor.order.show', $order) }}" class="btn btn-sm btn-info text-white">View</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    You don't have any completed orders.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Navigation -->
<div class="mobile-nav">
    <div class="nav-items">
        <div class="nav-item">
            <a href="{{ route('doctor.dashboard') }}" class="nav-link {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.my-work') }}" class="nav-link {{ request()->routeIs('doctor.my-work') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i>
                <span>My Work</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.new-order') }}" class="nav-link {{ request()->routeIs('doctor.new-order') ? 'active' : '' }}">
                <i class="fas fa-plus-circle"></i>
                <span>New Order</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.finance') }}" class="nav-link {{ request()->routeIs('doctor.finance') ? 'active' : '' }}">
                <i class="fas fa-dollar-sign"></i>
                <span>Finance</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.contact') }}" class="nav-link {{ request()->routeIs('doctor.contact') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i>
                <span>Contact</span>
            </a>
        </div>
    </div>
</div>

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection
@endsection
