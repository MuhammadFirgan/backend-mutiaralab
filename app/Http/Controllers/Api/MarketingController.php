<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\marketing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MarketingController extends Controller
{
    public function index($id) {
        $document = marketing::with("document")->where('document_id', $id)->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Documents fetched successfully',
            'data_marketing' => $document
        ], 200);
    }

    public function store(Request $request, $id) {


        $validator = Validator::make($request->all(), [
            'tgl_kajian' => 'required|max:255',
            'status' => 'required|in:accept marketing, decline marketing',
            'ket_kajian' => 'required|max:255',
            'document_path' => 'required|file|max:5024|mimes:jpg,jpeg,png'      
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add new document',
                'data' => $validator->errors()
            ], 401); 
        }


        $file = $request->file('document_path');
        $validatedData = $validator->validated();

        $originalName = $file->getClientOriginalName();
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        $storedName = 'IMG_'. time() . '_' . uniqid() . '.' . $extension;


        $path = $file->storeAs('uploads', $storedName, 'public');

        $url = Storage::url($path);
        $docDateObj = Carbon::createFromFormat('d-m-Y', $validatedData['tgl_kajian']);
        $docDate = $docDateObj->format('Y-m-d');

        $createdData = marketing::create([
            'user_id' => $request->user()->id,
            'document_id' => $id,
            'tgl_kajian' => $docDate,
            'status' => $validatedData["status"],
            'ket_kajian' => $validatedData["ket_kajian"],
            'document_path' => $url
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Success to add new document',
            'data_marketing' => $createdData
        ], 200);
    }

    public function update(Request $request, $user_id, $document_id) {
        $document = marketing::with('document')
            ->where('user_id', $user_id)
            ->where('document_id', $document_id)
            ->first();

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => "Document not found"
            ], 404);
        }

      

        $validator = Validator::make($request->all(), [
            'tgl_kajian' => 'required|max:255',
            'status' => ['required', Rule::in(['accept marketing', 'decline marketing'])],
            'ket_kajian' => 'required|max:255',
            'document_path' => 'required|file|max:5024|mimes:jpg,jpeg,png'      
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add new document',
                'data_marketing' => $validator->errors()
            ], 401); 
        }

        $validatedData = $validator->validated();
       



        $docDateObj = Carbon::createFromFormat('d-m-Y', $validatedData['tgl_kajian']);
        $validatedData['tgl_kajian'] = $docDateObj->format('Y-m-d');
        

        if ($request->hasFile('document_path')) {
            $file = $request->file('document_path');
            $extension = $file->getClientOriginalExtension();
            $storedName = 'IMG_' . time() . '_' . uniqid() . '.' . $extension;
            $path = $file->storeAs('uploads', $storedName, 'public');
            $url = Storage::url($path);
            $validatedData['document_path'] = $url;
        }

        $document->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Document updated successfully',
            'data_marketing' => $document
        ], 200);



    }

    public function destroy($user_id, $document_id) {
        $document = marketing::with('document')
            ->where('user_id', $user_id)
            ->where('document_id', $document_id)
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
