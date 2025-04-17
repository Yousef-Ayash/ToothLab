<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Procedure;
use App\Models\Tooth;
use App\Models\Color;
use App\Models\Step;
use App\Models\OrderStep;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'doctor') {
            // Doctors see only their own orders
            $orders = Order::where('doctor_id', $user->id)
                ->with(['procedure', 'color', 'teeth'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('doctor.my-work', compact('orders'));
        } elseif ($user->role === 'admin') {
            // Admins see all orders
            $orders = Order::with(['doctor', 'procedure', 'color', 'teeth'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('admin.orders.index', compact('orders'));
        } elseif ($user->role === 'employee') {
            // Employees see orders they are assigned to
            $orders = Order::whereHas('orderSteps.employees', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->with(['doctor', 'procedure', 'color', 'teeth', 'orderSteps.employees'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('employee.orders', compact('orders'));
        }

        return redirect()->route('home')->with('error', 'Unauthorized access');
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'doctor' && $user->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $procedures = Procedure::all();
        $teeth = Tooth::all();
        $colors = Color::all();

        return view('doctor.new-order', compact('procedures', 'teeth', 'colors'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'doctor' && $user->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $validator = Validator::make($request->all(), [
            'patient_name' => ['required', 'string', 'max:255'],
            'procedure_id' => ['required', 'exists:procedures,id'],
            'teeth' => ['required', 'array'],
            'teeth.*' => ['exists:teeth,id'],
            'color_id' => ['nullable', 'exists:colors,id'],
            'notes' => ['nullable', 'string'],
            'special_instructions' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date', 'after:today'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create the order
        $order = Order::create([
            'doctor_id' => $user->id,
            'patient_name' => $request->patient_name,
            'procedure_id' => $request->procedure_id,
            'color_id' => $request->color_id,
            'notes' => $request->notes,
            'special_instructions' => $request->special_instructions,
            'due_date' => $request->due_date,
            'status' => 'pending',
        ]);

        // Attach teeth to the order
        $order->teeth()->attach($request->teeth);

        // Create order steps based on procedure steps
        $procedure = Procedure::findOrFail($request->procedure_id);
        $steps = $procedure->steps;

        foreach ($steps as $step) {
            OrderStep::create([
                'order_id' => $order->id,
                'step_id' => $step->id,
                'status' => 'pending',
                'due_date' => $request->due_date ? date('Y-m-d', strtotime($request->due_date . ' -' . ($steps->count() - $step->order) . ' days')) : null,
            ]);
        }

        return redirect()->route('doctor.my-work')->with('success', 'Order created successfully');
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $user = Auth::user();

        if ($user->role === 'doctor' && $order->doctor_id !== $user->id) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        } elseif ($user->role === 'employee') {
            // Check if employee is assigned to any step in this order
            $isAssigned = $order->orderSteps()->whereHas('employees', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->exists();

            if (!$isAssigned) {
                return redirect()->route('home')->with('error', 'Unauthorized access');
            }
        }

        $order->load(['doctor', 'procedure', 'teeth', 'color', 'orderSteps.step', 'orderSteps.employees']);

        if ($user->role === 'doctor') {
            return view('doctor.order-details', compact('order'));
        } elseif ($user->role === 'admin') {
            return view('admin.orders.show', compact('order'));
        } elseif ($user->role === 'employee') {
            return view('employee.order-details', compact('order'));
        }

        return redirect()->route('home')->with('error', 'Unauthorized access');
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        $user = Auth::user();

        if (($user->role === 'doctor' && $order->doctor_id !== $user->id) || $user->role === 'employee') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $procedures = Procedure::all();
        $teeth = Tooth::all();
        $colors = Color::all();
        $selectedTeeth = $order->teeth->pluck('id')->toArray();

        return view('doctor.edit-order', compact('order', 'procedures', 'teeth', 'colors', 'selectedTeeth'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        $user = Auth::user();

        if (($user->role === 'doctor' && $order->doctor_id !== $user->id) || $user->role === 'employee') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $validator = Validator::make($request->all(), [
            'patient_name' => ['required', 'string', 'max:255'],
            'procedure_id' => ['required', 'exists:procedures,id'],
            'teeth' => ['required', 'array'],
            'teeth.*' => ['exists:teeth,id'],
            'color_id' => ['nullable', 'exists:colors,id'],
            'notes' => ['nullable', 'string'],
            'special_instructions' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date', 'after:today'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if procedure is changed
        $procedureChanged = $order->procedure_id != $request->procedure_id;

        // Update the order
        $order->update([
            'patient_name' => $request->patient_name,
            'procedure_id' => $request->procedure_id,
            'color_id' => $request->color_id,
            'notes' => $request->notes,
            'special_instructions' => $request->special_instructions,
            'due_date' => $request->due_date,
        ]);

        // Sync teeth
        $order->teeth()->sync($request->teeth);

        // If procedure changed, recreate order steps
        if ($procedureChanged) {
            // Delete existing order steps
            $order->orderSteps()->delete();

            // Create new order steps based on new procedure
            $procedure = Procedure::findOrFail($request->procedure_id);
            $steps = $procedure->steps;

            foreach ($steps as $step) {
                OrderStep::create([
                    'order_id' => $order->id,
                    'step_id' => $step->id,
                    'status' => 'pending',
                    'due_date' => $request->due_date ? date('Y-m-d', strtotime($request->due_date . ' -' . ($steps->count() - $step->order) . ' days')) : null,
                ]);
            }
        }

        if ($user->role === 'doctor') {
            return redirect()->route('doctor.my-work')->with('success', 'Order updated successfully');
        } else {
            return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully');
        }
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && ($user->role === 'doctor' && $order->doctor_id !== $user->id)) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        // Delete order steps first
        $order->orderSteps()->delete();

        // Detach teeth
        $order->teeth()->detach();

        // Delete the order
        $order->delete();

        if ($user->role === 'doctor') {
            return redirect()->route('doctor.my-work')->with('success', 'Order deleted successfully');
        } else {
            return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully');
        }
    }

    /**
     * Assign employees to order steps.
     */
    public function assignEmployees(Request $request, Order $order)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $validator = Validator::make($request->all(), [
            'step_assignments' => ['required', 'array'],
            'step_assignments.*' => ['array'],
            'step_assignments.*.*' => ['exists:users,id'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        foreach ($request->step_assignments as $orderStepId => $employeeIds) {
            $orderStep = OrderStep::findOrFail($orderStepId);
            $orderStep->employees()->sync($employeeIds);
        }

        return redirect()->route('admin.orders.show', $order)->with('success', 'Employees assigned successfully');
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && ($user->role === 'doctor' && $order->doctor_id !== $user->id)) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $validator = Validator::make($request->all(), [
            'status' => ['required', 'in:pending,in_progress,completed,cancelled'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $order->update([
            'status' => $request->status,
        ]);

        if ($user->role === 'doctor') {
            return redirect()->route('doctor.my-work')->with('success', 'Order status updated successfully');
        } else {
            return redirect()->route('admin.orders.index')->with('success', 'Order status updated successfully');
        }
    }
}
