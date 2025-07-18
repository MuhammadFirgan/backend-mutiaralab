<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Koor_teknis;
use App\Models\Marketing;
use Carbon\Carbon;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KoorTeknisController extends Controller
{
    public function index() {
        $document = Koor_teknis::with('marketing')->get();

        return response()->json([
            'success' => true,
            'message' => 'Documents fetched successfully',
            'data_koor_teknis' => $document
        ], 200);
    }

    public function store(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            // 'document_id' => 'required|max:255',
            'tgl_masuk' => 'required|max:255',
            'status' => 'required|in:accept koor teknis,decline koor teknis',
            'document_path' => 'required|file|max:5024|mimes:jpg,png,jpeg,pdf'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add new document',
                'data_koor_teknis' => $validator->errors()
            ], 401); 
        }

        // $marketing = marketing::with('document')->where('document_id', );

        // $user = Auth::user();
        // $id = $user->id;
        
        // $document = Document::where('user_id', $id)->first();
        // @dd($document);
        // if (!$document) {
        //     return response()->json(['message' => 'document not found for this user'], 404);
        // }
        // $marketing = Marketing::where('document_id', $document->id)->first();
        // if (!$marketing) {
        //     return response()->json(['message' => 'marketing not found for this user'], 404);
        // }
        
        // $validatedData["marketing_id"] = $marketing->id;
        
        
        $file = $request->file('document_path');
        $validatedData = $validator->validated();
       

        $originalName = $file->getClientOriginalName();
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        $storedName = 'FILE_'. time() . '_' . uniqid() . '.' . $extension;

        $path = $file->storeAs('uploads', $storedName, 'public');

        $url = Storage::url($path);

        $createdData = Koor_teknis::create([
            'user_id' => $request->user()->id,
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
        $document = Koor_teknis::with('marketing')
            ->where('user_id', $user_id)
            ->where('marketing_id', $document_id)
            ->first();

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => "Document not found"
            ], 404);
        }


        $validator = Validator::make($request->all(), [
            'tgl_masuk' => 'required|max:255',
            'status' => ['required', Rule::in(['accept koor teknis', 'decline koor teknis'])],
            'document_path' => 'required|file|max:5024|mimes:jpg,png,jpeg,pdf'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update document',
                'data_koor_teknis' => $validator->errors()
            ], 401); 
        }

        $validatedData = $validator->validated();
       
        

        if ($request->hasFile('document_path')) {
            $file = $request->file('document_path');
            $extension = $file->getClientOriginalExtension();
            $storedName = 'FILE_' . time() . '_' . uniqid() . '.' . $extension;
            $path = $file->storeAs('uploads', $storedName, 'public');
            $url = Storage::url($path);
            $validatedData['document_path'] = $url;
        }

        $document->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Document updated successfully',
            'data_koor_teknis' => $document
        ], 200);


    }

    public function destroy($user_id, $document_id) {
        $document = Koor_teknis::with('marketing')
            ->where('user_id', $user_id)
            ->where('marketing_id', $document_id)
            ->first();

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => "Document not found"
            ], 404);
        }

        if ($document->image_path) {
            $path = str_replace('/storage/', '', $document->document_path);
            Storage::disk('public')->delete($path);
        }

        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully'
        ], 200);
    }
}
