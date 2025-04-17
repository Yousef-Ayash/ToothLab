<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;
use Illuminate\Support\Facades\Validator;

class ColorController extends Controller
{
    /**
     * Display a listing of the colors.
     */
    public function index()
    {
        $colors = Color::all();
        return view('admin.colors.index', compact('colors'));
    }

    /**
     * Show the form for creating a new color.
     */
    public function create()
    {
        return view('admin.colors.create');
    }

    /**
     * Store a newly created color in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:colors'],
            'code' => ['required', 'string', 'max:7'], // Hex color code
            'description' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Color::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.colors.index')->with('success', 'Color created successfully');
    }

    /**
     * Display the specified color.
     */
    public function show(Color $color)
    {
        return view('admin.colors.show', compact('color'));
    }

    /**
     * Show the form for editing the specified color.
     */
    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    /**
     * Update the specified color in storage.
     */
    public function update(Request $request, Color $color)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:colors,name,' . $color->id],
            'code' => ['required', 'string', 'max:7'], // Hex color code
            'description' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $color->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.colors.index')->with('success', 'Color updated successfully');
    }

    /**
     * Remove the specified color from storage.
     */
    public function destroy(Color $color)
    {
        // Check if color is used in any procedures
        if ($color->procedures()->count() > 0) {
            return back()->with('error', 'Cannot delete color that is used in procedures');
        }

        $color->delete();

        return redirect()->route('admin.colors.index')->with('success', 'Color deleted successfully');
    }
}
