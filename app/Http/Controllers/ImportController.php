<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportJob;
use App\Jobs\ProcessCsvImportJob;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:10240']
        ]);

        $path = $request->file('csv_file')->store('imports');

        // Create import job record
        $importJob = ImportJob::create([
            'user_id' => Auth::id(),
            'file_path' => $path,
            'status' => 'pending',
        ]);

        // Dispatch background job
        ProcessCsvImportJob::dispatch($importJob);

        return response()->json([
            'message' => 'CSV import started',
            'job_id' => $importJob->id,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
