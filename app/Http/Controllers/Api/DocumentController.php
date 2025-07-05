<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    public function index($id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $documents = $user->documents;

        return response()->json([
            'success' => true,
            'message' => 'Documents for user fetched successfully',
            'data_document' => $documents
        ]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'doc_name' => 'required|max:255',
            'doc_date' => 'required|max:255',
            'doc_number' => 'required|max:255',
            'doc_desc' => 'required|max:255',
            'image_path' => 'required|file|max:5024|mimes:jpg,jpeg,png',
            'doc_year' => 'required|max:255',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add new document',
                'data' => $validator->errors()
            ], 401); 
        }

        $file = $request->file('image_path');
        $validatedData = $validator->validated();


        $originalName = $file->getClientOriginalName();
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        $storedName = 'IMG_'. time() . '_' . uniqid() . '.' . $extension;


        $path = $file->storeAs('uploads', $storedName, 'public');

        $url = Storage::url($path);
        $docDateObj = Carbon::createFromFormat('d-m-Y', $validatedData['doc_date']);
        $docDate = $docDateObj->format('Y-m-d');

        $docYear = $docDateObj->format('Y');


        $createdData = Document::create([
            'user_id' => $request->user()->id,
            'doc_name' => $validatedData["doc_name"],
            'doc_date' => $docDate,
            'doc_number' => $validatedData["doc_number"],
            'doc_desc' => $validatedData["doc_desc"],
            'image_path' => $url,
            'doc_year' => $docYear,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Success to add new document',
            'data' => $createdData
        ], 200);
    }


}
