<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderStep;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the employee dashboard.
     */
    public function index()
    {
        $employee = Auth::user();
        
        // Get assigned steps for the employee
        $assignedSteps = OrderStep::whereHas('employees', function($query) use ($employee) {
            $query->where('user_id', $employee->id);
        })
        ->with(['order', 'step'])
        ->orderBy('due_date')
        ->get();
        
        // Group steps by status
        $pendingSteps = $assignedSteps->where('status', 'pending');
        $inProgressSteps = $assignedSteps->where('status', 'in_progress');
        $completedSteps = $assignedSteps->where('status', 'completed');
        
        return view('employee.dashboard', compact('pendingSteps', 'inProgressSteps', 'completedSteps'));
    }
    
    /**
     * Display all orders for the employee.
     */
    public function orders()
    {
        $employee = Auth::user();
        
        // Get all orders that have steps assigned to this employee
        $orders = Order::whereHas('orderSteps.employees', function($query) use ($employee) {
            $query->where('user_id', $employee->id);
        })
        ->with(['doctor', 'orderSteps.step', 'orderSteps.employees'])
        ->orderBy('created_at', 'desc')
        ->get();
        
        return view('employee.orders', compact('orders'));
    }
    
    /**
     * Display a specific order with its steps.
     */
    public function showOrder(Order $order)
    {
        $employee = Auth::user();
        
        // Check if employee is assigned to any step in this order
        $isAssigned = $order->orderSteps()->whereHas('employees', function($query) use ($employee) {
            $query->where('user_id', $employee->id);
        })->exists();
        
        if (!$isAssigned) {
            return redirect()->route('employee.orders')->with('error', 'You are not assigned to this order');
        }
        
        $order->load(['doctor', 'procedure', 'teeth', 'color', 'orderSteps.step', 'orderSteps.employees']);
        
        return view('employee.order-details', compact('order'));
    }
    
    /**
     * Update the status of an order step.
     */
    public function updateStepStatus(Request $request, OrderStep $orderStep)
    {
        $employee = Auth::user();
        
        // Check if employee is assigned to this step
        $isAssigned = $orderStep->employees()->where('user_id', $employee->id)->exists();
        
        if (!$isAssigned) {
            return redirect()->route('employee.orders')->with('error', 'You are not assigned to this step');
        }
        
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
            'notes' => 'nullable|string',
        ]);
        
        $orderStep->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'completed_at' => $request->status === 'completed' ? now() : null,
        ]);
        
        // If all steps are completed, update order status
        $order = $orderStep->order;
        $allStepsCompleted = $order->orderSteps()->where('status', '!=', 'completed')->count() === 0;
        
        if ($allStepsCompleted) {
            $order->update(['status' => 'completed']);
        } elseif ($order->status === 'pending' && $request->status === 'in_progress') {
            $order->update(['status' => 'in_progress']);
        }
        
        return redirect()->route('employee.order.show', $order)->with('success', 'Step status updated successfully');
    }
}
