<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    public function index() {
        // $document = Document::with('user')->where('user_id', $id)->get();
        $document = Document::with('user')->get();

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }


        return response()->json([
            'success' => true,
            'message' => 'Documents for user fetched successfully',
            'data_document' => $document
        ]);
    }

    public function getDocumentByUser($id) {
        $document = Document::with('user')->where('user_id', $id)->get();
        

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }


        return response()->json([
            'success' => true,
            'message' => 'Documents for user fetched successfully',
            'data_document' => $document
        ]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'doc_name' => 'required|max:255',
            'doc_date' => 'required|max:255',
            'doc_number' => 'required|max:255',
            'doc_desc' => 'required|max:255',
            'image_path' => 'required|file|max:5024|mimes:jpg,jpeg,png,pdf',
            'doc_year' => 'required|max:255',
        ]);

       

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add new document',
                'data_customer' => $validator->errors()
            ], 401); 
        }

        $file = $request->file('image_path');
        $validatedData = $validator->validated();
       

        $originalName = $file->getClientOriginalName();
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        $storedName = 'FILE_'. time() . '_' . uniqid() . '.' . $extension;


        $path = $file->storeAs('uploads', $storedName, 'public');

        $url = Storage::url($path);
        

        $createdData = Document::create([
            'user_id' => $request->user()->id,
            'doc_name' => $validatedData["doc_name"],
            'doc_date' => $validatedData['doc_date'],
            'doc_number' => $validatedData["doc_number"],
            'doc_desc' => $validatedData["doc_desc"],
            'image_path' => $url,
            'doc_year' => $validatedData['doc_year'],
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Success to add new document',
            'data' => $createdData
        ], 200);
    }

    public function show($user_id, $year) {
        $documents = Document::with('user')
            ->where('user_id', $user_id)
            ->where('doc_year', $year)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($documents->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => "document not found in $year"
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => "Documents for user in $year",
            'albums' => $documents
        ], 200);
    }

    public function update(Request $request, $user_id, $document_id) {
        // laravel dak bisa nerima method put atau patch
        // jadinya di body ditambahkan _method="PUT"
        $document = Document::with('user')
            ->where('user_id', $user_id)
            ->where('id', $document_id)
            ->first();
        
        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => "Document not found"
            ], 404);
        }


        $validator = Validator::make($request->all(), [
            'doc_name' => 'required|max:255',
            'doc_date' => 'required|max:255',
            'doc_number' => 'required|max:255',
            'doc_desc' => 'required|max:255',
            'image_path' => 'required|file|max:5024|mimes:jpg,jpeg,png,pdf',
            'doc_year' => 'required|max:255',
            
        ]);


        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add new document',
                'data_customer' => $validator->errors()
            ], 401); 
        }

        // $file = $request->file('image_path');
        $validatedData = $validator->validated();
       
        

        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');
            $extension = $file->getClientOriginalExtension();
            $storedName = 'FILE_' . time() . '_' . uniqid() . '.' . $extension;
            $path = $file->storeAs('uploads', $storedName, 'public');
            $url = Storage::url($path);
            $validatedData['image_path'] = $url;
        }


        $document->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Document updated successfully',
            'data_customer' => $document
        ], 200);


    }

    public function destroy($user_id, $document_id) {
        $document = Document::with('user')
            ->where('user_id', $user_id)
            ->where('id', $document_id)
            ->first();

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => "Document not found"
            ], 404);
        }

        if ($document->image_path) {
            $path = str_replace('/storage/', '', $document->image_path);
            Storage::disk('public')->delete($path);
        }

        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully'
        ], 200);
        
    }

    public function getAlbum($user_id) {
        $albums = Document::where('user_id', $user_id)
        ->select('doc_year as year')
        ->selectRaw('COUNT(*) as total_document')
        ->groupBy('doc_year')
        ->orderByDesc('doc_year')
        ->get()
        ->map(function ($item) {
        
            return [
                'year' => (int) $item->year,
                'total_document' => $item->total_document . ' document'
            ];
        });

        if ($albums->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => "No document albums found for user $user_id.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'document fetched successfully',
            'data_album' => $albums
        ], 200);
    }

    // public function getAlbum($user_id) {
    //     return response()->json(['user_id' => $user_id]);
    //     @dd($user_id);
    //     $albums = Document::where('user_id', $user_id)
    //         ->selectRaw('doc_year as year, COUNT(*) as total_document')
    //         ->groupBy('doc_year')
    //         ->orderByDesc('doc_year')
    //         ->get()
    //         ->map(function ($item) {
    //             return [
    //                 'year' => (int) $item->year,
    //                 'total_document' => $item->total_document . ' document'
    //             ];
    //         });
    
    //     if ($albums->isEmpty()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => "No document albums found for user $user_id.",
    //         ], 404);
    //     }
    
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'okee',
    //         'data_album' => $albums
    //     ], 200);
    // }
    
    


}
