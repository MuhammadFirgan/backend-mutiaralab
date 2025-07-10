<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Koor_teknis;
use App\Models\Marketing;
use App\Models\Penyedia_sampling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PenyediaSamplingController extends Controller
{
    public function index() {
        // $user = Auth::user();

        // $data = penyedia_sampling::where('user_id', $user->id)
        //     ->with(['document', 'marketing', 'koor_teknis']) 
        //     ->get();
        $data = Penyedia_sampling::with(['document', 'marketing', 'koor_teknis'])->get();
        

        return response()->json([
            'success' => true,
            'message' => 'List of sampling documents',
            'data sampling' => $data
        ], 200);
    }

    public function store(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'tgl_survey' => 'required|max:255',
            'status' => 'required|in:accept sampling, decline sampling',
            'document_path' => 'required|file|max:5024|mimes:jpg,png,jpeg'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add new document',
                'data_penyelia_sampling' => $validator->errors()
            ], 401); 
        }

        // $user = Auth::user();
        // $id = $user->id;
        // $document = Document::where('user_id', $id)->first();
        // if (!$document) {
        //     return response()->json(['message' => 'document not found for this user'], 404);
        // }
        // $marketing = Marketing::where('document_id', $document->id)->first();
        // if (!$marketing) {
        //     return response()->json(['message' => 'document not found for this user'], 404);
        // }
        // $koorTeknis = Koor_teknis::where('marketing_id', $marketing->id)->first();
        // if (!$koorTeknis) {
        //     return response()->json(['message' => 'document not found for this user'], 404);
        // }

        $file = $request->file('document_path');
        $validatedData = $validator->validated();
        $validatedData["user_id"] = $request->user()->id;
        $validatedData["marketing_id"] = $id;

        $originalName = $file->getClientOriginalName();
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        $storedName = 'IMG_'. time() . '_' . uniqid() . '.' . $extension;

        $path = $file->storeAs('uploads', $storedName, 'public');

        $url = Storage::url($path);
        $validatedData["document_path"] = $url;

        $createdData = Penyedia_sampling::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Success to add new document',
            'data_sampling' => $createdData
        ], 200);

    }

    public function update(Request $request, $id) {
        $sampling = Penyedia_sampling::find($id);
        if (!$sampling) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found'
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'tgl_survey' => 'required|max:255',
            'status' => ['required', Rule::in('accept sampling', 'decline sampling')],
            'document_path' => 'required|file|max:5024|mimes:jpg,png,jpeg'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update document',
                'data_penyelia_sampling' => $validator->errors()
            ], 401); 
        }

        $validatedData = $validator->validated();

        if ($request->hasFile('document_path')) {
            $file = $request->file('document_path');
            $originalName = $file->getClientOriginalName();
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $storedName = 'IMG_' . time() . '_' . uniqid() . '.' . $extension;
            $path = $file->storeAs('uploads', $storedName, 'public');
            $url = Storage::url($path);
            $validatedData['document_path'] = $url;
        }

        $sampling->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Document updated successfully',
            'data_sampling' => $sampling
        ], 200);

    }

    public function destroy($id) {
        $sampling = Penyedia_sampling::find($id);

        if (!$sampling) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        if ($sampling->document_path && Storage::exists(str_replace('/storage/', '', $sampling->document_path))) {
            Storage::delete(str_replace('/storage/', '', $sampling->document_path));
        }
    
        $sampling->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Data deleted successfully'
        ], 200);
    }
}
