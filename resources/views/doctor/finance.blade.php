@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Finance Record</h4>
                    <a href="{{ route('doctor.dashboard') }}" class="btn btn-sm btn-secondary">Back to Dashboard</a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Account Summary</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1">Total Orders:</p>
                                            <h3 class="text-primary">0</h3>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1">Outstanding Balance:</p>
                                            <h3 class="text-danger">$0.00</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Payment History</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1">Total Paid:</p>
                                            <h3 class="text-success">$0.00</h3>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1">Last Payment:</p>
                                            <h3 class="text-info">N/A</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <ul class="nav nav-tabs mb-4" id="financeTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="unpaid-tab" data-bs-toggle="tab" data-bs-target="#unpaid" type="button" role="tab" aria-controls="unpaid" aria-selected="true">Unpaid Orders</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="paid-tab" data-bs-toggle="tab" data-bs-target="#paid" type="button" role="tab" aria-controls="paid" aria-selected="false">Paid Orders</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="false">All Orders</button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="financeTabsContent">
                        <div class="tab-pane fade show active" id="unpaid" role="tabpanel" aria-labelledby="unpaid-tab">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Patient Name</th>
                                            <th>Procedure</th>
                                            <th>Order Date</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- This will be populated with actual data from the controller -->
                                        <tr>
                                            <td colspan="7" class="text-center">No unpaid orders found.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="paid" role="tabpanel" aria-labelledby="paid-tab">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Patient Name</th>
                                            <th>Procedure</th>
                                            <th>Order Date</th>
                                            <th>Payment Date</th>
                                            <th>Amount</th>
                                            <th>Receipt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- This will be populated with actual data from the controller -->
                                        <tr>
                                            <td colspan="7" class="text-center">No paid orders found.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="all" role="tabpanel" aria-labelledby="all-tab">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Patient Name</th>
                                            <th>Procedure</th>
                                            <th>Order Date</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            <th>Payment Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- This will be populated with actual data from the controller -->
                                        <tr>
                                            <td colspan="7" class="text-center">No orders found.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
