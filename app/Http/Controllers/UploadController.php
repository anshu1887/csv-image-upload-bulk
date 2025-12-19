<?php

namespace App\Http\Controllers;

use App\Models\UploadSession;
use App\Models\UploadChunk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{

    /**
     * Start a new upload session
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
     * Upload single chunk (idempotent, resume-safe)
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

        $path = $request->file('chunk')->store('chunks');

        UploadChunk::firstOrCreate(
            [
                'upload_session_id' => $session->id,
                'chunk_index' => $request->chunk_index,
            ],
            [
                'chunk_size' => $request->file('chunk')->getSize(),
                'path' => $path,
                'checksum' => $request->checksum,
            ]
        );

        $session->incrementChunks();

        return response()->json([
            'message' => 'Chunk uploaded',
        ]);
    }

    /**
     * Resume support – get uploaded chunks
     */
    public function status(Request $request)
    {
        $request->validate([
            'session_uuid' => 'required|uuid',
        ]);

        $session = UploadSession::where('session_uuid', $request->session_uuid)
            ->with('chunks')
            ->firstOrFail();

        return response()->json([
            'uploaded_chunks' => $session->chunks->pluck('chunk_index'),
            'total_chunks' => $session->total_chunks,
            'status' => $session->status,
        ]);
    }

    /**
     * Merge all chunks into final file
     */
    public function merge(Request $request)
    {
        $request->validate([
            'session_uuid' => 'required|uuid',
        ]);

        $session = UploadSession::where('session_uuid', $request->session_uuid)
            ->with('chunks')
            ->firstOrFail();

        $finalPath = 'uploads/' . $session->original_filename;
        $finalFile = Storage::path($finalPath);

        file_put_contents($finalFile, '');

        foreach ($session->chunks()->orderBy('chunk_index')->get() as $chunk) {
            file_put_contents(
                $finalFile,
                file_get_contents(Storage::path($chunk->path)),
                FILE_APPEND
            );
        }

        $session->update(['status' => 'completed']);

        return response()->json([
            'message' => 'File merged successfully',
            'path' => $finalPath,
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
