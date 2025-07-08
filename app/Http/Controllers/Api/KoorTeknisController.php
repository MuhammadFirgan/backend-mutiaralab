<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\koor_teknis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KoorTeknisController extends Controller
{
    public function index($id) {
        $document = koor_teknis::with('marketing')->where('marketing_id', $id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Documents fetched successfully',
            'data_koor_teknis' => $document
        ], 200);
    }

    public function store(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'tgl_masuk' => 'required|max:255',
            'status' => 'required|in:accept koor teknis, decline koor teknis',
            'document_path' => 'required|file|max:5024|mimes:jpg,png,jpeg'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add new document',
                'data_koor_teknis' => $validator->errors()
            ], 401); 
        }

        $file = $request->file('document_path');
        $validatedData = $validator->validated();

        $originalName = $file->getClientOriginalName();
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        $storedName = 'IMG_'. time() . '_' . uniqid() . '.' . $extension;

        $path = $file->storeAs('uploads', $storedName, 'public');

        $url = Storage::url($path);

        $createdData = koor_teknis::create([
            'marketing_id' => $id,
            'tgl_masuk' => $validatedData["tgl_masuk"],
            'status' => $validatedData["status"],
            'document_path' => $url
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Success to add new document',
            'data_koor_teknis' => $createdData
        ], 200);

    }

    public function update(Request $request, $user_id, $document_id) {
        
    }

    public function destroy() {

    }
}
