<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreUploadRequest;
use App\Http\Requests\UpdateUploadRequest;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentFolderId = $request->query('folder');
        $currentFolder = null;
        $breadcrumbs = [];

        if ($currentFolderId) {
            $currentFolder = Upload::find($currentFolderId);
            if ($currentFolder && $currentFolder->user_id === auth()->id()) {
                // Build breadcrumbs starting from root
                $folder = $currentFolder;
                while ($folder) {
                    array_unshift($breadcrumbs, $folder);
                    $folder = $folder->parent;
                }
            } else {
                $currentFolderId = null;
                $currentFolder = null;
            }
        }

        $uploads = Upload::where('user_id', auth()->id())->get();

        return Inertia::render('Uploads', [
            'uploads' => $uploads,
            'currentFolder' => $currentFolder,
            'breadcrumbs' => $breadcrumbs,
        ]);
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
    public function store(StoreUploadRequest $request)
    {
        if ($request->type === 'file' && $request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('uploads', 'public');

            $upload = Upload::create([
                'name' => $request->name,
                'type' => 'file',
                'path' => $path,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'user_id' => auth()->id(),
                'parent_id' => $request->parent_id,
            ]);
        } else {
            $upload = Upload::create([
                'name' => $request->name,
                'type' => 'folder',
                'user_id' => auth()->id(),
                'parent_id' => $request->parent_id,
            ]);
        }

        return redirect()->route('upload.index')->with('success', 'created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Upload $upload)
    {
        // Ensure user owns the upload
        if ($upload->user_id !== auth()->id()) {
            abort(403);
        }

        if ($upload->type === 'file' && $upload->path) {
            // Check if file can be previewed
            $previewableTypes = [
                'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
                'application/pdf',
                'text/plain', 'text/html', 'text/css', 'text/javascript', 'text/markdown',
                'application/json', 'application/xml', 'text/xml', 'application/x-yaml',
                'video/mp4', 'video/webm', 'video/ogg',
                'audio/mpeg', 'audio/wav', 'audio/ogg'
            ];

            if (in_array($upload->mime_type, $previewableTypes)) {
                return Inertia::render('FilePreview', [
                    'upload' => $upload,
                    'fileUrl' => Storage::disk('public')->url($upload->path)
                ]);
            } else {
                // Download for non-previewable files
                return Storage::disk('public')->download($upload->path, $upload->name);
            }
        }

        return redirect()->back()->with('error', 'File not found or cannot be viewed.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Upload $upload)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUploadRequest $request, Upload $upload)
    {
        // Ensure user owns the upload
        if ($upload->user_id !== auth()->id()) {
            abort(403);
        }

        $upload->update($request->validated());

        return redirect()->back()->with('success', 'Upload updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Upload $upload)
    {
        // Ensure user owns the upload
        if ($upload->user_id !== auth()->id()) {
            abort(403);
        }

        // If it's a folder, delete all children recursively
        if ($upload->type === 'folder') {
            $this->deleteFolderRecursively($upload);
        } else {
            // Delete the file from storage
            if ($upload->path && Storage::disk('public')->exists($upload->path)) {
                Storage::disk('public')->delete($upload->path);
            }
        }

        $upload->delete();

        return redirect()->back()->with('success', 'Upload deleted successfully!');
    }

    private function deleteFolderRecursively(Upload $folder)
    {
        foreach ($folder->children as $child) {
            if ($child->type === 'folder') {
                $this->deleteFolderRecursively($child);
            } else {
                // Delete the file from storage
                if ($child->path && Storage::disk('public')->exists($child->path)) {
                    Storage::disk('public')->delete($child->path);
                }
            }
            $child->delete();
        }
    }
}
