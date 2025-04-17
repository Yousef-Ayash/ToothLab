<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderStep;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderStepController extends Controller
{
    /**
     * Display a listing of the order steps.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'employee') {
            // Employees see only their assigned steps
            $orderSteps = OrderStep::whereHas('employees', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['order', 'step'])
            ->orderBy('due_date')
            ->get();
            
            // Group steps by status
            $pendingSteps = $orderSteps->where('status', 'pending');
            $inProgressSteps = $orderSteps->where('status', 'in_progress');
            $completedSteps = $orderSteps->where('status', 'completed');
            
            return view('employee.dashboard', compact('pendingSteps', 'inProgressSteps', 'completedSteps'));
        } elseif ($user->role === 'admin') {
            // Admins see all steps
            $orderSteps = OrderStep::with(['order', 'step', 'employees'])
                ->orderBy('due_date')
                ->get();
                
            return view('admin.steps.index', compact('orderSteps'));
        }
        
        return redirect()->route('home')->with('error', 'Unauthorized access');
    }

    /**
     * Show the form for assigning employees to a step.
     */
    public function assignForm(OrderStep $orderStep)
    {
        $user = Auth::user();
        
        if ($user->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }
        
        $employees = User::where('role', 'employee')->get();
        $assignedEmployees = $orderStep->employees->pluck('id')->toArray();
        
        return view('admin.steps.assign', compact('orderStep', 'employees', 'assignedEmployees'));
    }

    /**
     * Assign employees to a step.
     */
    public function assign(Request $request, OrderStep $orderStep)
    {
        $user = Auth::user();
        
        if ($user->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }
        
        $validator = Validator::make($request->all(), [
            'employees' => ['required', 'array'],
            'employees.*' => ['exists:users,id'],
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $orderStep->employees()->sync($request->employees);
        
        return redirect()->route('admin.orders.show', $orderStep->order)->with('success', 'Employees assigned successfully');
    }

    /**
     * Update the status of an order step.
     */
    public function updateStatus(Request $request, OrderStep $orderStep)
    {
        $user = Auth::user();
        
        // Check if user is authorized to update this step
        if ($user->role === 'employee') {
            // Employee must be assigned to this step
            $isAssigned = $orderStep->employees()->where('user_id', $user->id)->exists();
            
            if (!$isAssigned) {
                return redirect()->route('home')->with('error', 'Unauthorized access');
            }
        } elseif ($user->role === 'doctor') {
            // Doctor must own the order
            if ($orderStep->order->doctor_id !== $user->id) {
                return redirect()->route('home')->with('error', 'Unauthorized access');
            }
        } elseif ($user->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }
        
        $validator = Validator::make($request->all(), [
            'status' => ['required', 'in:pending,in_progress,completed'],
            'notes' => ['nullable', 'string'],
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
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
        
        if ($user->role === 'employee') {
            return redirect()->route('employee.order.show', $order)->with('success', 'Step status updated successfully');
        } elseif ($user->role === 'doctor') {
            return redirect()->route('doctor.order.show', $order)->with('success', 'Step status updated successfully');
        } else {
            return redirect()->route('admin.orders.show', $order)->with('success', 'Step status updated successfully');
        }
    }
    
    /**
     * Update the due date of an order step.
     */
    public function updateDueDate(Request $request, OrderStep $orderStep)
    {
        $user = Auth::user();
        
        if ($user->role !== 'admin' && $user->role !== 'doctor') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }
        
        if ($user->role === 'doctor' && $orderStep->order->doctor_id !== $user->id) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }
        
        $validator = Validator::make($request->all(), [
            'due_date' => ['required', 'date', 'after:today'],
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $orderStep->update([
            'due_date' => $request->due_date,
        ]);
        
        if ($user->role === 'doctor') {
            return redirect()->route('doctor.order.show', $orderStep->order)->with('success', 'Due date updated successfully');
        } else {
            return redirect()->route('admin.orders.show', $orderStep->order)->with('success', 'Due date updated successfully');
        }
    }
}
