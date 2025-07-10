<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Marketing;
use App\Models\Penyedia_sampling;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidationValidator;

class QuotationController extends Controller
{
    public function index() {


        $data = quotation::with(['marketing', 'penyedia_sampling']) 
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'List of sampling documents',
            'data quotation' => $data
        ], 200);
    }

    public function store(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'tgl_acc' => 'required|max:255',
            'ket_acc' => 'required|max:255',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add new document',
                'data_quotation' => $validator->errors()
            ], 401); 
        }

        // nanti ambil document_id dari url
        // user id ngambil dari $request->user()->id

        $user = Auth::user();

        $document = Document::where('user_id', $user->id)->first();
        if (!$document) {
            return response()->json(['message' => 'document not found for this user'], 404);
        }

        $marketing = marketing::where('document_id', $document->id)->first();
        if (!$marketing) {
            return response()->json(['message' => 'document not found for this user'], 404);
        }

        $sampling = penyedia_sampling::where('marketing_id', $marketing->id)->first();

        if (!$sampling) {
            return response()->json(['message' => 'document not found for this user'], 404);
        }

        $validatedData = $validator->validated();
        $validatedData["user_id"] = $request->user()->id;
        $validatedData["marketing_id"] = $marketing->id;
        $validatedData["sampling_id"] = $sampling->id;

        $createdData = quotation::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Success to add new document',
            'data_sampling' => $createdData
        ], 200);
    }

    public function update(Request $request, $id) {
        $quotation = quotation::find($id);
        if (!$quotation) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'tgl_acc' => 'required|max:255',
            'ket_acc' => 'required|max:255',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add new document',
                'data_quotation' => $validator->errors()
            ], 401); 
        }

        $validatedData = $validator->validated();

        $quotation->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Document updated successfully',
            'data_quotation' => $quotation
        ], 200);

    }

    public function destroy($id) {
        $quotation = quotation::find($id);

        if (!$quotation) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        $quotation->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Data deleted successfully'
        ], 200);
    }
}
