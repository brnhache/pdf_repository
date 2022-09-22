<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    /**
     * Display a listing of the user's documents, ordered by latest updated.
     *
     * @return App\Models\Document
     */
    public function index()
    {
        try {
            return Document::whereBelongsTo(Auth::user())->latest('updated_at')->get();
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a document {currently only allows PDF}.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return App\Models\Document
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:pdf|max:2048',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $path = Storage::putFile('pdfs', $request->file);
            $doc = new Document();
            $doc->user_id = Auth::user()->id;
            $doc->name = $request->name;
            $doc->path = $path;
            $doc->save();
            return $doc;
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified pdf.
     *
     * @param  int  $id
     * @return App\Models\Document
     */
    public function show($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer'
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            return Document::whereBelongsTo(Auth::user())->find($id);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
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
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer'
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            $document = Document::find($id);
            if ($document == NULL || !$document->user->is(Auth::user())) {
                return response()->json(['message' => 'document not found.'], 404);
            }
            if ($document->delete() && Storage::delete($document->path)) {
                return response()->json(['message' => 'document deleted.']);
            }
            // return response()->json(['message' => 'document not found.'], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
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
        //
    }
}
