<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\File;
use App\Http\Resources\File as FileResource;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function __construct() {
        $this->middleware(['auth:api']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $files = File::orderBy('created_at', 'desc')->paginate(5);
        return FileResource::collection($files);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'archive' => 'required|file',
        ]);

        //TODO: test this
        if (!$request->hasFile('archive')) {
            return false;
        }

        $path = $request->archive->store('files');
        
        /***Store the file info in the database */
        $file = new File;

        $file->id = $request->input('file_id');
        $file->name = $request->input('name');
        $file->type = $request->input('type');
        $file->size = $request->input('size');
        $file->user_id = $request->input('user_id');
        $file->generated_name = $path;

        if($file->save()) {
            return new FileResource($file);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $file = File::findOrFail($id);

        return Storage::download($file->generated_name);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $file = File::findOrFail($request->file_id);

        $file->id = $request->input('file_id');
        $file->name = $request->input('name');

        if($file->save()) {
            return new FileResource($file);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file = File::findOrFail($id);
        if($file->delete()) {
            return new FileResource($file);
        }
    }
}