<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Upload;
use App\Http\Requests\StoreUploadRequest;
use App\Http\Requests\UpdateUploadRequest;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Uploads');
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
        // if ($request->type === 'file' && $request->hasFile('file')) {
        //     $file = $request->file('file');
        //     $path = $file->store('uploads');

        //     $upload = Upload::create([
        //         'name' => $request->name,
        //         'type' => 'file',
        //         'path' => $path,
        //         'size' => $file->getSize(),
        //         'mime_type' => $file->getMimeType(),
        //         'user_id' => auth()->id(),
        //         'parent_id' => $request->parent_id,
        //     ]);
        // } else {
        //     $upload = Upload::create([
        //         'name' => $request->name,
        //         'type' => 'folder',
        //         'user_id' => auth()->id(),
        //         'parent_id' => $request->parent_id,
        //     ]);
        // }

        dd($request->all());
        // return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Upload $upload)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Upload $upload)
    {
        //
    }
}
