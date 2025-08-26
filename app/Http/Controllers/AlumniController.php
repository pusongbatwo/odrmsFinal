<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Alumni;

class AlumniController extends Controller
{
    /**
     * Display a listing of the alumni.
     */
    public function index()
    {
        $alumni = Alumni::all();
        return response()->json($alumni);
    }
    /**
     * Store a newly created alumni in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'alumni_id' => 'required|string|max:50|unique:alumni,alumni_id',
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'year_graduated' => 'required|string|max:10',
        ]);
        $alumni = Alumni::create($validated);
        return response()->json($alumni, 201);
    }

    // Add other resource methods (index, show, update, destroy) as needed
    // File deleted as requested
