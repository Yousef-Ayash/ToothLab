<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Procedure;
use App\Models\Step;
use App\Models\Color;
use Illuminate\Support\Facades\Validator;

class ProcedureController extends Controller
{
    /**
     * Display a listing of the procedures.
     */
    public function index()
    {
        $procedures = Procedure::all();
        return view('admin.procedures.index', compact('procedures'));
    }

    /**
     * Show the form for creating a new procedure.
     */
    public function create()
    {
        $colors = Color::all();
        return view('admin.procedures.create', compact('colors'));
    }

    /**
     * Store a newly created procedure in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['required', 'string', 'max:255'],
            'color_ids' => ['nullable', 'array'],
            'color_ids.*' => ['exists:colors,id'],
            'steps' => ['required', 'array', 'min:1'],
            'steps.*.name' => ['required', 'string', 'max:255'],
            'steps.*.description' => ['required', 'string'],
            'steps.*.duration' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // dd($request);

        // Create procedure
        $procedure = Procedure::create([
            'name' => $request->name,
            'description' => $request->description,
            'cost' => $request->price,
            'color_id' => $request->color_ids[0],
            'category' => $request->category,
        ]);

        // dd($procedure->colors()->attach($request->color_ids[0]));

        // Attach colors if provided
        if ($request->has('color_ids')) {
            $procedure->colors()->attach($request->color_ids[0]);
        }

        // Create steps
        foreach ($request->steps as $stepData) {
            $step = new Step([
                'name' => $stepData['name'],
                'description' => $stepData['description'],
                'duration' => $stepData['duration'],
                'order' => $stepData['order'] ?? 0,
            ]);
            $procedure->steps()->save($step);
        }

        return redirect()->route('admin.procedures.index')->with('success', 'Procedure created successfully');
    }

    /**
     * Display the specified procedure.
     */
    public function show(Procedure $procedure)
    {
        $procedure->load('steps', 'colors');
        return view('admin.procedures.show', compact('procedure'));
    }

    /**
     * Show the form for editing the specified procedure.
     */
    public function edit(Procedure $procedure)
    {
        $procedure->load('steps', 'colors');
        $colors = Color::all();
        return view('admin.procedures.edit', compact('procedure', 'colors'));
    }

    /**
     * Update the specified procedure in storage.
     */
    public function update(Request $request, Procedure $procedure)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['required', 'string', 'max:255'],
            'color_ids' => ['nullable', 'array'],
            'color_ids.*' => ['exists:colors,id'],
            'steps' => ['required', 'array', 'min:1'],
            'steps.*.name' => ['required', 'string', 'max:255'],
            'steps.*.description' => ['required', 'string'],
            'steps.*.duration' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update procedure
        $procedure->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
        ]);

        // Sync colors
        $procedure->colors()->sync($request->color_ids ?? []);

        // Update steps
        $procedure->steps()->delete(); // Remove existing steps
        foreach ($request->steps as $stepData) {
            $step = new Step([
                'name' => $stepData['name'],
                'description' => $stepData['description'],
                'duration' => $stepData['duration'],
                'order' => $stepData['order'] ?? 0,
            ]);
            $procedure->steps()->save($step);
        }

        return redirect()->route('admin.procedures.index')->with('success', 'Procedure updated successfully');
    }

    /**
     * Remove the specified procedure from storage.
     */
    public function destroy(Procedure $procedure)
    {
        // Check if procedure is used in any orders
        if ($procedure->orders()->count() > 0) {
            return back()->with('error', 'Cannot delete procedure that is used in orders');
        }

        // Delete steps
        $procedure->steps()->delete();

        // Detach colors
        $procedure->colors()->detach();

        // Delete procedure
        $procedure->delete();

        return redirect()->route('admin.procedures.index')->with('success', 'Procedure deleted successfully');
    }
}
