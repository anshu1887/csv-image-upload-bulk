<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UploadChunk;
use App\Models\UploadSession;

class UploadController extends Controller
{

    /**
     * Start upload session
     */
    public function start(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
            'total_size' => 'required|integer',
            'total_chunks' => 'required|integer',
        ]);

        $session = UploadSession::create([
            'user_id' => Auth::id(),
            'original_filename' => $request->filename,
            'total_size' => $request->total_size,
            'total_chunks' => $request->total_chunks,
            'status' => 'uploading',
        ]);

        return response()->json([
            'session_uuid' => $session->session_uuid,
        ]);
    }

    /**
     * Upload a single chunk
     */
    public function uploadChunk(Request $request)
    {
        $request->validate([
            'session_uuid' => 'required|uuid',
            'chunk_index' => 'required|integer',
            'chunk' => 'required|file',
            'checksum' => 'required|string',
        ]);

        $session = UploadSession::where('session_uuid', $request->session_uuid)
            ->firstOrFail();

        // Store chunk
        $path = $request->file('chunk')->store('chunks');

        UploadChunk::create([
            'upload_session_id' => $session->id,
            'chunk_index' => $request->chunk_index,
            'chunk_size' => $request->file('chunk')->getSize(),
            'path' => $path,
            'checksum' => $request->checksum,
        ]);

        $session->incrementChunks();

        return response()->json([
            'uploaded_chunks' => $session->uploaded_chunks,
            'total_chunks' => $session->total_chunks,
        ]);
    }

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
        //
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
